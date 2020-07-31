<?php
$this->pageTitle=Yii::app()->name.' | Agencies';
$this->breadcrumbs=array('Agency Management'=>array('admin/agencies'));
?>

<?php

/* For the company id */
$coid = Yii::app()->user->company_id;
/* Userlevel */
$level =Yii::app()->user->company_level;
/* Country Code if not set default to Kenya*/
if(isset($_GET['country'])){
	$this_country_code = $_GET['country'];
}else{
	$this_country_code = 'KE';
}
/* 
** COMPANY BRANDS **
--------------------
** Obtain the company brands, use the status later if it is called for
** Status is left open
*/

?>
<div id="imageloadstatus" style="display:none"><div class="alert in fade alert-warning"><a class="close" data-dismiss="alert">×</a><strong>Loading ... </strong></div></div>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.css'; ?>"> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.plugin.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.js'; ?>"></script>

<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
	<header role="heading"><h2>Agency Summaries</h2></header>
	<div role="content">
		<div class="widget-body no-padding">
		<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'electronic','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('name'=>'myform', 'class'=>'smart-form'))); ?>
		
		<fieldset>
			<div class="row">
				<div class="padded">
					<div class="col-md-6">
						<div class="form-group">
							<?php echo $form->dropDownListRow($model, 'company', StorySearch::AgencySelect(), 
							array(
								'empty'=>'-- All Agencies --',
											'class'=>'form-control',
											'ajax'=>array(
												'type'=>'POST',
												'data'=>array('agency_brand_company'=>'js:this.value'),
												'url'=>CController::createURL('getdata'),'update'=>'#brandsid',
												),
											'id'=>'company',
											'name'=>'company',
											'onchange'=>"loading();",
											'required'=>'required'
								)
							); 
							?>
						</div>
				
					</div>
					<div class="col-md-6">
						
					</div>
				</div>

			</div>
			<div class="brand_section">
				<header role="heading">Brand(s) </header>
				
					<label class="checkbox" id="brandsid">
					
					</label>
					<div class="section_clear"></div>
			</div>
			<br>
			
		</fieldset>
		<footer>
		<?php echo CHtml::submitButton('Generate', array('class'=>'btn btn-primary','id'=>'submitp')); ?>
		</footer>
		<?php $this->endWidget(); ?>
		</div>
	</div>
</div>

<script type="text/javascript"><!--
var formblock;
var forminputs;
function prepare() {
	formblock= document.getElementById('electronic');
	forminputs = formblock.getElementsByTagName('input');
}
function select_all(name, value) {
	for (i = 0; i < forminputs.length; i++) {
	// regex here to check name attribute
	var regex = new RegExp(name, "i");
	if (regex.test(forminputs[i].getAttribute('name'))) 
	{
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
//--></script>


<style type="text/css">
.padded{
	margin: 0px 0px !important;
	padding: 10px 15px !important;
}
.padded .col-md-6{
	width: 49%;
	padding-right: 5px;
}
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
.autoflow{
	height: auto;
	width: 100%;
	overflow: auto;
	color: #333;
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
    /*width: 30%;*/
    /*overflow: hidden;
white-space: nowrap;
text-overflow: ellipsis;*/
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

 
fieldset .col-md-3{
	width: 22.33%;
	padding: 0px 10px 0px 0px;
}
</style>

<script>
$('#startdate,#enddate').datepick();
function loading(){
		$("#imageloadstatus").show();
	}
  </script>