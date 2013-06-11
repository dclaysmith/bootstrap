<?php
/*
 * Created on March 15
 *
 * Provides login check and session authentication.
 *
 * Stores a session guid with the user at time of login
 *  - subsequent authentications get the user based on the session_guid
 *  - can optionally check against the IP address (can fail)
 */
class CTesting {

	/**
	 * Constructor
	 */
	function __construct() {
	}

	function clearDatabase() {
		// delete all of the records from the lead table
		$oDBManager = new CDBManager();

	}

	function getRandomInteger($iStart=0,$iEnd=10000000) {
		return rand($iStart,$iEnd);
	}

	function getRandomFloat() {
		$sInteger = rand(100,10000000);
		return (float) substr($sInteger,0,strlen($sInteger) - 2).".".substr($sInteger,strlen($sInteger) - 2);
	}

	function getRandomCurrency() {
		$sInteger = rand(100,100000);
		return (float) substr($sInteger,0,strlen($sInteger) - 2).".".substr($sInteger,strlen($sInteger) - 2);
	}

	static function getRandomData($fieldname, $type='', $size=255) {
		switch ($type) {
			case ("varchar"):
				if (strpos($fieldname,"email") > 0) {
					return CTesting::getEmailAddress();
				} elseif (strpos($fieldname,"first") > 0) {
					return CTesting::getFirstName();
				} elseif (strpos($fieldname,"last") > 0) {
					return CTesting::getLastName();
				} elseif (strpos($fieldname,"postal") > 0 || strpos($fieldname,"zip") > 0) {
					return CTesting::getPostalCode();
				} elseif (strpos($fieldname,"region") > 0) {
					return CTesting::getRegionName();
				} elseif (strpos($fieldname,"street") > 0 || strpos($fieldname,"address")) {
					return CTesting::getStreet();
				} elseif (strpos($fieldname,"country") > 0) {
					return CTesting::getCountry();
				} else {
					return CTesting::getRandomText($size);
				}
				break;
			case ("text"):
				return CTesting::getRandomText(1500);
				break;
			case ("tinyint"):
				if (rand(1,2)==1){
					return TRUE;
				} else {
					return FALSE;
				}
				break;
			case ("bigint"):
			case ("int"):
			case "double":
				return mt_rand(1,429496729);
				break;
			case ("datetime"):
				return date("Y-m-d G:i:s");
				break;
			default:
				return "sample data";
		}
	}

	static function getUsername() {
		return CTesting::getFirstName()." ".CTesting::getLastName();
	}

	public static function getNoun() {

		$nouns = array("pencil",
				"plant",
				"rain",
				"river",
				"road",
				"rock",
				"room",
				"rose",
				"seed",
				"shape",
				"shoe",
				"shop",
				"show",
				"sink",
				"snail",
				"snake",
				"snow",
				"soda",
				"sofa",
				"star",
				"step",
				"stew",
				"stove",
				"straw",
				"string",
				"summer",
				"swing",
				"table",
				"tank",
				"team",
				"tent",
				"test",
				"toes",
				"tree",
				"vest",
				"water",
				"wing",
				"winter",
				"woman",
				"women");
		return $nouns[rand(0, count($nouns)-1)];
	}

	public static function getAdjective() {
		$adjectives = array("Heavenly",
							"Heavy",
							"Hellish",
							"Helpful",
							"Helpless",
							"Hesitant",
							"Hideous",
							"High",
							"Highfalutin",
							"High-pitched",
							"Hilarious",
							"Hissing",
							"Historical",
							"Holistic",
							"Hollow",
							"Homeless",
							"Homely",
							"Honorable",
							"Horrible",
							"Hospitable",
							"Hot",
							"Huge",
							"Humdrum",
							"Humorous",
							"Hungry",
							"Hurried",
							"Hurt",
							"Hushed",
							"Husky",
							"Hypnotic",
							"Hysterical");
		return $adjectives[rand(0, count($adjectives)-1)];
	}

	static function getFirstName() {
		$firstnames = array("David",
							"Larry",
							"Bartek",
							"Diarmuid",
							"Brian",
							"George",
							"Anto",
							"Theo",
							"Rajeev",
							"Christopher",
							"Sylvester",
							"Tiger",
							"Ernie",
							"Robert",
							"Bobby",
							"Sammy",
							"Gerry",
							"Michael",
							"Walter");
		return $firstnames[rand(0, count($firstnames)-1)];
	}
	static function getLastName() {
		$lastnames = array("Smith",
							"Powers",
							"Burba",
							"Borsje",
							"McGee",
							"Jones",
							"Michael",
							"Patel",
							"Singh",
							"Reeves",
							"Stallone",
							"Woods",
							"Els",
							"Tripp",
							"Burns",
							"Wilkins",
							"Keane",
							"Collins",
							"Mondale");
		return $lastnames[rand(0, count($lastnames)-1)];
	}
	static function getStreet() {
		$street = array("Fairway Dr",
							"Hillcrest Ave",
							"Long Lane",
							"12th St",
							"St. Catherine's Ave",
							"50th Ave",
							"Park Ave",
							"Avondale Mews",
							"College St",
							"Main St");
		return rand(1000,9999)." ".$street[rand(0, count($street)-1)];
	}
	static function getCity() {
		$cities = array("Gainesville",
							"Berkeley",
							"Decatur",
							"Appalachicola",
							"Dublin",
							"Galway",
							"Washington",
							"Avondale Estates",
							"Lilburn",
							"Sao Paulo");
		return $cities[rand(0, count($cities)-1)];
	}
	static function getEmailAddress() {
		return strtolower(substr(CFunctions::getGuid(),1,8)."@test.oldstuff.com");
	}

