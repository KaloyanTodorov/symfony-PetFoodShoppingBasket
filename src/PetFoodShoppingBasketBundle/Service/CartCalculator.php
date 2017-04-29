<?php
/**
 * Created by PhpStorm.
 * User: NOTEBOOK4
 * Date: 29/04/2017
 * Time: 17:19
 */

namespace PetFoodShoppingBasketBundle\Service;


use Doctrine\ORM\EntityManagerInterface;
use PetFoodShoppingBasketBundle\Entity\Cart;
use PetFoodShoppingBasketBundle\Entity\CartProduct;
use PetFoodShoppingBasketBundle\Entity\Product;
use PetFoodShoppingBasketBundle\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;


class CartCalculator implements CartServiceInterface
{
    private $entityManager;
    private $session;
    private $orderService;

    public function __construct(EntityManagerInterface $entityManager,
                                Session $session,
                                OrderServiceInterface $orderService)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->orderService = $orderService;
    }


    public function addProductToCart(User $user, Product $product)
    {
        // TODO: Implement addProductToCart() method.
    }

    /**
     * @param Product[]|ArrayCollection $products
     * @return float
     */
    public function calculateCart($products)
    {
        if($products->count() == 0) {
            return 0.0;
        }

        $cartSumTotal = array_sum(
            array_map(function (Product $p) {
                return $p->getPrice();
            }, $products->toArray()
        ));

        return $cartSumTotal;
    }


    public function getProductsTotal($products)
    {
        // TODO: Implement getProductsTotal() method.
    }

    public function removeProductFromCart(User $user, Product $product)
    {
        // TODO: Implement removeProductFromCart() method.
    }


    public function checkoutCart(User $user)
    {
        // TODO: Implement checkoutCart() method.
    }
}