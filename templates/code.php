<?php
session_start();
$con = mysqli_connect("localhost", "root", "employee_db");

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


if(isset($_POST['import_file_btn']))
 {
    $allowed_ext = ['xls', 'csv', 'xlsx'];
    $fileName = $_FILES['import_file']['name'];
    $chechking = explode(".", $fileName);
    $file_ext = end($chechking);


    if(in_array($file_ext, $allowed_ext)) {
        $targetPath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($targetPath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        foreach($data as $row) {
            
            $manager = $row['0'];
            $username = $row['1'];
            $email = $row['2'];
            $department = $row['3'];
            $phone_number = $row['4'];
            $address1 = $row['5'];
            $start_date = $row['6'];
            $end_date = $row['7'];

            $checkEmployee = "SELECT id FROM employee WHERE id= '$id' ";
            $chechkEmployee_result = mysqli_query($con, $checkEmployee);

            if(mysqli_num_rows($chechkEmployee_result) > 0) {

                // Already exists means update now
                $up_query = "UPDATE employee SET manager='$manager', username= '$username', 
                email= '$email', department='$department', phone_number='$phone_number', address1='$address1',
                start_date='$start_date', end_date='$end_date' ";
                $up_result = mysqli_query($con, $up_query);
                $msg = 1;

            } else {
                // New record to insert
                $in_query = "INSERT INTO employee(manager, username, email, department, phone_number, address1, start_date, end_date)
                VALUES ('$manager','$username','$email','$department','$phone_number','$address1','$start_date', '$end_date')";
                $in_result = mysqli_query($con, $in_query);
                $msg = 1;

                if(isset($msg)){
                    $_SESSION['status'] = "File Imported Successfully";
                    header("Location: index.html.twig");
                }
                else {
                    $_SESSION['status'] = "File Importing Failed";
                    header("Location: index.html.twig");
                }
            }
        }     

    } else {
        $_SESSION['status'] = "Invalid File";
        header("Location: index.html.twig");
    }
 }

?>