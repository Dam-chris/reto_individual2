<?php

namespace App\Controller;

use App\Entity\Asignatura;
use App\Entity\Asignaturas;
use App\Entity\Curso;
use App\Entity\Cursos;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class WsController extends AbstractController
{
    /**
     * @Route("/ws/cursos", name="ws_get_cursos", methods={"GET"})
     */
    public function cursos(): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $curso = $entityManager->getRepository(Cursos::class)->findAll();
        $json = $this->convertToJson($curso);
        return $json;
    }
    /**
     * @Route("/ws/asignaturas", name="ws_get_asignaturas", methods={"GET"})
     */
    public function asignaturas(): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $asignatura = $entityManager->getRepository(Asignaturas::class)->findAll();
        $json = $this->convertToJson($asignatura);
        return $json;
    }

    //conversor a Json
    private function convertToJson($object):JsonResponse
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $normalized = $serializer->normalize($object, null, array(DateTimeNormalizer::FORMAT_KEY => 'Y/m/d'));
        $jsonContent = $serializer->serialize($normalized, 'json');
        return JsonResponse::fromJsonString($jsonContent, 200);
    }
}
