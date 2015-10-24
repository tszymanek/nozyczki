<?php

namespace Nozyczki\ShortenerBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @MongoDB\HasLifecycleCallbacks
 */
class Alias
{
    const ALIAS_LENGHT = 5;

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
     * @MongoDB\ReferenceOne(targetDocument="Link", inversedBy="aliases", simple=true)
     */
    private $link;

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

    public function __construct($options = array()){
        $isCustom = (isset($options['custom']))?$options['custom']:false;
        $this->custom = $isCustom;
        if(!$isCustom)
          $this->setAlias();
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
    public function setAlias($alias = null)
    {
        if(!$alias)
            $alias = substr(md5(openssl_random_pseudo_bytes(SELF::ALIAS_LENGHT)), 0, SELF::ALIAS_LENGHT);
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

    /**
     * Set custom
     *
     * @param boolean $custom
     * @return self
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;
        return $this;
    }

    /**
     * Get custom
     *
     * @return boolean $custom
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * Set link
     *
     * @param Nozyczki\ShortenerBundle\Document\Link $link
     * @return self
     */
    public function setLink(\Nozyczki\ShortenerBundle\Document\Link $link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * Get link
     *
     * @return Nozyczki\ShortenerBundle\Document\Link $link
     */
    public function getLink()
    {
        return $this->link;
    }
}
