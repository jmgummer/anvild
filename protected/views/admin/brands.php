<?php
$this->pageTitle=Yii::app()->name.' | Agencies';
$this->breadcrumbs=array('Agency Management'=>array('admin/agencies'));
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.css'; ?>"> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.plugin.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.js'; ?>"></script>
<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
	<header role="heading"><h2>Update Brand</h2></header>
	<div role="content">
		<div class="widget-body no-padding">
		<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'login-form','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'smart-form'))); ?>
		<?php echo $form->errorSummary($model); ?>
		<fieldset>
			<label class="input">
				<?php echo $form->textFieldRow($model,'start_date',array('required'=>'required', 'class'=>'input-xs','id'=>'startdate')); ?>
			</label>
			<label class="input">
				<?php echo $form->textFieldRow($model,'end_date',array('required'=>'required', 'class'=>'input-xs','id'=>'enddate')); ?>
			</label>
		</fieldset>
		<footer>
		<?php echo CHtml::submitButton('Save', array('class'=>'btn btn-success')); ?>
		</footer>
		<?php $this->endWidget(); ?>
		</div>
	</div>
</div>

<script>
$('#startdate,#enddate').datepick();
  </script>