<?php

namespace App\Controller;

use App\Entity\ContactForm;
use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactFormController extends AbstractController
{
    /**
     * @Route("/contact", name="contact_form")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $contactForm = new ContactForm();

        $newForm = $this->createForm(ContactFormType::class, $contactForm);
        $newForm->handleRequest($request);

        if ($newForm->isSubmitted() && $newForm->isValid()) {

            $ip = $request->getClientIp();

            $contactForm->setIp($ip);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contactForm);
            $from = $contactForm->getEmail();
            $recipient = 'info@lamarchescolidaire.be';
            $subject = '[lamarchescolidaire.be]: '.$contactForm->getSubject();
            $message = '<p><strong>Nom : '.$contactForm->getName().'</strong></p><p>'.$contactForm->getMessage().'</p>';

            $email = (new Email())
                ->from($from)
                ->to($recipient)
                ->subject($subject)
                ->html($message);

            $mailer->send($email);

            $this->addFlash('success', 'Votre message a bien été envoyé');
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('contact_form/index.html.twig', [
            'newForm' => $newForm->createView(),
            'activeMenu' => 'contact'
        ]);
    }
}
