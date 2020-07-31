<?php

/**
* Outdoor Processed File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/

/*
** This Page is Used to Process the Outdoor Channel Data, for the time being
*/
$level = Yii::app()->user->company_level;
$company_name = Yii::app()->user->company_name;
$thisCompany_idField = $company_id = $this_company_id = Yii::app()->user->company_id;

$this->pageTitle=Yii::app()->name.' | Outdoor Channel Reports';
$this->breadcrumbs=array('Outdoor Channel Reports'=>array('media/outdoor'));

/* 
** Variable Details are Picked up from Post Data 
** Initialize empty Variables to rid off unknown variable errors
** Count the length of the month, if its 1 then add a zeo at the start for successful run
*/

$thisClient_id = $thisChannel_idField 	= $_POST['channel'];	
// $month_start 			= $_POST['month'];	
// if(strlen($month_start) ==1){
// 	$month_start = '0'.$month_start;
// }
$thisProvince_idField 	= $_POST['region'];	
$thisBrand_idField 		= $_POST['thisBrand_idField'];	
$thisSite_idField 		= $_POST['thisSite_idField'];	
// $year_start 			= $_POST['year'];
$enddate = date('Y-m-d', strtotime($_POST['enddate']));
$startdate = date('Y-m-d', strtotime($_POST['startdate']));
$query = ' ';
$title = ' ';
$body = ' ';

/* 
** Get Channel Name
*/

$sql_channel="select  billboard_company.company_name as company_name from  billboard_company where company_id=$thisChannel_idField";
if($channel_details = BillboardCompany::model()->findBySql($sql_channel)){
	$this_channel_name = $channel_details->company_name;
}else{
	$this_channel_name = 'Unknown Billboard Company';
}

/* 
** Process the Dates, Generate a Possible End Date From The Data
** Obtain Channel Data, Provinces, Sites, Brands
*/


// $this_date_start = $year_start."-".$month_start ;
// $this_date_end = BillBoardForm::lastday($month_start, $year_start);

$this_date_start = $startdate;
$this_date_end = $enddate;

if($thisProvince_idField && $thisProvince_idField!='all') {
    $query.= " and  province_id='$thisProvince_idField' ";
    $sql_province="select province_name from province where auto_id='$thisProvince_idField'";
    if($province_details = Province::model()->findBySql($sql_province)){
    	$my_province_name = $province_details->province_name;
    }else{
    	$my_province_name = 'Unknown Province';
    }
}

if($thisProvince_idField=='all') {
    $query.= " ";
    $my_province_name="All Provinces";
}


if($thisSite_idField) {
	if($thisSite_idField!='all'){
		$query.= " and  ocs.auto_id='$thisSite_idField' ";
		$sql_channel_site="select site_name from outdoor_channel_sites where auto_id='$thisSite_idField'";
		if($channel_details = OutdoorChannelSites::model()->findBySql($sql_channel_site)){
			$my_site_name=$channel_details ->site_name;
		}else{
			$my_site_name = 'Unknown Site';
		}
	}else{
		$query.=" ";
		$my_site_name = 'All Sites';
	}
	
}

if($thisBrand_idField && $thisBrand_idField!="all") {
	$query.= " and  oce.brand_id='$thisBrand_idField' ";
	$sql_brand="select brand_name from brand_table where brand_id='$thisBrand_idField'";
	if($brand_details = BrandTable::model()->findBySql($sql_brand)){
		$my_brand_name = $brand_details->brand_name;
	}else{
		$my_brand_name = 'Unknown Brand';
	}
}

if($thisBrand_idField=="all") {
    $query.= " ";
    $my_brand_name="All Brands";
}

// $title.="<strong>" .strtoupper($my_brand_name) ."</strong><br>";
if($my_province_name) { $title.="Region : " .$my_province_name; } else {$title.="Region : All" ;}
$title.=" | ";
if($my_site_name) { $title.="Site : " .$my_site_name; } else {$title.="Site : All" ;}

/* 
** This case(missing client id) is Unlikely to happen, but just in case 
*/

