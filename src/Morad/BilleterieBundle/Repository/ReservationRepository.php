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
    // Méthode 1 : en passant par l'EntityManager
    $queryBuilder = $this->_em->createQueryBuilder()
      ->select('a')
      ->from($this->_entityName, 'a')
    ;
    // Dans un repository, $this->_entityName est le namespace de l'entité gérée
    // Ici, il vaut donc OC\PlatformBundle\Entity\Advert
    // Méthode 2 : en passant par le raccourci (je recommande)
    $queryBuilder = $this->createQueryBuilder('a');
    // On n'ajoute pas de critère ou tri particulier, la construction
    // de notre requête est finie
    // On récupère la Query à partir du QueryBuilder
    $query = $queryBuilder->getQuery();
    // On récupère les résultats à partir de la Query
    $results = $query->getResult();
    // On retourne ces résultats
    return $results;
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


}
