<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;

use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    
    /**
     * @Route("/blog", name="blog_page")
     */
    public function blogs(ArticleRepository $repo): Response
    {
        $articles = $repo->findAll();
        return $this->render('home/blog.html.twig',[
            'les_articles' => $articles
        ]);
    }

    /**
     * @Route("/blog/ajouter", name="new_blog")
     * @Route("/blog/{id}/miseajour", name="update_blog")
     */
    public function add_And_Update_Blog(Article $article = null, HttpFoundationRequest $requette,EntityManagerInterface $manager): Response
    {
        if (!$article) {
            $article = new Article();
        }

        /* $form = $this->createFormBuilder($article)
                ->add('title')
                ->add('content')
                ->add('image')
                ->add('category',EntityType::class,[
                    'class'=> Category::class,
                    'choice_label' =>'title'
                ])
                ->getForm(); */

        $form = $this->createForm(ArticleFormType::class,$article);
        
        $form->handleRequest($requette);

        if ($form->isSubmitted() and $form->isValid()) {
            if (!$article->getId()) {
                $article->setCreatedAt(new \DateTimeImmutable());
            }
            $article->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_by_id',['id'=>$article->getId()]);

        }
        return $this->render('home/ajouterBlog.html.twig',[
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null

        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_by_id")
     */
    public function show(Article $article): Response
    {
        return $this->render('home/show.html.twig',[
            'article' => $article
        ]);
    }
}
