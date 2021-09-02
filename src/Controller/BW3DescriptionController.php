<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BW3DescriptionController extends AbstractController
{
    /**
     * @Route("/bw3", name="bw3_description")
     */
    public function index(): Response
    {
        return $this->render('bw3_description/index.html.twig', [
            'activeMenu' => 'bw3',
        ]);
    }
}
