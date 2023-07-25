<?php
// src/Controller/EasternController.php
namespace App\Controller;

use App\Service\DistanceService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EasternController extends AbstractController
{
    #[Route('/distance', name: 'get_distance')]
    public function distance(Request $request, DistanceService $distanceService)
    {
        $distance = $distanceService->getDistance();
        return new JsonResponse($distance);
    }
}