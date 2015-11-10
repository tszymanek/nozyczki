<?php

namespace Nozyczki\ShortenerBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @MongoDB\HasLifecycleCallbacks
 */
class Ip
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String
     */
    private $ip;

    /**
     * @MongoDB\Increment
     */
    private $counter;

    /**
     * @MongoDB\Date
     */
    private $modifiedAt;

    public function __construct(){
        $this->counter=1;
        $this->ip = "0.0.0.0";
    }

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $ip
     * @return self
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return string $ip
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @MongoDB\PostLoad
     */
    public function incrementCounter()
    {
        $this->counter++;
    }

    public function resetCounter(){
        $this->counter=0;
    }

    /**
     * @return increment $counter
     */
    public function getCounter()
    {
        return $this->counter;
    }

    /**
     * @MongoDB\PrePersist
     * @MongoDB\PreUpdate
     */
    public function setModifiedAt()
    {
        $this->modifiedAt = new \DateTime();
    }

    /**
     * @return \DateTime $modifiedAt
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }
}
