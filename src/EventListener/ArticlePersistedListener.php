<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Article;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class ArticlePersistedListener
{
    public function __construct(private Security $security)
    {
    }

    public function prePersist(Article $article, LifecycleEventArgs $event): void
    {
        $article->setPostedAt(new DateTimeImmutable());
        /**
         * @var User $user
         */
        $user = $this->security->getUser();
        $article->setPostedBy($user);
    }
}
