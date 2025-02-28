<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StatisticsController extends AbstractController
{
    #[Route('/statistics', name: 'app_statistics')]
    public function index(UserRepository $userRepository): Response
    {
        // Récupérer le nombre total d'utilisateurs
        $userCount = $userRepository->count([]);

        return $this->render('statistics/index.html.twig', [
            'controller_name' => 'StatisticsController',
            'user_count' => $userCount,
        ]);
    }
}
