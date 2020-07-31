<?php

/**
* PdfCreator Component Class
* This Class Is Used To Create PDF Files
* DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
* 
* @package     Anvil
* @subpackage  Components
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/

class PdfCreator{
	public static function CreatePdf($file){
		$pdf = Yii::app()->ePdf2->Output2('outdoor_pdf',array('sql_top_run'=>$sql_top_run,'company_name'=>$company_name,'channel'=>$this_channel_name,'title'=>$title));
		$filename="outdoor_channel_"  .str_replace(" ","_",Yii::app()->user->company_name)."_" . $this_province_name."_" .$this_date_start;
		$filename=str_replace(" ","_",$filename);
		$filename=preg_replace('/[^\w\d_ -]/si', '', $filename);
		$filename_pdf=$filename.'.pdf';
		$location = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/outdoor/pdf/".$filename_pdf;
		file_put_contents($location, $pdf);
	}
}