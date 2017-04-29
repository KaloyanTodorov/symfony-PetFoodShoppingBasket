<?php
/**
 * Created by PhpStorm.
 * User: NOTEBOOK4
 * Date: 30/04/2017
 * Time: 00:17
 */

namespace PetFoodShoppingBasketBundle\Service;


use PetFoodShoppingBasketBundle\Entity\User;

interface OrderServiceInterface
{
    public function createOrder(
        User $user,
        \DateTime $date,
        array $products,
        $total);
}