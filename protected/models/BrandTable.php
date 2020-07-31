<?php

/**
* BrandTable Model Class
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
 * This is the model class for table "brand_table".
 *
 * The followings are the available columns in table 'brand_table':
 * @property integer $brand_id
 * @property integer $agency_id
 * @property string $brand_name
 * @property string $brand_description
 * @property integer $industry_id
 * @property integer $sub_industry_id
 * @property integer $company_id
 * @property integer $country_id
 * @property string $status
 */
class BrandTable extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BrandTable the static model class
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
		return 'brand_table';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('agency_id, brand_description', 'required'),
			array('agency_id, industry_id, sub_industry_id, company_id, country_id', 'numerical', 'integerOnly'=>true),
			array('brand_name', 'length', 'max'=>100),
			array('status', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('brand_id, agency_id, brand_name, brand_description, industry_id, sub_industry_id, company_id, country_id, status', 'safe', 'on'=>'search'),
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
			'brand_id' => 'Brand',
			'agency_id' => 'Agency',
			'brand_name' => 'Brand Name',
			'brand_description' => 'Brand Description',
			'industry_id' => 'Industry',
			'sub_industry_id' => 'Sub Industry',
			'company_id' => 'Company',
			'country_id' => 'Country',
			'status' => 'Status',
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

		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('agency_id',$this->agency_id);
		$criteria->compare('brand_name',$this->brand_name,true);
		$criteria->compare('brand_description',$this->brand_description,true);
		$criteria->compare('industry_id',$this->industry_id);
		$criteria->compare('sub_industry_id',$this->sub_industry_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function CompanyBrands($coid){
		$sql = 'SELECT brand_id, brand_name from  user_table,brand_table 
		where  user_table.company_id='.$coid.' 
		and brand_table.company_id=user_table.company_id and brand_table.status=1 
		order by brand_name asc';
		return CHtml::listData(BrandTable::model()->findAllBySql($sql),'brand_id','brand_name');
	}

	public static function AgencyCompanyBrands($coid){
		$sql = 'SELECT brand_id, brand_name from  user_table,brand_table 
		where  user_table.company_id='.$coid.' 
		and brand_table.company_id=user_table.company_id  and brand_table.status=1 
		order by brand_name asc';
		return BrandTable::model()->findAllBySql($sql);
	}

	public static function AgencyBrands($agency_id){
		$agency_brands = "SELECT  distinct(brand_agency.brand_id) ,brand_name  FROM brand_agency, brand_table WHERE brand_agency.brand_id=brand_table.brand_id AND brand_agency.agency_id=$agency_id  and brand_table.status=1 ";
		// $agency_brands = "SELECT brand_name, brand_table.brand_id FROM brand_agency, brand_table WHERE brand_agency.brand_id=brand_table.brand_id AND brand_agency.agency_id=$agency_id";
		return CHtml::listData($companies = Yii::app()->db3->createCommand($agency_brands)->queryAll(),'brand_id','brand_name');
	}
}