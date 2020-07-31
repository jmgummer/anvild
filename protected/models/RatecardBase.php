<?php

/**
* RatecardBase Model Class
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
 * This is the model class for table "ratecard_base".
 *
 * The followings are the available columns in table 'ratecard_base':
 * @property integer $auto_id
 * @property string $date_start
 * @property integer $station_id
 * @property string $weekday
 * @property string $time_start
 * @property string $time_end
 * @property integer $rate
 * @property integer $duration
 */
class RatecardBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RatecardBase the static model class
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
		return 'ratecard_base';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('station_id, rate, duration', 'numerical', 'integerOnly'=>true),
			array('weekday', 'length', 'max'=>3),
			array('date_start, time_start, time_end', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('auto_id, date_start, station_id, weekday, time_start, time_end, rate, duration', 'safe', 'on'=>'search'),
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
			'date_start' => 'Date Start',
			'station_id' => 'Station',
			'weekday' => 'Weekday',
			'time_start' => 'Time Start',
			'time_end' => 'Time End',
			'rate' => 'Rate',
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

		$criteria->compare('auto_id',$this->auto_id);
		$criteria->compare('date_start',$this->date_start,true);
		$criteria->compare('station_id',$this->station_id);
		$criteria->compare('weekday',$this->weekday,true);
		$criteria->compare('time_start',$this->time_start,true);
		$criteria->compare('time_end',$this->time_end,true);
		$criteria->compare('rate',$this->rate);
		$criteria->compare('duration',$this->duration);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}