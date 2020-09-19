<?php
declare(strict_types = 1);

namespace App\Controller;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Panther\Client;
use Symfony\Component\Routing\Annotation\Route;

class IndexController
{
    /**
     * @Route(
     *      path="/",
     *      name="index",
     *  )
     */
    public function __invoke(): JsonResponse
    {
        $client = Client::createChromeClient();
        $client->request('GET', 'https://www.dia.es/compra-online/');

        $crawler = $client->waitFor('#nav-submenu-container');

        $output = [];

        $crawler
            ->filter('#nav-submenu-container > li > a')
            ->each(function (Crawler $node) use (&$output) {
                $output[] = [
                    'text' => strip_tags($node->html()),
                    'link' => $node->attr('href'),
                ];
            });

        $client->quit();

        return new JsonResponse($output);
    }
}
