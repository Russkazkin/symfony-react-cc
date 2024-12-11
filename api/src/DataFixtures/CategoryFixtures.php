<?php

namespace App\DataFixtures;

use App\Entity\Category;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    private const CATEGORIES = [
        [
            'name' => 'Автомобили',
            'slug' => 'auto',
            'description' => 'Ut id nisl quis enim dignissim sagittis.. Suspendisse potenti. Fusce fermentum. Pellentesque egestas, neque sit amet convallis pulvinar, justo nulla eleifend augue, ac auctor orci leo non est.',
            'sort' => 10,
        ],
        [
            'name' => 'Мотоциклы',
            'slug' => 'moto',
            'description' => 'Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Etiam feugiat lorem non metus. In hac habitasse platea dictumst. Maecenas nec odio et ante tincidunt tempus. Aenean tellus metus, bibendum sed, posuere ac, mattis non, nunc.',
            'sort' => 20,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $categoryData) {
            $category = new Category();
            $category->setName($categoryData['name']);
            $category->setSlug($categoryData['slug']);
            $category->setDescription($categoryData['description']);
            $category->setSort($categoryData['sort']);
            $manager->persist($category);
        }
        $manager->flush();
    }
}
