<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\DoctrineUser;
use App\Service\Predicty\PredictySettings;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Ulid;

class UserFixture extends Fixture
{
    public const JOHN_ID = '01H1PP4HSADWTJZWZ51ZBD92MG';
    public const JANE_ID = '01H1PP4NVKE2CW4C92J4QBW6BE';
    public const JACK_ID = '01H1PP8NX7WNCWHWXYDCT02KXC';
    public const BILL_ID = '01H1PP9894XYJC5Y8GJQSTSKE7';
    public const ANDREW_ID = '01H1PP9GG8Z7XMVN6X3JATP9Q2';

    public const JOHN = 'john.doe@example.com';
    public const JANE = 'jane.doe@example.com';
    public const JACK = 'jack.doe@example.com';
    public const BILL = 'bill.doe@example.com';
    public const ANDREW = 'andrew.doe@example.com';

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private PredictySettings $predictySettings,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            ['id' => Ulid::fromString(self::JOHN_ID), 'email' => self::JOHN],
            ['id' => Ulid::fromString(self::JANE_ID), 'email' => self::JANE],
            ['id' => Ulid::fromString(self::JACK_ID), 'email' => self::JACK],
            ['id' => Ulid::fromString(self::BILL_ID), 'email' => self::BILL],
            ['id' => Ulid::fromString(self::ANDREW_ID), 'email' => self::ANDREW],
        ];

        foreach ($users as $user) {
            $entity = new DoctrineUser($user['id'], $user['email']);
            $entity->setPassword($this->passwordHasher->hashPassword($entity, '123456'));

            if ($user['email'] === self::JOHN) {
                $entity->setAgreedToNewsletter();
                $entity->setAgreedToTerms($this->predictySettings->getCurrentTermsOfServiceVersion());
                $entity->setOnboardingComplete();
                $entity->setEmailVerified();
            }

            $manager->persist($entity);
            $this->addReference($user['email'], $entity);
            $this->addReference((string) $user['id'], $entity);
        }

        $manager->flush();
    }
}
