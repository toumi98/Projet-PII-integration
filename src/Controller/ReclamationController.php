<?php

namespace App\Controller;

use TCPDF;
use App\Entity\Reclamation;
use App\Form\Reclamation1Type;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\SmsService; 
use App\Repository\UserRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
   /* #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(Request $request, ReclamationRepository $reclamationRepository): Response
    {
        $page = max(1, $request->query->getInt('page', 1)); // page actuelle (1 par défaut)
        $limit = 5; // Limite d'éléments par page
        $offset = ($page - 1) * $limit; // Calcul de l'offset

        // Récupération des réclamations avec pagination
        $query = $reclamationRepository->createQueryBuilder('r')
            ->orderBy('r.dateCreation', 'DESC')
            ->setFirstResult($offset)  // Limite les résultats
            ->setMaxResults($limit)    // Limite à 5 résultats par page
            ->getQuery();

        // Utilisation du paginator de Doctrine
        $paginator = new Paginator($query);
        $totalItems = count($paginator);  // Nombre total de réclamations
        $totalPages = ceil($totalItems / $limit); // Calcul du nombre total de pages

        // Rendre la vue avec les réclamations paginées
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $paginator, // Passer le paginator aux vues
            'currentPage' => $page,  // Page actuelle
            'totalPages' => $totalPages, // Total des pages
        ]);
    }*/
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(Request $request, ReclamationRepository $reclamationRepository): Response
    {
        $page = max(1, $request->query->getInt('page', 1));
        $limit = 5;
        $offset = ($page - 1) * $limit;
    
        // Récupération des filtres
        $filterId = $request->query->get('filter_id');
        $filterDate = $request->query->get('filter_date');
        $filterStatus = $request->query->get('filter_status');
    
        $queryBuilder = $reclamationRepository->createQueryBuilder('r')
            ->orderBy('r.dateCreation', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);
    
        if ($filterId) {
            $queryBuilder->andWhere('r.id = :id')->setParameter('id', $filterId);
        }
    
        if ($filterDate) {
            $queryBuilder->andWhere('r.dateCreation BETWEEN :start AND :end')
            ->setParameter('start', new \DateTime($filterDate . ' 00:00:00'))
            ->setParameter('end', new \DateTime($filterDate . ' 23:59:59'));
        
        }
    
        if ($filterStatus) {
            $queryBuilder->andWhere('r.status = :status')->setParameter('status', $filterStatus);
        }
    
        $paginator = new Paginator($queryBuilder->getQuery());
        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / $limit);
    
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $paginator,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'filter_id' => $filterId,
            'filter_date' => $filterDate,
            'filter_status' => $filterStatus,
        ]);
    }
    


    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
  /* public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(Reclamation1Type::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
    
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
       $reclamation = new Reclamation();
    $form = $this->createForm(Reclamation1Type::class, $reclamation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Définir des valeurs par défaut pour les champs cachés
        $reclamation->setIsEtat(true);  // Valeur par défaut
        $reclamation->setStatus('En cours');  // Valeur par défaut
        $reclamation->setDateCreation(new \DateTime()); // Définir la date de création

        // Sauvegarder la réclamation
        $entityManager->persist($reclamation);
        $entityManager->flush();

        return $this->redirectToRoute('app_reclamation_index');
    }

    return $this->renderForm('reclamation/new.html.twig', [
        'form' => $form,
    ]);
}*/
public function new(Request $request, EntityManagerInterface $entityManager, SmsService $smsService): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(Reclamation1Type::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Définir des valeurs par défaut pour les champs cachés
            $reclamation->setIsEtat(true);  // Valeur par défaut
            $reclamation->setStatus('En cours');  // Valeur par défaut
            $reclamation->setDateCreation(new \DateTime()); // Définir la date de création

            // Sauvegarder la réclamation
            $entityManager->persist($reclamation);
            $entityManager->flush();

            // Envoyer un SMS via SmsService
            $smsService->sendSms(
                '+21621255046', // Remplacer par le numéro de téléphone du destinataire
                "Une nouvelle réclamation a été créée. Description: " . $reclamation->getDescription()
            );

            return $this->redirectToRoute('app_reclamation_index');
        }

        return $this->renderForm('reclamation/new.html.twig', [
            'form' => $form,
        ]);
    }
