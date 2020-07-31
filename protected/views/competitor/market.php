<?php
/**
* Market Share File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/
$level = Yii::app()->user->company_level;
$company_name = Yii::app()->user->company_name;
$this_company_id = Yii::app()->user->company_id;
$this->pageTitle=Yii::app()->name.' | Share of Market';
$this->breadcrumbs=array('Share of Market '=>array('competitor/marketshare'));
?>