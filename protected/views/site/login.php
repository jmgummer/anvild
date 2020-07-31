<?php
/**
* Index File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/

$this->pageTitle=Yii::app()->name . ' - Login';
?>

<div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
	<h1 class="txt-color-red login-header-big">Reelforge</h1>
	<div class="hero">

		<div class="pull-left login-desc-box-l">
			<h4 class="paragraph-header">Experience the simplicity of Reelforge, everywhere you go!</h4>
			<div class="login-app-icons">
				<a href="http://reelforge.com/reelforge/index.php" class="btn btn-danger btn-sm" target="_blank">Go to Site</a>
				<a href="http://reelforge.com/reelforge/index.php/insights" class="btn btn-danger btn-sm" target="_blank">Latest News</a>
			</div>
		</div>
		<img src="<?php echo Yii::app()->request->baseUrl . '/images/reelapp.png'; ?>" class="pull-right display-image" alt="" style="width:210px">
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<h5 class="about-heading">About Reelforge</h5>
			<p>
				Reelforge Media Monitoring is the region's leading and fastest growing media monitoring and intelligence company.
			</p>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<h5 class="about-heading">Contact Us!</h5>
			<p>
				5th Floor, Rhapta Heights, on Rhapta Rd.<br>
				Westlands, Nairobi <br>
				P. O. Box 8125 - 00200,<br> 
				Nairobi, Kenya <br><br>
				Phone:<br>
				+254 20 2372965/6<br>
				+254 731164469 
			</p>
		</div>
	</div>
</div>
<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
	<div class="well no-padding">
		<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'login-form','type'=>'horizontal','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('class'=>'smart-form client-form'))); ?>
			<header>
				Sign In
			</header>
			<fieldset>
				<section>
					<?php echo $form->labelEx($model,'username',array('class'=>'label')); ?>
					<label class="input"> <i class="icon-append fa fa-user"></i>
					<?php echo $form->textField($model,'username'); ?></label>
					<?php echo $form->error($model,'username'); ?>
				</section>

				<section>
					<?php echo $form->labelEx($model,'password',array('class'=>'label')); ?>
					<label class="input"> <i class="icon-append fa fa-lock"></i>
					<?php echo $form->passwordField($model,'password'); ?></label>
					<?php echo $form->error($model,'password'); ?>
				</section>

			</fieldset>
			<footer>
				<?php echo CHtml::submitButton('Login', array('class'=>'btn')); ?>
			</footer>
		<?php $this->endWidget(); ?>
	</div>
</div>