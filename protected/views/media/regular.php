<?php
/**
* Print File
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
$this->breadcrumbs=array('Proof of Print'=>array('media/regular'));
?>
<?php

/* For the company id */
$coid = Yii::app()->user->company_id;
/* Userlevel */
$level =Yii::app()->user->company_level;
/* Country Code */
$this_country_code = 'KE';
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
	<header role="heading"><h2>Proof of Flight Reports for Print Media</h2></header>
	<div role="content">
		<div class="widget-body no-padding">
			<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'electronic','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('name'=>'myform', 'class'=>'smart-form'))); ?>
			<fieldset>
				<input type="hidden" name="country" id="country" value="KE" />
				<!-- <div class="form-group">
					<?php //echo $form->dropDownListRow($model, 'country', Country::CompanyCountry($coid), array('class'=>'form-control', 'name'=>'country','onchange'=>"window.location.href=this.options[this.selectedIndex].value")); ?>
					<?php echo $form->dropDownListRow($model, 'country', Country::AllCompanies(), array('class'=>'form-control', 'name'=>'country','onchange'=>"window.location.href='?country='+this.options[this.selectedIndex].value")); ?>
				</div>
				<br> -->
				<!-- Advert Type  -->
				<div class="brand_section">
					<header role="heading"><strong>Advert Type</strong> </header>
					<label class="checkbox">
						<label class="checkbox"><input name="entry_type" type="radio" value="0" checked="checked" /><label>All</label></label>
						<label class="checkbox"><input name="entry_type" type="radio" value="1" /><label>Ads Only</label></label>
						<label class="checkbox"><input name="entry_type" type="radio" value="2" /><label>Notices Only</label></label>
					</label>
					
				</div>
				<br>
				<!-- Brands -->
				<div class="brand_section">
					<header role="heading"><strong>Brand(s)</strong> <a onClick="select_all('brands', '1');">Select All Brands</a> | <a  onClick="select_all('brands', '0');">Unselect All Brands</a></header>
					<div class="no-wrap">
						<label class="checkbox">
						<?php echo $form->checkBoxList($model,'brands', BrandTable::CompanyBrands($coid), array('name'=>'brands')); ?>
						</label>
						<div class="section_clear"></div>
					</div>
				</div>
				<br>

				<!-- Print - Dailies -->

				<div class="dailies_section">
					<header role="heading"><strong>Print Media</strong> <br><a onClick="select_all('print', '1');">Select All Papers</a> | <a  onClick="select_all('print', '0');">Unselect All Papers</a><br>Daily Newspaper(s) </header>
					<div class="daily-wrap">
						<label class="checkbox">
						<?php echo $form->checkBoxList($model,'print', AnvilMediahouse::PrintMediaDaily(), array('name'=>'print')); ?>
						</label>
						<div class="section_clear"></div>
					</div>
					<br>

					<header role="heading">Weekly Newspaper(s) </header>

					<div class="no-wrap">
						<label class="checkbox">
						<?php echo $form->checkBoxList($model,'print', AnvilMediahouse::PrintMediaWeekly(), array('name'=>'print2')); ?>
						</label>
						<div class="section_clear"></div>
					</div>
					<br>

					<header role="heading">Monthly Magazine(s) </header>

					<div class="no-wrap">
						<label class="checkbox">
						<?php echo $form->checkBoxList($model,'print', AnvilMediahouse::PrintMediaMonthly(), array('name'=>'print3')); ?>
						</label>
						<div class="section_clear"></div>
					</div>
					<br>

					<header role="heading">Quarterly Magazine(s) </header>

					<div class="daily-wrap">
						<label class="checkbox">
						<?php echo $form->checkBoxList($model,'print', AnvilMediahouse::PrintMediaQuarterly(), array('name'=>'print4')); ?>
						</label>
						<div class="section_clear"></div>
					</div>
					<br>

					<header role="heading">Bi Monthly Magazine(s) </header>

					<div class="daily-wrap">
						<label class="checkbox">
						<?php echo $form->checkBoxList($model,'print', AnvilMediahouse::PrintMediaBiMonthly(), array('name'=>'print5')); ?>
						</label>
						<div class="section_clear"></div>
					</div>
					<br>

					<header role="heading">Weekend Newspaper(s) </header>

					<div class="daily-wrap">
						<label class="checkbox">
						<?php echo $form->checkBoxList($model,'print', AnvilMediahouse::PrintMediaWeekend(), array('name'=>'print6')); ?>
						</label>
						<div class="section_clear"></div>
					</div>
					<br>


				</div>
				<br>
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
			<footer> <?php echo CHtml::submitButton('Generate', array('class'=>'btn btn-primary')); ?> </footer>
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
</script>


<style type="text/css">
.brand_section header,.station_section header,.dailies_section header{
	margin: 0px 0px !important;
	font-size: 13px !important;
}
.section_clear{
	clear: both;
	padding: 5px 5px;
}
.brand_section,.station_section,.dailies_section{
	clear: both;
}
.no-wrap{
	height: 200px;
	width: 100%;
	overflow: auto;
	color: #333;
}
.daily-wrap{
	height: 70px;
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
.smart-form .dailies_section .checkbox .checkbox {
    padding-left: 20px;
    text-decoration: none;
    float: left;
    width: 20%;
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
	width: 32.33%;
	padding: 0px 10px 0px 0px;
}
fieldset .col-md-3{
	width: 22.33%;
	padding: 0px 10px 0px 0px;
}
</style>

<script>
  $('#startdate,#enddate').datepick();
  </script>

