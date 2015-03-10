<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Url;
use AppBundle\Form\UrlType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UrlController extends Controller
{
    /**
     * @Route("/url", name="url_list")
     */
    public function indexAction()
    {
        $urlManager = $this->get('monitor.manager.url');
        $urls = $urlManager->getAllUrls();

        foreach ($urls as $url) {
            $url->setStatus($urlManager->getLastStatus($url->getUrl()));
        }

        return $this->render(
            'AppBundle:Url:index.html.twig',
            array(
                'urls' => $urls
            )
        );
    }

    /**
     * @Route("/url/new", name="url_new")
     */
    public function newAction()
    {
        $form = $this->createForm(new UrlType());

        return $this->render(
            'AppBundle:Url:new.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/url/update", name="url_update")
     */
    public function updateAction(Request $request, $url = null)
    {
        $urlManager = $this->get('monitor.manager.url');

        if (null == $url) {
            $urlEntity = new Url();
        } else {
            $urlEntity = $urlManager->getUrl($url);
        }

        $form = $this->createForm(new UrlType(), $urlEntity);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $urlManager->addUrl($urlEntity->getUrl());

            return $this->redirect($this->generateUrl('url_list'));
        }

        return $this->render(
            'AppBundle:Url:edit.html.twig',
            array(
                'form' => $form->createView()
            )
        );

    }


}
