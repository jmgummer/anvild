<?php

/**
* Home Controller Class
*
* @package     Anvil
* @subpackage  Controllers
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/

class HomeController extends Controller
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','print','view','video','tests','pdf','excel','getdata'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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
		$model=new StorySearch;
		if(isset($_POST['startdate'])){
			$this->render('dashboard',array('model'=>$model));
		}else{
			$this->render('index',array('model'=>$model));
		}
	}

	/** 
	*
	* @return  dashboard
	* @throws  InvalidArgumentException
	* @todo    Returns the dashboard page
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionDashboard()
	{
		$model=new StorySearch;
		$this->render('index',array('model'=>$model));
	}

	/** 
	*
	* @return  print page
	* @throws  InvalidArgumentException
	* @todo    Display Print Page and Handle Print Manipulations
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionPrint()
	{
		$model = new StorySearch('search');
		$model->unsetAttributes();
		if(isset($_POST['StorySearch']))
		{
			$model->attributes=Yii::app()->input->stripClean($_POST['StorySearch']);
			$model->startdate = date('Y-m-d',strtotime(str_replace('-', '/', $model->startdate)));
			$model->enddate = date('Y-m-d',strtotime(str_replace('-', '/', $model->enddate)));
		}
		$this->render('stories',array('model'=>$model));
	}

	/** 
	*
	* @return  pdf document
	* @throws  InvalidArgumentException
	* @todo    Generate PDF Document
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/
	
	public function actionPdf()
	{
	  	$model = new StorySearch('search');
		$model->unsetAttributes();
		if(isset($_POST['StorySearch']))
		{
			$model->attributes=Yii::app()->input->stripClean($_POST['StorySearch']);
			$model->startdate = date('Y-m-d',strtotime(str_replace('-', '/', $model->startdate)));
			$model->enddate = date('Y-m-d',strtotime(str_replace('-', '/', $model->enddate)));
		}
		$mPDF1 = Yii::app()->ePdf2->Download('pdf',array('model'=>$model),'PDF');
	}

	/** 
	*
	* @return  excel document
	* @throws  InvalidArgumentException
	* @todo    Return and Excel Document
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionExcel()
	{
		$todays = date('Y-m-d');
		$startdate = $enddate = $todays;
		$search = ' ';
		/*
		*  Adding Country Code
		*  Current Default value is Kenya
		*/
		$country = 1;
		$industries = '';
		// Adding backdate
		$cat_identifier = 1;
		$type_identifier = 1;

		$company_words = Company::model()->find('company_id=:a order by keywords', array(':a'=>Yii::app()->user->company_id));
		$backdate = $company_words->backdate;

		if(isset($_GET['startdate'])){
		  $startdate= $_GET['startdate'];
		}
		if(isset($_GET['enddate'])){
		  $enddate= $_GET['enddate'];
		}
		if(isset($_GET['search'])){
		  $search= $_GET['search'];
		}
		if(isset($_GET['industries'])){
		  $industries= $_GET['industries'];
		}

		if(isset($_GET['cat_identifier'])){
		  $cat_identifier= $_GET['cat_identifier'];
		}
		if(isset($_GET['type_identifier'])){
		  $type_identifier= $_GET['type_identifier'];
		}

		if($type_identifier==1){
			if($cat_identifier==1){
				$option = 1;
			}
			if($cat_identifier==2){
				$option = 2;
			}
			if($cat_identifier==3){
				$option = 3;
			}
		}
		if($type_identifier==2){
			if($cat_identifier==1){
				$option = 4;
			}
			if($cat_identifier==2){
				$option = 5;
			}
			if($cat_identifier==3){
				$option = 6;
			}
		}
		if($type_identifier==3){
			if($cat_identifier==1){
				$option = 7;
			}
			if($cat_identifier==2){
				$option = 8;
			}
			if($cat_identifier==3){
				$option = 9;
			}
		}
    	$stories = ExcelStories::GetMainOption(Yii::app()->user->company_id,$startdate,$enddate,$search,$backdate,$country,$industries,$option);
		Yii::app()->end();
	}

	/** 
	*
	* @return  users model
	* @throws  InvalidArgumentException
	* @todo    Returns a list of users
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		elseif($model->user_id!=Yii::app()->user->s_uid)
			throw new CHttpException(404,'The requested page does not exist.');
		else
			return $model;
	}

	/** 
	*
	* @return  link to file
	* @throws  InvalidArgumentException
	* @todo    Returns the link to a file
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionView($id)
	{
		if($id !=NULL && isset($_GET['ext_link'])){
			$link = $_GET['ext_link'];
			header("Location: ".$link);
		}else{
			throw new Exception("Error Processing Request", 1);
		}
	}

	/** 
	*
	* @return  tests page
	* @throws  InvalidArgumentException
	* @todo    Returns the Test Page
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionTests()
	{
		$this->render('tests');
	}

	/** 
	*
	* @return  video page
	* @throws  InvalidArgumentException
	* @todo    Returns the Video Page
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionVideo()
	{
		$this->render('video');
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
			$sql_sub_industry="select *  from  sub_industry where industry_id='$industry'  order by sub_industry_name asc";

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

		if(isset($_POST['competitor_company']) && !empty($_POST['competitor_company'])){
			$company 	= 	$_POST['competitor_company'];

			$sql_industry="select industry.industry_name, industry.industry_id  from industry ,  industryreport where
			industry.industry_id =industryreport.industry_id and
			industryreport.company_id='$company'
			order by industry_name asc";

			if($subs = Yii::app()->db3->createCommand($sql_industry)->queryAll()){
				echo "<option value=''>-- Please Select a Value --</option>";
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

	/** 
	*
	* @return  boolean
	* @throws  InvalidArgumentException
	* @todo    Performs Ajax Validation on Browser
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='category-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
