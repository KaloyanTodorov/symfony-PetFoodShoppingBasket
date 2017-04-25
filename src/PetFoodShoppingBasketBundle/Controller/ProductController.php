<?php

namespace PetFoodShoppingBasketBundle\Controller;

use PetFoodShoppingBasketBundle\Entity\Product;
use PetFoodShoppingBasketBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * @Route("/products", name="all_products")
     */
    public function viewAll()
    {
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findBy([], ['price' => 'ASC']);
        return $this->render('products/view_all.html.twig',
            [
                'products' => $products
            ]);
    }

    /**
     * @Route("/products/add", name="add_product_form")
     * @Method("GET")
     */
    public function add()
    {
        $form = $this->createForm(ProductType::class);
        return $this->render('products/add.html.twig',
            [
                'productForm' => $form->createView()
            ]);
    }

    /**
     * @Route("/products/add", name="add_product_process")
     * @Method("POST")
     */
    public function addProcess(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(
            ProductType::class,
            $product
        );
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash("info", "Product with name " . $product->getName() . " was added succesfully!");

            return $this->redirectToRoute("all_products");
        }

        return $this->render("products/add.html.twig",
            [
                'productForm' => $form->createView()
            ]);
    }

    /**
     * @Route("/product/edit/{id}", name="edit_product_form")
     * @Method("GET")
     * @param Product $product
     * @return Response
     * @internal param $id
     */
    public function edit(Product $product)
    {

        $form = $this->createForm(
            ProductType::class,
            $product
        );

        return $this->render("products/edit.html.twig",
            [
                'productForm' => $form->createView()
            ]);
    }

    /**
     * @Route("/product/edit/{id}", name="edit_product_process")
     * @Method("POST")
     * @param Product $product
     * @param Request $request
     * @return Response
     * @internal param $id
     */
    public function editProcess(Product $product, Request $request)
    {

        $form = $this->createForm(
            ProductType::class,
            $product);

        $form->handleRequest($request);

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash("info", "Product with name " . $product->getName() . " was edited successfully!");

            return $this->redirectToRoute("all_products");
        }

        return $this->render("products/edit.html.twig",
            [
                'productForm' => $form->createView()
            ]);
    }

    /**
     * @Route("/products/delete/{id}", name="delete_product_process")
     * @Method("POST")
     */
    public function delete(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        $this->addFlash("delete", "Product deleted!");

        return $this->redirectToRoute("all_products");
    }
}
