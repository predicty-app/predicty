<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use RuntimeException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class UserRepository implements PasswordUpgraderInterface
{
    /**
     * @var EntityRepository<User>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(User::class);
    }

    public function getById(int $id): User
    {
        $user = $this->repository->find($id);
        assert($user instanceof User, 'User not found');

        return $user;
    }

    public function getByUsername(string $username): User
    {
        $user = $this->findByUsername($username);

        if ($user === null) {
            throw new RuntimeException('User was not found');
        }

        return $user;
    }

    public function findById(int $id): ?User
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

    public function save(User $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->save($user);
    }
}
