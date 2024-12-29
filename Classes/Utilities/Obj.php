<?php

namespace Crayon\T3element\Utilities;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyObjectStorage;
use TYPO3\CMS\Extbase\Persistence\Generic\ObjectStorage;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Obj implements SingletonInterface
{

	const END_OF_RECURSION = '%#EOR#%';

	/**
	 * 	@var mixed
	 */
	protected $initialArgument;
	public function __construct($obj = null)
	{
		$this->initialArgument = $obj;
		return $this;
	}
	public function toArray($obj, $depth = 3, $fields = [], $addClass = false)
	{

		if ($obj === null) {
			return null;
		}

		$isSimpleType = $this->isSimpleType(gettype($obj));
		$isStorage = !$isSimpleType && $this->isStorage($obj);

		if ($depth < 0) {
			return $isSimpleType && !is_array($obj) ? $obj : self::END_OF_RECURSION;
		}

		if ($isSimpleType && !is_array($obj)) {
			return $obj;
		}

		$type = is_object($obj) ? get_class($obj) : false;
		$final = [];
		$depth--;

		if (is_a($obj, \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult::class)) {

			$obj = $obj->toArray();

		} else if (is_a($obj, \DateTime::class)) {

			// DateTime in UTC konvertieren
			$utc = $obj->getTimestamp();
			return $utc;

		} else if ($isStorage) {

			// StorageObject in einfaches Array konvertieren
			$obj = $this->forceArray($obj);
			if ($addClass)
				$obj['__class'] = ObjectStorage::class;

		} else if ($type) {

			// Alle anderen Objekte
			$keys = $fields ?: $this->getKeys($obj);

			foreach ($keys as $field) {
				$val = $this->prop($obj, $field);
				$val = $this->toArray($val, $depth, $fields, $addClass);
				if ($val === self::END_OF_RECURSION)
					continue;
				$final[$field] = $val;
			}
			return $final;

		}

		foreach ($obj as $k => $v) {
			$val = $this->toArray($v, $depth, $fields, $addClass);
			if ($val === self::END_OF_RECURSION)
				continue;
			$final[$k] = $val;
		}

		return $final;
	}

	public function isSimpleType($type = '')
	{
		return in_array($type, ['array', 'string', 'float', 'double', 'integer', 'int', 'boolean', 'bool']);
	}

	public function isStorage($obj)
	{
		if (!is_object($obj) || is_string($obj))
			return false;
		$type = get_class($obj);
		return is_a($obj, ObjectStorage::class) || $type == LazyObjectStorage::class || $type == ObjectStorage::class || $type == \TYPO3\CMS\Extbase\Persistence\ObjectStorage::class;
	}

	public function getKeys($obj)
	{
		if (is_string($obj) && class_exists($obj)) {
			$obj = new $obj();
		}
		$keys = [];
		if (is_object($obj)) {
			return ObjectAccess::getGettablePropertyNames($obj);
		} else if (is_array($obj)) {
			return array_keys($obj);
		}
		return [];
	}

	public function prop($obj, $key)
	{
		if ($key == '')
			return '';
		$key = explode('.', $key);
		if (count($key) == 1)
			return $this->accessSingleProperty($obj, $key[0]);

		foreach ($key as $k) {
			$obj = $this->accessSingleProperty($obj, $k);
			if (!$obj)
				return '';
		}
		return $obj;
	}

	public function accessSingleProperty($obj, $key)
	{
		if ($key == '')
			return '';

		if (is_object($obj)) {

			if (is_numeric($key)) {
				$obj = $this->forceArray($obj);
				return $obj[intval($key)];
			}

			$gettable = ObjectAccess::isPropertyGettable($obj, $key);
			if ($gettable)
				return ObjectAccess::getProperty($obj, $key);

			$camelCaseKey = GeneralUtility::underscoredToLowerCamelCase($key);
			$gettable = ObjectAccess::isPropertyGettable($obj, $camelCaseKey);
			if ($gettable)
				return ObjectAccess::getProperty($obj, $camelCaseKey);

			return $obj->$key ?? null;

		} else {
			if (is_array($obj))
				return $obj[$key] ?? null;
		}
		return [];
	}
}