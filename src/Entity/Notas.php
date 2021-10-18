<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notas
 *
 * @ORM\Table(name="notas", indexes={@ORM\Index(name="asignatura_id", columns={"asignatura_id"}), @ORM\Index(name="matricula_id", columns={"matricula_id"})})
 * @ORM\Entity (repositoryClass="App\Repository\NotasRepository")
 */
class Notas
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
     * @var string|null
     *
     * @ORM\Column(name="nota", type="decimal", precision=3, scale=2, nullable=true)
     */
    private $nota;

    /**
     * @var Matriculas
     *
     * @ORM\ManyToOne(targetEntity="Matriculas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="matricula_id", referencedColumnName="id")
     * })
     */
    private $matricula;

    /**
     * @var Asignaturas
     *
     * @ORM\ManyToOne(targetEntity="Asignaturas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="asignatura_id", referencedColumnName="id")
     * })
     */
    private $asignatura;

    /**
     * @param string|null $nota
     * @param Matriculas $matricula
     * @param Asignaturas $asignatura
     */
    public function __construct(?string $nota, Matriculas $matricula, Asignaturas $asignatura)
    {
        $this->nota = $nota;
        $this->matricula = $matricula;
        $this->asignatura = $asignatura;
    }

//getters y setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNota(): ?string
    {
        return $this->nota;
    }

    public function setNota(?string $nota): self
    {
        $this->nota = $nota;

        return $this;
    }

    public function getMatricula(): ?Matriculas
    {
        return $this->matricula;
    }

    public function setMatricula(?Matriculas $matricula): self
    {
        $this->matricula = $matricula;

        return $this;
    }

    public function getAsignatura(): ?Asignaturas
    {
        return $this->asignatura;
    }

    public function setAsignatura(?Asignaturas $asignatura): self
    {
        $this->asignatura = $asignatura;

        return $this;
    }


}
