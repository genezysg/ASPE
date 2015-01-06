<?php
namespace AppBundle\Entity;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use AppBundle\AppBundle;

class ProductRepository extends EntityRepository
{
	
	public function findAllOrderedByName(){
		return $this->getEntityManager()
			->createQuery(
				'SELECT p FROM AppBundle:Product p ORDER BY p.name ASC'
			)
			->getResult();
	}
}