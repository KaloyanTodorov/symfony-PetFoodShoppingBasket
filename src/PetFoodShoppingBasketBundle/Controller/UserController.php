<?php

namespace PetFoodShoppingBasketBundle\Controller;

use PetFoodShoppingBasketBundle\Entity\Role;
use PetFoodShoppingBasketBundle\Entity\User;
use PetFoodShoppingBasketBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_register_form")
     * @Method("GET")
     * @return Response
     */
    public function regsiterAction()
    {
        $form = $this->createForm(UserType::class);
        return $this->render('users/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/register", name="user_register_process")
     * @Method("POST")
     *
     * @param Request $request
     * @return Response
     */
    public function registerProcess(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $encoder = $this->get('security.password_encoder');

        if ($form->isValid()) {
            $hashedPassword = $encoder->encodePassword(
                $user,
                $user->getPassword()
            );

            $userRole = $this->getDoctrine()->getRepository(Role::class)
                ->findOneBy(['name'=>'ROLE_USER']);
            $user->addRole($userRole);
            $user->setPassword($hashedPassword);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("homepage");
        }

        return $this->render('users/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
