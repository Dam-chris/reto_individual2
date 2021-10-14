<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WsController extends AbstractController
{
    /**
     * @Route("/ws", name="ws_get_cursos", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'hola asier!!',
            'path' => 'lalalalal lalalala  lelelele',
        ]);
    }
}
