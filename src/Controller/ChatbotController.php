<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ChatbotController extends AbstractController
{
    private HttpClientInterface $client;
    private string $openAiApiKey;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->openAiApiKey = $_ENV['OPENAI_API_KEY'] ?? 'sk-xxxx'; // À configurer dans .env
    }

    #[Route('/api/chatbot', name: 'api_chatbot', methods: ['POST'])]
    public function ask(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $userMessage = $data['message'] ?? '';
        if (!$userMessage) {
            return $this->json(['success' => false, 'error' => 'Message manquant'], 400);
        }

        // Scénarios métiers personnalisés côté serveur
        $userInput = strtolower($userMessage);
        if (str_contains($userInput, 'facture')) {
            return $this->json(['success' => true, 'bot' => "Pour consulter vos factures, rendez-vous dans la section 'Factures' du menu."]);
        }
        if (str_contains($userInput, 'commande')) {
            return $this->json(['success' => true, 'bot' => "Pour suivre ou créer une commande, utilisez la section 'Commandes' du portail."]);
        }
        // ... autres scénarios personnalisés ...

        // Appel à l'API OpenAI
        $response = $this->client->request('POST', 'https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->openAiApiKey,
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'Tu es un assistant pour le portail client DMM.'],
                    ['role' => 'user', 'content' => $userMessage],
                ],
            ],
        ]);
        $data = $response->toArray(false);
        $botText = $data['choices'][0]['message']['content'] ?? "Désolé, je n'ai pas compris votre demande.";
        return $this->json(['success' => true, 'bot' => $botText]);
    }
}
