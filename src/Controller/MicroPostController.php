<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Entity\User;
use App\Manager\MicroPostManager;
use App\Manager\UserManager;

class MicroPostController extends AbstractController
{
    /**
     * @Route("/", name="micro_post_index")
     *
     * @param MicroPostManager $microPostManager
     * @param UserManager $userManager
     * @return Response
     */
    public function index(MicroPostManager $microPostManager, UserManager $userManager)
    {
        $currentUser = $this->getUser();
        $usersToFollow = [];

        if ($currentUser instanceof User) {
            $posts = $microPostManager->getMicroPostByUsers(
                $currentUser->getFollowing()
            );

            $usersToFollow = $userManager->getUsersToFollow(
                $currentUser,
                $posts
            );
        } else {
            $posts = $microPostManager->getAll();
        }
        return $this->render(
            'micro-post/index.html.twig',
            [
                'posts'         => $posts,
                'usersToFollow' => $usersToFollow
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="micro_post_edit")
     * @Security("is_granted('edit', microPost)", message="Access denied")
     *
     * @param MicroPost $microPost
     * @param Request $request
     * @return Response
     */
    public function edit(MicroPost $microPost, Request $request)
    {
        $form = $this->createForm(MicroPostType::class, $microPost);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('micro_post_index');
        }
        return $this->render(
            'micro-post/add.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="micro_post_delete")
     * @Security("is_granted('delete', microPost)", message="Access denied")
     *
     * @param MicroPost $microPost
     * @param MicroPostManager $microPostManager
     * @return Response
     */
    public function delete(MicroPost $microPost, MicroPostManager $microPostManager)
    {
        $microPostManager->delete($microPost);

        $this->addFlash('notice', 'Micro post was deleted');

        return $this->redirectToRoute('micro_post_index');
    }

    /**
     * @Route("/add", name="micro_post_add")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param Request $request
     * @param MicroPostManager $microPostManager
     * @return RedirectResponse|Response
     */
    public function add(Request $request, MicroPostManager $microPostManager)
    {
        $user = $this->getUser();

        $microPost = new MicroPost();
        $microPost->setUser($user);

        $form = $this->createForm(MicroPostType::class, $microPost);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $microPostManager->update($microPost);

            return $this->redirectToRoute('micro_post_index');
        }
        return $this->render(
            'micro-post/add.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/user/{username}", name="micro_post_user")
     *
     * @param User $userWithPosts
     * @param MicroPostManager $microPostManager
     * @return Response
     */
    public function userPosts(User $userWithPosts, MicroPostManager $microPostManager)
    {
        return $this->render(
            'micro-post/user-posts.html.twig',
            [
                'posts' => $microPostManager->getAllByUser($userWithPosts),
                'user' => $userWithPosts
            ]
        );
    }

    /**
     * @Route("/micro-post/{id}", name="micro_post_post")
     * @param MicroPost $microPost
     * @return Response
     */
    public function post(MicroPost $microPost)
    {
        return $this->render(
            'micro-post/post.html.twig',
            [
                'post' => $microPost
            ]
        );
    }
}
