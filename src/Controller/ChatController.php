<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\HistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
#[Route('', name: 'app_chat')]
final class ChatController extends AbstractController
{
    #[Route('/chat', name: '')]
    public function index(): Response
    {
        return $this->render('chat/index.html.twig');
    }
    #[Route('/chat/histories', name: '_histories')]
    public function histories(HistoryRepository $historyRepository, #[CurrentUser] ?User $user): Response
    {
        if ($user instanceof User) {
            $histories = $user->getHistories();
        } else {
            $histories = $historyRepository->findAll();
        }

        return $this->render('chat/histories.html.twig', [
            'histories' => $histories,
        ]);
    }
    #[Route('/chat/history/{id}', name: '_history', requirements: ['id' => '\d+'])]
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
