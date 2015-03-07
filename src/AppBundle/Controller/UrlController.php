<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UrlController extends Controller
{
    /**
     * @Route("/url", name="url_list")
     */
    public function indexAction()
    {
        $url = $this->get('monitor.manager.url');
        $urls = $url->getAllUrls();

        return $this->render('AppBundle:Url:index.html.twig', array(
            'urls' => $urls
        ));
    }
}
