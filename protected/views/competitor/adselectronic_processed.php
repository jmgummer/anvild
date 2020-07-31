<?php
/**
* Processed Electronic Ads File
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
$this->pageTitle=Yii::app()->name.' | Competitor Ads - Electronic Media ';
$this->breadcrumbs=array('Competitor Ads - Electronic Media '=>array('competitor/adselectronic'));
?>
<script src="<?php echo Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/FusionCharts.js'; ?>"></script>
<script language="JavaScript" src="<?php echo Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/FusionChartsExportComponent.js'; ?>"></script>

<?php

/*
** Required Variables
*/

$industry_id = $_POST['industry'];
$country_id = Yii::app()->user->country_id;
$enddate = $_POST['enddate'];
$startdate = $_POST['startdate'];
$sqlstartdate = date('Y-m-d', strtotime($startdate));
$sqlenddate = date('Y-m-d', strtotime($enddate));
$format = 'p';

/* Date Formating Starts Here */

$year_start     = date('Y',strtotime($startdate));  
$month_start    = date('m',strtotime($startdate));  
$day_start      = date('d',strtotime($startdate));
$year_end       = date('Y',strtotime($enddate)); 
$month_end      = date('m',strtotime($enddate)); 
$day_end        = date('d',strtotime($enddate));

/* 
** Industry Details 
*/
if(isset($_POST['industry'])){
	if($industry_name = AnvilIndustry::model()->find('industry_id=:a', array(':a'=>$_POST['industry']))){
		$industry_name = $industry_name->industry_name;
	}else{
		$industry_name = 'Unknown';
	}
}else{
	$error_code = 1;
}

/* 
** Sub Industry Text 
*/

$sub_industry_names = '';
$sub_industry_query = '';
$set_subs = '';
if(isset($_POST['sub_industry_name'])){
	foreach ($_POST['sub_industry_name'] as $sub_industry_id) {
		if($sub_industry_name= AnvilSubIndustry::model()->find('auto_id=:a', array(':a'=>$sub_industry_id))){
			$sub_industry_names .= ucwords(strtolower($sub_industry_name->sub_industry_name)).', ';
		}
	}
	$set_subs = implode(', ', $_POST['sub_industry_name']);
    $sub_industry_query = ' and brand_table.sub_industry_id IN ('.$set_subs.')';
}


/* If there are any errors terminate execution at this point and redirect back to the form */
if(isset($error_code)){
    Yii::app()->user->setFlash('warning', "<strong>Error ! Please select at least one from each section </strong>");
    $this->redirect(array('competitor/adselectronic'));
}

/* Select the Companies First */
$sql_companies="SELECT distinct(company_name), user_table.company_id 
FROM user_table, brand_table, incantation  
WHERE brand_table.company_id=user_table.company_id $sub_industry_query  
AND brand_table.brand_id=incantation.incantation_brand_id ORDER BY company_name";
if($stored_companies = Yii::app()->db3->createCommand($sql_companies)->queryAll()){
	/* Select Incantations for Each of the Companies */
	// File and Folder Operations
	$agency_id = Yii::app()->user->company_id;
	$industryid = $_POST['industry'];
	// Keep the Zip File Name Consistent - Combine Agencyid, industry & sub industry
	$azipfilename = $industryid.str_replace(', ', '', $set_subs);
	$cd_name = $agency_id . "_compilation_".$azipfilename;
	$file = Yii::app()->request->baseUrl . '/competitor/archives?zip='.$cd_name;
	$directory_cmd = $_SERVER['DOCUMENT_ROOT']."/anvild/html/".$cd_name;
	echo '<div id="download_zip_file"><a class="btn btn-primary btn-xs pdf-excel" target="_blank" href="'.$file.'"><i class="fa fa-circle-o-notch"></i> Download Zip File</a></div>';
	echo '<div id="filefuncs"></div>';

	if(!file_exists( $directory_cmd ) ){
		$directory_cmd ="mkdir -p ".$_SERVER['DOCUMENT_ROOT']."/anvild/html/".$cd_name;
		exec($directory_cmd);
		$permissions = "chmod -Rf 777 ".$_SERVER['DOCUMENT_ROOT']."/anvild/html/".$cd_name;
		exec($permissions);
		$ownership = "chown -Rf ReelAdmin:users  ".$_SERVER['DOCUMENT_ROOT']."/anvild/html/".$cd_name;
		exec($ownership);
	}
	$clienttype = Yii::app()->user->usertype;
	$clientid = Yii::app()->user->user_id;

	$compiled_file = "";
	$files = array();
	foreach ($stored_companies as $key) {
		$this_company_id = $key['company_id'];
		$this_company_name = $key['company_name'];
		$tabledata = Ads::ElectronicCompanyAds($this_company_id,$sub_industry_query,$sqlstartdate,$sqlenddate,$cd_name,$this_company_name);
		if($tabledata && $tabledata['data']!=''){
			$compiled_file .= '<p><strong>'.$this_company_name.'</strong></p>';
			$compiled_file .= $tabledata['data'];
			echo '<p><strong>'.$this_company_name.'</strong></p>';
			echo $tabledata['data'];
			$files[] = array_unique($tabledata['files'], SORT_REGULAR);
		}
	}

	$post['zipcopy']['clientid'] = $clientid;
	$post['zipcopy']['clienttype'] = $clienttype;
	$post['zipcopy']['dateadded'] = date("Y-m-d H:i:s");
	$post['zipcopy']['industryid'] = $industryid;
	$post['zipcopy']['subindustryid'] = $set_subs;
	$post['zipcopy']['startdate'] = $sqlstartdate;
	$post['zipcopy']['enddate'] = $sqlenddate;
	$post['zipcopy']['copystatus'] = 0;
	$post['zipcopy']['filename'] = $cd_name;
	$post['zipcopy']['ziplocation'] = "n/a";
	$post['zipcopy']['expirydate'] = date("Y-m-d H:i:s", strtotime("+1 Week"));
	$post['zipcopy']['filetypes'] = "Electronic";

	$post['files'] = $files;

	$url = Yii::app()->params->anvil_api . "processzip";
	$data = urldecode(http_build_query($post));
	$zip_status = Yii::app()->curl->post($url, $data);

	// $filejson = json_encode(array_filter($files));
	// echo "<input type='hidden' name='filejson' id='filejson' value='$filejson'>";
	$crunched = HtmlStories::FileBody($compiled_file);
}else{
	echo '<h4><strong>'.$industry_name.' Industry Competitor Reports</strong></h4>';
	echo '<h4>No Results Found</h4>';
}




?>
<style type="text/css">
.fupisha { max-width: 250px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; }
.nav-tabs > li.active > a { box-shadow: 0px -2px 0px #DB4B39; }
#tabs { width: 100%; background-color: #CCC; }
.pdf-excel{margin: 5px 1px;}
</style>