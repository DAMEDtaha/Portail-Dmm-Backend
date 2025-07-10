<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class InvoiceController extends AbstractController
{
    #[Route('/invoice', name: 'app_invoice')]
    public function index(): Response
    {
        return $this->render('invoice/index.html.twig', [
            'controller_name' => 'InvoiceController',
        ]);
    }

    #[Route('/api/invoices', name: 'api_invoices_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        // Simulation d'appel au CRM pour récupérer les factures
        $invoices = [
            [
                'id' => 1,
                'amount' => 1200.00,
                'status' => 'Payée',
                'issuedAt' => '2025-04-15',
            ],
            [
                'id' => 2,
                'amount' => 800.00,
                'status' => 'En attente',
                'issuedAt' => '2025-05-10',
            ],
        ];
        return $this->json(['invoices' => $invoices]);
    }

    #[Route('/api/invoices', name: 'api_invoices_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        // Ici, simuler l'envoi au CRM
        if (!empty($data['amount'])) {
            return $this->json([
                'success' => true,
                'invoice' => [
                    'id' => rand(100, 999),
                    'amount' => $data['amount'],
                    'status' => 'En attente',
                    'issuedAt' => date('Y-m-d'),
                ]
            ]);
        }
        return $this->json(['success' => false, 'error' => 'Montant manquant'], 400);
    }
}
