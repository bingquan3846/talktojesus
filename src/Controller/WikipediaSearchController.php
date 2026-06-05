<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\AI\Agent\Bridge\Wikipedia\Wikipedia;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class WikipediaSearchController extends AbstractController
{
    #[Route('/wikipedia/search', name: 'app_wikipedia_search')]
    public function index(Wikipedia $wikipedia,CacheInterface $cache ): Response
    {
        $query = 'bao';
        $allItems = $cache->get($query . '_all', function (ItemInterface $item) use ($wikipedia, $query) {
            $item->expiresAfter(3600);
            $allItems = $wikipedia->search($query);
            return $allItems;
        });

        $articles = $cache->get( $query . '_article', function (ItemInterface $item) use ($wikipedia, $query) {
            $item->expiresAfter(3600);
            $articles = $wikipedia->article($query);
            return $articles;
        });

        return $this->render('wikipedia_search/index.html.twig', [
            'query' => $query,
            'allItems' => $allItems,
            'articles' => $articles,
        ]);
    }
}
