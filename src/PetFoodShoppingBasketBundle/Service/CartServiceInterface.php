<?php
namespace PetFoodShoppingBasketBundle\Service;

use PetFoodShoppingBasketBundle\Entity\Product;
use PetFoodShoppingBasketBundle\Entity\User;

interface CartServiceInterface
{
    public function getProductsTotal($products);

    public function removeProductFromCart(User $user, Product $product);

    public function addProductToCart(User $user, Product $product);

    public function checkoutCart(User $user);
}