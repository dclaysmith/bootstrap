<?

spl_autoload_register(array('CAutoLoader','autoLoad'), true, true); // As of PHP 5.3.0

class CAutoLoader {

	public static function autoLoad($class_name) {
			
		// don't load PEAR libraries
		if (false !== strpos($class_name,"\\")) {
			return false;
		}
		
		if(substr($class_name,0,17)=="controller_admin_") {
			require_once "classes/controllers/admin/".substr($class_name,17).'.php';
		} elseif(substr($class_name,0,22)=="controller_developers_") {
			require_once "classes/controllers/developers/".substr($class_name,22).'.php';
		} elseif(substr($class_name,0,15)=="controller_api_") {
			require_once "classes/controllers/api/".substr($class_name,15).'.php';
		} elseif(substr($class_name,0,18)=="controller_public_") {
			require_once "classes/controllers/public/".substr($class_name,18).'.php';
		} elseif(substr($class_name,0,15)=="controller_app_") {
			require_once "classes/controllers/app/".substr($class_name,15).'.php';
		} elseif(substr($class_name,0,11)=="controller_") {
			require_once "classes/controllers/".substr($class_name,11).'.php';
		} elseif(substr($class_name,0,11)=="view_admin_") {
			require_once "classes/views/admin/".substr($class_name,11).'.php';
		} elseif(substr($class_name,0,9)=="view_api_") {
			require_once "classes/views/api/".substr($class_name,9).'.php';
		} elseif(substr($class_name,0,9)=="view_app_") {
			require_once "classes/views/app/".substr($class_name,9).'.php';
		} elseif(substr($class_name,0,12)=="view_public_") {
			require_once "classes/views/public/".substr($class_name,12).'.php';
		} elseif(substr($class_name,0,16)=="view_developers_") {
			require_once "classes/views/developers/".substr($class_name,16).'.php';
		} elseif(substr($class_name,0,5)=="view_") {
			require_once "classes/views/".substr($class_name,5).'.php';
		} elseif (strtolower(substr($class_name,0,1))=="c" && strtolower(substr($class_name,0,1)) === substr($class_name,0,1)) {
			/**
		 	 * Child Property Classes (tbl_c_*)
		 	 **/
			if (strtolower(substr($class_name,strlen($class_name)-4,4)) == "base") {

				$class_name = str_replace("_Eng","",$class_name);
				$class_name = str_replace("_Base","",$class_name);
				$class_name = str_replace("_","",$class_name);
				require_once "classes/models/do/doC".substr($class_name,1). '.php';
			} else {

				$class_name = str_replace("_Eng","",$class_name);
				$class_name = str_replace("_","",$class_name);
				require_once "classes/models/bo/boC".substr($class_name,1).'.php';
			}
		} elseif (strpos($class_name,"_c") > 1) {
			/**
		 	 * Child Classes (Internal _c "Product_cFranchise")
		 	 **/
			if (strtolower(substr($class_name,strlen($class_name)-4,4)) == "base") {
				require_once "classes/models/do/doP".substr($class_name,0,strpos($class_name,"_c")). '.php';
			} else {
				require_once "classes/models/bo/boP".substr($class_name,0,strpos($class_name,"_c")). '.php';
			}
		} elseif(substr($class_name,0,1)=="C" && strtoupper(substr($class_name,1,1)) === substr($class_name,1,1)) {
			/**
			 *  select generic classes "CClassName" (works)
			 */
			require_once "classes/helpers/class.".substr($class_name,1). '.php';
		} else {

			/**
			 * return and stop processing if class name has "WebService_"
			 * because the code is created dynamically using the proxy
			 * and will not exit.. or something
			 */
			if (strpos($class_name, "WebService_") !== false) {
				return;
			}

			/**
		 	 * Parent Classes (Product, ProductStatus) or Associative Classes (Product_Site)
		 	 */
			if (strtolower(substr($class_name,strlen($class_name)-4,4)) == "base") {
		 		$class_name = str_replace("_Base","",$class_name);
		 		$class_name = str_replace("_Eng","",$class_name);
				require_once "classes/models/do/doP".$class_name. '.php';
			} elseif (false !== strpos($class_name, "_Eng")) {
		 		$class_name = str_replace("_Eng","",$class_name);
				require_once "classes/models/bo/boP".$class_name. '.php';
			} else {
				require_once "classes/models/bo/boP".$class_name. '.php';
			}		
		}
	}

}


?>