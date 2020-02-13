<?php

namespace LocationBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use LocationBundle\Entity\Velo;
use LocationBundle\Form\VeloType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VeloController extends Controller
{
    public function addVeloAction(Request $request){
        $velo = new Velo();
        $form = $this->createForm(VeloType::class, $velo);
        $form->add('ajouter', SubmitType::class);
        $form->handleRequest($request);

        if($form->isSubmitted()){

            foreach ($_FILES['locationbundle_velo']['name'] as $name => $value)
            {
                $my_file_name = explode(".", $_FILES['locationbundle_velo']['name'][$name]);

                //{
                $NewImageName = md5(rand()) . '.' . $my_file_name[1];
                $SourcePath = $_FILES['locationbundle_velo']['tmp_name'][$name];
                $TargetPath = "ImagesVelosLocation/".$NewImageName;

                move_uploaded_file($SourcePath, $TargetPath);

            }

            $today = new \DateTime();
            $velo->setEtat('disponible');
            $velo->setDateMaintenance($today);
            $velo->setImage($TargetPath);


            $en = $this->getDoctrine()->getManager();
            $en->persist($velo);
            $en->flush();

            return $this->redirectToRoute('displayVelo');
        }
        return $this->render('@Location/Default/addVelo.html.twig', array('form'=>$form->createView()));
    }
    public function displayVeloAction(){
        $velos = $this->getDoctrine()->getRepository(Velo::class)->findAll();
        return $this->render('@Location/Default/displayVelo.html.twig', array('velos'=>$velos));
    }

    public function ListforClientAction(){
        $velos = $this->getDoctrine()->getRepository(Velo::class)->findAll();
        return $this->render('@Location/Default/interfaceVelo.html.twig', array('velos'=>$velos));
    }

    public function removeVeloAction($id){
        $velo = $this->getDoctrine()->getRepository(Velo::class)->find($id);
        $en=$this->getDoctrine()->getManager();
        $en->remove($velo);
        $en->flush();

        return $this->redirectToRoute('displayVelo');
    }

    public function updateVeloAction(Request $request, $id){

        $velo = $this->getDoctrine()->getRepository(Velo::class)->find($id);
        $velo->setImage('');
        $form = $this->createForm(VeloType::class, $velo);
        $form->add('etat', ChoiceType::class, ['choices'=>[
                                                        'Disponible'=>'disponible',
                                                        'nonDisponible'=>'nonDisponible',]])
             ->add('Modifier', SubmitType::class);


        $form->handleRequest($request);
        if($form->isSubmitted()){

            foreach ($_FILES['locationbundle_velo']['name'] as $name => $value)
            {
                $my_file_name = explode(".", $_FILES['locationbundle_velo']['name'][$name]);

                //{
                $NewImageName = md5(rand()) . '.' . $my_file_name[1];
                $SourcePath = $_FILES['locationbundle_velo']['tmp_name'][$name];
                $TargetPath = "ImagesVelosLocation/".$NewImageName;

                move_uploaded_file($SourcePath, $TargetPath);

            }

            $today = new \DateTime();
            $velo->setEtat('disponible');
            $velo->setDateMaintenance($today);
            $velo->setImage($TargetPath);
            $en = $this->getDoctrine()->getManager();
            $en->persist($velo);
            $en->flush();

            return $this->redirectToRoute('displayVelo');
        }
        return $this->render('@Location/Default/updateVelo.html.twig', array('form'=>$form->createView()));
    }
}
