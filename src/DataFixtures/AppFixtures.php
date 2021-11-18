<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('ar_SA');

        for ($i=0; $i <10 ; $i++) { 
            $dateCreatCategory = \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-3 years'));
            $category = new Category();
            $category->setTitle($faker->company)
                    ->setDescription($faker->paragraph())
                    ->setCreatedAt($dateCreatCategory);
            $manager->persist($category);

                for ($j=1; $j <mt_rand(6,15) ; $j++) {

                    $dateCreatArticle = $dateCreatCategory->diff(new \DateTime());

                    $dateCreatArticle_days ='-'.strval($dateCreatArticle ->days).' days';

                    $article = new Article();
                    $article->setTitle($faker->sentence(3,true))
                            ->setImage($faker->imageUrl())
                            ->setContent($faker->paragraph(2))
                            ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween($dateCreatArticle_days)))
                            ->setCategory($category);
                    $manager->persist($article);

                        for ($k=0; $k < mt_rand(12,100) ; $k++) {

                            $dateCreatComment = $article->getCreatedAt()->diff(new \DateTime());
                            $dateCreatComment_days = '-'.strval($dateCreatComment->days).' days';
                            
                            $comment = new Comment();
                            $comment->setAuthor($faker->name())
                                    ->setContent($faker->paragraph(3))
                                    ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween($dateCreatComment_days)))
                                    ->setArticle($article);
                            $manager->persist($comment);
                        }
                }
        }
        $manager->flush();
    }
}
