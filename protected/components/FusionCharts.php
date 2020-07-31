<?php
/**
* FusionCharts Component Class
* This Class Is used to Generate to facilitate working with FusionCharts
* This page contains functions that can be used to render FusionCharts.
* DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
* 
* @package     Anvil
* @subpackage  Components
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version     v.1.0
* @since       July 2008
*/

class FusionCharts{
// encodeDataURL function encodes the dataURL before it's served to FusionCharts.
// If you've parameters in your dataURL, you necessarily need to encode it.
// Param: $strDataURL - dataURL to be fed to chart
// Param: $addNoCacheStr - Whether to add aditional string to URL to disable caching of data
public static function encodeDataURL($strDataURL, $addNoCacheStr=false) {
    //Add the no-cache string if required
    if ($addNoCacheStr==true) {
        // We add ?FCCurrTime=xxyyzz
        // If the dataURL already contains a ?, we add &FCCurrTime=xxyyzz
        // We replace : with _, as FusionCharts cannot handle : in URLs
        if (strpos($strDataURL,"?")<>0)
            $strDataURL .= "&FCCurrTime=" . Date("H_i_s");
        else
            $strDataURL .= "?FCCurrTime=" . Date("H_i_s");
    }
    // URL Encode it
    return urlencode($strDataURL);
}


// datePart function converts MySQL database based on requested mask
// Param: $mask - what part of the date to return "m' for month,"d" for day, and "y" for year
// Param: $dateTimeStr - MySQL date/time format (yyyy-mm-dd HH:ii:ss)
public static function datePart($mask, $dateTimeStr) {
    @list($datePt, $timePt) = explode(" ", $dateTimeStr);
    $arDatePt = explode("-", $datePt);
    $dataStr = "";
    // Ensure we have 3 parameters for the date
    if (count($arDatePt) == 3) {
        list($year, $month, $day) = $arDatePt;
        // determine the request
        switch ($mask) {
        case "m": return $month;
        case "d": return $day;
        case "y": return $year;
        }
        // default to mm/dd/yyyy
        return (trim($month . "/" . $day . "/" . $year));
    }
    return $dataStr;
}

public static function StripExtra($text)
    {
        $limit = 25;
        $content = $text;
        if (strlen($content) > $limit){
            return $content = substr($text, 0, strrpos(substr($text, 0, $limit), ' '));
        }else{
            return $content;
        }
        
    }

// Simple Function To Package Data to XML
public static function packageXML($narrative, $co_name,$others, $co_value, $other_value)
{
    $currency =Common::CountryCurrency(Yii::app()->user->country_id);
    $strXML = "<chart bgAlpha='0,0' canvasBgAlpha='0' caption='".$narrative."' xAxisName='Month' yAxisName='Units'>";
    $strXML .= "<set label='".$co_name."' value='".$co_value."' />";
    $strXML .= "<set label='".$others."' value='".$other_value."' />";
    $strXML .= "</chart>";
    return $strXML;
}

// Simple Function to Package Column Data
public static function packageColumnXML($narrative,$tv,$radio,$print,$total,$xAxisName,$yAxisName)
{
    $currency =Common::CountryCurrency(Yii::app()->user->country_id);
    $strXML = "<chart bgAlpha='0,0' canvasBgAlpha='0' caption='".$narrative."' xAxisName='".$xAxisName."' yAxisName='".$yAxisName."'>";
    $strXML .= "<set label='TV' value='".$tv."' />";
    $strXML .= "<set label='Radio' value='".$radio."' />";
    $strXML .= "<set label='Print' value='".$print."' />";
    $strXML .= "<set label='Total' value='".$total."' />";
    $strXML .= "</chart>";
    return $strXML;
}

public static function packageMentionsXML($narrative, $array,$company_name, $startdate,$enddate,$industry,$backdate)
{
    $currency =Common::CountryCurrency(Yii::app()->user->country_id);
    $strXML = "<chart bgAlpha='0,0' canvasBgAlpha='0' caption='".$narrative."' xAxisName='Month' yAxisName='Units'>";
    foreach ($array as $key) {
        $co_name2 = $key->Client;
        $co_value2 = IndustryQueries::GetShareVoiceCount($key->client_id,$startdate,$enddate,$industry,$backdate);
        $strXML .= "<set label='".FusionCharts::StripExtra($co_name2)."' value='".$co_value2."' />";
    }
    $companyvalue=IndustryQueries::GetShareVoiceCount(Yii::app()->user->company_id,$startdate,$enddate,$industry,$backdate);
    $strXML .= "<set label='".$company_name."' value='".$companyvalue."' />";
    $strXML .= "</chart>";
    return $strXML;
}

public static function packageAnvilAirplayXML($narrative, $array,$company_name)
{
    $currency =Common::CountryCurrency(Yii::app()->user->country_id);
    $strXML = "<chart bgAlpha='0,0' canvasBgAlpha='0' caption='".$narrative."' xAxisName='Company' yAxisName='$currency' showValues='0'    formatNumberScale='0' showBorder='1'>";
    $x=0;
    $other=0;
    foreach ($array as $key) {
        $co_name2 = $key['company_name'];
        $co_value2 = $key['rate'];
        $this_rate=BillBoardForm::FormattedTime($key['rate']);
        $x++;
        if($x<20){
            $strXML .= "<set label='".FusionCharts::StripExtra($co_name2)." ($this_rate)' value='".$co_value2."' />";
        }else{
            $other=$other+$this_rate;
        }
    }
    if($other) {
        $strXML .= "<set label='Other(".$other.")' value='" . $other ."' />";
    }
    $strXML .= "</chart>";
    return $strXML;
}

public static function packageAnvilAirplayBrandXML($narrative, $array)
{
    $currency =Common::CountryCurrency(Yii::app()->user->country_id);
    $strXML = "<chart bgAlpha='0,0' canvasBgAlpha='0' caption='".$narrative."' xAxisName='Company' yAxisName='$currency' showValues='0'    formatNumberScale='0' showBorder='1'>";
    $x=0;
    $other=0;
    foreach ($array as $key) {
        $co_name2 = $key['brand_name'];
        $co_value2 = $key['rate'];
        $this_rate=BillBoardForm::FormattedTime($key['rate']);
        $x++;
        if($x<20){
            $strXML .= "<set label='".FusionCharts::StripExtra($co_name2)." ($this_rate)' value='".$co_value2."' />";
        }else{
            $other=$other+$this_rate;
        }
    }
    if($other) {
        $strXML .= "<set label='Other(".$other.")' value='" . $other ."' />";
    }
    $strXML .= "</chart>";
    return $strXML;
}

public static function packageAnvilCompetitorXML($narrative, $array)
{
    $currency =Common::CountryCurrency(Yii::app()->user->country_id);
    $strXML = "<chart bgAlpha='0,0' canvasBgAlpha='0' caption='".$narrative."' xAxisName='Company' yAxisName='$currency' showValues='0'    formatNumberScale='0' showBorder='1'>";
    $x=0;
    $other=0;
    foreach ($array as $key) {
        $co_name2 = $key['company_name'];
        $co_value2 = $key['count'];
        
        if($x<20){
            $strXML .= "<set label='".FusionCharts::StripExtra($co_name2)." ($co_value2)' value='".$co_value2."' />";
        }else{
            $other=$other+$co_value2;
        }
        $x++;
    }
    // if($other) {
    //     $strXML .= "<set label='Other(".$other.")' value='" . $other ."' />";
    // }
    $strXML .= "</chart>";
    return $strXML;
}

public static function packageAnvilCompetitorSpendXML($narrative, $array,$netspend,$format)
{
    $currency =Common::CountryCurrency(Yii::app()->user->country_id);
    if($format=='p'){
        $strXML = "<chart bgAlpha='0,0' canvasBgAlpha='0' caption='".$narrative."' xAxisName='Company' yAxisName='$currency' showValues='0'  showPercentValues='0' showPercentInToolTip='0' formatNumberScale='0' showBorder='1'>";
    }else{
        $strXML = "<chart bgAlpha='0,0' canvasBgAlpha='0' caption='".$narrative."' xAxisName='Company' yAxisName='$currency' showValues='0'  showPercentValues='0'  formatNumberScale='0' showBorder='1'>";
    }
    
    $x=0;
    $other=0;
    foreach ($array as $key) {
        $co_name2 = $key['company_name'];
        $co_value = $key['totalspend'];
        $co_valueformated = number_format($co_value);
	if($netspend>0){
            $co_value2 = ($key['totalspend']/$netspend)*100;
        }else{
            $co_value2 = 0;
        }
	/*
        $co_value2 = ($key['totalspend']/$netspend)*100;
        $co_value2 = round($co_value2, 2);
	*/
        
        if($x<20){
            if($format=='p'){
                $strXML .= "<set label='".FusionCharts::StripExtra($co_name2)." ($co_value2 %)' value='".$co_value."' />";
            }else{
                $strXML .= "<set label='".FusionCharts::StripExtra($co_name2)." ($co_valueformated)' value='".$co_value2."' />";
            }
        }else{
            $other=$other+$co_value2;
        }
        $x++;
    }
    // if($other) {
    //     $strXML .= "<set label='Other(".$other.")' value='" . $other ."' />";
    // }
    $strXML .= "</chart>";
    return $strXML;
}

public static function packagePrintBrandSpendXML($narrative, $array,$netspend,$format)
{
    $currency =Common::CountryCurrency(Yii::app()->user->country_id);
    if($format=='p'){
        $strXML = "<chart bgAlpha='0,0' canvasBgAlpha='0' caption='".$narrative."' xAxisName='Company' yAxisName='$currency' showValues='0'  showPercentValues='0' showPercentInToolTip='0' formatNumberScale='0' showBorder='1'>";
    }else{
        $strXML = "<chart bgAlpha='0,0' canvasBgAlpha='0' caption='".$narrative."' xAxisName='Company' yAxisName='$currency' showValues='0'  showPercentValues='0'  formatNumberScale='0' showBorder='1'>";
    }
    
    $x=0;
    $other=0;
    foreach ($array as $key) {
        $co_name2 = $key['brand_name'];
        $co_value = $key['totalspend'];
        $co_valueformated = number_format($co_value);
        if($netspend>0){
            $co_value2 = ($key['totalspend']/$netspend)*100;
        }else{
            $co_value2 = 0;
        }
        
        $co_value2 = round($co_value2, 2);
        
        if($x<20){
            if($format=='p'){
                $strXML .= "<set label='".FusionCharts::StripExtra($co_name2)." ($co_value2 %)' value='".$co_value."' />";
            }else{
                $strXML .= "<set label='".FusionCharts::StripExtra($co_name2)." ($co_valueformated)' value='".$co_value2."' />";
            }
        }else{
            $other=$other+$co_value2;
        }
        $x++;
    }
    // if($other) {
    //     $strXML .= "<set label='Other(".$other.")' value='" . $other ."' />";
    // }
    $strXML .= "</chart>";
    return $strXML;
}

public static function packageBrandCompetitorXML($narrative, $array)
{
    $currency =Common::CountryCurrency(Yii::app()->user->country_id);
    $strXML = "<chart bgAlpha='0,0' canvasBgAlpha='0' caption='".$narrative."' xAxisName='Company' yAxisName='$currency' showValues='0'    formatNumberScale='0' showBorder='1'>";
    $x=0;
    $other=0;
    foreach ($array as $key) {
        $co_name2 = $key['brand_name'];
        $co_value2 = $key['count'];
        
        if($x<20){
            $strXML .= "<set label='".FusionCharts::StripExtra($co_name2)." ($co_value2)' value='".$co_value2."' />";
        }else{
            $other=$other+$co_value2;
        }
        $x++;
    }
    // if($other) {
    //     $strXML .= "<set label='Other(".$other.")' value='" . $other ."' />";
    // }
    $strXML .= "</chart>";
    return $strXML;
}

public static function packageAnvilCompetitorPercentageXML($narrative, $array)
{
    $currency =Common::CountryCurrency(Yii::app()->user->country_id);
    $strXML = "<chart bgAlpha='0,0' canvasBgAlpha='0' caption='".$narrative."' xAxisName='Company' yAxisName='$currency' showValues='0'    formatNumberScale='0' showBorder='1'>";
    $x=0;
    $other=0;
    foreach ($array as $key) {
        $co_name2 = $key['company_name'];
        $co_value2 = $key['count'];
        $x++;
        if($x<20){
            $strXML .= "<set label='".FusionCharts::StripExtra($co_name2)." ($co_value2)' value='".$co_value2."' />";
        }else{
            $other=$other+$this_rate;
        }
    }
    if($other) {
        $strXML .= "<set label='Other(".$other.")' value='" . $other ."' />";
    }
    $strXML .= "</chart>";
    return $strXML;
}

public static function packageAVEMentionsXML($narrative, $array,$company_name, $startdate,$enddate,$industry,$backdate)
{
    $strXML = "<chart bgAlpha='0,0' canvasBgAlpha='0' caption='".$narrative."' xAxisName='Month' yAxisName='Units'>";
    foreach ($array as $key) {
        $co_name2 = $key->Client;
        $co_value2 = IndustryQueries::GetCompanyAve($key->client_id,$startdate,$enddate,$industry,$backdate);
        $strXML .= "<set label='".FusionCharts::StripExtra($co_name2)."' value='".$co_value2."' />";
    }
    $companyvalue=IndustryQueries::GetCompanyAve(Yii::app()->user->company_id,$startdate,$enddate,$industry,$backdate);
    $strXML .= "<set label='".$company_name."' value='".$companyvalue."' />";
    $strXML .= "</chart>";
    return $strXML;
}

public static function packageCATMentionsXML($narrative, $array, $startdate,$enddate,$industry,$backdate)
{
    $strXML = "<chart bgAlpha='0,0' canvasBgAlpha='0' caption='".$narrative."' xAxisName='Month' yAxisName='Units'>";
    foreach ($array as $key) {
        if($number = IndustryQueries::GetCatCount(Yii::app()->user->company_id,$startdate,$enddate,$industry,$key->Category_ID,$backdate) > 0){
            $cat_name = $key->Category_List;
            $cat_value = IndustryQueries::GetCatCount(Yii::app()->user->company_id,$startdate,$enddate,$industry,$key->Category_ID,$backdate);
            $strXML .= "<set label='".$cat_name."' value='".$cat_value."' />";
        }
    }

    $strXML .= "</chart>";
    return $strXML;
}

public static function packagePICMentionsXML($narrative, $array, $startdate,$enddate,$industry,$backdate)
{
    $strXML = "<chart bgAlpha='0,0' canvasBgAlpha='0' caption='".$narrative."' xAxisName='Month' yAxisName='Units'>";
    foreach ($array as $key) {
        $pic_name = $key->picture;
        $pic_value = IndustryQueries::GetPicCount(Yii::app()->user->company_id,$startdate,$enddate,$industry,$key->picture,$backdate);
        $strXML .= "<set label='".$pic_name."' value='".$pic_value."' />";
    }

    $strXML .= "</chart>";
    return $strXML;
}

public static function packageTonMentionsXML($narrative, $array, $startdate,$enddate,$industry,$backdate)
{
    $strXML = "<chart bgAlpha='0,0' canvasBgAlpha='0' caption='".$narrative."' xAxisName='Month' yAxisName='Units'>";
    foreach ($array as $key) {
        $ton_name = $key->tonality;
        $ton_value = IndustryQueries::GetSpTonality(Yii::app()->user->company_id,$startdate,$enddate,$industry,$key->tonality,$backdate);
        $strXML .= "<set label='".$ton_name."' value='".$ton_value."' />";
    }

    $strXML .= "</chart>";
    return $strXML;
}

// renderChart renders the JavaScript + HTML code required to embed a chart.
// This function assumes that you've already included the FusionCharts JavaScript class
// in your page.

// $chartSWF - SWF File Name (and Path) of the chart which you intend to plot
// $strURL - If you intend to use dataURL method for this chart, pass the URL as this parameter. Else, set it to "" (in case of dataXML method)
// $strXML - If you intend to use dataXML method for this chart, pass the XML data as this parameter. Else, set it to "" (in case of dataURL method)
// $chartId - Id for the chart, using which it will be recognized in the HTML page. Each chart on the page needs to have a unique Id.
// $chartWidth - Intended width for the chart (in pixels)
// $chartHeight - Intended height for the chart (in pixels)
// $debugMode - Whether to start the chart in debug mode
// $registerWithJS - Whether to ask chart to register itself with JavaScript
public static function renderChart($chartSWF, $strURL, $strXML, $chartId, $chartWidth, $chartHeight, $debugMode=false, $registerWithJS=false, $setTransparent="") {
    //First we create a new DIV for each chart. We specify the name of DIV as "chartId"Div.
    //DIV names are case-sensitive.

    // The Steps in the script block below are:
    //
    //  1)In the DIV the text "Chart" is shown to users before the chart has started loading
    //    (if there is a lag in relaying SWF from server). This text is also shown to users
    //    who do not have Flash Player installed. You can configure it as per your needs.
    //
    //  2) The chart is rendered using FusionCharts Class. Each chart's instance (JavaScript) Id
    //     is named as chart_"chartId".
    //
    //  3) Check whether we've to provide data using dataXML method or dataURL method
    //     save the data for usage below
    if ($strXML=="")
        $tempData = "//Set the dataURL of the chart\n\t\tchart_$chartId.setDataURL(\"$strURL\")";
    else
        $tempData = "//Provide entire XML data using dataXML method\n\t\tchart_$chartId.setDataXML(\"$strXML\")";

    // Set up necessary variables for the RENDERCAHRT
    $chartIdDiv = $chartId . "Div";
    $ndebugMode = FusionCharts::boolToNum($debugMode);
    $nregisterWithJS = FusionCharts::boolToNum($registerWithJS);
    $nsetTransparent=($setTransparent?"true":"false");


    // create a string for outputting by the caller
$render_chart = <<<RENDERCHART

    <!-- START Script Block for Chart $chartId -->
    <div id="$chartIdDiv" align="center">
        Chart.
    </div>
    <script type="text/javascript">
        //Instantiate the Chart
        var chart_$chartId = new FusionCharts("$chartSWF", "$chartId", "$chartWidth", "$chartHeight", "$ndebugMode", "$nregisterWithJS");
      chart_$chartId.setTransparent("$nsetTransparent");
    
        $tempData
        //Finally, render the chart.
        chart_$chartId.render("$chartIdDiv");
    </script>
    <!-- END Script Block for Chart $chartId -->
RENDERCHART;

  return $render_chart;
}


//renderChartHTML function renders the HTML code for the JavaScript. This
//method does NOT embed the chart using JavaScript class. Instead, it uses
//direct HTML embedding. So, if you see the charts on IE 6 (or above), you'll
//see the "Click to activate..." message on the chart.
// $chartSWF - SWF File Name (and Path) of the chart which you intend to plot
// $strURL - If you intend to use dataURL method for this chart, pass the URL as this parameter. Else, set it to "" (in case of dataXML method)
// $strXML - If you intend to use dataXML method for this chart, pass the XML data as this parameter. Else, set it to "" (in case of dataURL method)
// $chartId - Id for the chart, using which it will be recognized in the HTML page. Each chart on the page needs to have a unique Id.
// $chartWidth - Intended width for the chart (in pixels)
// $chartHeight - Intended height for the chart (in pixels)
// $debugMode - Whether to start the chart in debug mode
public static function renderChartHTML($chartSWF, $strURL, $strXML, $chartId, $chartWidth, $chartHeight, $debugMode=false,$registerWithJS=false, $setTransparent="") {
    // Generate the FlashVars string based on whether dataURL has been provided
    // or dataXML.
    $strFlashVars = "&chartWidth=" . $chartWidth . "&chartHeight=" . $chartHeight . "&debugMode=" . boolToNum($debugMode);
    if ($strXML=="")
        // DataURL Mode
        $strFlashVars .= "&dataURL=" . $strURL;
    else
        //DataXML Mode
        $strFlashVars .= "&dataXML=" . $strXML;
    
    $nregisterWithJS = boolToNum($registerWithJS);
    if($setTransparent!=""){
      $nsetTransparent=($setTransparent==false?"opaque":"transparent");
    }else{
      $nsetTransparent="window";
    }
$HTML_chart = <<<HTMLCHART
    <!-- START Code Block for Chart $chartId -->
    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="$chartWidth" height="$chartHeight" id="$chartId">
        <param name="allowScriptAccess" value="always" />
        <param name="movie" value="$chartSWF"/>
        <param name="FlashVars" value="$strFlashVars&registerWithJS=$nregisterWithJS" />
        <param name="quality" value="high" />
        <param name="wmode" value="$nsetTransparent" />
        <embed src="$chartSWF" FlashVars="$strFlashVars&registerWithJS=$nregisterWithJS" quality="high" width="$chartWidth" height="$chartHeight" name="$chartId" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="$nsetTransparent" />
    </object>
    <!-- END Code Block for Chart $chartId -->
HTMLCHART;

  return $HTML_chart;
}

// boolToNum function converts boolean values to numeric (1/0)
public static function boolToNum($bVal) {
    return (($bVal==true) ? 1 : 0);
}

}
?>
