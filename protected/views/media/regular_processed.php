<?php
/**
* Print Processed File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version     v.1.0
* @since       July 2008
*/
$level = Yii::app()->user->company_level;
$company_id = $this_company_id = Yii::app()->user->company_id;

$this->pageTitle=Yii::app()->name.' | Proof of Print';
$this->breadcrumbs=array('Proof of Print'=>array('media/regular'));

/* Print The Top */
echo '<h3>Proof of Flight Report</h3>';

/*if(Yii::app()->user->country_code=='KE'){
    $linkurl = 'beta';
}else{
    $linkurl = strtolower(Yii::app()->user->country_code);
}*/
$linkurl = Yii::app()->params['eleclink'];

/* Process the dates to mysql types */

$enddate = date('Y-m-d', strtotime($_POST['enddate']));
$startdate = date('Y-m-d', strtotime($_POST['startdate']));

/* Process the Ad Types */
$adtypes = array();

if(isset($_POST['entry_type'])){
    $adtype = $_POST['entry_type'];
    if($adtype==0){
        $entry_type_query = ' ';
    }else{
        $entry_type_query = ' and entry_type = '.$adtype;
    }
}else{
    $error_code = 1;
}

/* Process the brands */
$brands = array();

if(isset($_POST['brands']) && !empty($_POST['brands'])){
    foreach ($_POST['brands'] as $key) {
      $brands[] = $key;
    }
    $set_brands = implode(', ', $brands);
    $brand_query = ' and print_table.brand_id IN ('.$set_brands.')';
}else{
    $error_code = 2;
}

/* 
** Handle Print Publications
*/

$print = array();

if(isset($_POST['print']) && !empty($_POST['print'])){
    foreach ($_POST['print'] as $key) {
      $print[] = $key;
    }
}

if(isset($_POST['print2']) && !empty($_POST['print2'])){
    foreach ($_POST['print2'] as $key) {
      $print[] = $key;
    }
}

if(isset($_POST['print3']) && !empty($_POST['print3'])){
    foreach ($_POST['print3'] as $key) {
      $print[] = $key;
    }
}

if(isset($_POST['print4']) && !empty($_POST['print4'])){
    foreach ($_POST['print4'] as $key) {
      $print[] = $key;
    }
}

if(isset($_POST['print5']) && !empty($_POST['print5'])){
    foreach ($_POST['print5'] as $key) {
      $print[] = $key;
    }
}

if(isset($_POST['print6']) && !empty($_POST['print6'])){
    foreach ($_POST['print6'] as $key) {
      $print[] = $key;
    }
}
$print_array = array_count_values($print);
if($print_array > 0){
    $set_print = implode(', ', $print);
    $this_media_code = ' and print_table.media_house_id IN  ('.$set_print.')';
}else{
    $error_code = 3;
}



/* If there are any errors terminate execution at this point and redirect back to the form */
if(isset($error_code)){
    Yii::app()->user->setFlash('warning', "<strong>Error ! Please select at least one from each section </strong>");
    $this->redirect(array('regular'));
}

/* Date Formating Starts Here */

$year_start     = date('Y',strtotime($startdate));  
$month_start    = date('m',strtotime($startdate));  
$day_start      = date('d',strtotime($startdate));
$year_end       = date('Y',strtotime($enddate)); 
$month_end      = date('m',strtotime($enddate)); 
$day_end        = date('d',strtotime($enddate));

/*
** Query Build
*/
if($level==3) {
   /* 
    ** Create SQL Statement for Agency 
    ** Select all records at a go and then handle the sieving later on
    */

} else {
    /* 
    ** Create SQL Statement for Company 
    ** Select all records at a go and then handle the sieving later on
    */

    $print_sql = "select
        mediahouse.Media_House_List, 
        print_table.media_house_id,
        print_table.auto_id as print_id, 
        print_table.file, 
        print_table.page, 
        print_table.col, 
        print_table.centimeter, 
        print_table.ave, 
        print_table.brand_id, 
        date as this_date,
        brand_table.brand_name as brand
        from
        mediahouse,
        print_table, brand_table where 
        brand_table.brand_id=print_table.brand_id and
        brand_table.company_id='$this_company_id' and
        print_table.media_house_id=mediahouse.media_house_id and
        date between '$startdate' and '$enddate'  $brand_query $this_media_code $entry_type_query order by date asc";
}

/* 
** Creating Temporary Table Starts Here
** USING TEMPORARY TABLE AS SELECT
** The Data has been already sorted out so there is no need for extra work
*/
$temp_table = Common::PrintTempTable();
$tempsql = "insert into $temp_table  (Media_House_List,media_house_id,print_id,file,page,col,centimeter,ave,brand_id,this_date,brand) $print_sql ";

// $temp_table="anvil_print_temp_" . date("Ymdhis");
// $tempsql = 'create temporary table if not exists '.$temp_table.' AS '.$print_sql;
if(Yii::app()->db3->createCommand($tempsql)->execute()){
    // echo '<p>success</p>';
}else{
    echo '<p><strong>No Data Exists for the Query</strong></p>';
}

