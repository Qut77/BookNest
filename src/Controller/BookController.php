<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Book;
use App\Repository\BookRepository;

final class BookController extends AbstractController
{
    #[Route('/', name: 'app_book')]
    public function index(EntityManagerInterface $em, BookRepository $bookRepository): Response
    {
        $books = $bookRepository ->findAll();
        return $this->render('book/index.html.twig', [
            'books' => $books,
        ]);
    }
}
