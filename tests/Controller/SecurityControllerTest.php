<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\SecurityController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityControllerTest extends TestCase
{
    public function testLoginWithAuthenticatedUser()
    {
        // Mocking UserInterface
        $userMock = $this->createMock(UserInterface::class);

        // Create a partial mock of SecurityController
        $controller = $this->getMockBuilder(SecurityController::class)
                           ->onlyMethods(['getUser', 'redirectToRoute', 'render'])
                           ->getMock();

        // Define the behavior for the mocked getUser() method
        $controller->expects($this->once())
                   ->method('getUser')
                   ->willReturn($userMock);  // Use the mocked user

        // Mock the redirectToRoute method to return a RedirectResponse
        $controller->expects($this->once())
        ->method('redirectToRoute')
        ->willReturn(new RedirectResponse('/account'));;

        // Mocking AuthenticationUtils
        $authenticationUtils = $this->createMock(AuthenticationUtils::class);

        $response = $controller->login($authenticationUtils);
        
        $this->assertInstanceOf(Response::class, $response);
    }

    // ... other test methods
}
