<?php

namespace App\Controller;

use App\Entity\Alumnos;
use App\Entity\Asignatura;
use App\Entity\Asignaturas;
use App\Entity\Curso;
use App\Entity\Cursos;;

use App\Entity\Matriculas;
use App\Entity\Roles;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
    /**
     * @Route("/ws/alumnos", name="ws_get_alumnois", methods={"GET"})
     */
    public function alumnos(): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $asignatura = $entityManager->getRepository(Alumnos::class)->findAll();
        $json = $this->convertToJson($asignatura);
        return $json;
    }

    /**
     * @Route("/ws/asignaturas/{curso_id}", name="ws_get_asignaturas_by_curso", methods={"GET"})
     */
    public function getAsignaturasByCurso($curso_id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $asignatura = $entityManager->getRepository(Asignaturas::class)->findBy(['curso'=>$curso_id]);
        $json = $this->convertToJson($asignatura);
        return $json;
    }

    /**
     * @Route("/ws/alumnos/{curso_id}", name="ws_get_alumnos_by_curso", methods={"GET"})
     */
    public function getAlumnosByCurso($curso_id):JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $asignatura = $entityManager->getRepository(Alumnos::class)->findAlumnosByCursoId($curso_id);
        $json = $this->convertToJson($asignatura);
        return $json;
    }

    /**
     * @Route("/ws/login", name="ws_login", methods={"POST"})
     */
    public function ComprobarUsuario(Request $request):JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(),true);
        $user = $entityManager->getRepository(Alumnos::class)->findOneBy(['email' => $data['email']]);
        if($data['password'] != $user->getPassword())
        {
            return new JsonResponse(['status' => 'login fail'], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse(['status' => 'login ok'], Response::HTTP_OK); ;
    }

    /**
     * @Route("/ws/alumnos/add", name="ws_add_alumno", methods={"POST"})
     */
    public function altaAlumno(Request $request): JsonResponse
    {

        $data = json_decode($request->getContent());
        if(empty($data->email) || empty($data->nombre))
        {
            throw new NotFoundHttpException('Faltan parametros');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $rol = $entityManager->getRepository(Roles::class)->findOneBy(['id'=>$data->rol_id]);

        $alumno = new Alumnos($data->nombre, $data->apellido1, $data->apellido2,
            \DateTime::createFromFormat('Y-m-d', $data->fechaNac),
            $data->email, $data->password, $rol);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($alumno);
        $entityManager->flush();
        return new JsonResponse(['status' =>'Alumno creado'], Response::HTTP_CREATED);
    }
    /**
     * @Route("/ws/alumnos/addMatricula", name="ws_add_matricula", methods={"POST"})
     */
    public function altaMatricula(Request $request):JsonResponse
    {
        $data = json_decode($request->getContent());
        if(empty($data->cursoId) || empty($data->alumnoId))
        {
            throw new NotFoundHttpException('Faltan parametros');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $curso = $entityManager->getRepository(Cursos::class)->findOneBy(['id'=>$data->cursoId]);
        $alumno = $entityManager->getRepository(Alumnos::class)->findOneBy(['id'=>$data->alumnoId]);

        $matricula = new Matriculas(\DateTime::createFromFormat('Y-m-d', $data->fecha), $curso, $alumno);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($matricula);
        $entityManager->flush();
        return new JsonResponse(['status' =>'Matricula creada'], Response::HTTP_CREATED);
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
