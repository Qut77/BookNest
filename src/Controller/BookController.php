<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Book;
use App\Repository\BookRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

final class BookController extends AbstractController
{
    #[Route('/', name: 'app_book')]
    public function index(Request $request, BookRepository $bookRepository, PaginatorInterface $paginator): Response
    {
        $query = $bookRepository->getAllBooksQuery();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );
        $books = $bookRepository ->findAll();
        return $this->render('book/index.html.twig', [
            'books' => $pagination,
        ]);
    }
}
