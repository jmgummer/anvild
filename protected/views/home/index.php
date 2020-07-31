<?php
/**
* Index Page File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/
$this->pageTitle=Yii::app()->name.' | Home';
$this->breadcrumbs=array('Dashboard'=>array('home/index'));
?>
<br>
<p><strong>Hello <?php echo Yii::app()->user->client_name; ?>,</strong></p>

<div class="row">
	<?php 
	// echo Yii::app()->user->subscriptions; 
	// echo Yii::app()->user->company_id; 
	// echo Yii::app()->user->usertype; 
	// echo 'country_code - '.Yii::app()->user->country_code; 
	// echo 'country_code - '.Yii::app()->user->country_code;
	// echo 'country_id - '.Yii::app()->user->country_id;  
	// echo Common::CountryCurrency(Yii::app()->user->country_id);
	?>
</div>
<?php 
if(Yii::app()->user->usertype=='adflite'){
	echo '<p>You are now logged into your Adflite account.</p>';
}elseif (Yii::app()->user->usertype=='agency') {
	echo '<p>You are now logged into your Anvil account.</p>';
	echo '<p>By using your left hand menu, you can do the following: </p>
	<p><strong>Media Reports</strong><br>
	Proof of Flight (Electronic Media) – An audit report on day, time, material, and length of booked spots.<br>
	Proof of Print – An audit report on booked print ads.<br>
	Industry Competitor (Company) Reports – Advertising spend by company, by media and by industry.<br>
	Industry Competitor (Brand) Reports – Advertising spend by brand by media by sub category.<br>
	</p>
	<br>
	<p><strong>Company Rankings</strong><br>
	Top Spenders By Company – Company rankings based on advertising spend.<br>
	Top Spenders By Brand – Brand rankings based on advertising spend.<br>
	Summary Spends By Media – Total spend in advertising by media and industry.<br>
	</p>
	<br>
	<p><strong>Competitor Reports, Analysis & Miscellaneous Reports</strong> <br>
	Competitor Ads – An archive of competitor advertising.<br>
	Industry Summary – Special advertising spend report by industry.<br>
	Rate Change – Allows you to put in actual money spent on the proof of flight reports.<br>
	</p>';
}else{
	$this->renderPartial('dashboard_filter',array('model'=>$model));
}


?>
