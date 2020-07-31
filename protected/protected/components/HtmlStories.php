<?php
/**
* This Class Is used to generate the PDF Reports
*/
class HtmlStories{

/**
* This function handles all the heavylifting for Print Stories, fetch story and print out
* NB - Just for the Print Section
*/

public static function HtmlHeader()
{
	$style = '';
	$style = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
	$style.= "<head>";
	$style.= "<title>Reelforge HTML Compilation</title>";
	$style.= '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">';
	$style.= '<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">';
	$style.= HtmlStories::Stylesheet();
	$style.= "</head>";
	$style.= "<body>";
	$style.= "<div class='container-fluid'>";
	return $style;
}

public static function FileBody($content){
	$body  = '';
	$body .=  '<img src="http://beta.reelforge.com/anvild/images/anvil_header.jpg" width="100%" /><br>';
	$body .= '<h3>Competitor Ads - Media </h3>';
	$body .= HtmlStories::HtmlHeader();
	$body .= $content;
	$body .= '</div></body></html>';
	return $body;
}





/*
* Print The Top Section of Every Table
* NB - Just for the Print Section
*/
public static function PrintTableHead(){
	$country = Yii::app()->user->country_id;
	if($currency = Country::model()->find('country_id=:a', array(':a'=>$country))){
		$currency = $currency->currency;
	}else{
		$currency = 'KES';
	}
	return '<table class="table table-striped table-condensed table-hover table-bordered"><tr>
	<td style="width:11%;">DATE</td><td>PUBLICATION</td><td>JOURNALIST</td><td>HEADLINE/SUBJECT</td><td>PAGE</td><td>PUBLICATION TYPE</td><td>PICTURE</td><td>EFFECT</td><td style="text-align:right;">AVE('.$currency.')</td>
	</tr>';
}

public static function AgencyPrintTableHead(){
	$country = Yii::app()->user->country_id;
	if($currency = Country::model()->find('country_id=:a', array(':a'=>$country))){
		$currency = $currency->currency;
	}else{
		$currency = 'KES';
	}
	return '<table class="table table-striped table-condensed table-hover table-bordered"><tr>
	<td style="width:11%;">DATE</td><td>PUBLICATION</td><td>JOURNALIST</td><td>HEADLINE/SUBJECT</td><td>PAGE</td><td>PUBLICATION TYPE</td><td>PICTURE</td><td>EFFECT</td><td style="text-align:right;">AVE('.$currency.')</td><td style="text-align:right;">PRV('.$currency.')</td>
	</tr>';
}

/*
* Print The Top Section of Every Table
* NB - Just for the Electronic Section
*/
public static function ElectronicTableHead(){
	$country = Yii::app()->user->country_id;
	if($currency = Country::model()->find('country_id=:a', array(':a'=>$country))){
		$currency = $currency->currency;
	}else{
		$currency = 'KES';
	}
	return '<table class="table table-striped table-condensed table-hover table-bordered"><tr>
	<td style="width:11%;">DATE</td><td>STATION</td><td>JOURNALIST</td><td>SUMMARY</td><td>TIME</td><td>DURATION</td><td>CATEGORY</td><td>EFFECT</td><td style="text-align:right;">AVE('.$currency.')</td>
	</tr>';
}

public static function AgencyElectronicTableHead(){
	$country = Yii::app()->user->country_id;
	if($currency = Country::model()->find('country_id=:a', array(':a'=>$country))){
		$currency = $currency->currency;
	}else{
		$currency = 'KES';
	}
	return '<table class="table table-striped table-condensed table-hover table-bordered"><tr>
	<td style="width:11%;">DATE</td><td>STATION</td><td>JOURNALIST</td><td>SUMMARY</td><td>TIME</td><td>DURATION</td><td>CATEGORY</td><td>EFFECT</td><td style="text-align:right;">AVE('.$currency.')</td><td style="text-align:right;">PRV('.$currency.')</td>
	</tr>';
}

/*
* Print The Body of the Table This function may be called recursively
* NB - Just for the Print Section
*/
public static function PrintTableBody($date,$storyid,$pub,$journo,$head,$page,$pubtype,$pic,$effect,$ave,$link,$cont,$StoryColum,$ContinuingAve){
	return '<tr>
	<td><a href="http://beta.reelforge.com/reelmediad/swf/view/'.$storyid.'" target="_blank">'.date('d-M-Y', strtotime($date)).'</a></td>
	<td>'.$pub.'</td>
	<td>'.$journo.'</td>
	<td><a href="http://beta.reelforge.com/reelmediad/swf/view/'.$storyid.'" target="_blank">'.$head.'</a><br><font size="1">'.$cont.'</font></td>
	<td>'.$page.'</td>
	<td>'.$pubtype.'</td>
	<td>'.$pic.'</td>
	<td>'.$effect.'</td>
	<td style="text-align:right;">'.number_format($ContinuingAve).'</td>
	</tr>';
}

public static function AgencyPrintTableBody($date,$storyid,$pub,$journo,$head,$page,$pubtype,$pic,$effect,$ave,$link,$cont,$StoryColum,$ContinuingAve){
	// Obtain the Agency ID from Session
	$agency_id = Yii::app()->user->company_id;
	$sql_agency_pr="select agency_pr_rate  from agency where agency_id=$agency_id";
	if($agency_pr_rate = Agency::model()->findBySql($sql_agency_pr)){
		$agency_pr_rate = $agency_pr_rate->agency_pr_rate;
	}else{
		$agency_pr_rate = 3;
	}
	return '<tr>
	<td><a href="http://beta.reelforge.com/reelmediad/swf/view/'.$storyid.'" target="_blank">'.date('d-M-Y', strtotime($date)).'</a></td>
	<td>'.$pub.'</td>
	<td>'.$journo.'</td>
	<td><a href="http://beta.reelforge.com/reelmediad/swf/view/'.$storyid.'" target="_blank">'.$head.'</a><br><font size="1">'.$cont.'</font></td>
	<td>'.$page.'</td>
	<td>'.$pubtype.'</td>
	<td>'.$pic.'</td>
	<td>'.$effect.'</td>
	<td style="text-align:right;">'.number_format($ContinuingAve).'</td>
	<td style="text-align:right;">'.number_format($ContinuingAve*$agency_pr_rate).'</td>
	</tr>';
}

/*
* Print The Body of the Table This function may be called recursively
* NB - Just for the Electronic Section
*/
public static function ElectronicTableBody($date,$storyid,$pub,$journo,$head,$page,$pubtype,$pic,$effect,$ave,$link,$cont){
	return '<tr>
	<td><a href="'.Yii::app()->createUrl("video").'/'.$storyid.'" target="_blank">'.date('d-M-Y', strtotime($date)).'</a></td>
	<td>'.$pub.'</td>
	<td>'.$journo.'</td>
	<td><a href="'.Yii::app()->createUrl("video").'/'.$storyid.'" target="_blank">'.$head.'</a><br><font size="1">'.$cont.'</font></td>
	<td>'.$page.'</td>
	<td>'.$pubtype.'</td>
	<td>'.$pic.'</td>
	<td>'.$effect.'</td>
	<td style="text-align:right;">'.number_format($ave).'</td>
	</tr>';
}

public static function AgencyElectronicTableBody($date,$storyid,$pub,$journo,$head,$page,$pubtype,$pic,$effect,$ave,$link,$cont){
	// Obtain the Agency ID from Session
	$agency_id = Yii::app()->user->company_id;
	$sql_agency_pr="select agency_pr_rate  from agency where agency_id=$agency_id";
	if($agency_pr_rate = Agency::model()->findBySql($sql_agency_pr)){
		$agency_pr_rate = $agency_pr_rate->agency_pr_rate;
	}else{
		$agency_pr_rate = 3;
	}
	return '<tr>
	<td><a href="'.Yii::app()->createUrl("video").'/'.$storyid.'" target="_blank">'.date('d-M-Y', strtotime($date)).'</a></td>
	<td>'.$pub.'</td>
	<td>'.$journo.'</td>
	<td><a href="'.Yii::app()->createUrl("video").'/'.$storyid.'" target="_blank">'.$head.'</a><br><font size="1">'.$cont.'</font></td>
	<td>'.$page.'</td>
	<td>'.$pubtype.'</td>
	<td>'.$pic.'</td>
	<td>'.$effect.'</td>
	<td style="text-align:right;">'.number_format($ave).'</td>
	<td style="text-align:right;">'.number_format($ave*$agency_pr_rate).'</td>
	</tr>';
}
/*
* Close the Table and Its Bottom section
* NB - Just for the Print Section
*/
public static function PrintTableEnd(){
	return '</table>';
}

/*
* Close the Table and Its Bottom section
* NB - Just for the Electronic Section
*/
public static function ElectronicTableEnd(){
	return '</table>';
}

public static function Stylesheet()
{
	$style = '<style type="text/css">';
	$style .= 'body{ font-family: "Open Sans",Arial,Helvetica,Sans-Serif;
  font-size: 11px !important; } table{font-family: "Open Sans",Arial,Helvetica,Sans-Serif;
  font-size: 11px !important; }h3 { font-size: 14px; padding: 15px;}';
	$style .= '</style>';
	return $style;
}

	public static function MoveElectronicFile($file,$cd_name)
	{
		/* 
		** Copy the Required Files, Individually
		** get a copy of the electronic file, mp3 or mpg/flv
		** Check if Flash File Exists 
		** Convert to lower caps then try and find
		** flv or else copy mpg
		*/
		if(file_exists( $file ) ){
			// echo 'found';
			$file_destination = $_SERVER['DOCUMENT_ROOT']."anvild/html/".$cd_name."/";
			$flash_cmd ="cp -v ".$file ."  ".$file_destination." ";
			exec($flash_cmd);
		}else{
			// echo 'not found - '.$file.'<br>';
		}
	}

	public static function MovePrintFile($file,$cd_name)
	{
		/* 
		** Copy the Required Files, Individually
		** get a copy of the electronic file, mp3 or mpg/flv
		** Check if Flash File Exists 
		** Convert to lower caps then try and find
		** flv or else copy mpg
		*/
		if(file_exists( $file ) ){
			$file_destination = $_SERVER['DOCUMENT_ROOT']."anvild/html/".$cd_name."/";
			$flash_cmd ="cp -v ".$file ."  ".$file_destination." ";
			exec($flash_cmd);
		}else{
			// echo 'not found'.$file.'<br>';
		}
	}

}


?>