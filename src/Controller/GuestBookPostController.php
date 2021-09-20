<?php

namespace App\Controller;

use App\Entity\GuestBookPost;
use App\Form\GuestBookPostType;
use App\Repository\GuestBookPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/guestbook")
 */
class GuestBookPostController extends AbstractController
{
    /**
     * @Route("/", name="guest_book_post_index", methods={"GET"})
     */
    public function index(GuestBookPostRepository $guestBookPostRepository): Response
    {
        return $this->render('admin/guest_book_post/index.html.twig', [
            'guest_book_posts' => $guestBookPostRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="guest_book_post_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $guestBookPost = new GuestBookPost();
        $form = $this->createForm(GuestBookPostType::class, $guestBookPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $guestBookPost->setChecked(false);
            $entityManager->persist($guestBookPost);
            $entityManager->flush();

            return $this->redirectToRoute('guest_book_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/guest_book_post/new.html.twig', [
            'guest_book_post' => $guestBookPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="guest_book_post_show", methods={"GET"})
     */
    public function show(GuestBookPost $guestBookPost): Response
    {
        return $this->render('admin/guest_book_post/show.html.twig', [
            'guest_book_post' => $guestBookPost,
        ]);
    }

    /**
     * @Route("/{id}/approve", name="guest_book_post_approve", methods={"GET","POST"})
     */
    public function approve(GuestBookPost $guestBookPost): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $guestBookPost->setChecked(true);
        $entityManager->persist($guestBookPost);
        $entityManager->flush();

        return $this->redirectToRoute('guest_book_post_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/reject", name="guest_book_post_reject", methods={"GET","POST"})
     */
    public function reject(GuestBookPost $guestBookPost): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $guestBookPost->setChecked(false);
        $entityManager->persist($guestBookPost);
        $entityManager->flush();

        return $this->redirectToRoute('guest_book_post_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/edit", name="guest_book_post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, GuestBookPost $guestBookPost): Response
    {
        $form = $this->createForm(GuestBookPostType::class, $guestBookPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('guest_book_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/guest_book_post/edit.html.twig', [
            'guest_book_post' => $guestBookPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="guest_book_post_delete", methods={"POST"})
     */
    public function delete(Request $request, GuestBookPost $guestBookPost): Response
    {
        if ($this->isCsrfTokenValid('delete'.$guestBookPost->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($guestBookPost);
            $entityManager->flush();
        }

        return $this->redirectToRoute('guest_book_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
