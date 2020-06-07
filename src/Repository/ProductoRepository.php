<?php

namespace App\Repository;

use App\Entity\Producto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Producto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Producto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Producto[]    findAll()
 * @method Producto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductoRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Producto::class);
        $this->manager = $manager;
    }

    // /**
    //  * @return Producto[] Returns an array of Producto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Producto
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function saveProducto($nombre, $descripcion, $precio, $precioIVA, $imagen, $taxonomia)
    {
        $newProducto = new Producto();
        $newProducto
            ->setNombre($nombre)
            ->setDescripcion($descripcion)
            ->setPrecio($precio)
            ->setPrecioIVA($precioIVA)
            ->setImagen($imagen)
            ->setTaxonomia($taxonomia);
        $this->manager->persist($newProducto);
        $this->manager->flush();
    }

    public function updateProducto(Producto $producto): Producto {
        $this->manager->persist($producto);
        $this->manager->flush();
        return $producto;
    }

    public function removeProducto(Producto $producto){
        $this->manager->remove($producto);
        $this->manager->flush();
    }

    public function paginaProductos($pagina=1, $numProductos=3){
        $qb = $this->createQueryBuilder('p')
        ->setFirstResult($numProductos*($pagina-1))
        ->setMaxResults($numProductos)
        ->getQuery();
        return $qb->execute();
      }

      public function buscarPorTaxonomia($taxonomia, $pagina, $numProductos){
        $qb = $this->createQueryBuilder('p')
        ->where('p.taxonomia = '. $taxonomia)
        ->setFirstResult($numProductos*($pagina-1))
        ->setMaxResults($numProductos)
        ->getQuery();
        return $qb->execute();
      }

      public function buscarPorPrecio($precioDesde, $precioHasta, $pagina, $numProductos){
        $qb = $this->createQueryBuilder('p')
        ->where('p.precio >='. $precioDesde)
        ->andWhere('p.precio <='. $precioHasta)
        ->setFirstResult($numProductos*($pagina-1))
        ->setMaxResults($numProductos)
        ->getQuery();
        return $qb->execute();
      }

      public function buscarPorTexto($texto, $pagina, $numProductos){
        $qb = $this->createQueryBuilder('p')
        ->where("p.nombre LIKE '%$texto%'")
        ->orWhere("p.descripcion LIKE '%$texto%'")
        ->orWhere("p.imagen LIKE '%$texto%'")
        ->setFirstResult($numProductos*($pagina-1))
        ->setMaxResults($numProductos)
        ->getQuery();
        return $qb->execute();
      }

}
