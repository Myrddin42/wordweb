<?php

namespace App\Tests\Repository;

use App\Entity\Book;
use App\Entity\BookSeries;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BookRepositoryTest extends KernelTestCase
{
    /**
     * Tests that we can find all unassigned books, and only them
     */
    public function testFindAllUnassigned(): void
    {
        // Boot the kernel and get the container
        $kernel    = self::bootKernel();
        $container = static::getContainer();
        
        // Get access to the database
        $entityManager = $container->get(EntityManagerInterface::class);

        // Create a test series with a single book, and two test books
        $testSeries = new BookSeries();
        $testSeries->setName("Test Series");
        $testSeries->setDescription("Test Series from which we shouldn't fetch a single book!");
        $entityManager->persist($testSeries);

        $testSeriesBook = new Book();
        $testSeriesBook->setName("Test Serial");
        $testSeriesBook->setSeries($testSeries);
        $entityManager->persist($testSeriesBook);

        $testBookOne = new Book();
        $testBookOne->setName("Test Book One");
        $entityManager->persist($testBookOne);

        $testBookTwo = new Book();
        $testBookTwo->setName("Test Book Two");
        $entityManager->persist($testBookTwo);

        // Make sure evertything goes into the database
        $entityManager->flush();

        // Get access to the book repository, and ask it for all unassigned books
        $unassignedBooks = $entityManager->getRepository(Book::class)->findAllUnassigned();

        // Perform tests
        $this->assertTrue(in_array($testBookOne, $unassignedBooks), "The first test book is not assigned to a series, it should've been found!");
        $this->assertTrue(in_array($testBookTwo, $unassignedBooks), "The second test book is not assigned to a series, it should've been found!");
        $this->assertFalse(in_array($testSeriesBook, $unassignedBooks), "The book is the series has, by definition, a series, so why is it here?");
    }
}
