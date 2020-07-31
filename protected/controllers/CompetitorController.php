<?php

/**
* CompetitorController Controller Class
*
* @package     Anvil
* @subpackage  Controllers
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/

class CompetitorController  extends Controller
{
	/**
	 * @var This is the Competitor controller
	 */
	public $layout='//layouts/column1';

	/** 
	*
	* @return  Filters
	* @throws  InvalidArgumentException
	* @todo    Manage Access Control
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	/** 
	*
	* @return  Boolean
	* @throws  InvalidArgumentException
	* @todo    Track all User Actions
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	protected function beforeAction($event)
    {
        $track = new Tracker;
        $track->Utrack();
        return true;
    }

    /** 
	*
	* @return  Boolean
	* @throws  InvalidArgumentException
	* @todo    Determines whether a user has access to a section or otherwise
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','company','getdata','brand','adselectronic','adsprint','marketshare','cd','zip','archives'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/** 
	*
	* @return  index page
	* @throws  InvalidArgumentException
	* @todo    Loads the Index Page
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionIndex()
	{
		$model = $model = new StorySearch('search');
		$model->unsetAttributes();
		if(isset($_POST['StorySearch']))
		{
			$model->attributes=Yii::app()->input->stripClean($_POST['StorySearch']);
			$model->startdate = date('Y-m-d',strtotime(str_replace('-', '/', $model->startdate)));
			$model->enddate = date('Y-m-d',strtotime(str_replace('-', '/', $model->enddate)));
		}else{
			$model->storytype = 1;
			$model->startdate = $model->enddate = date('Y-m-d');
		}
		$this->render('index', array('model'=>$model));
	}

	public function actionArchives(){
		if(isset($_GET['zip']) && !empty($_GET['zip'])){
			$post_update['filename'] = $_GET['zip'];
			$post_update['archive_update'] = 1;

			$url = Yii::app()->params->anvil_api . "zipcopyupdate";
			$data = urldecode(http_build_query($post_update));
			$update = json_decode(Yii::app()->curl->post($url, $data));

			if($update != "-1"){
				$this->redirect(array('competitor/archives'));
			}

		}
		$this->render('archives');
	}

	/** 
	*
	* @return  company page
	* @throws  InvalidArgumentException
	* @todo    Loads the company competitor Page
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionCompany()
	{
		$model = new StorySearch;
		if(isset($_POST['enddate'])){
			$this->render('company_processed', array('model'=>$model));
		}else{
			$this->render('company', array('model'=>$model));
		}
	}

	/** 
	*
	* @return  Brand page
	* @throws  InvalidArgumentException
	* @todo    Loads the Brand Page
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionBrand()
	{
		$model = new StorySearch;
		if(isset($_POST['enddate'])){
			$this->render('brand_processed', array('model'=>$model));
		}else{
			$this->render('brand', array('model'=>$model));
		}
	}

	/** 
	*
	* @return  adselectronic page
	* @throws  InvalidArgumentException
	* @todo    Loads the Electronic Ads Page
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionAdselectronic()
	{
		$model = new StorySearch;
		if(isset($_POST['enddate'])){
			$this->render('adselectronic_processed', array('model'=>$model));
		}else{
			$this->render('adselectronic', array('model'=>$model));
		}
	}

	/** 
	*
	* @return  adsprint page
	* @throws  InvalidArgumentException
	* @todo    Loads the Print Ads Page
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionAdsprint()
	{
		$model = new StorySearch;
		if(isset($_POST['enddate'])){
			$this->render('adsprint_processed', array('model'=>$model));
		}else{
			$this->render('adsprint', array('model'=>$model));
		}
	}

	/** 
	*
	* @return  marketshare page
	* @throws  InvalidArgumentException
	* @todo    Loads the Market Share Page
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionMarketshare()
	{
		$model = new StorySearch;
		if(isset($_POST['enddate'])){
			$this->render('market_processed', array('model'=>$model));
		}else{
			$this->render('market', array('model'=>$model));
		}
	}

	public function actionCd()
	{
		if(isset($_GET['file'])){
			$file = $_GET['file'];
			$path=$_SERVER['DOCUMENT_ROOT'].'/anvild/html/'.$file.'.html';
			$type = 'text/html';
			if(file_exists($path)){
				header('Content-Type: '.$type);
				header('Content-Length: ' . filesize($path));
				header('Content-Disposition: attachment; filename="'.$file.'.html"');
				readfile($path);
			}else{
				echo 'file not found';
			}
		}else{
			$this->redirect(array('adselectronic'));
		}
		
	}

	public function actionZip()
	{
		ini_set('memory_limit','128M');
		if(isset($_GET['file'])){
			$file = $_GET['file'];
			$path=$_SERVER['DOCUMENT_ROOT'].'/anvild/html/'.$file.'.zip';
			$type = 'application/octet-stream';
			if(file_exists($path)){
				/* Delete The Folder Containing Records During First Zip Download */
				$directory_cmd = $_SERVER['DOCUMENT_ROOT']."/anvild/html/".$file;
				// if(file_exists( $directory_cmd ) ){
				// 	$directory_cmd ="rm -Rf ".$_SERVER['DOCUMENT_ROOT']."/anvild/html/".$file;
				// 	exec($directory_cmd);
				// }
				$site_url = 'www.reelforge.com';
				$this->redirect('http://'.$site_url.'/anvild/html/'.$file.'.zip');
				// header('Content-Type: '.$type);
				// header('Content-Length: ' . filesize($path));
				// header('Content-Disposition: attachment; filename="'.$file.'.zip"');
				// readfile($path);
			}else{
				echo 'file not found';
			}
		}else{
			$this->redirect(array('adselectronic'));
		}
		
	}

	/** 
	*
	* @return  packaged data options
	* @throws  InvalidArgumentException
	* @todo    Returns Various Options for Ajax Loading
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionGetdata()
	{
		if(isset($_POST['channel'])){
			$channel = $_POST['channel'];
			$client_id = Yii::app()->user->company_id;
			$sql_site="select distinct(brand_name),brand_table.brand_id from brand_table,outdoor_channel_entries where brand_table.brand_id=outdoor_channel_entries.brand_id and outdoor_channel_entries.company_id='$channel' and brand_table.company_id='$client_id' order by brand_name ; ";
			
			if($brands = Yii::app()->db3->createCommand($sql_site)->queryAll()){
				echo "<option value=''>-Select-</option>";
        		echo "<option value='all'>-ALL-</option>";
				foreach ($brands as $value) {
					$this_brand_id=$value["brand_id"];
					$this_brand_name=trim($value["brand_name"]);

					echo '<option value="'.$this_brand_id.'">'.$this_brand_name.'</option>';
				}
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				echo '<option>No Results Found</option>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}
		}
		if(isset($_POST['region']) && isset($_POST['company_id']) && !empty($_POST['company_id'])){
			$this_province_id 	= 	$_POST['region'];
			$this_company_id 	= 	$_POST['company_id'];

			if($this_province_id=="all") {
			$sql_site="select ocs.auto_id, ocs.site_name, ocs.site_location from outdoor_channel_sites ocs, towns where ocs.company_id='$this_company_id' and ocs.site_town=towns.auto_id order by site_name asc ";
			} else {
			$sql_site="select ocs.auto_id, ocs.site_name, ocs.site_location from outdoor_channel_sites ocs, towns where ocs.company_id='$this_company_id' and towns.province_id='$this_province_id' and ocs.site_town=towns.auto_id order by site_name asc ";
			}

			if($sites = Yii::app()->db3->createCommand($sql_site)->queryAll()){
        		echo "<option value='all'>-ALL-</option>";
				foreach ($sites as $value) {
					$this_site_id=$value["auto_id"];
					$this_site_name=trim($value["site_name"]);
					$this_site_location=trim($value["site_location"]);

					echo '<option value="'.$this_site_id.'">'.$this_site_name.' - '.$this_site_location.'</option>';
				}
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				echo '<option>No Results Found</option>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}
		}

		if(isset($_POST['industry']) && !empty($_POST['industry'])){
			$industry 	= 	$_POST['industry'];
			$sql_sub_industry="select *  from  sub_industry where industry_id='$industry'  order by sub_industry_name asc";

			if($subs = Yii::app()->db3->createCommand($sql_sub_industry)->queryAll()){
				foreach ($subs as $value) {
					$this_sub_industry_name=ucwords(strtolower($value["sub_industry_name"]));  
					$this_sub_industry_id=$value["auto_id"];
					echo '<div class="col-md-6"><label class="checkbox">';
					echo "<input id='this_sub_industry_id' name='sub_industry_name[".$this_sub_industry_id."]'  type='checkbox' value='$this_sub_industry_id' />";
					echo '<label class="checkbox">'.$this_sub_industry_name.'</label></label></div>';
				}
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				echo '<option>No Results Found</option>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}
		}

		if(isset($_POST['mainindustry']) && !empty($_POST['mainindustry'])){
			$industry 	= 	$_POST['mainindustry'];
			if($industry=='all'){
				echo '<option>No Results Found</option>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				$sql_sub_industry="select *  from  sub_industry where industry_id='$industry'  order by sub_industry_name asc";

				if($subs = Yii::app()->db3->createCommand($sql_sub_industry)->queryAll()){
					echo "<option value=''>-Select Sub Industry-</option>";
					if(Yii::app()->user->rpts_only==1){
						echo "<option value='all'>-- All Sub Industries --</option>";
					}
					foreach ($subs as $value) {
						$this_sub_industry_name=ucwords(strtolower($value["sub_industry_name"]));  
						$this_sub_industry_id=$value["auto_id"];
						echo '<option value="'.$this_sub_industry_id.'">'.$this_sub_industry_name.'</option>';
					}
					echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
				}else{
					echo '<option>No Results Found</option>';
					echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
				}
			}
		}

		if(isset($_POST['subindustry']) && !empty($_POST['subindustry'])){
			$subindustry 	= 	$_POST['subindustry'];
			if(isset($_POST['su_user_industry'])){
				$mainindustry 	= 	$_POST['su_user_industry'];
			}
			if($subindustry=='all' && isset($mainindustry)){
				$sql_brand="select *  from  brand_table where industry_id='$mainindustry'   order by brand_name asc";
				if($brands = Yii::app()->db3->createCommand($sql_brand)->queryAll()){
					echo '<div class="no-wrap">';
					$brandcount = 0;
					foreach ($brands as $value) {
						$this_brand_name=ucwords(strtolower($value["brand_name"]));  
						$this_brand_id=$value["brand_id"];
						echo '<div class="col-md-6"><label class="checkbox">';
						echo "<input id='this_brand_id' name='brands[]'  type='checkbox' value='$this_brand_id' />";
						echo '<label class="checkbox">'.$this_brand_name.'</label></label></div>';
						$brandcount++;
					}
					echo '</div>';
					echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
				}else{
					echo '<option>No Results Found</option>';
					echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
				}
			}elseif ($subindustry=='all' && !isset($mainindustry)) {
				echo "<p>Please Select a Sub Industry";
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{

				$sql_brand="select *  from  brand_table where sub_industry_id='$subindustry'   order by brand_name asc";
				if($brands = Yii::app()->db3->createCommand($sql_brand)->queryAll()){
					echo '<div class="no-wrap">';
					$brandcount = 0;
					foreach ($brands as $value) {
						$this_brand_name=ucwords(strtolower($value["brand_name"]));  
						$this_brand_id=$value["brand_id"];
						echo '<div class="col-md-6"><label class="checkbox">';
						echo "<input id='this_brand_id' name='brands[]'  type='checkbox' value='$this_brand_id' />";
						echo '<label class="checkbox">'.$this_brand_name.'</label></label></div>';
						$brandcount++;
					}
					echo '</div>';
					echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
				}else{
					echo '<option>No Results Found</option>';
					echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
				}
			}
			
		}

		if(isset($_POST['competitor_company']) && !empty($_POST['competitor_company'])){
			$company 	= 	$_POST['competitor_company'];

			$sql_industry="select industry.industry_name, industry.industry_id  from industry ,  industryreport where
			industry.industry_id =industryreport.industry_id and
			industryreport.company_id='$company'
			order by industry_name asc";

			if($subs = Yii::app()->db3->createCommand($sql_industry)->queryAll()){
				echo "<option value=''>-- Please Select a Value --</option>";
				if(Yii::app()->user->rpts_only==1){
					echo "<option value='all'>-- All Industries --</option>";
				}
				foreach ($subs as $value) {
					$this_industry_name=ucwords(strtolower($value["industry_name"]));  
					$this_industry_id=$value["industry_id"];
					echo '<option value="'.$this_industry_id.'">'.$this_industry_name.'</option>';
				}
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				echo '<option>No Results Found</option>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}
		}
	}
	
}
?>