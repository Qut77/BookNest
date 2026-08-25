<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

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
        return $this->render('book/index.html.twig', [
            'books' => $pagination,
        ]);
    }

    #[Route('/add', name:'Add_book')]
    public function Add(Request $request, EntityManagerInterface $em):Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
            {
                $em->persist($book);
                $em->flush();

                return $this->redirectToRoute('app_book');
            }
        
        return $this->render('book/add.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/update/{id}', name:'Upd_book')]
    public function edit(Request $request, EntityManagerInterface $em, Book $book):Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
            {
                $em->flush();
                return $this->redirectToRoute('app_book');
            }
        
        return $this->render('book/add.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name:'Del_book', methods:['POST'])]
    public function delete(Request $request, EntityManagerInterface $em, Book $book):Response
    {
        $token = $request->getPayload()->get('_token');
        if (!$this->isCsrfTokenValid('my-token-id', $token)) {
            throw new InvalidCsrfTokenException('Неверный CSRF токен');
        }
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('app_book');
        
    }
}
