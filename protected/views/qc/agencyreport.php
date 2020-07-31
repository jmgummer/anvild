<?php
/**
* Agency Report View File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2017
*/
$this->pageTitle=Yii::app()->name.' | Analytics POF';
$this->breadcrumbs=array(' Agency Report'=>array('qc/agencyreport'));

$coid = Yii::app()->user->company_id;
$level =Yii::app()->user->company_level;
$this_country_code = Yii::app()->params['country_code'];

?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.css'; ?>"> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.plugin.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.js'; ?>"></script>

<div id="wid-id-0" class="jarviswidget jarviswidget-sortable" style="" role="widget">
	<header role="heading"><h2>Agency Ranking Report</h2></header>
	<div role="content">
		<?php if(Yii::app()->user->rpts_only==1): ?>
		<div class="widget-body no-padding">
		<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'electronic','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('name'=>'myform', 'class'=>'smart-form'))); ?>
		
		<fieldset>
			<div class="brand_section">
				
				<div class="col-md-4">
					<header role="heading"><strong>Select Agency</strong> </header>
					<br>
					<div class="form-group">
						<?php echo $form->dropDownList($model, 'company', AgencyCompany::model()->getAgencyList(), 
							array(
								'empty'=>'-- All Agencies --',
											'class'=>'form-control',
											'id'=>'company',
											'name'=>'company'
								)
							); 
							?>
					</div>
				</div>
			
				<div class="col-md-4">
					<header role="heading"><strong>Start Date</strong> </header>
						<br>
					
					<div class="form-group">
						<?php echo $form->textField($model,'startdate',array('class'=>'form-control','name'=>'startdate','id'=>'startdate')); ?>
					</div>
				</div>

				<div class="col-md-4">
					<header role="heading"><strong>End Date</strong> </header>
						<br>
					
					<div class="form-group">
						<?php echo $form->textField($model,'enddate',array('class'=>'form-control','name'=>'enddate','id'=>'enddate')); ?>
					</div>
				</div>
			</div>
		</fieldset>
		<footer>
		<?php echo CHtml::submitButton('Generate', array('class'=>'btn btn-primary','id'=>'submitp')); ?>
		</footer>
		<?php $this->endWidget(); ?>
		</div>
		<?php else :
			echo 'You are not allowed to view this report';
		endif;
		?>
	</div>
</div>

<?php

