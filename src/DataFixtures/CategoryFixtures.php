<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORY_TECH = 'category-tech';
    public const CATEGORY_GAMING = 'category-gaming';
    public const CATEGORY_LIFESTYLE = 'category-lifestyle';
    public const CATEGORY_TUTORIELS = 'category-tutoriels';

    public function load(ObjectManager $manager): void
    {
        $categoriesData = [
            [self::CATEGORY_TECH, 'Technologie', 'Articles sur la tech, le code et l\'innovation.'],
            [self::CATEGORY_GAMING, 'Gaming', 'Actualités et tests de jeux vidéo.'],
            [self::CATEGORY_LIFESTYLE, 'Lifestyle', 'Mode de vie, conseils et astuces.'],
            [self::CATEGORY_TUTORIELS, 'Tutoriels', 'Guides et tutoriels pas à pas.'],
        ];

        foreach ($categoriesData as [$reference, $name, $description]) {
            $category = new Category();
            $category->setName($name);
            $category->setDescription($description);
            $manager->persist($category);

            $this->addReference($reference, $category);
        }

        $manager->flush();
    }
}
