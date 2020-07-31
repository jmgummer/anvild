<?php
/**
* Electronic File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/
$this->pageTitle=Yii::app()->name.' | Home';
$this->breadcrumbs=array('Company Brands)'=>array('ranking/companybrands'));
?>

<?php

/* For the company id */
$coid = Yii::app()->user->company_id;
/* Userlevel */
$level =Yii::app()->user->company_level;
/* Country Code if not set default to Kenya*/
$this_country_code = Yii::app()->params['country_code'];
/* 
** COMPANY BRANDS **
--------------------
** Obtain the company brands, use the status later if it is called for
** Status is left open
*/

?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.css'; ?>"> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.plugin.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.js'; ?>"></script>

<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
	<header role="heading"><h2>Company Brands</h2></header>
	<div role="content">
		<?php if(Yii::app()->user->rpts_only==1){ ?>
		<div class="widget-body no-padding">
		<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'electronic','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('name'=>'myform', 'class'=>'smart-form'))); ?>
		
		<fieldset>
			<div class="brand_section">
				<div class="col-md-4">
					<header role="heading"><strong>Brand Name</strong> </header>
					<br>
					<div class="form-group">
						<input type="text" name="brandname" class="form-control" />
					</div>
				</div>
				<div class="col-md-4">
					<header role="heading"><strong>Select Company</strong> </header>
					<br>
					<div class="form-group">
						<?php echo $form->dropDownList($model, 'company', StorySearch::AgencyCompanies($coid), 
							array(
								'empty'=>'-- Maybe Select A Company ? ;) --',
											'class'=>'form-control',
											'id'=>'company',
											'name'=>'company'
								)
							); 
							?>
					</div>
				</div>
			
				<div class="col-md-4">
					<header role="heading"><strong>Year</strong> </header>
						<br>
					
					<div class="form-group">
						<input type="text" name="year" class="form-control" />
					</div>
				</div>
			</div>
		</fieldset>
		<footer>
		<?php echo CHtml::submitButton('Generate', array('class'=>'btn btn-primary','id'=>'submitp')); ?>
		</footer>
		<?php $this->endWidget(); ?>
		</div>
		<?php }else{
			echo 'You are not allowed to view this report';
		}
		?>
	</div>
</div>

<?php

