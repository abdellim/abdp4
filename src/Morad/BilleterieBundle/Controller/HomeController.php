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
        $dateDay = $reservation->getDate()->format('d/m/Y');
        $date = $reservation->checkDimanche($dateDay);
        $jourFerie = $reservation->isNotWorkable(time());
        if ($date == 1 || $jourFerie === true) {
            $request->getSession()->getFlashBag()->add('reservationJf', "");
            return $this->render('MoradBilleterieBundle:Home:content.html.twig', array(
                'form' => $form->createView(),
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

                $capactiteMusee = 1000;
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
 


        //Boucle pour créer x objet coordonnees en fonction de la quantité
        for ($i=0; $i < $quantite ; $i++) {
            $data["coordonnees"][] = new Coordonnees();  
        }
        $formCordonnees = $this->get('form.factory')->create(CoordonneesRelaiType::class, $data);

        if ($request->isMethod('POST')) {
            $formCordonnees->handleRequest($request);
            if ($formCordonnees->isValid()) {
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

                if ($price == 0) {
                    $request->getSession()->getFlashBag()->add('ErrorPrice', "");
                    return $this->redirectToRoute('morad_billeterie_homepage');
                } else {
                //On affiche la vue paiement
                return $this->render('MoradBilleterieBundle:Home:paiement.html.twig', array(
                    'id' => $id, 
                    'price' => $price,
                ));
                }
            }
        }
        return $this->render('MoradBilleterieBundle:Home:Coordonnees.html.twig', array(
            'formCordonnees' => $formCordonnees->createView(),
            'reservation' => $reservation,
        ));
    }

 /**
     * @Route(
     *     "/checkout",
     *     name="order_checkout",
     *     methods="POST"
     * )
     */
    public function checkoutAction($id,Request $request)
    {
        //on recupere la reservation
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('MoradBilleterieBundle:Reservation')->find($id);
        $price = $reservation->getPrix();
        $date = $reservation->getDate();
        $email = $reservation->getEmail();
            
        // On récupère la reservation $id
        $id = $reservation->getId();
        $coordonnees = $em->getRepository('MoradBilleterieBundle:Coordonnees')->myFindDQL($id);

        $stripe = array(
          "secret_key"      => "sk_test_SbnNanLBqWn9GHDIvWMYQVXo",
          "publishable_key" => "pk_test_XBKuP2draWVwsYShfzBQK16G"
        );
        \Stripe\Stripe::setApiKey($stripe['secret_key']);
        $token = $request->request->get('stripeToken');

        $customer = \Stripe\Customer::create(array(
          'email' => $email,
          'source'  => $token
        ));

        // Create a charge: this will charge the user's card
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => $price*100, // Amount in cents
                "currency" => "eur",
                "customer" => $customer->id,
                "description" => "Paiement Musée du Louvre"
            ));
            $this->addFlash("paiementSuccess","");
            $message = \Swift_Message::newInstance();
            $message->setSubject("Votre réservation - Billeterie du Louvre");
            $message->setFrom('contact@louvre.fr');
            $message->setTo($email);
            // pour envoyer le message en HTML
            $message->setBody(
                $this->renderView(
                'Emails/registration.html.twig',
                array(
                    'date' => $date,
                    'price' => $price,
                    'coordonnees' => $coordonnees,
                    )
                ),
                'text/html'
            );
            $message->attach(\Swift_Attachment::fromPath('logo-louvre.png'));
            //envoi du message
            $this->get('mailer')->send($message);
            return $this->redirectToRoute('morad_billeterie_homepage');
        } catch(\Stripe\Error\Card $e) {

            $this->addFlash("paiementError","");
            return $this->redirectToRoute('morad_billeterie_homepage');
        }

        return $this->render('MoradBilleterieBundle:Home:paiement.html.twig', array(
            'price' => $price,
        ));
    }

    public function mentionsLegalesAction() {
        return $this->render('MoradBilleterieBundle:Home:mentions/mentionsLegales.html.twig');
    }

}
