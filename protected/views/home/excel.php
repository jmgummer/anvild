<?php
/**
* Excel Page File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/
$todays = date('Y-m-d');
$startdate = $enddate = $todays;
$search = ' ';
/*
*  Adding Country Code
*  Current Default value is Kenya
*/
$country = 1;
$industries = '';
// Adding backdate
$cat_identifier = 1;
$type_identifier = 1;

$company_words = Company::model()->find('company_id=:a order by keywords', array(':a'=>Yii::app()->user->company_id));
$backdate = $company_words->backdate;

if(isset($_GET['startdate'])){
  $startdate= $_GET['startdate'];
}
if(isset($_GET['enddate'])){
  $enddate= $_GET['enddate'];
}
if(isset($_GET['search'])){
  $search= $_GET['search'];
}
if(isset($_GET['industries'])){
  $industries= $_GET['industries'];
}

if(isset($_GET['cat_identifier'])){
  $cat_identifier= $_GET['cat_identifier'];
}
if(isset($_GET['type_identifier'])){
  $type_identifier= $_GET['type_identifier'];
}

if($type_identifier==1){
	if($cat_identifier==1){
		echo '<table><tr><td>My Stories</td></tr></table><br>';
		$stories = ExcelStories::GetClientStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		echo '<br>';
		// echo '<table><tr><td>My Electronic Stories</td></tr></table><br>';
		// $stories = PdfStories::GetElectronicStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		// echo '<br>';
		// echo '<table><tr><td>Industry Print Stories</td></tr></table><br>';
		// $stories = PdfStories::GetClientIndustryStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		// echo '<br>';
		// echo '<table><tr><td>Industry Electronic Stories</td></tr></table><br>';
		// $stories = PdfStories::GetClientElectronicIndustryStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		// echo '<br>';
	}
	// if($cat_identifier==2){
	// 	echo '<table><tr><td>My Stories</td></tr></table><br>';
	// 	$stories = PdfStories::GetClientStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
	// 	echo '<br>';
	// 	echo '<table><tr><td>My Electronic Stories</td></tr></table><br>';
	// 	$stories = PdfStories::GetElectronicStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
	// 	echo '<br>';
	// }
	// if($cat_identifier==3){
	// 	echo '<table><tr><td>Industry Print Stories</td></tr></table><br>';
	// 	$stories = PdfStories::GetClientIndustryStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
	// 	echo '<br>';
	// 	echo '<table><tr><td>Industry Electronic Stories</td></tr></table><br>';
	// 	$stories = PdfStories::GetClientElectronicIndustryStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
	// 	echo '<br>';
	// }
}
// if($type_identifier==2){
// 	if($cat_identifier==1){
// 		echo '<table><tr><td>My Stories</td></tr></table><br>';
// 		$stories = PdfStories::GetClientStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
// 		echo '<br>';
// 		echo '<table><tr><td>Industry Electronic Stories</td></tr></table><br>';
// 		$stories = PdfStories::GetClientIndustryStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
// 		echo '<br>';
// 	}
// 	if($cat_identifier==2){
// 		echo '<table><tr><td>My Stories</td></tr></table><br>';
// 		$stories = PdfStories::GetClientStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
// 		echo '<br>';
// 	}
// 	if($cat_identifier==3){
// 		echo '<table><tr><td>Industry Electronic Stories</td></tr></table><br>';
// 		$stories = PdfStories::GetClientIndustryStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
// 		echo '<br>';
// 	}
// }
// if($type_identifier==3){
// 	if($cat_identifier==1){
// 		echo '<table><tr><td>My Electronic Stories</td></tr></table><br>';
// 		$stories = PdfStories::GetElectronicStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
// 		echo '<br>';
// 		echo '<table><tr><td>Industry Electronic Stories</td></tr></table><br>';
// 		$stories = PdfStories::GetClientElectronicIndustryStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
// 		echo '<br>';
// 	}
// 	if($cat_identifier==2){
// 		echo '<table><tr><td>My Electronic Stories</td></tr></table><br>';
// 		$stories = PdfStories::GetElectronicStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
// 		echo '<br>';
// 	}
// 	if($cat_identifier==3){
// 		echo '<table><tr><td>Industry Electronic Stories</td></tr></table><br>';
// 		$stories = PdfStories::GetClientElectronicIndustryStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
// 		echo '<br>';
		
// 	}
// }
    
?>