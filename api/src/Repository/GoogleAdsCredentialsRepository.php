<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\GoogleAdsCredentials;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class GoogleAdsCredentialsRepository
{
    /**
     * @var EntityRepository<GoogleAdsCredentials>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $this->em->getRepository(GoogleAdsCredentials::class);
    }

    public function findOrCreate(int $userId): GoogleAdsCredentials
    {
        $entity = $this->repository->findOneBy(['userId' => $userId]);

        if ($entity === null) {
            $entity = new GoogleAdsCredentials($userId);
        }

        return $entity;
    }

    public function save(GoogleAdsCredentials $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}
