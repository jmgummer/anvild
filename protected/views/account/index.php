<?php
/**
* Accounts Index View File
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
$this->breadcrumbs=array('User Account'=>array('account/index'), 'User Details');
?>
<div class="row-fluid clearfix">
<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
	<header role="heading"><h2>Update User Details</h2></header>
	<div role="content">

		<?php if(Yii::app()->user->usertype=='client'){ ?>
		<div class="widget-body no-padding">
		<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'login-form','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'smart-form'))); ?>
		<?php echo $form->errorSummary($model); ?>
		<fieldset>
			<label class="input">
				<?php echo $form->textFieldRow($model,'surname',array('size'=>60,'maxlength'=>60, 'class'=>'input-xs')); ?>
			</label>
			<label class="input">
				<?php echo $form->textFieldRow($model,'firstname',array('size'=>60,'maxlength'=>60, 'class'=>'input-xs')); ?>
			</label>
			<label class="input">
				<?php echo $form->textFieldRow($model,'email',array('size'=>60,'maxlength'=>60, 'class'=>'input-xs')); ?>
			</label>
		</fieldset>

		<footer>
		<?php echo CHtml::submitButton('Update', array('class'=>'btn btn-success')); ?>
		<a href="<?=Yii::app()->createUrl("account/password");?>" class="btn btn-warning">Change Password ?</a>
		</footer>
		<?php $this->endWidget(); ?>
		</div>
		<?php } ?>

		<?php if(Yii::app()->user->usertype=='agency'){ ?>
		<div class="widget-body no-padding">
		<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'login-form','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'smart-form'))); ?>
		<?php echo $form->errorSummary($model); ?>
		<fieldset>
			<label class="input">
				<?php echo $form->textFieldRow($model,'username',array('size'=>60,'maxlength'=>60, 'class'=>'input-xs')); ?>
			</label>
			<label class="input">
				<?php echo $form->textFieldRow($model,'email',array('size'=>60,'maxlength'=>60, 'class'=>'input-xs')); ?>
			</label>
		</fieldset>

		<footer>
		<?php echo CHtml::submitButton('Update', array('class'=>'btn btn-success')); ?>
		<a href="<?=Yii::app()->createUrl("account/password");?>" class="btn btn-warning">Change Password ?</a>
		</footer>
		<?php $this->endWidget(); ?>
		</div>
		<?php } ?>

		<?php if(Yii::app()->user->usertype=='adflite'){ ?>
		<div class="widget-body no-padding">
		<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'login-form','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'smart-form'))); ?>
		<?php echo $form->errorSummary($model); ?>
		<fieldset>
			<label class="input">
				<?php echo $form->textFieldRow($model,'surname',array('size'=>60,'maxlength'=>60, 'class'=>'input-xs')); ?>
			</label>
			<label class="input">
				<?php echo $form->textFieldRow($model,'firstname',array('size'=>60,'maxlength'=>60, 'class'=>'input-xs')); ?>
			</label>
			<label class="input">
				<?php echo $form->textFieldRow($model,'email',array('size'=>60,'maxlength'=>60, 'class'=>'input-xs')); ?>
			</label>
		</fieldset>

		<footer>
		<?php echo CHtml::submitButton('Update', array('class'=>'btn btn-success')); ?>
		<a href="<?=Yii::app()->createUrl("account/password");?>" class="btn btn-warning">Change Password ?</a>
		</footer>
		<?php $this->endWidget(); ?>
		</div>
		<?php } ?>
	</div>
</div>
</div>