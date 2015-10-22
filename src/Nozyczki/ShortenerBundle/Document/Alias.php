<?php

namespace Nozyczki\ShortenerBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @MongoDB\HasLifecycleCallbacks
 */
class Alias{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\Boolean
     */
    private $custom;

    /**
     * @MongoDB\String
     */
    private $alias;

    /**
     * @MongoDB\Date
     */
    private $createdAt;

    /**
     * @MongoDB\Date
     */
    private $updatedAt;
    /**
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return boolean $custom
     */
    public function isCustom()
    {
        return $this->custom;
    }

    public function __construct(){
        $this->custom = FALSE;
    }

    /**
     * @return self
     */
    public function setIsCustom()
    {
        return $this->custom = TRUE;
    }

    /**
     * @return string $alias
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     * @return self
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * @MongoDB\PrePersist
     * @MongoDB\PreUpdate
     */
    public function registerDate(){
        $this->updatedAt = new \DateTime();
        if($this->createdAt == null)
            $this->createdAt = new \DateTime();
    }

    /**
     * Get createdAt
     *
     * @return date $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get updatedAt
     *
     * @return date $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}