<?php

namespace Nozyczki\ShortenerBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

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
     * @MongoDB\String
     */
    private $uri;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Alias", mappedBy="link")
     */
    private $aliases;

    public function __construct(){
        $this->aliases = new ArrayCollection();
    }

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
     * Add alias
     *
     * @param Nozyczki\ShortenerBundle\Document\Alias $alias
     */
    public function addAlias(\Nozyczki\ShortenerBundle\Document\Alias $alias)
    {
        $this->aliases[] = $alias;
    }

    /**
     * Remove alias
     *
     * @param Nozyczki\ShortenerBundle\Document\Alias $alias
     */
    public function removeAlias(\Nozyczki\ShortenerBundle\Document\Alias $alias)
    {
        $this->aliases->removeElement($alias);
    }

    /**
     * Get aliases
     *
     * @return \Doctrine\Common\Collections\Collection $aliases
     */
    public function getAliases()
    {
        return $this->aliases;
    }
}
