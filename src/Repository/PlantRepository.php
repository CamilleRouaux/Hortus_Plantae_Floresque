<?php

namespace App\Repository;

use App\Entity\Plant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Plant>
 *
 * @method Plant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plant[]    findAll()
 * @method Plant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plant::class);
    }

    public function add(Plant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Plant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findPlantsForBackHome($limit = 5): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM app\Entity\Plant p
            ORDER BY p.id DESC'
        )->setMaxResults($limit);

        return $query->getResult();
    }

    public function findAllOrderBy(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM app\Entity\Plant p
            ORDER BY p.id DESC'
        );

        return $query->getResult();
    }

    public function searchPlants($searchTerm)
    {
        $queryBuilder = $this->createQueryBuilder('plant');
        $queryBuilder
            ->where('LOWER(plant.name) LIKE :searchTerm OR LOWER(plant.latin_name) LIKE :searchTerm OR LOWER(plant.description) LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . strtolower($searchTerm) . '%');

        return $queryBuilder->getQuery()->getResult();
    }

    public function findByLetter($letter = null)
    {
        $queryBuilder = $this->createQueryBuilder('p');
    
        // Add sorting condition based on the selected letter
        $queryBuilder->orderBy('CASE WHEN UPPER(SUBSTRING(p.name, 1, 1)) = :letter THEN 1 ELSE 2 END, p.name', 'ASC')
                     ->setParameter('letter', strtoupper($letter));
    
        return $queryBuilder->getQuery()->getResult();
    }

//    /**
//     * @return Plant[] Returns an array of Plant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Plant
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
