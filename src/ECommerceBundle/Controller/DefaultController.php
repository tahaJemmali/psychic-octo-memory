<?php

namespace ECommerceBundle\Controller;

use ECommerceBundle\ECommerceBundle;
use ECommerceBundle\Entity\Cart;
use ECommerceBundle\Entity\Commande;
use ECommerceBundle\Entity\Image;
use ECommerceBundle\Entity\Product;
use LoginBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@ECommerce/Default/empty.html.twig');
    }

    public function testmailAction() //no
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('VeloEcommerceSite@gmail.com')
            ->setTo('fahd.larayedh@gmail.com')
            ->setBody('You should see me from the profiler!')
        ;

        $this->get('mailer')->send($message);

        return $this->render('@ECommerce/Default/empty.html.twig');
    }

    public function pdfAction() //test
    {
        $snappy = $this->get('knp_snappy.pdf');
        $snappy->setOption('no-outline', true);
        $snappy->setOption('page-size','A4');
        $snappy->setOption('encoding', 'UTF-8');

        $var = 're';
        $this->container->get('knp_snappy.pdf')->generateFromHtml(
            $this->container->get('templating')->render(
                'ECommerceBundle:Cart:Facture.html.twig',
                array(
                    'var' => $var,
                )
            ),
            'FacturePdf/f2.pdf',

            array(
                'lowquality' => false,
                'encoding' => 'utf-8',
                'images' => true,
            ));

        return $this->redirectToRoute('homepage');

    }

    public function Recherche_backAction()
    {
        //apigooglestat
        $connect = mysqli_connect('localhost','root','','velo');
        $query = "SELECT name,stock as number FROM product GROUP BY id ORDER by stock desc";
        $result = mysqli_query($connect, $query);
        $output='';
        while($row = mysqli_fetch_array($result))
        {
            if($row["number"] > 0)
                $output.= "['".$row["name"]."', ".$row["number"]."],";
            else
                $output.= "['".$row["name"]."', 0],";
        }
        //apigooglestat

        $where ="";
        if (!empty($_POST['id']) || !empty($_POST['reference']) || !empty($_POST['categorie']) || !empty($_POST['nom']) || !empty($_POST['mmin']) || !empty($_POST['mmax']) || !empty($_POST['qmin']) || !empty($_POST['qmax']) )
        {
            $where=$where." where ";
        }
        //$num_id=intval($_POST['id']);
        if (!empty($_POST['id']) && ctype_digit($_POST['id']) ) { // ICI 27

            $where = "where "."id=".$_POST["id"]."";
            if (!empty($_POST['reference']) || !empty($_POST['categorie']) || !empty($_POST['nom']) || !empty($_POST['mmin']) || !empty($_POST['mmax']) || !empty($_POST['qmin']) || !empty($_POST['qmax']) )
            {$where=$where." AND ";}
        }
        if (!empty($_POST['reference'])) {
            $where = $where."refrence = '".$_POST["reference"]."'";
            if (!empty($_POST['categorie']) || !empty($_POST['nom']) || !empty($_POST['mmin']) || !empty($_POST['mmax']) || !empty($_POST['qmin']) || !empty($_POST['qmax']))
            {$where=$where." AND ";}
        }
        if (!empty($_POST['nom'])) {
            $where = $where."name= '".$_POST["nom"]."'";
            if (!empty($_POST['categorie']) || !empty($_POST['mmin']) || !empty($_POST['mmax']) || !empty($_POST['qmin']) || !empty($_POST['qmax']) )
            {$where=$where." AND ";}
        }
        if (!empty($_POST['categorie'])) {
            $where = $where."category= '".$_POST["categorie"]."'";
            if ( !empty($_POST['mmin']) || !empty($_POST['mmax']) || !empty($_POST['qmin']) || !empty($_POST['qmax']) )
            {$where=$where." AND ";}
        }
        if ( !empty($_POST['mmax']) || !empty($_POST['mmin']) )
        {
            $where = $where."price BETWEEN ".$_POST["mmin"]."".' AND '.$_POST["mmax"]."";
            if ( (!empty($_POST['qmin']) || !empty($_POST['qmax'])) )
            {$where=$where." AND ";}
        }
        if ( !empty($_POST['qmax']) || !empty($_POST['qmin']) )
        {
            $where = $where."stock BETWEEN ".$_POST["qmin"]."".' AND '.$_POST["qmax"]."";
        }

        $query = "SELECT * FROM product ".$where." ";
        //echo $query ;

        $rm = $this->getDoctrine()->getManager();
        $products=$rm->getRepository(Product::class)->findQuerySearsh($where);
        $images = $this->getDoctrine()->getRepository(Image::class)->findAll();

        /* try{
             $resultquery=$db->query($query);
         }
         catch (Exception $e){
             die('Erreur: '.$e->getMessage());
         }*/

       // $output = '';

        return $this->render('@ECommerce/Fonction/Recherche_back_product.html.twig',array(
            'query'=>$query,
            'id'=>$_POST['id'],
            'reference'=>$_POST['reference'],
            'categorie'=>$_POST['categorie'],
            'nom'=>$_POST['nom'],
            'mmin'=>$_POST['mmin'],
            'mmax'=>$_POST['mmax'],
            'qmin'=>$_POST['qmin'],
            'qmax'=>$_POST['qmax'],
            'Products'=>$products,
            'Images'=>$images,
            'output'=>$output
        ));
    }

    public function Sort_backAction()
    {
        //apigooglestat
        $connect = mysqli_connect('localhost','root','','velo');
        $query = "SELECT name,stock as number FROM product GROUP BY id ORDER by stock desc";
        $result = mysqli_query($connect, $query);
        $output='';
        while($row = mysqli_fetch_array($result))
        {
            if($row["number"] > 0)
                $output.= "['".$row["name"]."', ".$row["number"]."],";
            else
                $output.= "['".$row["name"]."', 0],";
        }
        //apigooglestat

        $sort_var = $_POST["column_name"];
        if ($sort_var == 'reference')
            $sort_var= 'refrence';
        else if ($sort_var == 'categorie')
            $sort_var= 'category';
        else if ($sort_var == 'prix')
            $sort_var= 'price';
        else if ($sort_var == 'quantite')
            $sort_var= 'stock';
        else if ($sort_var == 'nom')
            $sort_var= 'name';

        $var ='';
        if (isset($_POST['query'])) {$var =$_POST['query']; }
        $query = "SELECT * FROM product ORDER BY ".$sort_var." ".$_POST["order"]." ";
        if (!empty($_POST['query']))
        {
            $query=$_POST['query']." ORDER BY ".$sort_var." ".$_POST["order"]." ";
        }

        $rm = $this->getDoctrine()->getManager();
        $products=$rm->getRepository(Product::class)->findQuerySort($query);
        $images = $this->getDoctrine()->getRepository(Image::class)->findAll();

        $order = $_POST["order"];
        if($order == 'desc')
        {
            $order = 'asc';
        }
        else
        {
            $order = 'desc';
        }

        return $this->render('@ECommerce/Fonction/Sort_back_product.html.twig',array(
            'Products'=>$products,
            'Images'=>$images,
            'var'=>$var,
            'order'=>$order,
            'output'=>$output
        ));
    }

    public function Sort_commandeAction()
    {
        $sort_var = $_POST["column_name"];
        if ($sort_var == 'nom_client')
            $sort_var= 'username';

        $var ='';
        $query = "SELECT * FROM commande join commandes on commande.id=commandes.commande_id
ORDER BY ".$sort_var." ".$_POST["order"]." ";

        if ($sort_var=='username')
        {
            $query = "SELECT * FROM commande join commandes on commande.id=commandes.commande_id
                      join user on user.id=commandes.user_id
ORDER BY ".$sort_var." ".$_POST["order"]." ";

        }
       // $query = "SELECT * FROM commande ORDER BY ".$sort_var." ".$_POST["order"]." ";

        $cmds = $this->getDoctrine()->getRepository(Commande::class)->findmyQuery($query);
        $Commandes = $this->getDoctrine()->getRepository(Commande::class)->findAll();
        $clients = $this->getDoctrine()->getRepository(User::class)->findAll();

        $order = $_POST["order"];

        if($order == 'desc')
        {
            $order = 'asc';
        }
        else
        {
            $order = 'desc';
        }

        return $this->render('@ECommerce/Fonction/Sort_back_commande.html.twig',
            array('commandes'=>$Commandes,
                'cmds'=>$cmds,
                'clients'=>$clients,
                'ordre'=>$order
            ));

    }

    public function Searsh_Product_FrontAction(Request $request)
    {
        $input = $request->get('input_searsh');
        //var_dump($input);die();
        $category = $request->get('categorys');

        //cartn
        $cartn = $this->getDoctrine()->getRepository(Cart::class)->Cart_user_id($this->getUser()->getId());

        if ($category == 'Velo' || $category == 'Piece de rechange' || $category == 'Accessoire' ) {
            $products = $this->getDoctrine()->getRepository(Product::class)->findByCategoryAndInput($category,$input);
            $images = $this->getDoctrine()->getRepository(Image::class)->findAll();

            return $this->render('@ECommerce/Default/Front_list_products.html.twig',
                array('category' => $category, 'Products' => $products, 'Images' => $images,'cartn'=>$cartn));
        }
        else if ($category == 'All')
        {   $products = $this->getDoctrine()->getRepository(Product::class)->findOnlyWithInput($input);
            $images = $this->getDoctrine()->getRepository(Image::class)->findAll();

            return $this->render('@ECommerce/Default/Front_list_products.html.twig',
                array('category' => $category, 'Products' => $products, 'Images' => $images,'cartn'=>$cartn));
        }
        else
            return $this->redirectToRoute('homepage'); // all products


    }
}
