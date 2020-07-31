<?php

/**
* Account Controller Class
*
* @package     Anvil
* @subpackage  Controllers
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/

class AdminController  extends Controller
{
	/**
	 * @var This is the Account controller
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
				'actions'=>array('index','brands', 'agencies','getdata'),
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
		$model = UserTable::model()->findAll();

		$this->render('index',array('model'=>$model,));
	}

	/** 
	*
	* @return  updated Password
	* @throws  InvalidArgumentException
	* @todo    Loads the password page and handles changes
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionBrands($id)
	{
		$model = BrandAgency::model()->find('auto_id=:a', array(':a'=>$id));
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}else{
			if(isset($_POST['BrandAgency']))
			{
				$model->attributes=$_POST['BrandAgency'];
				$model->start_date = date('Y-m-d',strtotime(str_replace('-', '/', $model->start_date)));
				$model->end_date = date('Y-m-d',strtotime(str_replace('-', '/', $model->end_date)));
				if($model->save()){
					Yii::app()->user->setFlash('success', "<strong>Success ! </strong> Details Updated");
				}else{
					Yii::app()->user->setFlash('danger', "<strong>Error ! </strong>Your Details were not Updated, please try later");
				}
			}
			$this->render('brands', array('model'=>$model));
		}
	}

	public function actionAgencies()
	{
		$model = $model = new StorySearch('search');
		$this->render('agencies', array('model'=>$model));
	}

	/** 
	*
	* @return  model of Anvil Clients
	* @throws  InvalidArgumentException
	* @todo    Returns an array of Anvil Client Records
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function loadModel($id)
	{
		$model = BrandAgency::model()->find('auto_id=:a', array(':a'=>$id));
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}else{
			return $model;
		}
			
	}

	public function actionGetdata()
	{
		/* Agency Brands */

		if(isset($_POST['agency_brand_company']) && !empty($_POST['agency_brand_company'])){
			$agency_id 	= 	$_POST['agency_brand_company'];
			$agency_brands = "SELECT  distinct(brand_agency.brand_id)as brand_id , brand_name, auto_id, start_date, end_date  
			FROM brand_agency, brand_table WHERE brand_agency.brand_id=brand_table.brand_id AND brand_agency.agency_id=$agency_id";
			if($brands = Yii::app()->db3->createCommand($agency_brands)->queryAll()){
				foreach ($brands as $value) {
					$this_brand_name=ucwords(strtolower($value["brand_name"]));  
					$this_brand_id=$value["brand_id"];
					$auto_id=$value["auto_id"];
					$start_date = $value["start_date"];
					$end_date = $value["end_date"];
					$update_link = Yii::app()->createUrl("admin/brands", array("id"=>$auto_id));
					echo "<div class='col-md-6'><p><a href='$update_link' target='_blank' >$this_brand_name - Start : $start_date End : $end_date</a></p></div>";
				}
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				echo '<p><strong>No Results Found</strong></p>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}
		}	
	}
}
?>