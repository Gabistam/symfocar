<?php

namespace App\Tests\Entity;

use App\Entity\Adress;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class AdressTest extends TestCase {
    public function testAdressEntity() {
        $adress = new Adress();

        // Test setting and getting user
        $user = $this->createMock(User::class);
        $adress->setUser($user);
        $this->assertSame($user, $adress->getUser());

        // Test setting and getting name
        $adress->setName('John Doe');
        $this->assertEquals('John Doe', $adress->getName());

        // Test setting and getting firstname
        $adress->setFirstname('John');
        $this->assertEquals('John', $adress->getFirstname());

        // Test setting and getting lastname
        $adress->setLastname('Doe');
        $this->assertEquals('Doe', $adress->getLastname());

        // Test setting and getting company
        $adress->setCompany('Tech Corp');
        $this->assertEquals('Tech Corp', $adress->getCompany());

        // Test setting and getting adresse
        $adress->setAdresse('123 Tech St.');
        $this->assertEquals('123 Tech St.', $adress->getAdresse());

        // Test setting and getting ville
        $adress->setVille('Tech City');
        $this->assertEquals('Tech City', $adress->getVille());

        // Test setting and getting CP
        $adress->setCP('12345');
        $this->assertEquals('12345', $adress->getCP());

        // Test setting and getting pays
        $adress->setPays('Techland');
        $this->assertEquals('Techland', $adress->getPays());

        // Test setting and getting phone
        $adress->setPhone('123-456-7890');
        $this->assertEquals('123-456-7890', $adress->getPhone());

        // Test the __toString() method
        $expectedString = 'John Doe[br]123 Tech St.[br]12345 Tech City - Techland';
        $this->assertEquals($expectedString, $adress->__toString());
    }
}
