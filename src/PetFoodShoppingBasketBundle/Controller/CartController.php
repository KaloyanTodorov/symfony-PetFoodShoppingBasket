<?php

namespace PetFoodShoppingBasketBundle\Controller;

use PetFoodShoppingBasketBundle\Entity\Cart;
use PetFoodShoppingBasketBundle\Entity\CartProduct;
use PetFoodShoppingBasketBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
                    $cp->setQuantity();
                } else {
                    $cp->setQuantity($cp->getQuantity() + 1);
                }

                $manager->persist($cp);
            }
        }

        $cart->setDateUpdated(new \DateTime());

        $manager->persist($cart);
        $manager->flush();


        return $this->redirectToRoute('cart_list');
    }


    /**
     * @Route("/cart/list", name="cart_list")
     */
    public function listAction()
    {
        return $this->render('cart/list.html.twig');
    }
}
