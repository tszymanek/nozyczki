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
     * @Assert\Url(message = "No valid url given")
     * @MongoDB\String
     */
    private $uri;

    /**
     * @Assert\Regex(
     *      pattern = "/[a-zA-Z0-9]{3,10}/",
     *      message = "Malformed alias. Be sure it's 3-10 characters long and is a valid string."
     * )
     * @MongoDB\ReferenceMany(targetDocument="Alias", mappedBy="link", simple=true)
     */
    private $aliases;

//    public function __construct(){
//        $this->aliases = new ArrayCollection();
//    }

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
