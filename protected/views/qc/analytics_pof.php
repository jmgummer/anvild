<?php
/**
* Brand Page File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/
$this->pageTitle=Yii::app()->name.' | Analytics POF';
$this->breadcrumbs=array(' Analytics POF (Company/Brand Report)'=>array('media/competitorpof'));

/* For the company id */
$coid = Yii::app()->user->company_id;
/* Userlevel */
$level =Yii::app()->user->company_level;
/* Country Code */
$this_country_code = Yii::app()->params['country_code'];

?>
<div id="imageloadstatus" style="display:none"><div class="alert in fade alert-warning"><a class="close" data-dismiss="alert">×</a><strong>Loading ... </strong></div></div>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.css'; ?>"> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.plugin.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.js'; ?>"></script>
<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
	<header role="heading"><h2>Analytics POF (Company/Brand Report)</h2></header>
	<div role="content">
		<div class="widget-body no-padding">
		<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'electronic','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('name'=>'myform', 'class'=>'smart-form'))); ?>
		
		<div class="row-fluid clearfix">
			<div class="col-md-4">
				<?php 
				echo $form->dropDownListRow($model, 'company', StorySearch::AgencyCompanies($coid), array(
				'empty'=>'--Please Select Company--','class'=>'form-control','ajax'=>array('type'=>'POST',
				'data'=>array('competitor_company'=>'js:this.value'),'url'=>CController::createURL('competitor/getdata'),'update'=>'#thisChannel_idField'),
				'id'=>'company','name'=>'competitor_company','onchange'=>"loading();",'required'=>'required'
				)
				); 
				?>
				<br>
				<?php 
				echo $form->dropDownListRow($model, 'industry', array(), array('empty'=>'-- All Industries --','class'=>'form-control',
				'ajax'=>array('type'=>'POST','data'=>array('agency_industry'=>'js:this.value','agency_company'=>'js:company.value','agency_id'=>$coid),
				'url'=>CController::createURL('getdata'),'update'=>'#brands'),
				'id'=>'thisChannel_idField','name'=>'industry','onchange'=>"loading();",'required'=>'required')
				); 
				?>
			</div>
			<div class="col-md-8 clearfix">
				<p><strong>Brands <a onClick="select_all('brands', '1');">Select All Brands</a> | <a  onClick="select_all('brands', '0');">Unselect All Brands</a></strong></p>
				<div id='brands' class='load-box no-wrap clearfix' ></div>
			</div>
		</div>

		<div class="row-fluid clearfix">
			<p><strong>TV Station(s) <a onClick="select_all('tvstation', '1');">Check All TV Stations</a> | <a  onClick="select_all('tvstation', '0');">Uncheck All TV Stations</a></strong></p>
			<div class="clearfix ppes"><?php echo $form->checkBoxList($model,'tv_station', Station::CountryTV($this_country_code), array('name'=>'tvstation')); ?></div>
			<p><strong>Radio Station(s) <a onClick="select_all('radiostation', '1');">Check All Radio Stations</a> | <a  onClick="select_all('radiostation', '0');">Uncheck All Radio Stations</a></strong></p>
			<div class="clearfix ppes"><?php echo $form->checkBoxList($model,'radio_station', Station::CountryRadio($this_country_code), array('name'=>'radiostation')); ?></div>
			<p><strong>Ad Type(s) <a onClick="select_all('adtype', '1');">Check All Ad Types</a> | <a  onClick="select_all('adtype', '0');">Uncheck All Ad Types</a></strong></p>
			<div class="clearfix ppes"><?php echo $form->checkBoxList($model,'adtype', DjmentionsEntryTypes::EntryTypes(),array('name'=>'adtype')); ?></div>
		</div>
		
		<div class="row-fluid clearfix">
			<div class="col-md-4"><?php echo $form->dropDownListRow($model, 'country', Country::AllCountries(), array('class'=>'form-control', 'name'=>'country')); ?></div>
			<div class="col-md-4"><?php echo $form->textFieldRow($model,'startdate',array('class'=>'form-control','name'=>'startdate','required'=>'required')); ?></div>
			<div class="col-md-4"><?php echo $form->textFieldRow($model,'enddate',array('class'=>'form-control','name'=>'enddate','required'=>'required')); ?></div>
		</div>
		<footer>
		<?php echo CHtml::submitButton('Generate', array('class'=>'btn btn-primary')); ?>
		</footer>
		<?php $this->endWidget(); ?>
		</div>
	</div>
