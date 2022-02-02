<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\GroupCategory;

class GroupCategoryRepository extends EntityRepository
{
    /**
     * Obtiene todos los Grupos con el enabled = 1
     */
    public function getGroupsAsOptions(): array
    {
        $manager = $this->getEntityManager();

        $query = "SELECT g.`name` as name_group, g.id as ident_group FROM group_category g WHERE g.enabled = 1 ORDER BY g.name";
        $conn = $manager->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $groups = $stmt->fetchAll();
        $groupOptions = [];
        foreach ($groups as $row) {
            $groupOptions[$row["ident_group"]] = $row["name_group"];
        }

        return $groupOptions;
    }
}
