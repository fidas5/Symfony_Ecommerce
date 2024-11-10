<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture
{
    private $count = 1;
    public function __construct(private SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $parent = $this->createCategory('Weapons', null, $manager); #1

        $this->createCategory(name: 'Light Saber', parent: $parent, manager: $manager); #2
        $this->createCategory(name: 'Blaster', parent: $parent, manager: $manager); #3
        $this->createCategory(name: 'Bowcaster', parent: $parent, manager: $manager); #4

        $parent = $this->createCategory('Clothing', null, $manager); #5

        $this->createCategory(name: 'Jedi Robes', parent: $parent, manager: $manager); #6
        $this->createCategory(name: 'Sith Robes', parent: $parent, manager: $manager); #7
        $this->createCategory(name: 'Bounty Hunter Armor', parent: $parent, manager: $manager); #8
        $this->createCategory(name: 'Rebel Pilot Suit', parent: $parent, manager: $manager); #9
        $this->createCategory(name: 'Imperial Uniform', parent: $parent, manager: $manager); #10
        $this->createCategory(name: 'Stormtrooper Armor', parent: $parent, manager: $manager); #11
        $this->createCategory(name: 'Rebel Soldier Uniform', parent: $parent, manager: $manager); #12

        $manager->flush();
    }

    public function createCategory(string $name, Category $parent = null, ObjectManager $manager)
    {
        $category = new Category();
        $category->setName($name);
        $category->setSlug($this->slugger->slug($category->getName())->lower());
        $category->setParent($parent);
        $category->setCategoryOrder($this->count);

        $manager->persist($category);

        $this->addReference('category-'.$this->count, $category);
        $this->count++;

        return $category;
    }
}
