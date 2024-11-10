<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $product = new Product();
        $product->setName('Lightwhip');
        $product->setSlug($this->slugger->slug($product->getName())->lower());
        $product->setDescription('The lightwhip is an exotic variation of the lightsaber that only specially-trained Jedi can wield. It is a rare weapon, even among the Jedi.');
        $product->setPrice(rand(100, 1000));
        $product->setStock(rand(0, 100));
        $category = $this->getReference('category-2');
        $product->setCategory($category);
        $this->setReference('product-1', $product);
        $manager->persist($product);

        $product = new Product();
        $product->setName('E-10 blaster rifle');
        $product->setSlug($this->slugger->slug($product->getName())->lower());
        $product->setDescription('The E-10 blaster rifle was a model of blaster rifle manufactured by BlasTech Industries used by Imperial Army troopers and Imperial Stormtroopers of the Galactic Empire.');
        $product->setPrice(rand(100, 1000));
        $product->setStock(rand(0, 100));
        $category = $this->getReference('category-3');
        $product->setCategory($category);
        $this->setReference('product-2', $product);
        $manager->persist($product);

        $product = new Product();
        $product->setName('Wookiee Crossbow');
        $product->setSlug($this->slugger->slug($product->getName())->lower());
        $product->setDescription('The Wookiee Crossbow, also known as the Wookiee Bowcaster, was a type of traditional Wookiee projectile weapon used by the Wookiees of Kashyyyk.');
        $product->setPrice(rand(100, 1000));
        $product->setStock(rand(0, 100));
        $category = $this->getReference('category-4');
        $product->setCategory($category);
        $this->setReference('product-3', $product);
        $manager->persist($product);

        $product = new Product();
        $product->setName('Jedi Knight Robe');
        $product->setSlug($this->slugger->slug($product->getName())->lower());
        $product->setDescription('The Jedi Knight robe was a traditional garment worn by the Jedi Order. It was a simple, loose-fitting, hooded robe that fell to the floor, with wide sleeves and a belt.');
        $product->setPrice(rand(100, 1000));
        $product->setStock(rand(0, 100));
        $category = $this->getReference('category-6');
        $product->setCategory($category);
        $this->setReference('product-4', $product);
        $manager->persist($product);

        $product = new Product();
        $product->setName('Sith apparel');
        $product->setSlug($this->slugger->slug($product->getName())->lower());
        $product->setDescription('Sith apparel was the term used to describe the clothing worn by members of the Sith Order. Sith apparel was typically black or dark in color, and often consisted of flowing robes and cloaks.');
        $product->setPrice(rand(100, 1000));
        $product->setStock(rand(0, 100));
        $category = $this->getReference('category-7');
        $product->setCategory($category);
        $this->setReference('product-5', $product);
        $manager->persist($product);

        $product = new Product();
        $product->setName('Mandalorian armor');
        $product->setSlug($this->slugger->slug($product->getName())->lower());
        $product->setDescription('Mandalorian armor referred to the traditional armor worn by the human warrior clans of the planet Mandalore. The armor was made from beskar, a nearly indestructible iron alloy.');
        $product->setPrice(rand(100, 1000));
        $product->setStock(rand(0, 100));
        $category = $this->getReference('category-8');
        $product->setCategory($category);
        $this->setReference('product-6', $product);
        $manager->persist($product);

        $product = new Product();
        $product->setName('X-wing Pilot Suit');
        $product->setSlug($this->slugger->slug($product->getName())->lower());
        $product->setDescription('The X-wing pilot suit was the standard flight suit worn by Rebel Alliance starfighter pilots during the Galactic Civil War. The suit was designed to protect the pilot in the event of a crash or ejection.');
        $product->setPrice(rand(100, 1000));
        $product->setStock(rand(0, 100));
        $category = $this->getReference('category-9');
        $product->setCategory($category);
        $this->setReference('product-7', $product);
        $manager->persist($product);

        $product = new Product();
        $product->setName('Imperial Officer Uniform');
        $product->setSlug($this->slugger->slug($product->getName())->lower());
        $product->setDescription('The Imperial officer uniform was the standard attire worn by officers of the Imperial Navy, Army, and Stormtrooper Corps. The uniform was a black tunic with a matching cap, trousers, and boots.');
        $product->setPrice(rand(100, 1000));
        $product->setStock(rand(0, 100));
        $category = $this->getReference('category-10');
        $product->setCategory($category);
        $this->setReference('product-8', $product);
        $manager->persist($product);

        $product = new Product();
        $product->setName('Stormtrooper armor');
        $product->setSlug($this->slugger->slug($product->getName())->lower());
        $product->setDescription('The stormtrooper armor was the standard armor worn by the Imperial stormtrooper. A white plastoid composite worn over a black body glove, the armor included a helmet, shoulder pauldrons, and wielded a blaster rifle.');
        $product->setPrice(rand(100, 1000));
        $product->setStock(rand(0, 100));
        $category = $this->getReference('category-11');
        $product->setCategory($category);
        $this->setReference('product-9', $product);
        $manager->persist($product);

        $product = new Product();
        $product->setName('R1 Bl Rebel Soldier Uniform');
        $product->setSlug($this->slugger->slug($product->getName())->lower());
        $product->setDescription('The R1 Bl Rebel Soldier Uniform was the standard attire worn by Rebel Alliance soldiers during the Galactic Civil War. The uniform was a khaki tunic with a matching cap, trousers, and boots.');
        $product->setPrice(rand(100, 1000));
        $product->setStock(rand(0, 100));
        $category = $this->getReference('category-12');
        $product->setCategory($category);
        $this->setReference('product-10', $product);
        $manager->persist($product);

        $manager->flush();
    }
}
