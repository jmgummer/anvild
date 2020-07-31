<?php

/**
* BillboardCompany Model Class
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
 * This is the model class for table "billboard_company".
 *
 * The followings are the available columns in table 'billboard_company':
 * @property integer $company_id
 * @property string $company_name
 * @property string $company_rep_name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $description
 * @property string $registerDate
 * @property string $lastvisitDate
 * @property string $login
 */
class BillboardCompany extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BillboardCompany the static model class
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
		return 'billboard_company';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('description', 'required'),
			array('company_name, company_rep_name, username, email, password', 'length', 'max'=>100),
			array('login', 'length', 'max'=>1),
			array('registerDate, lastvisitDate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('company_id, company_name, company_rep_name, username, email, password, description, registerDate, lastvisitDate, login', 'safe', 'on'=>'search'),
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
			'login' => 'Login',
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
		$criteria->compare('login',$this->login,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}