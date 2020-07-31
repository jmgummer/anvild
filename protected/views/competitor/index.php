<?php
/**
* Competitor File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/
$this->pageTitle=Yii::app()->name.' | Archive Reports';
$this->breadcrumbs=array('Archive Reports'=>array('csr/index'), 'Print Archive');
?>
<div class="row-fluid clearfix">
<div class="col-md-3">
	<?php $this->renderPartial('search_filter',array('model'=>$model)); ?>
</div>
<div class="col-md-9">
	<?php
	if(isset($_POST['StorySearch'])){
		$search = $model->search_text;
		$beginning = $model->startdate;
		$ending = $model->enddate;
		$media_house_id = $model->publications;
		// Initialize Empty Variables
		$query = '';
		$full_query = '';
		$media_code = '';

		if(!empty($search)){
			$display_search=$search=trim($search);
			$search=str_replace("  "," ",$search);  $search=str_replace("  "," ",$search);
			$strings=explode(" ",$search);
			$search_strings= count($strings);
			$search2=str_replace(" ","%",trim($search));
			$search2=' or fulltxt like "%'. $search2 . '%"';
			$search=' ( fulltxt like "% '.$search . ' %" '.$search2.')';
		}

		// Formating Dates for Search

		$year_start 	= date('Y',strtotime($beginning)); 	
		$month_start 	= date('m',strtotime($beginning)); 	
		$day_start 		= date('d',strtotime($beginning));
		$year_end 		= date('Y',strtotime($ending));	
		$month_end 		= date('m',strtotime($ending));	
		$day_end 		= date('d',strtotime($ending));

		$mysearch=$search;
		// I'm not very sure why Sammy Used 2 Here, I drink Monster, I suspect he has something waaa..y better, HEHEHE
		$post_date='2';

		$display_keywords='';

		//This is where we start the search

		//first we get the Company keywords
		$sql_mykeywords = Company::model()->find('company_id=:a', array(':a'=>Yii::app()->user->company_id));
		$backdate = $sql_mykeywords->backdate;
		$my_keywords=$sql_mykeywords->keywords;
		$my_subs=trim($sql_mykeywords->subs);

		$words=$my_keywords;
		$my_keywords=str_replace(", ",",",$my_keywords);
		$all_keywords=explode(",",$words);
		$all_keywords=array_filter($all_keywords);
		$all_my_keywords=array_unique($all_keywords);
		$all_my_keywords=array_unique($all_my_keywords);
		$my_keyword_count=count($all_keywords);
		// This Query is Used to Build the FullText Search Options, it grows quite big, depending on the size of the keywords
		for($x=0; $x<=$my_keyword_count; $x++) 
		{
			if(isset($all_my_keywords[$x])){
				if(trim($all_my_keywords[$x])){
			        $all_my_keywords[$x]=trim($all_my_keywords[$x]);
			        $display_keywords.=$all_my_keywords[$x] . ", ";
			        if(strlen($all_my_keywords[$x])>4) {
			            $query.=' fulltxt like "%'. trim($all_my_keywords[$x]) .'%" or ';	
			        }
			        if(strlen($all_my_keywords[$x])<=4) {
			            $query.=' fulltxt like "%'. trim($all_my_keywords[$x]) .', %" or ';
			            $query.=' fulltxt like "%'. trim($all_my_keywords[$x]) . '. %" or ';
			        }
			    }
			}
		}

		$display_keywords=trim($display_keywords);
		if(substr($display_keywords,-1)==","){
			$display_keywords=substr($display_keywords,0,-1);
		}

		if($query){
		    $query=' and (' . substr($query,0,-3) . ') ';
		    $full_query=$full_query . $query;
		}

		$url_query=" indexdate between '" . $beginning . "' and '" . $ending ."' ";
		$full_query=$full_query . " and indexdate>'$backdate'";

		if($url_query){
		    $url_query=" and (" . $url_query. " ) ";
		    $full_query=$full_query . $url_query;
		}

		if($mysearch || $search){
			$full_query=$full_query . " and " . $search ;
		}

		if(!empty($media_code)){
			$full_query=$full_query . " and url like '%" . $media_code . "_%' ";
		}

		if (!$post_date){
		    $this_i_date="and StoryDate between '".$td_yyyy."-".$td_mm."-".$td_dd."' and  '".$td_yyyy."-".$td_mm."-".$td_dd."' "  ;
		    $year_start=$td_yyyy;
		    $month_start=$td_mm;
		    $day_start=$td_dd;
		    $year_start=$td_yyyy;
		    $month_start=$td_mm;
		    $day_start=$td_dd;
		    $display_date=" <strong>".$td_yyyy."-".$td_mm."-".$td_dd."</strong> and <strong>".$td_yyyy."-".$td_mm."-".$td_dd  ."</strong>";
		}
		if ($post_date==1){
		    $display_date= " <strong>".$newdate."</strong> and <strong>".$todaysdate ."</strong>";
		}
		if ($post_date==2){
		    $display_date=" <strong>".$beginning."</strong> and <strong>".$ending ."</strong>";
		}
		// Get The Publication Name for The Narrative
		if(!$media_code) {
		    $publication= "All Publications";
		} else {
			$publication = Mediahouse::model()->find('media_house_id=:a', array(':a'=>$media_code))->Media_House_List;
		}


		$narrative='Searched <strong>'.$publication.'</strong> for <strong>';
		if(isset($display_search)) {
			$narrative.=$display_search.",";
		}
		$narrative.=" " .$display_keywords;
		$narrative.="</strong> between " .$display_date;

		// echo '<br><hr>'.$narrative;

		for ($x=$year_start;$x<=$year_end;$x++)
		{
		    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
		    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

		    $month_start_count=$month_start_count+0;

			for ($y=$month_start_count;$y<=$month_end_count;$y++)
			{
		        if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
		        $temp_table_month="sph_links_"  .$x."_".$my_month;
		        if(!empty($media_house_id)){
		        	$sql= "select  media_house_id, link_id, fulltxt, url,indexdate from $temp_table_month  where url IS NOT NULL " . $full_query  . " and media_house_id ='".$media_house_id."' order by url desc limit 10";
		        }else{
		        	$sql= "select  media_house_id, link_id, fulltxt, url,indexdate from $temp_table_month  where url IS NOT NULL " . $full_query  . " order by url desc limit 10";
		        }
		    }
		}
		$final_sql = $sql;

		if($stories = SphLinks::model()->findAllBySql($final_sql)){
			$record_count = count($stories);
			echo $record_count.' Records Found';
			foreach ($stories as $key) {
				$limit = 300;
			   	$content = $key->fulltxt;
			   	if (strlen($content) > $limit){
					$content = substr($content, 0, strrpos(substr($content, 0, $limit), ' '));
				}
				$format_date = date('D, F, Y', strtotime($key->indexdate));
				echo '<h3><a href="http://www.reelforge.com/reelmedia/print_story_console/print_stream.php?itemid='.$key->link_id.'" target="_blank">'.$key->Publication.'</a></h3>';
				echo '<p>'.$format_date.''.$key->Page.'</p>';
				echo '<p>'.$content.'</p>';
				echo '<p><a href="http://www.reelforge.com/reelmedia/print_story_console/print_stream.php?itemid='.$key->link_id.'" target="_blank">Read More</a> | <a href="'.$key->url.'" target="_blank">Download PDF</a></p>';
			}
		}else{
			echo 'No Records Found';
		}

		
	}else{
		echo '<h3>Print Archive</h3>';
		echo '<p>Newspaper/Print Archives – You may search our print archives here. Search results are limited to your subscription terms. Please call your client service representative for more information.</p>';
	}

	?>
</div>
</div>

<style type="text/css">
#content{
	min-height: 900px;
}
</style>