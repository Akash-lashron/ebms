<?php
$pagetitle = ':: e-Measurement System ::';
$DSType = "";
$GlobPageTitleArr = array(
386=>"Aluminium Works",
77=>"Antitermite Treatment",
3=>"Area Cleaning & Jungle Clearance",
163=>"Brick work",
229=>"Ceramic flooring & Dado",
97=>"Controlled RCC",
7=>"Conveying",
222=>"Granite flooring",
780=>"Horticulture",
216=>"Kota stone flooring",
210=>"Marble flooring",
265=>"Painting",
766=>"Dismantling and Demolision Items",
5=>"Earthwork Excavation",
137=>"Expansion Joint",
6=>"Filling Works",
401=>"False Ceiling",
118=>"Form Work & Scaffoldings",
134=>"Galvalume Sheet Roofing",
390=>"Glazing",
246=>"Paver block & Wall tile cladding",
493=>"Pipe Line Works",
82=>"Plain Cement Concrete",
252=>"Plastering",
296=>"Polishing & Varnishing",
107=>"Precast RCC",
238=>"PVC sheet flooring",
726=>"Road Works",
379=>"Rolling Shutter",
407=>"Sanitary Installations",
179=>"Solid Block Masonry",
198=>"Soling",
114=>"Steel Reinforcement",
123=>"Structural Steel Works",
937=>"Sub Data",
4=>"Tree Cutting",
147=>"Water / Weather Proofing",
299=>"Wood Works");

$GlobPageUrlArr = array(
386=>"DataSheetCreateAluminiumWorks",
77=>"DataSheetCreateAntitermiteTreatment",
3=>"DataSheetCreateAreaCleaning",
163=>"DataSheetCreateBrickwork",
229=>"DataSheetCreateCeramicflooringDado",
97=>"DataSheetCreateControlledRCC",
7=>"DataSheetCreateConveying",
222=>"DataSheetCreateGraniteflooring",
216=>"DataSheetCreateKotastoneflooring",
210=>"DataSheetCreateMarbleflooring",
265=>"DataSheetCreatePainting",
766=>"DataSheetCreateDismantlingDemolisionItems",
5=>"DataSheetCreateTreeCutting",
137=>"DataSheetCreateExpansionJoint",
6=>"DataSheetCreateFilling",
401=>"DataSheetCreateFalseCeiling",
118=>"DataSheetCreateFormWorkScaffoldings",
134=>"DataSheetCreateGalvalumeSheetRoofing",
390=>"DataSheetCreateGlazing",
246=>"DataSheetCreatePaverblockWalltilecladding",
493=>"DataSheetCreatePipeLineWorks",
82=>"DataSheetCreatePlainCementConcrete",
252=>"DataSheetCreatePlastering",
296=>"DataSheetCreatePolishingvarnishing",
107=>"DataSheetCreatePrecastRCC",
238=>"DataSheetCreatePVCsheetflooring",
726=>"DataSheetCreateRoadWorks",
379=>"DataSheetCreateRollingShutter",
407=>"DataSheetCreateSanitaryInstallations",
179=>"DataSheetCreateSolidBlockMasonry",
198=>"DataSheetCreateSoling",
114=>"DataSheetCreateSteelReinforcement",
123=>"DataSheetCreateStructuralSteelWorks",
937=>"Sub Data",
4=>"DataSheetCreateTreeCutting",
147=>"DataSheetCreateWaterWeatherProofing",
299=>"DataSheetCreateWoodWorks");

$GlobPageTitleArrHC = array(
1=>"Planting & Supplying",
2=>"Maintenance & Development"
);

$GlobLCItemArr = array(0=>"717",1=>"718",2=>"136",3=>"85",4=>"117");
$GlobCapacity = 8;
$GlobNetPayableAfterVoidDed = 6.4;
$GlobLCItemCodeArr 	= array('EW45a','EW45b','EW45c','EW45d','EW45e');

$PTPart1 	= 'FRFCF - EBMS';
$PTIcon 	= '&nbsp;&nbsp;<i class="fa fa-angle-double-right" style="font-size:19px"></i>&nbsp;&nbsp;';
//$DecMinHighLevel = 3; Bill Approve from AAo level
$DecMinHighLevelRet = 2;
$DecMinHighLevel = 5;
$DecMaxHighLevel = 5;
$DecMinHighLevelAppr = 3; // Bank Detail, Vendor detail Approve
$CHD0 = "Check Measurement is completed and ready to sent to Accounts";
$CHD1 = "Check Measurement Already Done by You and waiting for next level checking";
$CHD2 = "Check Measurement Already Done by You and Returned to previous level checking";


$CHD3 = "Previous Level Not Completed the Check Measurement";
$CHD4 = "Check Measurement Process is in Lower Level";
$CHD5 = "Check Measurement Process is in Lower Level";
//$CHD6 = "Check Measurement Already Done by You. Waiting for higher level checking";
$CHD7 = "Check Measurement Forwarded to Next Level";
$CHD8 = "Check Measurement Backawrd to Higher Level";
$CHD9 = "Check Measurement Forward to Next Level";
$CHD10 = "Check Measurement Returned to Previous Level";

$CHMStatArr = array('ER000'=>$CHD0, 'ER001'=>$CHD1, 'ER002'=>$CHD2, 'ER003'=>$CHD3, 'ER004'=>$CHD4, 'ER005'=>$CHD5, 'ER006'=>"", 'ER007'=>$CHD7, 'ER008'=>$CHD8, 'ER009'=>$CHD9, 'ER0010'=>$CHD10);
$GlobUnitId = 6;// This is for FRFCF
$GlobMiscellId = 1046;// This is for Miscellaneous Work Glob Id
?>