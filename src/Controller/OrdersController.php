<?php

namespace App\Controller;

use App\Entity\OrderItems;
use App\Entity\Orders;
use App\Form\OrdersType;
use App\Form\OrderItemsType;
use App\Repository\OrderItemsRepository;
use App\Repository\OrdersRepository;
use App\Services\ApiFakeStoreApi;
use App\Services\OrderSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/orders")
 */
class OrdersController extends AbstractController
{
    private OrderSession $orderSession;

    private OrdersRepository $ordersRepository;

    private OrderItemsRepository $orderItemsRepository;

    private ApiFakeStoreApi $apiFakeStoreApi;

    public function __construct(
        OrdersRepository $ordersRepository,
        OrderSession $orderSession,
        OrderItemsRepository $orderItemsRepository,
        ApiFakeStoreApi $api
    ) {
        $this->orderSession = $orderSession;
        $this->ordersRepository = $ordersRepository;
        $this->orderItemsRepository = $orderItemsRepository;
        $this->apiFakeStoreApi = $api;

        if($this->orderSession->getOrderId() > 0) {
            $res = $this->ordersRepository->findOneBy(['id' => $this->orderSession->getOrderId()]);
            if(!$res) {
                $this->orderSession->clearOrder();
            }
        }
    }

    /**
     * @Route("/", name="app_orders_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('orders/index.html.twig', [
            'orders' => $this->ordersRepository->findAll(),
            'order_current' => $this->orderSession->getOrderId(),
        ]);
    }

    /**
     * @Route("/new", name="app_orders_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $order = new Orders();
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->ordersRepository->add($order, true);

            return $this->redirectToRoute('app_orders_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('orders/new.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_orders_show", methods={"GET"})
     */
    public function show(Orders $order): Response
    {
        $productsIds = [];
        foreach($order->getOrderItems() as $orderItem) {
            $productsIds[] = $orderItem->getProductId();
        }

        return $this->render('orders/show.html.twig', [
            'order' => $order,
            'products' => $this->apiFakeStoreApi->getProductsByIds($productsIds),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_orders_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Orders $order): Response
    {
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->ordersRepository->add($order, true);

            return $this->redirectToRoute('app_orders_index', [], Response::HTTP_SEE_OTHER);
        }

        $productsIds = [];
        foreach($order->getOrderItems() as $orderItem) {
            $productsIds[] = $orderItem->getProductId();
        }

        return $this->renderForm('orders/edit.html.twig', [
            'order' => $order,
            'form' => $form,
            'products' => $this->apiFakeStoreApi->getProductsByIds($productsIds),
        ]);
    }

    /**
     * @Route("/{id}", name="app_orders_delete", methods={"POST"})
     */
    public function delete(Request $request, Orders $order): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $this->ordersRepository->remove($order, true);
        }

        return $this->redirectToRoute('app_orders_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/orderItems/{id}", name="app_order_items_update", methods={"POST"})
     */
    public function updateOrderItems(Request $request, OrderItems $orderItems): Response
    {
        if ($this->isCsrfTokenValid('update_order_items'.$orderItems->getId(), $request->request->get('_token'))) {
            $form = $this->createForm(OrderItemsType::class, $orderItems);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if($orderItems->getProductsCount() < 0) {
                    $orderItems->setProductsCount(0);
                }

                $this->orderItemsRepository->add($orderItems, true);
            }
        }

        return $this->redirectToRoute('app_orders_edit', ['id' => $orderItems->getOrder()->getId()], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/orderItems/{id}/delete", name="app_order_items_delete", methods={"POST"})
     */
    public function deleteOrderItems(Request $request, OrderItems $orderItems): Response
    {
        if ($this->isCsrfTokenValid('delete'.$orderItems->getId(), $request->request->get('_token'))) {
            $this->orderItemsRepository->remove($orderItems, true);
        }

        return $this->redirectToRoute('app_orders_edit', ['id' => $orderItems->getOrder()->getId()], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/addItem/{productId}", name="app_orders_add_item", methods={"GET", "POST"})
     */
    public function addItem(OrderSession $orderSession, int $productId): Response
    {
        $order = $this->ordersRepository->find($orderSession->getOrderId());
        if(!$order) {
            $order = new Orders();
            $this->ordersRepository->add($order, true);
            $orderSession->setOrderId($order->getId());
        }

        $orderItemFinded = false;
        foreach ($order->getOrderItems() as $orderItem) {
            if ($orderItem->getProductId() === $productId) {
                $orderItemFinded = true;
                break;
            }
        }

        if(!$orderItemFinded) {
            $orderItem = new OrderItems();
            $orderItem->setProductId($productId);
            $orderItem->setOrder($order);
        }

        $orderItem->increaseProductsCount();
        $this->orderItemsRepository->add($orderItem, true);
        $this->ordersRepository->add($order, true);

        return $this->redirectToRoute('app_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/set_current", name="app_orders_set_current", methods={"GET", "POST"})
     */
    public function setCurrent(Request $request, Orders $order): Response
    {
        $this->orderSession->setOrderId($order->getId());

        return $this->redirectToRoute('app_orders_index', [], Response::HTTP_SEE_OTHER);
    }

}
