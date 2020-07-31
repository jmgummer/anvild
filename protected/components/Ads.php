<?php

/**
* Ads Component Class
* This Class Is Used To Return The Users/Company Ads
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

class Ads{

	/**
	*
	* @return  Return Electronic Company Ads
	* @throws  InvalidArgumentException
	* @todo    Use This Function to Obtain the Company Electronic Ads
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function ElectronicCompanyAds($companyid,$sub_industry_query,$sqlstartdate,$sqlenddate,$cd_name,$this_company_name)
	{
		$data = '';

		$data_array = array();
		$filearray = array();
		$filecounter = 0;

		/* Date Formating Starts Here */
		$year_start     = date('Y',strtotime($sqlstartdate));  
		$month_start    = date('m',strtotime($sqlstartdate));  
		$day_start      = date('d',strtotime($sqlstartdate));
		$year_end       = date('Y',strtotime($sqlenddate)); 
		$month_end      = date('m',strtotime($sqlenddate)); 
		$day_end        = date('d',strtotime($sqlenddate));

		/* 
		** Reelforge Sample
		** Query Preparation - Loop through years, months, days
		*/

		$count = 0;

		for ($x=$year_start;$x<=$year_end;$x++)
		{
		    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
		    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

		    $month_start_count=$month_start_count+0;

		    for ($y=$month_start_count;$y<=$month_end_count;$y++)
		    {
		        if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
		        $temp_table_month="reelforge_sample_"  .$x."_".$my_month;

				$sql_incantation="SELECT distinct($temp_table_month.incantation_id), incantation.incantation_company_id,
				incantation.incantation_file,incantation.incantation_date, station.station_name, station.station_id, 
				sub_industry.sub_industry_name , incantation.file_path,incantation.mpg, incantation.mpg_path,
				$temp_table_month.reel_date, incantation.incantation_name, brand_table.brand_name 
				FROM $temp_table_month 
				INNER JOIN incantation on $temp_table_month.incantation_id = incantation.incantation_id
				INNER JOIN brand_table on brand_table.brand_id=incantation.incantation_brand_id
				INNER JOIN sub_industry on brand_table.sub_industry_id=sub_industry.auto_id
				INNER JOIN station ON station.station_id = $temp_table_month.station_id 
				WHERE brand_table.company_id = $companyid $sub_industry_query
				AND $temp_table_month.brand_id = brand_table.brand_id AND $temp_table_month.entry_type_id = 1 AND $temp_table_month.active=1 AND reel_date between '$sqlstartdate' AND '$sqlenddate';";

				if($ads = Yii::app()->db3->createCommand($sql_incantation)->queryAll()){
					/* Get the Location First */
					$agency_id = Yii::app()->user->company_id;
					$disp_month=date("M",mktime(0,0,0,$my_month,1,1999));
					$data .= "<p><strong>".$disp_month." - ".$x."</strong></p>"; 
					$data .= Ads::ElectronicTableHead();
					foreach ($ads as $key) {
						// $data .= Ads::TableHead();
						$brand_name=$key["brand_name"];
						$incantation_name=$key["incantation_name"] ;
						$thisIncantation_file=$incantation_file=$key["incantation_file"] ;
						$sub_industry_name=ucwords(strtolower($key["sub_industry_name"])) ;
						$file_path=$key["file_path"];
						$thisIncantation_mpg=$mpg=$key["mpg"];
						$thisIncantation_mpg_path=$mpg_path=$key["mpg_path"];
						$this_file = $key["incantation_file"];
						$thisFile_path = $key["file_path"];
						$thisFile_path=$this_file_path = str_replace("/home/srv/www/htdocs/anvil/","",$thisFile_path);
						$incantation_date=$key["reel_date"];
						$this_incantation_company_id=$key["incantation_company_id"];
						$inc_year=substr($incantation_date,0,4);
						$inc_month=substr($incantation_date,5,2);
						$inc_month=date("M",mktime(0,0,0,$inc_month,1,1999));
						$date_formated = date('d-m-Y', strtotime($incantation_date));
						/* Note to Future Self */
						/* 
						** Files are duplicated per month because the client wants to see the same campaign per month
						** What sorcery is this jameni ??
						*/
						$incantation_month = date('m-Y', strtotime($incantation_date));
						$station_name = str_replace(' ', '_', $key["station_name"]);
						$file_path=str_replace("/home/srv/www/htdocs","",$file_path);
						$incantation_file=str_replace(".wav", ".mp3",$incantation_file);
						$ad_name="<a href=\"javascript: void(0)\"  onclick=\"window.open('http://media.reelforge.com/" .$file_path . 		
						$incantation_file . "',  'windowname1',  'width=200, height=90'); return false;\">" . str_replace("_"," ",substr($incantation_name,0,30)) . "...</a>";
						$show=1;
						$data.= "<tr>";
						$dash=strpos( $thisIncantation_file, "_");
						$file_length=strlen($thisIncantation_file) - $dash;
						$brand_name_dashless=substr(substr( $thisIncantation_file, ($dash+1), $file_length),0,-4);
						$thisIncantation_mp3=str_replace(".wav",".mp3",$thisIncantation_file);
						$absolutepath = '/home/srv/www/htdocs/anvil/'.$thisFile_path . $thisIncantation_mp3;
						$filetag = str_replace(' ', '_', $this_company_name)."_".str_replace(' ', '_', $brand_name)."_".$thisIncantation_mp3;

						$data.= "<td><a href=\"javascript: void(0)\"  onclick=\"window.open('http://media.reelforge.com/anvil/";
						$data.= $thisFile_path . $thisIncantation_mp3;
						$data.= "','windowname1',  'width=200, height=90, status=0,toolbar=0,location=0');   return false;\">
						<img src='http://media.reelforge.com/anvil/images/play_icon.jpeg' alt='Play ad' width='15' height='15' border='0'/></a></td><td align='left' valign='top'>";

						if(isset($mpg_path) && !empty($mpg_path)) {
							$videopath = '/home/srv/www/htdocs/anvil/'.$thisFile_path . $mpg_path;
							// $filetag = str_replace(' ', '_', $this_company_name)."_".str_replace('-', '_', $incantation_date)."_".$station_name."_".str_replace(' ', '_', $brand_name)."_".$mpg_path;
							$filetag = str_replace(" ", "_", $incantation_name)."_".$mpg_path;


							$filearray[$filecounter]['filepath'] = $videopath;
							$filearray[$filecounter]['filepathname'] = $filetag;
							$filecounter++;	
							$videourl = "http://media.reelforge.com/anvil/".$thisFile_path.$mpg_path;
							$data.= "<a href=\"javascript: void(0)\"  onclick=\"window.open('$videourl";
							$data.="','windowname1',  'width=352, height=288, status=0,toolbar=0,location=0');   return false;\"> 
							<img src='http://media.reelforge.com/anvil//images/vid_icon.jpg' alt='Play ad' width='15' height='15' border='0' /></a>";
						}else{
							$filearray[$filecounter]['filepath'] = $absolutepath;
							$filearray[$filecounter]['filepathname'] = $filetag;
							$filecounter++;	
							$videourl = "";
							$data.= " - ";
						}

						// $copy_files = HtmlStories::MoveElectronicFile($key["incantation_file"],$cd_name,$key["file_path"]);					
						$data.= "</td>";
						$data.= "<td  width='30%'>". substr($brand_name,0,30) ."</td>";
						$data.= "<td  width='30%'>". $ad_name ."</td>";
						$data.= "<td  width='20%'>". $sub_industry_name."</td>";
						$data.= "<td  width='20%'>". $date_formated."</td>";
						$data.= "</tr>";
						$count++;
					}
					$data.= "<tr><td></td><td></td><td></td><td></td><td><strong>Ad Count</strong></td><td><strong>$count</strong></td></tr>";
					$data.='</table>';
				}

			}
		}
		$data_array['data'] = $data;
		$data_array['files'] = $filearray;
		return $data_array;
	}

	

	/**
	*
	* @return  Return Electronic Table Head
	* @throws  InvalidArgumentException
	* @todo    Use This Function to Create Electronic Table Head
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function ElectronicTableHead(){
		$tablehead = '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';	
		$tablehead.= "<thead>
		<th><strong>&nbsp</strong></th>
		<th><strong>&nbsp</strong></th>
		<th><strong>Brand Name</strong></th>
		<th><strong>Ad Name</strong></th>
		<th><strong>Sub Industry</strong></th>
		<th><strong>Time</strong></th></thead>";
		return $tablehead;
	}

	/** 
	*
	* @return  Return Print Ads
	* @throws  InvalidArgumentException
	* @todo    Use This Function to Generate Print Ads
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function PrintCompanyAds($companyid,$sub_industry_query,$sqlstartdate,$sqlenddate,$this_company_name)
	{
		$data = '';
		
		$data_array = array();
		$filearray = array();
		$filecounter = 0;

		$sql_incantation="SELECT distinct brand_name,date,page,sub_industry_name , file,ad_size,Media_House_List from brand_table, print_table, sub_industry,mediahouse  where 
		brand_table.company_id='$companyid' $sub_industry_query and
        brand_table.brand_id=print_table.brand_id and
        date between '$sqlstartdate' and '$sqlenddate' and
        brand_table.sub_industry_id=sub_industry.auto_id  AND mediahouse.Media_House_ID = print_table.media_house_id
        order by brand_name asc, date desc, Media_House_List asc ";
        if($ads = Yii::app()->db3->createCommand($sql_incantation)->queryAll()){
        	/* Folder Name, Consists of Company ID and the Date */
        	$agency_id = Yii::app()->user->company_id;
			$cd_name=$agency_id . "_compilation_print_".date('dmY');

        	$data .= Ads::PrintTableHead();
        	foreach ($ads as $key) {
        		$brand_name=$key["brand_name"];
				$thisDate=$key["date"] ;
				$page=$key["page"] ;
				$Media_House_List=$key["Media_House_List"] ;
				$sub_industry_nam=$key["sub_industry_name"] ;
				$file=$key["file"] ;
				/* Move the PDF File */
				$file_location = '/home/srv/www/htdocs/reelmedia/files/pdf/'.$file;
				// $file_location = 'http://media.reelforge.com/reelmedia/files/pdf/'.$file;

				$newfilename = $thisDate.'_'.$Media_House_List.'_'.$page.'_'.$brand_name;
				$newfilename = str_replace(" ", "_", $newfilename);
				$newfilename = str_replace("-", "_", $newfilename);
				$newfilename = str_replace(":", "_", $newfilename);
				$newfilename = str_replace("/", "_", $newfilename);
				$newfilename = $newfilename.'.pdf';

				$file_destination2 = "html/".$cd_name."/".$newfilename;

				$filetag = str_replace(' ', '_', $this_company_name)."_".$newfilename;

				$filearray[$filecounter]['filepath'] = $file_location;
				$filearray[$filecounter]['filepathname'] = $filetag;
				$filecounter++;
				/* End PDF Move */
				$ad_size=$key["ad_size"];		
				$inc_year=substr($thisDate,0,4);
				$inc_month=substr($thisDate,5,2);
				$inc_month=date("M",mktime(0,0,0,$inc_month,1,1999));
				$date_formated = date('d-m-Y', strtotime($thisDate));
				$show=1;
				// $swf_url="/anvil/reports/print_story_console/print_stream.php?url=$file&brand=$brand_name";
				$swf_url="http://media.reelforge.com/anvil/reports/print_story_console/print_stream.php?url=$file&brand=$brand_name";
				
				$data.= "<tr>";
				$data.= "<td valign='top'><a href='$swf_url' target=\"_blank\" class='set1' title='$brand_name' >". substr($brand_name,0,30) ."</a></td>";
				$data.= "<td valign='top'>". $ad_size .	" Page </td>";
				$data.= "<td valign='top'>". $Media_House_List .	" </td>";		
				$data.= "<td valign='top'>". $page."</td>";
				$data.= "<td  valign='top'>".$date_formated. $inc_month." ".$inc_year. "</td>";
				$data.= "</tr>";

        	}
        	$data.='</table>';
			// return $data;
        }

		$data_array['data'] = $data;
		$data_array['files'] = $filearray;
		return $data_array;
	}

	/** 
	*
	* @return  Return Print Table Head
	* @throws  InvalidArgumentException
	* @todo    Use This Function to Create Print Table Head
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function PrintTableHead(){
		$tablehead = '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';	
		$tablehead.= "<thead>
		<th width='23%'><strong>Brand Name</strong></th>
		<th width='40%'><strong>Ad Size</strong></th>
		<th width='15%'><strong>Media House</strong></th>
		<th width='10%'><strong>Page</strong></th>
		<th width='12%'><strong>Date first appeared</strong></th></thead>";
		return $tablehead;
	}
}