	static function getRegionName() {
		$region = array("Georgia",
							"Florida",
							"Louth",
							"Kerry",
							"Cork",
							"Washington",
							"California",
							"Connecticut",
							"Massachusetts",
							"Alabama");
		return $region[rand(0, count($region)-1)];
	}

	public static function getCountry() {
		$oCountry = Country_Eng::newCountry();
		$oCountry->setName(CTesting::getRandomText(23));
		$oCountry->setAbbreviation(CTesting::getRandomText(2));
		Country_Eng::save($oCountry);
		return $oCountry;
	}

	public static function populateCountries() {
		CTesting::truncateTable("tbl_p_country");
		$colCountries = new CCollection();
		for ($i=0;$i<10;$i++) {
			$colCountries->add(CTesting::getCountry());
		}
		return $colCountries;
	}

	static function getPostalCode() {
		if (rand(1,2)==1){
			return rand(100,999)." ".rand(100,999);
		} else {
			return rand(10000,99999);
		}
	}

	static function getRandomText($length,$bPrefix=true) {
		$sTmp = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.";

		// to make it very unique, add a guid at the beginning (helps pass UNIQUE contraints in db)

		if ($bPrefix) {
			$sTmp = CFunctions::getGuid(true).trim($sTmp);
			while (strlen($sTmp) <= $length) {
				$sTmp .= $sTmp;
			}
		}

		return trim(substr($sTmp,0,$length));
	}

	static function getParagraphs($iParagraphCount) {

		$sTmp = "<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>";
		$sReturn = "";
		for ($i=0;$i<$iParagraphCount;$i++) {
			$sReturn .= $sTmp;
		}
		return $sReturn;
	}

	static function getRandomDate($startDate,$endDate){
	    return date("Y-m-d",strtotime("$startDate + ".rand(0,round((strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24)))." days"));
	}

	public static function getRandomUuid($sPreface) {
		return $sPreface."-".CFunctions::getGuid();
	}

	public static function newAccount() {
		$oAccount = Account_Eng::newAccount();
		$oAccount->setId(CTesting::getRandomInteger());
		$oAccount->setUuid(CTesting::getRandomUuid("acc"));
		$oAccount->setName("Your Company, LLC");
		$oAccount->setStatusId(1);
		return $oAccount;
	}


	public static function newInvitation($iAccountId) {
		$oInvitation = Invitation_Eng::newInvitation();
		$oInvitation->setId(CTesting::getRandomInteger());
		$oInvitation->setUuid(CTesting::getRandomUuid("inv"));
		$oInvitation->setAccountId((int) $iAccountId);
		// $oInvitation->setFirstName(CTesting::getFirstName());
		// $oInvitation->setLastName(CTesting::getLastName());
		$oInvitation->setEmailAddress(CTesting::getEmailAddress());
		return $oInvitation;
	}

	public static function newProduct($iAccountId) {
		$oProduct = Product_Eng::newProduct();
		$oProduct->setId(CTesting::getRandomInteger());
		$oProduct->setUuid(CTesting::getRandomUuid("pro"));
		$oProduct->setAccountId((int) $iAccountId);
		$oProduct->setName(CTesting::getAdjective()." ".CTesting::getNoun()." Product");
		$oProduct->setPrice(CTesting::getRandomFloat());
		$oProduct->setCurrency("USD");
		return $oProduct;
	}

	public static function newProductVariation($iProductId) {
		$oProductVariation = ProductVariation_Eng::newProductVariation();
		$oProductVariation->setId(CTesting::getRandomInteger());
		$oProductVariation->setProductId($iProductId);
		$oProductVariation->setUuid(CTesting::getRandomUuid("pro"));
		$oProductVariation->setName(CTesting::getAdjective()." ".CTesting::getNoun()." Product");
		$oProductVariation->setPrice(CTesting::getRandomFloat());		
		$oProductVariation->setCurrency("USD");
		return $oProductVariation;		
	}

	public static function newRole($iAccountId) {
		$oRole = Role_Eng::newRole();
		$oRole->setId(CTesting::getRandomInteger());
		$oRole->setUuid(CTesting::getRandomUuid("rol"));
		$oRole->setAccountId((int) $iAccountId);
		$oRole->setName(CTesting::getAdjective()." ".CTesting::getNoun()." Role");
		return $oRole;
	}

	public static function newTeam($iAccountId) {
		$oTeam = Team_Eng::newTeam();
		$oTeam->setId(CTesting::getRandomInteger());
		$oTeam->setUuid(CTesting::getRandomUuid("tea"));
		$oTeam->setAccountId((int) $iAccountId);
		$oTeam->setName(CTesting::getAdjective()." ".CTesting::getNoun()." Team");
		return $oTeam;
	}
	public static function newUser($iAccountId) {
		$oUser = User_Eng::newUser();
		$oUser->setId(CTesting::getRandomInteger());
		$oUser->setUuid(CTesting::getRandomUuid("use"));
		$oUser->setAccountId((int) $iAccountId);
		$oUser->setFirstName(CTesting::getFirstName());
		$oUser->setLastName(CTesting::getLastName());
		return $oUser;
	}	

	public static function newEntity_Person($iAccountId, 
											$iUserId, 
											$iOrganizationId = 0) {
		return Entity_Eng::createPerson($iAccountId,
												$iUserId,
												$iOrganizationId,
												CTesting::getFirstName(),
												CTesting::getLastName());
	}

	public static function newEntity_Organization($iAccountId, $iUserId) {

		return Entity_Eng::createOrganization($iAccountId,
												$iUserId,
												CTesting::getFirstName()."'s ".CTesting::getAdjective()." ".CTesting::getNoun());
	}	
}

?>