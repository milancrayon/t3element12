<?php

namespace Crayon\T3element\Controller;

use Crayon\T3element\Domain\Repository\ElementsRepository;
use Crayon\T3element\Domain\Repository\ThemeconfigRepository;
use Crayon\T3element\Utilities\Obj;
use Crayon\T3element\Domain\Model\Elements;
use Crayon\T3element\Domain\Model\Themeconfig;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Resource\FileCollectionRepository;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Country\CountryProvider;
use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Fluid\View\TemplatePaths;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use Doctrine\DBAL\Exception\TableNotFoundException;
use TYPO3\CMS\Core\Http\UploadedFile; 
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Core\Environment;

class BaseController extends ActionController
{
    /**
     * elementsRepository
     *
     * @var ElementsRepository
     */
    protected $elementsRepository = null;
    /**
     * @param ElementsRepository $elementsRepository
     */
    public function injectElementsRepository(ElementsRepository $elementsRepository)
    {
        $this->elementsRepository = $elementsRepository;
    }
    /**
     * themeconfigRepository
     * 
     * @var ThemeconfigRepository
     */
    private $themeconfigRepository = null;
    /**
     * @param ThemeconfigRepository $themeconfigRepository
     */
    public function injectThemeconfigRepository(ThemeconfigRepository $themeconfigRepository)
    {
        $this->themeconfigRepository = $themeconfigRepository;
    }
 
    public function versionAction()
    {
        $typoversion = GeneralUtility::makeInstance(Typo3Version::class);
        return ['status' => 1, 'version' => $typoversion->getVersion()];
    }
     
    public function languagesAction($site)
    {
        $langues = $site->getLanguages($GLOBALS['BE_USER'], false, 0);
        $objUtility = GeneralUtility::makeInstance(Obj::class);
        $depth = 5;
        $langues = $objUtility->toArray($langues, $depth);
        return ['status' => 1, "languages" => $langues];
    }
 
    public function elementDatabaseAction($element)
    {
        if ($element['operations']) {
            if(isset($element['uid'])){
                $dbUpdates = $this->elementsRepository->elementUpdate($element['operations']);
            }else if (isset($element['rmvd'])){
                $dbUpdates = $this->elementsRepository->elementRemove($element['operations']);
            }else{
                $dbUpdates = $this->elementsRepository->elementOperations($element['operations']);
            }
            if ($dbUpdates) {
                return ['status' => 1, 'msg' => "Element Success!!"];
            } else {
                return ['status' => 0, 'msg' => "database regarding changes not perform due to some error!, please contact support."];
            }
        }
    }
 
    public function fetchFileDataAction($data)
    {
        $extpath = GeneralUtility::makeInstance(ExtensionManagementUtility::class)::extPath('t3element');
        if($data['content']){
            if(sizeof($data['content']) > 0){
                $__datafile =[];
                $fileswritten = 0;
                foreach ($data['content'] as $key => $value) {
                    $_path = $extpath.$value['path'];
                    if(file_exists($_path)){
                        $__datafile[$value['path']] = file_get_contents($_path);
                    }else{
                        $__datafile[$value['path']]="";
                    } 
                    $fileswritten = $fileswritten+1;
                }
                if($fileswritten == sizeof($data['content']) ){
                    return ['status' => 1, 'data' => $__datafile];            
                }
            }else{
                return ['status' => 0, 'msg' => "Something went wrong !"];            
            }
        }else{
            return ['status' => 0, 'msg' => "Something went wrong !"];            
        } 
    }
 
    public function removeFileAction($data)
    {
        $extpath = GeneralUtility::makeInstance(ExtensionManagementUtility::class)::extPath('t3element');
        if($data['content']){
            if(sizeof($data['content']) > 0){
                $fileswritten = 0;
                foreach ($data['content'] as $key => $value) {
                    $_path = $extpath.$value['path'];
                    if(file_exists($_path)){
                        unlink($_path);
                    }
                    $fileswritten = $fileswritten+1;
                }
                if($fileswritten == sizeof($data['content']) ){
                    return ['status' => 1];            
                }
            }else{
                return ['status' => 0, 'msg' => "Something went wrong !"];            
            }
        }else{
            return ['status' => 0, 'msg' => "Something went wrong !"];            
        } 
    }

