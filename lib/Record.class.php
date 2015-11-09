<?php

class Record
{
	var $tableName;
	var $matchFields = array();
	var $matchValues = array();
	var $fields = array();
	var $values = array();
	var $orderBy;
	
	function __construct( $par1 ){
		$this->tableName = $par1;
	}
	
	function setField($par1){
		array_push($this->fields, $par1);
	}
	
	function setValue($par1, $par2){
		array_push($this->fields, $par1);
		array_push($this->values, $par2);
	}
	
	function setMatchValue($par1, $par2){
		array_push($this->matchFields, $par1);
		array_push($this->matchValues, $par2);
	}
	
	function setOrder($par1){
		$this->orderBy = $par1;
	}
	
	function getValue($par1){
		$key = array_search($par1, $this->fields);
		return $this->values[$key];
	}
	
	function getMatchValue($par1){
		$key = array_search($par1, $this->matchFields);
		return $this->matchValues[$key];
	}
	
	function getFields(){
		return $this->fields;
	}
	
	function getMatchFields(){
		return $this->matchFields;
	}
}

?>