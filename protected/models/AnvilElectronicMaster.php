<?php

/**
* AnvilElectronicMaster Model Class
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
 * This is the model class for table "anvil_electronic_master".
 *
 * The followings are the available columns in table 'anvil_electronic_master':
 * @property integer $id
 * @property integer $brand_id
 * @property integer $entry_type_id
 * @property string $incantation_id
 * @property integer $station_id
 * @property string $station_name
 * @property string $date
 * @property string $time
 * @property string $comment
 * @property string $rate
 * @property string $brand_name
 * @property string $entry_type
 * @property string $incantation_name
 * @property string $duration
 */
class AnvilElectronicMaster extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AnvilElectronicMaster the static model class
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
		return 'anvil_electronic_master';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('brand_id, incantation_id, station_name', 'required'),
			array('brand_id, entry_type_id, station_id', 'numerical', 'integerOnly'=>true),
			array('incantation_id, station_name, comment, rate, brand_name, entry_type, incantation_name, duration', 'length', 'max'=>100),
			array('date, time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, brand_id, entry_type_id, incantation_id, station_id, station_name, date, time, comment, rate, brand_name, entry_type, incantation_name, duration', 'safe', 'on'=>'search'),
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
			'entry_type_id' => 'Entry Type',
			'incantation_id' => 'Incantation',
			'station_id' => 'Station',
			'station_name' => 'Station Name',
			'date' => 'Date',
			'time' => 'Time',
			'comment' => 'Comment',
			'rate' => 'Rate',
			'brand_name' => 'Brand Name',
			'entry_type' => 'Entry Type',
			'incantation_name' => 'Incantation Name',
			'duration' => 'Duration',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('entry_type_id',$this->entry_type_id);
		$criteria->compare('incantation_id',$this->incantation_id,true);
		$criteria->compare('station_id',$this->station_id);
		$criteria->compare('station_name',$this->station_name,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('rate',$this->rate,true);
		$criteria->compare('brand_name',$this->brand_name,true);
		$criteria->compare('entry_type',$this->entry_type,true);
		$criteria->compare('incantation_name',$this->incantation_name,true);
		$criteria->compare('duration',$this->duration,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}