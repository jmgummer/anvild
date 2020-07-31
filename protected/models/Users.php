<?php

/**
* Users Model Class
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
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property string $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $pass
 * @property integer $user_level
 * @property string $active
 * @property string $registration_date
 * @property string $last_login
 * @property integer $login_status
 * @property string $auth_session
 * @property string $username
 * @property integer $delete_status
 * @property string $temp_password
 * @property string $img
 * @property string $middle_name
 * @property integer $department
 * @property string $id_number
 * @property string $car_registration
 * @property string $phone
 * @property string $address
 * @property string $phone2
 */
class Users extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('first_name, last_name, email, pass, registration_date, last_login, auth_session, username, delete_status, temp_password, department', 'required'),
			array('user_level, login_status, delete_status, department', 'numerical', 'integerOnly'=>true),
			array('first_name', 'length', 'max'=>20),
			array('last_name', 'length', 'max'=>40),
			array('email, last_login, middle_name', 'length', 'max'=>80),
			array('pass, username, temp_password, img, id_number, car_registration, phone, phone2', 'length', 'max'=>255),
			array('active', 'length', 'max'=>32),
			array('auth_session', 'length', 'max'=>250),
			array('address', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, first_name, last_name, email, pass, user_level, active, registration_date, last_login, login_status, auth_session, username, delete_status, temp_password, img, middle_name, department, id_number, car_registration, phone, address, phone2', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'email' => 'Email',
			'pass' => 'Pass',
			'user_level' => 'User Level',
			'active' => 'Active',
			'registration_date' => 'Registration Date',
			'last_login' => 'Last Login',
			'login_status' => 'Login Status',
			'auth_session' => 'Auth Session',
			'username' => 'Username',
			'delete_status' => 'Delete Status',
			'temp_password' => 'Temp Password',
			'img' => 'Img',
			'middle_name' => 'Middle Name',
			'department' => 'Department',
			'id_number' => 'Id Number',
			'car_registration' => 'Car Registration',
			'phone' => 'Phone',
			'address' => 'Address',
			'phone2' => 'Phone2',
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

		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('pass',$this->pass,true);
		$criteria->compare('user_level',$this->user_level);
		$criteria->compare('active',$this->active,true);
		$criteria->compare('registration_date',$this->registration_date,true);
		$criteria->compare('last_login',$this->last_login,true);
		$criteria->compare('login_status',$this->login_status);
		$criteria->compare('auth_session',$this->auth_session,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('delete_status',$this->delete_status);
		$criteria->compare('temp_password',$this->temp_password,true);
		$criteria->compare('img',$this->img,true);
		$criteria->compare('middle_name',$this->middle_name,true);
		$criteria->compare('department',$this->department);
		$criteria->compare('id_number',$this->id_number,true);
		$criteria->compare('car_registration',$this->car_registration,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone2',$this->phone2,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}