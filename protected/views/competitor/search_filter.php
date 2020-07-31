<?php 
/**
* Search Filter File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/
?>
<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
	<header role="heading"><h2>Search Form</h2></header>
	<div role="content">
		<div class="widget-body no-padding">
		<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'login-form','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'smart-form'))); ?>
		<?php echo $form->errorSummary($model); ?>
		<fieldset>
			<label class="input">
				<?php echo $form->textFieldRow($model,'search_text',array('size'=>60,'maxlength'=>60, 'class'=>'input-xs')); ?>
			</label>
			<hr class="simple"></hr>
		    <div class="form-group">
				<?php echo $form->dropDownListRow($model, 'publications', StorySearch::getPrintList('mp01'), array('prompt'=>'All','class'=>'form-control')); ?>
			</div>
			<hr class="simple"></hr>
			<label class="input">
				<?php echo $form->textFieldRow($model,'startdate',array('size'=>60,'maxlength'=>60, 'class'=>'input-xs')); ?>
			</label>
			<hr class="simple"></hr>
			<label class="input">
				<?php echo $form->textFieldRow($model,'enddate',array('size'=>60,'maxlength'=>60, 'class'=>'input-xs')); ?>
			</label>
		</fieldset>
		<footer>
		<?php echo CHtml::submitButton('Generate', array('class'=>'btn btn-primary')); ?>
		</footer>
		<?php $this->endWidget(); ?>




		</div>
	</div>
</div>
<style type="text/css">
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
<script type="text/javascript">
/* Jquery UI Datepicker
    /*====================================================================*/
    $(function () {
        // Date
        $('input[name="StorySearch[start_date]"]').datepicker();
    });
</script>
<script>
  $(function() {
    $( "#StorySearch_startdate" ).datepicker();
    $( "#StorySearch_enddate" ).datepicker();
  });
  </script>