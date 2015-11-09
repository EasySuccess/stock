<?php

/*
 * DB Class
 */
class DB
{
	
	public $hostName = "";
	public $dbName = "";
	public $userName = "";
	public $passwd = "";
	public $charset;
	public $DB = "";
	public $link;
	
	/*
	 * constructor
	 */
	public function DB($DBInfo)
	{
		
		$this->hostName = $DBInfo['hostName'];
		$this->dbName   = $DBInfo['dbName'];
		$this->userName = $DBInfo['userName'];
		$this->passwd   = $DBInfo['passwd'];
		$this->charset  = "SET NAMES utf8";
		
	} //end of constructor
	
	/*
	 * Connect DB
	 */
	public function connect()
	{
		
		$this->link = mysql_pconnect($this->hostName, $this->userName, $this->passwd);
		
		if ($this->link == FALSE) {
			
			$err = mysql_error();
			
			$retInfo['ret'] = 1;
			$retInfo['msg'] = $err;
			
			//log
			//trigger_error(mysql_error(), E_USER_ERROR); 
			
		} else {
			
			mysql_query($this->charset);
			
			if (!mysql_select_db($this->dbName, $this->link)) {
				
				$err = mysql_error();
				
				$retInfo['ret'] = 2;
				$retInfo['msg'] = $err;
				
			} else {
				
				//ok
				$retInfo['ret'] = 0;
				$retInfo['msg'] = "connect success";
				
			}
		} //end of link    
		
		return $retInfo;
		
	} //end of open
	
	/*
	 * close
	 */
	public function close()
	{
		
		//disconnect db
		mysql_close($this->link);
		
	}
	
	/*
	 * query
	 */
	public function query($sql)
	{
		
		$resInfo = $this->connect();
		
		if ($resInfo['ret'] == 0) {
			
			$res = mysql_query($sql, $this->link);
			
			if ($res != FALSE) {
				
				$i = 0;
				while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
					$resultSet[$i++] = $row;
				}
				
				$resultSet['ret']    = 0;
				$resultSet['msg']    = "{$i} rows selected.";
				$resultSet['length'] = $i;
				
				//log off
				$this->close();
				
				mysql_free_result($res);
				
				return $resultSet;
				
			} else {
				
				$err = mysql_error();
				
				$retInfo['ret'] = 3;
				$retInfo['msg'] = $err['message'];
				
				//log off
				$this->close();
				
				mysql_free_result($res);
				
				return $retInfo;
			}
			
		} else {
			
			return $resInfo;
			
		}
		
	} //end of query
	
	/*
	 * insert
	 */
	public function insert($sql)
	{
		
		$resInfo = $this->connect();
		
		if ($resInfo['ret'] == 0) {
			
			$res = mysql_query($sql, $this->link);
			
			if ($res != FALSE) {
				
				$retInfo['ret'] = 0;
				$retInfo['msg'] = "insert success";
				$retInfo['id']  = mysql_insert_id($this->link);
				
			} else {
				
				$err = mysql_error();
				
				$retInfo['ret'] = 3;
				$retInfo['msg'] = $err['message'];
				
			}
			
			//log off
			$this->close();
			
			return $retInfo;
			
		} else {
			
			return $resInfo;
			
		}
		
	} //end of insert
	
	/*
	 * update
	 */
	public function update($sql)
	{
		
		$resInfo = $this->connect();
		
		if ($resInfo['ret'] == 0) {
			
			$res = mysql_query($sql, $this->link);
			
			if ($res != FALSE) {
				
				$retInfo['ret'] = 0;
				$retInfo['msg'] = "update success";
				
			} else {
				
				$err = mysql_error();
				
				$retInfo['ret'] = 3;
				$retInfo['msg'] = $err['message'];
				
			}
			
			//log off
			$this->close();
			
			return $retInfo;
			
		} else {
			
			return $resInfo;
			
		}
		
	} //end of update
		
} //end of DB Class

?>
