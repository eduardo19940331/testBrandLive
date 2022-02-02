<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Client;

class ClientRepository extends EntityRepository
{
    /**
     * Obtiene todos los Clientes con el enambled = 1
     */
    public function getAllClient(): array
    {
        $manager = $this->getEntityManager();

        $query = "SELECT
                    c.id,
                    c.firstname,
                    c.lastname,
                    c.email,
                    (SELECT GROUP_CONCAT(gc.name) FROM client_group cg 
                    INNER JOIN group_category gc ON gc.id = cg.group_id AND gc.enabled = 1
                    WHERE cg.client_id = c.id AND cg.enabled = 1
                    ORDER BY gc.`name`) AS clientg
                FROM client c
                WHERE c.enabled = 1
                ORDER BY c.id DESC";
        $conn = $manager->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
