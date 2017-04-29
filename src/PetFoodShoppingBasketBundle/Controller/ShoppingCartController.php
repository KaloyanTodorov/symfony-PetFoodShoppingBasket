<?php

namespace PetFoodShoppingBasketBundle\Controller;

use PetFoodShoppingBasketBundle\Entity\Product;
use PetFoodShoppingBasketBundle\Entity\ShoppingCart;
use PetFoodShoppingBasketBundle\Repository\ShoppingCartRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class CartController
 * @Route("cart")
 * @package PetFoodShoppingBasketBundle\Controller
 */
class ShoppingCartController extends Controller
{
    /**
     * @Route("/show", name="show_cart")
     */
    public function showCart()
    {
        return $this->render('cart/show.html.twig');
    }


    /**
     * @Route("/add/{id}", name="add_to_cart")
     * @Method("GET")
     */
    public function addToCart(Product $product)
    {


        $cart = $this->getDoctrine()->getRepository(ShoppingCart::class)
        ->find($product->getId());

        dump($cart);

        return $this->render('add_product.view.html.twig', array(
            'cart' => $cart
        ));

    }
}
