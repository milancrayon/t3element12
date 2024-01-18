<?php

declare(strict_types=1);

namespace Crayon\T3element\Domain\Model;


/**
 * Themeconfig
 */
class Themeconfig extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * header
     *
     * @var string
     */
    protected $header = '';
    
    /**
     * footer
     *
     * @var string
     */
    protected $footer = '';
    
    /**
     * cssjs
     *
     * @var string
     */
    protected $cssjs = '';
    
    /**
     * menu
     *
     * @var string
     */
    protected $menu = '';
    
    /**
     * langm
     *
     * @var string
     */
    protected $langm = '';

    /**
     * Returns the header
     *
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Sets the header
     *
     * @param string $header
     * @return void
     */
    public function setHeader(string $header)
    {
        $this->header = $header;
    }
   
    /**
     * Returns the footer
     *
     * @return string
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * Sets the footer
     *
     * @param string $footer
     * @return void
     */
    public function setFooter(string $footer)
    {
        $this->footer = $footer;
    }
    /**
     * Returns the cssjs
     *
     * @return string
     */
    public function getCssjs()
    {
        return $this->cssjs;
    }

    /**
     * Sets the cssjs
     *
     * @param string $cssjs
     * @return void
     */
    public function setCssjs(string $cssjs)
    {
        $this->cssjs = $cssjs;
    }

    /**
     * Returns the menu
     *
     * @return string
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Sets the menu
     *
     * @param string $menu
     * @return void
     */
    public function setMenu(string $menu)
    {
        $this->menu = $menu;
    }
  
    /**
     * Returns the langm
     *
     * @return string
     */
    public function getLangm()
    {
        return $this->langm;
    }

    /**
     * Sets the langm
     *
     * @param string $langm
     * @return void
     */
    public function setLangm(string $langm)
    {
        $this->langm = $langm;
    }
}
