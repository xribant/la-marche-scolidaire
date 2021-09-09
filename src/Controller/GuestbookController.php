<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GuestbookController extends AbstractController
{
    /**
     * @Route("/guestbook", name="guestbook")
     */
    public function index(): Response
    {
        return $this->render('guestbook/index.html.twig', [
            'activeMenu' => 'guestbook'
        ]);
    }
}
