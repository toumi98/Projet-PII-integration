<?php

namespace App\Controller\FrontOffice;

use App\Entity\Commande;
use App\Form\CommandeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'app_front_office_checkout')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('checkout_success'); // Redirection après succès
        }

        return $this->render('front_office/checkout/checkout.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/checkout/success', name: 'checkout_success')]
    public function success(): Response
    {
        return $this->render('front_office/checkout/success.html.twig');
    }
}
