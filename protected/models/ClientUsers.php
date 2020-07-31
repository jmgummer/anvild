<?php

/**
* ClientUsers Model Class
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
 * @property integer $client_users_id
 * @property string $username
 * @property string $surname
 * @property string $firstname
 * @property string $password
 * @property string $company
 * @property integer $co_id
 * @property string $co_hash
 * @property string $email
 * @property string $session_id
 * @property integer $user_status
 * @property integer $crm
 * @property string $reelmedia
 * @property string $reelonline
 * @property string $spreadsheet_attach
 * @property string $template
 */
class ClientUsers extends CActiveRecord
{
	/* Included A Few Extra For Changing Passwords */
	public $dummypass;
	public $dummypass2;
	public $dummypass3;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ClientUsers the static model class
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
		return Yii::app()->db2;
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
			// array('co_hash, session_id, crm', 'required'),
			array('co_id, user_status, crm', 'numerical', 'integerOnly'=>true),
			array('username, surname, firstname, company, email', 'length', 'max'=>100),
			array('password', 'length', 'max'=>50),
			array('co_hash, session_id', 'length', 'max'=>11),
			array('reelmedia, reelonline, spreadsheet_attach, template', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('client_users_id, username, surname, firstname, password, company, co_id, co_hash, email, session_id, user_status, crm, reelmedia, reelonline, spreadsheet_attach, template', 'safe', 'on'=>'search'),
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
			'client_users_id' => 'Client Users',
			'username' => 'Username',
			'surname' => 'Surname',
			'firstname' => 'Firstname',
			'password' => 'Password',
			'company' => 'Company',
			'co_id' => 'Co',
			'co_hash' => 'Co Hash',
			'email' => 'Email',
			'session_id' => 'Session',
			'user_status' => 'User Status',
			'crm' => 'Crm',
			'reelmedia' => 'Reelmedia',
			'reelonline' => 'Reelonline',
			'spreadsheet_attach' => 'Spreadsheet Attach',
			'template' => 'Template',
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

		$criteria->compare('client_users_id',$this->client_users_id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('surname',$this->surname,true);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('co_id',$this->co_id);
		$criteria->compare('co_hash',$this->co_hash,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('session_id',$this->session_id,true);
		$criteria->compare('user_status',$this->user_status);
		$criteria->compare('crm',$this->crm);
		$criteria->compare('reelmedia',$this->reelmedia,true);
		$criteria->compare('reelonline',$this->reelonline,true);
		$criteria->compare('spreadsheet_attach',$this->spreadsheet_attach,true);
		$criteria->compare('template',$this->template,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getUserName()
	{
		return ucfirst($this->firstname).' '.ucfirst($this->surname);
	}
}