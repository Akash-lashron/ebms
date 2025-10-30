<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
checkUser();
$msg = '';
?>
<?php include "Header.html"; ?>
<script type="text/javascript" language="javascript">
	function goBack(){
		url = "designationlist.php";
		window.location.replace(url);
	}
</script>
<link rel="stylesheet" href="Faq/Slider/FaqStyle.css" type="text/css" />
<script src="Faq/Slider/jquery-1.12.2.min.js" ></script> 
<script src="Faq/Slider/bootstrap.min.js">
<link rel="stylesheet" href="dashboard/css/verticalTab.css">
<script src="dashboard/js/verticalTab.js"></script>
</script>

    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
	    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader" onSubmit="submitform();">
           <?php include "Menu.php"; ?>
            <div class="content">
              <div class="title">Frequently Asked Questions</div>
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote id="bq1" class="bq1" style="overflow:auto">

							 <div align="center"> 
							 
								 <div class="grid FaqClass" data-toggle="modal" data-target="#myModal" data-id="1" data-slide-to="0">
								  	<div class="grid__item">
										<div class="card">
									  		<div class="card__content">
												<i class="fa fa-caret-right" style="font-size:20px"></i> How to create a new staff ?
									  		</div>
										</div>
								  	</div>
								</div>
								
								 <div class="grid FaqClass" data-toggle="modal" data-target="#myModal" data-id="2" data-slide-to="1">
								  	<div class="grid__item">
										<div class="card">
									  		<div class="card__content">
												<i class="fa fa-caret-right" style="font-size:20px"></i> How to create a new user / reset password?
									  		</div>
										</div>
								  	</div>
								</div>
								
								<div class="grid FaqClass" data-toggle="modal" data-target="#myModal" data-id="3" data-slide-to="2">
								  	<div class="grid__item">
										<div class="card">
									  		<div class="card__content">
												<i class="fa fa-caret-right" style="font-size:20px"></i> How to create new work ?
									  		</div>
										</div>
								  	</div>
								</div>
								
								<!--<div class="grid FaqClass" data-toggle="modal" data-target="#myModal" data-id="4" data-slide-to="3">
								  	<div class="grid__item">
										<div class="card">
									  		<div class="card__content">
												<i class="fa fa-caret-right" style="font-size:20px"></i> How to create upload SOQ / BOQ ?
									  		</div>
										</div>
								  	</div>
								</div>-->
								<div class="grid FaqClass" data-toggle="modal" data-target="#myModal" data-id="5" data-slide-to="4">
								  	<div class="grid__item">
										<div class="card">
									  		<div class="card__content">
												<i class="fa fa-caret-right" style="font-size:20px"></i> How to assign staff to works / check Mesurement level ?
									  		</div>
										</div>
								  	</div>
								</div>
								<div class="grid FaqClass" data-toggle="modal" data-target="#myModal" data-id="6" data-slide-to="5">
								  	<div class="grid__item">
										<div class="card">
									  		<div class="card__content">
												<i class="fa fa-caret-right" style="font-size:20px"></i> How to change item type ?
									  		</div>
										</div>
								  	</div>
								</div>
								<div class="grid FaqClass" data-toggle="modal" data-target="#myModal" data-id="7" data-slide-to="6">
								  	<div class="grid__item">
										<div class="card">
									  		<div class="card__content">
												<i class="fa fa-caret-right" style="font-size:20px"></i> How to add work extension ?
									  		</div>
										</div>
								  	</div>
								</div>
								<div class="grid FaqClass" data-toggle="modal" data-target="#myModal" data-id="8" data-slide-to="7">
								  	<div class="grid__item">
										<div class="card">
									  		<div class="card__content">
												<i class="fa fa-caret-right" style="font-size:20px"></i> How to create / view Supplementary ?
									  		</div>
										</div>
								  	</div>
								</div>
								<div class="grid FaqClass" data-toggle="modal" data-target="#myModal" data-id="9" data-slide-to="8">
								  	<div class="grid__item">
										<div class="card">
									  		<div class="card__content">
												<i class="fa fa-caret-right" style="font-size:20px"></i> How to create Additional Quantity Beyond the Deviation Limit ?
									  		</div>
										</div>
								  	</div>
								</div>
								<div class="grid FaqClass" data-toggle="modal" data-target="#myModal" data-id="10" data-slide-to="9">
								  	<div class="grid__item">
										<div class="card">
									  		<div class="card__content">
												<i class="fa fa-caret-right" style="font-size:20px"></i> How to create / view extra Item ?
									  		</div>
										</div>
								  	</div>
								</div>
								<div class="grid FaqClass" data-toggle="modal" data-target="#myModal" data-id="11" data-slide-to="10">
								  	<div class="grid__item">
										<div class="card">
									  		<div class="card__content">
												<i class="fa fa-caret-right" style="font-size:20px"></i> How to create substitute  Item ?
									  		</div>
										</div>
								  	</div>
								</div>
								<div class="grid FaqClass" data-toggle="modal" data-target="#myModal" data-id="12" data-slide-to="11">
								  	<div class="grid__item">
										<div class="card">
									  		<div class="card__content">
												<i class="fa fa-caret-right" style="font-size:20px"></i> How to issue mbook to work  ?
									  		</div>
										</div>
								  	</div>
								</div>
								<div class="grid FaqClass" data-toggle="modal" data-target="#myModal" data-id="13" data-slide-to="12">
								  	<div class="grid__item">
										<div class="card">
									  		<div class="card__content">
												<i class="fa fa-caret-right" style="font-size:20px"></i> How to issue mbook to staff ?
									  		</div>
										</div>
								  	</div>
								</div>
								<div class="grid FaqClass" data-toggle="modal" data-target="#myModal" data-id="14" data-slide-to="13">
								  	<div class="grid__item">
										<div class="card">
									  		<div class="card__content">
												<i class="fa fa-caret-right" style="font-size:20px"></i> How to  migrate work from one person to other ?
									  		</div>
										</div>
								  	</div>
								</div>
								<div class="grid FaqClass" data-toggle="modal" data-target="#myModal" data-id="15" data-slide-to="14">
								  	<div class="grid__item">
										<div class="card">
									  		<div class="card__content">
												<i class="fa fa-caret-right" style="font-size:20px"></i> How to change mbook page no ?
									  		</div>
										</div>
								  	</div>
								</div>
								<div class="grid FaqClass" data-toggle="modal" data-target="#myModal" data-id="16" data-slide-to="15">
								  	<div class="grid__item">
										<div class="card">
									  		<div class="card__content">
												<i class="fa fa-caret-right" style="font-size:20px"></i> How to send mbook to accounts ?
									  		</div>
										</div>
								  	</div>
								</div>
								<!--<div class="grid FaqClass" data-toggle="modal" data-target="#myModal" data-id="17" data-slide-to="16">
								  	<div class="grid__item">
										<div class="card">
									  		<div class="card__content">
												<i class="fa fa-caret-right" style="font-size:20px"></i> How to accept all Measurement Book which are waiting long time for Approval / some other reasons to do the Administrator to accept the RAB?
									  		</div>
										</div>
								  	</div>
								</div>-->
								<!--<div class="grid FaqClass" data-toggle="modal" data-target="#myModal" data-id="18" data-slide-to="17">
								  	<div class="grid__item">
										<div class="card">
									  		<div class="card__content">
												<i class="fa fa-caret-right" style="font-size:20px"></i> How to view work transaction ?
									  		</div>
										</div>
								  	</div>
								</div>-->
								<!--<div class="grid FaqClass" data-toggle="modal" data-target="#myModal" data-id="19" data-slide-to="18">
								  	<div class="grid__item">
										<div class="card">
									  		<div class="card__content">
												<i class="fa fa-caret-right" style="font-size:20px"></i> How to view consolidated work report ?
									  		</div>
										</div>
								  	</div>
								</div>-->
							</div>

							 
                        </blockquote>
                    </div>
                </div>
            </div>
			<!--begin modal window-->
			<div class="modal fade" id="myModal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<div class="pull-left"></div>
							<button type="button" class="close close-icon" data-dismiss="modal" title="Close">x</button>
						</div>
						<div class="modal-body">
						
						<!--begin carousel-->
						
							<div id="myGallery1" class="carousel slide myGallery" data-interval="false" style="display:none"  data-wrap="false">
								<div class="carousel-inner inner-gallery1">
									<div class="item"> 
										<img src="Faq/Admin/MasterMenu.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/StaffCreationMenu.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/StaffListView.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/StaffCreate.png" alt="No Image">
									</div>
									
								</div>
								<a class="left carousel-control" id="Left1" href="#myGallery1" role="button" data-slide="prev" data-id="1"> <i class="fa fa-chevron-left" style="font-size:24px"></i></a> <a class="right carousel-control" style="margin:0 0 0 976px;" href="#myGallery1" role="button" data-slide="next" id="Right1" data-id="1"><i class="fa fa-chevron-right" style="font-size:24px"></i></a>
							</div>

							<div id="myGallery2" class="carousel slide myGallery" data-interval="false" style="display:none" data-wrap="false">
								<div class="carousel-inner inner-gallery2">
									<div class="item"> 
										<img src="Faq/Admin/MasterMenu.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/UserCreationMenu.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/UserListView.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/UserCreate.png" alt="No Image">
									</div>
								</div>
								<a class="left carousel-control" id="Left2" href="#myGallery2" role="button" data-slide="prev"  data-id="2"> <i class="fa fa-chevron-left" style="font-size:24px"></i></a> <a class="right carousel-control" style="margin:0 0 0 976px;" href="#myGallery2" role="button" data-slide="next" id="Right2" data-id="2"><i class="fa fa-chevron-right" style="font-size:24px"></i></a>
							</div>
							
							<div id="myGallery3" class="carousel slide myGallery" data-interval="false" style="display:none" data-wrap="false">
								<div class="carousel-inner inner-gallery3">
									<div class="item"> 
										<img src="Faq/Admin/UploadMenu.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/DepartmentEstimateUpload.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/ConfirmDepartmentEstimate.png" alt="No Image">
									</div>
								</div>
								<a class="left carousel-control" id="Left3" href="#myGallery3" role="button" data-slide="prev" data-id="3"> <i class="fa fa-chevron-left" style="font-size:24px"></i></a> <a class="right carousel-control" style="margin:0 0 0 976px;" href="#myGallery3" role="button" data-slide="next" id="Right3" data-id="3"><i class="fa fa-chevron-right" style="font-size:24px"></i></a>
							</div>
							
							<div id="myGallery4" class="carousel slide myGallery" data-interval="false" style="display:none" data-wrap="false">
								<div class="carousel-inner inner-gallery4">
									<div class="item"> 
										<img src="Faq/Admin/SOQMenu.jpg" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/SOQUpload.jpg" alt="No Image">
									</div>
								</div>
								<a class="left carousel-control" id="Left4" href="#myGallery4" role="button" data-slide="prev" data-id="4"> <i class="fa fa-chevron-left" style="font-size:24px"></i></a> <a class="right carousel-control" style="margin:0 0 0 976px;" href="#myGallery4" role="button" data-slide="next" id="Right4" data-id="4"><i class="fa fa-chevron-right" style="font-size:24px"></i></a>
							</div>
							<div id="myGallery5" class="carousel slide myGallery" data-interval="false" style="display:none" data-wrap="false">
								<div class="carousel-inner inner-gallery5">
									<div class="item"> 
										<img src="Faq/Admin/StaffAssignMenu.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/StaffAssign.png" alt="No Image">
									</div>
								</div>
								<a class="left carousel-control" id="Left5" href="#myGallery5" role="button" data-slide="prev" data-id="5"> <i class="fa fa-chevron-left" style="font-size:24px"></i></a> <a class="right carousel-control" style="margin:0 0 0 976px;" href="#myGallery5" role="button" data-slide="next" id="Right5" data-id="5"><i class="fa fa-chevron-right" style="font-size:24px"></i></a>
							</div>
							<div id="myGallery6" class="carousel slide myGallery" data-interval="false" style="display:none" data-wrap="false">
								<div class="carousel-inner inner-gallery6">
									<div class="item"> 
										<img src="Faq/Admin/ItemTypeChangeMenu.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/ItemTypeViewEdit.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/ItemTypeEdit.png" alt="No Image">
									</div>
								</div>
								<a class="left carousel-control" id="Left6" href="#myGallery6" role="button" data-slide="prev" data-id="6"> <i class="fa fa-chevron-left" style="font-size:24px"></i></a> <a class="right carousel-control" style="margin:0 0 0 976px;" href="#myGallery6" role="button" data-slide="next" id="Right6" data-id="6"><i class="fa fa-chevron-right" style="font-size:24px"></i></a>
							</div>
							<div id="myGallery7" class="carousel slide myGallery" data-interval="false" style="display:none" data-wrap="false">
								<div class="carousel-inner inner-gallery7">
									<div class="item"> 
										<img src="Faq/Admin/WorkExtensionMenu.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/WorkExtensionList.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/WorkExtensionEntry.png" alt="No Image">
									</div>
								</div>
								<a class="left carousel-control" id="Left7" href="#myGallery7" role="button" data-slide="prev" data-id="7"> <i class="fa fa-chevron-left" style="font-size:24px"></i></a> <a class="right carousel-control" style="margin:0 0 0 976px;" href="#myGallery7" role="button" data-slide="next" id="Right7" data-id="7"><i class="fa fa-chevron-right" style="font-size:24px"></i></a>
							</div>
							<div id="myGallery8" class="carousel slide myGallery" data-interval="false" style="display:none" data-wrap="false">
								<div class="carousel-inner inner-gallery8">
									<div class="item"> 
										<img src="Faq/Admin/SuppAgreementEntryMenu.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/SuppAgreementEntry.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/SuppAgreemenrViewMenu.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/SuppAgreementViewEdit.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/SuppAgreementDelete.png" alt="No Image">
									</div>
								</div>
								<a class="left carousel-control" id="Left8" href="#myGallery8" role="button" data-slide="prev" data-id="8"> <i class="fa fa-chevron-left" style="font-size:24px"></i></a> <a class="right carousel-control" style="margin:0 0 0 976px;" href="#myGallery8" role="button" data-slide="next" id="Right8" data-id="8"><i class="fa fa-chevron-right" style="font-size:24px"></i></a>
							</div>
							<div id="myGallery9" class="carousel slide myGallery" data-interval="false" style="display:none" data-wrap="false">
								<div class="carousel-inner inner-gallery9">
									<div class="item"> 
										<img src="Faq/Admin/AgreementItemUploadMenu.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/AdditionalQuantityUpload.png" alt="No Image">
									</div>
								</div>
								<a class="left carousel-control" id="Left9" href="#myGallery9" role="button" data-slide="prev" data-id="9"> <i class="fa fa-chevron-left" style="font-size:24px"></i></a> <a class="right carousel-control" style="margin:0 0 0 976px;" href="#myGallery9" role="button" data-slide="next" id="Right9" data-id="9"><i class="fa fa-chevron-right" style="font-size:24px"></i></a>
							</div>
							<div id="myGallery10" class="carousel slide myGallery" data-interval="false" style="display:none" data-wrap="false">
								<div class="carousel-inner inner-gallery10">
									<div class="item"> 
										<img src="Faq/Admin/AgreementItemUploadMenu.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/ExtraItemUpload.png" alt="No Image">
									</div>
								</div>
								<a class="left carousel-control" id="Left10" href="#myGallery10" role="button" data-slide="prev" data-id="10"> <i class="fa fa-chevron-left" style="font-size:24px"></i></a> <a class="right carousel-control" style="margin:0 0 0 976px;" href="#myGallery10" role="button" data-slide="next" id="Right10" data-id="10"><i class="fa fa-chevron-right" style="font-size:24px"></i></a>
							</div>
							<div id="myGallery11" class="carousel slide myGallery" data-interval="false" style="display:none" data-wrap="false">
								<div class="carousel-inner inner-gallery11">
									<div class="item"> 
										<img src="Faq/Admin/AgreementItemUploadMenu.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/SubItemUpload.png" alt="No Image">
									</div>
								</div>
								<a class="left carousel-control" id="Left11" href="#myGallery11" role="button" data-slide="prev" data-id="11"> <i class="fa fa-chevron-left" style="font-size:24px"></i></a> <a class="right carousel-control" style="margin:0 0 0 976px;" href="#myGallery11" role="button" data-slide="next" id="Right11" data-id="11"><i class="fa fa-chevron-right" style="font-size:24px"></i></a>
							</div>
							<div id="myGallery12" class="carousel slide myGallery" data-interval="false" style="display:none" data-wrap="false">
								<div class="carousel-inner inner-gallery12">
									<div class="item"> 
										<img src="Faq/Admin/MBIssueToWorkMenu.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/MBIssueToWork.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/MBIssueToWorkViewRemove.png" alt="No Image">
									</div>
								</div>
								<a class="left carousel-control" id="Left12" href="#myGallery12" role="button" data-slide="prev" data-id="12"> <i class="fa fa-chevron-left" style="font-size:24px"></i></a> <a class="right carousel-control" style="margin:0 0 0 976px;" href="#myGallery12" role="button" data-slide="next" id="Right12" data-id="12"><i class="fa fa-chevron-right" style="font-size:24px"></i></a>
							</div>
							<div id="myGallery13" class="carousel slide myGallery" data-interval="false" style="display:none" data-wrap="false">
								<div class="carousel-inner inner-gallery13">
									<div class="item"> 
										<img src="Faq/Admin/MbookIssueToStaffMenu.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/MbookIssueToStaff.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/MbookToStaffViewRemove.png" alt="No Image">
									</div>
								</div>
								<a class="left carousel-control" id="Left13" href="#myGallery13" role="button" data-slide="prev" data-id="13"> <i class="fa fa-chevron-left" style="font-size:24px"></i></a> <a class="right carousel-control" style="margin:0 0 0 976px;" href="#myGallery13" role="button" data-slide="next" id="Right13" data-id="13"><i class="fa fa-chevron-right" style="font-size:24px"></i></a>
							</div>
							<div id="myGallery14" class="carousel slide myGallery" data-interval="false" style="display:none" data-wrap="false">
								<div class="carousel-inner inner-gallery14">
									<div class="item"> 
										<img src="Faq/Admin/StaffWorkMigrationMenu.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/StaffWorkMigration.png" alt="No Image">
									</div>
								</div>
								<a class="left carousel-control" id="Left14" href="#myGallery14" role="button" data-slide="prev" data-id="14"> <i class="fa fa-chevron-left" style="font-size:24px"></i></a> <a class="right carousel-control" style="margin:0 0 0 976px;" href="#myGallery14" role="button" data-slide="next" id="Right14" data-id="14"><i class="fa fa-chevron-right" style="font-size:24px"></i></a>
							</div>
							<div id="myGallery15" class="carousel slide myGallery" data-interval="false" style="display:none" data-wrap="false">
								<div class="carousel-inner inner-gallery15">
									<div class="item"> 
										<img src="Faq/Admin/MBPageUpdateMenu.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/MBPageUpdate.png" alt="No Image">
									</div>
								</div>
								<a class="left carousel-control" id="Left15" href="#myGallery15" role="button" data-slide="prev" data-id="15"> <i class="fa fa-chevron-left" style="font-size:24px"></i></a> <a class="right carousel-control" style="margin:0 0 0 976px;" href="#myGallery15" role="button" data-slide="next" id="Right15" data-id="15"><i class="fa fa-chevron-right" style="font-size:24px"></i></a>
							</div>
							<div id="myGallery16" class="carousel slide myGallery" data-interval="false" style="display:none" data-wrap="false">
								<div class="carousel-inner inner-gallery16">
									<div class="item"> 
										<img src="Faq/Admin/RABForwardtoAccountsMenu.png" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/RABForwardtoAccounts.png" alt="No Image">
									</div>
								</div>
								<a class="left carousel-control" id="Left16" href="#myGallery16" role="button" data-slide="prev" data-id="16"> <i class="fa fa-chevron-left" style="font-size:24px"></i></a> <a class="right carousel-control" style="margin:0 0 0 976px;" href="#myGallery16" role="button" data-slide="next" id="Right16" data-id="16"><i class="fa fa-chevron-right" style="font-size:24px"></i></a>
							</div>
							<div id="myGallery17" class="carousel slide myGallery" data-interval="false" style="display:none" data-wrap="false">
								<div class="carousel-inner inner-gallery17">
									<div class="item"> 
										<img src="Faq/Admin/RabAcceptMenu.jpg" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/ViewRab.jpg" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/AcceptRab.jpg" alt="No Image">
									</div>
								</div>
								<a class="left carousel-control" id="Left17" href="#myGallery17" role="button" data-slide="prev" data-id="17"> <i class="fa fa-chevron-left" style="font-size:24px"></i></a> <a class="right carousel-control" style="margin:0 0 0 976px;" href="#myGallery17" role="button" data-slide="next" id="Right17" data-id="17"><i class="fa fa-chevron-right" style="font-size:24px"></i></a>
							</div>
							<div id="myGallery18" class="carousel slide myGallery" data-interval="false" style="display:none" data-wrap="false">
								<div class="carousel-inner inner-gallery18">
									<div class="item"> 
										<img src="Faq/Admin/WorkTransactionMenu.jpg" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/ViewWorkTransaction.jpg" alt="No Image">
									</div>
								</div>
								<a class="left carousel-control" id="Left18" href="#myGallery18" role="button" data-slide="prev" data-id="18"> <i class="fa fa-chevron-left" style="font-size:24px"></i></a> <a class="right carousel-control" style="margin:0 0 0 976px;" href="#myGallery18" role="button" data-slide="next" id="Right18" data-id="18"><i class="fa fa-chevron-right" style="font-size:24px"></i></a>
							</div>
							<div id="myGallery19" class="carousel slide myGallery" data-interval="false" style="display:none" data-wrap="false">
								<div class="carousel-inner inner-gallery19">
									<div class="item"> 
										<img src="Faq/Admin/CosolidatedMenu.jpg" alt="No Image">
									</div>
									<div class="item"> 
										<img src="Faq/Admin/ViewCosolidatedReport.jpg" alt="No Image">
									</div>
								</div>
								<a class="left carousel-control" id="Left19" href="#myGallery19" role="button" data-slide="prev" data-id="19"> <i class="fa fa-chevron-left" style="font-size:24px"></i></a> <a class="right carousel-control" style="margin:0 0 0 976px;" href="#myGallery19" role="button" data-slide="next" id="Right19" data-id="19"><i class="fa fa-chevron-right" style="font-size:24px"></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
           <?php include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
		<script src="js/Resize-Page-Auto.js"></script>
    </body>
