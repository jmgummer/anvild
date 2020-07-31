<?php

/**
* AnvilClients Model Class
*
* @package     Anvil
* @subpackage  Models
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/

/**
 * This is the model class for table "client_users".
 *
 * The followings are the available columns in table 'client_users':
 * @property integer $users_id
 * @property string $username
 * @property string $surname
 * @property string $firstname
 * @property string $password
 * @property integer $co_id
 * @propertyAnvilClients string $email
 * @property string $user_status
 * @property string $trp
 */
class AnvilClients extends CActiveRecord
{
	/* Included A Few Extra For Changing Passwords */
	public $dummypass;
	public $dummypass2;
	public $dummypass3;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AnvilClients the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return CDbConnection database connection
	 */
	public function getDbConnection()
	{
		return Yii::app()->db3;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'client_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('surname, firstname, email', 'required'),
			array('co_id', 'numerical', 'integerOnly'=>true),
			array('username, surname, firstname', 'length', 'max'=>50),
			array('password, email', 'length', 'max'=>100),
			array('user_status, trp', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('users_id, username, surname, firstname, password, co_id, email, user_status, trp', 'safe', 'on'=>'search'),
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
			'users_id' => 'Users',
			'username' => 'Username',
			'surname' => 'Surname',
			'firstname' => 'Firstname',
			'password' => 'Password',
			'co_id' => 'Co',
			'email' => 'Email',
			'user_status' => 'User Status',
			'trp' => 'Trp',
			'dummypass'=>'Current Password',
			'dummypass2'=>'New Password',
			'dummypass3'=>'Confirm Password'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('users_id',$this->users_id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('surname',$this->surname,true);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('co_id',$this->co_id);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('user_status',$this->user_status,true);
		$criteria->compare('trp',$this->trp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getUserName()
	{
		return ucfirst($this->firstname).' '.ucfirst($this->surname);
	}

	public function getCompany()
	{
		if($company = UserTable::model()->find('company_id=:a', array(':a'=>$this->co_id))){
			return $company->company_name;
		}else{
			return 'Unknown';
		}
	}

	public function getUserLevel()
	{
		if($company = UserTable::model()->find('company_id=:a', array(':a'=>$this->co_id))){
			return $company->level;
		}else{
			return 0;
		}
	}
}