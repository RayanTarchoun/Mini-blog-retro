<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // ===== ADMIN =====
        $admin = new User();
        $admin->setEmail('admin@blog.com');
        $admin->setFirstName('Admin');
        $admin->setLastName('Blog');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setIsActive(true);
        $admin->setCreatedAt(new \DateTime());
        $manager->persist($admin);

        // ===== UTILISATEUR TEST =====
        $user = new User();
        $user->setEmail('user@blog.com');
        $user->setFirstName('Jean');
        $user->setLastName('Dupont');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user123'));
        $user->setIsActive(true);
        $user->setCreatedAt(new \DateTime());
        $manager->persist($user);

        // ===== CATÉGORIES =====
        $categories = [];
        $categoryNames = [
            'Technologie' => 'Articles sur la tech, le code et l\'innovation.',
            'Gaming' => 'Actualités et tests de jeux vidéo.',
            'Lifestyle' => 'Mode de vie, conseils et astuces.',
            'Tutoriels' => 'Guides et tutoriels pas à pas.',
        ];

        foreach ($categoryNames as $name => $description) {
            $category = new Category();
            $category->setName($name);
            $category->setDescription($description);
            $manager->persist($category);
            $categories[] = $category;
        }

        // ===== ARTICLES =====
        $postsData = [
            [
                'title' => 'Bienvenue sur le NES Blog !',
                'content' => 'Bienvenue sur notre blog au style rétro ! Ici vous trouverez des articles sur la technologie, le gaming et bien plus encore. Ce blog a été créé avec Symfony et le thème NES.css pour un look pixel art unique. N\'hésitez pas à explorer les différentes catégories et à laisser vos commentaires !',
                'picture' => 'https://picsum.photos/seed/welcome/800/400',
                'category' => 0,
            ],
            [
                'title' => 'Les bases de Symfony 7',
                'content' => 'Symfony est un framework PHP puissant et flexible. Dans cet article, nous allons explorer les fondamentaux : les routes, les contrôleurs, les entités Doctrine, et le moteur de templates Twig. Symfony utilise le pattern MVC et offre une architecture modulaire grâce à ses bundles. Le système de sécurité intégré permet de gérer facilement l\'authentification et les autorisations.',
                'picture' => 'https://picsum.photos/seed/symfony/800/400',
                'category' => 0,
            ],
            [
                'title' => 'Top 10 des jeux rétro incontournables',
                'content' => 'Les jeux rétro ont marqué toute une génération. De Super Mario Bros à The Legend of Zelda, en passant par Mega Man et Metroid, ces classiques restent indémodables. Leur gameplay simple mais efficace, leurs musiques mémorables et leur difficulté légendaire en font des œuvres intemporelles que chaque gamer devrait connaître.',
                'picture' => 'https://picsum.photos/seed/retrogaming/800/400',
                'category' => 1,
            ],
            [
                'title' => 'Configurer Docker pour Symfony',
                'content' => 'Docker facilite grandement le développement avec Symfony. Avec un simple docker-compose.yml, vous pouvez configurer PHP, MySQL, Nginx et même phpMyAdmin. L\'avantage principal est la reproductibilité de l\'environnement : tous les développeurs de l\'équipe travaillent dans les mêmes conditions, éliminant le fameux "ça marche sur ma machine".',
                'picture' => 'https://picsum.photos/seed/docker/800/400',
                'category' => 3,
            ],
        ];

        $posts = [];
        foreach ($postsData as $i => $data) {
            $post = new Post();
            $post->setTitle($data['title']);
            $post->setContent($data['content']);
            $post->setPicture($data['picture']);
            $post->setAuthor($admin);
            $post->setCategory($categories[$data['category']]);
            $post->setPublishedAt(new \DateTime("-{$i} days"));
            $manager->persist($post);
            $posts[] = $post;
        }

        // ===== COMMENTAIRES =====
        $commentsData = [
            ['Super article, merci !', $user, $posts[0], 'approved'],
            ['Très instructif, j\'ai appris beaucoup de choses.', $user, $posts[1], 'approved'],
            ['Nostalgie quand tu nous tiens...', $user, $posts[2], 'approved'],
            ['Un tuto Docker serait top aussi !', $user, $posts[3], 'pending'],
        ];

        foreach ($commentsData as $data) {
            $comment = new Comment();
            $comment->setContent($data[0]);
            $comment->setAuthor($data[1]);
            $comment->setPost($data[2]);
            $comment->setStatus($data[3]);
            $comment->setCreatedAt(new \DateTime());
            $manager->persist($comment);
        }

        $manager->flush();
    }
}