/* 
** Data Grouping Begins Here
** This will be used to Group Data 
** Create an array to hold the station ID (@brandarray)
** Start Selecting the Brand Data based on the records that exist on the temporary table 
*/

$audio_icon = Yii::app()->request->baseUrl .'/images/play_icon.jpeg';
$video_icon = Yii::app()->request->baseUrl .'/images/vid_icon.jpg';

$brand_sql = 'select distinct '.$temp_table.'.brand_id, brand  
from '.$temp_table.' order by brand desc';

if($stored_brands = Yii::app()->db3->createCommand($brand_sql)->queryAll())
{
    /*
    ** Ladies and Gentlemen, the WORK BEGINS HERE !
    ** This section is divided into two to achieve Tabs
    ** If the Entry Type == 3 then its a caption which dont have files If the Station is a Radio Type then Just Give Mp3 files and icon
    ** If the Station is a TV type then check if it has a file first and get MpG file and Icon Else Mp3 files and Icon
    ** Start by Generating The Excel Workbook then PDF then Lastly Print Tables
    */

    /* 
    ** This Section is for Excel
    ** Use the Results for Excel Processing 
    */

    /* 
    ** This section if for the Tables for the Browser View 
    */

    $PHPExcel = new PHPExcel();

    $title = 'The following is a Proof of Flight Summary Report  the period: between '.$startdate.' and '.$enddate;

    /* 
    ** Set properties of the Excel Workbook 
    */

    $PHPExcel->getProperties()->setCreator("Reelforge Systems")
    ->setTitle("Reelforge Anvil Proof of Flight Reports")
    ->setSubject("Reelforge Anvil Proof of Flight Reports")
    ->setDescription("Reelforge Anvil Proof of Flight Reports");

    $sheet_index = 0;
    foreach ($stored_brands as $excel_brands) {
        $excel_brand_id     =   $excel_brands['brand_id'];
        $excel_brand_name   =   $excel_brands['brand'];
        $PHPExcel->createSheet(NULL, $sheet_index);
        $PHPExcel->setActiveSheetIndex($sheet_index)
        ->setCellValue('A1', 'Reelforge Proof of Flight Report')
        ->setCellValue('B1', ' ')
        ->setCellValue('C1', ' ')
        ->setCellValue('A2', 'Station : '.$excel_brand_name)
        ->setCellValue('B2', ' ')
        ->setCellValue('C2', ' ')
        ->setCellValue('A3', $title)
        ->setCellValue('B3', ' ')
        ->setCellValue('C3', ' ')
        ->setCellValue('A4', 'Client : '.Yii::app()->user->company_name)
        ->setCellValue('B4', ' ')
        ->setCellValue('C4', ' ')
        ->setCellValue('A5', ' ')
        ->setCellValue('B5', ' ')
        ->setCellValue('C5', ' ')
        ->setCellValue('A6', 'Ad Name')
        ->setCellValue('B6', 'Date')
        ->setCellValue('C6', 'Newspaper')
        ->setCellValue('D6', 'Page')
        ->setCellValue('E6', 'Rate');

        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);

        /* 
        ** Now to Add Values to the Spreadsheet
        ** Start from 7th row
        ** Pick Elements From The Array and Start Working The List
        */

        $count = 7;
        $styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));
        $temp_select1 = 'SELECT * FROM '.$temp_table.' WHERE brand_id='.$excel_brand_id.' order by this_date';
        if($excel_data = Yii::app()->db3->createCommand($temp_select1)->queryAll())
        {
            foreach ($excel_data as $elements) {
                $this_file = $elements['file'];
                $this_ave = $elements['ave'];
                $this_brand_name = $elements['brand'];
                $jurl = $linkurl."anvil/reports/print_story_console/print_stream.php?url=". $this_file . "&rate=$this_ave&ad_name=$this_brand_name";

                $PHPExcel->getActiveSheet()
                ->setCellValue("A$count", $elements['brand'])
                ->setCellValue("B$count", $elements['this_date'])
                ->setCellValue("C$count", $elements['Media_House_List'])
                ->setCellValue("D$count", $elements['page'])
                ->setCellValue("E$count", number_format($elements['ave']));
                $PHPExcel->getActiveSheet()->getCell("A$count")->getHyperlink()->setUrl($jurl);
                $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray);
                $PHPExcel->getActiveSheet()->getStyle("A$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
                $count++;
            }
            unset($styleArray);
        }
        /* 
        ** Rename sheet, Remove any HTML Tags, truncate string
        ** make sure it ends in a word so assassinate doesn't become ass...
        */
        $string = strip_tags($excel_brand_name);

        if (strlen($string) > 25) {
            $stringCut = substr($string, 0, 25);
            $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'... '; 
        }
        $PHPExcel->getActiveSheet()->setTitle($string);
        $sheet_index++;
    }
    // Set active sheet index to the right sheet, depending on the options,
    // so Excel opens this as the first sheet
    $PHPExcel->setActiveSheetIndex(0);

    $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
    /* Save to File */
    $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/pof/excel/";
    $filename =str_replace(" ","_",Yii::app()->user->company_name);
    $filename = date("Ymdhis").'_'.$filename.'pof.xls';
    $objWriter->save($upload_path.$filename);

    /* 
    ** PDF Time
    */

    $pdf = Yii::app()->ePdf2->Output2('pop_pdf',array('brands'=>$stored_brands,'temp_table'=>$temp_table));
    $filename_pdf =str_replace(" ","_",Yii::app()->user->company_name);
    $filename_pdf = date("Ymdhis").'_'.$filename_pdf.'pof.pdf';
    $location = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/pof/pdf/".$filename_pdf;
    if(file_put_contents($location, $pdf)){
        // echo '<p>PDF CREATED</p>';
    }else{
        // echo '<p>PDF FAILED</p>';
    }

