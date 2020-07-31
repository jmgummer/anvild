<?php
/* Media House Data */
$this->breadcrumbs=array('Videos'=>array('view','id'=>$model->auto_id),$model->BrandName); 
$mediahouse=$model->station_id;
if($mediahouse = Station::model()->find('station_id=:a', array(':a'=>$mediahouse)) ){
	$mediahouse = $mediahouse->station_name;
}else{
	$mediahouse = '';
}

/* Date */
$dt=$model->date;  
$dt_inparts= explode("-", $dt);
$story_date = date ("l, F d Y", mktime (0,0,0,$dt_inparts[1],$dt_inparts[2],$dt_inparts[0]));

/* Duration */
$duration = $model->duration;

/* Time */
$StoryTime=$model->time;
$StoryTime=str_replace(":","",$StoryTime);

$seconds = $duration; //example

$hours = floor($seconds / 3600);
$mins = floor(($seconds - $hours*3600) / 60);
$s = $seconds - ($hours*3600 + $mins*60);

$mins = ($mins<10?"0".$mins:"".$mins);
$s = ($s<10?"0".$s:"".$s); 

$formatedtime = ($hours>0?$hours." hr(s) ":"").$mins." min(s) ".$s." sec(s)";

/* The File Path */
$filepath=$model->file_path;
$clip= "../" . $model->filename;
$filepath =trim(str_replace("/home/srv/www/htdocs","",$filepath));
$clip=str_replace("files/","",$clip);
$clip=str_replace("../","",$clip);
if(substr($clip,-3)=="mpg") {
	$download_clip=$filepath .trim(str_replace(" ", "_", $clip));
	$clip_type="Video";
}else{
	$download_clip=$filepath .trim(str_replace(" ", "_", $clip));
	$clip_type="Audio";
} 
$flash_clip=$filepath .$clip;

if(substr($flash_clip,-3)=="mpg") {
	$flash_clip=strtolower(substr($flash_clip,0,-3) ."flv");
}
$download_clip = 'http://www.reelforge.com/'.$download_clip;
$flash_clip ='http://www.reelforge.com/'.$flash_clip;


?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl . '/jwplayer/jwplayer.js'; ?>"></script>
<div class="row-fluid clearfix"><div class="col-md-12"><h3><?php echo $title=$model->BrandName; ?></h3></div></div>
<div class="row-fluid clearfix">
	<div class="col-md-8">
		<div id="myElement">Loading the player...</div>
		<script type="text/javascript">
		    jwplayer("myElement").setup({
		        file: "<?php echo $flash_clip; ?>",
		        width: 640,
		        height: 360
		    });
		</script>
	</div>
	<div class="col-md-4">
		<p><strong>Station : <?php echo $mediahouse; ?></strong></p>
		<p><?php echo $story_date; ?></p>
		<p>Time : <?php echo substr($StoryTime, 0,4); ?> hrs<?php //echo $ampm; ?></p>
		<p>Length : <?php echo $formatedtime; ?></p>
		<p>Type : <?php echo $clip_type; ?></p><br>
		<p><strong>Download </strong><br></p>
		<p><a href="<?php echo $download_clip; ?>"><i class="fa fa-download fa-2x"></i></a></p>
	</div>
</div>
<div class="row-fluid clearfix">
	<div class="col-md-12">
		<br>
		<!-- <p><strong>Companies Mentioned : </strong></p> -->
		<p><?php //echo $companies_mentioned; ?></p>
	</div>
</div>