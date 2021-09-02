<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectDescriptionController extends AbstractController
{
    /**
     * @Route("/project", name="project_description")
     */
    public function index(): Response
    {
        return $this->render('project_description/index.html.twig', [
            'activeMenu' => 'project'
        ]);
    }
}
