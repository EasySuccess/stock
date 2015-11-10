<?php

class Table 
{
	public $DB;

	public function __construct($DBInfo){
		$this->DB = new DB($DBInfo);
	}
	
	//------------------------------------------------------------------------------------------------------------------------------------------
	//Doing change below
	
	public function getRecord($record){
		
		$table = $record->tableName;
		$statement = "";
		$matchStatement = "";
		$orderByStatement = "";
		
		foreach($record->getFields() as $field){
			$statement .= "$field,";
		}
		$statement = substr($statement, 0, -1);
		
		if(count($record->getMatchFields()) > 0){
			$matchStatement .= 'WHERE ';
			foreach($record->getMatchFields() as $field){
				$matchStatement .= $field . ' = ' . $record->getMatchValue($field) . ',';
			}
			$matchStatement = substr($matchStatement, 0, -1);
		}
		
		if(isset($record->orderBy)){
			$orderByStatement = "ORDER BY $record->orderBy";
		}
	
		$sql =	"	SELECT $statement	FROM $table $matchStatement $orderByStatement";

		echo $sql;
		$res = $this->DB->query($sql);
      
		return $res;
	}
	
	//-------------------------------------------------------------------------------------------------------------------
	
	public function addRecord($record){
	
		$table = $record->tableName;
		$fieldStatement = "";
		$valueStatement = "";
		
		foreach($record->getFields() as $field){
			$fieldStatement .= "`$field`,";
			$valueStatement .= "'{$record->getValue($field)}',";
		}
		$fieldStatement = substr($fieldStatement, 0, -1);
		$valueStatement = substr($valueStatement, 0, -1);
		
		$sql = "INSERT INTO  `{$table}` ($fieldStatement) VALUES ($valueStatement);";
		$res = $this->DB->insert($sql);
		
		return $res;
	}
	
	public function updateRecord($record){
		$table = $record->tableName;
		$statement = "";
		$matchStatement = "";
		
		foreach($record->getFields() as $field){
			$statement .= "`$field`='{$record->getValue($field)}',";
		}
		$statement = substr($statement, 0, -1);
		
		if(count($record->getMatchFields()) == 0){
		
			$res['ret'] = 3;
			$res['msg'] = "ambiguous update forbidden";
			
		}else{
		
			$matchStatement .= "WHERE ";
			foreach($record->getMatchFields() as $field){
				$matchStatement .= "`$field`='{$record->getMatchValue($field)}',";
			}
			$matchStatement = substr($matchStatement, 0, -1);
			
			$sql = "UPDATE `{$table}` SET $statement $matchStatement";
			$res = $this->DB->update($sql);
		}
		
		return $res;
	}
	
	public function deleteRecord($record){
	
		$table = $record->tableName;
		$matchStatement = "";
		
		if(count($record->getMatchFields()) == 0){
		
			$res['ret'] = 3;
			$res['msg'] = "ambiguous delete forbidden";
			
		}else{
		
			$matchStatement .= "WHERE ";
			foreach($record->getMatchFields() as $field){
				$matchStatement .= "`$field`='{$record->getMatchValue($field)}',";
			}
			$matchStatement = substr($matchStatement, 0, -1);
			
			$sql =	"DELETE FROM `{$table}` $matchStatement";
			$res = $this->DB->update($sql);
		}
		
		echo $sql;
		echo $res['msg'];
		return $res;
	}
	
}
?>