</div>
<br>
<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
<header role="heading"><h2>Analytics POF Results</h2></header>
<div role="content">
	<?php
	if(isset($_POST['country']) && $_POST['country']!=''){ $country_id = $_POST['country']; }else{ $country_id = 1; }
	if(isset($_POST['country'])){
		$enddate = $_POST['enddate'];
		$startdate = $_POST['startdate'];
		// $industry = $_POST['industry'];
		// $subindustries = $_POST['subindustry'];
		/* Process the brands */
		$brands = array();
		if(isset($_POST['brands']) && !empty($_POST['brands'])){
		    foreach ($_POST['brands'] as $key) {
		      $brands[] = $key;
		    }
		    $set_brands = implode(', ', $brands);
		    $brand_query = 'brand_id IN ('.$set_brands.')';
		}else{
		    $error_code = 2;
		}
		/* Process the Ad Types */
		$adtypes = array();

		if(isset($_POST['adtype']) && !empty($_POST['adtype'])){
		    $group_ad_types = $_POST['adtype'];
		    foreach ($_POST['adtype'] as $key) {
		      $adtypes[] = $key;
		    }
		    $set_adtypes = implode(', ', $adtypes);
		    $ad_query = 'djmentions_entry_types.entry_type_id IN ('.$set_adtypes.')';
		}else{
		    $error_code = 1;
		}

		/* 
		** Handle Both TV Stations and Radio Stations
		*/
		$tvstation = array();

		if(isset($_POST['tvstation']) && !empty($_POST['tvstation'])){
		    foreach ($_POST['tvstation'] as $key) {
		      $tvstation[] = $key;
		    }
		    $set_tvstation = implode(', ', $tvstation);
		    $station_query = 'station_id IN ('.$set_tvstation.')';
		}

		$radiostation = array();

		if(isset($_POST['radiostation']) && !empty($_POST['radiostation'])){
		    foreach ($_POST['radiostation'] as $key) {
		      $radiostation[] = $key;
		    }
		    $set_radiostation = implode(', ', $radiostation);
		    if(isset($station_query)){
		        $station_query = 'station_id IN ('.$set_tvstation.', '.$set_radiostation.')';
		    }else{
		        $station_query = 'station_id IN ('.$set_radiostation.')';
		    }
		}

		if(!isset($station_query)){
		    $error_code = 3;
		}

		/* If there are any errors terminate execution at this point and redirect back to the form */
		if(isset($error_code)){
		    Yii::app()->user->setFlash('warning', "<strong>Error ! Please select at least one from each section </strong>");
		    $this->redirect(array('electronic'));
		}else{
			$ads = AnalyticsPOF::CompetitorReport($startdate,$enddate,$brand_query,$country_id,$station_query,$ad_query);
			echo '<p><strong><a href="'.Yii::app()->request->baseUrl . '/docs/competitor/excel/'.$ads.'"><i class="fa fa-file-excel-o"></i> Download Excel File</a></strong></p>';
		}
	}
	
	?>
</div>
</div>

<style type="text/css">
.smart-form .checkbox input {
    position: absolute;
    left: 0px !important;
}
.radio input[type="radio"], .radio-inline input[type="radio"], .checkbox input[type="checkbox"], .checkbox-inline input[type="checkbox"] {
    float: left;
    margin-left: 0px !important;
}
.row-fluid{
	padding: 5px !important;
}
.col-md-4{
	width: 32% !important;
	padding-right: 12px;
}
.no-wrap{
	height: 200px;
	/*width: 100%;*/
	overflow: auto;
	color: #333;
}
#brands .checkbox{
	width: 45%;
	float: left;
}
.ppes .checkbox{
	width: 20%;
	float: left;
}
</style>
<script type="text/javascript"><!--
var formblock;
var forminputs;
function prepare() {
	formblock= document.getElementById('electronic');
	forminputs = formblock.getElementsByTagName('input');
}
function select_all(name, value) {
	for (i = 0; i < forminputs.length; i++) {
		var regex = new RegExp(name, "i");
		if (regex.test(forminputs[i].getAttribute('name'))){
			if (value == '1') {
				forminputs[i].checked = true;
			} else {
				forminputs[i].checked = false;
			}
		}
	}
}
if (window.addEventListener) {
	window.addEventListener("load", prepare, false);
} else if (window.attachEvent) {
	window.attachEvent("onload", prepare)
} else if (document.getElementById) {
	window.onload = prepare;
}
$('#startdate,#enddate').datepick();
	function loading(){
	$("#imageloadstatus").show();
}
function checkelement() {
	var idset = document.getElementById('thisChannel_idField');
	var id = 'adtype_' + idset;
	alert(id);
}
</script>