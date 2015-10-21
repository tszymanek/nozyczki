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

    /** @MongoDB\String */
    private $alias;

    /**
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @MongoDB\Boolean
     */
    private $isCustom;

    /**
     * @MongoDB\Date
     */
    private $createdAt;

    /**
     * @MongoDB\Date
     */
    private $updatedAt;

    /**
     * @param boolean $hasAlias
     * @return self
     */
    public function setHasAlias($hasAlias)
    {
        $this->alias = $hasAlias;
        return $this;
    }

    /**
     * @return boolean $hasAlias
     */
    public function getHasAlias()
    {
        return $this->hasAlias;
    }

    /**
     * @param string $encodedUri
     * @return self
     */
    public function setEncodedUri($encodedUri)
    {
        $this->encodedUri = $encodedUri;
        return $this;
    }

    /**
     * @return string $encodedUri
     */
    public function getEncodedUri()
    {
        return $this->encodedUri;
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

//    /**
//     * Set createdAt
//     *
//     * @param date $createdAt
//     * @return self
//     */
//    public function setCreatedAt($createdAt)
//    {
//        $this->createdAt = $createdAt;
//        return $this;
//    }

    /**
     * Get createdAt
     *
     * @return date $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

//    /**
//     * Set updatedAt
//     *
//     * @param date $updatedAt
//     * @return self
//     */
//    public function setUpdatedAt($updatedAt)
//    {
//        $this->updatedAt = $updatedAt;
//        return $this;
//    }

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