if($thisClient_id) {
	$sql_outdoor = "select ut.company_name,bt.brand_name,oce.inspection_date,oce.inspection_time ,oce.quality ,oce.comment ,oce.inspector_name ,oce.audience_male ,oce.audience_female,
	oce.audience_children ,oce.entry_type ,oce.length ,oce.incantation_id,ocs.site_name ,ocs.site_location,ocs.site_town ,ocs.site_type ,town_name, province_name 
	from
	outdoor_channel_entries  oce,brand_table bt,billboard_company bc,user_table ut,outdoor_channel_sites ocs,towns,province where 
	inspection_date between '$this_date_start%' and '$this_date_end%' and 
	oce.company_id='$thisClient_id' and bt.brand_id=oce.brand_id and bc.company_id=oce.company_id  and ut.company_id=bt.company_id and ocs.auto_id=oce.site_id and
	ocs.site_town=towns.auto_id and towns.province_id=province.auto_id and bt.company_id=$thisCompany_idField $query order by oce.inspection_date,oce.inspection_time ";
}else{
	$sql_outdoor = "select ut.company_name,bt.brand_name,oce.inspection_date,oce.inspection_time ,oce.quality ,oce.comment ,oce.inspector_name ,oce.audience_male ,oce.audience_female,
	oce.audience_children ,oce.entry_type ,oce.length ,oce.incantation_id,ocs.site_name ,ocs.site_location,ocs.site_town ,ocs.site_type ,town_name,province_name
	from
	outdoor_channel_entries  oce,brand_table bt,billboard_company bc,user_table ut, outdoor_channel_sites ocs, towns, province where 
	inspection_date between '$this_date_start%' and '$this_date_end%' and 
	oce.company_id='$thisCompany_idField' and bt.brand_id=oce.brand_id and bc.company_id=oce.company_id  and ut.company_id=bt.company_id and ocs.auto_id=oce.site_id and 
	ocs.site_town=towns.auto_id and towns.province_id=province.auto_id $query order by oce.inspection_date,oce.inspection_time ";
}

/* 
** Create temp table
*/

$outdoor_temp="outdoor_temp_" . date("Ymdhis");
$tempsql = 'create temporary table if not exists '.$outdoor_temp.' AS '.$sql_outdoor;
// echo $tempsql.'<br>------<br>';
if($my_query = Yii::app()->db3->createCommand($tempsql)->execute()){
	// echo 'Temp Ran<br>------<br>';
}else{
	// echo 'Temp Failed<br>------<br>';
}

// $sql_top="select distinct(oce.incantation_id),entry_type, length 
// from outdoor_channel_entries oce, brand_table bt, billboard_company bc, user_table ut, outdoor_channel_sites ocs 
// where 
// inspection_date between '$this_date_start%' and '$this_date_end%' 
// and oce.company_id='$thisCompany_idField' 
// and bt.brand_id=oce.brand_id 
// and bc.company_id=oce.company_id 
// and ut.company_id=bt.company_id 
// and ocs.auto_id=oce.site_id
// and oce.brand_id='$thisBrand_idField' 
// order by oce.inspection_date,oce.inspection_time" ;


$sql_top="select * from $outdoor_temp";
if($sql_top_run = Yii::app()->db3->createCommand($sql_top)->queryAll()){
	
}

$this_province_name=str_replace(" ","_",$my_province_name);

/* 
** PDF Time
*/

$pdf = Yii::app()->ePdf2->Output2('outdoor_pdf',array('sql_top_run'=>$sql_top_run,'company_name'=>$company_name,'channel'=>$this_channel_name,'title'=>$title,'my_brand_name'=>$my_brand_name));
$filename="outdoor_channel_"  .str_replace(" ","_",Yii::app()->user->company_name)."_" . $this_province_name."_" .$this_date_start;
$filename=str_replace(" ","_",$filename);
$filename=preg_replace('/[^\w\d_ -]/si', '', $filename);
$filename_pdf=$filename.'.pdf';
$location = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/outdoor/pdf/".$filename_pdf;
if(file_put_contents($location, $pdf)){
    // echo '<p>PDF CREATED</p>';
}else{
    // echo '<p>PDF FAILED</p>';
}

$period = $this_date_start.' and '.$this_date_end;
$excel = OutdoorExcel::ExcelBook($sql_top_run,$period,$company_name,$my_brand_name);

?>
<h3>Outdoor Channel Brand Report - <?php echo $this_channel_name; ?> | Brand : <?php echo $my_brand_name; ?></h3>


