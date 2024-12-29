<?php
declare(strict_types=1);
namespace Crayon\T3element\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;
/**
 * The repository for Elements
 */
class ElementsRepository extends Repository
{  
    /**
     * Return boolean
     * 
     * @param $data
     * 
     */
    public function elementOperations($data){
        
        if(sizeof($data['tt_content']) > 0){
            
            $i = 0;
            foreach($data['tt_content'] as $ttcontent){ 
                
                $addColumn = "ALTER TABLE tt_content
							ADD ".$ttcontent['val'];
                $modifycolumn  = "ALTER TABLE tt_content
                                MODIFY COLUMN ".$ttcontent['val'];

                $checkforColumn = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content')->exec("SHOW COLUMNS FROM `tt_content` LIKE '".$ttcontent['key']."'");
                
                if($checkforColumn){
                    try{
                        GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content')->exec($modifycolumn);
                    }
                    catch(Exception $e){
                        return false;
                    }
                }else{
                    try{
                        GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content')->exec($addColumn);
                    }
                    catch(Exception $e){
                        return false;
                    }
                }
                if($i == (sizeof($data['tt_content'])-1)){
                    if(sizeof($data['repeater']) > 0){
                        return $this->repeaterElementContent($data['repeater']);
                    }else{
                        return true;
                    }
                }
                $i++;
            }
        }else{
            if($i == (sizeof($data['tt_content'])-1)){
                if(sizeof($data['repeater']) > 0){
                    return $this->repeaterElementContent($data['repeater']);
                }else{
                    return true;
                }
            }
        }
    }

    /**
     * Return boolean
     * 
     * @param $data
     * 
     */
    public function repeaterElementContent($data){
        if(sizeof($data) > 0){
            $j=0;
            $size = sizeof($data);
            foreach($data as $repeater){
                
                $data = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($repeater['key'])->getSchemaManager()->tablesExist([$repeater['key']]);
                if($data){
                    GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($repeater['key'])->exec('Drop Table '.$repeater['key']);
                }
                try{
                    GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($repeater['key'])->exec($repeater['val']);
                    if($j == ($size - 1)){
                        return true;
                    }
                    $j++;
                }
                catch(Exeption $r){
                    return false;
                }
            }
        }else{
            return true; 
        }
    }
    /**
     * Return 
     * 
     * @param $element
     * 
     */
    public function elementRemove($element){
        try{
            if(sizeof($element['tt_content']) > 0){
                foreach($element['tt_content'] as $tt){
                    $checkforColumn = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content')->exec("SHOW COLUMNS FROM `tt_content` LIKE '".$tt['key']."'");
                    if($checkforColumn){
                        GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content')->exec("ALTER TABLE tt_content DROP COLUMN ".$tt['key'].";");
                    }
                }
            }
            if(sizeof($element['repeater']) > 0){
                $s = [];$i=0;
                foreach($element['repeater'] as $repeater){
                    $data = GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getConnectionForTable($repeater['key'])
                    ->getSchemaManager()
                    ->tablesExist($repeater['key']);

                    if($data){
			            $s[$i] = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($repeater['key'])->exec($repeater['data']);	
                    }
                    $i++;
                }
                if(sizeof($s) == sizeof($element['repeater'])){
                    return true;
                }
            }else{
                return true;
            }
		}
		catch (Exception $e){
			return false;
		}
    }
    /**
     * Return 
     * 
     * @param $element
     * 
     */
    public function elementUpdate($element){
        $tt_content = $element['tt_content'];
        $repeater = $element['repeater'];
        if(sizeof($tt_content['newfield']) > 0){
            foreach($tt_content['newfield'] as $nFld){
                $addColumn = "ALTER TABLE tt_content
							ADD ".$nFld['val'];
                $modifycolumn  = "ALTER TABLE tt_content
                                MODIFY COLUMN ".$nFld['val'];
                $checkforColumn = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content')->exec("SHOW COLUMNS FROM `tt_content` LIKE '".$nFld['key']."'");
                if($checkforColumn){
                    try{GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content')->exec($modifycolumn);}catch(Exception $e){return false;}
                }else{
                    try{GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content')->exec($addColumn);}catch(Exception $e){return false;}
                }
            }
        }
        if(sizeof($tt_content['removefields']) > 0){
            foreach($tt_content['removefields'] as $rFld){
                $checkforColumn = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content')->exec("SHOW COLUMNS FROM `tt_content` LIKE '".$rFld['key']."'");
                if($checkforColumn){
                    try{GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content')->exec('ALTER TABLE tt_content DROP COLUMN '.$rFld['key']);}catch(Exception $e){return false;}
                }
            }
        }
        if(sizeof($repeater['remove']) > 0){
            foreach($repeater['remove'] as $rmvv){
                $data = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable($rmvv)
                ->getSchemaManager()
                ->tablesExist($rmvv);

                if($data){
                    try{GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($rmvv)->exec('DROP TABLE '.$rmvv);}catch(Exeption $r){return false;}
                }
            }
        }
        if(sizeof($repeater['newD']) > 0){
            foreach($repeater['newD'] as $ndata){
                $data = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($ndata['key'])->getSchemaManager()->tablesExist([$ndata['key']]);
                if($data){
                    try{GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($ndata['key'])->exec('Drop Table '.$ndata['key']);}catch(Exeption $r){return false;}
                }
                try{GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($ndata['key'])->exec($ndata['val']);}catch(Exeption $r){return false;}
                
            }
        }
        if(sizeof($repeater['updation']) > 0){
            foreach ($repeater['updation'] as $up) {
                $table = $up['table'];
                $fields = $up['data'];
                if(sizeof($fields) > 0){
                    foreach($fields as $fl){
                        $checkforTable = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table)->getSchemaManager()->tablesExist($table);
                        if($checkforTable){
                            $checkforColumn = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table)->exec("SHOW COLUMNS FROM ".$table." LIKE '".$fl['key']."'");
                            if($checkforColumn){
                                try{GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table)->exec("ALTER TABLE ".$table." MODIFY COLUMN ".$fl['val']);}catch(Exception $e){return false;}
                            }else{
                                try{GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table)->exec("ALTER TABLE ".$table." ADD COLUMN ".$fl['val']);}catch(Exception $e){return false;}
                            }
                        }
                    }
                }
            }
        }
        return true;
    }
}