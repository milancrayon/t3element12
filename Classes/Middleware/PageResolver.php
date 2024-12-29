<?php

namespace Crayon\T3element\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\StreamFactoryInterface;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ResponseFactoryInterface;
use Crayon\T3element\Controller\BaseController;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class PageResolver implements MiddlewareInterface
{

/**
	 * @var ResponseFactoryInterface
	 */
	protected $responseFactory;

    
	/**
	 *	@param ServerRequestInterface $request
	 *	@param RequestHandlerInterface $handler
	 *	@return ResponseInterface 
      */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $queryString = $request->getQueryParams(); 
        $method = strtolower($request->getMethod());
        $slug = $queryString['params'];
        if (isset($_SERVER["CONTENT_LENGTH"])) {
            $expectedLength = intval($_SERVER["CONTENT_LENGTH"]);
        }else{
            $expectedLength = 0;
        }
        $rawInput = file_get_contents('php://input', false, stream_context_get_default(), 0, $expectedLength);
        $bodyData = json_decode($rawInput, true);
        $bsController = GeneralUtility::makeInstance(BaseController::class);
        $responseFactory = GeneralUtility::makeInstance(ResponseFactoryInterface::class);
        $response = $responseFactory->createResponse()
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Max-Age', '86400')
            ->withHeader('Cache-Control', 'public, max-age=86400,no-store, no-cache, must-revalidate, post-check=0, pre-check=0, false')
            ->withHeader('Vary', 'origin')
            ->withHeader(
                'Access-Control-Allow-Headers',
                $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'] ?? 'Origin, X-Requested-With, Content-Type, Authorization, Cache-Control',
            )
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('Allow', 'GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS')
            ->withHeader('Pragma', 'no-cache')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS')
        ;


        $uploadedData = $request->getUploadedFiles();
        $site = $request->getAttribute('site');
        $parsedBody = $request->getParsedBody(); 

        if ($method === "get" && ($slug === "version" || $slug === "version/")) {
            $responSControl = $bsController->versionAction();
            $response->getBody()->write(json_encode($responSControl));
            return $response;
        } else if ($method === "get" && ($slug === "languages" || $slug === "languages/")) {
            $responSControl = $bsController->languagesAction($site);
            $response->getBody()->write(json_encode($responSControl));
            return $response;
         
        } else if ($method === "post" && ($slug === "elementdb" || $slug === "elementdb/")) {
            $responSControl = $bsController->elementDatabaseAction($bodyData);
            $response->getBody()->write(json_encode($responSControl));
            return $response;
        } else if ($method === "post" && ($slug === "filedata" || $slug === "filedata/")) {
            $responSControl = $bsController->fetchFileDataAction($bodyData);
            $response->getBody()->write(json_encode($responSControl));
            return $response;
        } else if ($method === "post" && ($slug === "removefile" || $slug === "removefile/")) {
            $responSControl = $bsController->removeFileAction($bodyData);
            $response->getBody()->write(json_encode($responSControl));
            return $response;
        } else if ($method === "post" && ($slug === "fileupdate" || $slug === "fileupdate/")) {
            $responSControl = $bsController->fileUpdationAction($bodyData);
            $response->getBody()->write(json_encode($responSControl));
            return $response;
        } else if ($method === "post" && ($slug === "getdata" || $slug === "getdata/")) {
            $responSControl = $bsController->resultAction($bodyData);
            $response->getBody()->write(json_encode($responSControl));
            return $response; 
        } else if ($method === "post" && ($slug === "storedata" || $slug === "storedata/")) {
            $responSControl = $bsController->storeAction($bodyData);
            $response->getBody()->write(json_encode($responSControl));
            return $response;
        } else if ($method === "post" && ($slug === "updatedata" || $slug === "updatedata/")) {
            $responSControl = $bsController->updateAction($bodyData);
            $response->getBody()->write(json_encode($responSControl));
            return $response;
        } else if ($method === "post" && ($slug === "upload" || $slug === "upload/")) {
            $responSControl = $bsController->uploadAction($uploadedData);
            $response->getBody()->write(json_encode($responSControl));
            return $response;
        } else {
            return $handler->handle($request);
        }


    }


}