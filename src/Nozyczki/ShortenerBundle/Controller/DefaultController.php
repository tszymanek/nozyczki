<?php

namespace Nozyczki\ShortenerBundle\Controller;

use Nozyczki\ShortenerBundle\Document\Link;
use Nozyczki\ShortenerBundle\Form\Type\ShortenType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $dm=$this->get('doctrine_mongodb')->getManager();
        $links = $dm->getRepository('NozyczkiShortenerBundle:Link')->findAll();

        if (!$links) {
            throw $this->createNotFoundException('No links found.');
        }
        return $this->render('NozyczkiShortenerBundle::index.html.twig', array(
            'links' => $links
        ));
    }

    /**
     * @Route("/create")
     * @Method({"GET", "POST"})
     * @param Request $request
     */
    public function createAction(Request $request)
    {
        $link = new Link();
        $form = $this->createForm(new ShortenType(), $link);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->persist($link);
            $dm->flush();

            return $this->redirect($this->generateUrl(
                'link_show',
                array('encodedUri' => $link->getEncodedUri())
            ));
        }

        return $this->render('NozyczkiShortenerBundle::create.html.twig', array(
            'link' => $link,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{encodedUri}", name="link_show")
     * @Method("GET")
     */
    public function showAction($encodedUri)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $link = $dm->getRepository('NozyczkiShortenerBundle:Link')->findOneBy(array('encodedUri' => $encodedUri));
        if (!$link) {
            throw $this->createNotFoundException('Unable to find link '.$encodedUri);
        }
        return $this->render('NozyczkiShortenerBundle::show.html.twig', array(
            'link' => $link
        ));
    }
}
