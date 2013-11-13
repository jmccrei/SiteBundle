<?php

namespace CoreSys\SiteBundle\Entity;

use Doctrine\ORM\EntityRepository;
use CoreSys\SiteBundle\Entity\Config;

/**
 * ConfigRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ConfigRepository extends EntityRepository
{
    public function getConfig()
    {
        $row = $this->findOneById( 1 );
        if( empty( $row ) )
        {
            $row = new Config();
            $this->getEntityManager()->persist( $row );
            $this->getEntityManager()->flush();
        }
        return $row;
    }
}
