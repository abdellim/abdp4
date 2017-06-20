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
        $form = $this->get('form.factory')->create(ReservationType::class, $reservation);
        
        if ($request->isMethod('POST')) {
          $form->handleRequest($request);
            if ($form->isValid()) {
                // On récupère l'EntityManager
                $em = $this->getDoctrine()->getManager();
                $em->persist($reservation);
                $em->flush($reservation);
                $id = $reservation->getId();
                return $this->redirectToRoute('morad_billeterie_coordonnes', array('id' => $id));
            }
        }
        
       /* $em = $this->getDoctrine()->getManager();
        $date = $reservation->getDate();
        $quantite = $reservation->getQuantite();
        $nbreDeBilletParDate = $em->getRepository('MoradBilleterieBundle:Reservation')->findByQuantiteAndDate($date);
        $prix = $reservation->getPrix();
        //Verification de la disponibilité en fonction de la date
        if ($nbreDeBilletParDate > 5) {
            $request->getSession()->getFlashBag()->add('info', 'La quantité maximum à été atteinte pour cette date.');
        }
*/  
        return $this->render('MoradBilleterieBundle:Home:content.html.twig', array(
       'form' => $form->createView(),

        //'prix' => $prix,
        ));
    }


    public function coordonneesAction($id, Request $request) 
    {
        $em = $this->getDoctrine()->getManager();
        // On récupère la reservation $id
        $reservation = $em->getRepository('MoradBilleterieBundle:Reservation')->find($id);
        $date = $reservation->getDate();
        $journee = $reservation->getbillet();
        $quantite = $reservation->getQuantite();

        //Boucle pour créer x objet coordonnees en fonction de la quantité
        for ($i=0; $i < $quantite ; $i++) { 
            $coordonnees = new Coordonnees();
            $formi = $this->get('form.factory')->create(CoordonneesType::class, $coordonnees);
        }




        if ($request->isMethod('POST')) {
            $formi->handleRequest($request);
            if ($formi->isValid()) {
                // On récupère l'EntityManager
                $em = $this->getDoctrine()->getManager();
                // On lie coordonnees et reservation
                $coordonnees->setReservation($reservation);
                $em->persist($coordonnees);
                $tableauReservation[] = $coordonnees;
                $em->flush($coordonnees);
            }    
        }

        
       $tableauReservation[] = $coordonnees;



        //Calcul de l'age
        $datetime2 = $coordonnees->getDateDeNaissance();
        if (is_null($datetime2)) {
            $datetime1 = $date;
            $datetime2 = $coordonnees->getDateDeNaissance();
            $age = '';
        } else {
            $datetime1 = $date;
            $datetime2 = $coordonnees->getDateDeNaissance();
            $age = $datetime1->diff($datetime2, true)->y;        
        }

        // Calcul du prix en fonction de l'age
        $prix = 0;

        if ($age >= 12) {
            $prix = 16;
        }
        if ($age >= 4 && $age < 12) {
            $prix = 8;
        }
        if ($age > 60) {
            $prix = 12;
        }
        if ($journee == 0) {
           $prix = $prix/2;
        }
        if ($prix != 0) {
            $prix = $prix*$quantite;
        }
        $tarifReduit = $coordonnees->getTarifReduit();
        if ($tarifReduit == true) {
            $prix = $prix/2;
        }

        return $this->render('MoradBilleterieBundle:Home:Coordonnees.html.twig', array(
        'formi' => $formi->createView(),
        'id' => $id,
        'reservation' => $reservation,
        'quantite' => $quantite,
        'prix' => $prix,
        'tableauReservation' => $tableauReservation,
        ));
    }
}