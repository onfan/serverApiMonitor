<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Key;
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
            $url->setKeyStatus($urlManager->hasKeysError($url) ? 'error' : 'ok');
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
        $url = new Url();
        $form = $this->createForm(new UrlType(), $url);

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
            $urlManager->setKeys($urlEntity);

            return $this->redirect($this->generateUrl('url_list'));
        }

        return $this->render(
            'AppBundle:Url:edit.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/url/delete/{slug}", name="url_delete")
     */
    public function deleteAction($slug)
    {
        $urlManager = $this->get('monitor.manager.url');
        $url = Url::createFromSlug($slug);
        $urlManager->delete($url);

        return $this->redirect($this->generateUrl('url_list'));
    }

    /**
     * @Route("/url/edit/{slug}", name="url_edit")
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($slug)
    {
        $urlManager = $this->get('monitor.manager.url');
        $url = Url::createFromSlug($slug);
        $urlManager->populateKeysFor($url);
        $form = $this->createForm(new UrlType(), $url);

        return $this->render(
            'AppBundle:Url:edit.html.twig',
            array(
                'form' => $form->createView(),
                'url' => $url
            )
        );

    }


}
