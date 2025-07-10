<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SupportCaseController extends AbstractController
{
    #[Route('/support/case', name: 'app_support_case')]
    public function index(): Response
    {
        return $this->render('support_case/index.html.twig', [
            'controller_name' => 'SupportCaseController',
        ]);
    }

    #[Route('/api/support-cases', name: 'api_support_cases_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        // Simulation d'appel au CRM pour récupérer les tickets/support
        $cases = [
            [
                'id' => 1,
                'subject' => 'Problème de connexion',
                'status' => 'Ouvert',
                'createdAt' => '2025-05-10',
            ],
            [
                'id' => 2,
                'subject' => 'Facture non reçue',
                'status' => 'Résolu',
                'createdAt' => '2025-05-12',
            ],
        ];
        return $this->json(['cases' => $cases]);
    }

    #[Route('/api/support-cases', name: 'api_support_cases_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        // Ici, simuler l'envoi au CRM
        if (!empty($data['subject'])) {
            return $this->json([
                'success' => true,
                'case' => [
                    'id' => rand(100, 999),
                    'subject' => $data['subject'],
                    'status' => 'Ouvert',
                    'createdAt' => date('Y-m-d'),
                ]
            ]);
        }
        return $this->json(['success' => false, 'error' => 'Sujet manquant'], 400);
    }
}
