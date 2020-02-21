<?php

namespace EventBundle\Controller;

use EventBundle\Entity\Trophet;
use EventBundle\Form\TrophetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

class TrophetController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventBundle:Default:index.html.twig');
    }
    public function showBackAction()
    {
        $em = $this->getDoctrine()->getManager();
        $trophets = $em->getRepository('EventBundle:Trophet')->findAll();
        return $this->render('@Event/trophet/back.html.twig',compact("trophets"));
    }

    public function addAction(Request $request)
    {   $event=new Trophet();
        $form=$this->createForm(TrophetType::class,$event);
        $form=$form->handleRequest($request);
        if ($form->isSubmitted())
        {
            foreach ($_FILES['eventbundle_trophet']['name'] as $name => $value)
            {
                $my_file_name = explode(".", $_FILES['eventbundle_trophet']['name'][$name]);
                $NewImageName = md5(rand()) . '.' . $my_file_name[1];
                $SourcePath = $_FILES['eventbundle_trophet']['tmp_name'][$name];
                $TargetPath = "uploads/".$NewImageName;
                move_uploaded_file($SourcePath, $TargetPath);
            }
            $event->setImage($TargetPath);
            $em=$this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute("list_trophet_back");
        }
        $form=$form->createView();
        return $this->render('@Event/trophet/add.html.twig',compact("form"));
    }
    public function deleteAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $event=$this->getDoctrine()->getRepository(Trophet::class)->find($id);
        $filesystem = new Filesystem();
        $filesystem->remove($event->getImage());
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute("list_trophet_back");
    }
    public function editAction(Request $request,$id){
        $evenement = $this->getDoctrine()->getRepository(Trophet::class)->find($id);
        $evenement->setImage(null);
        $form= $this->createForm(TrophetType::class,$evenement);
        $form=$form->handleRequest($request);

        if ($form->isSubmitted())
        {
            foreach ($_FILES['eventbundle_trophet']['name'] as $name => $value)
            {
                $my_file_name = explode(".", $_FILES['eventbundle_trophet']['name'][$name]);
                $NewImageName = md5(rand()) . '.' . $my_file_name[1];
                $SourcePath = $_FILES['eventbundle_trophet']['tmp_name'][$name];
                $TargetPath = "uploads/".$NewImageName;
                move_uploaded_file($SourcePath, $TargetPath);
            }
            $evenement->setImage($TargetPath);
            $em= $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em ->flush();
            return $this->redirectToRoute("list_trophet_back");
        }
        $form=$form->createView();
        return $this->render('@Event/trophet/edit.html.twig',compact("form"));
    }
}
