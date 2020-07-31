<?php $this->breadcrumbs=array('Videos'=>array('view','id'=>$model->reel_auto_id),$model->BrandName); ?>
<?php

/* Media House Data */
$mediahouse=$model->station_id;
if($mediastation = Station::model()->find('station_id=:a', array(':a'=>$mediahouse)) ){
	$mediahouse = $mediastation->station_name;
	$station_type = $mediastation->station_type;
}else{
	$mediahouse = '';
	$station_type = 'radio';
}

/* Duration */
$incantation = Incantation::model()->find('incantation_id=:a', array(':a'=>$model->incantation_id));

$duration = $incantation->incantation_length;
$company_id = $model->company_id;
$company_ext = $company_id.'_';

/* Time */
$StoryTime=$model->reel_time;
$StoryTime=str_replace(":","",$StoryTime);

$seconds = $duration; //example

$hours = floor($seconds / 3600);
$mins = floor(($seconds - $hours*3600) / 60);
$s = $seconds - ($hours*3600 + $mins*60);

$mins = ($mins<10?"0".$mins:"".$mins);
$s = ($s<10?"0".$s:"".$s); 

$formatedtime = ($hours>0?$hours." hr(s) ":"").$mins." min(s) ".$s." sec(s)";

/* Date */
$dt=$incantation->incantation_date;  
$dt_inparts= explode("-", $dt);
$story_date = date ("l, F d Y", mktime (0,0,0,$dt_inparts[1],$dt_inparts[2],$dt_inparts[0]));



/* The File Path */
$filepath=$incantation->file_path;
$clip= "../" . $incantation->incantation_file;
$filepath =trim(str_replace("/home/srv/www/htdocs","",$filepath));
// $clip=str_replace($company_ext,"",$clip);
$clip=str_replace("files/","",$clip);
$clip=str_replace("../","",$clip);
$flash_clip=$filepath .$clip;
if(substr($clip,-3)=="mpg") {
	$flash_clip=strtolower(substr($flash_clip,0,-3) ."flv");
	$download_clip=$filepath .trim(str_replace(" ", "_", $clip));
	$clip_type="Video";
}elseif($station_type!='radio'){
	$flash_clip=strtolower(substr($flash_clip,0,-3) ."flv");
	$download_clip=$filepath .trim(str_replace(" ", "_", $clip));
	$download_clip=$filepath .trim(str_replace(".wav", ".flv", $clip));
	$clip_type="Video";
}else{
	$flash_clip=strtolower(substr($flash_clip,0,-3) ."mp3");
	$download_clip=$filepath .trim(str_replace(" ", "_", $clip));
	$download_clip=$filepath .trim(str_replace(".wav", ".mp3", $clip));
	$clip_type="Audio";
}

$thisIncantation_file=$incantation_file=$incantation->incantation_file;
$thisFile_path = $incantation->file_path;
$thisFile_path=$this_file_path = str_replace("/home/srv/www/htdocs/anvil/","",$thisFile_path);

$thisIncantation_mpg_path=$mpg_path=$incantation->mpg_path;

if($mpg_path) {					  
	$thisIncantation_mp3=str_replace(".wav",".mp3",$thisIncantation_file);
	$thisIncantation_mpg_path=str_replace(".wav",".mpg",$thisIncantation_file);
	$first_pos=stripos($thisIncantation_mpg_path, "_");
	$thisIncantation_mpg_path=substr($thisIncantation_mpg_path,($first_pos+1));
	$endfile = $thisFile_path .$thisIncantation_mpg_path;
}else{
	$thisIncantation_mp3=str_replace(".wav",".mp3",$thisIncantation_file);
	$endfile = $thisFile_path .$thisIncantation_mp3;

}
echo $endfile;



$download_clip = 'http://www.reelforge.com/'.$download_clip;
echo $flash_clip ='http://www.reelforge.com/'.$flash_clip;

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