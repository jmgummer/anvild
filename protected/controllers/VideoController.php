<?php

class VideoController extends Controller
{
	/**
	 * @var This is the admin controller
	 */
	public $layout='//layouts/swf';

	public function actionIndex()
	{
		if(isset($_GET['type']) && isset($_GET['file_id'])){
			if($_GET['type']=='dm'){
				$story_id = $_GET['file_id'];
				if($model = Djmentions::model()->find('auto_id=:a', array(':a'=>$story_id))){
					$this->render('index', array('model'=>$model));
				}
			}elseif ($_GET['type']=='el') {
				$story_id = $_GET['file_id'];
				if($model = ReelforgeSample::model()->find('reel_auto_id=:a', array(':a'=>$story_id))){
					$this->render('sample', array('model'=>$model));
				}
			}else{
				$this->render('error');
			}
		}else{
			$this->render('error');
		}
	}

	public function actionView($id)
	{
		if(isset($_GET['type']) && isset($_GET['file_id'])){
			if($_GET['type']=='dm'){
				$story_id = $_GET['file_id'];
				if($model = Djmentions::model()->find('auto_id=:a', array(':a'=>$story_id))){
					$this->render('index', array('model'=>$model));
				}
			}
		}else{
			$this->render('error');
			
		}
		// if($model = Story::model()->find('Story_ID=:a', array(':a'=>$id))){
		// 	$this->render('index',array('model'=>$model));
		// }else{
		// 	throw new CHttpException(404,'The requested page does not exist.');
		// }
	}
}