<?php

namespace App\Tests\Repository;

use App\DataFixtures\AppFixtures;
use App\Entity\Business;
use App\Repository\BusinessRepository;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BusinessRepositoryTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        $purger = new ORMPurger($entityManager);

        $loader = new Loader();
        $loader->addFixture(new AppFixtures());
        $executor = new ORMExecutor($entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }

    public function testSearchWithoutQueryReturnsAllBusinessesOrderedByName(): void
    {
        $query = null;
        $businessRepository = static::getContainer()->get(BusinessRepository::class);
        $res = $businessRepository->search($query);

        $names = array_map(fn(Business $b) => $b->getName(), $res);
        $sorted = $names;
        sort($sorted, SORT_STRING | SORT_FLAG_CASE);

        $this->assertCount(10, $res);
        $this->assertSame($sorted, $names);
    }

    public function testSearchByPartialBusinessNameReturnsSpecifiedBusinesses() {
        $query = "Garage";
        $businessRepository = static::getContainer()->get(BusinessRepository::class);
        $res = $businessRepository->search($query);

        $name = array_map(fn(Business $b) => $b->getName(), $res);

        $this->assertCount(1, $res);
        $this->assertSame("Garage Dupont", $name[0]);
    }

    public function testSearchByPartialAddressReturnsConcernedBusinesses() {
        $query = "Victor";
        $businessRepository = static::getContainer()->get(BusinessRepository::class);
        $res = $businessRepository->search($query);

        $names = array_map(fn(Business $b) => $b->getName(), $res);
        $this->assertCount(2, $res);
        $this->assertSame("Cabinet Dentaire Sourire", $names[0]);
        $this->assertSame("Coiffure Élégance", $names[1]);
    }

    public function testSearchUnknownBusinessReturnsEmptyArray() {
        $query = "aeaddfvqsq";
        $businessRepository = static::getContainer()->get(BusinessRepository::class);
        $res = $businessRepository->search($query);

        $this->assertCount(0, $res);
    }
}
