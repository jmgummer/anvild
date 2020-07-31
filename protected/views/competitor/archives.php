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
$this->pageTitle=Yii::app()->name.' | Competitor Ads - Archives ';
$this->breadcrumbs=array('Competitor Ads - Archives '=>array('competitor/archives'));
?>
<p><strong>Download Your Files</strong></p>
<p><i>Please note that these files are generated from your competitor ads and are retained for only 1 week!</i></p>
<table class="table table-condensed">
	<thead><th>Industry</th><th>Sub Industries</th><th>Start Date</th><th>End Date</th><th>Ad Types</th><th>Status</th><th>Download</th></thead>
	<?php
		// get zip
		$getzip['clienttype'] = Yii::app()->user->usertype;
		$getzip['clientid'] = Yii::app()->user->user_id;
		// $sqlfilecheck = "SELECT * FROM zipcopy WHERE clientid=$clientid AND clienttype='$clienttype'";
		// $zipmanager = Zipcopy::model()->findAllBySql($sqlfilecheck);

		$url = Yii::app()->params->anvil_api . "getzips";
		$data = urldecode(http_build_query($getzip));
		$zipmanager = json_decode(Yii::app()->curl->post($url, $data), true);

		if($zipmanager != "no_data"){
			// echo "set";
			foreach ($zipmanager as $key) {
				$industry = $key['Industry'];
				$copystatus = $key['CopyStatus'];
				$filetypes = $key['filetypes'];
				$startdate = $key['startdate'];
				$enddate = $key['enddate'];
				$subs = $key['SubIndustries'];
				$downloadlink = $key['DownloadLink'];
				echo "<tr><td>$industry</td><td>$subs</td><td>$startdate</td><td>$enddate</td><td>$filetypes</td><td><font style='color:blue;'>$copystatus</font></td><td>$downloadlink</td></tr>";
			}
			
			// echo "<tr><td>No Records Found</td></tr>";
		}else{
			echo "<tr><td>No Records Found</td></tr>";
		}
	?>
</table>