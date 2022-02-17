<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private const DEFAULT_PASSWORD = 'admin';
    private const NUMBER_OF_USERS = 5;
    private const NUMBER_OF_ARTICLES = 50;

    private Generator $faker;

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        // disabling article prepersist listener when loading fixtures
        $manager->getMetadataFactory()->getMetadataFor(Article::class)->entityListeners = [];

        $this->loadUsers($manager);
        $this->loadArticles($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        for ($i = 0; $i < self::NUMBER_OF_USERS; $i++) {
            $user = new User();
            $user->setUsername($this->faker->userName());
            $user->setPassword($this->passwordHasher->hashPassword($user, self::DEFAULT_PASSWORD));
            $this->addReference("user-{$i}", $user);
            $manager->persist($user);
        }
        $manager->flush();
    }

    private function loadArticles(ObjectManager $manager)
    {
        $createdAt = new DateTimeImmutable();
        for ($i = 0; $i < self::NUMBER_OF_ARTICLES; $i++) {
            $article = new Article();
            $article->setTitle($this->faker->text());
            $article->setContent($this->faker->paragraphs(3, true));
            $article->setPostedAt($createdAt);
            /**
             * @var User $user
             */
            try {
                $user = $this->getReference("user-" . random_int(0, self::NUMBER_OF_USERS - 1));
            } catch (Exception $e) {
                $user = $this->getReference("user-0");
            }
            $article->setPostedBy($user);
            $manager->persist($article);
        }
        $manager->flush();
    }
}
