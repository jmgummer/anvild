<?php

/**
* BillBoardForm Component Class
* This Class Is Used To Return The Users/Company BillBoard Ads
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

class BillBoardForm{

	/** 
	*
	* @return  Return Channel Ads
	* @throws  InvalidArgumentException
	* @todo    Use This Function to Obtain the Channel Ads
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function ChanelCompanies($coid)
	{
		$sql_site="select distinct(billboard_company.company_id) as company_id, billboard_company.company_name as company_name 
		from  billboard_company, outdoor_channel_entries, brand_table 
		where outdoor_channel_entries.company_id=billboard_company.company_id 
		and outdoor_channel_entries.brand_id=brand_table.brand_id 
		and brand_table.company_id='$coid' 
		order by company_name";
		if($companies = Yii::app()->db3->createCommand($sql_site)->queryAll()){
			echo '<select name="channels" class="form-control" id="channels"   onchange="loading();checkchannels();" >';
			echo '<option value="" >-Select-</option>';
			foreach ($companies as $key) {
				$this_channel_id=$key["company_id"];
                $this_channel_name=trim($key["company_name"]);
				echo "<option value='" . $this_channel_id . "'>".$this_channel_name. "</option>";
			}
			echo '</select>';
		}else{
			echo 'No Results Found';
		}
	}

	/** 
	*
	* @return  Return A Select of All Provinces
	* @throws  InvalidArgumentException
	* @todo    Use This Function to create A Select of All Provinces
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function Provinces()
	{
		return '<select name="thisProvince_idField" id="thisProvince_idField"  onchange="checksites()"" class="form-control" >
		<option value="" selected="selected">-Select-</option>
		<option value="all" >-ALL-</option>
		<option value="2">Central</option>
		<option value="9">Coast</option>
		<option value="10">Eastern</option>
		<option value="8">Nairobi</option>
		<option value="11">North Eastern</option>
		<option value="6">Nyanza</option>
		<option value="7">Rift Valley</option>
		<option value="5">Western</option>
		</select>';
	}

	/** 
	*
	* @return  Return A Formatted Date
	* @throws  InvalidArgumentException
	* @todo    Use This Function to Return A Formatted Date
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function lastday($month = '', $year = '') {
		if (empty($month)) {
			$month = date('m');
		}
		if (empty($year)) {
			$year = date('Y');
		}
		$result = strtotime("{$year}-{$month}-01");
		$result = strtotime('-1 second', strtotime('+1 month', $result));
		return date('Y-m-d', $result);
	}

	/** 
	*
	* @return  Return A Formatted Date in Seconds, Hours & Minutes
	* @throws  InvalidArgumentException
	* @todo    Use This Function to Return a Formatted Date in Seconds, Hours & Minutes
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function sec2hms ($sec, $padHours = false)
	{
		// holds formatted string
		$hms = "";

		// there are 3600 seconds in an hour, so if we
		// divide total seconds by 3600 and throw away
		// the remainder, we've got the number of hours
		$hours = intval(intval($sec) / 3600);

		// add to $hms, with a leading 0 if asked for
		$hms .= ($padHours)	? str_pad($hours, 2, "0", STR_PAD_LEFT). ':' : $hours. ':';

		// dividing the total seconds by 60 will give us
		// the number of minutes, but we're interested in
		// minutes past the hour: to get that, we need to
		// divide by 60 again and keep the remainder
		$minutes = intval(($sec / 60) % 60);

		// then add to $hms (with a leading 0 if needed)
		$hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';

		// seconds are simple - just divide the total
		// seconds by 60 and keep the remainder
		$seconds = intval($sec % 60);

		// add to $hms, again with a leading 0 if needed
		$hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

		// done!
		return $hms;
	}

	/** 
	*
	* @return  Return A Formatted Date in Seconds, Hours & Minutes
	* @throws  InvalidArgumentException
	* @todo    Use This Function to Return a Formatted Date in Seconds, Hours & Minutes
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	This is an altered Version of the Formed [Sec2hms] function
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function FormattedTime ($sec, $padHours = false)
	{
		$hms = "";
		$hours = intval(intval($sec) / 3600);
		$hms .= ($padHours)	? str_pad($hours, 2, "0", STR_PAD_LEFT). ':' : $hours. 'h ';
		$minutes = intval(($sec / 60) % 60);
		$hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT).'m ';
		$seconds = intval($sec % 60);
		$hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT).'s';
		return $hms;
	}

}