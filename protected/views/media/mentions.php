<?php
/**
* Mentions File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version     v.1.0
* @since       July 2008
*/
$this->pageTitle=Yii::app()->name.' | Industry Reports';
$this->breadcrumbs=array('Industry Reports'=>array('industryreports/index'), 'Number of Mentions'=>array('industryreports/mentions'));
?>
<script src="<?php echo Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/FusionCharts.js'; ?>"></script>
<script language="JavaScript" src="<?php echo Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/FusionChartsExportComponent.js'; ?>"></script>
<div class="row-fluid clearfix">
<div class="col-md-3">
<?php $this->renderPartial('search_filter',array('model'=>$model)); ?>
</div>
<div class="col-md-9">
<?php
$csql = 'SELECT backdate from company WHERE company_id ='.Yii::app()->user->company_id;
$company_words = Company::model()->findBySql($csql);
$backdate = $company_words->backdate;
  $report_identifier = array();
  $company = Yii::app()->user->company_name;
  $narrative = 'Number of '.$company.' stories in ';
  if(isset($_POST['StorySearch'])){
    $model->attributes=Yii::app()->input->stripClean($_POST['StorySearch']);
    $model->startdate = date('Y-m-d',strtotime(str_replace('-', '/', $model->startdate)));
    $model->enddate = date('Y-m-d',strtotime(str_replace('-', '/', $model->enddate)));

    $startdate = $model->startdate;
    $enddate = $model->enddate;
    $industry = implode(',', $model->industry);
    $inda=array();
    foreach ($model->industry as $key) {
      $indas = Industry::model()->find('Industry_ID=:a',array(':a'=>$key))->Industry_List;
      $inda[] = $indas;
    }
    $inda_text = implode(', ', $inda);
    $narrative .= $inda_text.' Between ';
    $drange = date('d-M-Y',strtotime(str_replace('-', '/', $startdate))).' and '.date('d-M-Y',strtotime(str_replace('-', '/', $enddate)));
    $narrative .= $drange;
    if(isset($model->industryreports) && !empty($model->industryreports)){
      foreach ($model->industryreports as $report_id) {
        $report_identifier[]= $report_id;
      }
    }
  }else{
    $report_identifier[] = 1;
    $industry = $model->industry;
    $startdate = $model->startdate;
    $enddate = $model->enddate;
    $indas = Industry::model()->find('Industry_ID=:a',array(':a'=>$industry))->Industry_List;
    $narrative .= $indas.' Between ';
    $drange = date('d-M-Y',strtotime(str_replace('-', '/', $startdate))).' and '.date('d-M-Y',strtotime(str_replace('-', '/', $enddate)));
    $narrative .= $drange;
  }
	
  foreach ($report_identifier as $repkey) {
    switch ($repkey) {
      /* Load the Mentions Report */
      case 1:
        $total = IndustryQueries::GetAllCompanyMentions($startdate,$enddate,$industry,$backdate);
        $ctotal = IndustryQueries::GetCompanyMentions(Yii::app()->user->company_id,$startdate,$enddate,$industry,$backdate);
        $ttotal = $total - $ctotal;
        echo '<h3>Number of Mentions</h3>';
        echo '<p>This simply gives an aggregate of the total number of stories that appeared in the media about your organisation or topic of interest being monitored. If the subscriber is interested in industry mentions, the report will aggregate the total number of stories for the industry and indicate which stories were about ´myself´ and how many were for the ´others´. The number of mentions is also reported by distribution by media-house.</p>';
        $chart_name = 'Number_of_Mentions';
        echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
        $strXML = FusionCharts::packageXML($narrative, $company, 'Others', $ctotal, $ttotal,$backdate);
        $charty = new FusionCharts;
        echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Pie2D.swf', "", $strXML, $chart_name, 600, 300, false, true, true);
        echo '</div>';
        break;
      /* Load the AVE Report */
      case 2:
        $avtotal = IndustryQueries::GetCompanyAve(Yii::app()->user->company_id,$startdate,$enddate,$industry,$backdate);
        $avttotal = IndustryQueries::GetAllCompanyAve(Yii::app()->user->company_id,$startdate,$enddate,$industry,$backdate);
        $chart_name = 'AVE';
        echo '<h3>AVE</h3>';
        echo '<p>Ad Value Equivalent (AVE) is a measuring tool that calculates the value of the ´space´ or ´air-time´ used for a story on the basis of the rate-card of the particular media house. The value derived thus is calculated on the same basis an Ad of similar page coverage or air play placed on the same page or time segment would. AVE compares the subscriber´s values to those of other players in the industry if the subscription includes competitors or the entire industry.</p>';
        $avnarrative = $company.' AVE in '.$inda_text.' Between '.$drange;
        $ctext ='My Ave(Kshs.'.number_format($avtotal).')';
        $otext = 'Others (Kshs.'.number_format($avttotal).')';
        echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
        $strXML = FusionCharts::packageXML($avnarrative, $ctext,$otext, $avtotal, $avttotal,$backdate);
        $charty = new FusionCharts;
        echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Pie2D.swf', "", $strXML, $chart_name, 600, 300, false, true, true);
        echo '</div>';
        break;
      /* Load the Share of Voice/Ink - By Media Type Report */
      case 3:
        $tv = IndustryQueries::GetShareVoiceMediaTV(Yii::app()->user->company_id,$startdate,$enddate,$industry,$backdate);
        $radio = IndustryQueries::GetShareVoiceMediaRadio(Yii::app()->user->company_id,$startdate,$enddate,$industry,$backdate);
        $print = IndustryQueries::GetShareVoiceMediaPrint(Yii::app()->user->company_id,$startdate,$enddate,$industry,$backdate);
        $total = $tv+$radio+$print;
        $chart_name = 'Share_By_Media_Type';
        echo '<h3>Share of Voice/Ink - By Media Type</h3>';
        echo '<p>Of the total number of stories that appeared, how many were in print and how many were on electronic media. This report will simply show the type of media where your PR activity is most visible.</p>';
        $svmnarrative = $company.' Share of Voice - By Media Type in '.$inda_text.' Between '.$drange;
        echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
        $xAxisName = 'Media';
        $yAxisName = 'Number of Mentions';
        $strXML = FusionCharts::packageColumnXML($svmnarrative,$tv,$radio,$print,$total,$xAxisName,$yAxisName,$backdate);
        $charty = new FusionCharts;
        echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Column2D.swf', "", $strXML, $chart_name, 600, 300, false, true, true);
        echo '</div>';
        break;
      /* Load the Share of Voice/Ink - By Mentions */
      case 4:
        $chart_name = 'Share_By_Mentions';
        echo '<h3>Share of Voice/Ink - By Mentions</h3>';
        echo '<p>This report compares your company´s mentions to those of the top 10 companies in your industry</p>';
        $svm2narrative = $company.' Share of Voice - By Mentions Top Performers in '.$inda_text.' Between '.$drange;
        // Get Array of Companies
        $wol = IndustryQueries::GetShareVoiceIndustry(Yii::app()->user->company_id,$startdate,$enddate,$industry,$backdate);
        echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
        $strXML = FusionCharts::packageMentionsXML($svm2narrative, $wol,$company, $startdate,$enddate,$industry,$backdate);
        $charty = new FusionCharts;
        echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Pie2D.swf', "", $strXML, $chart_name, 600, 300, false, true, true);
        echo '</div>';
        break;
      /* Load the Share of Voice/Ink - By AVE */
      case 5:
        $chart_name = 'Share_By_AVE';
        echo '<h3>Share of Voice/Ink - By AVE</h3>';
        echo '<p>This report compares your AVE to those of the top 10 companies in your industry</p>';
        $svm3narrative = $company.' Share of Voice - By AVE Top Performers in '.$inda_text.' Between '.$drange;
        // Get Array of Companies
        $aol = IndustryQueries::GetShareVoiceIndustry(Yii::app()->user->company_id,$startdate,$enddate,$industry,$backdate);
        echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
        $strXML = FusionCharts::packageAVEMentionsXML($svm3narrative, $aol,$company, $startdate,$enddate,$industry,$backdate);
        $charty = new FusionCharts;
        echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Pie2D.swf', "", $strXML, $chart_name, 600, 300, false, true, true);
        echo '</div>';
        break;
      /* Load the Categories Mentioned Report */
      case 6:
        $chart_name = 'Categories_Mentioned';
        echo '<h3>Categories Mentioned</h3>';
        echo '<p>This part of the report highlights the segments on which mentions appeared. Categories here include General News, Business News, Commentaries, Special Features, Sports News and Letters. The report will compare the distribution of mentions based on these segments. </p>';
        $svm4narrative = $company.' Share of Voice - By AVE Top Performers in '.$inda_text.' Between '.$drange;
        // Get the Array of Categories
        $cats = IndustryQueries::GetCategories();
        echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
        $strXML = FusionCharts::packageCATMentionsXML($svm4narrative, $cats, $startdate,$enddate,$industry,$backdate);
        $charty = new FusionCharts;
        echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Pie2D.swf', "", $strXML, $chart_name, 600, 300, false, true, true);
        echo '</div>';
        break;
      /* Load the Pictures & File Footage Report */
      case 7:
        $chart_name = 'Pictures_File_Footage';
        echo '<h3>Pictures & File Footage</h3>';
        echo '<p>This report compares the number of stories that contained pictures (for print stories) and file footage (Electronic Stories) to those that did not contain any. Pictures are a powerful medium of communication and as the saying goes ´a picture is worth a thousand words´. Of the total number of stories that appeared, how many were about ´myself´ and how many were about each of the ´others´ in my industry.</p>';
        $pnarrative = $company.' Stories with Pictures in '.$inda_text.' Between '.$drange;
        $cats = IndustryQueries::GetPictures();
        echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
        $strXML = FusionCharts::packagePICMentionsXML($pnarrative, $cats, $startdate,$enddate,$industry,$backdate);
        $charty = new FusionCharts;
        echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Pie2D.swf', "", $strXML, $chart_name, 600, 300, false, true, true);
        echo '</div>';
        break;
      /* Load the Tonality Report */
      case 8:
        $chart_name = 'Tonality';
        echo '<h3>Tonality</h3>';
        echo '<p>Of the total number of media mentions, how many stories were positive, negative and neutral. This report will give an analysis indicating Positive, Negative and Neutral tonality of the stories over a period. This report can further drill down and aggregate tonality by Media-Houses. </p>';
        $tnarrative = 'Tonality of '.$company.' in '.$inda_text.' Between '.$drange;
        $tons = IndustryQueries::GetTonality(Yii::app()->user->company_id,$startdate,$enddate,$industry,$backdate);
        echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
        $strXML = FusionCharts::packageTonMentionsXML($tnarrative, $tons, $startdate,$enddate,$industry,$backdate);
        $charty = new FusionCharts;
        echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Pie2D.swf', "", $strXML, $chart_name, 600, 300, false, true, true);
        echo '</div>';
        
        break;
      /* Load the Default Report - Mentions */
      default:
        $total = IndustryQueries::GetAllCompanyMentions($startdate,$enddate,$industry,$backdate);
        $ctotal = IndustryQueries::GetCompanyMentions(Yii::app()->user->company_id,$startdate,$enddate,$industry,$backdate);
        $ttotal = $total - $ctotal;
        $chart_name = 'default';
        echo '<h3>Default</h3>';
        echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
        $strXML = FusionCharts::packageXML($narrative, $company,'Others', $ctotal, $ttotal,$backdate);
        $charty = new FusionCharts;
        echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Pie2D.swf', "", $strXML, $chart_name, 600, 300, false, true, true);
        echo '</div>';
        break;
    }
  }
    
  ?>
    
	</div>
</div>

<style type="text/css">
#content{
	height: 100%;
}
</style>
