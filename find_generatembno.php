<?php
session_start();
@ob_start();
require_once 'library/config.php';
$data='';
$sheetid = $_GET['sheetid'];
$staffid = $_SESSION['sid'];
$mtype = $_GET['mtype'];
$mbookquery = "SELECT    agreementmbookallotment.allotmentid, agreementmbookallotment.mbno, agreementmbookallotment.mbooktype, mbookallotment.mballotmentid
                          FROM mbookallotment
                            INNER JOIN agreementmbookallotment ON (mbookallotment.sheetid = agreementmbookallotment.sheetid) AND (mbookallotment.allotmentid = agreementmbookallotment.allotmentid)
                            WHERE agreementmbookallotment.mbooktype = '$mtype' AND mbookallotment.active=1 AND agreementmbookallotment.active = 1 AND mbookallotment.flag = 1 AND mbookallotment.sheetid = '$sheetid' AND mbookallotment.staffid = '$staffid' ORDER BY mbookallotment.mballotmentid ASC";
$mbooksqlquery = mysql_query($mbookquery);
           // echo $mbooksqlquery;
           if ($mbooksqlquery == true )
           {
            $id = 'group2';
            $mbno = 'group2';       
            while($row = mysql_fetch_array($mbooksqlquery))
            {
                $id=$id . '*' . $row['allotmentid'];
                $mbno=$mbno . '*' . $row['mbno'];
               // $mbno .= "MBNO"."*".$row['mbno'];
            } 
            $group=$id . '*' . $mbno;
           }
echo $group;
?>