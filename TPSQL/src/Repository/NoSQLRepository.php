<?php

namespace App\Repository;

use Faker;
use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Contracts\ClientInterface;

class NoSQLRepository
{

    public function dropDatabase(ClientInterface $client)
    {

        // Clean NoSQL database
        $client->run(
            'MATCH (n) DETACH DELETE (n)'
        );
    }

    public function createUser(ClientInterface $client, String $nb_user)
    {
        $faker = Faker\Factory::create('fr_FR');

        // Add some user
        for ($i = 0; $i < $nb_user; $i++) {
            $client->run(
                'CREATE (u:Person {id: $id, first_name: $fn, last_name: $ln})',
                ['id' => $i, 'fn' => $faker->firstName, 'ln' => $faker->lastName],
            );
        }

    }

    public function createProduct(ClientInterface $client,String $nb_product)
    {

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < $nb_product; $i++) {
            $client->run(
                'CREATE (u:Product {id: $id, name: $n})',
                ['id' => $i, 'n' => $faker->company]
            );
        }

    }

    public function createPurchase(ClientInterface $client,String $nb_user, String $nb_product)
    {
        $nb_purchases_max = 5;

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < $nb_user; $i++) {
            $nb_purchases = rand(0, $nb_purchases_max);
            if ($nb_purchases != 0) {
                $purchases = array();
                for ($f = 0; $f < $nb_purchases; $f++) {
                    $new_purchase = rand(0, $nb_product-1);
                    if($new_purchase != $i && !in_array($new_purchase, $purchases)) {
                        $purchases[] = $new_purchase;
                        $client->run(
                            'MATCH (a:Person {id: $id_a}), (b:Product {id: $id_b})
                            CREATE (a)-[:Purchase]->(b)',
                            ['id_a' => $i, 'id_b' => $new_purchase]
                        );
                    }
                }
            }
        }

    }

    public function createFriend(ClientInterface $client, String $nb_user)
    {
        $nb_followers_max = 20;

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < $nb_user; $i++) {
            $nb_followers = rand(0, $nb_followers_max);
            if ($nb_followers != 0) {
                $followers = array();
                for ($f = 0; $f < $nb_followers; $f++) {
                    $new_follower = rand(0, $nb_user-1);
                    if($new_follower != $i && !in_array($new_follower, $followers)) {
                        $followers[] = $new_follower;
                        $client->run(
                            'MATCH (a:Person {id: $id_a}), (b:Person {id: $id_b})
                            CREATE (b)-[:Follow]->(a)',
                            ['id_a' => $i, 'id_b' => $new_follower]
                        );
                    }
                }
            }
        }
    }

    public function getAllPurchaseByFollower(ClientInterface $client, String $user_id, String $level)
    {
        return $client->run(
            'MATCH (:Person {id: '.$user_id.'})<-[:Follow *1..'.$level.']-(p:Person)
             WITH DISTINCT p
             MATCH (p)-[:Purchase]->(n:Product)
             RETURN n.name AS name, COUNT(*) AS nb
             ORDER BY COUNT(*) DESC'
        );
    }

    public function getPurchaseByProduct(ClientInterface $client, String $user_id, String $product_id, String $level)
    {
        return $client->run(
            'MATCH (:Person {id: '.$user_id.'})<-[:Follow *1..'.$level.']-(p:Person)
             WITH DISTINCT p
             MATCH (p)-[:Purchase]->(n:Product {id: '.$product_id.'})
             RETURN n.name AS name, COUNT(*) AS nb
             ORDER BY COUNT(*) DESC'
        );
    }

    public function getBuyersByProduct(ClientInterface $client, String $product_id, String $level){

        return $client->run(
            'MATCH (n:Person)-[:Follow *'.$level.']->(:Person)-[:Purchase]->(p:Product {id: '.$product_id.'}) RETURN p.name AS name, COUNT(n) AS nb'
        );
    }

    public function getCount(ClientInterface $client){

        return $client->run(
            'MATCH(n:Person) 
            WITH COUNT(n) AS count 
            RETURN \'Utilisateurs\' AS nom, count AS nombre
            UNION
            MATCH(m:Product) 
            WITH COUNT(m) AS count 
            RETURN \'Produits\' AS nom, count AS nombre
            UNION
            MATCH ()-[p:Purchase]->()
            WITH COUNT(p) AS count 
            RETURN \'Achats\' AS nom, count AS nombre
            UNION
            MATCH ()-[o:Follow]->()
            WITH COUNT(o) AS count 
            RETURN \'Amis\' AS nom, count AS nombre'  
        );
    }
}
