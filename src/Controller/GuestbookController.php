<?php

namespace App\Controller;

use App\Entity\GuestBookPost;
use App\Form\GuestBookPostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/guestbook")
 */
class GuestbookController extends AbstractController
{
    /**
     * @Route("/", name="guestbook")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $allPost = $entityManager->getRepository(GuestBookPost::class)->findAll();

        return $this->render('guestbook/index.html.twig', [
            'activeMenu' => 'guestbook',
            'allpost' => $allPost
        ]);
    }

    /**
     * @Route("/post", name="guestbook_post")
     */
    public function guestbookPost(Request $request): Response
    {
        $guestBookPost = new GuestBookPost();
        $form = $this->createForm(GuestBookPostType::class, $guestBookPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $guestBookPost->setChecked(false);
            $entityManager->persist($guestBookPost);
            $entityManager->flush();

            return $this->redirectToRoute('guestbook', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('guestbook/post.html.twig', [
            'guest_book_post' => $guestBookPost,
            'activeMenu' => 'guestbook',
            'form' => $form->createView(),
        ]);
    }
}
