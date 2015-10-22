<?php

namespace Nozyczki\ShortenerBundle\Controller;

use Nozyczki\ShortenerBundle\Document\Link;
use Nozyczki\ShortenerBundle\Document\Alias;
use Nozyczki\ShortenerBundle\Form\Type\ShortenType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Method({"GET", "POST"})
     * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $link = new Link();
        $alias = new Alias();
        $link->getAliases()->add($alias);
        $form = $this->createForm(new ShortenType(), $link);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->persist($link);
            $dm->persist($alias);
            $dm->flush();

            return $this->redirect($this->generateUrl(
                'link_show',
                array('encodedUri' => $link->getEncodedUri())
            ));
        }

        return $this->render('NozyczkiShortenerBundle::create.html.twig', array(
            'link' => $link,
            'aliases' => $alias,
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
