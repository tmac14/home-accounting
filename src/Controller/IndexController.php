<?php

declare(strict_types = 1);

namespace App\Controller;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Panther\Client;

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
        $exclude = ['Novedades', 'Ver marcas', 'Soy solidario', 'Ofertas'];
        
        $crawler
            ->filter('#nav-submenu-container > li')
            ->each(function (Crawler $node, $i) use (&$output, $exclude) {
                if ($node->filter('a')->count() > 0) {
                    $nodeLink = $node->filter('a');
                    $text = trim(strip_tags($nodeLink->html()));

                    if (!in_array($text, $exclude)) {
                        $output[$i] = [
                            'text' => $text,
                            'link' => $nodeLink->attr('href')
                        ];

                        if ($node->children('ul')->count() > 0) {
                            $node->children('ul > li')
                                ->each(function (Crawler $node, $x) use (&$output, $exclude, $i) {
                                    $nodeLink = $node->filter('a');
                                    $text = trim(strip_tags($nodeLink->html()));

                                    if (!in_array($text, $exclude)) {
                                        $output[$i]['child'][$x] = [
                                            'text' => $text,
                                            'link' => $nodeLink->attr('href')
                                        ];

                                        if ($node->children('ul')->count() > 0) {
                                            $node->children('ul > li')
                                                ->each(function (Crawler $node) use (&$output, $exclude, $i, $x) {
                                                    $nodeLink = $node->filter('a');
                                                    $text = trim(strip_tags($nodeLink->html()));

                                                    if (!in_array($text, $exclude)) {
                                                        $output[$i]['child'][$x]['child'][] = [
                                                            'text' => $text,
                                                            'link' => $nodeLink->attr('href')
                                                        ];
                                                    }
                                                });
                                        }
                                    }
                                });
                        }
                    }
                }
            });

        $client->quit();

        return new JsonResponse($output);
    }
}
