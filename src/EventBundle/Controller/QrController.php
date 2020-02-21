<?php

namespace EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Convertio\Convertio;


class QrController extends Controller
{

    public function QR($msg)
    {
        $options = array(
            'code'   => $msg,
            'type'   => 'qrcode',
            'format' => 'svg',
            'width'  => 10,
            'height' => 10,
            'color'  => 'black',
        );

        $barcode = $this->get('skies_barcode.generator')->generate($options);
        $savePath = __DIR__ . '/../../../web/taha/QR/';
        $fileName = 'qr.html';
        $QR=$savePath.$fileName;
        file_put_contents($QR, $barcode);
        $API = new Convertio("febd0d9fd2495c01c7da565a297baff2");
        $API->settings(array('api_protocol' => 'http', 'http_timeout' => 10));
        $API->start($QR, 'jpg')->wait()->download($savePath.'qr.jpg')->delete();
        $filesystem = new Filesystem();
        $filesystem->remove($QR);
        $QR=__DIR__ . '/../../../web/taha/QR/qr.jpg';
        return $QR;
    }



    public function mailAction($id)
    {

        $event=$this->getDoctrine()->getRepository('EventBundle:Evenement')->find($id);
        $users = $this->getDoctrine()->getRepository('LoginBundle:User')->inviter($id);
        if (!empty($users))
       {
            foreach ($users as $key  ){
                $key = $this->getDoctrine()->getRepository('LoginBundle:User')->find($key['id']);
                $name=$key->getUsername();
$msg="C'est Mr/Mme ".$key->getUsername()." et de mail ".$key->getEmail()." d'id ".$key->getId()." qui participe à l'évenement d'id ".$id;


          $QR=$this->QR($msg);//25 par jour seulement Convertio
        $path = $QR;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = base64_encode($data);


        $post = [
            'key' => '72a396b6aa35271c91e4e7514b33d4f7',
            'image' => $base64,
            'name'   => 'qr',
        ];
$curl=curl_init('https://api.imgbb.com/1/upload');
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
$data=curl_exec($curl);

if (!$data)
{
    dump(curl_error($curl));
}
else
{
    $data= json_decode($data,true);
    $image=$data['data']['display_url'];
}
curl_close($curl);


                $message = (new \Swift_Message('Participation Mail'))
          ->setFrom('velo.tn.contact@gmail.com')
            ->setTo($key->getEmail())
           ->setBody(
               $this->renderView('@Event/mail/qr.html.twig',compact("image","name","event")),
                'text/html'
            );

        $this->get('mailer')->send($message);

        $filesystem = new Filesystem();
        $filesystem->remove($QR);

            }
        }
 return $this->redirectToRoute("list_evenement_back");
    }



    public function drive($QR)
    {
        $client = new \Google_Client();

        $client->setApplicationName("Clé API 1");
        $client->setDeveloperKey("AIzaSyDV1-8jptZ6bWWWBeMkbi5BAP5r6wkC-5U");

        $driveService = new \Google_Service_Drive($client);
        $fileMetadata = new \Google_Service_Drive_DriveFile(array(
            'name' => 'qr.jpg'));
        $content = file_get_contents($QR);

        $file = $driveService->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => 'image/jpeg',
            'uploadType' => 'multipart',
            'fields' => 'id'));
        printf("File ID: %s\n", $file->id);
    }
}
