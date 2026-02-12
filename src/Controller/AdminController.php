<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard')]
    public function dashboard(
        PostRepository $postRepository,
        UserRepository $userRepository,
        CommentRepository $commentRepository,
        CategoryRepository $categoryRepository
    ): Response {
        return $this->render('admin/dashboard.html.twig', [
            'totalPosts' => count($postRepository->findAll()),
            'totalUsers' => count($userRepository->findAll()),
            'totalComments' => count($commentRepository->findAll()),
            'totalCategories' => count($categoryRepository->findAll()),
            'latestPosts' => $postRepository->findLatestPosts(5),
            'pendingComments' => $commentRepository->findPendingComments(),
        ]);
    }

    // ========== GESTION DES UTILISATEURS ==========

    #[Route('/users', name: 'app_admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $userRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/users/{id}/toggle', name: 'app_admin_user_toggle', methods: ['POST'])]
    public function toggleUser(User $user, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('toggle' . $user->getId(), $request->request->get('_token'))) {
            $user->setIsActive(!$user->isActive());
            $user->setUpdatedAt(new \DateTime());
            $entityManager->flush();

            $status = $user->isActive() ? 'activé' : 'désactivé';
            $this->addFlash('success', "Compte de {$user->getFullName()} {$status}.");
        }

        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/users/{id}/promote', name: 'app_admin_user_promote', methods: ['POST'])]
    public function promoteUser(User $user, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('promote' . $user->getId(), $request->request->get('_token'))) {
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                $user->setRoles(['ROLE_USER']);
                $this->addFlash('success', "{$user->getFullName()} n'est plus administrateur.");
            } else {
                $user->setRoles(['ROLE_ADMIN']);
                $this->addFlash('success', "{$user->getFullName()} est maintenant administrateur.");
            }
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_users');
    }

    // ========== GESTION DES ARTICLES ==========

    #[Route('/posts', name: 'app_admin_posts')]
    public function posts(PostRepository $postRepository): Response
    {
        return $this->render('admin/posts.html.twig', [
            'posts' => $postRepository->findBy([], ['publishedAt' => 'DESC']),
        ]);
    }

    // ========== GESTION DES CATÉGORIES ==========

    #[Route('/categories', name: 'app_admin_categories')]
    public function categories(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/categories.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/categories/new', name: 'app_admin_category_new', methods: ['GET', 'POST'])]
    public function newCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Catégorie créée !');
            return $this->redirectToRoute('app_admin_categories');
        }

        return $this->render('admin/category_form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Nouvelle catégorie',
        ]);
    }

    #[Route('/categories/{id}/edit', name: 'app_admin_category_edit', methods: ['GET', 'POST'])]
    public function editCategory(Category $category, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Catégorie modifiée !');
            return $this->redirectToRoute('app_admin_categories');
        }

        return $this->render('admin/category_form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier la catégorie',
        ]);
    }

    #[Route('/categories/{id}/delete', name: 'app_admin_category_delete', methods: ['POST'])]
    public function deleteCategory(Category $category, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            if ($category->getPosts()->count() > 0) {
                $this->addFlash('error', 'Impossible de supprimer une catégorie qui contient des articles.');
            } else {
                $entityManager->remove($category);
                $entityManager->flush();
                $this->addFlash('success', 'Catégorie supprimée.');
            }
        }

        return $this->redirectToRoute('app_admin_categories');
    }

    // ========== GESTION DES COMMENTAIRES ==========

    #[Route('/comments', name: 'app_admin_comments')]
    public function comments(CommentRepository $commentRepository): Response
    {
        return $this->render('admin/comments.html.twig', [
            'comments' => $commentRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/comments/{id}/approve', name: 'app_admin_comment_approve', methods: ['POST'])]
    public function approveComment(Comment $comment, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('approve' . $comment->getId(), $request->request->get('_token'))) {
            $comment->setStatus('approved');
            $entityManager->flush();
            $this->addFlash('success', 'Commentaire approuvé.');
        }

        return $this->redirectToRoute('app_admin_comments');
    }

    #[Route('/comments/{id}/reject', name: 'app_admin_comment_reject', methods: ['POST'])]
    public function rejectComment(Comment $comment, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('reject' . $comment->getId(), $request->request->get('_token'))) {
            $comment->setStatus('rejected');
            $entityManager->flush();
            $this->addFlash('success', 'Commentaire rejeté.');
        }

        return $this->redirectToRoute('app_admin_comments');
    }

    #[Route('/comments/{id}/delete', name: 'app_admin_comment_delete', methods: ['POST'])]
    public function deleteComment(Comment $comment, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Commentaire supprimé.');
        }

        return $this->redirectToRoute('app_admin_comments');
    }
}
