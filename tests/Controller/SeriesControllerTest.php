<?php

namespace App\Tests\Controller;

use App\Entity\BookSeries;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SeriesControllerTest extends WebTestCase
{
    /**
     * Helper to create a new book series with the given details (via web request), and test that it was created successfully
     * 
     * @param KernelBrowser $client               The "web" client we use to make web requests to the application
     * @param string        $newSeriesName        The name of the new series
     * @param string        $newSeriesDescription The description of the new series
     * @return BookSeries                         The newly created book series
     */
    private function helpTestCreateSeries(KernelBrowser $client, string $newSeriesName, string $newSeriesDescription = ""): BookSeries
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
                "book_series[name]" => $newSeriesName,
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
        $this->assertInstanceOf(BookSeries::class, $bookSeries, "The new book series was not found in the database!");
        $this->assertEquals($newSeriesName, $bookSeries->getName(), "The name of the new book series should match the given one!");
        $this->assertEquals($newSeriesDescription, $bookSeries->getDescription(), "The description of the new book series should match the given one!");

        // Return the new series so that the invoker can work with it
        return $bookSeries;
    }

    /**
    * Ensures that the index page loads successfully, and shows us at least the "unassigned" series
    */
    public function testEmptyHasUnassigned(): void
    {
        $client = static::createClient();
        $client->request('GET', '/series/');
        $this->assertResponseIsSuccessful();
        $this->assertAnySelectorTextContains('a', 'Unassigned');
    }

    /**
     * Tests that we can create a series with the given description
     */
    public function testCreateSeriesWithDescription(): void
    {
        $this->helpTestCreateSeries(static::createClient(), "Strength, Guile, and Truth", "A wonderful trilogy, which I'll hopefully have finished writing soon.");
    }

    /**
     * Tests that we can create a series with an empty description
     */
    public function testCreateSeriesWithoutDescription(): void
    {
        $this->helpTestCreateSeries(static::createClient(), "Strength, Guile, and Truth");
    }

    /**
     * Tests that we can remove a series if needed
     */
    public function testRemoveSeries(): void
    {
        // Create the series first
        $seriesName = "Alte Nacht, Alter Schrecken";
        $client = static::createClient();
        $client->followRedirects();
        $bookSeries = $this->helpTestCreateSeries($client, $seriesName);
        // Get the id, and throw the reference away (I'm not sure if it may create problems with the entity manager)
        $seriesId = $bookSeries->getId();
        $bookSeries = null;

        // Then try to delete it
        $client->request('GET', sprintf('/series/remove/%d', $seriesId));
        $this->assertResponseIsSuccessful();

        // Fact check
        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        $shouldBeNull = $entityManager->getRepository(BookSeries::class)->findOneBy(["name" => $seriesName]);
        $this->assertNull($shouldBeNull, "The series was deleted successfully (according to the HTTP status code), so why is it still in the database?");
    }
}