<?php

namespace PetFoodShoppingBasketBundle\Controller;

use PetFoodShoppingBasketBundle\Entity\Cart;
use PetFoodShoppingBasketBundle\Entity\CartProduct;
use PetFoodShoppingBasketBundle\Entity\Product;
use PetFoodShoppingBasketBundle\Form\CartType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CartController extends Controller
{
    /**
     * @Route("/cart/add", name="cart_add")
     */
    public function addAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $session = $this->get('session');

        $id_cart = $session->get('id_cart', false);

        if(!$id_cart){
            $cart = new Cart();
            $cart->setUser($this->getUser()->getId());
            $cart->setDateCreated(new \DateTime());
            $cart->setDateUpdated(new \DateTime());
            $cart->setTotalPricePerCart(0.0);
            $manager->persist($cart);
            $manager->flush();

            $session->set('id_cart', $cart->getId());
        }

        $cart = $this->getDoctrine()->getRepository(Cart::class)->find($session->get('id_cart', false));

        $products = $request->get('products');


        foreach ($products as $id_product){
            /** @var Product $product */
            $product = $this->getDoctrine()->getRepository(Product::class)->find($id_product);

            if($product){
                $cp = $this->getDoctrine()->getRepository(CartProduct::class)->findOneBy([
                    'cart' => $cart,
                    'product'=> $product
                ]);

                if(!$cp){
                    $cp= new CartProduct();
                    $cp->setCart($cart);
                    $cp->setProduct($product);
                    $cp->setQuantity(1);
                    $cp->setProductPrice($product->getPrice());
                } else {
                    $cp->setQuantity($cp->getQuantity() + 1);
                    $cp->setProductPrice($cp->getQuantity() * $product->getPrice());
                }

                $manager->persist($cp);

            }

            dump($product);
        }


        $cart->setDateUpdated(new \DateTime());

        $manager->persist($cart);
        $manager->flush();

        return $this->redirectToRoute('cart_show', [
            'cart' => $cart->getId()
        ]);
    }


    /**
     * @Route("/cart/show/{cart}", name="cart_show")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Cart $cart)
    {
        $user = $this->getUser($cart->getUser());
        $productsCart = $this->fillCart($cart);
        $sumCart = $this->cartSum($cart);

        return $this->render('cart/show.html.twig', [
            'productsCart' => $productsCart,
            'user' => $user,
            'totalPrice' => $sumCart,
            ]);
    }


    public function fillCart(Cart $cart)
    {
        $repo = $this->getDoctrine()->getRepository(CartProduct::class)->findAll();
        $productsCart = [];

        foreach ($repo as $product) {
            if($cart->getId() == $product->getCart()->getId()) {
                $productsCart[$product->getProduct()->getId()] = $product;
            }
        }

        return $productsCart;
    }

    /**
     * @param Cart $cart
     * @Route("/cart/sum/{cart}")
     */
    public function cartSum(Cart $cart)
    {
        $productsCart = $this->fillCart($cart);
        /** @var Product $product */
        $sum = 0.0;
        foreach ($productsCart as $product) {
            $countPerProduct = $product->getQuantity();
            $productId = $product->getProduct()->getid();
            $productPrice = $product->getProduct()->getPrice();

            $sum += $productPrice * $countPerProduct;

            dump($sum);
        }
        return $sum;
    }

    /**
     * @Route("/cart/checkout/{cart}")
     */
    public function checkoutCart(Cart $cart)
    {

    }
}
