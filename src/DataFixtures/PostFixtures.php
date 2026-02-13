<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture implements FixtureGroupInterface
{
    public const POST_WELCOME = 'post-welcome';
    public const POST_SYMFONY = 'post-symfony';
    public const POST_RETRO = 'post-retro';
    public const POST_DOCKER = 'post-docker';
    public const POST_AI = 'post-ai';
    public const POST_API = 'post-api';
    public const POST_HABITS = 'post-habits';

    public function __construct(
        private UserRepository $userRepository,
        private CategoryRepository $categoryRepository
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Récupérer ou ignorer si pas d'admin
        $admin = $this->userRepository->findOneBy(['email' => 'admin@blog.com']);
        if (!$admin) {
            throw new \RuntimeException('Admin introuvable. Lancez d\'abord : php bin/console doctrine:fixtures:load --group=users --append');
        }

        // Récupérer les catégories existantes
        $categories = [];
        $categoryNames = ['Technologie', 'Gaming', 'Lifestyle', 'Tutoriels'];
        foreach ($categoryNames as $name) {
            $cat = $this->categoryRepository->findOneBy(['name' => $name]);
            if (!$cat) {
                throw new \RuntimeException("Catégorie '$name' introuvable. Lancez d'abord : php bin/console doctrine:fixtures:load --group=categories --append");
            }
            $categories[] = $cat;
        }

        $postsData = [
            ['ref' => self::POST_WELCOME, 'title' => 'Bienvenue sur le NES Blog !', 'content' => 'Bienvenue sur notre blog au style rétro ! Ici vous trouverez des articles sur la technologie, le gaming et bien plus encore. Ce blog a été créé avec Symfony et le thème NES.css pour un look pixel art unique. N\'hésitez pas à explorer les différentes catégories et à laisser vos commentaires !', 'picture' => 'https://picsum.photos/seed/welcome/800/400', 'category' => 0],
            ['ref' => self::POST_SYMFONY, 'title' => 'Les bases de Symfony 7', 'content' => 'Symfony est un framework PHP puissant et flexible. Dans cet article, nous allons explorer les fondamentaux : les routes, les contrôleurs, les entités Doctrine, et le moteur de templates Twig. Symfony utilise le pattern MVC et offre une architecture modulaire grâce à ses bundles. Le système de sécurité intégré permet de gérer facilement l\'authentification et les autorisations.', 'picture' => 'https://picsum.photos/seed/symfony/800/400', 'category' => 0],
            ['ref' => self::POST_RETRO, 'title' => 'Top 10 des jeux rétro incontournables', 'content' => 'Les jeux rétro ont marqué toute une génération. De Super Mario Bros à The Legend of Zelda, en passant par Mega Man et Metroid, ces classiques restent indémodables. Leur gameplay simple mais efficace, leurs musiques mémorables et leur difficulté légendaire en font des œuvres intemporelles que chaque gamer devrait connaître.', 'picture' => 'https://picsum.photos/seed/retrogaming/800/400', 'category' => 1],
            ['ref' => self::POST_DOCKER, 'title' => 'Configurer Docker pour Symfony', 'content' => 'Docker facilite grandement le développement avec Symfony. Avec un simple docker-compose.yml, vous pouvez configurer PHP, MySQL, Nginx et même phpMyAdmin. L\'avantage principal est la reproductibilité de l\'environnement : tous les développeurs de l\'équipe travaillent dans les mêmes conditions, éliminant le fameux "ça marche sur ma machine".', 'picture' => 'https://picsum.photos/seed/docker/800/400', 'category' => 3],
            ['ref' => self::POST_AI, 'title' => 'Intelligence artificielle : où en est-on en 2026 ?', 'content' => 'L\'intelligence artificielle continue de transformer notre quotidien à une vitesse folle. Des modèles de langage aux outils de génération d\'images, l\'IA s\'infiltre dans tous les secteurs. En entreprise, elle automatise des tâches répétitives, assiste les développeurs dans leur code et révolutionne le service client.', 'picture' => 'https://picsum.photos/seed/ai2026/800/400', 'category' => 0],
            ['ref' => self::POST_API, 'title' => 'Créer une API REST avec Symfony en 30 minutes', 'content' => 'Symfony permet de créer des API REST rapidement et proprement. Grâce à des outils comme API Platform ou simplement les contrôleurs avec JsonResponse, vous pouvez exposer vos données en quelques étapes.', 'picture' => 'https://picsum.photos/seed/apirest/800/400', 'category' => 3],
            ['ref' => self::POST_HABITS, 'title' => '5 habitudes pour devenir un meilleur développeur', 'content' => 'Devenir un bon développeur ne se résume pas à maîtriser un langage. C\'est un état d\'esprit. Lisez du code open source, écrivez des tests, documentez votre code, participez à des communautés et n\'ayez pas peur de casser des choses.', 'picture' => 'https://picsum.photos/seed/devhabits/800/400', 'category' => 2],
        ];

        foreach ($postsData as $i => $data) {
            $post = new Post();
            $post->setTitle($data['title']);
            $post->setContent($data['content']);
            $post->setPicture($data['picture']);
            $post->setAuthor($admin);
            $post->setCategory($categories[$data['category']]);
            $post->setPublishedAt(new \DateTime("-{$i} days"));
            $manager->persist($post);
            $this->addReference($data['ref'], $post);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['posts'];
    }
}