<?php

namespace ECommerceBundle\Repository;

use LoginBundle\Entity\User;

/**
 * CommandeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommandeRepository extends \Doctrine\ORM\EntityRepository
{
    public function myfind()
    {
        $em = $query = $this->getEntityManager();
        $RAW_SQL = "SELECT * from commandes";
        $statement = $em->getConnection()->prepare($RAW_SQL);
        $statement->execute();
        $result = $statement->fetchAll();
        //var_dump((Object)$result[0]['user_id']);
        //die();
        return  $result;
    }

    public function myfind_user_id($userid)
    {
        $em = $query = $this->getEntityManager();
        $RAW_SQL = "SELECT * from commandes c join commande e on c.commande_id=e.id where c.user_id =:userid ";
        $statement = $em->getConnection()->prepare($RAW_SQL);
        $statement->bindValue('userid',$userid);
        $statement->execute();
        $result = $statement->fetchAll();
        //var_dump((Object)$result[0]['user_id']);
        //die();
        return  $result;
    }

    public function findmyQuery($queryy)
    {
        $em = $query = $this->getEntityManager();
        $RAW_SQL = $queryy;
        $statement = $em->getConnection()->prepare($RAW_SQL);
        $statement->execute();
        $result = $statement->fetchAll();
        //var_dump((Object)$result[0]['user_id']);
        //die();
        return  $result;
    }
}
