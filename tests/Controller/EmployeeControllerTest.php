<?php

namespace App\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmployeeControllerTest extends WebTestCase
{
    public function testImport(): void
    {
        // Arrange
        $client = static::createClient();
    
        // Act
        $crawler = $client->request('POST', '/import');
    
        // Assert
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('/employee'));
    }
    
}
    