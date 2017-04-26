<?php

namespace PetFoodShoppingBasketBundle\Controller;

use Doctrine\DBAL\Types\DecimalType;
use PetFoodShoppingBasketBundle\Entity\User;
use PetFoodShoppingBasketBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    /**
     * @Route("/user/profile", name="show_user_profile")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param $user
     */
    public function showProfileAction(Request $request)
    {
        return $this->render('/user/profile.html.twig');
    }

    /**
     * @Route("/user/edit/{id}", name="edit_user_profile_form")
     * @Method("GET")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param Product $product
     * @internal param $id
     */
    public function editUserFormAction(User $user, $id)
    {

        $id = $user->getId();

        $form = $this->createForm(
            UserType::class,
            $user
        );


        return $this->render("user/edit.html.twig",
            [
                'userProfileForm' => $form->createView()
            ]);
    }

    /**
     * @Route("/user/edit/{id}", name="edit_user_profile_process")
     * @Method("POST")
     * @param User $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param Product $product
     * @internal param $id
     */
    public function editUserProcess(User $user, Request $request, $id)
    {

        $id = $user->getId();

        if($user->getId() != $this->getUser()->getId() && !$this->isGranted('ROLE_ADMIN', $this->getUser() )) {
            $this->get('session')->getFlashBag()->add('error', 'Only owners or admins can edit profiles.');
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(
            UserType::class,
            $user
        );

        $form->handleRequest($request);

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Profile was edited successfully');

            return $this->redirectToRoute("show_user_profile");
        }

        return $this->render("user/edit.html.twig",
            [
                'userProfileForm' => $form->createView()
            ]);
    }
}
