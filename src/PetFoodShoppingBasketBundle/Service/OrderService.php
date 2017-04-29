<?php
/**
 * Created by PhpStorm.
 * User: NOTEBOOK4
 * Date: 30/04/2017
 * Time: 00:17
 */

namespace PetFoodShoppingBasketBundle\Service;


use PetFoodShoppingBasketBundle\Entity\User;

class OrderService implements OrderServiceInterface
{
    private $entityManager;

    /**
     * OrderService constructor.
     * @param $entityManager
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @param User $user
     * @param \DateTime $date
     * @param array $products
     * @param $total
     * @return ProductsOrder
     */
    public function createOrder(
        User $user,
        \DateTime $date,
        array $products,
        $total)
    {
        $order = new ProductsOrder();
        $order->setUser($user);
        $order->setDate($date);
        $order->setProducts($products);
        $order->setTotal($total);
        $order->setVerified(false);
        return $order;
    }
}