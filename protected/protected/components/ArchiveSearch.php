<?php
    // Initialize Empty Variables
    $todaysdate=date("Y-m-d");
    $search = 'kws';
    $query = '';
    $full_query = '';
    $media_code = '';



    if($search){
        $display_search=$search=trim($search);
        $search=str_replace("  "," ",$search);  $search=str_replace("  "," ",$search);
        $strings=explode(" ",$search);
        $search_strings= count($strings);
        $search2=str_replace(" ","%",trim($search));
        $search2=' or fulltxt like "%'. $search2 . '%"';
        $search=' ( fulltxt like "% '.$search . ' %" '.$search2.')';
    }

    // Formating Dates for Search

    $year_start = date('Y');$month_start = date('m');$day_start = date('d');
    $year_end = date('Y');$month_end = date('m');$day_end = date('d');

    $mysearch=$search;

    $beginning=$year_start . "-" . $month_start . "-" . $day_start;
    $ending=$year_end . "-" . $month_end . "-" . $day_end;
    $post_date='2';
    $start_date=mktime(0,0,0,$month_start,$day_start,$year_start) ;
    $end_date=mktime(0,0,0,$month_end,$day_end,$year_end) ;

    $display_keywords='';
    $add_my_query = 0;
    $temp_table="tempstories_" .date("Ymdhis");

    // Create The Temporary Table
    // $sql='CREATE TEMPORARY TABLE  '.$temp_table.' (`auto` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`link_id` INT NOT NULL,fulltxt mediumtext ,url varchar(255),indexdate date, UNIQUE (`link_id`)) ENGINE = MYISAM ;';

    //This is where we start the search

    //first we get MY keywords
    $sql_mykeywords = Company::model()->find('company_id=:a', array(':a'=>Yii::app()->user->company_id));
    $my_keywords=$sql_mykeywords->keywords;
    $my_subs=trim($sql_mykeywords->subs);
    if($my_keywords) {
        $add_my_query++;
    }

    $words=$my_keywords;
    $my_keywords=str_replace(", ",",",$my_keywords);
    $all_keywords=explode(",",$words);
    $all_keywords=array_filter($all_keywords);
    $all_my_keywords=array_unique($all_keywords);
    $all_my_keywords=array_unique($all_my_keywords);
    $my_keyword_count=count($all_keywords);
    // print_r($all_my_keywords);

    for($x=0; $x<=$my_keyword_count; $x++ ) 
    {
        // Since some of the array elements are being taken away by unique, check if exists first
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


    // $full_query=$full_query . ' and indexdate>"'.$backdate.'"';

    // for($x=0; $x<=$number_of_days; $x++)
    // {
    //     $offset=$x*60*60*24;
    //     $day_from_start=$start_date+ $offset;
    //     $this_year=date("Y",mktime(0, 0, 0, $month_start, $day_start+$x, $year_start, 0));
    //     $this_month=date("m",mktime(0, 0, 0, $month_start, $day_start+$x, $year_start, 0));
    //     $this_day=date("d",mktime(0, 0, 0, $month_start, $day_start+$x, $year_start, 0));
    //     $url_query.=" url like '%$this_year$this_month$this_day%'  or ";
    // }

    $url_query=" indexdate between '" . $year_start."-".$month_start."-".$day_start . "' and '" . $year_end."-".$month_end."-".$day_end ."' ";

    if($url_query){
        $url_query=" and (" . $url_query. " ) ";
        $full_query=$full_query . $url_query;
        // $full_query2=$full_query2 . $url_query;
    }

    if($mysearch || $search){
        $full_query=$full_query . " and " . $search ;
        // $full_query2=$full_query2 . " and " . $search ;
    }

    if($media_code){
        $full_query=$full_query . " and url like '%" . $media_code . "_%' ";
        // $full_query2=$full_query2 . " and url like '%" . $media_code . "_%' ";
    }

    echo '<br><hr>'.$full_query;
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
        $publication = Mediahouse::model()->find('media_code=:a', array(':a'=>$media_code))->Media_House_List;
    }


    $narrative='Searched <strong>'.$publication.'</strong> for <strong>';
    if($display_search) {$narrative.=$display_search.",";}
    $narrative.=" " .$display_keywords;
    $narrative.="</strong> between " .$display_date;

    echo '<br><hr>'.$narrative;

    for ($x=$year_start;$x<=$year_end;$x++)
    {
        if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
        if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

        $month_start_count=$month_start_count+0;

        for ($y=$month_start_count;$y<=$month_end_count;$y++)
        {
            if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
            $temp_table_month="sph_links_"  .$x."_".$my_month;

            echo $sql=" insert into $temp_table  (link_id, fulltxt, url,indexdate) select  link_id, fulltxt, url,indexdate from $temp_table_month  where url IS NOT NULL " . $full_query  . " order by url desc";
            // $my_query= mysql_query($sql,$db);
        }
    }

// $sql_no='SELECT  count(distinct(url)) as no_of_pages from '.$temp_table.' where  MATCH(title,fulltxt) AGAINST ('.$my_query_words.' IN BOOLEAN MODE ) '. $full_query2;
// $sql_no='SELECT  count(distinct(url)) as no_of_pages from $temp_table ';
// $query_no=mysql_query($sql_no, $db);
// $results_no=mysql_fetch_array($query_no);
// $this_num_results=$no_of_pages=$results_no["no_of_pages"];
// $no_of_pages=ceil($no_of_pages/20);

// $pages="";

// for($x=1;$x<=$no_of_pages;$x++) {
//     if(!$this_page){$this_page=1;}
//     if($x==$this_page) { 
//      $pages.='<strong><a href="sort_filter_cl3.php?this_page=$x&search=$display_search&media_code=$media_code&year_start=$year_start&month_start=$month_start&day_start=$day_start&year_end=$year_end&month_end=$month_end&day_end=$day_end">' . $x . '</a></strong> | '; 
//  } else{
//      $show=0;
//      if($x>$this_page  && ($x-$this_page)<5 ) { $show=1;}
//      if($this_page>$x  && ($this_page-$x)<5 ) { $show=1;}
//      if($show){
//          $pages.='<a href="sort_filter_cl3.php?this_page=$x&search=$display_search&media_code=$media_code&year_start=$year_start&month_start=$month_start&day_start=$day_start&year_end=$year_end&month_end=$month_end&day_end=$day_end">' . $x . '</a> | ';
//      }
//  }
// }
// $pages=substr($pages,0,-2);
// $page_limit=($this_page-1)*20;
// if($page_limit<0) {$page_limit=0;}
// $sql_industry='SELECT  distinct(url), link_id, fulltxt from $temp_table where MATCH(title,fulltxt) AGAINST ($my_query_words IN BOOLEAN MODE ) ' . $full_query2  . ' order by url desc limit $page_limit,20';


// $sql_industry='SELECT  distinct(url), link_id, fulltxt from $temp_table order by url desc limit $page_limit,20';
// $query_query=mysql_query($sql_industry, $db);

//         if($this_num_results > 0){
//                 $link=1;
//                 $story_type="Print Stories";
//                 // include("results_display_table_voodoo.php");
//         }  else {

//         echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='2' class='main_box_content'><tr bgcolor='#ffffff'>
//         <td><strong>No Results Found</strong></td> </tr></table>";


//         }







    ?>