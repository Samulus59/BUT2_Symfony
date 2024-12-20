<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'titre' => 'Article'
        ]);

    }

    #[Route('article/creer',name:'app_article_creer')]
    #[IsGranted('ROLE_USER')]
    public function create(EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/brochures')] string $dossierImages
    ) : Response
    {
        $article = new Article();

        $article->setDate(new \DateTimeImmutable());

        $form = $this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {

            $image = $form->get('image')->getData();

            if ($image) 
            {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
            }

            try 
            {
                $image->move($dossierImages, $newFilename);
                $article->setImage($newFilename);
            } 
            catch (FileException $e) 
            {
                // ... handle exception if something happens during file upload
            }
            
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Article Créé!');
            return $this->redirectToRoute('app_article_liste');
        }

        return $this->render('article/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('article/liste',name:'app_article_liste')]
    public function show(EntityManagerInterface $entityManager):Response
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found'
            );
        }
    
        return $this->render('article/liste.html.twig', [
            'articles' => $articles
    ]);
    }

    #[Route('article/{id}', name: 'app_article_show')]
public function showOne(EntityManagerInterface $entityManager, int $id): Response
{
    $article = $entityManager->getRepository(Article::class)->find($id);

    if (!$article) {
        throw $this->createNotFoundException('L\'article demandé n\'existe pas.');
    }

    return $this->render('article/show.html.twig', [
        'article' => $article
    ]);
}


    #[Route('article/update/{id}', name: 'app_article_update')]
    #[IsGranted('ROLE_USER')]
    public function update(EntityManagerInterface $entityManager, Request $request, int $id, SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/brochures')] string $dossierImages): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);
    
        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }
        if ($article->getImage()) {
            $imagePath = $dossierImages . '/' . $article->getImage();
        
            if (file_exists($imagePath)) 
            {
                $article->setImage(new File($imagePath));
            } 
            else
            {

            }
        }
    
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();
    
                try 
                {
                    $image->move($dossierImages, $newFilename);
                    if ($article->getImage() instanceof File) 
                    {
                        $ancienneImagePath = $dossierImages . '/' . $article->getImage()->getFilename();
                        if (file_exists($ancienneImagePath)) 
                        {
                            unlink($ancienneImagePath);
                        }
                    }
                    $article->setImage($newFilename);
                } 
                catch (FileException $e) 
                {
                    
                }
            }
    
            $entityManager->flush();
            $this->addFlash('success', 'Article Modifié!');
            return $this->redirectToRoute('app_article_liste');
        }
    
        return $this->render('article/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    

    #[Route('article/delete/{id}',name:'app_article_delete')]
    #[IsGranted('ROLE_USER')]
    public function delete(EntityManagerInterface $entityManager, int $id):Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'article not found'
            );
        }

        $entityManager->remove($article);
        $entityManager->flush();
        $this->addFlash('success', 'Article Supprimmé!');
    
        return $this->redirectToRoute('app_article_liste');
    }
}
