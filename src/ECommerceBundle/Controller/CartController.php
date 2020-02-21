<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 10/02/2020
 * Time: 19:03
 */

namespace ECommerceBundle\Controller;


use ECommerceBundle\Entity\Cart;
use ECommerceBundle\Entity\Image;
use ECommerceBundle\Entity\Product;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{

    public function Add_CartAction()
    {
        $authChecker=$this->container->get('security.authorization_checker');

        if ($authChecker->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute("admin");
        }
        else if ($authChecker->isGranted('ROLE_USER'))
        {
            $User = $this->getUser();
            $User_Id = $this->getUser()->getId();
            $id_product =  $_POST['id_produit'];
            $quantite_produit = $_POST['quantite_produit'];

            //echo $user;
            //var_dump($user);
            //return new Response('Panier');
            //add
            //list his own cart
            // return to twig + array

            //$ref = $_POST['ref'];
// verify if produt exsist already in cart => only add quantity
            $cart_user = $this->getDoctrine()->getRepository(Cart::class)->Cart_user_id($User_Id);
            //var_dump($cart_user);
            $test = 0;
            foreach ($cart_user as $key)
            {
                //echo 'getProduct()->getId : '.$key->getProduct()->getId().'_              _';
                //echo '$id_product : '.$id_product .'_                      _';
                    //echo $key->getProduct()->getId() ;
                //echo $id_product;
                if ($key->getProduct()->getId() == $id_product ){
                    $test=1;
                }
               // echo 'KK  '.$key->getProduct()->getId() ;
            }
            //var_dump($test);
            //die();
            if ($test ==1)
            {
                $article = $this->getDoctrine()->getRepository(Cart::class)->Cart_user_id_product_id($User_Id,$id_product);
                $article->setQuantity($article->getQuantity()+$quantite_produit);
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();
            }
// verify if produt exsist already in cart => only add quantity
            else {
            //adding new item in cart
                $Cart = new Cart();
                $Product = $this->getDoctrine()->getRepository(Product::class)->find($id_product);
                $em = $this->getDoctrine()->getManager();
                $Cart->setProduct($Product);
                $Cart->setUser($User);
                $Cart->setQuantity($quantite_produit);
                $em->persist($Cart);
                $em->flush();
            //adding new item in cart
            }
//List items for current user
           // $cart_user = $this->getDoctrine()->getRepository(Cart::class)->Cart_user_id($User_Id);
            //var_dump($cart_user);
            //die();
            //die($cart_user);
//List items for current user

           // return $this->render('@ECommerce/Cart/List_Cart.html.twig',array('cart'=>$cart_user));

            return $this->redirectToRoute("Cart");

        }
        else
        {
            return $this->redirectToRoute("fos_user_security_login");
        }

    }

    public function CartAction()
    {
        $authChecker=$this->container->get('security.authorization_checker');

        if ($authChecker->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute("admin");
        }
        else if ($authChecker->isGranted('ROLE_USER'))
        {
            $User_Id = $this->getUser()->getId();
            $cart_user = $this->getDoctrine()->getRepository(Cart::class)->Cart_user_id($User_Id);
            $images = $this->getDoctrine()->getRepository(Image::class)->findAll();
            return $this->render('@ECommerce/Cart/List_Cart.html.twig',array('cart'=>$cart_user,'Images'=>$images));
        }
        else
        {
            return $this->redirectToRoute("fos_user_security_login");
        }
    }

    public function delete_articleAction($id_article)
    {
        $authChecker=$this->container->get('security.authorization_checker');

        if ($authChecker->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute("admin");
        }
        else if ($authChecker->isGranted('ROLE_USER'))
        {
            $rm = $this->getDoctrine()->getManager();
            $article=$rm->getRepository(Cart::class)->find($id_article);

            $rm->remove($article);
            $rm->flush();

            return $this->redirectToRoute("Cart");
        }
        else
        {
            return $this->redirectToRoute("fos_user_security_login");
        }
    }

    public function CheckoutAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        $authChecker=$this->container->get('security.authorization_checker');

        if ($authChecker->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute("admin");
        }
        else if ($authChecker->isGranted('ROLE_USER'))
        {
           /* $rm = $this->getDoctrine()->getManager();
            $article=$rm->getRepository(Cart::class)->find($id_article);

            $rm->remove($article);
            $rm->flush();
            */
          // var_dump($_POST);
            $user = $this->getUser();
            $User_Id = $user->getId();
            $cart_ids = $this->getDoctrine()->getRepository(Cart::class)->Cart_user_id($User_Id);
            if ($cart_ids==null)
                return $this->redirectToRoute("Cart");
            $em = $this->getDoctrine()->getManager();
            $total_panier = $_POST['Total_Panierez'];
            //var_dump($cart_ids);
            //die();
            foreach ($cart_ids as $key)
            {   //echo $key->getProduct()->getId();
                //echo $_POST['QUANTITEES_'.$key->getProduct()->getRefrence()];
                //die();
                if ($key->getProduct()->getId() == $_POST[$key->getProduct()->getId()])
                {
                    $article = $this->getDoctrine()->getRepository(Cart::class)->Cart_user_id_product_id($User_Id,$key->getProduct()->getId());
                   // var_dump($article);
                   // die();
                    $article->setQuantity(intval($_POST['QUANTITEES_'.$key->getProduct()->getRefrence()]));
                    $em->persist($article);

                }
            }
            $em->flush();

            if ($_POST['whichway']==0)
            return $this->redirectToRoute("List_Product_Front",array('category'=>'All')); // here
            else
                return $this->render('@ECommerce/Cart/Checkout.html.twig',
                    array('total_panier'=>$total_panier,
                        'user_username'=> $user->getUsername(),
                        'user_email'=> $user->getEmail(),
                        'user_adress'=> $user->getAddress(),
                        'user_phone'=> $user->getPhone(),
                        'cart'=>$cart_ids //chkour cartn
                    )
                );

        }


           /* $form = $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($product);
                $em->flush();*/



          // die();

        else
        {
            return $this->redirectToRoute("fos_user_security_login");
        }
    }




    public function empty_cartAction()
    {
        $User_Id = $this->getUser()->getId();

        $rm = $this->getDoctrine()->getManager();
        $articles_user=$rm->getRepository(Cart::class)->findUser_id($User_Id);

        foreach ($articles_user as $key)
        {
            $rm->remove($key);
            $rm->flush();
        }

        return $this->redirectToRoute("Cart");
    }




}