<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/parcours")
 */
class RoadMapController extends AbstractController
{
    /**
     * @Route("/jour1", name="road_map_day1")
     */
    public function showDay1(): Response
    {
        return $this->render('road_map/day1.html.twig', [
            'activeMenu' => 'roadmap'
        ]);
    }

    /**
     * @Route("/jour2", name="road_map_day2")
     */
    public function showDay2(): Response
    {
        return $this->render('road_map/day2.html.twig', [
            'activeMenu' => 'roadmap'
        ]);
    }

    /**
     * @Route("/jour3", name="road_map_day3")
     */
    public function showDay3(): Response
    {
        return $this->render('road_map/day3.html.twig', [
            'activeMenu' => 'roadmap'
        ]);
    }
}
