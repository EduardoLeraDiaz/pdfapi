<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DocumentRepository extends EntityRepository
{
    public function getAvaibleDocuments() {
        $result = $this->createQueryBuilder('p')
            ->select(['p.id','p.title','p.description'])
            //->from('p.documents')
            ->getQuery()
            ->getResult();

        return $result;

    }
}
