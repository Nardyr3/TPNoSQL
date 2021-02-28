<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

use Faker;
use PDO;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        // Get the database connection
        $conn = $manager->getConnection();


       // $conn = new PDO('mysql:host=127.0.0.1:3306;dbname=tpnosql', 'root', '');

        // Create the User table
        $sql = 'DROP TABLE IF EXISTS User;CREATE TABLE User(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, first_name VARCHAR(255),last_name VARCHAR(255));';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Create the Product table
        $sql = 'DROP TABLE IF EXISTS Product;CREATE TABLE Product(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,  name VARCHAR(255));';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Create the Purchase table
        $sql = 'DROP TABLE IF EXISTS Purchase;CREATE TABLE Purchase(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,  id_person INT NOT NULL, id_product INT NOT NULL, 
        FOREIGN KEY (id_person) references Person(id) ON DELETE RESTRICT, FOREIGN KEY (id_product) references Product(id) ON DELETE RESTRICT);';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Add some user
        for ($i = 0; $i < 1000; $i++) {
            $sql = 'INSERT INTO User(first_name, last_name) VALUES (\''.$faker->firstName.'\',\''.$faker->lastName.'\');';
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }

        // Add some product
        for ($i = 0; $i < 1000; $i++) {
            $sql = 'INSERT INTO Product(name) VALUES (\''.$faker->company.'\');';
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }

        // Add some Purchase
        for ($i = 0; $i < 1000; $i++) {
            $id_user = $faker->numberBetween(1, 999);
            $id_product = $faker->numberBetween(1, 999);
            $sql = 'INSERT INTO Purchase(id_person, id_product) VALUES (\''.$id_user.'\',\''.$id_product.'\');';
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }
        
        

        //$manager->flush();
    }
}
