<?php

declare(strict_types=1);

namespace Crayon\T3element\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * License
 */
class License extends AbstractEntity
{   
    /**
     * email
     *
     * @var string
     */
    protected $email = '';
    

    /**
     * email
     *
     * @var string
     */
    protected $cLc = '';
    

     /**
     * status
     *
     * @var int
     */
    protected $status = 0;

     /**
     * isVerify
     *
     * @var int
     */
    protected $isVerify = 0;


    /**
     * log
     *
     * @var string
     */
    protected $log = '';

    /**
     * version
     *
     * @var string
     */
    protected $version = '';


     /**
     * lastVerify
     *
     * @var int
     */
    protected $lastVerify = 0;


    /**
     * Returns the email
     *
     * @return string email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email
     *
     * @param string $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }


    /**
     * Returns the cLc
     *
     * @return string cLc
     */
    public function getCLc()
    {
        return $this->cLc;
    }

    /**
     * Sets the cLc
     *
     * @param string $cLc
     * @return void
     */
    public function setCLc($cLc)
    {
        $this->cLc = $cLc;
    }
    

     /**
     * Returns the status
     *
     * @return int $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the status
     *
     * @param int $status
     * @return void
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Returns the isVerify
     *
     * @return int $isVerify
     */
    public function getIsVerify()
    {
        return $this->isVerify;
    }

    /**
     * Sets the isVerify
     *
     * @param int $isVerify
     * @return void
     */
    public function setIsVerify($isVerify)
    {
        $this->isVerify = $isVerify;
    }

    /**
     * Returns the log
     *
     * @return string log
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Sets the log
     *
     * @param string $log
     * @return void
     */
    public function setLog($log)
    {
        $this->log = $log;
    }         

    /**
     * Returns the version
     *
     * @return string version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Sets the version
     *
     * @param string $version
     * @return void
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }         

    /**
     * Returns the lastVerify
     *
     * @return int $lastVerify
     */
    public function getLastVerify()
    {
        return $this->lastVerify;
    }

    /**
     * Sets the lastVerify
     *
     * @param int $lastVerify
     * @return void
     */
    public function setLastVerify($lastVerify)
    {
        $this->lastVerify = $lastVerify;
    }

}
