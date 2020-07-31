<?php

/**
* UserTable Model Class
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
 * This is the model class for table "user_table".
 *
 * The followings are the available columns in table 'user_table':
 * @property integer $company_id
 * @property string $company_name
 * @property string $company_rep_name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $description
 * @property string $registerDate
 * @property string $lastvisitDate
 * @property string $activation
 * @property integer $level
 * @property string $usertype
 * @property integer $master_id
 * @property string $login
 * @property string $trp
 * @property string $picture
 * @property string $show_rate
 * @property string $pofemail
 */
class UserTable extends CActiveRecord
{
	/* Included A Few Extra For Changing Passwords */
	public $dummypass;
	public $dummypass2;
	public $dummypass3;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserTable the static model class
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
		return 'user_table';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// array('description, trp, picture', 'required'),
			array('level, master_id', 'numerical', 'integerOnly'=>true),
			array('company_name, company_rep_name, username, email, password, description, picture', 'length', 'max'=>100),
			array('activation, usertype, login, trp, show_rate, pofemail', 'length', 'max'=>1),
			array('registerDate, lastvisitDate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('company_id, company_name, company_rep_name, username, email, password, description, registerDate, lastvisitDate, activation, level, usertype, master_id, login, trp, picture, show_rate, pofemail', 'safe', 'on'=>'search'),
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
			'company_id' => 'Company',
			'company_name' => 'Company Name',
			'company_rep_name' => 'Company Rep Name',
			'username' => 'Username',
			'email' => 'Email',
			'password' => 'Password',
			'description' => 'Description',
			'registerDate' => 'Register Date',
			'lastvisitDate' => 'Lastvisit Date',
			'activation' => 'Activation',
			'level' => 'Level',
			'usertype' => 'Usertype',
			'master_id' => 'Master',
			'login' => 'Login',
			'trp' => 'Trp',
			'picture' => 'Picture',
			'show_rate' => 'Show Rate',
			'pofemail' => 'Pofemail',
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

		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('company_rep_name',$this->company_rep_name,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('registerDate',$this->registerDate,true);
		$criteria->compare('lastvisitDate',$this->lastvisitDate,true);
		$criteria->compare('activation',$this->activation,true);
		$criteria->compare('level',$this->level);
		$criteria->compare('usertype',$this->usertype,true);
		$criteria->compare('master_id',$this->master_id);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('trp',$this->trp,true);
		$criteria->compare('picture',$this->picture,true);
		$criteria->compare('show_rate',$this->show_rate,true);
		$criteria->compare('pofemail',$this->pofemail,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}