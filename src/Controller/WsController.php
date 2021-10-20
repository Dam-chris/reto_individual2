<?php

namespace App\Controller;

use App\Entity\Alumnado;
use App\Entity\Alumnos;
use App\Entity\Asignatura;
use App\Entity\Asignaturas;
use App\Entity\Curso;
use App\Entity\Cursos;;

use App\Entity\Matriculas;
use App\Entity\Notas;
use App\Entity\Roles;
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
use Symfony\Config\Framework\ValidationConfig;

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
        $json = $this->convertToJson($user);
        return $json;
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
            $data->email, $data->password, $data->fotoperfil, $rol);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($alumno);
        $entityManager->flush();
        $json = $this->convertToJson($alumno);
        return $json;
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
        $date = substr($data->fecha, 0, 10);

        $entityManager = $this->getDoctrine()->getManager();
        $curso = $entityManager->getRepository(Cursos::class)->findOneBy(['id'=>$data->cursoId]);
        $alumno = $entityManager->getRepository(Alumnos::class)->findOneBy(['id'=>$data->alumnoId]);

        $matricula = new Matriculas(\DateTime::createFromFormat('Y-m-d', $date), $curso, $alumno);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($matricula);
        $entityManager->flush();

        return new JsonResponse(['status' =>'Matricula creada'], Response::HTTP_CREATED);
    }
    /**
     * @Route("/ws/notas/{alumnoId}", name="ws_get_notas_by_alumno", methods={"GET"})
     */
    public function getNotasByAlumno($alumnoId):JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $notas = $entityManager->getRepository(Notas::class)->findNotasByAlumno($alumnoId);
        $json = $this->convertToJson($notas);
        return $json;
    }

    /**
     * @Route("/ws/imagenes", name="ws_put_image", methods={"PUT"})
     */
    public function putImage(Request $request):JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $entityManager = $this->getDoctrine()->getManager();

        $alumno = $entityManager->getRepository(Alumnos::class)->findOneBy(['id'=> $data['alumnoId']]);

        empty($data['fotoperfil']) ? true:$alumno->setFotoperfil($data['fotoperfil']);

        $entityManager->persist($alumno);
        $entityManager->flush();

        $json = $this->convertToJson($alumno);
        return $json;
    }
    /**
     * @Route("/ws/imagenes/get/{alumnoId}", name="ws_get_image", methods={"GET"})
     */
    public function getImage($alumnoId):JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();

        $alumno = $entityManager->getRepository(Alumnos::class)->findOneBy(['id'=> $alumnoId]);;

        $json = $this->convertToJson($alumno);

        return $json;
    }

    /**
     * @Route ("/ws/cursos/get/{alumnoId}", name="ws_getcursos_by_id", methods={"GET"})
     */
    public function getCursosByAlumno($alumnoId):JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();

        $curso = $entityManager->getRepository(Cursos::class)->findCursoByAlumnoId(['id'=> $alumnoId]);


        $json = $this->convertToJson($curso);

        return $json;

    }
    /**
     * @Route("/ws/matricula/delete/{alumnoId}", name="ws_delete_matricula", methods={"DELETE"})
     */
    public function borrarMatricula($alumnoId):JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $alumno = $entityManager->getRepository(Alumnos::class)->findBy(['id'=> $alumnoId]);

        $matricula = $entityManager->getRepository(Matriculas::class)->findOneBy(['alumno'=>$alumno]);
        $entityManager->remove($matricula);
        $entityManager->flush();
        ////////////////////////////////////////////
        $entityManager = $this->getDoctrine()->getManager();
        $Matriculas = $entityManager->getRepository(Matriculas::class)->findAll();
        $json = $this->convertToJson($Matriculas);
        return $json;
    }



    /*update
     *
     * public function updateAlumno(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $entityManager = $this->getDoctrine()->getManager();
        $alu = $entityManager->getRepository(Alumnado::class)->findOneBy(['id'=> $data["id"]]);

        empty($data["dni"]) ? true:$alu->setDni($data["dni"]);
        empty($data["nombre"]) ? true:$alu->setNombre($data["nombre"]);
        empty($data["apellido1"]) ? true:$alu->setApellido1($data["apellido1"]);
        empty($data["apellido2"]) ? true:$alu->setApellido2($data["apellido2"]);
        empty($data["fecha"]) ? true:$alu->setFecha(\DateTime::createFromFormat('Y-m-d', $data["fecha"]));
        empty($data["provincia"]) ? true:$alu->setProvincia($data["provincia"]);

        $entityManager->persist($alu);
        $entityManager->flush();
        return new JsonResponse(['status'=>'Alumno modificado'], Response::HTTP_CREATED);
    }

     */
    /*delete
     *     public function deleteAlumno($id):JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $alu = $entityManager->getRepository(Alumnado::class)->findOneBy(['id'=>$id]);
        $entityManager->remove($alu);
        $entityManager->flush();
        ////////////////////////////////////////////
        $entityManager = $this->getDoctrine()->getManager();
        $alumnado = $entityManager->getRepository(Alumnado::class)->findAll();
        $json = $this->convertToJson($alumnado);
        return $json;
    }
     */

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
