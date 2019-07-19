<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Post;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // utilisation de faker, localisé en français
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i <= 30; $i++) {

            $post = new Post();
            $slugify = new Slugify();

            $title = $faker->sentence();
            $content = $faker->paragraph(5);

            $post->setTitle($title)
                ->setContent($content)
                ->setSlug($slugify->slugify($title));

            for ($j = 0; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();

                $image->setUrl('https://lorempixel.com/output/nature-q-c-640-480-' . mt_rand(1, 10) . '.jpg')
                    ->setpost($post);
                $manager->persist($image);
            }

            $manager->persist($post);
        }
        $manager->flush();
    }
}

