<?php

namespace PetFoodShoppingBasketBundle\Controller;

use PetFoodShoppingBasketBundle\Entity\User;
use PetFoodShoppingBasketBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="user_login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        $auth_utils = $this->get('security.authentication_utils');

        $error = $auth_utils->getLastAuthenticationError();
        $last_username = $auth_utils->getLastUsername();

        return [
            'error' => $error,
            'last_username' => $last_username,
        ];
    }

    /**
     * @Route("/register", name="user_register")
     * @Template()
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registerAction(Request $request)
    {
        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            /** @var User $user */
            $user = $form->getData();



            $user->setRoles([$user->getDefaultRole()]);

            $encrypter = $this->get('security.password_encoder');

            $user->setPassword(
                $encrypter->encodePassword($user, $user->getPasswordRaw())
            );

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);

            return $this->redirectToRoute('homepage');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }
}
