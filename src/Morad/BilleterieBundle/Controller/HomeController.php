<?php
namespace Morad\BilleterieBundle\Controller;
use Morad\BilleterieBundle\Entity\Reservation;
use Morad\BilleterieBundle\Entity\Coordonnees;
use Morad\BilleterieBundle\Form\ReservationType;
use Morad\BilleterieBundle\Form\CoordonneesType;
use Morad\BilleterieBundle\Form\CoordonneesRelaiType;
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
        ));
    }


    public function coordonneesAction($id, Request $request) 
    {
        $em = $this->getDoctrine()->getManager();
        // On récupère la reservation $id
        $reservation = $em->getRepository('MoradBilleterieBundle:Reservation')->find($id);
        $date = '';
        $journee = $reservation->getbillet();
        $quantite = $reservation->getQuantite();
        $id = $reservation->getID();
        $price = 0;
        $prix = 0;
        $age = '';

        //Boucle pour créer x objet coordonnees en fonction de la quantité
        for ($i=0; $i < $quantite ; $i++) {
            $data["coordonnees"][] = new Coordonnees();  
        }
        $formi = $this->get('form.factory')->create(CoordonneesRelaiType::class, $data);

        if ($request->isMethod('POST')) {
            $formi->handleRequest($request);
            if ($formi->isValid()) {
                $prix = array();
                foreach ($data["coordonnees"] as $value){
                    //Calcul de l'age
                    $date = $reservation->getDate();
                    $datetime2 = $value->getDateDeNaissance();
                    if (is_null($datetime2)) {
                        $datetime1 = $date;
                        $datetime2 = $value->getDateDeNaissance();
                        $age = '';
                    } else {
                        $datetime1 = $date;
                        $datetime2 = $value->getDateDeNaissance();
                        $age = $datetime1->diff($datetime2, true)->y;        
                    }
                    //Calcul du prix
                    $prixi = $value->getPrix($age, $journee);
                    //Ajout au tableau le montant de chaque billet
                    $prix[] = $prixi;
                    //Calcul de prix total
                    $price = array_sum($prix);

                    $em->persist($value);
                    $value->setReservation($reservation);
                    $em->flush($value);
                }
            }
        }

        return $this->render('MoradBilleterieBundle:Home:Coordonnees.html.twig', array(
        'formi' => $formi->createView(),
        'id' => $id,
        'reservation' => $reservation,
        'data' => $data,
        'date' => $date,
        'age' => $age,
        'price' => $price,
        ));
    }
}