<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookSeries;
use App\Form\BookType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This controller manages books
 */
#[Route('/book')]
final class BookController extends AbstractController
{
    /**
     * Creates a new book
     */
    #[Route('/new/{seriesId}', name: 'book_new')]
    public function index(Request $request, EntityManagerInterface $entityManager, int $seriesId = -1): Response
    {
        // Check if we were given a valid series
        $addToSeries = $entityManager->getRepository(BookSeries::class)->findOneBy(["id" => $seriesId]);

        // Prepare the form to create a new book with
        $newBook = new Book();
        $newBook->setSeries($addToSeries);
        $newBookForm = $this->createForm(BookType::class, $newBook);
        $newBookForm->add('New', SubmitType::class);

        // Check if it was submitted in the request
        $newBookForm->handleRequest($request);
        if ($newBookForm->isSubmitted() && $newBookForm->isValid()) {
            // Save the new book
            $entityManager->persist($newBookForm->getData());
            $entityManager->flush();
            if ($addToSeries instanceof BookSeries) {
                return $this->redirectToRoute("series_show", ["id" => $addToSeries->getId()]);
            } else {
                return $this->redirectToRoute("series_unassigned");
            }
        }

        // Show the form to the user
        $breadcrumbs = [];
        if ($addToSeries instanceof BookSeries) {
            $breadcrumbs[] = ["name" => $addToSeries->getName(), "url" => $this->generateUrl("series_show", ["id" => $addToSeries->getId()])];
            $breadcrumbs[] = ["name" => "New Book", "url" => "#"];
        }
        return $this->render('book/new.html.twig', [
            "page_title"  => "New Book",
            "breadcrumbs" => $breadcrumbs,
            "book_form"   => $newBookForm
        ]);
    }
}
