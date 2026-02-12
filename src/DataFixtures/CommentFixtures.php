<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = $this->getReference(UserFixtures::USER_REFERENCE, User::class);

        $commentsData = [
            [
                'content' => 'Super article, merci pour ce blog au style rétro !',
                'post' => PostFixtures::POST_WELCOME,
                'status' => 'approved',
            ],
            [
                'content' => 'Très instructif, j\'ai appris beaucoup de choses sur Symfony.',
                'post' => PostFixtures::POST_SYMFONY,
                'status' => 'approved',
            ],
            [
                'content' => 'Nostalgie quand tu nous tiens... Zelda reste mon préféré !',
                'post' => PostFixtures::POST_RETRO,
                'status' => 'approved',
            ],
            [
                'content' => 'Un tuto Docker plus détaillé serait top !',
                'post' => PostFixtures::POST_DOCKER,
                'status' => 'pending',
            ],
            [
                'content' => 'L\'IA va vraiment tout changer dans les années à venir.',
                'post' => PostFixtures::POST_AI,
                'status' => 'pending',
            ],
            [
                'content' => 'Merci pour ces conseils, je vais essayer d\'appliquer tout ça !',
                'post' => PostFixtures::POST_HABITS,
                'status' => 'approved',
            ],
        ];

        foreach ($commentsData as $i => $data) {
            $comment = new Comment();
            $comment->setContent($data['content']);
            $comment->setAuthor($user);
            $comment->setPost($this->getReference($data['post'], Post::class));
            $comment->setStatus($data['status']);
            $comment->setCreatedAt(new \DateTime("-{$i} hours"));
            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PostFixtures::class,
        ];
    }
}
