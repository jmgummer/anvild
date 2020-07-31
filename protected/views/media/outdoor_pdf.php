<?php
/**
* Outdoor PDF File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version     v.1.0
* @since       July 2008
*/
$anvil_header = Yii::app()->request->baseUrl . '/images/anvil_header.jpg';
echo '<img src="'.$anvil_header.'" width="100%" />';
echo '<h4>Outdoor Channel Report</h4>';
echo '<h4>Company : '.$company_name.'</h4>';
echo '<h4>Outdoor Channel : '.$channel.'</h4>';
echo '<h4>Generated Date : '.date("Y-m-d").'</h4>';
echo '<h4>Brand : '.$my_brand_name.'</h4>';
echo $title;
echo '<br><hr><br>';

$counter = 1;
echo '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
echo "<tr valign='top'   bgcolor='#ffffff'>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>#</font></strong></td>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>SITE</font></strong></td>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>REGION</font></strong></td>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>TYPE</font></strong></td>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>TOWN</font></strong>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>DATE</font></strong></td>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>TIME</font></strong></td>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>COMMENTS</font></strong></td>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>MEN</font></strong></td>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>WOMEN</font></strong></td>
<td ><strong><font face='Arial, Helvetica, sans-serif' size='-3'>CHILDREN</font></strong></td></tr>";
foreach ($sql_top_run as $key_main_run) {
    $my_company_name=$key_main_run["company_name"];
    $brand_name=$key_main_run["brand_name"];
    $inspection_date=$key_main_run["inspection_date"];
    $inspection_time=$key_main_run["inspection_time"];
    $quality=$key_main_run["quality"];
    $comment=$key_main_run["comment"];
    $inspector_name=$key_main_run["inspector_name"];
    $audience_male=$key_main_run["audience_male"];
    $audience_female=$key_main_run["audience_female"];
    $audience_children=$key_main_run["audience_children"];
    $entry_type=$key_main_run["entry_type"];
    $length=$key_main_run["length"];
    $incantation_id=$key_main_run["incantation_id"];
    $site_name=$key_main_run["site_name"];
    $site_location=$key_main_run["site_location"];
    $site_town=$key_main_run["town_name"];
    $site_type=$key_main_run["site_type"];
    $site_province=$key_main_run["province_name"];

    echo  "<tr  valign='top'  bgcolor='#f2f2f2' >
    <td ><font color='black'>". $counter."</font></td>
    <td ><font color='black'>". $site_name."</font></td>
    <td ><font color='black'>". $site_province."</font></td>
    <td ><font color='black'>". $site_type."</font></td>
    <td ><font color='black'>". substr($site_town,0,10)."</font></td>
    <td ><font color='black'>". $inspection_date."</font></td>
    <td ><font color='black'>". $inspection_time."</font></td>
    <td ><font color='black'>". $comment."</font></td>
    <td ><font color='black'>". $audience_male."</font></td>
    <td ><font color='black'>". $audience_female."</font></td>
    <td ><font color='black'>". $audience_children."</font></td><tr>";
    $x++;
    $counter++;
}
echo "</table><br><hr size='1'>";
?>