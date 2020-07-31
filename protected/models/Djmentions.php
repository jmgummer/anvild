<?php

/**
* Djmentions Model Class
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
 * This is the model class for table "djmentions".
 *
 * The followings are the available columns in table 'djmentions':
 * @property integer $auto_id
 * @property integer $brand_id
 * @property integer $station_id
 * @property string $date
 * @property string $time
 * @property string $lastClipTime
 * @property integer $duration
 * @property string $filename
 * @property integer $campaign_id
 * @property string $entry_type
 * @property string $Program
 * @property string $file_path
 * @property string $comment
 * @property integer $rate
 * @property string $trp
 */
class Djmentions extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Djmentions the static model class
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
		return 'djmentions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('brand_id, station_id, date, time, duration, filename, entry_type, Program, file_path, comment, rate', 'required'),
			array('brand_id, station_id, duration, campaign_id, rate', 'numerical', 'integerOnly'=>true),
			array('filename, Program, file_path', 'length', 'max'=>100),
			array('entry_type', 'length', 'max'=>20),
			array('comment', 'length', 'max'=>25),
			array('trp', 'length', 'max'=>6),
			array('lastClipTime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('auto_id, brand_id, station_id, date, time, lastClipTime, duration, filename, campaign_id, entry_type, Program, file_path, comment, rate, trp', 'safe', 'on'=>'search'),
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
			'brand_id' => 'Brand',
			'station_id' => 'Station',
			'date' => 'Date',
			'time' => 'Time',
			'lastClipTime' => 'Last Clip Time',
			'duration' => 'Duration',
			'filename' => 'Filename',
			'campaign_id' => 'Campaign',
			'entry_type' => 'Entry Type',
			'Program' => 'Program',
			'file_path' => 'File Path',
			'comment' => 'Comment',
			'rate' => 'Rate',
			'trp' => 'Trp',
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
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('station_id',$this->station_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('lastClipTime',$this->lastClipTime,true);
		$criteria->compare('duration',$this->duration);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('campaign_id',$this->campaign_id);
		$criteria->compare('entry_type',$this->entry_type,true);
		$criteria->compare('Program',$this->Program,true);
		$criteria->compare('file_path',$this->file_path,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('rate',$this->rate);
		$criteria->compare('trp',$this->trp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getStationName()
	{
		if(isset($this->station_id) && $this->station_id!=0){
			return Station::model()->find('station_id=:a', array(':a'=>$this->station_id))->station_name;
		}else{
			return 'Not Found';
		}
	}

	public function getBrandName()
	{
		if(isset($this->brand_id) && $this->brand_id!=0){
			return BrandTable::model()->find('brand_id=:a', array(':a'=>$this->brand_id))->brand_name;
		}else{
			return 'Not Found';
		}
	}

	public static function EntryType($id)
	{
		return DjmentionsEntryTypes::model()->find('entry_type_id=:a', array(':a'=>$id))->entry_type;
	}

	public function getLength()
	{
		return $this->duration;
	}
}