<?php

namespace App\Controller;

use App\Manager\UserManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        return $this->render(
            'security/login.html.twig',
            [
                'last_username' => $authenticationUtils->getLastUsername(),
                'error' => $authenticationUtils->getLastAuthenticationError()
            ]
        );
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
        //
    }

    /**
     * @Route("/confirm/{token}", name="security_confirm")
     * @param string $token
     * @param UserManager $userManager
     * @return Response
     */
    public function confirm(string $token, UserManager $userManager)
    {
        $user = $userManager->getUserByConfirmToken($token);
        if ($user !== null) {
            $userManager->activeUser($user);
        }

        return $this->render(
            'security/confirmation.html.twig',
            [
                'user' => $user
            ]
        );
    }
}
