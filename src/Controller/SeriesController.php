<?php

namespace App\Controller;

use App\Entity\BookSeries;
use App\Form\BookSeriesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This controller manages book series
 */
#[Route(['/series', '/'])]
class SeriesController extends AbstractController
{
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
                "page_title" => "Series",
                "breadcrumbs" => [["name" => "Series", "url" => "#"]],
                "bookSeries" => $series
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
            'series/new.html.twig',
            [
                "page_title" => "New Series",
                "breadcrumbs" => [["name" => "Series", "url" => $this->generateUrl("series_index")], ["name" => "New", "url" => "#"]],
                "newSeriesForm" => $newSeriesForm
            ]
        );
    }

    /**
     * Removes the given series
     */
    #[Route('/remove/{id:series}')]
    public function remove(BookSeries $series, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the delete parameter, or a good default
        $paramDeleteBooks = $request->query->get("delete_books", 0);
        // Remove the series, and send the user back to the index page
        $entityManager->getRepository(BookSeries::class)->removeSeries($series, $paramDeleteBooks == "1");
        return $this->redirectToRoute("series_index");
    }
}