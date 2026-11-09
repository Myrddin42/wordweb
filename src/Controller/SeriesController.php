<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookSeries;
use App\Form\BookSeriesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This controller manages book series
 */
#[Route(['/series', '/'])]
final class SeriesController extends AbstractController
{
    /**
     * Provides a form so that a user can edit a series
     */
    #[Route('/edit/{id:series}', name: 'series_edit')]
    public function edit(BookSeries $series, Request $request, EntityManagerInterface $entityManager) {
        // Take the supplied series, then put it into the form we use to edit it
        $changeForm = $this->createForm(BookSeriesType::class, $series);
        $changeForm->add('Change', SubmitType::class);

        // Check if the user send us any changes to make
        $changeForm->handleRequest($request);
        if ($changeForm->isSubmitted() && $changeForm->isValid()) {
            // The series should now be changed, so tell the Entity Manager to persist the changes
            $entityManager->flush();
            // Go back to the index page
            return $this->redirectToRoute("series_show", ["id" => $series->getId()]);
        }

        // Show the form to the user
        return $this->render(
            'series/form.html.twig',
            [
                "page_title"  => "Edit Series: " . $series->getName(),
                "breadcrumbs" => [["name" => "Series", "url" => $this->generateUrl("series_index")], ["name" => $series->getName(), "url" => "#"]],
                "series_form"  => $changeForm
            ]
        );
    }

    /**
     * Lists all series
     */
    #[Route('/', name: 'series_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Fetch all series
        $series = $entityManager->getRepository(BookSeries::class)->findAll();

        // Render the page
        return $this->render(
            'series/index.html.twig',
            [
                "page_title"  => "Series",
                "breadcrumbs" => [["name" => "Series", "url" => "#"]],
                "bookSeries"  => $series
            ]
        );
    }

    /**
     * Creates a new series
     */
    #[Route('/new', name: 'series_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Create the form to receive the data about the new series
        $newSeries     = new BookSeries();
        $newSeriesForm = $this->createForm(BookSeriesType::class, $newSeries);
        $newSeriesForm->add('New', SubmitType::class);

        // Check if we got data for that form in the request
        $newSeriesForm->handleRequest($request);
        if ($newSeriesForm->isSubmitted() && $newSeriesForm->isValid()) {
            // Save the new series
            // (Object is being updated, but we explicitly ask for it, just in case)
            $entityManager->persist($newSeriesForm->getData());
            $entityManager->flush();
            // Go back to index page
            // TODO: Go to page for this new series
            return $this->redirectToRoute("series_index");
        }

        return $this->render(
            'series/form.html.twig',
            [
                "page_title"  => "New Series",
                "breadcrumbs" => [["name" => "Series", "url" => $this->generateUrl("series_index")], ["name" => "New", "url" => "#"]],
                "series_form"  => $newSeriesForm
            ]
        );
    }

    /**
     * Removes the given series
     */
    #[Route('/remove/{id:series}', name: 'series_remove')]
    public function remove(BookSeries $series, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the delete parameter, or a good default
        $paramDeleteBooks = $request->query->get("delete_books", 0);
        // Remove the series, and send the user back to the index page
        $entityManager->getRepository(BookSeries::class)->removeSeries($series, $paramDeleteBooks == "1");
        return $this->redirectToRoute("series_index");
    }

    /**
     * Shows the given series in detail
     */
    #[Route('/show/{id:series}', name: 'series_show')]
    public function show(BookSeries $series, Request $request): Response
    {
        return $this->render(
            'series/show.html.twig',
            [
                "page_title"  => "Series: " . $series->getName(),
                "breadcrumbs" => [["name" => "Series", "url" => $this->generateUrl("series_index")], ["name" => $series->getName(), "url" => "#"]],
                "series"      => $series
            ]
        );
    }

    /**
     * Shows all books which do not belong to a series
     */
    #[Route('/unassgined', name: 'series_unassigned')]
    public function unassigned(EntityManagerInterface $entityManager): Response
    {
        // Fetch all books which are not assigned to a series
        $bookRepository = $entityManager->getRepository(Book::class);
        return $this->render(
            'series/unassigned.html.twig',
            [
                "page_title"  => "Unassigned Books",
                "breadcrumbs" => [["name" => "Series", "url" => $this->generateUrl("series_index")], ["name" => "Unassigned Books", "url" => "#"]],
                "books"       => $bookRepository->findAllUnassigned()
            ]
        );
    }
}