<?php
/**
* Brand Page File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/
$this->pageTitle=Yii::app()->name.' | Analytics POF';
$this->breadcrumbs=array(' Analytics POF (Company/Brand Report)'=>array('media/competitorpof'));

/* For the company id */
$coid = Yii::app()->user->company_id;
/* Userlevel */
$level =Yii::app()->user->company_level;
/* Country Code */
$this_country_code = Yii::app()->params['country_code'];

?>
<div id="imageloadstatus" style="display:none"><div class="alert in fade alert-warning"><a class="close" data-dismiss="alert">×</a><strong>Loading ... </strong></div></div>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.css'; ?>"> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.plugin.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/js/datepick/jquery.datepick.js'; ?>"></script>
<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
	<header role="heading"><h2>Compliance</h2></header>
	<div role="content">
		<div class="widget-body no-padding">
		<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'electronic','type'=>'smart-form','enableClientValidation'=>true, 'clientOptions'=>array('validateOnSubmit'=>true), 'htmlOptions'=>array('name'=>'myform', 'class'=>'smart-form'))); ?>
		
		<div class="row-fluid clearfix">
			<div class="col-md-4">
				<?php 
				echo $form->dropDownListRow($model, 'company', StorySearch::AgencyCompanies($coid), array('empty'=>'--Please Select Company--','class'=>'form-control','required'=>'required'
				)
				); 
				?>
				<br>
				<?php 
				?>
			</div>
			<div class="col-md-4"><?php echo $form->textFieldRow($model,'startdate',array('class'=>'form-control','autocomplete'=>"off",'required'=>'required', 'id'=>'startdate')); ?></div>
			<div class="col-md-4"><?php echo $form->textFieldRow($model,'enddate',array('class'=>'form-control','autocomplete'=>"off",'required'=>'required', 'id'=>'enddate')); ?></div>
		</div>
		
		<div class="row-fluid clearfix">
			
		</div>
		<footer>
		<?php echo CHtml::submitButton('Generate', array('class'=>'btn btn-primary')); ?>
		</footer>
		<?php $this->endWidget(); ?>
		</div>
	</div>
</div>
<br>
<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
<header role="heading"><h2>Results</h2></header>
<div role="content">
	<?php
	// var_dump($_POST);
	if(isset($_POST['StorySearch'])){
		$enddate = $model->enddate;
		$startdate = $model->startdate;
		$company = $model->company;
		// var_dump($_POST);
		echo "<hr>";

		$html = file_get_contents("http://197.248.156.26/compliance_alert/compliance_api.php?startDate=$startdate&endDate=$enddate&company=$company");
		// echo $html;

		$phparray = json_decode($html, TRUE);
		// var_dump($phparray);
		// echo "<hr>";
		foreach ($phparray as $key => $value) {
			echo $brandname = $key;
			// var_dump($key);
			echo "<hr>";
			// var_dump($value);
			// echo "<hr>";
			echo "<table class='table table-condensed table-striped'>";
			echo "<tr>
			<td>Station</td>
			<td>Expected Auto Ads</td>
			<td>Exected Manual Ads</td>
			<td>Actual Auto Runs</td>
			<td>Actual Manual Runs</td>
			<td>ratio_auto</td>
			<td>ratio_manual</td>
			</tr>";
			foreach ($value as $station => $results) {
				echo "<tr><td>$station</td>";
				// echo "<hr>";
				// var_dump($results);
				foreach ($results as $keyvalue) {
					// var_dump($keyvalue);
					echo "<td>$keyvalue</td>";
				}
				echo "</tr>";
			}
			echo "</table>";
		}




		
	}
	
	?>
</div>
</div>

<style type="text/css">
.smart-form .checkbox input {
    position: absolute;
    left: 0px !important;
}
.radio input[type="radio"], .radio-inline input[type="radio"], .checkbox input[type="checkbox"], .checkbox-inline input[type="checkbox"] {
    float: left;
    margin-left: 0px !important;
}
.row-fluid{
	padding: 5px !important;
}
.col-md-4{
	width: 32% !important;
	padding-right: 12px;
}
.no-wrap{
	height: 200px;
	/*width: 100%;*/
	overflow: auto;
	color: #333;
}
#brands .checkbox{
	width: 45%;
	float: left;
}
.ppes .checkbox{
	width: 20%;
	float: left;
}
</style>
<script type="text/javascript"><!--
$('#startdate,#enddate').datepick({dateFormat: "yyyy-mm-dd"}); 
</script>