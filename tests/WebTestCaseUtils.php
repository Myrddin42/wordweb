<?php

namespace App\Tests;

use App\Entity\Book;
use App\Entity\BookSeries;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WebTestCaseUtils extends WebTestCase
{
    /**
     * Helper to create a new book via web request. It tests that everything was created correctly, and will error out if told to create a duplicate.
     */
    protected function helpTestCreateBook(KernelBrowser $client, string $newBookName, string $newBookDescription = "", ?BookSeries $addToSeries = null): Book
    {
        // Preset stuff depending on if a series was given or not
        $bookSearch = ["name" => $newBookName];
        $bookNewUrl = "/book/new";
        if ($addToSeries instanceof BookSeries) {
            $bookSearch["series"] = $addToSeries;
            $bookNewUrl . sprintf('/%d', $addToSeries->getId());
        }

        // Make sure that there is no such book yet
        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        $bookNull = $entityManager->getRepository(Book::class)->findOneBy($bookSearch);
        $this->assertNull($bookNull, "There already is a book with that name! Duplicates are okay generally, but not for tests.");

        // Fetch the form to create a new book, then submit it
        $client->request('GET', $bookNewUrl);
        $client->submitForm(
            "New",
            [
                "book[name]"        => $newBookName,
                "book[description]" => $newBookDescription,
                "book[series]"      => $addToSeries instanceof BookSeries ? $addToSeries->getId() : ""
            ]
        );

        // Make sure that the book was created, then return it
        $newBook = $entityManager->getRepository(Book::class)->findOneBy($bookSearch);
        $this->assertNotNull($newBook, "The book should now exist, but it doesn't?");
        $this->assertEquals($newBookDescription, $newBook->getDescription(), "The book was created with an incorrect description!");

        // Doctrine fetches the relation lazy, and ->getSeries() would give us only the id, which causes the assert to fail when compared
        // against $addToSeries itself
        if ($addToSeries instanceof BookSeries) {
            $this->assertEquals($addToSeries->getId(), $newBook->getSeries()->getId(), "The book was attached to the wrong series!");
        } else {
            $this->assertNull($newBook->getSeries(), "We didn't give a series, so why does the book have one?");
        }

        return $newBook;
    }

    /**
     * Helper to create a new book series with the given details (via web request), and test that it was created successfully
     * 
     * @param KernelBrowser $client               The "web" client we use to make web requests to the application
     * @param string        $newSeriesName        The name of the new series
     * @param string        $newSeriesDescription The description of the new series
     * @return BookSeries                         The newly created book series
     */
    protected function helpTestCreateSeries(KernelBrowser $client, string $newSeriesName, string $newSeriesDescription = ""): BookSeries
    {
        // Make sure that there is no such series yet
        $client->request('GET', '/series/');
        $this->assertResponseIsSuccessful();
        $this->assertAnySelectorTextNotContains('a', $newSeriesName, "The new series should not be there yet, did you forget to clear the database?");

        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        $bookSeries = $entityManager->getRepository(BookSeries::class)->findOneBy(["name" => $newSeriesName]);
        $this->assertNull($bookSeries, "The new book series should not yet be in the database!");

        // Try to submit the "form" to create the new series
        $client->request('GET', '/series/new');
        $client->submitForm(
            "New",
            [
                "book_series[name]"        => $newSeriesName,
                "book_series[description]" => $newSeriesDescription
            ]
        );

        // The new series should be there now
        $client->request('GET', '/series/');
        $this->assertResponseIsSuccessful();
        $this->assertAnySelectorTextContains('a', $newSeriesName, "The new series should've been created successfully!");

        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        $bookSeries = $entityManager->getRepository(BookSeries::class)->findOneBy(["name" => $newSeriesName]);
        $this->assertInstanceOf(BookSeries::class, $bookSeries                  , "The new book series was not found in the database!");
        $this->assertEquals($newSeriesDescription, $bookSeries->getDescription(), "The description of the new book series should match the given one!");

        // Return the new series so that the invoker can work with it
        return $bookSeries;
    }
}