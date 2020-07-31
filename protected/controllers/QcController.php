<?php

/**
* Media Controller Class
*
* @package     Anvil
* @subpackage  Controllers
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/

class QcController extends Controller
{

	/*
	** Load the default layout for this section
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
			array('allow',
				'actions'=>array('index','companybrands','competitorpof','analyticspof','agencyreport','getdata','test'),
				'users'=>array('admin'),
			),
			array('allow',
				'actions'=>array('stationlog','pof','mediahouse','audience','companymediareport','compliance'),
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
		$this->render('index');
	}

	public function actionTest()
	{
		// $this->render('index');
		Tester::NCCReports();
	}

	public function actionStationlog()
	{
		$model = new StorySearch;
		if(isset($_POST['addate'])){
			$model->station=$_POST['station'];
			$model->adtype=$_POST['adtype'];
			$model->addate=$_POST['addate'];
		}
		$this->render('stationlog', array('model'=>$model));
	}

	public function actionMediahouse()
	{
		$model = new StorySearch;
		if(isset($_POST['addate'])){
			$model->station=$_POST['station'];
			$model->adtype=$_POST['adtype'];
			$model->addate=$_POST['addate'];
		}
		$this->render('mediahouse', array('model'=>$model));
	}

	public function actionPof()
	{
		$model = new StorySearch;
		if(isset($_POST['addate'])){
			$model->station=$_POST['station'];
			$model->adtype=$_POST['adtype'];
			$model->addate=$_POST['addate'];
		}
		$this->render('pof', array('model'=>$model));
	}

	public function actionAgencyreport(){
		$model = new StorySearch;
		if(isset($_POST['company'])){
			$model->company=$_POST['company'];
			$model->startdate=$_POST['startdate'];
			$model->enddate=$_POST['enddate'];
		}
		$this->render('agencyreport', array('model'=>$model));
	}

	public function actionAudience()
	{
		$model = new StorySearch;
		if(isset($_POST['addate'])){
			$model->station=$_POST['station'];
			$model->adtype=$_POST['adtype'];
			$model->addate=$_POST['addate'];
		}
		$this->render('audience', array('model'=>$model));
	}

	public function actionCompliance()
	{
		$model = new StorySearch;
		if(isset($_POST['StorySearch'])){
			$model->attributes=Yii::app()->input->stripClean($_POST['StorySearch']);
		}
		$this->render('compliance', array('model'=>$model));
	}


	public function actionCompanybrands()
	{
		$model = new StorySearch;
		$this->render('companybrands', array('model'=>$model));
	}

	public function actionCompetitorpof()
	{
		$model = new StorySearch;
		if(isset($_GET['country'])){
			$model->country =$_GET['country'];
		}
		$this->render('competitor_pof', array('model'=>$model));
	}

	public function actionAnalyticspof()
	{
		$model = new StorySearch;
		if(isset($_GET['country'])){
			$model->country =$_GET['country'];
		}
		$this->render('analytics_pof', array('model'=>$model));
	}

	public function actionCompanymediareport(){
		$model = new StorySearch;
		if(isset($_GET['country'])){
			$model->country =$_GET['country'];
		}
		$this->render('companymedia', array('model'=>$model));
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
			$sql_site="SELECT distinct(brand_name),brand_table.brand_id from brand_table,outdoor_channel_entries where brand_table.brand_id=outdoor_channel_entries.brand_id and outdoor_channel_entries.company_id='$channel' and brand_table.company_id='$client_id' order by brand_name ; ";
			
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

		if(isset($_POST['industry']) && !empty($_POST['industry'])){
			$industry 	= 	$_POST['industry'];
			$sql_sub_industry="SELECT *  from  sub_industry where industry_id='$industry'  order by sub_industry_name asc";

			if($subs = Yii::app()->db3->createCommand($sql_sub_industry)->queryAll()){
				foreach ($subs as $value) {
					$this_sub_industry_name=ucwords(strtolower($value["sub_industry_name"]));  
					$this_sub_industry_id=$value["auto_id"];
					echo '<div class="col-md-6"><label class="checkbox">';
					echo "<input id='this_sub_industry_id' name='sub_industry_name[]'  type='checkbox' value='$this_sub_industry_id' />";
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
			$sql_sub_industry="SELECT *  from  sub_industry where industry_id='$industry'  order by sub_industry_name asc";

			if($subs = Yii::app()->db3->createCommand($sql_sub_industry)->queryAll()){
				echo "<option value='all'>-Select-</option>";
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

		/* Select Company Industries
		** This Query Works For Agencies
		** Do not Modify Unless you Understand
		*/

		if(isset($_POST['company']) && !empty($_POST['company'])){
			$company 	= 	$_POST['company'];

			$sql_industry="SELECT industry.industry_name, industry.industry_id  from industry ,  industryreport where
			industry.industry_id =industryreport.industry_id and
			industryreport.company_id='$company'
			order by industry_name asc";

			if($subs = Yii::app()->db3->createCommand($sql_industry)->queryAll()){
				echo "<option value=''>-- Please Select a Value --</option>";
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

		/* Select Company Brands
		** This Query Works For Agencies
		** Do not Modify Unless you Understand
		*/

		if(isset($_POST['agency_industry']) && isset($_POST['agency_id']) && isset($_POST['agency_company'])){
			$company 			= 	$_POST['agency_company'];
			$agency_id 			= 	$_POST['agency_id'];
			$agency_industry 	= 	$_POST['agency_industry'];

			if($agency_industry=='all'){
				$sql_ad="SELECT distinct(brand_name), brand_table.brand_id from agency_brand, brand_table 
				where agency_brand.agency_id='$agency_id' and brand_table.brand_id=agency_brand.brand_id  and brand_table.company_id='$company' order by brand_name asc ";
			}else{
				$sql_ad="SELECT distinct(brand_name), brand_table.brand_id from agency_brand, brand_table 
				where agency_brand.agency_id='$agency_id' and brand_table.brand_id=agency_brand.brand_id  and 
				brand_table.industry_id='$agency_industry' and brand_table.company_id='$company' order by brand_name asc ";
			}

			if(Yii::app()->user->rpts_only==1){
				if($agency_industry=='all'){
					$sql_ad="SELECT distinct(brand_name), brand_table.brand_id from brand_table 
					where brand_table.company_id='$company' order by brand_name asc ";
				}else{
					$sql_ad="SELECT distinct(brand_name), brand_table.brand_id from brand_table 
					where brand_table.company_id='$company' and brand_table.industry_id='$agency_industry' order by brand_name asc ";
				}
			}

			if($brands = Yii::app()->db3->createCommand($sql_ad)->queryAll()){
				$count = 0;
				foreach ($brands as $key) {
					$brandid = $key['brand_id'];
					$brandname = $key['brand_name'];
					echo '<label class="checkbox">
					<input id="brands_'.$count.'" value="'.$brandid.'" name="brands[]" type="checkbox">
					<label for="brands_'.$count.'">	'.$brandname.' </label></label>';
					$count++;
				}
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				echo '<option>No Results Found</option>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}
		}

		if(isset($_POST['qccompany'])){
			$company = $_POST['qccompany'];
			$sql_ad="SELECT brand_name, brand_id FROM brand_table WHERE brand_table.company_id='$company' order by brand_name asc ";
			if($brands = Yii::app()->db3->createCommand($sql_ad)->queryAll()){
				$count = 0;
				foreach ($brands as $key) {
					$brandid = $key['brand_id'];
					$brandname = $key['brand_name'];
					echo '<label class="checkbox">
					<input id="brands_'.$count.'" value="'.$brandid.'" name="brands[]" type="checkbox">
					<label for="brands_'.$count.'">	'.$brandname.' </label></label>';
					$count++;
				}
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				echo '<option>No Results Found</option>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}
		}
	}
}
