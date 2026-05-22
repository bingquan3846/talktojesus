<?php

namespace App\Controller;

use App\Entity\History;
use App\Service\AiAssistant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiController extends AbstractController
{
    #[Route('/api/ask', name: 'app_api_ask', methods: ['POST'])]
    public function ask(Request $request, AiAssistant $assistant): StreamedResponse
    {
        $data = json_decode($request->getContent(), true);
        $question = $data['question'] ?? '';

        $response = new StreamedResponse(function () use ($assistant, $question) {
            // Use the agent to get the result
            $result = $assistant->askstreamed($question);

            // Iterate over the stream and collect all content
            foreach ($result as $chunk) {
                // Collect the chunk
                // Echo to client
                echo $chunk;

                // Flush to ensure it's sent to the browser immediately
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            }
        });

        // Set headers BEFORE the callback starts streaming output
        $response->headers->set('Content-Type', 'application/x-ndjson');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no'); // For Nginx

        return $response;
   }
   #[Route('/api/create/history', name: 'app_api_create_history', methods: ['POST'])]
   public function createHistory(Request $request, EntityManagerInterface $entityManager): JsonResponse
   {
       $data = json_decode($request->getContent(), true);

       $history = new History();
       $history->setQuestion($data['question']);
       $history->setAnswer($data['answer']);
       $history->setCreateDate(new \DateTime());
       $entityManager->persist($history);
       $entityManager->flush();

       return new JsonResponse($data);
   }
}
