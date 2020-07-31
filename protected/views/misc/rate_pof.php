<?php
/**
* POF PDF File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version     v.1.0
* @since       July 2008
*/
$anvil_header = Yii::app()->request->baseUrl . '/images/anvil_header.jpg';
echo '<img src="'.$anvil_header.'" width="100%" />';
echo $table;
?>