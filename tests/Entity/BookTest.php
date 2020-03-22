<?php

declare(strict_types=1);

namespace App\Entity;

use PHPUnit\Framework\TestCase;

final class BookTest extends TestCase
{
    public function testBook()
    {
        $book = new Book();
        $book->setTitle('My title');
        $book->setAuthor('My author');
        $book->setReleaseDate(new \DateTimeImmutable('2015-05-02'));

        $this->assertEquals('My title', $book->getTitle());
        $this->assertEquals('My author', $book->getAuthor());
        $this->assertEquals('02-05-2015', $book->getReleaseDate()->format('d-m-Y'));
    }
}
