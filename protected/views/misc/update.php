<?php
/**
* Update File
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
$this->pageTitle=Yii::app()->name.' | Update Invoice';
$this->breadcrumbs=array('Update Invoice'=>array('misc/rateupdate','id'=>$invoice));
$agency_id=Yii::app()->user->company_id;
// $z=1;$my_count = 1;
foreach ($model as $key) {
	$station_id = $key['station_id'];
	$station_name = $key['station_name'];
	// echo '<h4>'.$station_name.'</h4>';
	$sql_data="select * from invoice_reconciler  where inv_id='$invoice' and station_id='$station_id'";
	if($invoice_items = Yii::app()->db3->createCommand($sql_data)->queryAll()){
		echo '<div class="jarviswidget jarviswidget-sortable"style="" role="widget" style="position:relative"><header role="heading"><h2><strong>'.$station_name;
		echo "</strong>  <span class='minute' onclick=Reveal($station_id)>click to show details</span> </h2></header></div>";
		echo '<div id="'.$station_id.'" class="reveal">';
		echo "<form method='post'><table id='dt_basic' class='table table-condensed table-hover'>";
		
		foreach ($invoice_items as $myrow_data) {
			$ad_name 					=	"";
			$auto_id 					= 	$myrow_data["auto_id"]; 
			$data_reel_auto_id 			= 	$myrow_data["table_id"];        
			$data_this_brand_name 		= 	$myrow_data["brand_name"];      
			$data_this_data_date 		= 	$myrow_data["inv_date"];      
			$data_this_data_time 		= 	$myrow_data["inv_time"];       
			$data_this_data_station 	= 	$myrow_data["station_id"];
			$data_this_data_station_id 	= 	$myrow_data["station_id"];
			$data_this_entry_type 		= 	$myrow_data["entry_type"];      
			$data_this_Rate 			= 	$myrow_data["inv_rate"];    

			$thisSlotMonth=substr($data_this_data_date,5,2); 
			$thisSlotDay=substr($data_this_data_date,8,2);
			$thisSlotYear=substr($data_this_data_date,0,4);
			$thisSlotHour=substr($data_this_data_time,0,2);
			$thisinfoMinute=substr($data_this_data_time,3,2);
			$data_this_data_day=strtoupper(date("D",mktime($thisSlotHour, $thisinfoMinute, 0, $thisSlotMonth,$thisSlotDay, $thisSlotYear)));
						

					if($data_this_entry_type=='Spot Ad') { $table_name='A';} else {$table_name='M';};
					echo  "<tr bgcolor='#f2f2f2'>
						<td>$data_this_data_date</td>
						<td>$data_this_data_day</td>
						<td>$data_this_data_time</td>
						<td>$ad_name</td>
						<td>$data_this_brand_name</td>
						<td>$data_this_entry_type</td>";
								
						
				echo 	"<td><input class='form-control' type='text' name='invoice[$auto_id]' value='$data_this_Rate' id=".$auto_id." onchange='AddTotal(\"$data_this_data_station_id\")' /></td>";
						echo "</tr>";		
		}
		echo "<tr><td><strong>".$station_name."</strong></td><td> Total Number of Ads : ".$total_ad_count= count($invoice_items)."</td><td></td><td></td><td></td><td></td><td><input class='btn btn-success' type='submit' name='Submit' value='Update' /></td></tr>";
		echo "</table>"; 
		echo "</form>";
		echo '</div>';
		echo '<br>';
	}
}

// print_r($model);
?>
<script type="text/javascript">
$(document).ready(function(){
	$(".reveal").hide();
  	$("button").click(function(){
    	$(".reveal").slideToggle();
  	});
});
function Reveal(data)
{
var divid = data;
$("#"+divid).slideToggle();
}
</script>

<style type="text/css">
.jarviswidget {
    margin: 0px 0px 0px !important;
}
.minute{
	font-size: 12px !important;
	text-decoration: underline;
	cursor: pointer;
	color:red;
}
</style>