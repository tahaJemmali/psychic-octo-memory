<?php

namespace ForumBundle\Controller;

use ForumBundle\Entity\Commentaire;
use ForumBundle\Entity\Sujet;
use ForumBundle\Form\CommentaireType;
use ForumBundle\Form\SujetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $sujets=$this->getDoctrine()->getRepository(Sujet::class)->findAll();
        $sujet=new Sujet();
        $form=$this->createForm(SujetType::class,$sujet);
        $form=$form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em=$this->getDoctrine()->getManager();
            $sujet->setDateCreation(new \DateTime('now'));
            $sujet->setUser( $this->get('security.token_storage')->getToken()->getUser());
            $em->persist($sujet);
            $em->flush();
            return $this->redirectToRoute('forum_homepage');
        }
        $form=$form->createView();
        return $this->render('@Forum/Default/index.html.twig',compact("form",'sujets'));
    }

    public function showAction(Request $request,$id)
    {
        $array=[];
        $commentaires=$this->getDoctrine()->getRepository(Commentaire::class)->commantaires();
        foreach ($commentaires as $key  ){
            if($key->getSujet()){
            if($key->getSujet()->getId() == $id)
            {
                $array[]=$key;
            }}
        }
        $commentaires=$array;
        $sujet=$this->getDoctrine()->getRepository(Sujet::class)->find($id);
        $commentaire=new Commentaire();
        $form=$this->createForm(CommentaireType::class,$commentaire);
        $form=$form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em=$this->getDoctrine()->getManager();
            $commentaire->setDateCreation(new \DateTime('now'));
            $commentaire->setUser( $this->get('security.token_storage')->getToken()->getUser());
            $commentaire->setSujet($sujet);
            $em->persist($commentaire);
            $em->flush();
            return $this->redirectToRoute('commentaire_homepage',['id'=>$sujet->getId()]);
        }
        $form=$form->createView();
        return $this->render('@Forum/Default/sujet.html.twig',compact("form",'commentaires','sujet'));
    }
}
