<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\DoctrineUser;
use App\Service\Predicty\PredictySettings;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Factory\UuidFactory;

class UserFixture extends Fixture
{
    public const JOHN = 'john.doe@example.com';
    public const JANE = 'jane.doe@example.com';
    public const JACK = 'jack.doe@example.com';
    public const BILL = 'bill.doe@example.com';
    public const ANDREW = 'andrew.doe@example.com';

    public function __construct(
        private UuidFactory $uuidFactory,
        private UserPasswordHasherInterface $passwordHasher,
        private PredictySettings $predictySettings,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            self::JOHN,
            self::JANE,
            self::JACK,
            self::BILL,
            self::ANDREW,
        ];

        foreach ($users as $user) {
            $entity = new DoctrineUser($user);
            $entity->setUuid($this->uuidFactory->create());
            $entity->setPassword($this->passwordHasher->hashPassword($entity, '123456'));

            if ($user === self::JOHN) {
                $entity->setAgreedToNewsletter();
                $entity->setAgreedToTerms($this->predictySettings->getCurrentTermsOfServiceVersion());
                $entity->setOnboardingComplete();
                $entity->setEmailVerified();
            }

            $manager->persist($entity);
            $this->addReference($user, $entity);
        }

        $manager->flush();
    }
}
