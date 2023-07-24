<?php
// src/Controller/EasternController.php
namespace App\Controller;

use App\Service\DistanceService;
use App\Service\LatLongService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EasternController extends AbstractController
{
    #[Route('/distance', name: 'get_distance')]
    public function distance(Request $request, LatLongService $latLongService, DistanceService $distanceService)
    {
        $distance = $distanceService->getDistance();
        $response = new Response(json_encode($distance));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}