    public function fileUpdationAction($data)
    {
        if($data['content']){
            if(sizeof($data['content']) > 0){
                $fileswritten = 0;
                $extpath = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::class)::extPath('t3element');
                foreach ($data['content'] as $key => $value) {
                    if ($ttcont_dat = fopen($extpath.$value['path'], "w")) {
                        if (fwrite($ttcont_dat, $value['content'])) {
                            fclose($ttcont_dat);
                        }
                        $fileswritten = $fileswritten + 1;
                    }
                }
                if($fileswritten == sizeof($data['content']) ){
                    $cacheManager =  GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class);
                    $cacheManager->flushCaches();
                    return ['status' => 1, 'msg' => "Files Updated Successfully !"];            
                }
            }else{
                return ['status' => 0, 'msg' => "Something went wrong !"];            
            }
        }else{
            return ['status' => 0, 'msg' => "Something went wrong !"];            
        }
    }
 
    public function resultAction($data)
    { 
        if(isset($data['table'])){
            try{
                $table = $data['table'];
                $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
                $queryBuilder->select("*");
                $queryBuilder->from($table);
                if(isset($data['orderby'])){
                    if(isset($data['orderby']['by']) && isset($data['orderby']['dirc'])){
                        $queryBuilder->orderBy($data['orderby']['by'], $data['orderby']['dirc']); 
                    }
                }
                if(isset($data['where'])){
                    $_where = [];
                    $_orWhere = [];
                    if(isset($data['where']['and'])){
                        if(sizeof($data['where']['and']) > 0){ 
                            foreach ($data['where']['and'] as $key => $value) {
                                if($value['exp'] == "eq"){ 
                                    $_where[] = $queryBuilder->expr()->eq($value['field'], $queryBuilder->createNamedParameter($value['value']));
                                }
                                if($value['exp'] == "neq"){ 
                                    $_where[] = $queryBuilder->expr()->neq($value['field'], $queryBuilder->createNamedParameter($value['value']));
                                }
                                if($value['exp'] == "like"){
                                    if(sizeof($value['fields'])) {
                                        foreach ($value['fields'] as $ssey => $value__) {
                                            $_where[] = $queryBuilder->expr()->like($value__, $queryBuilder->createNamedParameter('%' . $queryBuilder->escapeLikeWildcards($value['value']) . '%'));
                                        }
                                    }
                                }
                            }
                            $queryBuilder->where(...$_where);
                        }
                    }
                    if(isset($data['where']['or'])){
                        if(sizeof($data['where']['or']) > 0){ 
                            foreach ($data['where']['or'] as $key => $value) {
                                if($value['exp'] == "eq"){ 
                                    $_orWhere[] = $queryBuilder->expr()->eq($value['field'], $queryBuilder->createNamedParameter($value['value']));
                                }
                                if($value['exp'] == "neq"){ 
                                    $_orWhere[] = $queryBuilder->expr()->neq($value['field'], $queryBuilder->createNamedParameter($value['value']));
                                }
                                if($value['exp'] == "like"){
                                    if(sizeof($value['fields'])) {
                                        foreach ($value['fields'] as $ssey => $value__) {
                                            $_orWhere[] = $queryBuilder->expr()->like($value__, $queryBuilder->createNamedParameter('%' . $queryBuilder->escapeLikeWildcards($value['value']) . '%'));
                                        }
                                    }
                                }
                            }
                            $queryBuilder->orWhere(...$_orWhere);
                        }
                    }
                }
                if(isset($data['limit'])){
                    $queryBuilder->setMaxResults($data['limit']);
                }
                if(isset($data['offset'])){
                    $queryBuilder->setFirstResult($data['offset']);
                }
                $result = $queryBuilder->execute();
                return['data'=> $result->fetchAll()];
            }catch(Exception $e){
                return ['error'=>$e];
            }
        } 
        return['error'=> 'Invalid Request!!'];     
    }
 
    public function storeAction($data)
    {
        if (isset($data['table'])) {
            if ($data['table'] && $data['values'] && sizeof($data['values']) > 0) {
                $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($data['table']);
                $affectedRows = $queryBuilder
                    ->insert($data['table'])
                    ->values($data['values'])
                    ->execute();
                return ['data' => $affectedRows];
            }
        }
        return ['error' => 'Invalid Request!!'];
    }

    public function updateAction($data)
    {
        if (isset($data['table'])) {
            try {
                if (isset($data['table'])) {
                    if (isset($data['table']) && isset($data['value']) && isset($data['key']) && isset($data['updates']) && sizeof($data['updates']) > 0) {
                        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($data['table']);
                        $queryBuilder->update($data['table'])->where($queryBuilder->expr()->eq($data['key'], $queryBuilder->createNamedParameter($data['value'])));
                        if(sizeof($data['updates']) > 0){
                            foreach ($data['updates'] as $key => $value) {
                                $queryBuilder->set( $value['key'] , $value['val']);
                            }
                        }
                        $result = $queryBuilder->execute();
                        return ['data' => $result];
                    }else{
                        return ['error' => 'Parameters Missing!!'];
                    }
                }
            } catch (Exception $e) {
                return ['error' => $e->getMessage()];
            } catch (TableNotFoundException $e) {
                return ['error' => $e->getMessage()];
            }
        }
        return ['error' => 'Invalid Request!!'];
    }

    public function uploadAction($uploadedd)
    {
        $updated_paths = [];
        if (sizeof($uploadedd) > 0) {
            $ext_pub = 'EXT:t3element/Resources/Public/';
            $_extpath = GeneralUtility::getFileAbsFileName($ext_pub);
            foreach ($uploadedd as $key => $value) {
                $src = $value;
                $directory = '';
                if($key == 'favicon' || $key == 'logo'){
                    $directory = 'images/';
                }else{
                    if(strpos($key,'_js_')){
                        $directory = 'js/';
                    }
                    if(strpos($key,'_css_')){
                        $directory = 'css/';
                    }
                    if(strpos($key,'_font_')){
                        $directory = 'fonts/';
                    }
                }
                $dir = $_extpath.$directory;
                GeneralUtility::mkdir_deep($dir);
                $updated_paths[$key] = $ext_pub.$directory.pathinfo( $src->getClientFilename(), PATHINFO_BASENAME);
                $srcFileName =$dir.'/'. pathinfo( $src->getClientFilename(), PATHINFO_BASENAME);
                if (!is_string($src) && is_a($src, \TYPO3\CMS\Core\Http\UploadedFile::class)) {
                    if ($stream = $src->getStream()) {
                        $handle = fopen($srcFileName, 'wb+');
                        if ($handle === false) return false;
                        $stream->rewind();
                        while (!$stream->eof()) {
                            $bytes = $stream->read(4096);
                            fwrite($handle, $bytes);
                        }
                        fclose($handle);
                    }
                } 
            }
        }
        return ['uploads' => $updated_paths];
    }
  
}