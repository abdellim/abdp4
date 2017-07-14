<?php

namespace Morad\BilleterieBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * ReservationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReservationRepository extends \Doctrine\ORM\EntityRepository
{
  public function findByQuantiteAndDate($date)

	{
		$select =  $this->createQueryBuilder('a')
		 ->select ('a.quantite')
		 ->where('a.date = :date')->setParameter('date', $date)
		 ->getQuery()
		 ->getArrayResult();
		$messi = count($select);

		$messi = 0;
		foreach($select AS $article) {
	    $messi += $article['quantite'];
		}
		 
		return $messi;
	}
}