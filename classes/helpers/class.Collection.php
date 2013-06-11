<?php
/**
 * class CCollection
 *
 * Notice the "implements Iterator" - important! 
 *
 * PHP version 5
 *
 * @author     D CLAY SMITH <clay@franchisedirect.com>
 * @copyright  2007 Franchise Direct
 * 
 * EXAMPLE:
 * 
 * foreach ( $colors as $color ) { 
 *  echo $color."<br>"; 
 * }
 * 
 * foreach ( $colors as $key => $color ) { 
 *  echo "$key: $color<br>"; 
 * }
 * 
 * // Reset the iterator - foreach does this automatically 
 * $colors->rewind(); 
 * // Loop while valid 
 * while ( $colors->valid() ) { 
 * 
 *    echo $colors->key().": ".$colors->current()."<br>"; 
 *    $colors->next(); 
 * }
 *  
 */ 
class CCollection implements Iterator { 

  	/** 
   	 * Variables 
     */ 
	private $array = array();  
	private $valid = FALSE; 
	
	/** 
	 * Constructor 
	 */ 
	function __construct($array = null) {
		if ($array != null) { 		
	   		$this->array = $array;
		} 
	 } 

	   /** 
	   * Return the array "pointer" to the first element 
	   * PHP's reset() returns false if the array has no elements 
	   */ 
	 function rewind(){ 
	   $this->valid = (FALSE !== reset($this->array)); 
	 } 
	
		/** 
	   * Return the current array element 
	   */ 
	 function current(){ 
	   return current($this->array); 
	 } 
	
	   /** 
	   * Return the key of the current array element 
	   */ 
	 function key(){ 
	   return key($this->array); 
	 } 
	
	   /** 
	   * Move forward by one 
	   * PHP's next() returns false if there are no more elements 
	   */ 
	 function next(){ 
	   $this->valid = (FALSE !== next($this->array)); 
	 } 
	
	   /** 
	   * Is the current element valid? 
	   */ 
	 function valid(){ 
	   return $this->valid; 
	 } 
	 
	###########################################################################
	# add()
	###########################################################################
	function add($item) {
		if ($item instanceof CCollection) {
			$this->array = array_merge($this->array, $item->array);
		} else {
			$this->array[] = $item;	
		}				
	}
	###########################################################################
	# count()
	###########################################################################
	function count() {
		return count($this->array);	
	}
	###########################################################################
	# first()
	###########################################################################
	function first() {
		$this->rewind();
		return $this->current();
	}
	
	###########################################################################
	# last()
	###########################################################################
	function last() {
		return $this->array[count($this->array)-1];
	}
	
	function getByKey($sKeyName,$sKeyValue) {
		$count = count($this->array);
		for($i=0;$i<$count;$i++) {
		
			if (method_exists($this->array[$i],$sKeyName)) {
				if ($this->array[$i]->$sKeyName() == $sKeyValue) {
					return $this->array[$i];
				}
			}
		}
		return false;
	}
	
	function switchTwoItems($iItemIndex1, $iItemIndex2) {
			// read item 1 to tmp getItem
			$oTmp = $this->getItem($iItemIndex1);
			
			// set index 1 = item 2 setItem
			$this->setItem($iItemIndex1, $this->getItem($iItemIndex2));	
			
			// set index 2 = tmp setItem
			$this->setItem($iItemIndex2, $oTmp);			
	}
	
	function getItem($iItemIndex) {
			return $this->array[$iItemIndex];			
	}	
	
	function getItemIndex($item) {
		return array_search($item, $this->array);
	}
	
	function setItem($iItemIndex, $oObject) {
			$this->array[$iItemIndex] = $oObject;
	}

	function shuffle() {
			shuffle($this->array);
			return $this;
	}

	function serialize($iTotalCount = -1) {

		$items = array();
		foreach ($this as $object) {
			$items[] = (method_exists($object, "serialize")) ? $object->serialize($bForExport) : array();
		}
		$aOutput = array(
			"count" => (int) $iTotalCount,
			"items" => $items
		);
		return $aOutput;
	}
	
	public static function merge($col1, $col2) {
		$aItems = array();
		foreach ($col1 as $item) {
			$aItems[$item->getId()] = $item;
		}
		foreach ($col2 as $item) {
			$aItems[$item->getId()] = $item;
		}
		return new CCollection($aItems);
	}
}
?>