</html>
<style>
.fa-chevron-left::before, .fa-chevron-right::before{
	top: 250px !important;
	position: absolute !important;
	color:#000000;
}
.btn-faq-close{
	padding:4px 5px !important;
	border:2px solid #046DA9 !important;
	color:#033378;
	font-family:Verdana;
	font-size:12px;
	font-weight:bold;
	opacity: 1 !important;
}
.btn-faq-close:hover{
	background:#046DA9;
	color:#ffffff;
	font-family:Verdana;
	font-size:12px;
}
.pull-left{
	font-family:Verdana;
	font-size:13px;
	font-weight:bold;
}
a.FaqClass{
	cursor:pointer;
}
.item img{
	width:100%;
	height:500px !important;
	border-radius:4px;
}
.modal-header {
	padding: 5px 15px;
    background-color: #047ABD;
    color: #fff;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
}
.close-icon{
	color:#ffffff;
	font-family:Verdana;
	font-size:16px;
	font-weight:bold;
	opacity: 1 !important;
}





.grid {
  display: grid;
  width: 90%;
  margin-top:10px;
  margin-bottom:5px;
}
.grid__item {
  background-color: #fff;
  border-radius: 0.4rem;
  overflow: hidden;
  box-shadow: 0px 1px 2px 1px rgba(0, 0, 0, 0.4);
  cursor: pointer;
  transition: 0.2s;
}
.grid__item:hover {
	/*border:1px solid #B2B6B9;*/
	/*box-shadow: 2px 1px 2px 1px rgba(0, 0, 0, 0.4);*/
	box-shadow: 0px 1px 2px 3px rgba(9, 106, 178, 0.6);
}

