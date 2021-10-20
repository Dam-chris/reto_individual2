<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Asignaturas
 *
 * @ORM\Table(name="asignaturas", indexes={@ORM\Index(name="curso_id", columns={"curso_id"})})
 * @ORM\Entity
 */
class Asignaturas
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
     * @var \Cursos
     *
     * @ORM\ManyToOne(targetEntity="Cursos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="curso_id", referencedColumnName="id")
     * })
     */
    private $curso;

    /**
     * @param string $nombre
     * @param \Cursos $curso
     */
    public function __construct(string $nombre, Cursos $curso)
    {
        $this->nombre = $nombre;
        $this->curso = $curso;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getCurso(): ?Cursos
    {
        return $this->curso;
    }

    public function setCurso(?Cursos $curso): self
    {
        $this->curso = $curso;

        return $this;
    }


}
