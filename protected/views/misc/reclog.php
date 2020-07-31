<?php
/**
* Reconciliation Log File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version     v.1.0
* @since       July 2008
*/
$level = Yii::app()->user->company_level;
$company_name = Yii::app()->user->company_name;
$coid=$this_company_id = Yii::app()->user->company_id;
$this->pageTitle=Yii::app()->name.' | Reconciliation Log';
$this->breadcrumbs=array('Reconciliation Log'=>array('misc/reconciliationlog'));
$agency_id=Yii::app()->user->company_id;
/* Country Code if not set default to Kenya*/
$this_country_code = Yii::app()->params['country_code'];
$country_id = Yii::app()->params['country_id'];

?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.css'; ?>"> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.plugin.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.js'; ?>"></script>

<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
    <header role="heading"><h2>Reconciliation Log</h2></header>
    <div role="content">
        <div class="widget-body no-padding">
        <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'reclog','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('name'=>'myform', 'class'=>'smart-form'))); ?>
        
        <fieldset>
            <div class="station_section">
                <header role="heading">Client </header>
                <br>
                <div class="form-group">
					<?php 
                    echo $form->dropDownList($model, 'company', StorySearch::AdfliteCompanies($coid), 
					array(
						'empty'=>'--Please Select An Company--',
						'class'=>'form-control',
						'ajax'=>array(
							'type'=>'POST',
							'data'=>array('company'=>'js:this.value','recon'=>'set'),
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
            <br>
            <div class="station_section">
                <header role="heading">Brands <a onClick="select_all('brands', '1');">Check All Brands</a> | <a  onClick="select_all('brands', '0');">Uncheck All Brands</a></header>
                <br>
                <label class="checkbox" id="brandsid">
					
					</label>
					<div class="section_clear"></div>
            </div>
            
            <div class="station_section">
                <header role="heading">Station(s) <a onClick="select_all('station', '1');">Check All Stations</a> | <a  onClick="select_all('station', '0');">Uncheck All Stations</a></header>
                <br>
                <label class="checkbox" id="stationsid">
                <?php echo $form->checkBoxList($model,'station', StorySearch::AdfliteStations($coid), array('name'=>'station','id'=>'station')); ?>
                </label>
                <div class="section_clear"></div>
            </div>

            

            
            <br>
            <div class="station_section">
                    <header role="heading">Ad Type(s) <a onClick="select_all('adtype', '1');">Check All Ad Types</a> | <a  onClick="select_all('adtype', '0');">Uncheck All Ad Types</a></header>
				<div class="no-wrap">
					<label class="checkbox">
					<?php echo $form->checkBoxList($model,'adtype', DjmentionsEntryTypes::EntryTypes(),array('name'=>'adtype','id'=>'adtype')); ?>
					</label>
					<div class="section_clear"></div>
				</div>
            </div>
            <br>
			<div class="dates_section">
				<label class="input">
					<?php echo $form->textFieldRow($model,'startdate',array('size'=>60,'maxlength'=>60, 'name'=>'startdate','id'=>'startdate')); ?>
				</label>
				<label class="input">
					<?php echo $form->textFieldRow($model,'enddate',array('size'=>60,'maxlength'=>60, 'name'=>'enddate','id'=>'enddate')); ?>
				</label>
				<div class="section_clear"></div>
			</div>

        </fieldset>
        <footer>
        <?php echo CHtml::submitButton('Generate', array('class'=>'btn btn-primary','id'=>'submitp')); ?>
        </footer>
        <?php $this->endWidget(); ?>
        </div>
    </div>
</div>

<div class="archive-section" id="load-content">
    <h3>Reconciliation Log</h3>
    <p>Reports Will Appear Here. </p>
</div>

<style type="text/css">
.smart-form .checkbox input, .smart-form .radio input {
    position: relative;
    left: 0px;
    margin-left: 10px;
    margin-right: 5px;
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
    width: 32.33%;
    padding: 0px 10px 0px 0px;
}
fieldset .col-md-3{
    width: 22.33%;
    padding: 0px 10px 0px 0px;
}
</style>

<script>
function checkLoad()
{
   if(document.getElementById("bottom"))
   {
	document.getElementById("preLoaderDiv").style.visibility = "hidden";
   }
}
var formblock;
var forminputs;
function prepare() {
	formblock= document.getElementById('reclog');
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

$('#startdate,#enddate').datepick();

function loading(){
	$("#imageloadstatus").show();
}

</script>