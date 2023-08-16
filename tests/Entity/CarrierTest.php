<?php

namespace App\Tests\Entity;

use App\Entity\Carrier;
use PHPUnit\Framework\TestCase;

class CarrierTest extends TestCase
{
    private Carrier $carrier;

    protected function setUp(): void
    {
        $this->carrier = new Carrier();
    }

    public function testGetSetId(): void
    {
        // We don't set the ID as it's typically managed by Doctrine, but we can still check it starts as null
        $this->assertNull($this->carrier->getId());
    }

    public function testGetSetName(): void
    {
        $name = "CarrierName";
        $this->carrier->setName($name);
        $this->assertSame($name, $this->carrier->getName());
    }

    public function testGetSetDescription(): void
    {
        $description = "This is a carrier description";
        $this->carrier->setDescription($description);
        $this->assertSame($description, $this->carrier->getDescription());
    }

    public function testGetSetPrice(): void
    {
        $price = 123.45;
        $this->carrier->setPrice($price);
        $this->assertSame($price, $this->carrier->getPrice());
    }

    public function testToString(): void
{
    $name = "CarrierName";
    $description = "This is a carrier description";
    $price = 12345; // Considering the price is in cents

    $this->carrier->setName($name);
    $this->carrier->setDescription($description);
    $this->carrier->setPrice($price);

    $expectedString = "CarrierName[br]This is a carrier description[br][b]123,45 €[/b]";
    $this->assertSame($expectedString, $this->carrier->__toString());
}

}
