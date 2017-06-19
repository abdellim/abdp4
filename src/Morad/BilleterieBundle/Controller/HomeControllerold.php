<?php
namespace Morad\BilleterieBundle\Controller;
use Morad\BilleterieBundle\Entity\Reservation;
use Morad\BilleterieBundle\Entity\Coordonnees;
use Morad\BilleterieBundle\Form\ReservationType;
use Morad\BilleterieBundle\Form\CoordonneesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function indexAction(Request $request)
    {
        $reservation = new Reservation();
       // $form = $this->get('form.factory')->create(ReservationType::class, $reservation);
        // Si la requête est en POST
        /*if ($request->isMethod('POST')) {
          $form->handleRequest($request);
          // On vérifie que les valeurs entrées sont correctes
          if ($form->isValid()) {        
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
          }
        }*/
    // Création de l'entité Advert

   
    $reservation->setbillet('1');
    $reservation->setEmail("luimem@gmail.com");
    $reservation->setQuantite("5");

    // Création d'une première candidature
    $coordonnees = new Coordonnees();
    $coordonnees->setTarifReduit('0');
    $coordonnees->setNom("Moi mo");
    $coordonnees->setPrenom("il");
    $coordonnees->setDateDeNaissance('1985/12/06');
    $coordonnees->setPays("fr");
    $coordonnees->setCodeReservation("fddddddr");
    //$coordonnees->setDate('2017-06-07 00:00:00');



    // On lie les candidatures à l'annonce
    $coordonnees->setReservation($reservation);
    $coordonnees->setReservation($reservation);

    // On récupère l'EntityManager
    $em = $this->getDoctrine()->getManager();

    // Étape 1 : On « persiste » l'entité
    $em->persist($reservation);

    // Étape 1 ter : pour cette relation pas de cascade lorsqu'on persiste Advert, car la relation est
    // définie dans l'entité Application et non Advert. On doit donc tout persister à la main ici.
    $em->persist($coordonnees);


    // Étape 2 : On « flush » tout ce qui a été persisté avant
    $em->flush();

   
        //$em->flush();
       /* $em = $this->getDoctrine()->getManager();
        $date = $reservation->getDate();
        $quantite = $reservation->getQuantite();
        $nbreDeBilletParDate = $em->getRepository('MoradBilleterieBundle:Reservation')->findByQuantiteAndDate($date);
        $prix = $reservation->getPrix();
        //Verification de la disponibilité en fonction de la date
        if ($nbreDeBilletParDate > 5) {
            $request->getSession()->getFlashBag()->add('info', 'La quantité maximum à été atteinte pour cette date.');
        }
        //Affiche le message si la case tarif réduit est cocher
        $tarifReduit = $reservation->getTarifReduit();
        if ($tarifReduit == true) {
            $prix = $prix/2;
            $request->getSession()->getFlashBag()->add('info', 'Tarif réduit sur présentation de justificatifs à l\'entrée du musée !');
        }*/
        
        return $this->render('MoradBilleterieBundle:Home:content.html.twig', array(
        
       // 'form' => $form->createView(),
        'reservation' =>$reservation,
        //'prix' => $prix,
        
        ));
    }

    public function coordonnesAction(Request $request) {

    
        return $this->render('MoradBilleterieBundle:Home:Coordonnees.html.twig');    
 
    }
}