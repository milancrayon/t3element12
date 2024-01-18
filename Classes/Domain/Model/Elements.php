<?php

declare(strict_types=1);

namespace Crayon\T3element\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Elements
 */
class Elements extends AbstractEntity
{
    /**
     * data
     *
     * @var string
     */
    protected $data = '';

    /**
     * Returns the data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the data
     *
     * @param string $data
     * @return void
     */
    public function setData(string $data)
    {
        $this->data = $data;
    }
}