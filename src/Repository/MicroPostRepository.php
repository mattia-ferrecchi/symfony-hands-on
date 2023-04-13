<?php

namespace App\Repository;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MicroPost>
 *
 * @method MicroPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method MicroPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method MicroPost[]    findAll()
 * @method MicroPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MicroPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MicroPost::class);
    }

    public function save(MicroPost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MicroPost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllWithComments(): array
    {
        return $this->findAllQuery(
            withComments: true
        )->getQuery()
        ->getResult();
    }

    public function findAllByAuthor(
        int | User $author
    ):array
    {
        return $this->findAllquery(
            withComments:true,
            withLikes:true,
            withAuthors:true,
            withProfiles:true
        )->where('p.author = :author')
        ->setParameter(
            'author',
            $author instanceof User ? $author->getId() : $author
        )->getQuery()
        ->getResult();
    }

    private function findAllQuery(
        bool $withComments=false,
        bool $withLikes=false,
        bool $withAuthors=false,
        bool $withProfiles=false,
    ): QueryBuilder{
        $query=$this->createQueryBuilder('p');

        if($withComments){
            $query->leftJoin('p.comments', 'c')
                ->addselect('c');
        }

        if($withLikes){
            $query->leftJoin('p.likedBy', 'l')
                ->addselect('l');
        }

        if($withAuthors || $withProfiles){
            $query->leftJoin('p.author', 'a')
                ->addselect('a');
        }

        if($withProfiles){
            $query->leftJoin('a.userProfile', 'up')
                ->addselect('up');
        }

        return $query->orderBy('p.created', 'DESC');
    }

//    /**
//     * @return MicroPost[] Returns an array of MicroPost objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MicroPost
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
