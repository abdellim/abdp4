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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{
    public function indexAction(Request $request)
    {

        $reservation = new Reservation();
        $form = $this->get('form.factory')->create(ReservationType::class, $reservation);
        $date = $reservation->getDate()->format('d/m/Y');
        $jourFerie = $reservation->isNotWorkable(time());
        if ($date == 1 || $jourFerie == true) {
            $request->getSession()->getFlashBag()->add('reservationJf', " jkl");
            return $this->render('MoradBilleterieBundle:Home:content.html.twig', array(
            'form' => $form->createView(),
            'date' => $date,
            'jourFerie' => $jourFerie,
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
                   'jourFerie' => $jourFerie,
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
       'jourFerie' => $jourFerie,
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
                    $prixCalcul = $value->getPrix($age, $journee);
                    //Ajout au tableau le montant de chaque billet
                    $prix[] = $prixCalcul;

                    //Génération d'un token / billet
                    $token = bin2hex(random_bytes(10));
                    $value->setCodeReservation($token);

                    $em->persist($value);
                    $value->setReservation($reservation);
                    $em->flush($value);
                }
                //Calcul de prix total et paiement

                $price = array_sum($prix);
                $em = $this->getDoctrine()->getManager();
                // On récupère la reservation $id pour ajouter le prix total à la reservation
                $reservation = $em->getRepository('MoradBilleterieBundle:Reservation')->find($id);
                $prixTotal = $reservation->setPrix($price);
                $em->persist($prixTotal);
                $em->flush($prixTotal);

                //On affiche la vue paiement
                return $this->redirectToRoute('morad_billeterie_checkout', array('id' => $id));
                
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


     /**

     * @Route(

     *     "/order_checkout",

     *     name="order_checkout",

     *     methods="POST"

     * )

     */
    public function checkoutAction($id)
    {
        //on recupere la reservation
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('MoradBilleterieBundle:Reservation')->find($id);
        $price = $reservation->getPrix();
        
        \Stripe\Stripe::setApiKey("sk_test_tKfLZqRevKvdjIvvIBTwYlBw");

        // Get the credit card details submitted by the form
        $token = $_POST['stripeToken'];

        // Create a charge: this will charge the user's card
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => $price, // Amount in cents
                "currency" => "eur",
                "source" => $token,
                "description" => "Paiement Stripe - OpenClassrooms Exemple"
            ));
            $this->addFlash("success","Bravo ça marche !");
             return $this->render('MoradBilleterieBundle:Home:paiementStatus.html.twig');
        } catch(\Stripe\Error\Card $e) {

            $this->addFlash("error","Snif ça marche pas :(");
             return $this->render('MoradBilleterieBundle:Home:paiementStatus.html.twig');
            // The card has been declined
        }
        return $this->render('MoradBilleterieBundle:Home:paiement.html.twig', array(
                    'price' =>$price
                    ));
    }    








}