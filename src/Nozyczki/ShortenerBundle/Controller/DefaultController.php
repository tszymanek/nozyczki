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
        $link->addAlias($alias);
        $alias->setLink($link);
        $form = $this->createForm(new ShortenType(), $link);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dm = $this->get('doctrine_mongodb')->getManager();
//            $linkRequest=$request->request->get('uri');
//            $aliasRequest=$request->request->get('alias');
            $linkRequest=$link->getUri();
            $linkExists = $dm->getRepository('NozyczkiShortenerBundle:Link')->findOneBy(array('uri' => $linkRequest));
            if($linkExists){

            }
            $dm->persist($link);
            $dm->persist($alias);
            $dm->flush();
            return $this->redirect($this->generateUrl(
                'link_show',
                array('alias' => $alias->getAlias())
            ));
        }

        return $this->render('NozyczkiShortenerBundle::create.html.twig', array(
            'link' => $link,
            'aliases' => $alias,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{alias}", name="link_show")
     * @Method("GET")
     */
    public function showAction($alias)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $alias = $dm->getRepository('NozyczkiShortenerBundle:Alias')->findOneBy(array('alias' => $alias));
        $alias->getLink()->getUri();
        if (!$alias) {
            throw $this->createNotFoundException('Unable to find link '.$alias);
        }
        return $this->render('NozyczkiShortenerBundle::show.html.twig', array(
            'alias' => $alias,
//            'link' => $link
        ));
    }
}
