<?php
$this->pageTitle=Yii::app()->name.' | Station Spends';
$this->breadcrumbs=array('Station Spends'=>array('ranking/stationsummary'));
$coid = Yii::app()->user->company_id;
?>
<div id="imageloadstatus" style="display:none"><div class="alert in fade alert-warning"><a class="close" data-dismiss="alert">×</a><strong>Loading ... </strong></div></div>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.css'; ?>"> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.plugin.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.js'; ?>"></script>
<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
	<header role="heading"><h2>Station Spends - Please Set a Date Range & Select Media Type</h2></header>
	<div role="content">
		<div class="widget-body no-padding crypton">
			<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'electronic','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('name'=>'myform', 'class'=>'smart-form'))); ?>
			<fieldset>
				<div class="clearfix">
					<div class="col-md-4">
						<div class="qc_section">
							<header role="heading"><strong>Media Type</strong> </header>
							<?php echo $form->dropDownList($model, 'reportformat', array('0'=>'All','radio'=>'Radio','tv'=>'TV','print'=>'Print'), array('class'=>'form-control', 'name'=>'reportformat','id'=>'reportformat')); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="qc_section">
							<header role="heading"><strong>Start Date</strong> </header>
							<?php echo $form->textField($model,'startdate',array('class'=>'form-control','name'=>'startdate','id'=>'startdate','required'=>'required')); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="qc_section">
							<header role="heading"><strong>End Date</strong> </header>
							<?php echo $form->textField($model,'enddate',array('class'=>'form-control','name'=>'enddate','id'=>'enddate','required'=>'required')); ?>
						</div>
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
<br>
<div class="">
	<?php
	$currency = "KES";
	$error_message = '';
	if(isset($_POST['startdate']) && isset($_POST['enddate'])){
		$startdate 	= $_POST['startdate'];
		$enddate 	= $_POST['enddate'];
		$reportformat = $_POST['reportformat'];
	}
	if(isset($_POST['startdate']) && $error_message==''){
		$temptable = StationSummarySpends::CreateTempTable();
		if($temptable){
			// Add the Data
			$stdata = StationSummarySpends::GetStationData($startdate,$enddate,$temptable);
			// Get the Processed Data
			$printdata = StationSummarySpends::PrintData($temptable,$reportformat);
			if($printdata){
				echo "<p><strong>We found data for the following stations</strong></p>";
				$excel = StationSummarySpends::GenerateExcel($startdate,$enddate,$printdata,$currency);
				echo '<p><strong><a href="'.Yii::app()->request->baseUrl . '/docs/misc/excel/'.$excel.'"><i class="fa fa-file-excel-o"></i> Download Excel File</a></strong></p>';
				echo '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
				echo '<thead><th>#</th><th>Station</th><th>Rate ('.$currency.')</th></thead>';
				$count = 1;
				foreach ($printdata as $key) {
					$stationname = $key['station_name'];
					$stationrate = number_format((float)$key['rate']);
					echo "<tr><td>$count</td><td>$stationname</td><td>$stationrate</td></tr>";
					$count++;
				}
				echo "</table>";
			}
		}else{
			echo "<p><strong>No Data</strong></p>";
		}

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