/*public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
{
    $reclamation = new Reclamation();
    $form = $this->createForm(Reclamation1Type::class, $reclamation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $reclamation->setIsEtat(true);
        $reclamation->setStatus('En cours');
        $reclamation->setDateCreation(new \DateTime());

        $entityManager->persist($reclamation);
        $entityManager->flush();

        // Récupérer l'email de l'utilisateur associé à la réclamation
        $user = $reclamation->getIdUser();
        $userEmail = $user ? $user->getEmail() : null;

        if ($userEmail) {
            // Création de l'email
            $email = (new Email())
                ->from('malek.maroufi123@gmail.com') // Remplace par ton email
                ->to('malek.maroufi123@gmail.com')
                ->subject('Nouvelle Réclamation Créée')
                ->text("Votre réclamation a été enregistrée avec succès. Statut: En cours.");

            // Envoi de l'email
            $mailer->send($email);
        }

        return $this->redirectToRoute('app_reclamation_index');
    }

    return $this->renderForm('reclamation/new.html.twig', [
        'form' => $form,
    ]);
}
#[Route('/{id}/respond', name: 'app_reclamation_respond', methods: ['GET', 'POST'])]
public function respond(Reclamation $reclamation, Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
{
    if ($reclamation->getStatus() == 'En cours') {
        $reclamation->setStatus('Traité');
    }

    $entityManager->flush();

    // Récupérer l'email de l'utilisateur associé à la réclamation
    $user = $reclamation->getIdUser();
    $userEmail = $user ? $user->getEmail() : null;

    if ($userEmail) {
        // Création de l'email
        $email = (new Email())
            ->from('malek.maroufi123@gmail.com')
            ->to('malek.maroufi123@gmail.com')
            ->subject('Réclamation Traité')
            ->text("Votre réclamation a été traitée. Statut: Traité.");

        // Envoi de l'email
        $mailer->send($email);
    }

    return $this->redirectToRoute('app_reclamation_index');
}*/

    // #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    // public function show(Reclamation $reclamation): Response
    // {
        // return $this->render('reclamation/show.html.twig', [
            // 'reclamation' => $reclamation,
        // ]);
    // }
    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
public function show(Reclamation $reclamation): Response
{
    // Vérifier si l'objet 'User' existe et récupérer le nom d'utilisateur
    $user = $reclamation->getIdUser();  // Récupère l'objet User associé à la réclamation
    $username = $user ? $user->getUsername() : 'Utilisateur inconnu';  // Utilisez getUsername() pour récupérer le nom d'utilisateur

    return $this->render('reclamation/show.html.twig', [
        'reclamation' => $reclamation,
        'username' => $username,  // Passez le nom d'utilisateur à la vue
    ]);
}


    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Reclamation1Type::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/respond', name: 'app_reclamation_respond', methods: ['GET', 'POST'])]
public function respond(Reclamation $reclamation, Request $request, EntityManagerInterface $entityManager): Response
{
    // Mettre à jour le statut de la réclamation
    if ($reclamation->getStatus() == 'En cours') {
        $reclamation->setStatus('Traité');  // Mettre à jour le statut à "Traité"
    }

    // Ajouter la logique pour répondre (par exemple, créer une réponse à la réclamation)
    // Par exemple, en ajoutant une réponse ou autre logique à la réclamation.

    $entityManager->flush();  // Sauvegarder les changements

    // Rediriger vers la liste des réclamations après avoir répondu
    return $this->redirectToRoute('app_reclamation_index');
}

#[Route('/export/pdf', name: 'app_reclamation_export_pdf')]
public function exportPdf(ReclamationRepository $reclamationRepository): Response
{
    $reclamations = $reclamationRepository->findAll(); // Récupérer toutes les réclamations

    // Créer une instance de TCPDF
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Votre Nom');
    $pdf->SetTitle('Liste des Réclamations');
    $pdf->SetSubject('Exportation des réclamations');
    $pdf->SetMargins(10, 10, 10);
    $pdf->AddPage();

    // Titre du document
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Liste des Réclamations', 0, 1, 'C');
    $pdf->Ln(5);

    // En-tête du tableau
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(20, 10, 'ID', 1);
    $pdf->Cell(80, 10, 'Description', 1);
    $pdf->Cell(30, 10, 'État', 1);
    $pdf->Cell(40, 10, 'Date de Création', 1);
    $pdf->Ln();

    // Contenu du tableau
    $pdf->SetFont('helvetica', '', 10);
    foreach ($reclamations as $reclamation) {
        $pdf->Cell(20, 10, $reclamation->getId(), 1);
        $pdf->Cell(80, 10, substr($reclamation->getDescription(), 0, 40) . '...', 1);
        $pdf->Cell(30, 10, $reclamation->isEtat() ? 'Oui' : 'Non', 1);
        $pdf->Cell(40, 10, $reclamation->getDateCreation()->format('Y-m-d H:i:s'), 1);
        $pdf->Ln();
    }

    // Générer le PDF
    $pdf->Output('liste_reclamations.pdf', 'D'); // 'D' pour téléchargement

    return new Response('', 200, ['Content-Type' => 'application/pdf']);
}

}
