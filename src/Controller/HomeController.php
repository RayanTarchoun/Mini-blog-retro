<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
#[Route('/', name: 'app_home')]
public function index(PostRepository $postRepository, CategoryRepository $categoryRepository, Request $request, PaginatorInterface $paginator): Response
{
    $query = $postRepository->createQueryBuilder('p')
        ->orderBy('p.publishedAt', 'DESC')
        ->getQuery();

    $latestPosts = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
        6
    );

    return $this->render('home/index.html.twig', [
        'latestPosts' => $latestPosts,
        'categories' => $categoryRepository->findAll(),
    ]);
}
}
