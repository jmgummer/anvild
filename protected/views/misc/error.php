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

<?php echo "<h3>There was an error Creating the Invoice, Please try again Later</h3>"; ?>