<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PostRepository $postRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'latestPosts' => $postRepository->findLatestPosts(6),
            'categories' => $categoryRepository->findAll(),
        ]);
    }
}
