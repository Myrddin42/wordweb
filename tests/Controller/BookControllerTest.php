<?php

namespace App\Tests\Controller;

use App\Tests\WebTestCaseUtils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCaseUtils
{
    /**
     * Tests that we can create new books via web requests
     */
    public function testNewBookAssigned(): void
    {
        // Get a client to do requests with
        $client = static::createClient();

        // Create a series we can attach the books to
        $testSeries = self::helpTestCreateSeries($client, "Test Series", "The mother that holds all test books of this test.");

        // Try to add books to that series
        self::helpTestCreateBook($client, "Test Book"  , ""                   , $testSeries);
        self::helpTestCreateBook($client, "Test Book 2", "With description!!!", $testSeries);
    }

    /**
     * Tests that we can create new books via web requests
     */
    public function testNewBookUnassigned(): void
    {
        // Tests that we can create books with or without descriptions
        $client = static::createClient();
        self::helpTestCreateBook($client, "Test Book");
        self::helpTestCreateBook($client, "Test Book 2", "With description!!!");
    }
}
