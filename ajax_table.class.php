<?

	/*
	* Add edit delete rows dynamically using jquery and php
	* http://www.amitpatil.me/
	*
	* @version
	* 2.0 (4/19/2014)
	* 
	* @copyright
	* Copyright (C) 2014-2015 
	*
	* @Auther
	* Amit Patil
	* Maharashtra (India)
	*
	* @license
	* This file is part of Add edit delete rows dynamically using jquery and php.
	* 
	* Add edit delete rows dynamically using jquery and php is freeware script. you can redistribute it and/or 
	* modify it under the terms of the GNU Lesser General Public License as published by
	* the Free Software Foundation, either version 3 of the License, or
	* (at your option) any later version.
	* 
	* Add edit delete rows dynamically using jquery and php is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	* GNU General Public License for more details.
	* 
	* You should have received a copy of the GNU General Public License
	* along with this script.  If not, see <http://www.gnu.org/copyleft/lesser.html>.
	*/
 @ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
class ajax_table {
     
  /*public function __construct(){
	$this->dbconnect();
  }*/
   
 /* private function dbconnect() {
    $conn = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)
      or die ("<div style='color:red;'><h3>Could not connect to MySQL server</h3></div>");
         
    mysql_select_db(DB_DB,$conn)
      or die ("<div style='color:red;'><h3>Could not select the indicated database</h3></div>");
     
    return $conn;
  }*/
   
  function getRecords($temp,$menutype)
  {
  	if($temp == 'role')
	{
		$this->res = mysql_query("select * from userrole");
	}
	elseif($temp == 'users')
	{
		$this->res = mysql_query("select * from users where active = 1");
	}
	elseif($temp == 'menu')
	{
		$this->res = mysql_query("select * from menu");
	}
	elseif($temp == "X")
	{
		//$this->res = mysql_query("select * from modules where type = '$menutype'");
		$this->res = mysql_query("select * from modules");
		//$this->res = mysql_query("Select c1.moduledname,c1.modulecode,c1.parentcode from modules c1,modules c2 where c1.modulecode =c2.parentcode and c1.type = '1'");
		//$this->res = mysql_query("Select * from modules c1,modules c2 where c1.modulecode =c2.parentcode");
	}
	elseif($temp == "Y")
	{
		$this->res = mysql_query("select * from modules where parentcode = '$menutype' and parentcode != '0'");
		//$this->res = mysql_query("select * from modules");
	}
	else
	{
		$this->res = mysql_query("select * from info");
	}
	if(mysql_num_rows($this->res))
	{
		while($this->row = mysql_fetch_assoc($this->res)){
			$record = array_map('stripslashes', $this->row);
			$this->records[] = $record; 
	}
		return $this->records;
	}
	//else echo "No records found";
  }	

  function save($data,$temp,$menutype,$pcode)
  {
	if(count($data))
	{
		if($temp == 'role')
		{
			//$values = implode("''", array_values($data));
			$values = array_values($data);
			$values = $values[0];
			$fieldname = "roledescription";
			mysql_query("insert into userrole (".$fieldname.") values ('".$values."')");
			
			if(mysql_insert_id()) return mysql_insert_id();
			return 0;
		}
		else
		{
			//$values = implode("','", array_values($data));
			$fieldname = "moduledname,modulecode,type,parentcode";
			//$sql = "insert into info (".$fieldname.") values ('".$values."')";
			//mysql_query("insert into info (".$fieldname.") values ('".$values."')");
			$values = array_values($data);
			$values = $values[0]."','".$values[1]."','".$menutype."','".$pcode;
			//$fieldname = "roledescription";
			mysql_query("insert into modules (".$fieldname.") values ('".$values."')");
			
			if(mysql_insert_id()) return mysql_insert_id();
			return 0;
		}
	}
	else return 0;	
  }	

  function delete_record($id,$temp){
	 if($id)
	 {
	 	if($temp == 'role')
		{
			mysql_query("delete from userrole where roleid = $id limit 1");
			return mysql_affected_rows();
		}
		else
		{
			mysql_query("delete from modules where moduleid = $id limit 1");
			return mysql_affected_rows();
		}
	 }
  }	

  function update_record($data,$temp){
	if(count($data))
	{
		if($temp == 'role')
		{
			$id = $data['rid'];
			unset($data['rid']);
			/*$values = implode("','", array_values($data));
			$str = "";
			foreach($data as $key=>$val){
				$str .= $key."='".$val."',";
			}
			$str = substr($str,0,-1);*/
			$values = array_values($data);
			$values = $values[0];
			$sql = "update userrole set roledescription = '$values' where roleid = $id limit 1";
	
			$res = mysql_query($sql);
			
			if(mysql_affected_rows()) return $id;
			return 0;
		}
		else
		{
			$id = $data['rid'];
			unset($data['rid']);
			
			/*$values = array_values($data);
			for($i=0; $i<count($values); $i+=5)
			{
				$fname = $values[$i];
				$lname = $values[$i+1];
				$tech = $values[$i+2];
				$email = $values[$i+3];
				$address = $values[$i+4];
				$sql = "update info set fname = '$fname', lname = '$lname', tech = '$tech', email = '$email', address = '$address' where id = $id limit 1";
				$res = mysql_query($sql);
			}*/
			
			$values = array_values($data);
			$menuname = $values[0];
			$mencode  = $values[1];
			$sql = "update modules set moduledname = '$menuname', modulecode = '$mencode' where moduleid = $id limit 1";
			$res = mysql_query($sql);
			
			
	/*		$values = implode("','", array_values($data));
			$str = "";
			foreach($data as $key=>$val){
				$str .= $key."='".$val."',";
			}
		*/
			//$str = substr($str,0,-1);
			//$sql = "update info set $str where id = $id limit 1";
	
			//$res = mysql_query($sql);
			
			if(mysql_affected_rows()) return $id;
			return 0;
		}
	}
	else return 0;	
  }	

  function update_column($data,$temp)
  {
	if(count($data))
	{
		if($temp == 'role')
		{
			$id = $data['rid'];
			unset($data['rid']);
			$values = array_values($data);
			$values = $values[0];
			$sql = "update userrole set roledescription = '$values' where roleid = $id limit 1";
			$res = mysql_query($sql);
			if(mysql_affected_rows()) return $id;
			return 0;
		}
		else
		{
			$id = $data['rid'];
			unset($data['rid']);
			$sql = "update info set ".key($data)."='".$data[key($data)]."' where id = $id limit 1";
			$res = mysql_query($sql);
			if(mysql_affected_rows()) return $id;
			return 0;
		}
		
	}	
  }

  function error($act){
	 return json_encode(array("success" => "0","action" => $act));
  }

}
?>