<?php

namespace App\Repository;

use App\Entity\Alumnos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AlumnadoRepository extends ServiceEntityRepository
{
    private $entityManager;

    /**
     * @param $entityManager
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alumnos::class);
        $this->entityManager = $this->getEntityManager();
    }

    public function findAlumnosByCursoId($curso_id)
    {
        $sql = "SELECT alu.nombre FROM App\Entity\Matriculas mat 
        JOIN mat.alumno alu 
        JOIN mat.curso curs
        WHERE curs.id = :curso_id";
        $query = $this->entityManager->createQuery($sql)->setParameter('curso_id', $curso_id);
        return $query->execute();
    }

}