<?php

namespace App\Tests\EventSubscriber;

use App\Entity\Product;
use App\EventSubscriber\EasyAdminSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;

class EasyAdminSubscriberTest extends TestCase
{
    private $kernelMock;
    private $subscriber;
    private $productMock;
    private $persistEventMock;
    private $updateEventMock;

    protected function setUp(): void
    {
        $this->kernelMock = $this->createMock(KernelInterface::class);
        $this->subscriber = new EasyAdminSubscriber($this->kernelMock);

        $this->productMock = $this->createMock(Product::class);
        $this->persistEventMock = $this->createMock(BeforeEntityPersistedEvent::class);
        $this->updateEventMock = $this->createMock(BeforeEntityUpdatedEvent::class);

        $this->persistEventMock->method('getEntityInstance')->willReturn($this->productMock);
        $this->updateEventMock->method('getEntityInstance')->willReturn($this->productMock);

        // Define the project directory returned by the Kernel mock
        $this->kernelMock->method('getProjectDir')->willReturn('/fake/project/dir');
    }

    public function testSetIllustration()
    {
        // Set up a mock for the global $_FILES array
        $_FILES = [
            'Product' => [
                'tmp_name' => ['illustration' => '/path/to/temp/file'],
                'name' => ['illustration' => 'image.jpg']
            ]
        ];

        $this->productMock->expects($this->once())->method('setIllustration');

        $this->subscriber->setIllustration($this->persistEventMock);
    }

    public function testUpdateIllustration()
    {
        $_FILES = [
            'Product' => [
                'tmp_name' => ['illustration' => '/path/to/temp/file'],
                'name' => ['illustration' => 'updated_image.jpg']
            ]
        ];

        $this->productMock->expects($this->once())->method('setIllustration');

        $this->subscriber->updateIllustration($this->updateEventMock);
    }
}
