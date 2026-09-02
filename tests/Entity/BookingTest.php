<?php

namespace App\Tests\Entity;

use App\Entity\Availabilities;
use App\Entity\Booking;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class BookingTest extends TestCase
{
    public function testSettersAreFluentAndGettersReturnTheSameValues(): void
    {
        $availability = new Availabilities();
        $client = new User();

        $booking = (new Booking())
            ->setPurpose('Coupe et brushing')
            ->setIsValidated(true)
            ->setAvailabilites($availability)
            ->setClient($client);

        $this->assertSame('Coupe et brushing', $booking->getPurpose());
        $this->assertTrue($booking->isValidated());
        $this->assertSame($availability, $booking->getAvailabilites());
        $this->assertSame($client, $booking->getClient());
    }

    public function testSetClientToNullClearsTheClient(): void
    {
        $booking = (new Booking())->setClient(new User());

        $booking->setClient(null);

        $this->assertNull($booking->getClient());
    }
}