.card__img {
  display: block;
  width: 100%;
  height: 18rem;
  object-fit: cover;
}
.card__content {
  padding: 10px 15px;
  text-align:left;
  font-weight:bold;
  color:#033378;
  font-size:12px;
  font-family:Verdana;
}
.card__header {
  font-size: 3rem;
  font-weight: 500;
  color: #0d0d0d;
  margin-bottom: 1.5rem;
}
.card__text {
  font-size: 1.5rem;
  letter-spacing: 0.1rem;
  line-height: 1.7;
  color: #3d3d3d;
  margin-bottom: 2.5rem;
}
.card__btn {
  display: block;
  width: 100%;
  padding: 1.5rem;
  font-size: 2rem;
  text-align: center;
  color: #3363ff;
  background-color: #e6ecff;
  border: none;
  border-radius: 0.4rem;
  transition: 0.2s;
  cursor: pointer;
}
.card__btn span {
  margin-left: 1rem;
  transition: 0.2s;
}
.card__btn:hover, .card__btn:active {
  background-color: #dce4ff;
}
.card__btn:hover span, .card__btn:active span {
  margin-left: 1.5rem;
}

</style>
<script>
	$(document).ready(function () {
		var ItemCnt = 0; var ImgPoint = 1; var img = 1;
		$(".FaqClass").click(function(event){ 
			ItemCnt = 0; ImgPoint = 1; img = 1;
			var FaqId = $(this).attr("data-id");
			$(".myGallery").hide();
			$("#myGallery"+FaqId).show();
			ItemCnt = $('#myGallery'+FaqId+' .item').length; //alert(ItemCnt);
			$("#Right"+FaqId).show();
			$("#Left"+FaqId).hide();
			if(ImgPoint == ItemCnt){
				$("#Right"+FaqId).hide();
			}
			$(".inner-gallery"+FaqId+" .item").hide();
			$(".inner-gallery"+FaqId+" .item:nth-child(1)").show();
			//img++;
			//$("#myGallery"+FaqId).carousel({ wrap:false});
		});
		$(".right").click(function(event){ 
			ImgPoint++; 
			var FaqId = $(this).attr("data-id");
			if(ImgPoint > 1){ 
				$("#Left"+FaqId).show();
			}
			var RowId = $(this).attr("data-id");
			var ItemCnt = $('#myGallery'+FaqId+' .item').length;
			if(ImgPoint == ItemCnt){
				$("#Right"+FaqId).hide();
			}
			img++;
			$(".inner-gallery"+FaqId+" .item").hide();
			$(".inner-gallery"+FaqId+" .item:nth-child("+img+")").show();
		});
		$(".left").click(function(event){ 
			ImgPoint--;
			var FaqId = $(this).attr("data-id");
			var RowId = $(this).attr("data-id");
			var ItemCnt = $('#myGallery'+FaqId+' .item').length;
			if(ImgPoint < ItemCnt){ 
				$("#Right"+FaqId).show();
			}
			if(ImgPoint == 1){
				$("#Left"+FaqId).hide();
			}
			img--;
			$(".inner-gallery"+FaqId+" .item").hide();
			$(".inner-gallery"+FaqId+" .item:nth-child("+img+")").show();
		});
		//$('#myGallery1').carousel({ interval: 6000, wrap: true, keyboard: true });

//Stop intro slider on last item
		/*var cnt = $('.carousel-inner .item').length; alert(cnt);
		$('.carousel-inner').on('slid', '', function() {
			cnt--;
			if (cnt == 1) {
				$('.carousel-inner').carousel('pause');
			}
		});*/
		
	});
</script>