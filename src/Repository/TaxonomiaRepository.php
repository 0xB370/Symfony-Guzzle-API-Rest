<?php

namespace App\Repository;

use App\Entity\Taxonomia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Taxonomia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Taxonomia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Taxonomia[]    findAll()
 * @method Taxonomia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaxonomiaRepository extends ServiceEntityRepository
{

    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Taxonomia::class);
        $this->manager = $manager;
    }

    // /**
    //  * @return Taxonomia[] Returns an array of Taxonomia objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Taxonomia
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function saveTaxonomia($nombre, $descripcion, $imagen)
    {
        $newTaxonomia = new Taxonomia();
        $newTaxonomia
            ->setNombre($nombre)
            ->setDescripcion($descripcion)
            ->setImagen($imagen);
        $this->manager->persist($newTaxonomia);
        $this->manager->flush();
    }

    public function updateTaxonomia(Taxonomia $taxonomia): Taxonomia {
        $this->manager->persist($taxonomia);
        $this->manager->flush();
        return $taxonomia;
    }

    public function removeTaxonomia(Taxonomia $taxonomia){
        $this->manager->remove($taxonomia);
        $this->manager->flush();
    }

    public function paginaTaxonomias($pagina=1, $numTaxonomias=3){
        $qb = $this->createQueryBuilder('t')
        ->setFirstResult($numTaxonomias*($pagina-1))
        ->setMaxResults($numTaxonomias)
        ->getQuery();
        return $qb->execute();
    }

    public function buscarPorTexto($texto, $pagina, $numTaxonomias){
        $qb = $this->createQueryBuilder('t')
        ->where("t.nombre LIKE '%$texto%'")
        ->orWhere("t.descripcion LIKE '%$texto%'")
        ->orWhere("t.imagen LIKE '%$texto%'")
        ->setFirstResult($numTaxonomias*($pagina-1))
        ->setMaxResults($numTaxonomias)
        ->getQuery();
        return $qb->execute();
    }

}
