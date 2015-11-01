<?php

namespace Nozyczki\ShortenerBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @MongoDB\HasLifecycleCallbacks
 */
class User
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
    private $counter=1;

    /**
     * @MongoDB\Date
     */
    private $modifiedAt;

    

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
     * @param increment $counter
     */
    public function incrementCounter()
    {
        $this->counter++;
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
     * @return self
     */
    public function setModifiedAt()
    {
        $this->modifiedAt = New \DateTime();
        return $this;
    }

    /**
     * Get modifiedAt
     *
     * @return date $modifiedAt
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }
}
