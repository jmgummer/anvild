<?php

$level = Yii::app()->user->company_level;
$company_name = Yii::app()->user->company_name;
$coid=$this_company_id = Yii::app()->user->company_id;
$this->pageTitle=Yii::app()->name.' | Creating New Rate Change';
$this->breadcrumbs=array('New Rate Change'=>array('misc/newrates'));
$agency_id=Yii::app()->user->company_id;
/* For the company id */
$coid = Yii::app()->user->company_id;
/* Userlevel */
$level =Yii::app()->user->company_level;
/* Country Code if not set default to Kenya*/
$this_country_code = Yii::app()->params['country_code'];

?>


<div class="supercenter">
	<p>The Invoice was successfully created, Click to Download Your File</p>
	<br>
	<i class="fa fa-file-pdf-o fa-4x"></i>
	<div class="media-content">
		<p><?php echo $date; ?></p>
		<p><a href="<?=$file;?>" class="btn btn-primary" >Download</a></p>
	</div>
</div>
<style type="text/css">
.supercenter{
	font-weight: bold;
	font-size: 14px;
	text-align: center;
	padding: 150px 50px 50px 50px;
}
.supercenter i,.supercenter p,.supercenter a{
	font-weight: normal;
	text-align: center;
}
.supercenter a{
	margin: 
}
.media-content p{
	font-size: 12px;
	padding: 10px 10px;
}
.media-content a{
	font-size: 14px;
}
</style>