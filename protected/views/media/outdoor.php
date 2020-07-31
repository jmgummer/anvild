<?php
/**
* Outdoor File
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
$this->breadcrumbs=array('Outdoor Channel Reports'=>array('media/outdoor'));

/* For the company id */
$coid = Yii::app()->user->company_id;
/* Userlevel */
$level =Yii::app()->user->company_level;
/* Country Code */
$this_country_code = 'KE';
/* 
** COMPANY BILLBOARD DETAILS **
--------------------
** Obtain the company billboard channel details
*/

$sql_site="select distinct(billboard_company.company_id) as company_id, billboard_company.company_name as company_name 
from  billboard_company, outdoor_channel_entries, brand_table 
where outdoor_channel_entries.company_id=billboard_company.company_id 
and outdoor_channel_entries.brand_id=brand_table.brand_id 
and brand_table.company_id='$coid' 
order by company_name";
?>
<div id="imageloadstatus" style="display:none"><div class="alert in fade alert-warning"><a class="close" data-dismiss="alert">×</a><strong>Loading ... </strong></div></div>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.css'; ?>"> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.plugin.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.js'; ?>"></script>
<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
	<header role="heading"><h2>Outdoor Channels Report</h2></header>
	<div role="content">
		<div class="widget-body no-padding">
		<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'electronic','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('name'=>'myform', 'class'=>'smart-form'))); ?>
		
		<fieldset>
			<div class="col-md-3">
				<div class="regular">
					<header role="heading">Outdoor Channel Company </header>
					<label class="checkbox"><?php
						echo $form->dropDownList($model,'channel',StorySearch::Channels($coid), 
						array(
							'empty'=>'--Please Select A Company--',
							'class'=>'form-control',
							'ajax'=>array(
								'type'=>'POST',
								'data'=>array('channel'=>'js:this.value'),
								'url'=>CController::createURL('getdata'),'update'=>'#thisBrand_idField',
								),
							'id'=>'thisChannel_idField',
							'name'=>'channel',
							'onchange'=>"loading();"
							)
						); 
						?>
					</label>
				</div>
			</div>

			<div class="col-md-3">
				<div class="regular">
					<header role="heading">Brand </header>
					<label class="checkbox"><select name='thisBrand_idField'   id='thisBrand_idField' class='form-control'  >
			        <option value=''>-Select-</option>
			        </select></label>
				</div>
			</div>
			<div class="col-md-3">
			<div class="regular">
				<header role="heading">Region </header>
				<label class="checkbox"><?php
						echo $form->dropDownList($model,'region',array('all'=>'--All--','2'=>'Central','9'=>'Coast','10'=>'Eastern','8'=>'Nairobi','11'=>'North Eastern','6'=>'Nyanza','7'=>'Rift Valley','5'=>'Western'), 
						array(
							'empty'=>'--Please Select A Region--',
							'class'=>'form-control',
							'ajax'=>array(
								'type'=>'POST',
								'data'=>array('region'=>'js:this.value','company_id'=>'js:thisChannel_idField.value'),
								'url'=>CController::createURL('getdata'),'update'=>'#thisSite_idField',
								),
							'name'=>'region',
							'onchange'=>"loading();"
							)
						); 
						?>
					</label>
			</div>
			</div>
			<div class="col-md-3">
				<div class="regular">
					<header role="heading">Site </header>
					<label class="checkbox"><select name='thisSite_idField'   id='thisSite_idField' class='form-control'   >
					<option value='0'>-All-</option>
					</select></label>
				</div>
			</div>
			<div class="dates_section">
				<label class="input">
					<?php echo $form->textFieldRow($model,'startdate',array('size'=>60,'maxlength'=>60, 'name'=>'startdate')); ?>
				</label>
				<label class="input">
					<?php echo $form->textFieldRow($model,'enddate',array('size'=>60,'maxlength'=>60, 'name'=>'enddate')); ?>
				</label>
				<div class="section_clear"></div>
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
.regular header,.station_section header{
	margin: 5px 0px !important;
	font-size: 13px !important;
}
.dates_section header {
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
    width: 30%;
    overflow: hidden;
white-space: nowrap;
text-overflow: ellipsis;
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
.MonthDatePicker .ui-datepicker-year
{
    display:none;   
}
.YearDatePicker .ui-datepicker-month
{
    display:none;   
}
.YearShowDatePicker .ui-datepicker-month
{
    display:inline; ;   
}
.HideTodayButton .ui-datepicker-buttonpane .ui-datepicker-current
{
    visibility:hidden;
}

.hide-calendar .ui-datepicker-calendar
{
	display:none!important;
	visibility:hidden!important
}
</style>

<script>
 $('#startdate,#enddate').datepick();
 	function loading(){
		$("#imageloadstatus").show();
	}

</script>