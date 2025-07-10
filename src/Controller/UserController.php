<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        // Exemple : appel à l'API du CRM pour vérifier l'utilisateur
        // Ici, on simule la réponse du CRM
        if ($email === 'admin@dmm.com' && $password === 'admin123') {
            $user = new User([
                'email' => $email,
                'fullName' => 'Admin DMM',
                'roles' => ['ROLE_ADMIN'],
            ]);
            return $this->json([
                'success' => true,
                'user' => [
                    'email' => $user->getEmail(),
                    'fullName' => $user->getFullName(),
                    'roles' => $user->getRoles(),
                ],
                'token' => 'fake-jwt-token-admin',
            ]);
        }
        // Simuler un utilisateur classique
        if ($email === 'client@dmm.com' && $password === 'client123') {
            $user = new User([
                'email' => $email,
                'fullName' => 'Client DMM',
                'roles' => ['ROLE_USER'],
            ]);
            return $this->json([
                'success' => true,
                'user' => [
                    'email' => $user->getEmail(),
                    'fullName' => $user->getFullName(),
                    'roles' => $user->getRoles(),
                ],
                'token' => 'fake-jwt-token-client',
            ]);
        }
        return $this->json(['success' => false, 'error' => 'Identifiants invalides'], 401);
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $fullName = $data['fullName'] ?? '';
        $password = $data['password'] ?? '';

        // Ici, on simule l'enregistrement dans le CRM
        // En production, faire un appel HTTP au CRM pour créer l'utilisateur
        if ($email && $fullName && $password) {
            // Simuler succès
            return $this->json([
                'success' => true,
                'user' => [
                    'email' => $email,
                    'fullName' => $fullName,
                    'roles' => ['ROLE_USER'],
                ],
                'token' => 'fake-jwt-token-register',
            ]);
        }
        return $this->json(['success' => false, 'error' => 'Champs manquants'], 400);
    }
}
