<?php

/**
* Site Controller Class
*
* @package     Anvil
* @subpackage  Controllers
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
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
		// Yii::app()->user->logout();
		// if(isset($_SESSION)){
		// 	session_start();
		// 	session_destroy();
		// }
		// $site_url = 'www.reelforge.com';
		// $this->redirect('http://'.$site_url.'/reelforge_back');
		$this->redirect(array('site/login'));
	}

	/** 
	*
	* @return  error page
	* @throws  InvalidArgumentException
	* @todo    This is the action to handle external exceptions.
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionError()
	{
		$this->layout='//layouts/error';
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/** 
	*
	* @return  contact page
	* @throws  InvalidArgumentException
	* @todo    Displays the contact page
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/** 
	*
	* @return  boolean
	* @throws  InvalidArgumentException
	* @todo    Displays the login page.
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionLogin()
	{
		$this->layout='//layouts/login';
		$model=new LoginForm;
		if(Yii::app()->user->isGuest){
			// if it is ajax validation request
			if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}

			// collect user input data
			if(isset($_POST['LoginForm']))
			{
				$model->attributes=$_POST['LoginForm'];
				// validate user input and redirect to the previous page if valid
				if($model->validate() && $model->login())
				{
					$this->redirect(array('home/index'));
				}
			}

			if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])){
				$model->username = $_POST['username'];
				$model->password = $_POST['password'];
				// validate user input and redirect to the previous page if valid
				if($model->validate() && $model->login())
				{
					$this->redirect(array('home/index'));
				}
			}
			// display the login form
			// $site_url = "../../reelforge/";
			/*$site_url = "https://reelforge.com/reelforge/site/index.php";
			$this->redirect($site_url);*/
			$this->render('login',array("model"=>$model));
		}else{
			$this->redirect(array('home/index'));
		}
	}

	/** 
	*
	* @return  boolean
	* @throws  InvalidArgumentException
	* @todo    Checks if a user is logged in.
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionSuper()
	{
		$model=new LoginForm;
		if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])){
			$model->username = $_POST['username'];
			$model->password = $_POST['password'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
			{
				echo 'success';
			}else{
				echo 'Check your Login';
			}
		}
	}

	/** 
	*
	* @return  boolean
	* @throws  InvalidArgumentException
	* @todo    Logs out the current user and redirect to homepage.
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionLogout()
	{
		Yii::app()->user->logout();
		if(isset($_SESSION)){
			session_start();
			session_destroy();
		}
		// $site_url = "../../reelforge/";
		// $this->redirect($site_url);
		$this->redirect(array('site/login'));
	}

	/** 
	*
	* @return  boolean
	* @throws  InvalidArgumentException
	* @todo    Used to change the site views
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionMinified()
	{
		if(isset($_POST['view'])){
			session_start();
			if(isset($_SESSION['view'])){
				echo 'session_set';
				if($_SESSION['view']=='minified'){
					unset($_SESSION['view']);
					echo $_SESSION['view']='unminified';
				}else{
					unset($_SESSION['view']);
					echo $_SESSION['view']='minified';
				}
			}else{
				echo $_SESSION['view'] = 'minified';
			}
		}else{
			echo 'unset';
		}
	}

	public function actionTest()
	{
		if (isset($_SERVER['REMOTE_ADDR'])){
			$userip = $_SERVER['REMOTE_ADDR'];
			$checkips = array($userip => '192.168.0.*');
			foreach ($checkips as $ip => $range) {
				$ok = Common::ip_in_range($ip, $range);
				if($ok==1){

				}else{
					header("Location: https://reelforge.com/reelforge/site/sitelogin");
					die();
				}

			}
		}
	}
}