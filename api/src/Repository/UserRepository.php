<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\DoctrineUser;
use App\Entity\User;
use App\Entity\UserWithId;
use App\Entity\WrappedUser;
use App\Service\Security\Authorization\PasswordUpgrader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use RuntimeException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Uid\Ulid;

class UserRepository implements PasswordUpgrader
{
    /**
     * @var EntityRepository<DoctrineUser>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(DoctrineUser::class);
    }

    public function getById(Ulid|UserWithId $id): User
    {
        if ($id instanceof DoctrineUser) {
            return $id;
        }

        if ($id instanceof UserWithId) {
            $id = $id->getId();
        }

        return $this->repository->find($id) ?? throw new RuntimeException('User was not found');
    }

    public function getByUsername(string $username): User
    {
        return $this->findByUsername($username) ?? throw new RuntimeException('User was not found');
    }

    public function findById(Ulid $id): ?User
    {
        return $this->repository->find($id);
    }

    public function findByUsername(string $username): ?User
    {
        return $this->repository->findOneBy(['email' => $username]);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->repository->findOneBy(['email' => $email]);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof DoctrineUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->save($user);
    }

    /**
     * @return User[]
     */
    public function findByAccountId(Ulid $accountId): array
    {
        // For some reason, doctrine would not let me bind the $accountId parameter
        $query = "SELECT u.id FROM \"user\" u WHERE jsonb_path_exists(u.account_ids, '$[*].id ? (@[*] == \"$accountId\")')";
        $stmt = $this->em->getConnection()->prepare($query);

        $result = $stmt->executeQuery();
        $ids = $result->fetchFirstColumn();

        // decided to split it into two queries as doctrine native query was a bit too complex, maybe change in the future?
        return $this->repository->findBy(['id' => $ids]);
    }

    public function save(User $user): void
    {
        while ($user instanceof WrappedUser) {
            $user = $user->getUser();
        }

        if (!$user instanceof DoctrineUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $this->em->persist($user);
        $this->em->flush();
    }
}
