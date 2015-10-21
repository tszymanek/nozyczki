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
     * @MongoDB\ReferenceMany(targetDocument="Documents\Alias")
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
     * @param string $uri
     * @return self
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @return string $uri
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set alias
     *
     * @param string $aliases
     * @return self
     */
    public function setAlias($aliases)
    {
        $this->aliases = $aliases;
        return $this;
    }

    /**
     * Get aliases
     *
     * @return string $aliases
     */
    public function getAlias()
    {
        return $this->aliases;
    }

    public function getAliases() { return $this->aliases; }
    public function addProject(Alias $aliases) { $this->aliases[] = $aliases; }
}
