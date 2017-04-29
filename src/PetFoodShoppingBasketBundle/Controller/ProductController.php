<?php

namespace PetFoodShoppingBasketBundle\Controller;

use PetFoodShoppingBasketBundle\Entity\Product;
use PetFoodShoppingBasketBundle\Entity\Promotion;
use PetFoodShoppingBasketBundle\Entity\Stock;
use PetFoodShoppingBasketBundle\Entity\User;
use PetFoodShoppingBasketBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    const NUM_RESULT = 10;

    /**
     * @Route("/products", name="all_products")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function viewAll(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(Product::class)
            ->createQueryBuilder('p')
            ->select('p');

        $pagination = $paginator->paginate(
            $query->getQuery(),
            $request->query->getInt('page', 1),
            self::NUM_RESULT
        );

        return compact('pagination');
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addProcess(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(
            ProductType::class,
            $product
        );

        $product->setUser($this->getUser());

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash("info", "Product with name " . $product->getName() . " was added succesfully!");

            return $this->redirectToRoute("all_products");
        }

        return $this->render("products/add.html.twig",
            [
                'productForm' => $form->createView(),
            ]);
    }

    /**
     * @Route("/product/view/{id}", name="view_product_form")
     * @Method("GET")
     * @param Product $product
     * @return Response
     */
    public function viewProduct(Product $product)
    {
        $form = $this->createForm(
            ProductType::class,
            $product
        );

        return $this->render("products/view_product.html.twig",
            [
                'product' => [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'description' => $product->getDescription(),
                    'price' => $product->getPrice(),
                    'created_on' => $product->getCreatedOn()->format('Y:m:d H:i'),
                    'category' => $product->getCategory(),
                    'user' => $product->getUser()->getId(),
                ]
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
        if($product->getUser()->getId() != $this->getUser()->getId() && !$this->isGranted('ROLE_ADMIN', $this->getUser() )) {
            $this->get('session')->getFlashBag()->add('error', 'Only owners or admins can edit their projects.');
            return $this->redirectToRoute('all_products');
        }

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

    /**
     * @Route("/products", name="all_products")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function producsKnpAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(Product::class)
            ->createQueryBuilder('p')
            ->select('p');

        $pagination = $paginator->paginate(
            $query->getQuery(),
            $request->query->getInt('page', 1),
            self::NUM_RESULT
        );

        $calc = $this->get('price_calculator');

        $max_promotion = $this->get('promotion_manager')
                        ->getGeneralPromotion();

        return compact('pagination', 'max_promotion', 'calc');

        // in template use {{ calc.calculate(product.price) | number_format(2) }}
    }
}
