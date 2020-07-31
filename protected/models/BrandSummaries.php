<?php

/**
 * This is the model class for table "brand_summaries".
 *
 * The followings are the available columns in table 'brand_summaries':
 * @property integer $id
 * @property integer $brand_id
 * @property integer $company_id
 * @property integer $sub_industry_id
 * @property integer $station_id
 * @property string $station_name
 * @property string $brand_name
 * @property integer $entry_type_id
 * @property string $entry_type
 * @property string $recorddate
 * @property string $period
 * @property string $station_type
 * @property integer $rate
 * @property string $db
 */
class BrandSummaries extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'brand_summaries';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('brand_id, company_id, sub_industry_id, station_id, station_name, brand_name, entry_type_id, entry_type, recorddate, period, station_type, rate, db', 'required'),
			array('brand_id, company_id, sub_industry_id, station_id, entry_type_id, rate', 'numerical', 'integerOnly'=>true),
			array('station_name, brand_name', 'length', 'max'=>80),
			array('entry_type', 'length', 'max'=>100),
			array('period', 'length', 'max'=>30),
			array('station_type, db', 'length', 'max'=>5),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, brand_id, company_id, sub_industry_id, station_id, station_name, brand_name, entry_type_id, entry_type, recorddate, period, station_type, rate, db', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'brand_id' => 'Brand',
			'company_id' => 'Company',
			'sub_industry_id' => 'Sub Industry',
			'station_id' => 'Station',
			'station_name' => 'Station Name',
			'brand_name' => 'Brand Name',
			'entry_type_id' => 'Entry Type',
			'entry_type' => 'Entry Type',
			'recorddate' => 'Recorddate',
			'period' => 'Period',
			'station_type' => 'Station Type',
			'rate' => 'Rate',
			'db' => 'Db',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('sub_industry_id',$this->sub_industry_id);
		$criteria->compare('station_id',$this->station_id);
		$criteria->compare('station_name',$this->station_name,true);
		$criteria->compare('brand_name',$this->brand_name,true);
		$criteria->compare('entry_type_id',$this->entry_type_id);
		$criteria->compare('entry_type',$this->entry_type,true);
		$criteria->compare('recorddate',$this->recorddate,true);
		$criteria->compare('period',$this->period,true);
		$criteria->compare('station_type',$this->station_type,true);
		$criteria->compare('rate',$this->rate);
		$criteria->compare('db',$this->db,true);

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
	 * @return BrandSummaries the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