if(isset($_POST['company']) && isset($_POST['startdate']) && isset($_POST['enddate']) ){
	$excelarray = array();
	$agency_id = $_POST['company'];
	if($agency_id!=''){
		$agencyselect = " AND brand_agency.agency_id=$agency_id";
	}else{
		$agencyselect = " ";
	}
	$startdate = $_POST['startdate'];
	$enddate = $_POST['enddate'];

	$sqlbrands = "SELECT brand_agency.auto_id, brand_agency.brand_id, brand_table.brand_name, brand_agency.agency_id, 
	agency_company.agency_name, brand_agency.start_date, brand_agency.end_date 
	FROM brand_agency 
	INNER JOIN brand_table ON brand_table.brand_id=brand_agency.brand_id
	INNER JOIN agency_company ON agency_company.agency_id=brand_agency.agency_id
	WHERE brand_agency.agency_id != 0 $agencyselect
	AND (brand_agency.start_date>='$startdate' AND brand_agency.start_date<='$enddate' AND brand_agency.end_date<='$enddate')
	ORDER BY brand_agency.brand_id ASC,brand_agency.agency_id ASC,  brand_agency.start_date ASC";
	echo "<hr>";

	if($agencybrands = Yii::app()->db3->createCommand($sqlbrands)->queryAll()){
		$tabledata = "<table class='table table-condensed'> ";
		$tabledata.="<tr><td><strong>#</strong></td><td><strong>Agency</strong></td><td><strong>Brand</strong></td><td><strong>Start Date</strong></td><td><strong>End Date</strong></td><td><strong>Print Spend</strong></td><td><strong>TV Spend</strong></td><td><strong>Radio Spend</strong></td></tr>";
		$count = 1;
		foreach ($agencybrands as $brand_data):
			$brand_id = $brand_data['brand_id'];
			$agency_id = $brand_data['agency_id'];
			$agency_name = $brand_data['agency_name'];
			$brand_name = $brand_data['brand_name'];
			$agencystartdate = $brand_data['start_date'];
			$agencyenddate = $brand_data['end_date'];
			// Spends Data, set to 0
			$tvspend = 0;
			$radiospend = 0;
			$printspend = 0;
			// Electronic Spends
			$brandspends_electronic_sql = "SELECT sum(rate) AS rate, station.station_type 
			FROM brand_summaries 
			INNER JOIN station ON station.station_id=brand_summaries.station_id
			WHERE brand_summaries.brand_id = $brand_id AND brand_summaries.station_type='e' 
			AND (recorddate between '$startdate' AND '$enddate')
			GROUP BY station.station_type;";
			if($brandspends = Yii::app()->db3->createCommand($brandspends_electronic_sql)->queryAll()){
				foreach($brandspends as $brandvalues):
					if($brandvalues['station_type']=='TV'){
						$tvspend = $brandvalues['rate'];
					}
					if($brandvalues['station_type']=='radio'){
						$radiospend = $brandvalues['rate'];
					}
				endforeach;
			}
			// Print Spends
			$brandspends_print_sql = "SELECT sum(rate) AS rate 
			FROM brand_summaries 
			WHERE brand_summaries.brand_id = $brand_id AND brand_summaries.station_type='p' 
			AND (recorddate between '$startdate' AND '$enddate');";
			if($brandspends = Yii::app()->db3->createCommand($brandspends_print_sql)->queryRow()){
				$printspend = $brandspends['rate'];
			}
			$tvspend = number_format($tvspend);
			$radiospend = number_format($radiospend);
			$printspend = number_format($printspend);

			$excelarray[] = array('agency_name'=>$agency_name,'brand_name'=>$brand_name,'agencystartdate'=>$agencystartdate,'agencyenddate'=>$agencyenddate,'printspend'=>$printspend,'tvspend'=>$tvspend,'radiospend'=>$radiospend);
			$tabledata.="<tr><td>$count</td><td>$agency_name</td><td>$brand_name</td><td>$agencystartdate</td><td>$agencyenddate</td><td>$printspend</td><td>$tvspend</td><td>$radiospend</td></tr>";
			$count++;
		endforeach;
		$tabledata.= "</table>";
	}else{
		$tabledata = "<p>No Agency Brand Data Found</p>";
	}

	/* 
	** PDF Time
	*/
	$anvil_header = Yii::app()->request->baseUrl . '/images/anvil_header.jpg';
	$pdf_header = '<img src="'.$anvil_header.'" width="100%" /><br>';
	$pdf_header.= "<h2>Agency Ranking Report</h2>";
	$pdf_header.="<p>From $startdate to $enddate</p>";
	$pdf_file = $pdf_header;
	$pdf_file = $pdf_file.$tabledata;

	$pdf = Yii::app()->ePdf2->WriteOutput($pdf_file,array());
	$filename="Agency_Ranking_Report_"  .date('dmY');
	$filename=str_replace(" ","_",$filename);
	$filename=preg_replace('/[^\w\d_ -]/si', '', $filename);
	$filename_pdf=$filename.'.pdf';
	$location = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/pdf/".$filename_pdf;

	if(file_put_contents($location, $pdf)){
		$file = Yii::app()->request->baseUrl . '/docs/misc/pdf/'.$filename_pdf;
	    $pdfpackage = "<a href='$file' class='btn btn-danger btn-xs' target='_blank'><i class='fa fa-file-pdf-o'></i> Download PDF</a>";
	}else{
	    $pdfpackage = "";
	}
	/*
	** Excel Time
	*/
	$excelfile = AgencyRanking::CreateExcel($excelarray,$startdate,$enddate);
	$file = Yii::app()->request->baseUrl . '/docs/misc/excel/'.$excelfile;
	$excelpackage = "<a href='$file' class='btn btn-success btn-xs' target='_blank'><i class='fa fa-file-excel-o'></i> Download Excel</a>";
	$html = "<p>".$pdfpackage."&nbsp;".$excelpackage."&nbsp;</p>".$tabledata;
	echo $html;

	// echo $tabledata;

	
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

<script>
$('#startdate,#enddate').datepick({dateFormat: "yyyy-mm-dd"}); 
  </script>