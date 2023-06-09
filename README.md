# README

Symfony TestTask Application

## What is the purpose of this repository?

* The 'Symfony TestTask Application' serves as a reference application that demonstrates the process of uploading files using a React Component and storing the associated data in a MySQL database.

* Version 1.0

## Requirements

* Make sure you have [PHP 8.1](https://www.php.net/releases/8_0_0.php/) or a higher version installed.
* Make sure you have [Visual Studio Code](https://code.visualstudio.com/) or any of recommended IDEs for Symfony installed.

## Built With

[<img src="https://www.php.net/images/logos/php-logo.svg" height="20">](https://www.php.net/)
[<img src="https://symfony.com/images/logos/header-logo.svg" height="20">](https://symfony.com/)
[<img src="https://reactjs.org/logo-og.png" height="20">](https://react.dev/)
[<img src="https://www.mysql.com/common/logos/logo-mysql-170x115.png" height="20">](https://www.mysql.com/)


## Installation

There are multiple ways to install this project based on your requirements:

* Download [Symfony CLI](https://symfony.com/download) and use the installed `symfony` binary to run this command:

```shell
$ symfony new --demo test_task
```
* Download [Composer](https://getcomposer.org/) and use the installed `composer` binary to run these commands:
```shell
# Create a new project based on the Symfony Demo project:
$ composer create-project symfony/symfony-demo test_task

# Alternatively, you can clone the code repository and install its dependencies:
$ git clone https://github.com/besartaajvazi/TestTask/ test_task
$ cd test_task
$ composer install

```
## How to configure MySQL database connection?
To specify the MySQL database connection configuration in a .env file, modify DATABASE_URL to:

```shell 
DATABASE_URL="mysql://root:@127.0.0.1:3306/test_task_db?serverVersion=5.7"
```

### Is a specific file or data format required?
Yes, for this test example you need an excel file with these two tables:

    * Employee
        * name
        * manager
        * username
        * email
        * department
        * phone number
        * address1
        * start_date
        * end_date
    
    * Department
        * department_name
        * department_leader
        * department_phone


 