<?php

/**
 * This is the model class for table "adflite".
 *
 * The followings are the available columns in table 'adflite':
 * @property integer $adflite_id
 * @property string $adflite_name
 * @property string $adflite_contact_person
 * @property string $adflite_email
 * @property string $adflite_tel
 * @property string $adflite_box
 * @property string $adflite_status
 */
class Adflite extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'adflite';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('adflite_name, adflite_contact_person, adflite_email, adflite_tel, adflite_box, adflite_status,country_id', 'required'),
			array('adflite_name, adflite_contact_person, adflite_email, adflite_tel, adflite_box', 'length', 'max'=>100),
			array('adflite_status', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('adflite_id,country_id, adflite_name, adflite_contact_person, adflite_email, adflite_tel, adflite_box, adflite_status', 'safe', 'on'=>'search'),
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
			'adflite_id' => 'Adflite',
			'adflite_name' => 'Adflite Name',
			'adflite_contact_person' => 'Adflite Contact Person',
			'adflite_email' => 'Adflite Email',
			'adflite_tel' => 'Adflite Tel',
			'adflite_box' => 'Adflite Box',
			'adflite_status' => 'Adflite Status',
			'country_id'=>'Country',
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

		$criteria->compare('adflite_id',$this->adflite_id);
		$criteria->compare('adflite_name',$this->adflite_name,true);
		$criteria->compare('adflite_contact_person',$this->adflite_contact_person,true);
		$criteria->compare('adflite_email',$this->adflite_email,true);
		$criteria->compare('adflite_tel',$this->adflite_tel,true);
		$criteria->compare('adflite_box',$this->adflite_box,true);
		$criteria->compare('adflite_status',$this->adflite_status,true);
		$criteria->compare('country_id',$this->country_id);

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
	 * @return Adflite the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
