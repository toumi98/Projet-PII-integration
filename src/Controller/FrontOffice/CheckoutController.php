<?php

namespace App\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Commande;
use App\Form\CommandeType;
use Doctrine\ORM\EntityManagerInterface;

final class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'app_front_office_checkout', methods: ['GET', 'POST'])]
    public function checkout(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commande a été enregistrée avec succès. Vous recevrez un email de confirmation sous peu.');

            return $this->redirectToRoute('app_front_office_cart'); // Vérifiez que cette route est correcte
        }

        return $this->render('front_office/checkout/checkout.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
