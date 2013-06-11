<?php
/**
 * abstract class CDataObject
 *
 * Base class for all child data objects
 *
 * USAGE: This is an abstract class inherited by data objects.
 *
 * PHP version 5
 *
 * @category   Data Object
 * @author     D CLAY SMITH
 * @copyright  2007 Franchise Direct
 *
 */
 abstract class CDataObject implements IDataObject {

	#######################################################################
	#
	#
	# VARIABLES
	#
	#
	#######################################################################
	private $_dbManager;
	protected $_isNew;
	protected $_isDirty;
	protected $_id;
	protected $_dateEntered;
	protected $_dateModified;
	protected $_ts;
	protected $_aChanged = array();

	#######################################################################
	#
	#
	# PROPERTIES
	#
	#
	#######################################################################

	#######################################################################
	# PUBLIC PROPERTY getDbManager()
	#######################################################################
	public function getDbManager() {
		if ($this->_dbManager == NULL) {
			$this->_dbManager = new CDBManager();
		}
		return $this->_dbManager;
	}

	#######################################################################
	# PUBLIC PROPERTY setDbManager()
	#######################################################################
	public function setDbManager(CDBManager $value) {
		$this->_dbManager = $value;
	}

	#######################################################################
	# PUBLIC PROPERTY getIsNew()
	#######################################################################
	public function getIsNew() {
		return $this->_isNew;
	}
	public function setIsNew($value) {
		if ($value != 0 && $value != 1) {
			throw new exception('Non-tiny int value provided for setNew.',null);
		}
		$this->_isNew = $value;
	}

	#######################################################################
	# PUBLIC PROPERTY getIsDirty()
	#######################################################################
	public function getIsDirty() {
		return $this->_isDirty;
	}
	public function setIsDirty($value) {
		if ($value != 0 && $value != 1) {
			throw new exception('Non-tiny int value provided for setDirty.', null);
		}
		$this->_isDirty = $value;
	}

	#######################################################################
	# PROPERTY getId()
	#######################################################################
	public function getId() {
		return $this->_id;
	}
	public function setId($value) {
		if (!is_numeric($value)) {
			throw new exception('Non-integer value provided for setId.');
		}
		$this->_id = $value;
	}

 	#######################################################################
	# PROPERTY getDateEntered()
	#######################################################################
	public function getDateEntered() {
		return $this->_dateEntered;
	}
	public function setDateEntered($value) {
		if (!CValidation::isValidTimestamp($value)) {
			throw new exception('Non-timestamp value provided for setDateEntered: '.$value);
		}
		if ($this->_dateEntered != $value) {
			$this->_isDirty = true;
		}		
		$this->_dateEntered = $value;
	}

	#######################################################################
	# PROPERTY getDateModified()
	#######################################################################
	public function getDateModified() {
		return $this->_dateModified;
	}
	public function setDateModified($value) {
		if (!CValidation::isValidTimestamp($value)) {
			throw new exception('Non-timestamp value provided for setDateModified.');
		}
		$this->_dateModified = $value;
	}


	#######################################################################
	# READONLY PROPERTY getTs()
	#######################################################################
	public function getTs() {
		return $this->_ts;
	}
 	public function setTs($value) {
		$this->_ts = $value;
	}


	#######################################################################
	# READONLY PROPERTY changes()
	#######################################################################
	public function changed() {
		return $this->_aChanged;
	}
 }

 /**
 * interface class IDataObject
 *
 * Interface For Data Objects
 *
 * PHP version 5
 *
 * @category   Data Object
 * @author     D CLAY SMITH
 * @copyright  2007 Franchise Direct
 *
 */
 interface IDataObject
{
	###########################################################################
	#
	# Properties
	#
	###########################################################################
   	public function getId();
   	public function getDateEntered();
   	public function getDateModified();
   	public function getTs();
   	public function getIsNew();
   	public function setIsNew($value);
   	public function getIsDirty();
   	public function setIsDirty($value);

   	###########################################################################
	#
	# functions
	#
	###########################################################################
 	public function add();
 	public function update();
 	public function delete();
}

/******************************************************************************
 * CTypeHolder
 *****************************************************************************/
class CTypeHolder {
	/**
	 * variables
	 */
	private $_iId;
	private $_sTypeName;
	private $_sDescription;

	/**
	 * properties
	 */
	function getId() {
		return $this->_iId;
	}
	function setId($value) {
		$this->_iId = $value;
	}
	function getTypeName() {
		return $this->_sTypeName;
	}
	function setTypeName($value) {
		$this->_sTypeName = $value;
	}
	function getDescription() {
		return $this->_sDescription;
	}
	function setDescription($value) {
		$this->_sDescription = $value;
	}
	/**
	 * constructor
	 */
	function __construct($iId,$sTypeName,$sDescription) {
		$this->_iId = $iId;
		$this->_sTypeName = $sTypeName;
		$this->_sDescription = $sDescription;
	}
}
?>
