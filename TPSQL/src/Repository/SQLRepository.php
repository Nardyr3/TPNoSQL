<?php

namespace App\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Faker;

class SQLRepository 
{

    public function dropDatabase(EntityManagerInterface $entityManager)
    {

        $conn = $entityManager->getConnection();

        // Create the User table
        $sql = 'TRUNCATE TABLE User;';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Create the Product table
        $sql = 'TRUNCATE TABLE Product;';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Create the Purchase table
        $sql = 'TRUNCATE TABLE Purchase;';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Create the Friend table
        $sql = 'TRUNCATE TABLE Friend;';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    public function createUser(EntityManagerInterface $entityManager, String $nb_user, String $nb_product)
    {

        $faker = Faker\Factory::create('fr_FR');

        $conn = $entityManager->getConnection();
        // Add some user
        set_time_limit(600000);

        $sql = '';
        $count = 0;
        for ($i = 0; $i < $nb_user; $i++) {
            $count = $count + 1;
            if ($count < 9999){
                $sql = $sql.'INSERT INTO User(first_name, last_name) VALUES (\''.$faker->firstName.'\',\''.$faker->lastName.'\');';
            }
            else{
                $sql = $sql.'INSERT INTO User(first_name, last_name) VALUES (\''.$faker->firstName.'\',\''.$faker->lastName.'\');';
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $entityManager->clear();
                $sql = '';
                $count = 0;
            }
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute();

    }
            
    public function createProduct(EntityManagerInterface $entityManager, String $nb_user, String $nb_product)
    {

        $faker = Faker\Factory::create('fr_FR');

        $conn = $entityManager->getConnection();
        // Add some product
        set_time_limit(600000);

        $sql = '';
        $count = 0;
        for ($i = 0; $i < $nb_product; $i++) {
            $count = $count + 1;
            if ($count < 9999){
                $sql = $sql.'INSERT INTO Product(name) VALUES (\''.$faker->company.'\');';
            }
            else{
                $sql = $sql.'INSERT INTO Product(name) VALUES (\''.$faker->company.'\');';
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $entityManager->clear();
                $sql = '';
                $count = 0;
            }
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute();

    }

    public function createPurchase(EntityManagerInterface $entityManager, String $nb_user, String $nb_product)
    {

        $faker = Faker\Factory::create('fr_FR');

        $conn = $entityManager->getConnection();
        // Add some Purchase
        set_time_limit(600000);

        $sql = '';
        $count = 0;
        for ($i = 0; $i < $nb_user; $i++) {
            $id = $i + 1;
            $max = $faker->numberBetween(0, 10);
            for ($j = 0; $j < $max; $j++) {
                $count = $count + 1;
                $id_product = $faker->numberBetween(1, $nb_product);
                if ($count < 9999){
                    $sql = $sql.'INSERT INTO Purchase(id_person, id_product) VALUES (\''.$id.'\',\''.$id_product.'\');';
                }
                else{
                    $sql = $sql.'INSERT INTO Purchase(id_person, id_product) VALUES (\''.$id.'\',\''.$id_product.'\');';
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $entityManager->clear();
                    $sql = '';
                    $count = 0;
                }
                
            }  
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
    }

    public function createFriend(EntityManagerInterface $entityManager, String $nb_user, String $nb_product)
    {

        $faker = Faker\Factory::create('fr_FR');

        $conn = $entityManager->getConnection();
        // Add some Friends
        set_time_limit(600000);

        $sql = '';
        $count = 0;
        for ($i = 0; $i < $nb_user; $i++) {
            $id = $i + 1;
            $max = $faker->numberBetween(0, 20);
            for ($j = 0; $j < $max; $j++) {
               
                $id_user = $faker->numberBetween(1, $nb_user);
                if($id_user !=$id) {
                    $count = $count + 1;
                    if ($count < 9999){
                        $sql = $sql.'INSERT INTO Friend(id_person, id_follower) VALUES (\''.$id.'\',\''.$id_user.'\');';
                    }
                    else{
                        $sql = $sql.'INSERT INTO Friend(id_person, id_follower) VALUES (\''.$id.'\',\''.$id_user.'\');';
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $entityManager->clear();
                        $sql = '';
                        $count = 0;
                    }
                }
                
            }  
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    public function getAllPurchaseByFollower(EntityManagerInterface $entityManager, String $user_id, String $level)
    {
        $conn = $entityManager->getConnection();
        $sql = 'WITH RECURSIVE cte AS ( SELECT *, 1 level FROM friend WHERE id_person = ' . $user_id .
        ' UNION ALL SELECT f.*, level + 1 FROM cte c INNER JOIN friend f ON f.id_person = c.id_follower WHERE f.id_follower <> ' . $user_id .
        ' AND level < '.$level.' ) SELECT p.name AS name, COUNT(p.name) AS nb FROM product p JOIN purchase pu ON pu.id_product = p.id '.
        'WHERE pu.id_person IN (SELECT DISTINCT id_follower FROM cte) AND pu.id_product IN ( SELECT DISTINCT id_product FROM purchase pu WHERE pu.id_person = ' . $user_id .
        ')GROUP BY name ORDER BY COUNT(nb) DESC;';

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPurchaseByProduct(EntityManagerInterface $entityManager, String $user_id, String $product_id, String $level)
    {
        $conn = $entityManager->getConnection();

        $sql = 'WITH recursive cte AS
        (
               SELECT *,
                      1 level
               FROM   friend
               WHERE  id_person = '.$user_id.'
               UNION ALL
               SELECT     f.*,
                          level + 1
               FROM       cte c
               INNER JOIN friend f
               ON         f.id_person = c.id_follower
               WHERE      f.id_follower <> '.$user_id.'
               AND        level < '.$level.')
        SELECT   p.NAME        AS name,
                 count(p.NAME) AS nb
        FROM     product p
        JOIN     purchase pu
        ON       pu.id_product = p.id
        WHERE    pu.id_person IN
                                  (
                                  SELECT DISTINCT id_follower
                                  FROM            cte)
        AND      p.id = '.$product_id.'
        GROUP BY NAME
        ORDER BY nb DESC;';

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCount(EntityManagerInterface $entityManager){
        $conn = $entityManager->getConnection();
        $sql = 'SELECT \'Utilisateur\' AS nom, COUNT(*) AS nombre FROM user UNION SELECT \'Produits\', COUNT(*) FROM product UNION SELECT \'Achat\', COUNT(*) FROM purchase UNION SELECT \'Amis\', COUNT(*) FROM friend';

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
