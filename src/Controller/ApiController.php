<?php

namespace App\Controller;

use App\Service\AiAssistant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiController extends AbstractController
{
    #[Route('/api/ask', name: 'app_api_ask', methods: ['POST'])]
    public function ask(Request $request, AiAssistant $aiAssistant): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $question = $data['question'] ?? '';

        $answer = $aiAssistant->ask($question);

        return $this->json([
            'answer' => $answer,
        ]);
    }
}
