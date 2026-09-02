<?php

namespace App\Tests\Form;

use App\Entity\Business;
use App\Form\BusinessFormType;
use Symfony\Component\Form\Test\TypeTestCase;

class BusinessFormTypeTest extends TypeTestCase
{
    public function testSubmitValidDataPopulatesTheBusiness(): void
    {
        $formData = [
            'name' => 'Le Bon Salon',
            'address' => '12 rue des Lilas',
            'description' => 'Salon de coiffure au centre-ville',
        ];

        $form = $this->factory->create(BusinessFormType::class, new Business());
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $business = $form->getData();
        $this->assertInstanceOf(Business::class, $business);
        $this->assertSame('Le Bon Salon', $business->getName());
        $this->assertSame('12 rue des Lilas', $business->getAddress());
        $this->assertSame('Salon de coiffure au centre-ville', $business->getDescription());
    }

    public function testFormHasTheExpectedFields(): void
    {
        $form = $this->factory->create(BusinessFormType::class, new Business());

        $this->assertTrue($form->has('name'));
        $this->assertTrue($form->has('address'));
        $this->assertTrue($form->has('description'));
    }
}
