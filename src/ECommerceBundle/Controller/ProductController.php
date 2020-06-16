<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 01/02/2020
 * Time: 20:36
 */

namespace ECommerceBundle\Controller;


use ECommerceBundle\Entity\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ECommerceBundle\Entity\Image;
use ECommerceBundle\Entity\Product;
use ECommerceBundle\Form\ProductType;

class ProductController extends Controller
{

    public function Add_ProductAction(Request $request)
    {
        //$today = new \DateTime();
        $Product = new Product();
        $form = $this->createForm(ProductType::class,$Product);

        $form = $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //$em = $this->getDoctrine()->getManager();
            //$Product->setDate($today);
            //$em->persist($Product);
            //$em->flush();

            return new Response(json_encode(array('status'=>'success')));

        }

        return $this->render('@ECommerce/Default/index.html.twig',array('form' => $form->createView()));
    }

    public function testAction(Request $request)
    {
        echo 'start ECommerce';
        $em = $this->getDoctrine()->getManager();
        $array = $em->getRepository(Image::class)->FindAllByProductRefrence('test');
        //var_dump($array);


        return $this->render('@ECommerce/Default/empty.html.twig');
    }

    public function List_ProductAction()
    {
        $Products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        $images = $this->getDoctrine()->getRepository(Image::class)->findAll();

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

        return $this->render('@ECommerce/Default/list.html.twig',array(
            'Products'=>$Products,
            'Images'=>$images,
            'results'=>json_encode($output),
            'output'=>$output
        ));
    }

    public function Delete_ProductAction($id)
    {
        $rm = $this->getDoctrine()->getManager();
        $product=$rm->getRepository(Product::class)->find($id);

        $images=$rm->getRepository(Image::class)->findByProducts($id);

        foreach ($images as $key)
        {
            $rm->remove($key);
        }
        $rm->remove($product);

        $rm->flush();

        return $this->redirectToRoute('List_Product');
    }

    public function Modify_ProductAction($id,Request $request)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductType::class,$product);
           /* ->add('Modify',SubmitType::class,
                ['label'=> 'Modify Product']);*/

        $form = $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('List_Product');
        }
        //var_dump($id);
        //die();
        return $this->render('@ECommerce/Default/Modify.html.twig',array(
            'form' => $form->createView(),'id_product'=>$id
        ));
    }

    public function List_Product_FrontAction($category)
    {   //cartn
        $cartn = $this->getDoctrine()->getRepository(Cart::class)->Cart_user_id($this->getUser()->getId());

        if ($category == 'Velo' || $category == 'Piece de rechange' || $category == 'Accessoire' ) {
            $products = $this->getDoctrine()->getRepository(Product::class)->findByCategory($category);
            $images = $this->getDoctrine()->getRepository(Image::class)->findAll();

            return $this->render('@ECommerce/Default/Front_list_products.html.twig',
                array('category' => $category, 'Products' => $products, 'Images' => $images,'cartn'=>$cartn));
        }
        else if ($category == 'All')
        {   $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
            $images = $this->getDoctrine()->getRepository(Image::class)->findAll();

            return $this->render('@ECommerce/Default/Front_list_products.html.twig',
                array('category' => $category, 'Products' => $products, 'Images' => $images,'cartn'=>$cartn));
        }
        else
            return $this->redirectToRoute('homepage'); // all products
    }

    public function Single_Product_FrontAction($refrence,Request $request)
    {   //cartn
        $cartn = $this->getDoctrine()->getRepository(Cart::class)->Cart_user_id($this->getUser()->getId());
        //$oldroute=$request->attributes->get('_route');
        //die();
        $products = $this->getDoctrine()->getRepository(Product::class)->findByRefrence($refrence);
        if ($products!=null){
            $images = $this->getDoctrine()->getRepository(Image::class)->findAll();
            return $this->render('@ECommerce/Default/Single_list_products.html.twig',
                array('Product'=>$products,'Images'=>$images,'cartn'=>$cartn));
        }

            return $this->redirectToRoute('homepage'); // previous page
    }






}