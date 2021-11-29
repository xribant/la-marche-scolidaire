<?php

namespace App\Controller;

use App\Entity\ImageGallery;
use App\Entity\Picture;
use App\Form\ImageGalleryType;
use App\Repository\ImageGalleryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/gallery")
 */
class ImageGalleryController extends AbstractController
{
    /**
     * @Route("/", name="gallery_index", methods={"GET"})
     */
    public function index(ImageGalleryRepository $imageGalleryRepository): Response
    {
        return $this->render('admin/image_gallery/index.html.twig', [
            'image_galleries' => $imageGalleryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="gallery_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $imageGallery = new ImageGallery();
        $form = $this->createForm(ImageGalleryType::class, $imageGallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('images')->getData();

            foreach($images as $image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                $image->move(
                    $this->getParameter('app.path.gallery_images'),
                    $fichier
                );

                $img = new Picture();
                $img->setName($fichier);
                $imageGallery->addPicture($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($imageGallery);
            $entityManager->flush();

            return $this->redirectToRoute('gallery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/image_gallery/new.html.twig', [
            'image_gallery' => $imageGallery,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="gallery_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ImageGallery $imageGallery): Response
    {
        $form = $this->createForm(ImageGalleryType::class, $imageGallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('images')->getData();

            foreach($images as $image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                $image->move(
                    $this->getParameter('app.path.gallery_images'),
                    $fichier
                );

                $img = new Picture();
                $img->setName($fichier);
                $imageGallery->addPicture($img);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gallery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/image_gallery/edit.html.twig', [
            'image_gallery' => $imageGallery,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="gallery_delete", methods={"POST"})
     */
    public function delete(Request $request, ImageGallery $imageGallery): Response
    {
        if ($this->isCsrfTokenValid('delete'.$imageGallery->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($imageGallery);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gallery_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/delete/image/{id}", name="gallery_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Picture $picture, Request $request) {

        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete'.$picture->getId(), $data['_token'])){

            $nom = $picture->getName();

            unlink($this->getParameter('app.path.gallery_images').'/'.$nom);

            $em = $this->getDoctrine()->getManager();
            $em->remove($picture);
            $em->flush();

            return new JsonResponse(['success' => 1]);

        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }
}
