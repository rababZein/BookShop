<?php 
class Validation {
	public $errors=array();

	public function validate($data,$rules){
		$valid=true;
		foreach ($rules as $fieldname=>$rule){
			$callbacks = explode('|',$rule);
			foreach($callbacks as $callback){
				$value= isset($data[$fieldname]) ? $data[$fieldname] : NULL;
				if($this->$callback($value,$fieldname)==false){
					$valid=false;
					
				}
			}
		}
		return $valid;
	}
	public function email($value,$fieldname){
		$valid=filter_var($value,FILTER_VALIDATE_EMAIL);
		//$domain = substr($value, strpos($value, '@')+1, strlen($value));
		
		if($valid==false){

			$this->errors[]="$fieldname NEED TO BE A VALID EMAIL";
		}
		// if($domain != "gmail.com"){
		// 	$valid=false;
		// 	$this->errors[]="$fieldname domain must be gmail.com ";
		// }
		return $valid;
	}
	
	public function required($value,$fieldname){
		$valid = !empty($value);
		if($valid==false){
			$this->errors[]="$fieldname is required";
		}
		return $valid;
		
	}
	public function confirmPassword($Password,$confirmPassword){
		if($Password==$confirmPassword){
			$valid = true;
		}
		else{
			$valid=false;
			$this->errors[]="confirm password not the same as password ";
		}
		return $valid;
		
	}
}
?>
