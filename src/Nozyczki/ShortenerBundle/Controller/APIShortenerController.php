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
use Symfony\Component\HttpFoundation\JsonResponse;

class APIShortenerController extends Controller
{

    /**
     * Akcja dodawania, oczekuje przekazania postem paramaetrow:
     * 'url' => validUrl,
     * oraz parametru opcjonalnego
     * 'url_short' => 5-10 string a-zA-Z0

     * @Route(
     *        "/api"
     *  )
     * @Method({"POST"})
     * @param Request $request
     */
    public function addAction(Request $request)
    {
        $requestData  = $request->request->all();
        $retCode      = 400;
        $retMessage   = 'Saved new shortened link';
        try {
            if (!isset($requestData['url']) || null == ($uri = $requestData['url']))
                throw new \Exception('Empty URL given');
            $dm         = $this->get('doctrine_mongodb');
            $linkState  = $this->provideLink($dm, $uri);
            $link       = $linkState['link'];

            $shortUrl   = (isset($requestData['url_short']) && null !== $requestData['url_short']) ? $requestData['url_short'] : false;
            $aliasState = $this->provideAlias($dm, $link, $shortUrl);
            $alias      = $aliasState['alias'];
            if(!$linkState['state'] || !$aliasState['state']) {
                  $link->addAlias($alias);
                  $alias->setLink($link);
                  $dm->getManager()->persist($link);
                  $dm->getManager()->persist($alias);
            }

            $dm->getManager()->flush();
        } catch(\Exception $e) {
            return new JsonResponse(
                array(
                  'message' => $e->getMessage(),
                  'status'  => false
                ),
                $retCode,
                ['Access-Control-Allow-Origin' => '*']
            );
        }

        $retCode = 200;
        return new JsonResponse(
            array(
              'message' => $retMessage,
              'params'  => array(
                  'short_url' => $this->container->getParameter('DOMAIN').$alias->getAlias()
              ),
            ),
            $retCode,
            ['Access-Control-Allow-Origin' => '*']
        );
    }

    /**
     * Akcja zwraca URLa po aliasie, badz tez info o braku urla
     * 'short_url' => validUrl
     *
     * @Route(
     *        "/api"
     *  )
     * @Method({"GET"})
     * @param Request $request
     */
    public function getUrl(Request $request) {
        $aliasSuggested   = $request->query->get('url_short');
        $retCode          = 400;

        try {
          if (null == $aliasSuggested)
              throw new \Exception('Empty shortened url given');
          $aliasSuggested   = explode('/', $aliasSuggested);
          $aliasSuggested   = end($aliasSuggested);
          $aliasRepository  = $this->get('doctrine_mongodb')->getRepository('NozyczkiShortenerBundle:Alias');
          $alias            = $aliasRepository->findOneBy(array('alias' => $aliasSuggested));
          if (empty($alias))
              throw new \Exception('No URL shortened under this alias');
        } catch (\Exception $e) {
              return new JsonResponse(
                  array(
                    'message' => $e->getMessage(),
                  ),
                  $retCode,
                  ['Access-Control-Allow-Origin' => '*']
              );
        }

        $retCode    = 200;
        $retMessage = 'Found shortened URL';
        return new JsonResponse(
            array(
              'message' => $retMessage,
              'params'  => array(
                  'url' => $alias->getLink()->getUri()
              ),
            ),
            $retCode,
            ['Access-Control-Allow-Origin' => '*']
        );
    }

    /**
    * Metoda zwraca kolekcje: obiekt linka w zaleznosci od przekazanych parametrow oraz status istienia encji w bazie
    * @return mixed
    */
    private function provideLink($dm, $uri) {
        $exists = true;
        if(empty($link = $dm->getRepository("NozyczkiShortenerBundle:Link")->findOneBy(array('uri' => $uri)))) {
            $exists = false;
            $link = new Link();
            $link->setUri($uri);
            $validator = $this->get('validator');
            if(count($linkErrors = $validator->validate($link)))
                throw new \Exception($linkErrors);
        }

        return array(
            'state' => $exists,
            'link' => $link
        );
    }

    /**
    * Metoda zwraca kolekcje: obiekt aliasu w zaleznosci od przekazanych parametrow oraz status istienia encji w bazie
    * @return mixed
    */
    private function provideAlias($dm, Link $link, $shortUrl) {
        $exists = false;
        if (!$shortUrl) {
              $alias = $dm->getRepository("NozyczkiShortenerBundle:Alias")->findOneBy(array('link' => $link->getId()));
              if (!empty($alias)) {
                    $exists = true;
              } else {
                    $alias = new Alias(array('custom' => false));
                    $alias->setLink($link);
              }
        } else {
              $alias = new Alias(array('custom' => true));
              $aliasExists = $dm->getRepository("NozyczkiShortenerBundle:Alias")->findOneBy(array('alias' => $shortUrl));
              if (count($aliasExists) > 0)
                  throw new \Exception('Given shortened alias exists, pick another one!');

              $alias->setAlias($shortUrl);
              $validator = $this->get('validator');
              if (count($aliasErrors = $validator->validate($alias)))
                  throw new \Exception($aliasErrors);
        }

        return array(
            'state' => $exists,
            'alias' => $alias
        );
    }
}
