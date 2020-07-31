<?php
/**
* Stories File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/
$this->pageTitle=Yii::app()->name.' | Stories';
$this->breadcrumbs=array('Index');
?>
<div class="row-fluid clearfix">
<div class="col-md-3">
<?php $this->renderPartial('search_filter',array('model'=>$model)); ?>
</div>
<div class="col-md-9">

<?php
$todays = date('Y-m-d');
$startdate = $enddate = $todays;
$search = ' ';
// Adding Country Code
$country = 1;
$industries = '';
// Adding backdate

$cat_identifier = 1;
$type_identifier = 1;

$company_words = Company::model()->find('company_id=:a order by keywords', array(':a'=>Yii::app()->user->company_id));
$backdate = $company_words->backdate;

if(isset($_POST['StorySearch'])){
	$startdate = $model->startdate;
	$enddate = $model->enddate;
	$search = $model->search_text;
	$inda=array();
	if(isset($model->industry) && !empty($model->industry)){
	    foreach ($model->industry as $key) {
	      $inda[] = $key;
	    }
	    $industries = implode(', ', $inda);
	}
	if(isset($model->storycategory) && !empty($model->storycategory)){
      $cat_identifier= $model->storycategory;
    }
    if(isset($model->storytype) && !empty($model->storytype)){
      $type_identifier= $model->storytype;
    }
	
	?>
	<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
		<header role="heading">
			<h2>Print PDF and Create Spreadsheets ? 
				<a href="<?=Yii::app()->createUrl("home/pdf?startdate=$startdate&enddate=$enddate&search=$search&industries=$industries&cat_identifier=$cat_identifier&type_identifier=$type_identifier");?>" class="btn btn-danger btn-xs pdf-excel"><i class="fa fa-file-pdf-o"></i> PDF</a>
				<a href="<?=Yii::app()->createUrl("home/excel?startdate=$startdate&enddate=$enddate&search=$search&industries=$industries&cat_identifier=$cat_identifier&type_identifier=$type_identifier");?>" class="btn btn-success btn-xs pdf-excel"><i class="fa fa-file-excel-o"></i> EXCEL</a>
			</h2>
		</header>
	</div>
	<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
		<header role="heading"><h2>Company : <?php echo Yii::app()->user->company_name; ?> <strong>Search</strong> for Dates between <?php echo $startdate.' and '.$enddate; ?></h2></header>
				
		<div class="reveal">
		<div id="chat-body" role="content">
			<div class="widget-body no-padding">
				<div class="search-params">
				<?php echo '<br><strong>Key Words Searched : </strong> '.$search; ?>
				</div>
				<?php
				if($company_words){
					if($pieces = explode(",", $company_words->keywords)){
						foreach ($pieces as $key) {
							echo '<div class="col-md-3">'.$key.'</div>';
						}
					}
				}
				?>
			</div>
		</div>
		</div>
		<footer class="reveal-footer"><button class="btn btn-primary">View Keywords</button></footer>
	</div>
	<?php
}else{ ?>
	<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
		<header role="heading">
			<h2>Print PDF and Create Spreadsheets ? 
				<a href="<?=Yii::app()->createUrl("home/pdf");?>" class="btn btn-danger btn-xs pdf-excel"><i class="fa fa-file-pdf-o"></i> PDF</a> 
				<a href="<?=Yii::app()->createUrl("home/excel");?>" class="btn btn-success btn-xs pdf-excel"><i class="fa fa-file-excel-o"></i> EXCEL</a>
			</h2>
		</header>
	</div>
	<?php
}

if($type_identifier==1){
	if($cat_identifier==1){
		echo '<div class="widget-body">
		<ul id="myTab1" class="nav nav-tabs bordered">
			<li class="active"><a href="#s1" data-toggle="tab">My Print Stories</a></li>
			<li class=""><a href="#s2" data-toggle="tab">My Electronic Stories</a></li>
			<li class=""><a href="#s3" data-toggle="tab">Industry Print Stories</a></li>
			<li class=""><a href="#s4" data-toggle="tab">Industry Electronic Stories</a></li>
			<li class="pull-right">
				<a href="javascript:void(0);">
				<div class="sparkline txt-color-pinkDark text-align-right" data-sparkline-height="18px" data-sparkline-width="90px" data-sparkline-barwidth="7"><canvas width="52" height="18" style="display: inline-block; width: 52px; height: 18px; vertical-align: top;"></canvas></div> </a>
			</li>
		</ul>';
		echo '<div id="myTabContent1" class="tab-content padding-10">';

		echo '<div class="tab-pane fade active in" id="s1">';
		$stories = RecentStories::GetClientStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		echo '</div>';

		echo '<div class="tab-pane fade" id="s2">';
		$stories = RecentStories::GetElectronicStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		echo '</div>';

		echo '<div class="tab-pane fade" id="s3">';
		$stories = RecentStories::GetClientIndustryStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		echo '</div>';

		echo '<div class="tab-pane fade" id="s4">';
		$stories = RecentStories::GetClientElectronicIndustryStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		echo '</div>';

		echo '</div>';
		echo '</div>';
	}
	if($cat_identifier==2){
		echo '<div class="widget-body">
		<ul id="myTab1" class="nav nav-tabs bordered">
			<li class="active"><a href="#s1" data-toggle="tab">My Print Stories</a></li>
			<li class=""><a href="#s2" data-toggle="tab">My Electronic Stories</a></li>
			<li class="pull-right">
				<a href="javascript:void(0);">
				<div class="sparkline txt-color-pinkDark text-align-right" data-sparkline-height="18px" data-sparkline-width="90px" data-sparkline-barwidth="7"><canvas width="52" height="18" style="display: inline-block; width: 52px; height: 18px; vertical-align: top;"></canvas></div> </a>
			</li>
		</ul>';
		echo '<div id="myTabContent1" class="tab-content padding-10">';

		echo '<div class="tab-pane fade active in" id="s1">';
		$stories = RecentStories::GetClientStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		echo '</div>';

		echo '<div class="tab-pane fade" id="s2">';
		$stories = RecentStories::GetElectronicStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		echo '</div>';

		echo '</div>';
		echo '</div>';
	}
	if($cat_identifier==3){
		echo '<div class="widget-body">
		<ul id="myTab1" class="nav nav-tabs bordered">
			<li class="active"><a href="#s3" data-toggle="tab">Industry Print Stories</a></li>
			<li class=""><a href="#s4" data-toggle="tab">Industry Electronic Stories</a></li>
			<li class="pull-right">
				<a href="javascript:void(0);">
				<div class="sparkline txt-color-pinkDark text-align-right" data-sparkline-height="18px" data-sparkline-width="90px" data-sparkline-barwidth="7"><canvas width="52" height="18" style="display: inline-block; width: 52px; height: 18px; vertical-align: top;"></canvas></div> </a>
			</li>
		</ul>';
		echo '<div id="myTabContent1" class="tab-content padding-10">';

		echo '<div class="tab-pane fade active in" id="s3">';
		$stories = RecentStories::GetClientIndustryStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		echo '</div>';

		echo '<div class="tab-pane fade" id="s4">';
		$stories = RecentStories::GetClientElectronicIndustryStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		echo '</div>';

		echo '</div>';
		echo '</div>';
	}
}
if($type_identifier==2){
	if($cat_identifier==1){
		echo '<div class="widget-body">
		<ul id="myTab1" class="nav nav-tabs bordered">
			<li class="active"><a href="#s1" data-toggle="tab">My Print Stories</a></li>
			<li class=""><a href="#s3" data-toggle="tab">Industry Print Stories</a></li>
			<li class="pull-right">
				<a href="javascript:void(0);">
				<div class="sparkline txt-color-pinkDark text-align-right" data-sparkline-height="18px" data-sparkline-width="90px" data-sparkline-barwidth="7"><canvas width="52" height="18" style="display: inline-block; width: 52px; height: 18px; vertical-align: top;"></canvas></div> </a>
			</li>
		</ul>';
		echo '<div id="myTabContent1" class="tab-content padding-10">';

		echo '<div class="tab-pane fade active in" id="s1">';
		$stories = RecentStories::GetClientStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		echo '</div>';

		echo '<div class="tab-pane fade" id="s3">';
		$stories = RecentStories::GetClientIndustryStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		echo '</div>';

		echo '</div>';
		echo '</div>';
	}
	if($cat_identifier==2){
		echo '<div class="widget-body">
		<ul id="myTab1" class="nav nav-tabs bordered">
			<li class="active"><a href="#s1" data-toggle="tab">My Print Stories</a></li>
			<li class="pull-right">
				<a href="javascript:void(0);">
				<div class="sparkline txt-color-pinkDark text-align-right" data-sparkline-height="18px" data-sparkline-width="90px" data-sparkline-barwidth="7"><canvas width="52" height="18" style="display: inline-block; width: 52px; height: 18px; vertical-align: top;"></canvas></div> </a>
			</li>
		</ul>';
		echo '<div id="myTabContent1" class="tab-content padding-10">';

		echo '<div class="tab-pane fade active in" id="s1">';
		$stories = RecentStories::GetClientStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		echo '</div>';

		echo '</div>';
		echo '</div>';
	}
	if($cat_identifier==3){
		echo '<div class="widget-body">
		<ul id="myTab1" class="nav nav-tabs bordered">
			<li class="active"><a href="#s3" data-toggle="tab">Industry Print Stories</a></li>
			<li class="pull-right">
				<a href="javascript:void(0);">
				<div class="sparkline txt-color-pinkDark text-align-right" data-sparkline-height="18px" data-sparkline-width="90px" data-sparkline-barwidth="7"><canvas width="52" height="18" style="display: inline-block; width: 52px; height: 18px; vertical-align: top;"></canvas></div> </a>
			</li>
		</ul>';
		echo '<div id="myTabContent1" class="tab-content padding-10">';

		echo '<div class="tab-pane fade active in" id="s3">';
		$stories = RecentStories::GetClientIndustryStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		echo '</div>';

		echo '</div>';
		echo '</div>';
	}
}
if($type_identifier==3){
	if($cat_identifier==1){
		echo '<div class="widget-body">
		<ul id="myTab1" class="nav nav-tabs bordered">
			<li class="active"><a href="#s2" data-toggle="tab">My Electronic Stories</a></li>
			<li class=""><a href="#s4" data-toggle="tab">Industry Electronic Stories</a></li>
			<li class="pull-right">
				<a href="javascript:void(0);">
				<div class="sparkline txt-color-pinkDark text-align-right" data-sparkline-height="18px" data-sparkline-width="90px" data-sparkline-barwidth="7"><canvas width="52" height="18" style="display: inline-block; width: 52px; height: 18px; vertical-align: top;"></canvas></div> </a>
			</li>
		</ul>';
		echo '<div id="myTabContent1" class="tab-content padding-10">';

		echo '<div class="tab-pane fade active in" id="s2">';
		$stories = RecentStories::GetElectronicStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		echo '</div>';

		echo '<div class="tab-pane fade" id="s4">';
		$stories = RecentStories::GetClientElectronicIndustryStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		echo '</div>';

		echo '</div>';
		echo '</div>';
	}
	if($cat_identifier==2){
		echo '<div class="widget-body">
		<ul id="myTab1" class="nav nav-tabs bordered">
			<li class="active"><a href="#s2" data-toggle="tab">My Electronic Stories</a></li>
			<li class="pull-right">
				<a href="javascript:void(0);">
				<div class="sparkline txt-color-pinkDark text-align-right" data-sparkline-height="18px" data-sparkline-width="90px" data-sparkline-barwidth="7"><canvas width="52" height="18" style="display: inline-block; width: 52px; height: 18px; vertical-align: top;"></canvas></div> </a>
			</li>
		</ul>';
		echo '<div id="myTabContent1" class="tab-content padding-10">';

		echo '<div class="tab-pane fade active in" id="s2">';
		$stories = RecentStories::GetElectronicStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		echo '</div>';

		echo '</div>';
		echo '</div>';
	}
	if($cat_identifier==3){
		echo '<div class="widget-body">
		<ul id="myTab1" class="nav nav-tabs bordered">
			<li class="active"><a href="#s4" data-toggle="tab">Industry Electronic Stories</a></li>
			<li class="pull-right">
				<a href="javascript:void(0);">
				<div class="sparkline txt-color-pinkDark text-align-right" data-sparkline-height="18px" data-sparkline-width="90px" data-sparkline-barwidth="7"><canvas width="52" height="18" style="display: inline-block; width: 52px; height: 18px; vertical-align: top;"></canvas></div> </a>
			</li>
		</ul>';
		echo '<div id="myTabContent1" class="tab-content padding-10">';

		echo '<div class="tab-pane fade active in" id="s4">';
		$stories = RecentStories::GetClientElectronicIndustryStory(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries);
		echo '</div>';

		echo '</div>';
		echo '</div>';
	}
}
    
?>
</div>
</div>



<style type="text/css">
.radio label{
	/*font-weight: bold;*/
	/*margin-left: -10px;*/
}
.search-params{
	padding: 10px 13px;
	clear: both;
}
.pdf-excel{
	margin: 5px 1px;
}
#chat-body {
    background: linear-gradient(to bottom, #F5FCFF 0px, #FFF 100%) repeat scroll 0% 0% transparent;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.04) inset;
    display: block;
    height: 270px;
    overflow-y: scroll;
    overflow-x: hidden;
    padding: 10px;
    box-sizing: border-box;
    border-right: 1px solid #FFF;
    border-width: 0px 1px 1px;
    border-style: none solid solid;
    border-color: -moz-use-text-color #FFF #FFF;
    -moz-border-top-colors: none;
    -moz-border-right-colors: none;
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    border-image: none;
}
table{
	font-size: 12px;
}
table a{
	color: #2F5961;
}
.reveal-footer{
	display: block;
	padding: 7px 14px 15px;
	border-top: 1px solid rgba(0, 0, 0, 0.1);
	background: none repeat scroll 0% 0% rgba(248, 248, 248, 0.9);
	margin: 0px;
	box-sizing: content-box;
	color: #666;
	border: 1px solid #C2C2C2;
}
</style>
<script>
$(document).ready(function(){
$(".reveal").hide();
  $("button").click(function(){
    $(".reveal").slideToggle();
  });
});
</script>