<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
    <header role="heading clearfix">
        <?php echo '<h2><span class="pull-left">The following is a Report for the period between : '.$this_date_start.' and '.$this_date_end.'</span><span class="pull-right">'; ?>
        <a href="<?php echo Yii::app()->request->baseUrl . '/docs/outdoor/pdf/'.$filename_pdf; ?>" class="btn btn-danger btn-xs pull-right pdf-excel" target="_blank"><i class="fa fa-file-pdf-o"></i> PDF</a>
        <a href="<?php echo Yii::app()->request->baseUrl . '/docs/outdoor/excel/'.$excel; ?>" class="btn btn-success btn-xs pull-right pdf-excel" target="_blank"><i class="fa fa-file-excel-o"></i> EXCEL</a>
        </span></h2>
    </header>
</div>



<table width="90%" id="dt_basic" class="table table-condensed table-bordered table-hover"  >
<thead  valign='top'  bgcolor='#cfcfcf' >
<th ><strong>#</strong></th>
<th ><strong>Site</strong></th>
<th ><strong>Region</strong></th>
<th ><strong>Type</strong></th>
<th ><strong>Town</strong></th>
<th ><strong>Date</strong></th>
<th ><strong>Time</strong></th>
<th ><strong>Comments</strong></th>
<th ><strong>Men</strong></th>
<th ><strong>Women</strong></th>
<th ><strong>Children</strong></th></thead>


<?php



$body.= "<table width='100%' border='0' cellpadding='0' cellspacing='1'  >";
$x=1;

$body.=  "<tr valign='top'   bgcolor='#ffffff'>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>#</font></strong></td>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>SITE</font></strong></td>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>LOCATION</font></strong></td>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>TYPE</font></strong></td>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>TOWN</font></strong>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>DATE</font></strong></td>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>TIME</font></strong></td>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>COMMENTS</font></strong></td>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>MEN</font></strong></td>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>WOMEN</font></strong></td>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>CHILDREN</font></strong></td></tr>";

if($level==0) {
	echo "<td valign='top'><strong><font color='red'>&nbsp; Delete</strong></td>";
}
echo "</tr>" ;
//echo $sql_log . "<hr>";
$sql_log_main="select * from $outdoor_temp";

if($sql_main_run = Yii::app()->db3->createCommand($sql_log_main)->queryAll()){
	$counter = 1;
	foreach ($sql_main_run as $key_main_run) {
		$my_company_name=$key_main_run["company_name"];
        $brand_name=$key_main_run["brand_name"];
        $inspection_date=$key_main_run["inspection_date"];
        $inspection_time=$key_main_run["inspection_time"];
        $quality=$key_main_run["quality"];
        $comment=$key_main_run["comment"];
        $inspector_name=$key_main_run["inspector_name"];
        $audience_male=$key_main_run["audience_male"];
        $audience_female=$key_main_run["audience_female"];
        $audience_children=$key_main_run["audience_children"];
        $entry_type=$key_main_run["entry_type"];
        $length=$key_main_run["length"];
        $incantation_id=$key_main_run["incantation_id"];
        $site_name=$key_main_run["site_name"];
        $site_location=$key_main_run["site_location"];
        $site_town=$key_main_run["town_name"];
        $site_type=$key_main_run["site_type"];
        $site_province=$key_main_run["province_name"];

        echo  "<tr  valign='top'  bgcolor='#f2f2f2' >
        <td ><font color='black'>". $counter."</font></td>
		<td ><font color='black'>". $site_name."</font></td>
		<td ><font color='black'>". $site_province."</font></td>
		<td ><font color='black'>". $site_type."</font></td>
		<td ><font color='black'>". substr($site_town,0,10)."</font></td>
		<td ><font color='black'>". $inspection_date."</font></td>
		<td ><font color='black'>". $inspection_time."</font></td>
		<td ><font color='black'>". $comment."</font></td>
		<td ><font color='black'>". $audience_male."</font></td>
		<td ><font color='black'>". $audience_female."</font></td>
		<td ><font color='black'>". $audience_children."</font></td><tr>";
		$x++;
		$counter++;
	}
}

$sql_drop="drop table $outdoor_temp";
if($query_drop = Yii::app()->db3->createCommand($sql_drop)->execute()){
	// echo 'Successful Drop';
}else{
	// echo 'Drop Failed';
}
?>
</table>
<style type="text/css">
.fupisha { max-width: 250px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; }
.nav-tabs > li.active > a { box-shadow: 0px -2px 0px #DB4B39; }
#tabs { width: 100%; background-color: #CCC; }
.pdf-excel{margin: 5px 1px;}
</style>