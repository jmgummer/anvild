<?php
/**
* PDF File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/
$anvil_header = Yii::app()->request->baseUrl . '/images/anvil_header.jpg';
echo '<img src="'.$anvil_header.'" width="100%" />';
// echo '<h4>Outdoor Channel Report</h4>';
// echo '<h4>Company : '.$company_name.'</h4>';
// echo '<h4>Outdoor Channel : '.$channel.'</h4>';
echo $brand;
echo '<h4>Generated Date : '.date("Y-m-d").'</h4>';
echo $title;

echo '<br><hr>';

$body = '';

foreach ($model as $key) {
	$station_id = $key['station_id'];
	$this_station_name = $key['station_name'];
	
	$body.= '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
	$body.='<tr><td><strong>'.$this_station_name.'</strong></td></tr>';
	$body.= '<tr><td><strong>Date</strong></td><td><strong>Day</strong></td><td><strong>Time</strong></td><td><strong>Ad Name</strong></td><td><strong>Brand Name</strong></td>
	<td><strong>Type</strong></td><td><strong>Duration</strong></td><td><strong>Rate Card Value(Kshs)</strong></td><td><strong>Actual Value(Kshs)</strong></td></tr>';
	$inv_id = $invoice;
	$sql_data="select * from invoice_reconciler  where inv_id='$inv_id' and station_id='$station_id' order by inv_date asc";
	$station_ad_count = 0;
	$this_station_total=0;
	$station_ad_count=0;
	$this_ratecard_total=0;
	if($invoice_items = Yii::app()->db3->createCommand($sql_data)->queryAll()){
		foreach ($invoice_items as $keyvalues) {
			$data_inv_id = $keyvalues["inv_id"];        
			$data_table_name = $keyvalues["table_name"];   
			$data_table_id = $keyvalues["table_id"];      
			$data_inv_rate = $keyvalues["inv_rate"]; 
			$data_ratecard_value = $keyvalues["ratecard_rate"];		
			$data_inv_date = $keyvalues["inv_date"];
			$data_inv_time = $keyvalues["inv_time"];
			$data_station_id = $keyvalues["station_id"]; 
			$data_this_entry_type = $keyvalues["entry_type"];
			$data_duration = $keyvalues["duration"];
			$display_ad_name = $keyvalues["ad_name"];
			$data_this_brand_name = $keyvalues["brand_name"];										 

			$thisSlotMonth=substr($data_inv_date,5,2); 
			$thisSlotDay=substr($data_inv_date,8,2);
			$thisSlotYear=substr($data_inv_date,0,4);
			$thisSlotHour=substr($data_inv_time,0,2);
			$thisinfoMinute=substr($data_inv_time,3,2);
			$data_this_data_day=strtoupper(date("D",mktime($thisSlotHour, $thisinfoMinute, 0, $thisSlotMonth,$thisSlotDay, $thisSlotYear)));
			$this_ratecard_total=	$this_ratecard_total+$data_ratecard_value;
			$this_station_total=	$this_station_total+$data_inv_rate;
			$station_ad_count++;
			$body.= "<tr valign='top' bgcolor='#ffffff'>
			<td><font face='Arial, Helvetica, sans-serif' size='-3'>$data_inv_date</font></td>											
			<td><font face='Arial, Helvetica, sans-serif' size='-3'>$data_this_data_day</font></td>									
			<td><font face='Arial, Helvetica, sans-serif' size='-3'>$data_inv_time</font></td>						
			<td><font face='Arial, Helvetica, sans-serif' size='-3'>". substr($display_ad_name,0,40). "</td>
			<td><font face='Arial, Helvetica, sans-serif' size='-3'>". substr($data_this_brand_name,0,40). "</font></td>
			<td><font face='Arial, Helvetica, sans-serif' size='-3'>$data_this_entry_type</font></td>
			<td><font face='Arial, Helvetica, sans-serif' size='-3'>$data_duration secs</font></td>
			<td><font face='Arial, Helvetica, sans-serif' size='-3'><div align='right'>". number_format($data_ratecard_value) . 
			"</div></font></td>
			<td><font face='Arial, Helvetica, sans-serif' size='-3'><div align='right'>". number_format($data_inv_rate) . 
			"</div></font></td></tr>";
		}
		$body.= "<tr  bgcolor='#ffffff'  valign='top' >";
		$body.= "<td colspan=7><font face='Arial, Helvetica, sans-serif' size='-3'><strong>";
		$body.= "STATION TOTAL  ($this_station_name) | Total Number of Ads $station_ad_count</strong></font></td>";
		$body.= "<td align='right'>";
		$body.= "<font face='Arial, Helvetica, sans-serif' size='-3'><strong><div align='right'>";
		$body.= number_format($this_ratecard_total) . "</div>  </strong></font></td>";
		$body.= "<td align='right'>";
		$body.= "<font face='Arial, Helvetica, sans-serif' size='-3'><strong><div align='right'>";
		$body.= number_format($this_station_total) . "</div>  </strong></font></td>";
		$body.= "</tr>";
	}
	$body.= '</table><br>';
}
echo $body;