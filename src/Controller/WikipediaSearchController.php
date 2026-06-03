<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\AI\Agent\Bridge\Wikipedia\Wikipedia;

final class WikipediaSearchController extends AbstractController
{
    #[Route('/wikipedia/search', name: 'app_wikipedia_search')]
    public function index(Wikipedia $wikipedia): Response
    {
        $query = 'bao';
        $allItems = $wikipedia->search($query);
        $articles = $wikipedia->article($query);
        return $this->render('wikipedia_search/index.html.twig', [
            'query' => $query,
            'allItems' => $allItems,
            'articles' => $articles,
        ]);
    }
}
