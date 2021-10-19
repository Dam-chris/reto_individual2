<?php

namespace App\Repository;

use App\Entity\Notas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class NotasRepository extends ServiceEntityRepository
{
    private $entityManager;

    /**
     * @param $entityManager
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notas::class);
        $this->entityManager = $this->getEntityManager();
    }
    public function findNotasByAlumno($alumnoId)
    {
        $sql = "SELECT asigna.nombre, notas.nota FROM App\Entity\Notas notas
                JOIN notas.matricula mat
                JOIN notas.asignatura asigna
                JOIN mat.alumno alu
                WHERE alu.id = :alumnoId";
        $query = $this->entityManager->createQuery($sql)->setParameter('alumnoId', $alumnoId);
        return $query->execute();
    }
}