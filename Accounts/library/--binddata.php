<?php
ob_start();
require_once 'config.php';
//require_once 'ExcelReader/excel_reader2.php';
$userid = $_SESSION['userid'];
$staffid = $_SESSION['sid'];
class BindList
{
	/*public function BindStaffRole($rolevalue,$sectionid)
    {
		$levelquery = "SELECT sroleid, role_name FROM staffrole WHERE active=1 AND sectionid = '$sectionid' ORDER BY levelid desc";
        $levelsql = mysqli_query($dbConn,$levelquery);
        if ($levelsql == true )
        {
        	while($row = mysqli_fetch_array($levelsql))
            {
				if ($rolevalue == $row['sroleid'])
				{
					$sel = "selected";
				}
				else
				{
					$sel = "";
				}
                $section .=  '<option value="'. $row['sroleid'].'"'.$sel.'>'.$row['role_name'].'</option>'; 
            }            
        }
        return $section;            
    }*/
	public function BindUnit($unit_name){
		$selectquery 	= "select * from unit ORDER BY unit_name asc";
		$selectsql 		= mysqli_query($dbConn,$selectquery);
		if($selectsql == true ){
			while($row = mysqli_fetch_array($selectsql)){
				if($unit_name == $row['unit_name']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$res .=  '<option value="'. $row['unit_name'].'"'.$sel.'>'.$row['unit_name'].'</option>';
			}            
		}
		return $res;            
	} 
	public function BindReferenceID($ref_id){
		$selectquery 	= "select * from datasheet_master ORDER BY ref_id asc";
		$selectsql 		= mysqli_query($dbConn,$selectquery);
		if($selectsql == true ){
			while($row = mysqli_fetch_array($selectsql)){
			$res .=  '<option value="'.$row['ref_id'].'">';				}            
		}
		return $res;            
	} 
	
	public function BindGroupI($group_id,$Type){
		$selectquery 	= "select * from group_datasheet where char_length( group_id ) = '2' and type='$Type' order by group_id";
		$selectsql 		= mysqli_query($dbConn,$selectquery);
		if($selectsql == true ){
			while($row = mysqli_fetch_array($selectsql)){
				if($group_id == $row['group_id']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$res .=  '<option data-id="'.$row['id'].'" data-parid="'.$row['par_id'].'" data-group="1" value="'. $row['group_id'].'"'.$sel.'>'.$row['group_desc'].'</option>';
			}            
		}
		return $res;            
	} 
	public function BindGroup2($group1_id,$group2_id){
		$selectquery 	= "select * from group_datasheet where group_id like '".$group1_id."%' and char_length( group_id ) = '4' and delete_In='' order by group_id";
		$selectsql 		= mysqli_query($dbConn,$selectquery);
		if($selectsql == true ){
			while($row = mysqli_fetch_array($selectsql)){
				if($group2_id == $row['group_id']){
					$sel = "selected";
				}else{
					$sel = "";
				}
				$res .=  '<option data-id="'.$row['id'].'" data-parid="'.$row['par_id'].'" data-group="2" value="'. $row['group_id'].'"'.$sel.'>'.$row['group_desc'].'</option>';
			}            
		}
		return $res;            
	} 
}
$objBind = new BindList();
?>