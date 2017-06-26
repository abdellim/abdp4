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
        //$em = $this->getDoctrine()->getManager();
        $dateD = $reservation->getDate()->format('d/m/y');
        $date = $reservation->check_dimanche($dateD);
        //if( date("w", $date ) == 6 )  return 1;
        if ($date == 1) {
            $request->getSession()->getFlashBag()->add('reservationJf', " jkl");
            return $this->render('MoradBilleterieBundle:Home:content.html.twig', array(
            'form' => $form->createView(),
            'date' => $date,
            ));
        }


        if ($request->isMethod('POST')) {
          $form->handleRequest($request);
            if ($form->isValid()) {
                // On récupère l'EntityManager
                $em = $this->getDoctrine()->getManager();
                $em->persist($reservation);

                //----- Verification de la disponibilité en fonction de la date --------//
                $date = $reservation->getDate();
                $quantite = $reservation->getQuantite();
                $nbreDeBilletParDate = $em->getRepository('MoradBilleterieBundle:Reservation')->findByQuantiteAndDate($date);

                $capactiteMusee = 7;
                $placeDispo = $capactiteMusee - $nbreDeBilletParDate;
                if ($quantite > $placeDispo) {
                    $request->getSession()->getFlashBag()->add('quantite', "La quantité maximum à été atteinte pour cette date. Il ne reste que $placeDispo place(s) pour cette date.");
                    return $this->render('MoradBilleterieBundle:Home:content.html.twig', array(
                   'form' => $form->createView(),
                    ));
                }
                else {
                    $em->flush($reservation);
                    $id = $reservation->getId();
                    return $this->redirectToRoute('morad_billeterie_coordonnes', array('id' => $id));
                }
                //-------- Fin Verification Quantité / Date --------// 
            }
        }
        
        return $this->render('MoradBilleterieBundle:Home:content.html.twig', array(
       'form' => $form->createView(),
       'date' => $date,
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

                    //Génération d'un token / billet
                    $token = bin2hex(random_bytes(10));
                    $value->setCodeReservation($token);

                    $em->persist($value);
                    $value->setReservation($reservation);
                    $em->flush($value);
                }
                //Calcul de prix total
                $price = array_sum($prix);
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