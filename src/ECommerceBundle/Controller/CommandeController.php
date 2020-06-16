<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/02/2020
 * Time: 18:59
 */

namespace ECommerceBundle\Controller;


use ECommerceBundle\Entity\Cart;
use ECommerceBundle\Entity\Commande;
use ECommerceBundle\Form\CommandeType;
use LoginBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommandeController extends Controller
{
    public function checkout_confirmAction(Request $request)
    {
        //var_dump($request);
        //die();

        // if cart is null redirect to Cart
        $user = $this->getUser();
        $rm = $this->getDoctrine()->getManager();
        $articles = $rm->getRepository(Cart::class)->Cart_user_id($user->getId());
        if ($articles == null)
            return $this->redirectToRoute('Cart');
        // if cart is null redirect to Cart

        $user = $this->getUser();

        $commande = new Commande();
        $commande->setDescription('');
        $commande->setEtat('Nouvelle Commande');
        $commande->setPaiment($request->get('payment_radio'));

        //create new Commande

        $commande->addCommandes($user);
        //add table Commandes

        $rm = $this->getDoctrine()->getManager();

        $rm->persist($commande);
        $rm->flush();

        //delete panier
        $articles = $rm->getRepository(Cart::class)->Cart_user_id($user->getId());

        //recuperer les ids des items avec leurs quantités

        //create facture
        $snappy = $this->get('knp_snappy.pdf');
        $snappy->setOption('no-outline', true);
        $snappy->setOption('page-size','A4');
        $snappy->setOption('encoding', 'UTF-8');

        //$var = '';

        $this->container->get('knp_snappy.pdf')->generateFromHtml(
            $this->container->get('templating')->render(
                'ECommerceBundle:Cart:Facture.html.twig',
                array(
                    'articles' => $articles,
                    'total_cmd'=>$request->get('totalpanier'),
                    'request'=>$request,
                    'id_commande'=>$commande->getId(),

                    'checkout_country'=>$request->get('checkout_country'),
                    'checkout_address'=>$request->get('checkout_address'),
                    'checkout_address_2'=>$request->get('checkout_address_2'),
                    'checkout_zipcode'=>$request->get('checkout_zipcode'),
                    'checkout_phone'=>$request->get('phone'),
                    'checkout_email'=>$request->get('email'),

            )),
            'FacturePdf/'.$commande->getId().'_'.$user->getId().'.pdf',

            array(
                'lowquality' => false,
                'encoding' => 'utf-8',
                'images' => true,
            ));
        // return $this->redirectToRoute('homepage');
        //create facture

        foreach ($articles as $key)
        {
            $rm->remove($key);
        }
        $rm->flush();

        //send facture by email ;
        $message = (new \Swift_Message('Bonjour '.$user->getUsername().' !'))
            ->setFrom('VeloEcommerceSite@gmail.com')
            ->setTo($user->getEmail())
            ->setBody('Voice votre facture :')
            ->attach(\Swift_Attachment::fromPath('FacturePdf/'.$commande->getId().'_'.$user->getId().'.pdf'));
        ;
        $this->get('mailer')->send($message);

        //return new \Symfony\Component\HttpFoundation\Response('DONE !');
        return $this->redirectToRoute('fos_user_profile_commande');

    }
    public function List_CommandeAction()
    {
        $cmds = $this->getDoctrine()->getRepository(Commande::class)->myfind();
        $Commandes = $this->getDoctrine()->getRepository(Commande::class)->findAll();
        $clients = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('@ECommerce/Default/list_commande.html.twig',
            array('commandes'=>$Commandes,
                'cmds'=>$cmds,
                'clients'=>$clients
            ));
    }

    public function delete_commandeAction($id_commande)
    {
       // echo $id_commande ;
        //die();
        $rm = $this->getDoctrine()->getManager();
        $commande=$rm->getRepository(Commande::class)->find($id_commande);

        $rm->remove($commande);
        $rm->flush();

        return $this->redirectToRoute('List_Commande');
    }

    public function modify_commandeAction($id_commande,$clientid,Request $request)
    {
        $commande = $this->getDoctrine()->getRepository(Commande::class)->find($id_commande);
        $form = $this->createForm(CommandeType::class,$commande);
        /* ->add('Modify',SubmitType::class,
             ['label'=> 'Modify Product']);*/

        $form = $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();
            return $this->redirectToRoute('List_Commande');
        }
        //var_dump($id);
        //die();
        return $this->render('@ECommerce/Default/Modify_commande.html.twig',array(
            'form' => $form->createView(),
            'id_commande'=>$id_commande,
            'id_client'=>$clientid
        ));

    }
    public function fos_user_profile_commandeAction()
    {

        $authChecker=$this->container->get('security.authorization_checker');

        if ($authChecker->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute("admin");
        }
        else if ($authChecker->isGranted('ROLE_USER'))
        {
            $user = $this->getUser();
            $rm = $this->getDoctrine()->getManager();
            $commandes=$rm->getRepository(Commande::class)->myfind_user_id($user->getId());

            return $this->render('@ECommerce/Default/MesCommande.html.twig',
                array(
                    'user'=>$user,
                    'commandes'=>$commandes
                ));
        }
        else
        {
            return $this->redirectToRoute("fos_user_security_login");
        }

    }

}