<?php

namespace App\Controller;

use App\Repository\ImageGalleryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    /**
     * @Route("/gallery", name="gallery")
     */
    public function index(ImageGalleryRepository $imageGalleryRepository): Response
    {
        return $this->render('gallery/index.html.twig', [
            'activeMenu' => 'photos',
            'galeries' => $imageGalleryRepository->findAll()
        ]);
    }
}
