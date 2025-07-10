<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FactureController extends AbstractController
{
    #[Route('/api/factures', name: 'creer_facture', methods: ['POST'])]
    public function creerFacture(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        // Récupération des données du client et des lignes de facture
        $buyerName = $data['buyer_name'] ?? '';
        $buyerTaxNo = $data['buyer_tax_no'] ?? '';
        $positions = $data['positions'] ?? [];

        // Variables d'environnement
        $apiToken = $_ENV['VOSFACTURES_API_TOKEN'];
        $accountHost = $_ENV['VOSFACTURES_HOST'];

        // Préparer les données pour VosFactures
        $invoiceData = [
            'api_token' => $apiToken,
            'invoice' => [
                'kind' => 'vat',
                'number' => null,
                'sell_date' => date('Y-m-d'),
                'issue_date' => date('Y-m-d'),
                'payment_to' => date('Y-m-d', strtotime('+7 days')),
                'buyer_name' => $buyerName,
                'buyer_tax_no' => $buyerTaxNo,
                'positions' => $positions,
            ]
        ];

        // Appel à VosFactures
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://$accountHost/invoices.json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($invoiceData));

        $result = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($httpStatus >= 200 && $httpStatus < 300) {
            return $this->json(['success' => true, 'data' => json_decode($result, true)]);
        }

        return $this->json(['success' => false, 'message' => 'Erreur lors de la création de la facture', 'details' => $result], $httpStatus);
    }
}
