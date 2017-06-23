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
  public function myFindAll()
  {

  }

  public function findByQuantiteAndDate($date)

	{
		$select =  $this->createQueryBuilder('a')
		 ->select ('a.quantite')
		 ->where('a.date = :date')->setParameter('date', $date)
		 ->getQuery()
		 ->getArrayResult();
		$messi = count($select);
		/*for ($i=0; $i <$messi ; $i++) { 
			$select;
		}*/
		$messi = 0;
		foreach($select AS $article) {
	    $messi += $article['quantite'];
		}

		
		 
		return $messi;
	}

  public function getAge($id)

  {
    $select =  $this->createQueryBuilder('a')
     ->select ('a.quantite')
     ->where('a.reservtation_id = :$id')->setParameter('date', $date)
     ->getQuery()
     ->getArrayResult();
    $messi = count($select);
    /*for ($i=0; $i <$messi ; $i++) { 
      $select;
    }*/
    $messi = 0;
    foreach($select AS $article) {
      $messi += $article['quantite'];
    }

    
     
    return $messi;
  }


}
