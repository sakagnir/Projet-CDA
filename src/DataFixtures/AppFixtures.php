<?php

namespace App\DataFixtures;

use App\Entity\Business;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private const BUSINESSES = [
        ['name' => 'Boulangerie du Marché', 'address' => '8 rue du Marché', 'description' => 'Pain et viennoiseries artisanales'],
        ['name' => 'Coiffure Élégance', 'address' => '12 avenue Victor Hugo', 'description' => 'Salon de coiffure mixte'],
        ['name' => 'Garage Dupont', 'address' => '3 rue de la Gare', 'description' => 'Réparation et entretien automobile'],
        ['name' => 'Pharmacie Centrale', 'address' => '45 rue de la République', 'description' => 'Pharmacie de quartier'],
        ['name' => 'Restaurant Le Gourmet', 'address' => '2 place du Marché', 'description' => 'Cuisine française traditionnelle'],
        ['name' => 'Fleuriste Les Pétales', 'address' => '9 rue des Fleurs', 'description' => 'Compositions florales sur mesure'],
        ['name' => 'Librairie Page Blanche', 'address' => '17 rue de la Gare', 'description' => 'Librairie généraliste'],
        ['name' => 'Institut de Beauté Sérénité', 'address' => '6 avenue de la Paix', 'description' => 'Soins esthétiques et bien-être'],
        ['name' => 'Boucherie Martin', 'address' => '21 rue de la République', 'description' => 'Viandes locales et charcuterie'],
        ['name' => 'Cabinet Dentaire Sourire', 'address' => '4 avenue Victor Hugo', 'description' => 'Soins dentaires pour toute la famille'],
    ];

    public function load(ObjectManager $manager): void
    {
        $role = new Role();
        $role->setName('role_test');
        $manager->persist($role);

        foreach (self::BUSINESSES as $i => $data) {
            $client = new User();
            $client->setFirstName('Gérant');
            $client->setLastName((string) ($i + 1));
            $client->setPassword('toto1234*');
            $client->setEmail(sprintf('manager%d@test.com', $i + 1));
            $client->setPhoneNumber(sprintf('06%08d', $i + 1));
            $client->setRole($role);

            $business = new Business();
            $business->setName($data['name']);
            $business->setAddress($data['address']);
            $business->setDescription($data['description']);
            $business->setManager($client);

            $manager->persist($client);
            $manager->persist($business);
        }

        $manager->flush();
    }
}
