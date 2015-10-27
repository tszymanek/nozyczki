<?php

namespace Nozyczki\ShortenerBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document
 */
class Link
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\Url(
     * message = "The url '{{ value }}' is not a valid url",
     * protocols = {"http", "https", "ftp", "ftps"}),
     * checkDNS = true
     * @MongoDB\String
     */
    private $uri;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Alias", mappedBy="link", simple=true)
     */
    private $aliases;

    /**
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string $uri
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return self
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @param Nozyczki\ShortenerBundle\Document\Alias $alias
     */
    public function addAlias(\Nozyczki\ShortenerBundle\Document\Alias $alias)
    {
        $this->aliases[] = $alias;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection $aliases
     */
    public function getAliases()
    {
        return $this->aliases;
    }
}
