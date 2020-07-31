<?php
/**
* Brand Spends Processed File
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
$coid=$this_company_id = Yii::app()->user->company_id;
$this->pageTitle=Yii::app()->name.' | Agency Summaries';
$this->breadcrumbs=array('Agency Summaries'=>array('ranking/companyspends'));
?>
<script src="<?php echo Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/FusionCharts.js'; ?>"></script>
<script language="JavaScript" src="<?php echo Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/FusionChartsExportComponent.js'; ?>"></script>
<?php
/*
** Required Variables
*/

$agency_id = $_POST['company'];
$enddate = $_POST['enddate'];
$startdate = $_POST['startdate'];
$sqlstartdate = date('Y-m-d', strtotime($startdate));
$sqlenddate = date('Y-m-d', strtotime($enddate));

/* Process the Brands */
$brands = array();

if(isset($_POST['brands']) && !empty($_POST['brands'])){
    foreach ($_POST['brands'] as $key) {
      $brands[] = $key;
    }
    $set_brands = implode(', ', $brands);
}else{
    $error_code = 1;
}
/* Processing Begins Here,
** Obtain Company List then Pass to TopSpends Class to Manipulate
*/
$results = BrandSpends::AggregateTotalBrandSpends($agency_id,$enddate,$startdate,$sqlstartdate,$sqlenddate,$set_brands);



?>
<style type="text/css">
.fupisha { max-width: 250px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; }
.nav-tabs > li.active > a { box-shadow: 0px -2px 0px #DB4B39; }
#tabs { width: 100%; background-color: #CCC; }
.pdf-excel{margin: 5px 1px;}
.no-wrap{
	max-height: 500px;
	width: 100%;
	overflow: auto;
	color: #333;
}
</style>