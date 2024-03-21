<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function add(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @return Article[] Returns an array of Article objects
    */
    public function findArticleByType(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
        "SELECT a
        FROM App\Entity\Article a
        JOIN a.type t
        WHERE t.name = 'Article'
        "
        );

        // returns an array of Product objects
        return $query->getResult();
    }

    /**
    * @return Article[] Returns an array of Article objects
    */
    public function findLastTreeArticleByType(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
        "SELECT a
        FROM App\Entity\Article a
        JOIN a.type t
        WHERE t.name = 'Article'
        ORDER BY a.created_at DESC
        "
        )->setMaxResults(3);;

        // returns an array of Product objects
        return $query->getResult();
    }

    /**
    * @return Card[] Returns an array of Article objects
    */
    public function findCardByType(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "SELECT a
            FROM App\Entity\Article a
            JOIN a.type t
            WHERE t.name = 'Fiche'
            "
        );

        // returns an array of Product objects
        return $query->getResult();
    }

    public function findArticlesForBackHome($limit = 5): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a
            FROM app\Entity\Article a
            ORDER BY a.created_at DESC'
        )->setMaxResults($limit);

        return $query->getResult();
    }

    public function findAllOrderBy(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a
            FROM app\Entity\Article a
            ORDER BY a.created_at DESC'
        );

        return $query->getResult();        
    }

    public function searchArticles($searchTerm, $articleType)
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->leftJoin('a.type', 't')
            ->where('LOWER(a.title) LIKE :searchTerm AND t.name = :articleType')
            ->setParameter('searchTerm', '%' . strtolower($searchTerm) . '%')
            ->setParameter('articleType', $articleType);

        return $queryBuilder->getQuery()->getResult();
    }


//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
