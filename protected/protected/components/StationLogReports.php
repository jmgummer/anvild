<?php
/**
* StationLogReports Component Class
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

class StationLogReports{

	/** 
	*
	* @return  Create Logs
	* @throws  InvalidArgumentException
	* @todo    Use This Function to Obtain the Channel Ads
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2015-01-21 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function CreateLog($entry_type,$this_date,$searched_station_id)
	{
		$tabledata = "";
		$temp_table="station_temp"  .date("Ymhmis");
		$year_start     = date('Y',strtotime($this_date));  
		$month_start    = date('m',strtotime($this_date)); 
		$reelforge_sample="reelforge_sample_"  .$year_start ."_".$month_start;
		$djmentions="djmentions_"  .$year_start ."_".$month_start;
		/* Call Function to Create the Temporary Table to Hold Data */
		$temp_table_create = StationLogReports::CreateTempTable($temp_table);
		
		if($entry_type=="all" || $entry_type=="auto") {
			$sql_log="insert ignore into $temp_table (`reel_incarntation` ,`reel_station`  ,`reel_date`,`reel_time` ,`incantation_length` ,`reel_brand` , 
			`entry_id`,adtype, comment,entry_type_id) select distinct reel_incarntation, reel_station, reel_date, reel_time, incantation_length, brand_name, 
			reel_auto_id, adtype ,comment,entry_type_id  from $reelforge_sample rfs , incantation inc, brand_table  btl where reel_date='$this_date' and station_id='$searched_station_id'  and  
			inc.incantation_id=rfs.incantation_id  and btl.brand_id=inc.incantation_brand_id order by reel_time asc;";
			Yii::app()->db3->createCommand($sql_log)->execute();
			$sql_update="update $temp_table set  entry_type='Spot Ad',entry_table='auto' ;";
			Yii::app()->db3->createCommand($sql_update)->execute();
		}

		if($entry_type=="all" || $entry_type=="manual") {
			$sql_log2="insert ignore into $temp_table 
			(`reel_incarntation` ,`reel_station`  ,`reel_date`,`reel_time` ,`incantation_length` ,`entry_type` ,`file_path`,`reel_brand`, `entry_id` ,`comment`,entry_type_id) 
			select distinct filename,  station_id, date, time, duration, entry_type, file_path, brand_name, $djmentions.auto_id,  comment ,entry_type_id 
			from $djmentions, brand_table where date='$this_date' and station_id='$searched_station_id'  and $djmentions.brand_id=brand_table.brand_id order by time asc";
			Yii::app()->db3->createCommand($sql_log2)->execute();
			$sql_update="update $temp_table set  entry_table='manual' where entry_table='';";
			Yii::app()->db3->createCommand($sql_update)->execute();
		}

		$sql_log="select * from $temp_table order by reel_time asc;";

		if($logs = Yii::app()->db3->createCommand($sql_log)->queryAll()){
			foreach ($logs as $key) {
				$this_reel_incarntation=$key["reel_incarntation"];
				$this_reel_station=$key["reel_station"];
				$this_reel_date=$key["reel_date"];
				$this_reel_time=$key["reel_time"];
				$this_reel_station=$key["reel_station"];
				$this_incantation_length=$key["incantation_length"];
				$this_entry_type=$key["entry_type"];
				$this_file_path=$key["file_path"];
				$this_brand_name=$key["reel_brand"];
				$this_entry_id=$key["entry_id"];
				$this_entry_table=$key["entry_table"];
				$this_adtype=$key["adtype"];
				$comment=$key["comment"];
				$entry_type_id=$key["entry_type_id"];

				$rth=substr($this_reel_time,0,2);
				$rtm=substr($this_reel_time,3,2);
				$rts=substr($this_reel_time,6,2);
				$eth=substr($last_end_time,0,2);
				$etm=substr($last_end_time,3,2);
				$ets=substr($last_end_time,6,2);

				$this_reel_time_ts=mktime($rth,$rtm,$rts,$month_start, $day_start ,$year_start);
				$last_end_time_ts=mktime($eth,$etm,$ets,$month_start, $day_start ,$year_start);
				$this_end_time=date("H:i:s",($this_reel_time_ts+$this_incantation_length));
				$time_diff=$this_reel_time_ts-$last_end_time_ts;

				if( $time_diff>5 && $time_diff<180 && $this_entry_type=="DJ Mention") {
					$tabledata .=  "<tr  valign='top'  bgcolor='#f2f2f2' ><td colspan=2 ><font color='red'>$time_diff sec gap </font></td><td  colspan=3><font color='red'>Check here for new ad</font></td></tr>" ;
				}

				if($this_adtype=="0" && $this_entry_table=='auto') {$this_adtype_name="A";}
				if($this_adtype=="m" && $this_entry_table=='auto') {$this_adtype_name="M"; $this_table="rs";$edit=1;}
				if(!$this_adtype && $this_entry_table=='manual') {$this_adtype_name="M";$this_table="dm";$edit=1;}
				if($this_entry_table=='manual') {$this_adtype_name="M";$this_table="dm";$edit=1;}
				if(!$comment) {$comment="OK";}

				if(!isset($this_table)) $this_table = 'dm';
				if($this_entry_id != '1' || $this_entry_id != '23' ) {

					$sql_editor="select company_name, date, time from  user_table,djmentions_editor where  user_table.company_id=djmentions_editor.editor_id and djmentions_editor.manual_id=$this_entry_id";
					$query_editor = Yii::app()->db3->createCommand($sql_editor)->queryAll();
					foreach ($query_editor as $myrow_editor) {
						$editor_name=$myrow_editor["company_name"];
						$editor_date=$myrow_editor["date"];
						$editor_time=$myrow_editor["time"];
					}

					$title=" title='".$editor_name." : ".$editor_date." : ".$editor_time."'";
					} else {
						$title='';
					}

					$tabledata .= "<tr><td >". $x.  "</td>
					<td ><a href='#' ".$title." >". $this_reel_time."</a></td>
					<td >".  substr($this_brand_name,0,20)." </td>
					<td >". $this_adtype_name."</td>
					<td >". $this_end_time."</td>
					<td >". $this_entry_type."</td>
					<td >". $this_incantation_length." secs</td>
					<td >";

					if(strlen($this_reel_incarntation)>10) {
						$this_reel_incarntation_pdf=$this_reel_incarntation;		
					} else {
						$this_reel_incarntation_pdf=	"-";	
					}

					if(strlen($this_reel_incarntation)>8) {
						if($this_entry_type=="DJ Mention") {
							$this_file_path=str_replace("/home/srv/www/htdocs","",$this_file_path);
							$tabledata .= '<a href=""  >';
			   				$tabledata .=  substr($this_reel_incarntation,0,30)  . "</a>";
						} else {
							$tabledata .=  substr($this_reel_incarntation,0,40) ;
						}
					} else {
						$tabledata .=  " - ";
					}
					$tabledata .=  "</font></td>";
					
					$tabledata .=  "<td ><font color='black'>". $comment." </font></td>";

					if($edit){
						$tabledata .=   "<td ><a href='../djmentions/editDjmentions.php?thisAuto_id=$this_entry_id&this_table=$this_table'>Edit</a></td>";
					}
					$tabledata .=  "<td><font color='black'>";
					//added by joe temporarily
					$tb = $year_start."_".$month_start;
					$j_entry_id = $myrow_log["entry_type_id"];
					$tabledata .= "<td ><div align='right'>
					<input name='check[$this_entry_id]'  type='checkbox' form='joe_del' />
					<input name='entry_id[$this_entry_id]' type='hidden' value='$this_entry_id' form='joe_del' />
					<input name='entry_type_id[$this_entry_id]' type='hidden' value='$j_entry_id' form='joe_del' />
					<input name='tb' type='hidden' value='$tb' form='joe_del' />
					</div>
					</td>";
					$tabledata .= "</tr>" ;
		
					//end added by joe
							
					$last_end_time=date("H:i:s", ($this_reel_time_ts+$this_incantation_length));

					$x++;











			}
		}else{
			$tabledata .= 'No Results Found';
		}
		return $tabledata;
	}

	public static function CreateTempTable($temp_table)
	{
		$temp_sql="CREATE TEMPORARY TABLE `".$temp_table."` (
		`auto_id` INT  AUTO_INCREMENT PRIMARY KEY ,
		`reel_incarntation` VARCHAR( 100 ) NOT NULL ,
		`reel_brand` VARCHAR( 100 ) NOT NULL ,
		`reel_station` VARCHAR( 10 ) NOT NULL ,
		`reel_date` DATE NOT NULL ,
		`reel_time` TIME NOT NULL ,
		`entry_type` VARCHAR( 10 ) NULL ,
		`entry_type_id` INT NULL ,
		`adtype` VARCHAR( 10 ) NULL ,
		`incantation_length` INT NULL,
		`file_path` VARCHAR( 100 ) NULL ,
		`entry_id` INT  ,
		`entry_table` VARCHAR( 10 ) NULL ,
		`comment` VARCHAR( 20 )  NULL 
		) ENGINE = MYISAM ;";
		Yii::app()->db3->createCommand($temp_sql)->execute();
		$sql_optimize="ALTER TABLE `$temp_table` ADD UNIQUE (`reel_incarntation` ,`reel_brand` ,`reel_station` ,`reel_date` ,`reel_time` ,`entry_type` ,`entry_table`);";
		Yii::app()->db3->createCommand($sql_optimize)->execute();
		$sql_optimize="ALTER TABLE `$temp_table` ADD UNIQUE (`reel_station` ,`reel_date` ,`reel_time` ,`entry_type` ,`entry_table`);";
		Yii::app()->db3->createCommand($sql_optimize)->execute();
		$sql_optimize="ALTER TABLE `$temp_table` ADD INDEX ( `reel_incarntation` , `reel_brand` , `reel_station` , `reel_date` , `reel_time` , `entry_type` , `entry_table` ) ;";
		Yii::app()->db3->createCommand($sql_optimize)->execute();
	}

	/** 
	*
	* @return  Create Table Header for Station Log
	* @throws  InvalidArgumentException
	* @todo    Use This Function to Create Table Header for Station Log
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2015-01-21  
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function LogTableHead()
	{
		return "<tr  valign='top'  bgcolor='#cfcfcf' > 
		<td ><strong><font color='red'>&nbsp;</font></strong></td>
		<td ><strong><font color='red'>Time</font></strong></td>
		<td ><strong><font color='red'>Brand Name</font></strong></td>
		<td width=5 ><strong><font color='red'>Ad Type</font></strong></td>
		<td ><strong><font color='red'>End Time</font></strong></td>
		<td ><strong><font color='red'>Type</font></strong></td>
		<td ><strong><font color='red'>Length</font></strong></td>
		<td ><strong><font color='red'>Ad Name</font></strong></td>
		<td ><strong><font color='red'>Comment</font></strong></td>
		<td colspan=2><strong><font color='red'>Edit</font></strong></td>
		<td valign='top' colspan=2>  <input type='submit' form='joe_del' value='Del' /> </td></tr>";
	}
}