<?php

namespace App\Repository;

use App\Entity\Annonce;
use App\Entity\AnnonceSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

/**
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    /**
     * Undocumented function
     *
     * @return 
     */
    public function findNotExpiredAnnonces()
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.expired = false');
    }

    public function findfiltredNotExpiredAnnonces(AnnonceSearch $search, $user): Query
    {

        $query = $this->findNotExpiredAnnonces();

        if ($search->getMaxPrice()) {
            $query = $query->andwhere('a.price <= :maxprice')
                ->setParameter('maxprice', $search->getMaxPrice());
        }

        if ($search->getCategory()) {
            $query = $query->andwhere('a.category = :category')
                ->setParameter('category', $search->getCategory()->getId());
        }

        if ($search->getVille()) {
            $query = $query->andwhere('a.ville = :ville')
                ->setParameter('ville', $search->getVille());
        }
        if ($user) {
            $query = $query->andwhere('a.contact = :user')
                ->setParameter('user', $user);
        }



        return $query->getQuery();
    }

    // /**
    //  * @return Annonce[] Returns an array of Annonce objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Annonce
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
