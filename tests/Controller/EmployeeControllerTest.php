<?php

namespace App\Tests\Controller;

use App\Entity\Employee;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\EmployeeController;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmployeeControllerTest extends WebTestCase
{ 
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/employee');

        // Assert the response
        $this->assertResponseIsSuccessful();
    }
    
    public function testSearch()
    {
        // Create a mock of the EmployeeRepository
        $employeeRepositoryMock = $this->createMock(EmployeeRepository::class);
    
        // Create some dummy employees for testing
        $employees = [
            (new Employee())
                ->setName('John')
                ->setManager('liz.erd')
                ->setUsername('john.doe')
                ->setEmail('john.doe@gmail.com')
                ->setDepartment('Dev')
                ->setPhoneNumber('(566) 576-7803')
                ->setAddress1('Prishtine, L.Dardania')
                ->setStartDate('2015-03-02 00:00:00')
                ->setEndDate('2022-05-10 00:00:00'),
            (new Employee())
                ->setName('John')
                ->setManager('liz.erd')
                ->setUsername('john.doe')
                ->setEmail('john.doe@gmail.com')
                ->setDepartment('Dev')
                ->setPhoneNumber('(566) 576-7803')
                ->setAddress1('Prishtine, L.Dardania')
                ->setStartDate('2015-03-02 00:00:00')
                ->setEndDate('2022-05-10 00:00:00'),
        ];
    
        $employeeRepositoryMock->method('searchEmployees')
            ->willReturn($employees);
    
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
    
        $employeeController = new EmployeeController($entityManagerMock, $employeeRepositoryMock);
    
        $requestMock = $this->createMock(Request::class);
        $queryParameters = new ParameterBag(['q' => 'John']);
    
        $requestMock->query = $queryParameters;    
        $response = $employeeController->search($requestMock);
    
        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);
    
        // Assert the response content
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals([
            [
                'name' => 'John',
                'manager' => 'liz.erd',
                'username' => 'john.doe',
                'email' => 'john.doe@gmail.com',
                'department' => 'Dev',
                'phoneNumber' => '(566) 576-7803',
                'address1' => 'Prishtine, L.Dardania',
                'startDate' => '2015-03-02 00:00:00',
                'endDate' => '2022-05-10 00:00:00'
            ],
            [
                'name' => 'John',
                'manager' => 'liz.erd',
                'username' => 'john.doe',
                'email' => 'john.doe@gmail.com',
                'department' => 'Dev',
                'phoneNumber' => '(566) 576-7803',
                'address1' => 'Prishtine, L.Dardania',
                'startDate' => '2015-03-02 00:00:00',
                'endDate' => '2022-05-10 00:00:00'
            ],
        ], $responseData);
    }   
}
    