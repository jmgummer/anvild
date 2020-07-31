<?php
/**
* Company Airplay File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/
$this->pageTitle=Yii::app()->name.' | Total Airplay (Company)';
$this->breadcrumbs=array('Total Airplay (Company) Report'=>array('media/companyairplay'));

/* For the company id */
$coid = Yii::app()->user->company_id;
/* Userlevel */
$level =Yii::app()->user->company_level;
/* Country Code */
$this_country_code = 'KE';

?>
<div id="imageloadstatus" style="display:none"><div class="alert in fade alert-warning"><a class="close" data-dismiss="alert">×</a><strong>Loading ... </strong></div></div>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.css'; ?>"> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.plugin.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.js'; ?>"></script>
<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
	<header role="heading"><h2>Total Airplay (Company) Report</h2></header>
	<div role="content">
		<div class="widget-body no-padding">
		<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'electronic','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('name'=>'myform', 'class'=>'smart-form'))); ?>
		
		<fieldset>

			<div class="col-md-6">
				<div class="col-md-6">
					<div class="regular">
						<header role="heading">Select Media Type </header>
						<label class="radio">
							<?php
							echo $form->radioButtonList($model,'adtype',array('1'=>'Spot Ad Spends','2'=>'Overall Exposure'), array('name'=>'adtype','required'=>'required')); 
							?>
						</label>
					</div>
				</div>

				<div class="col-md-6">
					<div class="regular">
						<header role="heading">Subscribed Industries </header>
						<label class="checkbox"><?php
							echo $form->dropDownList($model,'industry',StorySearch::Industries($coid,$level), 
							array(
								'empty'=>'--Please Select An Industry--',
								'class'=>'form-control',
								'ajax'=>array(
									'type'=>'POST',
									'data'=>array('industry'=>'js:this.value'),
									'url'=>CController::createURL('getdata'),'update'=>'#show_subindustries',
									),
								'id'=>'thisChannel_idField',
								'name'=>'industry',
								'onchange'=>"loading();",
								'required'=>'required'
								)
							); 
							?>
						</label>
					</div>
				</div>

			</div>
			

			
			<div class="col-md-6">
				<div class="regular">
					<header role="heading">Sub Industries <a onClick="select_all('sub_industry_name', '1');">Select All Industries</a> | <a  onClick="select_all('sub_industry_name', '0');">Unselect All Industries</a></header>
					<label class="checkbox">
						<div id='show_subindustries' class='load-box' ></div>

					</label>
				</div>
			</div>

			<div class="section_clear"></div>
			
			<div class="col-md-4">
				<div class="regular">
					<header role="heading">Country </header>
					<div class="form-group">
				<?php echo $form->dropDownList($model, 'country', Country::CompanyCountryById($coid), array('class'=>'form-control', 'name'=>'country')); ?>
			</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="regular">
					<header role="heading">Beginning </header>
					<label class="input">
						<?php echo $form->textField($model,'startdate',array('size'=>60,'maxlength'=>60, 'name'=>'startdate','required'=>'required')); ?>
					</label>
				</div>
			</div>
			<div class="col-md-4">
				<div class="regular">
					<header role="heading">Ending </header>
					<label class="input">
						<?php echo $form->textField($model,'enddate',array('size'=>60,'maxlength'=>60, 'name'=>'enddate','required'=>'required')); ?>
					</label>
				</div>
			</div>
		</fieldset>
		<footer>
		<?php echo CHtml::submitButton('Generate', array('class'=>'btn btn-primary')); ?>
		</footer>
		<?php $this->endWidget(); ?>
		</div>
	</div>
</div>

<style type="text/css">
.regular{
	padding-right: 5px;
}
.regular header,.station_section header{
	margin: 5px 0px !important;
	font-size: 13px !important;
}
.section_clear{
	clear: both;
	padding: 5px 5px;
}
.regular,.station_section{
	clear: both;
}
.no-wrap{
	height: 200px;
	width: 100%;
	overflow: auto;
	color: #333;
}
.smart-form .regular .checkbox .checkbox {
    padding-left: 20px;
    text-decoration: none;
    float: left;
}

.station_section{
	clear: both;
}
.dates_section .input{
	width: 95%;
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
<script>
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