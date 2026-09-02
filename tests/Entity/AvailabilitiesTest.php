<?php

namespace App\Tests\Entity;

use App\Entity\Availabilities;
use App\Entity\Business;
use DateTime;
use PHPUnit\Framework\TestCase;

class AvailabilitiesTest extends TestCase {
    public function testSettersAreFluentAndGettersReturnTheSameValues(): void
    {
        $business = new Business();
        $date = new DateTime('2026-08-28 17:15:00');

        $Availability = (new Availabilities())
            ->setStartDate($date)
            ->setBusiness($business);

        $this->assertSame($date, $Availability->getStartDate());
        $this->assertSame($business, $Availability->getBusiness());
    }

    public function testSetBusinessToNullClearsTheBusiness(): void
    {
        $availability = (new Availabilities())->setBusiness(new Business());

        $availability->setBusiness(null);

        $this->assertNull($availability->getBusiness());
    }
}