?>
<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
    <header role="heading clearfix">
        <?php echo '<h2><span class="pull-left">The following is a Proof of Flight Report the period: '.$startdate.' and '.$enddate.'</span><span class="pull-right">'; ?>
        <a href="<?php echo Yii::app()->request->baseUrl . '/docs/pof/pdf/'.$filename_pdf; ?>" class="btn btn-danger btn-xs pull-right pdf-excel" target="_blank"><i class="fa fa-file-pdf-o"></i> PDF</a>
        <a href="<?php echo Yii::app()->request->baseUrl . '/docs/pof/excel/'.$filename; ?>" class="btn btn-success btn-xs pull-right pdf-excel" target="_blank"><i class="fa fa-file-excel-o"></i> EXCEL</a>
        </span></h2>
    </header>
</div>
<?php

    echo '<div class="widget-body">
    <ul id="tabs" class="nav nav-tabs bordered">';
    $first_element = $stored_brands[0];
    $active_tab = $first_element ['brand_id'];
    foreach ($stored_brands as $brand_header) {
        $tab_id = $brand_header['brand_id'];
        $tab_name = $brand_header['brand'];
        if($tab_id==$active_tab){
            echo '<li class="active"><a href="#'.$tab_id.'" data-toggle="tab">'.$tab_name.'</a></li>';
        }else{
            echo '<li><a href="#'.$tab_id.'" data-toggle="tab">'.$tab_name.'</a></li>';
        }
    }
    echo '</ul>';
    echo '<div id="myTabContent1" class="tab-content padding-10">';
    $grand_total = 0;
    foreach ($stored_brands as $found_brands) {
        $fbrand_id = $found_brands['brand_id'];
        $fbrand_name = $found_brands['brand'];
        $temp_select = 'SELECT * FROM '.$temp_table.' WHERE brand_id='.$fbrand_id.' order by this_date';
        
        if($data = Yii::app()->db3->createCommand($temp_select)->queryAll())
        {
            if($fbrand_id==$active_tab){
                echo '<div class="tab-pane fade active in" id="'.$fbrand_id.'">';
            }else{
                echo '<div class="tab-pane fade" id="'.$fbrand_id.'">';
            }
            echo '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
            echo '<thead><th>Ad Name</th><th>Date</th><th>Newspaper</th><th>Page</th><th>Rate(Kshs)</th></thead>';
            $sum = 0;
            foreach ($data as $result) {
                $this_file = $result['file'];
                $this_ave = $result['ave'];
                $this_brand_name = $result['brand'];
                $jurl = $linkurl."anvil/reports/print_story_console/print_stream.php?url=". $this_file . "&rate=$this_ave&ad_name=$this_brand_name";

                echo '<tr>';
                echo '<td>'.$result['brand'].'</td>';
                echo '<td>'.$result['this_date'].'</td>';
                echo '<td><a href="'.$jurl.'" target="_blank">'.$result['Media_House_List'].'</a></td>';
                echo '<td>'.$result['page'].'</td>';
                echo '<td>'.number_format($result['ave']).'</td>';
                echo '</tr>';
                $sum = $sum + $result['ave'];
            }
            echo '</table>';
            echo '<div class="row-fluid clearfix">';
            
            $total = count($data);
            echo '<p class="pull-left"><strong>Brand TOTAL ('.$fbrand_name.') | Total Number of Ads '.$total.'</strong></p>';
            echo '<p class="pull-right"><strong>'.number_format($sum).'</strong></p>';
            echo '</div>';
            echo '</div>';
            $grand_total = $grand_total + $sum;
        }

    }
    echo '<div class="row-fluid clearfix"><hr class="simple"></hr><p class="pull-left"><strong>Grand TOTAL : '.number_format($grand_total).'</strong></p></div>';
    echo '</div>';
    echo '</div>';
}
?>
    

<style type="text/css">
.fupisha { max-width: 250px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; }
.nav-tabs > li.active > a { box-shadow: 0px -2px 0px #DB4B39; }
#tabs { width: 100%; background-color: #CCC; }
.pdf-excel{margin: 5px 1px;}
</style>
