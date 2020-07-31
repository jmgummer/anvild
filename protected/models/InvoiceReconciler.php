<?php

/**
* InvoiceReconciler Model Class
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
 * This is the model class for table "invoice_reconciler".
 *
 * The followings are the available columns in table 'invoice_reconciler':
 * @property integer $auto_id
 * @property string $table_name
 * @property integer $table_id
 * @property integer $inv_rate
 * @property integer $ratecard_rate
 * @property string $inv_date
 * @property string $inv_time
 * @property integer $inv_id
 * @property string $entry_type
 * @property string $ad_name
 * @property string $brand_name
 * @property integer $station_id
 * @property integer $duration
 */
class InvoiceReconciler extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'invoice_reconciler';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// array('table_name, table_id, inv_rate, ratecard_rate, inv_date, inv_time, inv_id, entry_type, ad_name, brand_name, station_id, duration', 'required'),
			array('table_id, inv_rate, ratecard_rate, inv_id, station_id, duration', 'numerical', 'integerOnly'=>true),
			array('table_name', 'length', 'max'=>1),
			array('entry_type', 'length', 'max'=>50),
			array('ad_name, brand_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('auto_id, table_name, table_id, inv_rate, ratecard_rate, inv_date, inv_time, inv_id, entry_type, ad_name, brand_name, station_id, duration', 'safe', 'on'=>'search'),
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
			'auto_id' => 'Auto',
			'table_name' => 'Table Name',
			'table_id' => 'Table',
			'inv_rate' => 'Inv Rate',
			'ratecard_rate' => 'Ratecard Rate',
			'inv_date' => 'Inv Date',
			'inv_time' => 'Inv Time',
			'inv_id' => 'Inv',
			'entry_type' => 'Entry Type',
			'ad_name' => 'Ad Name',
			'brand_name' => 'Brand Name',
			'station_id' => 'Station',
			'duration' => 'Duration',
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

		$criteria->compare('auto_id',$this->auto_id);
		$criteria->compare('table_name',$this->table_name,true);
		$criteria->compare('table_id',$this->table_id);
		$criteria->compare('inv_rate',$this->inv_rate);
		$criteria->compare('ratecard_rate',$this->ratecard_rate);
		$criteria->compare('inv_date',$this->inv_date,true);
		$criteria->compare('inv_time',$this->inv_time,true);
		$criteria->compare('inv_id',$this->inv_id);
		$criteria->compare('entry_type',$this->entry_type,true);
		$criteria->compare('ad_name',$this->ad_name,true);
		$criteria->compare('brand_name',$this->brand_name,true);
		$criteria->compare('station_id',$this->station_id);
		$criteria->compare('duration',$this->duration);

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
	 * @return InvoiceReconciler the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
