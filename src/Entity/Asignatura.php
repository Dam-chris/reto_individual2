<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Asignatura
 *
 * @ORM\Table(name="asignatura", indexes={@ORM\Index(name="alumno_id", columns={"alumno_id"}), @ORM\Index(name="curso_id", columns={"curso_id"})})
 * @ORM\Entity
 */
class Asignatura
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=45, nullable=false)
     */
    private $nombre;

    /**
     * @var \Curso
     *
     * @ORM\ManyToOne(targetEntity="Curso")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="curso_id", referencedColumnName="id")
     * })
     */
    private $curso;

    /**
     * @var \Alumno
     *
     * @ORM\ManyToOne(targetEntity="Alumno")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="alumno_id", referencedColumnName="id")
     * })
     */
    private $alumno;


    /**
     * @param string $nombre
     * @param \Curso $curso
     * @param \Alumno $alumno
     */
    public function __construct(string $nombre, \Curso $curso, \Alumno $alumno)
    {
        $this->nombre = $nombre;
        $this->curso = $curso;
        $this->alumno = $alumno;
    }

//getters y setters

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return \Curso
     */
    public function getCurso(): \Curso
    {
        return $this->curso;
    }

    /**
     * @param \Curso $curso
     */
    public function setCurso(\Curso $curso): void
    {
        $this->curso = $curso;
    }

    /**
     * @return \Alumno
     */
    public function getAlumno(): \Alumno
    {
        return $this->alumno;
    }

    /**
     * @param \Alumno $alumno
     */
    public function setAlumno(\Alumno $alumno): void
    {
        $this->alumno = $alumno;
    }


}