if(isset($_POST['company']) && isset($_POST['year'])){
	if(!empty($_POST['company']) && !empty($_POST['year']) ){
		$companyid = $_POST['company'];
		$year = $_POST['year'];
		$pdf_title= "<p><strong>The following is your report for $year</strong></p>";
		$sqlbrands = "SELECT distinct(brand_table.brand_id), brand_name,company_name,agency_name,industry_name,sub_industry_name, start_date, end_date  
		from brand_table,agency_company,user_table,industry,sub_industry,brand_agency 
		where brand_table.company_id=38  
		and brand_table.brand_id=brand_agency.brand_id 
		and brand_table.company_id=user_table.company_id 
		and brand_agency.start_date like '%$year%'  
		and  brand_agency.agency_id=agency_company.agency_id 
		and brand_table.sub_industry_id=sub_industry.auto_id 
		and sub_industry.industry_id=industry.industry_id;";
	}elseif (!empty($_POST['brandname']) && empty($_POST['company']) && empty($_POST['year'])) {
		$brandname = $_POST['brandname'];
		$pdf_title= "<p><strong>The following is your report for $brandname</strong></p>";
		$sqlbrands = "SELECT distinct(brand_table.brand_id), brand_name,company_name,agency_name,industry_name,sub_industry_name, start_date, end_date 
		from brand_table,agency_company,user_table,industry,sub_industry,brand_agency 
		where brand_table.brand_name like '%$brandname%' 
		and brand_table.company_id=user_table.company_id 
		and brand_table.brand_id=brand_agency.brand_id 
		and brand_agency.agency_id=agency_company.agency_id 
		and brand_table.sub_industry_id=sub_industry.auto_id 
		and sub_industry.industry_id=industry.industry_id";
	}else{
		$error = 1;
	}

	$sqlcompanybrand = "SELECT * from brand_table 
	inner join user_table on brand_table.company_id=user_table.company_id
	inner join sub_industry on brand_table.sub_industry_id=sub_industry.auto_id
	inner join  industry on sub_industry.industry_id=industry.industry_id
	where brand_name like '%$brandname%';";

	// echo $sqlbrands;
	// exit;
	$html = "<p><strong>Brand Details</strong></p>";

	$html.= "<table class='table table-condensed' width='100%'>
	<tr><td>#</td><td>Brand Name</td><td>Company Name</td><td>Industry</td><td>Sub Industry</td><td>Entry Date</td></tr>";
	if($brand_details = Yii::app()->db3->createCommand($sqlcompanybrand)->queryAll()){
		$count = 1;
		foreach ($brand_details as $key) {
			$brand_name = $key['brand_name'];
			$company_name = $key['company_name'];
			$industry_name = $key['industry_name'];
			$sub_industry_name = $key['sub_industry_name'];
			$start_date = $key['entry_date'];
			$html.="<tr><td>$count</td><td>$brand_name</td><td>$company_name</td><td>$industry_name</td><td>$sub_industry_name</td><td>$start_date</td></tr>";
			$count++;
		}
	}
	$html.= "</table><br>";
	$html .= "<p><strong>Agencies</strong></p>";
	$html.= "<table class='table table-condensed' width='100%'>
	<tr><td>#</td><td>Brand Name</td><td>Company Name</td><td>Agency Name</td><td>Industry</td><td>Sub Industry</td></tr>";
	if($brands_query = Yii::app()->db3->createCommand($sqlbrands)->queryAll()){
		$count = 1;
		foreach ($brands_query as $key) {
			$brand_name = $key['brand_name'];
			$agency_name = $key['agency_name'];
			$company_name = $key['company_name'];
			$industry_name = $key['industry_name'];
			$sub_industry_name = $key['sub_industry_name'];
			$start_date = $key['start_date'];
			$end_date = $key['end_date'];
			$html.="<tr><td>$count</td><td>$brand_name</td><td>$company_name</td><td>$agency_name</td><td>$industry_name</td><td>$sub_industry_name</td></tr>";
			$count++;
		}
	}
	$html.= "</table>";

	// /* 
	// ** PDF Time
	// */
	// $anvil_header = Yii::app()->request->baseUrl . '/images/anvil_header.jpg';
	// $pdf_header = '<img src="'.$anvil_header.'" width="100%" /><br>';
	// $pdf_header.= "<h2>Company Brands - Agency Assignment</h2>";
	// $pdf_header.=$pdf_title;
	// $pdf_file = $pdf_header;
	// $pdf_file = $pdf_file.$html;

	// $pdf = Yii::app()->ePdf2->WriteOutput($pdf_file,array());
	// $filename="company_brands_assignment_"  .str_replace(" ","_",Yii::app()->user->company_name)."_" .date('dmY');
	// $filename=str_replace(" ","_",$filename);
	// $filename=preg_replace('/[^\w\d_ -]/si', '', $filename);
	// $filename_pdf=$filename.'.pdf';
	// $location = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/pdf/".$filename_pdf;

	// /* Set Permissions, Just In case */
	// $endlocation = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/";
	// $cmd = "chmod 777 -Rf $endlocation && chown nobody:nobody -Rf $endlocation";
	// exec($cmd);

	// if(file_put_contents($location, $pdf)){
	// 	$file = Yii::app()->request->baseUrl . '/docs/misc/pdf/'.$filename_pdf;
	//     $fppackage = "<p><a href='$file' class='btn btn-danger btn-xs' target='_blank'><i class='fa fa-file-pdf-o'></i> Download PDF</a></p>";
	// }else{
	//     $fppackage = "";
	// }
	// $html = $fppackage.$html;
	echo $html;
}
?>




<style type="text/css">
.brand_section header,.station_section header{
	margin: 0px 0px !important;
	font-size: 13px !important;
}
.section_clear{
	clear: both;
	padding: 5px 5px;
}
.brand_section,.station_section{
	clear: both;
}
.no-wrap{
	height: 200px;
	width: 100%;
	overflow: auto;
	color: #333;
}
.smart-form .brand_section .checkbox .checkbox {
    padding-left: 20px;
    text-decoration: none;
    float: left;
    width: 30%;
    overflow: hidden;
white-space: nowrap;
text-overflow: ellipsis;
}

.station_section{
	clear: both;
}
.dates_section .input{
	width: 45%;
	float: left;
	padding-right: 10px;
}
.smart-form .station_section .checkbox .checkbox {
    padding-left: 20px;
    text-decoration: none;
    float: left;
    width: 19%;
}
.smart-form .alert ul li{
	list-style: none;
}
.smart-form .checkbox{
	padding: 0px 10px 0px 0px;
}
.smart-form .checkbox label{
	text-decoration: underline;
}
.smart-form .checkbox .checkbox{
	padding-left: 20px;
	text-decoration: none;
}
.smart-form .checkbox .checkbox label{
	text-decoration: none;
	font-size: 11px;
}
.smart-form .checkbox input, .smart-form .radio input{
	position: relative;
	left: 0;
}
.smart-form .checkbox input{
	margin-top: 6px;
}
.smart-form .radio{
	padding-left: 10px;
}

 
fieldset .col-md-4{
	width: 30.33%;
	padding: 0px 10px 0px 0px;
}
fieldset .col-md-6{
	width: 45%;
	padding: 0px 10px 0px 0px !important;
}
</style>