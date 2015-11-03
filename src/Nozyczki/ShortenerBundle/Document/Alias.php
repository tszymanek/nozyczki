<?php

namespace Nozyczki\ShortenerBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Regex(
     *      pattern = "/[a-zA-Z0-9]{3,10}/",
     *      message = "Malformed alias. Be sure it's 3-10 characters long and is a valid string."
     * )
     * @MongoDB\String
     */
    private $alias;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Link", inversedBy="aliases", simple=true)
     */
    private $link;

    public function __construct(){
        $this->custom=FALSE;
    }

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

    /**
     * @return self
     */
    public function setIsCustom()
    {
        $this->custom = TRUE;
    }

    /**
     * @MongoDB\PrePersist
     */
    public function checkAlias(){
        if($this->getAlias())
            $this->setIsCustom();
        else
            $this->setAlias();
    }

//    public function __construct($options = array()){
//        $isCustom = (isset($options['custom']))?$options['custom']:false;
//        $this->custom = $isCustom;
//        if(!$isCustom)
//          $this->setAlias();
//    }

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
     * @return Nozyczki\ShortenerBundle\Document\Link $link
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param Nozyczki\ShortenerBundle\Document\Link $link
     * @return self
     */
    public function setLink(\Nozyczki\ShortenerBundle\Document\Link $link)
    {
        $this->link = $link;
        return $this;
    }
}
