<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 02/02/2020
 * Time: 14:58
 */

namespace ECommerceBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ECommerceBundle\Entity\Image;
use ECommerceBundle\Entity\Product;

class ImageController extends Controller
{

    public function nextAction(Request $request)
    {
        //$ref = $_POST['ref'];
          $today = new \DateTime();

        $date = $today->format('Y-m-D');
       // $time = $dt->format('H:i:s');

        $refrence =  $request->get('refrence');
        $name =  $request->get('name');
        $category =  $request->get('category');
        $price =  $request->get('price');
        $stock =  $request->get('stock');
        $description =  $request->get('description');

        $product = new Product();

         $em = $this->getDoctrine()->getManager();

         $product->setRefrence($refrence);
         $product->setName($name);
         $product->setDate($today);
         $product->setCategory($category);
         $product->setPrice($price);
         $product->setStock($stock);
         $product->setDescription($description);


         $em->persist($product);
         $em->flush();
         $array='';
     //   echo $refrence.'   '.$name.'   '.$category.'   '.$price.'   '.$stock.'   '.$description;
        //echo $ref;
        //echo '<script> alert('.$ref.');</script>';
        //if ()
        return $this->render('@ECommerce/Default/next.html.twig',array('name'=>$name,'refrence'=>$refrence,'images'=>$array));
        //else
          //  return $this->redirectToRoute('List_Product');
       // return $this->render('@ECommerce/Default/empty.html.twig',array('amine'=>'Dridi'));

    }
    public function modifier_nextAction(Request $request)
    {

        $id =  $request->get('id');
        $refrence =  $request->get('refrence');
        $name =  $request->get('name');
        $category =  $request->get('category');
        $price =  $request->get('price');
        $stock =  $request->get('stock');
        $description =  $request->get('description');

        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        $em = $this->getDoctrine()->getManager();

        $product->setRefrence($refrence);
        $product->setName($name);
        //$product->setDate($today);
        $product->setCategory($category);
        $product->setPrice($price);
        $product->setStock($stock);
        $product->setDescription($description);

        //var_dump($product);
        //die();

        $em->persist($product);
        $em->flush();

        $array = $em->getRepository(Image::class)->FindAllByProductRefrence($refrence);

        //   echo $refrence.'   '.$name.'   '.$category.'   '.$price.'   '.$stock.'   '.$description;
        //echo $ref;
        //echo '<script> alert('.$ref.');</script>';
        return $this->render('@ECommerce/Default/next.html.twig',array('name'=>$name,'refrence'=>$refrence,'images'=>$array));

    }

    public function remove_imageAction()
    {
        $id = $_POST['id'];

        $rm = $this->getDoctrine()->getManager();
        $imageid=$rm->getRepository(Image::class)->find($id);

        $rm->remove($imageid);
        $rm->flush();

        $refrence = $_POST['refrence'];
        $em = $this->getDoctrine()->getManager();
        $array = $em->getRepository(Image::class)->FindAllByProductRefrence($refrence);

       // echo 'done after remove';
        /*$ouyput = '';
        $i=0;
        while ($array[$i]!=null)
        {
            //$ouyput+='<img src='.'"{{ asset"'.'" }}"'.'  style='.'"height: 150px"'.' class='.'"img-thumbnail"'.'>';
            $ouyput.= '<input type="text" value="liu">';
            $i++;
        }

    <button type="button"  class="btn btn-link remove_images" id="{{ image.id }}">Remove</button>
    <input type="hidden" value="{{ refrence }}" id="refe">

        echo $ouyput;*/
        return $this->render('@ECommerce/Default/emty.html.twig',array('images'=>$array,'refrence'=>$refrence));

    }

