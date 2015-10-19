<?php
require 'appconf.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ORM
 *
 * @author islam
 */
class ORM {
    //put your code here
    
	static $conn;
	private $dbconn;
	protected $table;

	public $errorsInDataBase=array();

	static function getInstance(){
		if(self::$conn == null){
			self::$conn = new ORM();
		}
	        return self::$conn;
	}
    
	protected function __construct(){        
		extract($GLOBALS['conf']);
		$this->dbconn = new mysqli($host, $username, $password, $database);
	}
    
	function getConnection(){
		return $this->dbconn;
	}
    
	function setTable($table){
		$this->table = $table;
	}

	function insert($data){
		$query = "insert into $this->table set ";
		foreach ($data as $col => $value) {

		$unDoubleQuotingValue=str_replace('"', "", $value);
		$unQuotingValue=str_replace("'", "", $unDoubleQuotingValue);

		if (gettype($unQuotingValue) == "string")
			$query .= $col."= '".$unQuotingValue."', ";
		else
			$query .= $col."= $unQuotingValue , ";  
		}
		$query[strlen($query)-2]=" ";
		$state = $this->dbconn->query($query);
		if(! $state){
		    $this->errorsInDataBase[]= $this->dbconn->error;
			$done=0;
		}else{$done=1;}
		
		//return $this->dbconn->affected_rows;  
		return $done;   
    }


	function update($data,$conditions){
		$query = "update $this->table set ";
		foreach ($data as $col => $value) {

			$unDoubleQuotingValue=str_replace('"', "", $value);
			$unQuotingValue=str_replace("'", "", $unDoubleQuotingValue);
			$unsemicolum=str_replace(";", "", $unQuotingValue);

			if (gettype($value) == "string")
		    		$query .= $col."= '".$unsemicolum."', ";
			else
		    		$query .= $col."= $unsemicolum , ";
		}
		$query[strlen($query)-2]=" ";
		$query .="WHERE ";
		foreach ($conditions as $col => $value) {

			$unDoubleQuotingValue=str_replace('"', "", $value);
			$unQuotingValue=str_replace("'", "", $unDoubleQuotingValue);
			$unsemicolum=str_replace(";", "", $unQuotingValue);

			$query .= $col."= '".$unsemicolum."'AND ";            
		}
		$query = substr ( $query  , 0 , strlen($query)-4) ;

		$state = $this->dbconn->query($query);
		if(! $state){
		   // return $this->dbconn->error;
		   $this->errorsInDataBase[]= $this->dbconn->error;
		}
		//return $this->dbconn->affected_rows;
		return $state;
    	}
	

	function delete( $cond){
		$query = "delete from $this->table where ";
		foreach($cond as $col =>$value){
			if (gettype($value) == "string")
				$query.= "$col = '$value'  and ";
			else
				$query.= "$col = $value  and ";

		}
		$query = substr ( $query  , 0 , strlen($query)-4) ;
	        $state = $this->dbconn->query($query);
        	if(! $state){
		    $this->errorsInDataBase[]= $this->dbconn->error;
        	    //return $this->dbconn->error;
        	}
        	//return $this->dbconn->affected_rows;
		return $state;
    	}


	function selectRow( $cols ,  $cond='' ){
		$query = "select  $cols from $this->table ";
		if (!empty($cond)){
			$query.="where ";
			foreach($cond as $col =>$value){
				if (gettype($value) == "string")
					$query.= "$col = '$value'  and ";
				else
					$query.= "$col = $value  and ";
				}
			$query = substr ( $query  , 0 , strlen($query)-4) ;
		}

	        //$state = $this->dbconn->query($query);
		$state=mysqli_query($this->dbconn, $query);
        	if(!$state){
        	  
			$this->errorsInDataBase[]= $this->dbconn->error;
        	}
		
		else{
			$row = mysqli_fetch_assoc($state);
		}
		return $row;
    	}

	function selectAll( $cols ,  $cond=''){
		$query = "select  $cols from $this->table ";
		if (!empty($cond)){
			$query.="where ";
			foreach($cond as $col =>$value){
				if (gettype($value) == "string")
					$query.= "$col = '$value'  and ";
				else
					$query.= "$col = $value  and ";
				}
			$query = substr ( $query  , 0 , strlen($query)-4) ;
		}

	        //$state = $this->dbconn->query($query);
		$state=mysqli_query($this->dbconn, $query);
        	if(!$state){
        	  
			$this->errorsInDataBase[]= $this->dbconn->error;
        	}
		
		else{

			$allRow=array();
			$num_results =mysqli_num_rows($state);
			for ($i=0; $i <$num_results; $i++) {
				$row = mysqli_fetch_assoc($state);
				$allRow[]=$row;
			}
		}
		return $allRow ;
    	}

	



}	
