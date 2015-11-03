<?php
namespace Nozyczki\ShortenerBundle\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;

use Nozyczki\ShortenerBundle\Document\Alias;
use Nozyczki\ShortenerBundle\Document\Link;
use Nozyczki\ShortenerBundle\Document\Ip;

use Nozyczki\ShortenerBundle\Form\Type\ShortenType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
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

        $form = $this->createForm(new ShortenType(), $link);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dm = $this->get('doctrine_mongodb')->getManager();

            $currentIp = new Ip();
            $ip = $this->container->get('request')->getClientIp();
            if(!null == $dbIp = $dm->getRepository('NozyczkiShortenerBundle:Ip')->findOneBy(array('ip' => $ip)))
                $currentIp = $dbIp;
            else
                $currentIp->setIp($ip);
            if($currentIp->getCounter() > 5){
                $interval = date_create('now')->diff($currentIp->getModifiedAt());
                if($interval->d < 1){
                    $message    = 'Wykorzystałeś dzienny limit.';
                    $type       = 'warning';
                    return $this->render('NozyczkiShortenerBundle::error.html.twig', array(
                        'message' => $message,
                        'type' => $type
                        )
                    );
                }
                $currentIp->resetCounter();
            }

            if(!null == ($dbAlias = $dm->getRepository('NozyczkiShortenerBundle:Alias')->findOneBy(array('alias' => $alias->getAlias())))) {
                $message  = 'URL already shortened under this alias. Pick another one mate.';
                $type     = 'warning';
                return $this->render('NozyczkiShortenerBundle::error.html.twig', array(
                        'message' => $message,
                        'type'    => $type
                    )
                );
            }

            if(!null == ($dbLink = $dm->getRepository('NozyczkiShortenerBundle:Link')->findOneBy(array('uri' => $link->getUri())))) {
                $link = $dbLink;
                $link->addAlias($alias);
            }

            $alias->setLink($link);
            $dm->persist($link);
            $dm->persist($alias);
            $dm->persist($currentIp);
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
    public function showAction($alias, Request $request)
    {
        $aliasSuggested   = $alias;
        try {
            if (null == $aliasSuggested)
                throw new \Exception('Empty shortened url given');
            $aliasSuggested   = explode('/', $aliasSuggested);
            $aliasSuggested   = end($aliasSuggested);
            $aliasRepository  = $this->get('doctrine_mongodb')->getRepository('NozyczkiShortenerBundle:Alias');
            $alias            = $aliasRepository->findOneBy(array('alias' => $aliasSuggested));
            if (empty($alias))
                throw new \Exception('No URL shortened under this alias');
            $link             = $alias->getLink();
            if (empty($link))
                throw new \Exception('No URL shortened under this alias');
        } catch (\Exception $e) {
            return $this->render('NozyczkiShortenerBundle::error.html.twig', array(
                'message' => $e->getMessage()
            ));
        }
        return $this->render('NozyczkiShortenerBundle::forward.html.twig', array(
            'link'  => $link->getUri(),
            'alias' => $alias
        ));
    }
}