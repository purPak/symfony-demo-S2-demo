<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

class ArticleController extends AbstractFOSRestController
{

    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /* Find ALL article */

    /**
     * @Rest\Get("/", name="articles")
     */
    public function getApiArticles(){
        $article = $this->articleRepository->findAll();
        return $this->render('article/index.html.twig',[
            'articles' => $article
        ]);
    }

    /* Find ALL article SERIALIZED [id] */

    /**
     * @Rest\Get("/api/articless")
     * @Rest\View(serializerGroups={"article"})
     */
    public function getApiArticlesById(){
        $articles = $this->articleRepository->findAll();
        return $this->view($articles);
    }

    /* Find a article by NAME */

    /**
     * @Rest\Get("/api/articles/{name}")
     */
    public function getApiArticlesByName(Article $articles){
        return $this->view($articles);
    }

    /* PATCH an article [description] */

    /**
     * @Rest\Patch("/api/articles/{name}")
     */
    public function patchApiArticle(Article $article, Request $request){
        $attributeName = ['description' => 'setDescription'];
        foreach ($attributeName as $key => $setterName) {
            if ($request->get($key) === null) {
                continue;
            }
            $article->$setterName($request->get($key));
        }

        $this->getDoctrine()->getManager()->flush();
        return $this->view($article);
    }

    /* Create a New article */
    /**
     * @Rest\Route("/articles/new")
     */
    public function newApiArticle(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('article/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /* POST an article */

    /**
     * @Rest\Post("/api/articles")
     * @ParamConverter("article", converter="fos_rest.request_body")
     */
    public function postApiArticle(Article $article){
        $this->em->persist($article);$this->em->flush();
        return $this->view($article);
    }

    /* DELETE an article */

    /**
     * @Rest\Delete("/api/articles/{name}")
     */
    public function deleteApiArticle(Article $article){
        $this->getDoctrine()->getManager()->remove($article);
        $this->getDoctrine()->getManager()->flush();
        return $this->view($article);
    }
}

