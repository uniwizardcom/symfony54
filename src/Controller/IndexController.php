<?php

namespace App\Controller;

use App\Services\ApiFakeStoreApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="app_index")
     */
    public function index(ApiFakeStoreApi $api): Response
    {
        $products = [];
        try {
            $products = $api->getProducts();
        } catch (\Throwable $e) {
            /**
             * for all: ClientExceptionInterface, DecodingExceptionInterface, RedirectionExceptionInterface, ServerExceptionInterface, TransportExceptionInterface
             *
             * use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
             * use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
             * use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
             * use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
             * use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
             */
        }

        return $this->render('index.html.twig', [
            'site_title' => 'Order Management Service',
            'products'   => $products,
        ]);
    }
}
