<?php

namespace Nozyczki\ShortenerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Nozyczki\ShortenerBundle\Document\Link;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/link")
     */
    public function indexAction()
    {
        $links = $this->get('doctrine_mongodb')->getRepository('NozyczkiShortenerBundle:Link')->findAll();

        if (!$links) {
            throw $this->createNotFoundException('No links found.');
        }
        return $this->render('default/index.html.twig', array(
            'links' => $links
        ));
    }

    /**
     * @Route("/link/create")
     */
    public function createAction()
    {
        $link = new Link();
        $link->setUrl('http://www.wp.pl');

        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($link);
        $dm->flush();

        return new Response('Created link id '.$link->getId());
    }
}
