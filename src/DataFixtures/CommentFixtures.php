<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private PostRepository $postRepository
    ) {}

    public function load(ObjectManager $manager): void
    {
        $user = $this->userRepository->findOneBy(['email' => 'user@blog.com']);
        if (!$user) {
            throw new \RuntimeException('Utilisateur test introuvable. Lancez d\'abord : php bin/console doctrine:fixtures:load --group=users --append');
        }

        $posts = $this->postRepository->findAll();
        if (empty($posts)) {
            throw new \RuntimeException('Aucun article trouvé. Lancez d\'abord : php bin/console doctrine:fixtures:load --group=posts --append');
        }

        $commentsData = [
            ['content' => 'Super article, merci pour ce blog au style rétro !', 'status' => 'approved'],
            ['content' => 'Très instructif, j\'ai appris beaucoup de choses sur Symfony.', 'status' => 'approved'],
            ['content' => 'Nostalgie quand tu nous tiens... Zelda reste mon préféré !', 'status' => 'approved'],
            ['content' => 'Un tuto Docker plus détaillé serait top !', 'status' => 'pending'],
            ['content' => 'L\'IA va vraiment tout changer dans les années à venir.', 'status' => 'pending'],
            ['content' => 'Merci pour ces conseils, je vais essayer d\'appliquer tout ça !', 'status' => 'approved'],
        ];

        foreach ($commentsData as $i => $data) {
            $comment = new Comment();
            $comment->setContent($data['content']);
            $comment->setAuthor($user);
            $comment->setPost($posts[$i % count($posts)]);
            $comment->setStatus($data['status']);
            $comment->setCreatedAt(new \DateTime("-{$i} hours"));
            $manager->persist($comment);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['comments'];
    }
}