<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\HistoryRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        if(!$user) {
            throw $this->createNotFoundException();
        }

        if ($user instanceof User && in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            $histories = $historyRepository->findBySortedByDate();
        
        } else {
            $histories = $user->getHistories();
        }
       
        return $this->render('chat/histories.html.twig', [
            'initialHistories' => $histories,
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
    #[Route('/chat/history/delete/{id}', name: '_history_delete', requirements: ['id' => '\d+'])]
    public function deleteHistory(int $id, HistoryRepository $historyRepository, EntityManagerInterface $entityManager): Response
    {
        $history = $historyRepository->find($id);
        if(!$history) {
            throw $this->createNotFoundException();
        }

        $this->denyAccessUnlessGranted('POST_DELETE', $history);

        $entityManager->remove($history);
        $entityManager->flush();

        return $this->redirectToRoute('app_chat_histories');
    }
}
