<?php

/**
 * This is the model class for table "agency_company".
 *
 * The followings are the available columns in table 'agency_company':
 * @property integer $agency_id
 * @property string $agency_name
 * @property string $agency_rep_name
 * @property string $email
 * @property string $password
 * @property string $description
 * @property string $registerDate
 * @property string $lastvisitDate
 */
class AgencyCompany extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'agency_company';
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
			array('agency_name, agency_rep_name, email, password, description', 'length', 'max'=>100),
			array('registerDate, lastvisitDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('agency_id, agency_name, agency_rep_name, email, password, description, registerDate, lastvisitDate', 'safe', 'on'=>'search'),
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
			'agency_id' => 'Agency',
			'agency_name' => 'Agency Name',
			'agency_rep_name' => 'Agency Rep Name',
			'email' => 'Email',
			'password' => 'Password',
			'description' => 'Description',
			'registerDate' => 'Register Date',
			'lastvisitDate' => 'Lastvisit Date',
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

		$criteria->compare('agency_id',$this->agency_id);
		$criteria->compare('agency_name',$this->agency_name,true);
		$criteria->compare('agency_rep_name',$this->agency_rep_name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('registerDate',$this->registerDate,true);
		$criteria->compare('lastvisitDate',$this->lastvisitDate,true);

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
	 * @return AgencyCompany the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getAgencyList(){
		return CHtml::listData(AgencyCompany::model()->findAll(), 'agency_id','agency_name');
	}
}
