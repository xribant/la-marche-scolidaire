<?php

namespace App\Controller;

use App\Entity\GuestBookPost;
use App\Form\GuestBookPostType;
use Flasher\Prime\FlasherInterface;
use Flasher\Toastr\Prime\ToastrFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
    public function guestbookPost(Request $request, MailerInterface $mailer, FlasherInterface $flasher, ToastrFactory $toastr): Response
    {
        $guestBookPost = new GuestBookPost();
        $form = $this->createForm(GuestBookPostType::class, $guestBookPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $guestBookPost->setChecked(false);
            $entityManager->persist($guestBookPost);
            $entityManager->flush();

            $message = '<p>Un nouveau post est en attente de validation pour le livre d\'or : <strong>#'.$guestBookPost->getId().'</strong></p>
                        <p><strong>Auteur: '.$guestBookPost->getAuthor().'</strong></p>
                        <p>'.$guestBookPost->getMessage().'</p>
                        <p><a href="https://www.lamarchescolidaire.be/admin/guestbook">Admin Guestbook</a></p>';

            $email = (new Email())
                ->from('no-reply@lamarchescolidaire.be')
                ->to('direction@biereau.be')
                ->subject('Le nouveau post #'.$guestBookPost->getId().' est en attente de validation pour le Livre d\'or')
                ->html($message);

            $mailer->send($email);

            $builder = $flasher->type('success')
                ->message('Votre post a bien été pris en compte et celui-ci est en attente de validation pour être affiché')
                ->option('timer', 8000);
            $builder->flash();

            return $this->redirectToRoute('guestbook', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('guestbook/post.html.twig', [
            'guest_book_post' => $guestBookPost,
            'activeMenu' => 'guestbook',
            'form' => $form->createView(),
        ]);
    }
}
