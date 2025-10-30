<?php
require_once 'php-excel-reader/excel_reader2.php';
require('SpreadsheetReader.php');
if(isset($_POST["GetSheet"])){
	$target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["excel_file"]["name"]); 
	if (move_uploaded_file($_FILES["excel_file"]["tmp_name"], $target_file)) {
		$Spreadsheet = new SpreadsheetReader($target_file);
		$Sheets = $Spreadsheet->Sheets();
		foreach ($Sheets as $index => $SheetName) {
            echo "<option value=\"" . htmlspecialchars($SheetName) . "\">" . htmlspecialchars($SheetName) . "</option>";
        }
        // unlink($target_file);
	}
}
?>