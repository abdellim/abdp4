<?php

namespace Morad\BilleterieBundle\Controller;

use Morad\BilleterieBundle\Entity\Reservation;
use Morad\BilleterieBundle\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function indexAction(Request $request)
    {
        $reservation = new Reservation();
        $form = $this->get('form.factory')->create(ReservationType::class, $reservation);

        // Si la requête est en POST
        if ($request->isMethod('POST')) {
          $form->handleRequest($request);
          // On vérifie que les valeurs entrées sont correctes
          if ($form->isValid()) {
            $LesReservation = [];
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
          }
        }

        $em = $this->getDoctrine()->getManager();
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
        }
        
        return $this->render('MoradBilleterieBundle:Home:content.html.twig', array(
        'form' => $form->createView(),
        'nbreDeBilletParDate' =>$nbreDeBilletParDate,
        'prix' => $prix,
        ));
    }
}
