<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Service\MailerService;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/api', name: 'api_')]
class PostController extends AbstractController
{
    #[Route('/posts', name: 'app_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): JsonResponse
    {
        $posts = $postRepository->findAll();
        $data = [];
   
        foreach ($posts as $post) {
           $data[] = [
               'id' => $post->getId(),
               'name' => $post->getName()
           ];
        }
   
        return $this->json($data);
    }

    #[Route('/posts', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,MailerService $serviceMailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        //$form->handleRequest($request);
        $form->submit($data);
        $entityManager->persist($post);
        $entityManager->flush();
        $data =  [
            'id' => $post->getId(),
            'name' => $post->getName(),
        ];
          
        $to= "team@devphantom";
        $sujet="notification création";
        $content="Un post a été créé avec succé";

        $serviceMailer->sendEmail($to,$sujet,$content);

        return $this->json($data);
        
    }

    #[Route('/posts/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(PostRepository $postRepository, int $id): JsonResponse
    {
        $post = $postRepository->findOneBy(['id'=>$id]);
        if (!$post) {
   
            return $this->json('No post found for id ' , 404);
        }
   
        $data =  [
            'id' => $post->getId(),
            'name' => $post->getName(),
        ];
           
        return $this->json($data);
    }

    

    
}
