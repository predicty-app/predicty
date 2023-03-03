<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Ad;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class AdRepository
{
    /**
     * @var EntityRepository<Ad>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Ad::class);
    }

    public function save(Ad $ad):void
    {
        $this->em->persist($ad);
        $this->em->flush();
    }
}
