<?php

namespace PetFoodShoppingBasketBundle\Controller;

use PetFoodShoppingBasketBundle\Entity\Product;
use PetFoodShoppingBasketBundle\Entity\Tag;
use PetFoodShoppingBasketBundle\Form\TagType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TagController extends Controller
{
    /**
     * @Route("/product/{id}/tags/add", name="add_tag_form")
     * @Method("GET")
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addTag(Product $product)
    {
        $form = $this->createForm(TagType::class);
        return $this->render('tags/add.html.twig', ['tagForm' => $form->createView()]);
    }

    /**
     * @Route("/product/{id}/tags/add", name="add_tag_process")
     * @Method("POST")
     * @param Product $product
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addTagProcess(Product $product, Request $request)
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if($form->isValid()) {
            $repository = $this->getDoctrine()->getRepository(Tag::class);
            $tagDb = $repository->findOneBy(['name' => $tag->getName()]);
            if($tagDb) {
                $tag = $tagDb;
            }

            $tag->getProducts()->add($product);
            $product->getTags()->add($tag);
            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->persist($product);
            $em->flush();



            return $this->redirectToRoute('all_products');
        }

        return $this->render('tags/add.html.twig',
            [
                'tagForm' => $form->createView()
            ]);
    }

    /**
     * @Route("/products/{id}/tags", name="tags_by_product")
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listByProduct(Product $product)
    {
        return $this->render("tags/product.html.twig",
            [
                'product' => $product
            ]);
    }

    /**
     * @Route("/tags/{id}", name="tag_view")
     * @param Tag $tag
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function productsByTag(Tag $tag)
    {
        return $this->render('tags/current_tag.html.twig', ['tag' => $tag]);
    }
}
