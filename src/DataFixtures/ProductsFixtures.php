<?php

namespace App\DataFixtures;

use App\Entity\Products;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductsFixtures extends Fixture
{

    public function __construct(private SluggerInterface $slugger) {}
    
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($prod = 1; $prod <= 10; $prod++){
            $product = new Products();
            $product->setName($faker->text(5));
            $product->setDescription($faker->text());
            $product->setSlug(
                $this->slugger->slug($product->getName())->lower()  
            );
            $product->setPrice($faker->numberBetween(900, 150000));
            $product->setStock($faker->numberBetween(0,10));
            $category = $this->getReference('cat-'.rand(1,8));
            $product->setCategories($category);
            $manager->persist($product);
            $this->addReference('prod-'.$prod,$product);
        }
        
        $manager->flush();
    }
}
