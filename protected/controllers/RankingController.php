<?php

/**
* Ranking Controller Class
*
* @package     Anvil
* @subpackage  Controllers
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/

class RankingController extends Controller
{
	/**
	 * @var This is the admin controller
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
				'actions'=>array('companyspenders','brandspenders','getdata','summaryspends','stationspends','brandspends','companyspends','stationsummary'),
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

	/** 
	*
	* @return  companyspends page
	* @throws  InvalidArgumentException
	* @todo    Performs Manipulations for Company Spends
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionCompanyspenders()
	{
		$model = new StorySearch;
		if(isset($_POST['enddate'])){
			$this->render('companyspenders_processed');
		}else{
			$this->render('companyspenders', array('model'=>$model));
		}
	}

	/** 
	*
	* @return  brandspends page
	* @throws  InvalidArgumentException
	* @todo    Performs Manipulations for Brands
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionBrandspenders()
	{
		$model = new StorySearch;
		if(isset($_POST['enddate'])){
			$this->render('brandspenders_processed');
		}else{
			$this->render('brandspenders', array('model'=>$model));
		}
	}

	/** 
	*
	* @return  summaryspends page
	* @throws  InvalidArgumentException
	* @todo    Handles manipulations for Summary Spends
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/
	

	public function actionSummaryspends()
	{
		$model = new StorySearch;
		if(isset($_POST['enddate'])){
			$this->render('summaryspends_processed');
		}else{
			$this->render('summaryspends', array('model'=>$model));
		}
	}

	public function actionStationspends()
	{
		$model = new StorySearch;
		if(isset($_POST['enddate'])){
			$this->render('stationspends_processed');
		}else{
			$this->render('stationspends', array('model'=>$model));
		}
	}

	public function actionBrandspends()
	{
		$model = new StorySearch;
		if(isset($_POST['enddate'])){
			$this->render('brandspends_processed');
		}else{
			$this->render('brandspends', array('model'=>$model));
		}
	}

	public function actionCompanyspends()
	{
		$model = new StorySearch;
		if(isset($_POST['enddate'])){
			$this->render('companyspends_processed');
		}else{
			$this->render('companyspends', array('model'=>$model));
		}
	}

	public function actionStationsummary()
	{
		$model = new StorySearch;
		if(isset($_POST['enddate'])){
			$model->enddate = $_POST['enddate'];
			$model->startdate = $_POST['startdate'];
			$model->reportformat = $_POST['reportformat'];
		}
		$this->render('stationsummary', array('model'=>$model));
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
		/* Country ID From Session */
		
		if(isset(Yii::app()->user->country_id)){
			$country_id = Yii::app()->user->country_id;
		}else{
			$country_id = 1;
		}

		/* For Channel Data */

		if(isset($_POST['channel'])){
			$channel = $_POST['channel'];
			$client_id = Yii::app()->user->company_id;
			$sql_site="SELECT distinct(brand_name),brand_table.brand_id from brand_table,outdoor_channel_entries where brand_table.brand_id=outdoor_channel_entries.brand_id and outdoor_channel_entries.company_id='$channel' and brand_table.company_id='$client_id' order by brand_name ; ";
			
			if($brands = Yii::app()->db3->createCommand($sql_site)->queryAll()){
				echo "<option value=''>-SELECT-</option>";
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

		/* For Region Data */

		if(isset($_POST['region']) && isset($_POST['company_id']) && !empty($_POST['company_id'])){
			$this_province_id 	= 	$_POST['region'];
			$this_company_id 	= 	$_POST['company_id'];

			if($this_province_id=="all") {
			$sql_site="SELECT ocs.auto_id, ocs.site_name, ocs.site_location from outdoor_channel_sites ocs, towns where ocs.company_id='$this_company_id' and ocs.site_town=towns.auto_id order by site_name asc ";
			} else {
			$sql_site="SELECT ocs.auto_id, ocs.site_name, ocs.site_location from outdoor_channel_sites ocs, towns where ocs.company_id='$this_company_id' and towns.province_id='$this_province_id' and ocs.site_town=towns.auto_id order by site_name asc ";
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

		/* For Industry Data */

		if(isset($_POST['industry']) && !empty($_POST['industry'])){
			$industry 	= 	$_POST['industry'];
			$sql_sub_industry="SELECT *  from  sub_industry where industry_id='$industry'  order by sub_industry_name asc";

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

		/* For More Industry Data..... */

		if(isset($_POST['mainindustry']) && !empty($_POST['mainindustry'])){
			$industry 	= 	$_POST['mainindustry'];
			$sql_sub_industry="SELECT *  from  sub_industry where industry_id='$industry'  order by sub_industry_name asc";

			if($subs = Yii::app()->db3->createCommand($sql_sub_industry)->queryAll()){
				echo "<option value='all'>-SELECT-</option>";
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

		/* Agency Brands */

		if(isset($_POST['agency_brand_company']) && !empty($_POST['agency_brand_company'])){
			$agency_id 	= 	$_POST['agency_brand_company'];
			$agency_brands = "SELECT  distinct(brand_agency.brand_id) ,brand_name  FROM brand_agency, brand_table WHERE brand_agency.brand_id=brand_table.brand_id AND brand_agency.agency_id=$agency_id";
			if($brands = Yii::app()->db3->createCommand($agency_brands)->queryAll()){
				echo '<div class="no-wrap">';
				foreach ($brands as $value) {
					$this_brand_name=ucwords(strtolower($value["brand_name"]));  
					$this_brand_id=$value["brand_id"];
					echo '<div class="col-md-6">';
					echo "<label class='checkbox'><input id='this_brand_id' name='brands[]'  type='checkbox' value='$this_brand_id' />".$this_brand_name."</label>";
					echo '</div>';
				}
				echo '</div>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				echo '<option>No Results Found</option>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}
		}

		/* For Sub Industry Data */

		if(isset($_POST['subindustry']) && !empty($_POST['subindustry'])){
			$subindustry 	= 	$_POST['subindustry'];
			$sql_brand="SELECT *  from  brand_table where sub_industry_id='$subindustry'   order by brand_name asc";
			if($brands = Yii::app()->db3->createCommand($sql_brand)->queryAll()){
				echo '<div class="no-wrap">';
				foreach ($brands as $value) {
					$this_brand_name=ucwords(strtolower($value["brand_name"]));  
					$this_brand_id=$value["brand_id"];
					echo '<div class="col-md-6"><label class="checkbox">';
					echo "<input id='this_brand_id' name='brands[]'  type='checkbox' value='$this_brand_id' />";
					echo '<label class="checkbox">'.$this_brand_name.'</label></label></div>';
				}
				echo '</div>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				echo '<option>No Results Found</option>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}
		}

		if(isset($_POST['mediatype']) && !empty($_POST['mediatype'])){
			$mediatype 	= 	$_POST['mediatype'];
			if($mediatype=='e'){
				echo "<option value=''>-- SELECT --</option>";
        		echo "<option value='radio'>Radio</option>";
        		echo "<option value='tv'>TV</option>";
        		echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
        		echo '<script type="text/javascript"> populatestations(); </script>';
			}elseif($mediatype=='a'){
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
				echo '<script type="text/javascript"> populatestations(); </script>';
			}
		}

		if(isset($_POST['mediaoptions']) && !empty($_POST['mediaoptions'])){
			$mediaoptions 	= 	$_POST['mediaoptions'];
			if($mediaoptions=="tv") {
				$sql_station="SELECT distinct(station.station_name),station.station_id,station.station_code from station where country_id = $country_id and station_type='tv' and online=1";
				$checked_value=10000001;
			}else{
				$sql_station="SELECT distinct(station.station_name),station.station_id,station.station_code from station where country_id = $country_id and station_type='radio' and online=1";
				$checked_value=10000002;
			}
			if($stations = Yii::app()->db3->createCommand($sql_station)->queryAll()){
				echo '<div class="no-wrap">';
				echo "<div class='col-md-6 unresized'><label class'radio'><input id='stations' name='stations'  type='radio' value='$checked_value' checked /><label> <strong>ALL</strong></label></label></div>";
				foreach ($stations as $value) {
					$this_station_name=$value["station_name"];  
					$this_station_id=$value["station_id"];

					echo '<div class="col-md-6 resized"><label class="radio">';
					echo "<input id='stations' name='stations'  type='radio' value='$this_station_id' />";
					echo '<label>'.$this_station_name.'</label></label></div>';
				}
				echo '</div>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				echo '<option>No Results Found</option>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}
		}

		if(isset($_POST['mediatype2']) && !empty($_POST['mediatype2'])){
			$mediatype 	= 	$_POST['mediatype2'];
			if($mediatype=="p") {
				$sql_station="SELECT * from mediahouse  where country_id = $country_id  ";
				$checked_value=10000003;
				if($stations = Yii::app()->db3->createCommand($sql_station)->queryAll()){
					echo '<div class="no-wrap">';
					echo "<div class='col-md-6 unresized'><label class'radio'><input id='stations' name='stations'  type='radio' value='$checked_value' checked /><label> <strong>ALL</strong></label></label></div>";
					foreach ($stations as $value) {
						$this_mediahouse_name=$value["Media_House_List"];  
						$this_mediahouse_id=$value["Media_House_ID"];

						echo '<div class="col-md-6 resized"><label class="radio">';
						echo "<input id='stations' name='stations'  type='radio' value='$this_mediahouse_id' />";
						echo '<label>'.$this_mediahouse_name.'</label></label></div>';
					}
					echo '</div>';
					echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
				}else{
					echo '<option>No Results Found</option>';
					echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
				}
			}else{
				$sql_station="SELECT distinct(station.station_name),station.station_id,station.station_code from station where country_id = $country_id and station_type='radio' and online=1";
				$checked_value=10000002;
				if($stations = Yii::app()->db3->createCommand($sql_station)->queryAll()){
					echo '<div class="no-wrap">';
					echo "<div class='col-md-6 unresized'><label class'radio'><input id='stations' name='stations'  type='radio' value='$checked_value' checked /><label> <strong>ALL</strong></label></label></div>";
					foreach ($stations as $value) {
						$this_station_name=$value["station_name"];  
						$this_station_id=$value["station_id"];

						echo '<div class="col-md-6 resized"><label class="radio">';
						echo "<input id='stations' name='stations'  type='radio' value='$this_station_id' />";
						echo '<label>'.$this_station_name.'</label></label></div>';
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

			$sql_industry="SELECT industry.industry_name, industry.industry_id  from industry ,  industryreport where
			industry.industry_id =industryreport.industry_id and
			industryreport.company_id='$company'
			order by industry_name asc";

			if($subs = Yii::app()->db3->createCommand($sql_industry)->queryAll()){
				echo "<option value=''>-- Please SELECT a Value --</option>";
				echo "<option value='all'>-- All Industries --</option>";
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