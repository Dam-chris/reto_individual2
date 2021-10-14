<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Alumno
 *
 * @ORM\Table(name="alumno", indexes={@ORM\Index(name="usuario_id", columns={"usuario_id"})})
 * @ORM\Entity
 */
class Alumno
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
     * @var string
     *
     * @ORM\Column(name="apellido1", type="string", length=45, nullable=false)
     */
    private $apellido1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="apellido2", type="string", length=45, nullable=true)
     */
    private $apellido2;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     */
    private $usuario;



    /**
     * @param string $nombre
     * @param string $apellido1
     * @param string|null $apellido2
     * @param \Usuario $usuario
     */
    public function __construct(string $nombre, string $apellido1, ?string $apellido2, \Usuario $usuario)
    {
        $this->nombre = $nombre;
        $this->apellido1 = $apellido1;
        $this->apellido2 = $apellido2;
        $this->usuario = $usuario;
    }

    //getter y seters

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
     * @return string
     */
    public function getApellido1(): string
    {
        return $this->apellido1;
    }

    /**
     * @param string $apellido1
     */
    public function setApellido1(string $apellido1): void
    {
        $this->apellido1 = $apellido1;
    }

    /**
     * @return string|null
     */
    public function getApellido2(): ?string
    {
        return $this->apellido2;
    }

    /**
     * @param string|null $apellido2
     */
    public function setApellido2(?string $apellido2): void
    {
        $this->apellido2 = $apellido2;
    }

    /**
     * @return \Usuario
     */
    public function getUsuario(): \Usuario
    {
        return $this->usuario;
    }

    /**
     * @param \Usuario $usuario
     */
    public function setUsuario(\Usuario $usuario): void
    {
        $this->usuario = $usuario;
    }


}
