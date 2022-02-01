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

        $query = "SELECT * FROM client c WHERE c.enabled = 1 ORDER BY c.lastname";
        $conn = $manager->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
