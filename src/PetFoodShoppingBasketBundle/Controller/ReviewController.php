<?php

namespace PetFoodShoppingBasketBundle\Controller;

use PetFoodShoppingBasketBundle\Entity\Product;
use PetFoodShoppingBasketBundle\Entity\Review;
use PetFoodShoppingBasketBundle\Form\ReviewType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;

class ReviewController extends Controller
{
    /**
     * @Route("/products/{id}/reviews", name="product_reviews")
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewByProduct(Product $product)
    {
        $reviews = $product->getReviews();

        return $this->render('reviews/product.html.twig',
            [
                'product' => $product
            ]);
    }

    /**
     * @Route("/products/{id}/reviews/add", name="leave_review_form")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function leaveReviewFormAction(Product $product)
    {
        $form = $this->createForm(
            ReviewType::class
        );

        return $this->render('reviews/leave_review.html.twig',
            [
                'reviewForm' => $form->createView(),
                'product' => $product
            ]);
    }

    /**
     * @Route("/products/{id}/reviews/add", name="leave_review_process")
     * @Method("POST")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function leaveReviewProcess(Product $product, Request $request)
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);
        if($form->isValid()) {
            $review->setProduct($product);
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            $this->addFlash("info", "Review added");

            return $this->redirectToRoute('product_reviews', ['id' => $product->getId()]);
        }

        return $this->render('reviews/product.html.twig', [
            'product' => $product
        ]);
    }
}
