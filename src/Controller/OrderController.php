<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function index(): Response
    {
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }

    #[Route('/api/orders', name: 'api_orders_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        // Simulation d'appel au CRM pour récupérer les commandes
        $orders = [
            [
                'id' => 1,
                'type' => 'SEO',
                'status' => 'En attente',
                'createdAt' => '2025-05-01',
            ],
            [
                'id' => 2,
                'type' => 'SEA',
                'status' => 'En cours',
                'createdAt' => '2025-05-10',
            ],
        ];
        return $this->json(['orders' => $orders]);
    }

    #[Route('/api/orders', name: 'api_orders_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        // Ici, simuler l'envoi au CRM
        if (!empty($data['type'])) {
            return $this->json([
                'success' => true,
                'order' => [
                    'id' => rand(100, 999),
                    'type' => $data['type'],
                    'status' => 'En attente',
                    'createdAt' => date('Y-m-d'),
                ]
            ]);
        }
        return $this->json(['success' => false, 'error' => 'Type de commande manquant'], 400);
    }
}
