<?php

namespace App\Repository;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 *
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function add(Notification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Notification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findBandRequestNotification(User $sender, User $ban): ?Notification
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.type = :type')
            ->andWhere('n.sender = :sender')
            ->andWhere('n.ban = :ban')
            ->andWhere('NOT EXISTS (
                SELECT 1 FROM App\Entity\Notification n2
                WHERE n2.sender = :sender
                AND n2.ban = :ban
                AND n2.type = :type
                AND n2.createdAt > n.createdAt
            )')
            ->setParameter('type', 'banning')
            ->setParameter('sender', $sender)
            ->setParameter('ban', $ban)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findRoleChangeNotificationsForSender($sender)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.type = :type')
            ->andWhere('n.sender = :sender')
            ->setParameter('type', 'role')
            ->setParameter('sender', $sender)
            ->getQuery()
            ->getResult();
    }

    
//    /**
//     * @return Notification[] Returns an array of Notification objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Notification
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
