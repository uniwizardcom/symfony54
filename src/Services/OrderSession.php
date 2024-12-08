<?php
/**
 * Copyright ©Uniwizard All rights reserved.
 * See LICENSE_UNIWIZARD for license details.
 */
declare(strict_types=1);


namespace App\Services;


use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderSession
{
    private RequestStack $requestStack;
    private SessionInterface $session;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->session = $requestStack->getSession();
    }

    public function getOrderId(): int
    {
        return $this->session->get('order_id', 0);
    }

    public function setOrderId(int $orderId = 0): void
    {
        $this->session->set('order_id', $orderId);
        if($orderId <= 0) {
            $this->session->remove('order_id');
        }
    }

    public function clearOrder(): void
    {
        $this->setOrderId(0);
    }
}
