<?php

/**
 * This is the model class for table "adflite_users".
 *
 * The followings are the available columns in table 'adflite_users':
 * @property integer $adflite_user_id
 * @property string $username
 * @property string $surname
 * @property string $firstname
 * @property string $password
 * @property integer $adflite_id
 * @property string $email
 * @property string $user_status
 * @property string $user_level
 * @property string $email_alert
 */
class AdfliteUsers extends CActiveRecord
{
	/* Included A Few Extra For Changing Passwords */
	public $dummypass;
	public $dummypass2;
	public $dummypass3;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'adflite_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// array('username, surname, firstname, password, adflite_id, email, user_status, user_level, email_alert', 'required'),
			array('adflite_id', 'numerical', 'integerOnly'=>true),
			array('username, surname, firstname, password, email', 'length', 'max'=>50),
			array('user_status, user_level, email_alert', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('adflite_user_id, username, surname, firstname, password, adflite_id, email, user_status, user_level, email_alert', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'adflite_user_id' => 'Adflite User',
			'username' => 'Username',
			'surname' => 'Surname',
			'firstname' => 'Firstname',
			'password' => 'Password',
			'adflite_id' => 'Adflite',
			'email' => 'Email',
			'user_status' => 'User Status',
			'user_level' => 'User Level',
			'email_alert' => 'Email Alert',
			'dummypass'=>'Current Password',
			'dummypass2'=>'New Password',
			'dummypass3'=>'Confirm Password'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('adflite_user_id',$this->adflite_user_id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('surname',$this->surname,true);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('adflite_id',$this->adflite_id);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('user_status',$this->user_status,true);
		$criteria->compare('user_level',$this->user_level,true);
		$criteria->compare('email_alert',$this->email_alert,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db3;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AdfliteUsers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getClientName()
	{
		return strtoupper($this->firstname).' '.strtoupper($this->surname);
	}

	public function getCountry(){
		$sql = "SELECT * FROM adflite WHERE adflite_id = $this->adflite_id";
		if($adflite = Adflite::model()->findBySql($sql)){
			return $adflite->country_id;
		}else{
			return 1;
		}
	}

	public function getCountryCode(){
		$sql = "SELECT * FROM country WHERE country_id = $this->COuntry";
		if($country = Country::model()->findBySql($sql)){
			return $country->country_code;
		}else{
			return 'KE';
		}
	}
}
