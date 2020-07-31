<?php

/**
* ImageClass Component Class
* This Class Is Used To Return The Image Manipulations
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

class ImageClass{
	public static function Generatestory_uniqueid ($length = 5)
	{
	  $story_uniqueid = "";
	  $possible = "0123456789bcdfghjkmnpqrstvwxyz";
	  $i = 0;
	  while ($i < $length) {
	    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
	    if (!strstr($story_uniqueid, $char)) {
	      $story_uniqueid .= $char;
	      $i++;
	    }
	  }
	  return $story_uniqueid;
	}

	public static function Highlight($relative_img, $x1, $y1, $width, $height){
		$uniquefile=ImageClass::Generatestory_uniqueid();
		$my_image="highlight_" .$uniquefile. ".jpg";
		$highlight_image="highlight_" .$uniquefile. ".jpg";

		//Get ratios
		$cmd="identify -format \"%w\"  /home/srv/www/htdocs/reelmediad/conversions/" . trim($relative_img);
		$actual_width=exec($cmd);
		$cmd="identify -format \"%h\"  /home/srv/www/htdocs/reelmediad/conversions/" . trim($relative_img);
		$actual_height=exec($cmd);

		$width_ratio=$actual_width/1000;
		$height_ratio=$actual_height/1298;

		$resize="/usr/bin/convert    /home/srv/www/htdocs/reelmedia/images/watermark.png  -resize  ". ($width*$width_ratio)."x".($height*$height_ratio)."\!   /home/srv/www/htdocs/reelmedia/tmp/$highlight_image" ;
		exec($resize);
		//echo "<hr>";
		$cmd="/usr/bin/composite -compose multiply -geometry  +".($x1*$width_ratio)."+".($y1*$height_ratio) ." /home/srv/www/htdocs/reelmedia/tmp/$highlight_image   /home/srv/www/htdocs/reelmediad/conversions/" . trim($relative_img) ."  " . $my_image;
		exec($cmd);

		$fullpath2="/home/srv/www/htdocs/reelmedia/tmp/";
		//$cropped=$fullpath2 . $my_image;
		$cropped= $my_image;
		header('Content-Description: File Transfer');
		header("Content-type: image/jpg");
		header("Content-disposition: attachment; filename= ".$cropped."");
		readfile($cropped);
	}


}
