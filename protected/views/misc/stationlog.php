<?php
/**
* Station Log File
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
$this->pageTitle=Yii::app()->name.' | Station Log';
$this->breadcrumbs=array('Station Log'=>array('misc/stationlog'));
$agency_id=Yii::app()->user->company_id;
/* Country Code if not set default to Kenya*/
$this_country_code = Yii::app()->params['country_code'];
$country_id = Yii::app()->params['country_id'];

?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.css'; ?>"> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.plugin.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.js'; ?>"></script>

<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
    <header role="heading"><h2>Station Log</h2></header>
    <div role="content">
        <div class="widget-body no-padding">
        <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'stationlog','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('name'=>'myform', 'class'=>'smart-form'))); ?>
        
        <fieldset>
            <div class="col-md-3">
                <div class="station_section">
                    <div class="form-group">
                        <header role="heading">Country </header>
                        <br>
                        <?php //echo $form->dropDownListRow($model, 'country', Country::CompanyCountry($coid), array('class'=>'form-control', 'name'=>'country','onchange'=>"window.location.href=this.options[this.selectedIndex].value")); ?>
                        <?php echo $form->dropDownList($model, 'country', Country::AllCompanies(), array('class'=>'form-control', 'name'=>'country','onchange'=>"window.location.href='?country='+this.options[this.selectedIndex].value")); ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="station_section">
                    <header role="heading">Station </header>
                    <br>
                    <label class="checkbox">
                    <?php echo $form->dropDownList($model,'station', Station::StationList($level,$company_name=null,$country_id), array('class'=>'form-control', 'name'=>'station','id'=>'station')); ?>
                    </label>
                </div>
            </div>

            <div class="col-md-3">
                <div class="station_section">
                    <header role="heading">Date </header>
                    <br>
                    <label class="input">
                        <?php echo $form->textField($model,'logdate',array('size'=>60,'maxlength'=>60, 'class'=>'form-control input-xs', 'name'=>'logdate','id'=>'logdate','required'=>'required')); ?>
                    </label>
                    <div class="section_clear"></div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="station_section">
                    <header role="heading">Entry Type </header>
                    <br>
                    <label class="radio">
                        <?php echo $form->dropDownList($model,'entrytype', array('all'=>'Manual & Spot Ads','manual'=>'Manual Only (No Spot Ads)','auto'=>'Spot Ads Only'), array('id'=>'entrytype','class'=>'form-control')); ?>
                    </label>
                </div>
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
    <h3>Station Log</h3>
    <p>Reports Will Appear Here. </p>
</div>

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
    width: 32.33%;
    padding: 0px 10px 0px 0px;
}
fieldset .col-md-3{
    width: 22.33%;
    padding: 0px 10px 0px 0px;
}
</style>

<script>
$('#logdate').datepick();
$("#submitp").click(
function(event) 
{
event.preventDefault();
Generate();
}
);
function Generate()
{
    
    entrytype        = document.getElementById('entrytype').value;
    logdate          = document.getElementById('logdate').value;
    station       = document.getElementById('station').value;
    if(checkvalue(logdate)==false){
        alert('Please add Date value');
    }else{
        load            = document.getElementById("load-content");
        load.innerHTML  = "<div id='preLoaderDiv'><p id='preloaderAnimation' style='color:#fff;text-align:center;position: relative;top: 50%;margin-left:15px;'>Loading  </p> <img id='preloaderAnimation' src='<?php echo Yii::app()->request->baseUrl . "/images/loading.gif"; ?>' /></div>";
        $.ajax({
            url: '<?=Yii::app()->createUrl("misc/getlogs");?>',
            data: { 'entrytype': entrytype, 'logdate': logdate, 'station':station  },
            type: 'POST',
            cache: false,
            success: function(data){
                load.innerHTML = data;
            },
            error: function(){
                load.innerHTML = "<br><h4><span class='label label-info'>There was an error generating the report! Please Try Again Later</span></h4>";
            },
            complete: function() {
                // loadcomplete();
            }
        });
    }

    
}

function checkvalue(logdate) { 
    var mystring = logdate; 
    if(!mystring.match(/\S/)) {
        return false;
    } else {
        return true;
    }
}
</script>