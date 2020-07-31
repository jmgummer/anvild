<?php

/**
* Country Model Class
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
 * This is the model class for table "country".
 *
 * The followings are the available columns in table 'country':
 * @property integer $country_id
 * @property string $country_name
 * @property string $country_code
 * @property string $currency
 */
class Country extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Country the static model class
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
		return 'country';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('country_code', 'required'),
			array('country_name', 'length', 'max'=>100),
			array('country_code', 'length', 'max'=>2),
			array('currency', 'length', 'max'=>3),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('country_id, country_name, country_code, currency', 'safe', 'on'=>'search'),
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
			'country_id' => 'Country',
			'country_name' => 'Country Name',
			'country_code' => 'Country Code',
			'currency' => 'Currency',
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

		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('country_name',$this->country_name,true);
		$criteria->compare('country_code',$this->country_code,true);
		$criteria->compare('currency',$this->currency,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function CompanyCountry($coid){
		$sql = 'select country.country_id, country.country_name, country_code 
				from company_country, country  
				where company_country.company_id='.$coid.' 
				and country.country_id=company_country.country_id';
		return CHtml::listData(Country::model()->findAllBySql($sql),'country_code','country_name');
	}

	public static function CompanyCountryById($coid){
		$sql = 'select country.country_id, country.country_name, country_code 
				from company_country, country  
				where company_country.company_id='.$coid.' 
				and country.country_id=company_country.country_id';
		return CHtml::listData(Country::model()->findAllBySql($sql),'country_id','country_name');
	}

	public static function AllCompanies()
	{
		return CHtml::listData(Country::model()->findAll(),'country_code','country_name');
	}

	public static function DefaultCountries($coid)
	{
		$sql = 'select country.country_id, country.country_name, country_code 
				from company_country, country  
				where company_country.company_id='.$coid.' 
				and country.country_id=company_country.country_id';
		return CHtml::listData(Country::model()->findAllBySql($sql),'country_id','country_name');
	}
	public static function AllCountries(){
		$sql = 'select country.country_id, country.country_name, country_code 
				from  country  ';
		return CHtml::listData(Country::model()->findAllBySql($sql),'country_id','country_name');
	}
}