    public function imAction(Request $request)
    {
        $refrence =  $request->get('refrence');
        $em = $this->getDoctrine()->getManager();
        //$arramaybe = $em->getRepository(Product::class)->FindOneByRefrence($refrence);
       // var_dump($arramaybe[0]);

        /*$image = new Image();

        $em = $this->getDoctrine()->getManager();

        $image->setProduct($arramaybe[0]);
        $image->setImage('img');
        $image->setOrdrer('default');

        $em->persist($image);
        $em->flush();*/

        // var_dump($request);
        //echo 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA';
       //   var_dump($_FILES);
        //echo 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA';
        //var_dump($_FILES['files']['name']);
        $result = '';
        $TargetPath='';
        $array[]='';
        $i=0;
        if(is_array($_FILES))
        {
            foreach ($_FILES['files']['name'] as $name => $value)
            {
                $my_file_name = explode(".", $_FILES['files']['name'][$name]);
                $extension_name = array("jpg", "jpeg", "png", "gif");
                if(in_array($my_file_name[1], $extension_name))
                {
                    $NewImageName = md5(rand()) . '.' . $my_file_name[1];
                    $SourcePath = $_FILES['files']['tmp_name'][$name];
                    $TargetPath = "Products/".$NewImageName;
                    //$array[$i]=$TargetPath;
                    //$i++;
                    /* adding images */

                    $image = new Image();

                    $em = $this->getDoctrine()->getManager();

                    $image->setImage($TargetPath);
                    $image->setOrdrer('default');
                    $image->setProduct($em->getRepository(Product::class)->FindOneByRefrence($refrence)[0]);

                    $em->persist($image);
                    $em->flush();

                    move_uploaded_file($SourcePath, $TargetPath);

                    /* adding images */

                    /*if(move_uploaded_file($SourcePath, $TargetPath))
                    {
                        $result .= '<div class="col-md-4"><img src="'.$TargetPath.'" class="img-responsive"></div>';
                        // $result .= '<div class="col-md-4"><img src="/web/img.png" class="img-responsive"></div>';

                    }*/
                }
            }

            //echo $result;
        }
      //  echo 'tre' ;
      //  echo $SourcePath ;
        //echo 'cbo';
        $array = $em->getRepository(Image::class)->FindAllByProductRefrence($refrence);
        //var_dump($array);
        //echo $result;

        return $this->render('@ECommerce/Default/emty.html.twig',array('images'=>$array,'refrence'=>$refrence));

        //var_dump($request);
        /******************************************************/

        /* var_dump($_FILES);

         echo 'Thehe ;'.$request->get('cin_im');
         echo 'Thehe ;'.$request->get('filename');
         //$datas = 'fahd_ajax';
         var_dump($request);*/

        //echo $datas;
        //  echo '<script> alert("fez");</script>';
        //return $this->render('@ECommerce/Default/next.html.twig',array('datas'=>$datas));

        //  echo $_FILES["file"]["name"] ;

        /*  if($_FILES["file"]["name"] != '')
          {
              $cin=$_POST['cin_im'];
              $ECommerce = explode('.', $_FILES["file"]["name"]);
              $ext = end($ECommerce);

              echo $cin.' '.$ECommerce.' '.$ext ;*/
        //  $name = rand(100, 999).'_'.$cin. '.' . $ext;
        //  $location = './images/' . $name;
        //  move_uploaded_file($_FILES["file"]["tmp_name"], $location);

        /* $sql = "INSERT INTO photos (cin,image) VALUES ('$cin','$name')";
         // execute query
//    $db = config3::getConnexion();
         $req=$db->prepare($sql);
         $req->execute();*/

        /* $sqle = "SELECT * from photos where cin ='$cin' ";
         // execute query
//    $db = config3::getConnexion();
         $liste=$db->query($sqle);
         foreach ($liste as $im)
         {
             echo '<img src="'.'./images/' . $im['image'].'" height="150" width="225" class="img-thumbnail" />
         <button type="button"  class="btn btn-link remove_image" id="'.$im['id'].'">Remove</button>
         <input type="hidden" value="'.$im['cin'].'" id="cinn">
     ';
         }*/
// class="btn btn-link remove_image"
        //echo '<img src="'.$location.'" height="150" width="225" class="img-thumbnail" />';
        // }
    }

}