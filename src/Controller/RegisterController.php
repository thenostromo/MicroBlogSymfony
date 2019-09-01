<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use App\Manager\UserManager;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="user_register")
     * @param Request $request
     * @param UserManager $userManager
     * @return RedirectResponse|Response
     */
    public function register(Request $request, UserManager $userManager)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->register($user);

            return $this->redirectToRoute('micro_post_index');
        }

        return $this->render(
            'register/register.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}
