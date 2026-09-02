<?php

namespace App\Tests\Entity;

use App\Entity\Availabilities;
use App\Entity\Business;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class BusinessTest extends TestCase
{
    public function testSettersAreFluentAndGettersReturnTheSameValues(): void
    {
        $manager = new User();

        $business = (new Business())
            ->setName('Le Bon Salon')
            ->setAddress('12 rue des Lilas')
            ->setDescription('Salon de coiffure')
            ->setManager($manager);

        $this->assertSame('Le Bon Salon', $business->getName());
        $this->assertSame('12 rue des Lilas', $business->getAddress());
        $this->assertSame('Salon de coiffure', $business->getDescription());
        $this->assertSame($manager, $business->getManager());
    }

    public function testNewBusinessHasNoAvailabilities(): void
    {
        $business = new Business();

        $this->assertCount(0, $business->getAvailabilities());
    }

    public function testAddAvailabilitySetsBothSidesOfTheRelation(): void
    {
        $business = new Business();
        $availability = new Availabilities();

        $business->addAvailability($availability);

        $this->assertCount(1, $business->getAvailabilities());
        $this->assertTrue($business->getAvailabilities()->contains($availability));
        $this->assertSame($business, $availability->getBusiness());
    }

    public function testAddingTheSameAvailabilityTwiceDoesNotDuplicateIt(): void
    {
        $business = new Business();
        $availability = new Availabilities();

        $business->addAvailability($availability);
        $business->addAvailability($availability);

        $this->assertCount(1, $business->getAvailabilities());
    }

    public function testRemoveAvailabilityClearsBothSidesOfTheRelation(): void
    {
        $business = new Business();
        $availability = new Availabilities();
        $business->addAvailability($availability);

        $business->removeAvailability($availability);

        $this->assertCount(0, $business->getAvailabilities());
        $this->assertNull($availability->getBusiness());
    }

    public function testRemovingAnAvailabilityThatBelongsToAnotherBusinessDoesNotStealIt(): void
    {
        $businessA = new Business();
        $businessB = new Business();
        $availability = new Availabilities();
        $businessA->addAvailability($availability);

        // l'availability n'a jamais été ajoutée à businessB : la retirer ne doit rien casser
        $businessB->removeAvailability($availability);

        $this->assertSame($businessA, $availability->getBusiness());
    }
}
