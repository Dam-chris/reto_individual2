<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cursos
 *
 * @ORM\Table(name="cursos")
 *  @ORM\Entity(repositoryClass="App\Repository\CursosRepository")
 */
class Cursos
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
     * @param string $nombre
     */
    public function __construct(string $nombre)
    {
        $this->nombre = $nombre;
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


}
