<?php

namespace App\Entity;

use App\Repository\ProductoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductoRepository::class)
 */
class Producto
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $nombre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="float")
     */
    private $precio;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imagen;

    /**
     * @ORM\ManyToOne(targetEntity=Taxonomia::class, inversedBy="productos")
     */
    private $taxonomia;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $precioIVA;

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

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getTaxonomia(): ?Taxonomia
    {
        return $this->taxonomia;
    }

    public function setTaxonomia(?Taxonomia $taxonomia): self
    {
        $this->taxonomia = $taxonomia;

        return $this;
    }


    public function toArray(){
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'descripcion' => $this->getDescripcion(),
            'precio' => $this->getPrecio(),
            'imagen' => $this->getImagen(),
            'taxonomia' => $this->getTaxonomia(),
        ];
    }

    public function getPrecioIVA(): ?float
    {
        return $this->precioIVA;
    }

    public function setPrecioIVA(float $precioIVA): self
    {
        $this->precioIVA = $precioIVA;

        return $this;
    }

}
