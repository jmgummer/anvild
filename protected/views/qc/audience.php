<?php
$this->pageTitle=Yii::app()->name.' | POF Log';
$this->breadcrumbs=array('POF Log'=>array('qc/stationlog'));
$coid = Yii::app()->user->company_id;
?>
<div id="imageloadstatus" style="display:none"><div class="alert in fade alert-warning"><a class="close" data-dismiss="alert">×</a><strong>Loading ... </strong></div></div>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.css'; ?>"> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.plugin.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.js'; ?>"></script>
<?php if(!isset($_POST['startdate']) && !isset($_POST['enddate'])){ ?>
<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
	<header role="heading"><h2>POF Log - Please Select a Station, Ad Type & Date</h2></header>
	<div role="content">
		<div class="widget-body no-padding crypton">
			<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'electronic','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('name'=>'myform', 'class'=>'smart-form'))); ?>
		
			<fieldset>
				<div class="clearfix">
					<div class="col-md-6">
						<div class="qc_section">
							<header role="heading"><strong>Report Format</strong> </header>
							<?php echo $form->dropDownList($model, 'reportformat', array('0'=>'Standard','1'=>'Group by Stations (Break Down by Brand)','2'=>'Group by Brands (Break Down by Station)'), array('class'=>'form-control', 'name'=>'reportformat','id'=>'reportformat')); ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="qc_section">
							<header role="heading"><strong>Company</strong> </header>
							<?php 
								echo $form->dropDownList($model, 'company', StorySearch::AgencyCompanies($coid), 
								array(
									'class'=>'form-control', 
									'name'=>'company',
									'id'=>'company',
									'ajax'=>array(
										'type'=>'POST',
										'data'=>array('qccompany'=>'js:this.value'),
										'url'=>CController::createURL('getdata'),'update'=>'#brandsid',
										'onchange'=>"loading();",
										),
									)
								); 
							?>
						</div>
					</div>
				</div>
				<div class="qc_section clearfix">
					<header role="heading"><strong>Brands &nbsp;<a onClick="select_all('brands', '1');">Check All Brands</a> | <a  onClick="select_all('brands', '0');">Uncheck All Brands</a></strong> </header>
					<div class="no-wrap" id="brandsid"></div>
				</div>


				<div class="qc_section clearfix">
					<header role="heading"><strong>Stations &nbsp;<a onClick="select_all('station', '1');">Check All Stations</a> | <a  onClick="select_all('station', '0');">Uncheck All Stations</a></strong> </header>
					<?php echo $form->checkBoxList($model,'station', Station::AdminStations(), array('name'=>'station','id'=>'station')); ?>
				</div>

				<div class="qc_section clearfix">
					<header role="heading"><strong>Ad Type &nbsp;<a onClick="select_all('adtype', '1');">Check All Ad Types</a> | <a  onClick="select_all('adtype', '0');">Uncheck All Ad Types</a></strong> </header>
					<?php echo $form->checkBoxList($model,'adtype', DjmentionsEntryTypes::EntryTypes(), array('name'=>'adtype','id'=>'adtype')); ?>
				</div>
				<div class="qc_section clearfix">
					<header role="heading"><strong>Report Date Range</strong> </header>
					<div class="col-md-6">
					<?php echo $form->textFieldRow($model,'startdate',array('class'=>'form-control','name'=>'startdate','id'=>'startdate','required'=>'required')); ?>
					</div>
					<div class="col-md-6">
					<?php echo $form->textFieldRow($model,'enddate',array('class'=>'form-control','name'=>'enddate','id'=>'enddate','required'=>'required')); ?>
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
<?php } ?>
<br>
<div class="">
	<?php
	$error_message = '';
	if(isset($_POST['startdate']) && isset($_POST['enddate'])){
		$startdate 	= $_POST['startdate'];
		$enddate 	= $_POST['enddate'];
		$reportformat = $_POST['reportformat'];

		if(isset($_POST['adtype']) && !empty($_POST['adtype'])){
			foreach ($_POST['adtype'] as $adkey) {
				$adtypes[] = $adkey;
			}
			$set_adtypes = implode(', ', $adtypes);
		}else{
			$error_message .= 'Please Select Ad Type(s)<br>';
		}

		if(isset($_POST['station']) && !empty($_POST['station'])){
			foreach ($_POST['station'] as $stationkey) {
				$stations[] = $stationkey;
			}
			$set_stations = implode(', ', $stations);
		}else{
			$error_message .= 'Please Select Station(s)<br>';
		}

		if(isset($_POST['brands']) && !empty($_POST['brands'])){
			foreach ($_POST['brands'] as $brandkey) {
				$brands[] = $brandkey;
			}
			$set_brands = implode(', ', $brands);
		}else{
			$error_message .= 'Please Select Brand(s)<br>';
		}
	}

	if(isset($_POST['startdate']) && $error_message==''){
		$logs = new AudiencePOF;
		$stationlog = $logs->GetLogs($set_stations,$set_brands,$set_adtypes,$startdate,$enddate,$reportformat);
	}else{
		echo $error_message;
	}

	
	?>
</div>
<style type="text/css">
.no-wrap{
	height: 200px;
	width: 100%;
	overflow: auto;
	color: #333;
}
.smart-form .checkbox input {
    position: relative;
    left: 0px;
    margin-top: 7px;
}
.qc_section header{
	font-size: 13px !important;
	margin: 5px 0px 10px 0px !important;
}

.qc_section .checkbox{
	width: 22% !important;
	float: left;
}
.qc_section .col-md-6{
	padding-right: 10px;
	width: 48%;
}
.crypton .col-md-6{
	padding-right: 10px;
	width: 48%;
}
	.crypton{}
	.crypton .col-md-4{
		padding-right: 10px;
		width: 32.3%;
	}
	.crypton fieldset{
		padding: 10px;
	}
	.fupisha { max-width: 250px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; }
.nav-tabs > li.active > a { box-shadow: 0px -2px 0px #DB4B39; }
#tabs { width: 100%; background-color: #CCC; }
.pdf-excel{margin: 5px 1px;}
#station_breakdown{
    padding: 5px 0px 10px;
    background-color: #f5f5f5;
    border-top: 1px solid #ddd;
}
#station_breakdown h3 {
  display: block;
  font-size: 16px;
  font-weight: 400;
  margin: 5px 0;
  line-height: normal;
}
#station_breakdown h4{
    display: block;
    font-size: 16px;
    font-weight: 400;
    margin: 5px 0px;
}
#station_brand {
    border-top: 1px dashed rgba(0,0,0,.2);
    border-bottom: 1px dashed rgba(0,0,0,.2);
}
.station_header .col-md-6 a{
    cursor: pointer;
    text-decoration: none;
    color: #FF5A14;
    font-weight: normal;
}
.blinky{
    cursor: pointer;
    text-decoration: none;
    color: #FF5A14;
    font-weight: normal;
}
</style>
<script>
$('#enddate').datepick({dateFormat: "yyyy-mm-dd"});
$('#startdate').datepick({dateFormat: "yyyy-mm-dd"}); 

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