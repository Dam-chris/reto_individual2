<?php

namespace App\Repository;

use App\Entity\Alumnos;
use App\Entity\Cursos;
use App\Entity\Matriculas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CursosRepository extends ServiceEntityRepository
{
    private $entityManager;

    /**
     * @param $entityManager
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cursos::class);
        $this->entityManager = $this->getEntityManager();
    }
    public function findCursoByAlumnoId($id)
    {
        $sql = "SELECT curs.id, curs.nombre FROM App\Entity\Matriculas mat 
        JOIN mat.curso curs 
        JOIN mat.alumno alu
        WHERE alu.id = :alumno_id";
        $query = $this->entityManager->createQuery($sql)->setParameter('alumno_id', $id);
        return $query->execute();
    }
}