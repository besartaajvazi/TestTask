<?php


namespace App\Controller;

use App\Entity\Department;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EmployeeRepository;
use App\Entity\Employee;
use Ramsey\Uuid\Uuid;

class EmployeeController extends AbstractController
{
    private $em;
    private $employeeRepository;
    public function __construct(EntityManagerInterface $em, EmployeeRepository $employeeRepository) 
    {
        $this->em = $em;
        $this->employeeRepository = $employeeRepository;
    }

    #[Route('/employee', name: 'employee')]
    public function index(): Response
    {
        $employees = $this->employeeRepository->findAll();


        return $this->render('employee/index.html.twig', [
            'employees' => $employees
        ]);
    }

    #[Route('/employee/search', methods: ['GET'], name: 'app_employee_search_api')]
    public function search(Request $request): JsonResponse
    {
        $searchTerm = $request->query->get('q'); 
    
        $employees = $this->employeeRepository->searchEmployees($searchTerm);
    
        $results = [];
    
        foreach ($employees as $employee) {
            $result = [
                'name' => $employee->getName(),
                'manager' => $employee->getManager(),
                'username' => $employee->getUsername(),
                'email' => $employee->getEmail(),
                'department' => $employee->getDepartment(), 
                'phoneNumber' => $employee->getPhoneNumber(),
                'address1' => $employee->getAddress1(),
                'startDate' => $employee->getStartDate(), 
                'endDate' => $employee->getEndDate() 
            ];

            $results[] = $result;
        }
            
        // Return the search results as JSON
        return new JsonResponse($results);
    }
    

    #[Route('/import', methods:['GET', 'POST'], name: 'import')]
    public function import(Request $request): Response
    {
        $file = $request->files->get('import_file');
    
        if ($file === null) {
            $this->addFlash('error', 'No file uploaded.');
            return $this->redirectToRoute('employee');
        }
        
        $allowed_ext = ['xls', 'csv', 'xlsx'];
        $fileName = $file->getClientOriginalName();
        $chechking = explode(".", $fileName);
        $file_ext = strtolower(end($chechking));
    
        if (!in_array($file_ext, $allowed_ext)) {
            $this->addFlash('error', 'Invalid file format.');
            return $this->redirectToRoute('employee');
        }
    
        // Retrieve existing employees and departments from the database
        $existingEmployees = $this->em->getRepository(Employee::class)->findAll();
        $existingDepartments = $this->em->getRepository(Department::class)->findAll();
    
        // Clear existing employee and department data
        foreach ($existingEmployees as $employee) {
            $this->em->remove($employee);
        }
        foreach ($existingDepartments as $department) {
            $this->em->remove($department);
        }
    
        $this->em->flush();
    
        $targetPath = $file->getPathName();
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($targetPath);
        $data = $spreadsheet->getActiveSheet()->toArray();
    
        // Remove the first row (column names)
        array_shift($data);

        foreach ($data as $row) {
            // Extract employee and department information from the row
            $name = $row['0'];
            $manager = $row['1'];
            $username = $row['2'];
            $email = $row['3'];
            $department = $row['4'];
            $phoneNumber = $row['5'];
            $address1 = $row['6'];
            $startDate = $row['7'];
            $endDate = $row['8'];
    
            $department_name = $row['11'];
            $department_leader = $row['12'];
            $department_phone = $row['13'];
    
            // Generate a UUID for the employee
            $idEmployee = Uuid::uuid4()->toString();
    
            // Generate a UUID for the department
            $idDepartment = Uuid::uuid4()->toString();
    
            // Check if the employee already exists in the database
            $employee = $this->em->getRepository(Employee::class)->findOneBy(['id' => $idEmployee]);
    
            if ($employee) {
                // Update existing employee record
                $employee->setName($name);
                $employee->setManager($manager);
                $employee->setUsername($username);
                $employee->setEmail($email);
                $employee->setDepartment($department);
                $employee->setPhoneNumber($phoneNumber);
                $employee->setAddress1($address1);
                  // Convert DateTime objects to strings
                $startDateString = $startDate->format('Y-m-d');
                $endDateString = $endDate->format('Y-m-d');

                    // Set the converted date strings in the employee entity
                $employee->setStartDate($startDateString);
                $employee->setEndDate($endDateString);
    
                $today = new \DateTime();
                $startDate = \DateTime::createFromFormat('Y-m-d', $startDate);
                $endDate = \DateTime::createFromFormat('Y-m-d', $endDate);
    
                if ($startDate instanceof \DateTime && $endDate instanceof \DateTime) {
                    // Check if the start date is in the past and end date is in the future
                    if ($startDate <= $today && $endDate >= $today) {
                        // Set the employee as active
                        $employee->setIsActive(true);
                    } else {
                        // Set the employee as inactive
                        $employee->setIsActive(false);
                    }
                } else {
                    // Set the employee as inactive if the start date or end date is invalid
                    $employee->setIsActive(false);
                }
            } else {
                // Create new employee record
                $employee = new Employee();
                $employee->setId($idEmployee); // Set the UUID for the new employee
                $employee->setName($name);
                $employee->setManager($manager);
                $employee->setUsername($username);
                $employee->setEmail($email);
                $employee->setDepartment($department);
                $employee->setPhoneNumber($phoneNumber);
                $employee->setAddress1($address1);
                $employee->setStartDate($startDate);
                $employee->setEndDate($endDate);
    
                $today = new \DateTime();
                $start_datetime = \DateTime::createFromFormat('Ymd', $startDate);
                $end_datetime = \DateTime::createFromFormat('Ymd', $endDate);
    
                if ($start_datetime instanceof \DateTime && $end_datetime instanceof \DateTime) {
                    // Check if the start date is in the past and end date is in the future
                    if ($start_datetime <= $today && $end_datetime >= $today) {
                        // Set the employee as active
                        $employee->setIsActive(true);
                    } else {
                        // Set the employee as inactive
                        $employee->setIsActive(false);
                    }
                } else {
                    // Set the employee as inactive if the start date or end date is invalid
                    $employee->setIsActive(false);
                }
            }
    
            // Check if the department already exists in the database
            $departmentTable = $this->em->getRepository(Department::class)->findOneBy(['id' => $idDepartment]);
    
            if ($departmentTable) {
                // Update existing department record
                $departmentTable->setDepartmentName($department_name);
                $departmentTable->setDepartmentLeader($department_leader);
                $departmentTable->setDepartmentPhone($department_phone);
            } else {
                // Create new department record
                $departmentTable = new Department();
                $departmentTable->setId($idDepartment); // Set the UUID for the new department
                $departmentTable->setDepartmentName($department_name);
                $departmentTable->setDepartmentLeader($department_leader);
                $departmentTable->setDepartmentPhone($department_phone);
    
                $this->em->persist($departmentTable);
            }
    
            $this->em->persist($employee);
        }
    
        $this->em->flush();
    
        $this->addFlash('success', 'File imported successfully.');
    
        return $this->redirectToRoute('employee');
    }
       
}

