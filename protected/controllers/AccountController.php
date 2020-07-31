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

class AccountController  extends Controller
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
				'actions'=>array('index','password'),
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
		$model=$this->loadModel(Yii::app()->user->user_id);
		if(isset($_POST['AnvilClients']))
		{
			$model->attributes=$_POST['AnvilClients'];

			$account_update['account'] = "anvil_clients";
			$account_update['user_id'] = Yii::app()->user->user_id;
			$account_update['surname'] = $model->surname;
			$account_update['firstname'] = $model->firstname;
			$account_update['email'] = $model->email;

			$url = Yii::app()->params->anvil_api . "accountupdate";
			$data = urldecode(http_build_query($account_update));
			$output = json_decode(Yii::app()->curl->post($url, $data));

			if($output === "success"){
				Yii::app()->user->setFlash('success', "<strong>Success ! </strong> Details Updated, Please log out and log in again");
			} else{
				Yii::app()->user->setFlash('danger', "<strong>Error ! </strong>Your Details were not Updated, please try later");
			}

			// if($model->save()){
			// 	Yii::app()->user->setFlash('success', "<strong>Success ! </strong> Details Updated, Please log out and log in again");
			// }else{
			// 	Yii::app()->user->setFlash('danger', "<strong>Error ! </strong>Your Details were not Updated, please try later");
			// }
		}

		if(isset($_POST['UserTable']))
		{
			$model->attributes=$_POST['UserTable'];

			$account_update['account'] = "user_table";
			$account_update['user_id'] = Yii::app()->user->user_id;
			$account_update['username'] = $model->username;
			$account_update['email'] = $model->email;

			$url = Yii::app()->params->anvil_api . "accountupdate";
			$data = urldecode(http_build_query($account_update));
			$output = json_decode(Yii::app()->curl->post($url, $data));

			if($output === "success"){
				Yii::app()->user->setFlash('success', "<strong>Success ! </strong> Details Updated, Please log out and log in again");
			} else{
				Yii::app()->user->setFlash('danger', "<strong>Error ! </strong>Your Details were not Updated, please try later");
			}

			// if($model->save()){
			// 	Yii::app()->user->setFlash('success', "<strong>Success ! </strong> Details Updated, Please log out and log in again");
			// }else{
			// 	Yii::app()->user->setFlash('danger', "<strong>Error ! </strong>Your Details were not Updated, please try later");
			// }
		}

		if(isset($_POST['AdfliteUsers']))
		{
			$model->attributes=$_POST['AdfliteUsers'];

			$account_update['account'] = "adflite_users";
			$account_update['user_id'] = Yii::app()->user->user_id;
			$account_update['surname'] = $model->surname;
			$account_update['firstname'] = $model->firstname;
			$account_update['email'] = $model->email;

			$url = Yii::app()->params->anvil_api . "accountupdate";
			$data = urldecode(http_build_query($account_update));
			$output = json_decode(Yii::app()->curl->post($url, $data));

			if($output === "success"){
				Yii::app()->user->setFlash('success', "<strong>Success ! </strong> Details Updated, Please log out and log in again");
			} else{
				Yii::app()->user->setFlash('danger', "<strong>Error ! </strong>Your Details were not Updated, please try later");
			}

			// if($model->save()){
			// 	Yii::app()->user->setFlash('success', "<strong>Success ! </strong> Details Updated, Please log out and log in again");
			// }else{
			// 	Yii::app()->user->setFlash('danger', "<strong>Error ! </strong>Your Details were not Updated, please try later");
			// }
		}

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

	public function actionPassword()
	{
		$model=$this->loadModel(Yii::app()->user->user_id);
		if(isset($_POST['AnvilClients'])){
			$old = md5($_POST['AnvilClients']['dummypass']);
			$new = md5($_POST['AnvilClients']['dummypass2']);
			$confirm = md5($_POST['AnvilClients']['dummypass3']);

			if($_POST['AnvilClients']['dummypass2'] =='' || $_POST['AnvilClients']['dummypass3']==''){
				Yii::app()->user->setFlash('danger', "<strong>Error ! You need to add values in the Password Fields! </strong>");
			}else{
				if($old==$model->password && $new==$confirm){

					$post_update_password['account'] = "anvil_clients";
					$post_update_password['user'] = Yii::app()->user->user_id;
					$post_update_password['password'] = $confirm;

					$url = Yii::app()->params->anvil_api . "pwordupdate";
					$data = urldecode(http_build_query($post_update_password));
					$output = json_decode(Yii::app()->curl->post($url, $data));

					if($output === "success"){
						Yii::app()->user->setFlash('success', "<strong>Success ! Your account password has been updated, login again to effect changes! </strong>");
					}

					// $model->password=$confirm;
					// if($model->save()){
					// 	Yii::app()->user->setFlash('success', "<strong>Success ! Your account password has been updated, login again to effect changes! </strong>");
					// }
				}else{
					Yii::app()->user->setFlash('danger', "<strong>Error ! Your account could not be updated, check your passwords again! </strong>");
				}
			}
		}

		if(isset($_POST['UserTable'])){
			$old = md5($_POST['UserTable']['dummypass']);
			$new = md5($_POST['UserTable']['dummypass2']);
			$confirm = md5($_POST['UserTable']['dummypass3']);

			if($_POST['UserTable']['dummypass2'] =='' || $_POST['UserTable']['dummypass3']==''){
				Yii::app()->user->setFlash('danger', "<strong>Error ! You need to add values in the Password Fields! </strong>");
			}else{
				if($old==$model->password && $new==$confirm){

					$post_update_password['account'] = "user_table";
					$post_update_password['user'] = Yii::app()->user->user_id;
					$post_update_password['password'] = $confirm;

					$url = Yii::app()->params->anvil_api . "pwordupdate";
					$data = urldecode(http_build_query($post_update_password));
					$output = json_decode(Yii::app()->curl->post($url, $data));

					if($output === "success"){
						Yii::app()->user->setFlash('success', "<strong>Success ! Your account password has been updated, login again to effect changes! </strong>");
					}

					// $model->password=$confirm;
					// if($model->save()){
					// 	Yii::app()->user->setFlash('success', "<strong>Success ! Your account password has been updated, login again to effect changes! </strong>");
					// }
				}else{
					Yii::app()->user->setFlash('danger', "<strong>Error ! Your account could not be updated, check your passwords again! </strong>");
				}
			}
		}

		if(isset($_POST['AdfliteUsers'])){
			$old = md5($_POST['AdfliteUsers']['dummypass']);
			$new = md5($_POST['AdfliteUsers']['dummypass2']);
			$confirm = md5($_POST['AdfliteUsers']['dummypass3']);

			if($_POST['AdfliteUsers']['dummypass2'] =='' || $_POST['AdfliteUsers']['dummypass3']==''){
				Yii::app()->user->setFlash('danger', "<strong>Error ! You need to add values in the Password Fields! </strong>");
			}else{
				if($old==$model->password && $new==$confirm){

					$post_update_password['account'] = "adflite_users";
					$post_update_password['user'] = Yii::app()->user->user_id;
					$post_update_password['password'] = $confirm;

					$url = Yii::app()->params->anvil_api . "pwordupdate";
					$data = urldecode(http_build_query($post_update_password));
					$output = json_decode(Yii::app()->curl->post($url, $data));

					if($output === "success"){
						Yii::app()->user->setFlash('success', "<strong>Success ! Your account password has been updated, login again to effect changes! </strong>");
					}

					// $model->password=$confirm;
					// if($model->save()){
					// 	Yii::app()->user->setFlash('success', "<strong>Success ! Your account password has been updated, login again to effect changes! </strong>");
					// }
				}else{
					Yii::app()->user->setFlash('danger', "<strong>Error ! Your account could not be updated, check your passwords again! </strong>");
				}
			}
		}

		$this->render('update',array('model'=>$model,));
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
		if(Yii::app()->user->usertype=='agency'){
			$username = Yii::app()->user->agencyusername;
			$company_id = Yii::app()->user->user_id;

			$sql_activate = "select * from user_table where username='$username' and company_id=$company_id and level=3";
			$model = UserTable::model()->findBySql($sql_activate);
		}elseif(Yii::app()->user->usertype=='adflite'){
			$user_id = Yii::app()->user->user_id;

			$sql_adflite = "select * from adflite_users where adflite_user_id=$user_id  ";
			$model = AdfliteUsers::model()->findBySql($sql_adflite);
		}else{
			$model = AnvilClients::model()->find('users_id=:a', array(':a'=>$id));
		}

		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		else
			return $model;
	}
	
}
?>