<?php

namespace App\Controller;

use App\Entity\History;
use App\Repository\HistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ChatController extends AbstractController
{
    #[Route('/chat', name: 'app_chat')]
    public function index(): Response
    {
        return $this->render('chat/index.html.twig');
    }
    #[Route('/chat/histories', name: 'app_chat_histories')]
    public function histories(HistoryRepository $historyRepository): Response
    {
        $histories = $historyRepository->findAll();

        return $this->render('chat/histories.html.twig', [
            'histories' => $histories,
        ]);
    }
    #[Route('/chat/history/{id}', name: 'app_chat_history', requirements: ['id' => '\d+'])]
    public function history(int $id, HistoryRepository $historyRepository): Response
    {
        $history = $historyRepository->find($id);
        if(!$history) {
            throw $this->createNotFoundException();
        }

        return $this->render('chat/history.html.twig', [
            'history' => $history,
        ]);
    }
}
