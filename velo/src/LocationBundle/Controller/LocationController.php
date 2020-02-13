<?php

namespace LocationBundle\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use LocationBundle\Entity\Location;
use LocationBundle\Entity\Velo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use LocationBundle\Form\LocationType;


class LocationController extends Controller
{
    public function addLocationAction(Request $request, $id){
        $velo = $this->getDoctrine()->getRepository(Velo::class)->find($id);
        $prixVelo = $velo->getPrix();

        $location = new Location();
        $location->setVelo($velo);
        $location->setPrix(0);
        $location->setEtat("");
        $form = $this->createForm(LocationType::class, $location)->add('valider', SubmitType::class);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $en = $this->getDoctrine()->getManager();
            $en->persist($location);
            $en->flush();

            $locationRec = $this->getDoctrine()->getRepository(Location::class)->find($location->getId());
            $duration = $location->getDateDebut()->diff($location->getDateDebut())->days;
            $locationRec->setPrix($duration * $prixVelo);
            $velo->setEtat('nonDisponible');
            $en->persist($locationRec);
            $en->persist($velo);
            $en->flush();

            return $this->render('@Location/Default/facture.html.twig', array('location'=>$location));

        }
        return $this->render('@Location/Default/addLocation.html.twig', array('form'=>$form->createView()));


    }
    public function getFactureAction(Request $request, $id)
    {
        $velo = $this->getDoctrine()->getRepository(Velo::class)->find($id);
        $prixVelo = $velo->getPrix();

        $location = new Location();
        $location->setVelo($velo);
        $location->setPrix(0);
        $location->setEtat("");
        $form = $this->createForm(LocationType::class, $location)->add('valider', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $en = $this->getDoctrine()->getManager();
            $en->persist($location);
            $en->flush();

            $locationRec = $this->getDoctrine()->getRepository(Location::class)->find($location->getId());
            $duration = $location->getDateDebut()->diff($location->getDateDebut())->days;
            $locationRec->setPrix($duration * $prixVelo);
            $velo->setEtat('nonDisponible');
            $en->persist($locationRec);
            $en->persist($velo);
            $en->flush();

            return $this->redirectToRoute('displayfacture');
        }
        return $this->render('@Location/Default/facture.html.twig', array('form'=>$form->createView()));
    }



        public function  displayLocationAction(){
        $locations=$this->getDoctrine()->getRepository(Location::class)->findAll();
        return $this->render('@Location/Default/displayLocation.html.twig',array('locations'=>$locations));

    }

    public function removeLocationAction($id){
        $location =$this->getDoctrine()->getRepository(Location::class)->find($id);
        $en = $this->getDoctrine()->getManager();
        $en->remove($location);
        $en->flush();
        return $this->redirectToRoute('displayLocation');
    }

    public function updateLocationAction(Request $request, $id){
        $location = $this->getDoctrine()->getRepository(Location::class)->find($id);
        $form = $this->createForm(LocationType::class, $location)->add('modifier',SubmitType::class)
                                                                        ->add('etat',ChoiceType::class,
                                                                        ['choices'=>[
                                                                            'retournee'=>'nonRetournee',
                                                                            'nonRetournee'=>'nonRetournee',]]);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($location);
            $em->flush();
            return $this->redirectToRoute('displayLocation');
        }
        return $this->render('@Location/Default/updateLocation.html.twig', array('form'=>$form->createView()));
    }
    public function displayFactureAction(){
        $velos = $this->getDoctrine()->getRepository(Velo::class)->findAll();
        return $this->render('@Location/Default/facture.html.twig', array('velos'=>$velos));
    }

}
