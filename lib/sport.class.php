<?
class sport 
{
	public $DB;

	public function sport($DBInfo)
	{
		$this->DB = &new DB($DBInfo);
	
	}//end of constructor
	public function getStdData($std_id ){
		$sql =	"SELECT * 
				 FROM std 
				 WHERE std_id = '{$std_id}' ";
     
		$res = $this->DB->query($sql);
      
		return $res;
	}
	
	public function getGrade($birthyear){
		$now_year = date("Y");
		$std_age = $now_year - $birthyear+1;
		
		if($std_age>=17){
			return "A";
		}elseif($std_age <=16 && $std_age >=15){
			return "B";
		}elseif($std_age <=14 && $std_age >=13){
			return "C";
		}elseif($std_age <=12 && $std_age >=11){
			return "D";
		}else{//($age <=10){
			return "E";
		}
	}
	
	public function setShowMode($events){
		$result = "";
		if($events =='0' || $events =='-1'  ){
			$result =  "";
		}else{
			if($events == '1'){
				$result = "*";
			}else{
				$result = $events ; 
			}
		}
		return $result;
	}
	
	public function checkEventTotal($class_name , $events_name ,$sex , $grade ){
		// sport_events = "m50"
		$now_year = date("Y");
		$start_age_year = 0;
		$end_age_year = 0;
		if($grade=="A"){
			$start_age_year = $now_year-99;//小
			$end_age_year = $now_year -17+1;//大
		}elseif($grade=="B"){
			$start_age_year = $now_year-16+1;//小
			$end_age_year = $now_year -15+1;//大
		}elseif($grade=="C"){
			
			$start_age_year = $now_year-14+1;//小
			$end_age_year = $now_year -13+1;//大
		}elseif($grade=="D"){
			
			$start_age_year = $now_year-12+1;//小
			$end_age_year = $now_year -11+1;//大
		}elseif($grade=="E"){
			$start_age_year = $now_year -10+1;//小
			$end_age_year = $now_year;//大
		}
		
		
		$sql = "SELECT COUNT(*) AS result 
				FROM sport_events ,std 
				WHERE sport_events.std_id = std.std_id 
				AND std.class_name = '{$class_name}' 
				AND std.sex = '{$sex}'
				AND (std.year >= {$start_age_year} AND std.year<={$end_age_year})
				AND sport_events.{$events_name} = '1'";
		$res = $this->DB->query($sql);
		$result =0;
		if($res['length']>0){
			$row = $res[0];
			$result = $row['result'];
		}else{
			$result =  0;
		}
		//echo $sql."<br>";
		return $result;
	}
	
	public function insertData($std_id , $m50 ,$m60 , $m100 , $m200 ,$m800 , $highjump , $longjump ,$softball , $shotput , $javelin ,$works ){
		$sql = "INSERT INTO  `sport_events` (
					`std_id` ,
					`m50` ,
					`m60` ,
					`m100` ,
					`m200` ,
					`m800` ,
					`highjump` ,
					`longjump` ,
					`softball` ,
					`shotput` ,
					`javelin` ,
					`works` , 
					`create_time`
				)
				VALUES (
					'{$std_id}' , '{$m50}' ,'{$m60}' , '{$m100}' , '{$m200}' ,'{$m800}' ,
					 '{$highjump}' , '{$longjump}' , '{$softball}' , '{$shotput}' , '{$javelin}' ,'{$works}' , NOW( )
				)";
		 $this->DB->query($sql);
	}

	public function getEvents($std_id){
		$std_id_sql = " 1 ";
		if($std_id!=-1){
			$std_id_sql = " std.std_id = '{$std_id}' ";
		}
		/*
		$sql = "SELECT *
				FROM sport_events ,std 
				WHERE sport_events.std_id = std.std_id 
				AND {$std_id_sql} 
				ORDER BY  std.class_name ASC , std.no ASC  ";
			*/
		/*
		$sql = "SELECT * , std.std_id AS std_id_no 
				FROM std 
				LEFT JOIN  sport_events ON (sport_events.std_id = std.std_id) 
				WHERE {$std_id_sql} 
				ORDER BY  std.class_name ASC , std.no ASC  ";		
		*/
		$sql = "SELECT *  
				FROM std 
				LEFT JOIN  sport_events ON (sport_events.std_id = std.std_id) 
				WHERE {$std_id_sql} 
				ORDER BY  std.class_name ASC , std.no ASC  ";	
		$res = $this->DB->query($sql);
		return $res;
	}

	public function getAllEvents($std_id , $class_name){
		$std_id_sql = " 1 ";
		if($std_id!=-1){
			$std_id_sql = " std.std_id = '{$std_id}' ";
		}
		
		$class_name_sql = " 1 ";
		if($class_name!=-1){
			$class_name_sql = " std.class_name = '{$class_name}' ";
		}

		$sql = "SELECT * , std.std_id AS std_id_no 
				FROM std 
				LEFT JOIN  sport_events ON (sport_events.std_id = std.std_id) 
				WHERE {$std_id_sql} AND {$class_name_sql} 
				ORDER BY  std.class_name ASC , std.no ASC  ";		
		$res = $this->DB->query($sql);
		return $res;
	}	
	function CheckIsSignUp($std_id){
		$sql = "SELECT * 
				FROM sport_events ,std 
				WHERE sport_events.std_id = std.std_id 
				AND std.std_id= '{$std_id}'
				AND (sport_events.m50 = '1' OR sport_events.m60 = '1' OR sport_events.m100 = '1' 
				     OR sport_events.m200 = '1' OR sport_events.m800 = '1' 
					 OR sport_events.highjump = '1' 
					 OR sport_events.longjump = '1' 
					 OR sport_events.softball = '1' 
					 OR sport_events.shotput = '1' 
					 OR sport_events.javelin = '1'
					 OR sport_events.works <> '-1' ) ";

		$res = $this->DB->query($sql);
		if($res['length']>0){
			return true;
		}else{
			return false;
		}
	}
	
	function DelEvents($std_id){
		$sql = "DELETE FROM sport_events 
				WHERE sport_events.std_id = '{$std_id}' ";
				
		$this->DB->query($sql);
	}
	
}//end
?>