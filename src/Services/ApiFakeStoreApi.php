<?php
/**
 * Copyright ©Uniwizard All rights reserved.
 * See LICENSE_UNIWIZARD for license details.
 */
declare(strict_types=1);

namespace App\Services;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiFakeStoreApi
{
    private HttpClientInterface $client;

    /**
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getProducts(): array
    {
        $response = $this->client->request(
            'GET',
            'https://fakestoreapi.com/products'
        );

//        $statusCode = $response->getStatusCode(); // $statusCode = 200
//        $contentType = $response->getHeaders()['content-type'][0]; // $contentType = 'application/json'
//        $content = $response->getContent(); // $content = '{"id":521583, "name":"symfony-docs", ...}'
//        $content = $response->toArray(); // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $response->toArray();
    }

    /**
     * @param int[] $ids
     *
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getProductsByIds(array $ids): array
    {
        $response = $this->client->request(
            'GET',
            'https://fakestoreapi.com/products'
        );

        $results = [];
        foreach($response->toArray() as $product) {
            if(in_array((int)$product['id'], $ids)) {
                $results[(int)$product['id']] = $product;
            }
        }

        return $results;
    }

    /**
     * @param int $id
     *
     * @return array|null
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getProduct(int $id): ?array
    {
        $response = $this->client->request(
            'GET',
            'https://fakestoreapi.com/products'
        );

        foreach($response->toArray() as $product) {
            if((int)$product['id'] === $id) {
                return $product;
            }
        }

        return null;
    }
}
