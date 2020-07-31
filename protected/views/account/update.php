<?php
/**
* Accounts Update File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/
$this->pageTitle=Yii::app()->name.' | Client Account';
$this->breadcrumbs=array('User Account'=>array('account/index'), 'Change Password');
?>
<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
	<header role="heading"><h2>Update Your Password</h2></header>
	<div role="content">
		<div class="widget-body no-padding">
		<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'login-form','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'smart-form'))); ?>
		<?php echo $form->errorSummary($model); ?>
		<fieldset>
			<label class="input">
				<?php echo $form->passwordFieldRow($model,'dummypass',array('required'=>'required', 'class'=>'input-xs','placeholder'=>'')); ?>
			</label>
			<label class="input">
				<?php echo $form->passwordFieldRow($model,'dummypass2',array('required'=>'required', 'class'=>'input-xs')); ?>
			</label>
			<label class="input">
				<?php echo $form->passwordFieldRow($model,'dummypass3',array('required'=>'required', 'class'=>'input-xs')); ?>
			</label>
		</fieldset>
		<footer>
		<?php echo CHtml::submitButton('Save', array('class'=>'btn btn-success')); ?>
		</footer>
		<?php $this->endWidget(); ?>
		</div>
	</div>
</div>