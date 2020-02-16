<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        // replace this example code with whatever you need
        $em = $this->getDoctrine()->getManager();
        $evenements = $em->getRepository('EventBundle:Evenement')->findAll();
        return $this->render('front/index.html.twig', compact("evenements"));
    }
    /**
     * @Route("/rederict", name="redirection")
     */
    public function backAction()
    {
        $authChecker=$this->container->get('security.authorization_checker');

        if ($authChecker->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute("admin");
        }else if ($authChecker->isGranted('ROLE_USER'))
        {
            return $this->redirectToRoute("fos_user_profile_show");
        }
        else
        {
            return $this->redirectToRoute("login");
        }
    }
    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction()
    {
            return $this->render('back/index.html.twig');
    }
}
