<?php
$this->pageTitle=Yii::app()->name.' | Station Log';
$this->breadcrumbs=array('Station Log'=>array('qc/stationlog'));
?>
<div id="imageloadstatus" style="display:none"><div class="alert in fade alert-warning"><a class="close" data-dismiss="alert">×</a><strong>Loading ... </strong></div></div>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.css'; ?>"> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.plugin.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.js'; ?>"></script>
<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
	<header role="heading"><h2>Station Log - Please Select a Station, Ad Type & Date</h2></header>
	<div role="content">
		<div class="widget-body no-padding crypton">
			<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'electronic','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('name'=>'myform', 'class'=>'smart-form'))); ?>
		
			<fieldset>
				<div class="col-md-4">
					<?php echo $form->dropDownListRow($model,'station', Station::AdminStations(), array('name'=>'station','class'=>'form-control','id'=>'station')); ?>
				</div>
				<div class="col-md-4">
					<?php echo $form->dropDownListRow($model, 'adtype', array('1'=>'Manuals & Spot Ads','2'=>'Manuals Only','3'=>'Spot Ads Only'), array('class'=>'form-control', 'name'=>'adtype','id'=>'adtype')); ?></div>
				<div class="col-md-4">
					<?php echo $form->textFieldRow($model,'addate',array('class'=>'form-control','name'=>'addate','id'=>'addate','required'=>'required')); ?>
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
	if(isset($_POST['addate'])){
		$logstation=$_POST['station'];
		$logadtype=$_POST['adtype'];
		$logaddate=$_POST['addate'];
		$logs = new StationLog;
		$stationlog = $logs->GetLogs($logstation,$logadtype,$logaddate);
		// var_dump($stationlog);
		$htmltable = $logs->HtmlLogs($stationlog);
		echo $htmltable;
	}
	?>
</div>
<style type="text/css">
	.crypton{}
	.crypton .col-md-4{
		padding-right: 10px;
		width: 32.3%;
	}
	.crypton fieldset{
		padding: 10px;
	}
</style>
<script>
  $('#addate').datepick({dateFormat: "yyyy-mm-dd"});
  </script>