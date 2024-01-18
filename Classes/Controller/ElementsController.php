<?php
declare(strict_types=1);
namespace Crayon\T3element\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Psr\Http\Message\ResponseInterface;

/**
 * ElementsController
 */
class ElementsController extends ActionController
{
    /**
     * action list
     *
     * @return string|object|null|void
     */
    public function indexAction(): ResponseInterface
    { 
        return $this->htmlResponse();
    }
 
}