<?php

/**
* ReelforgeSample Model Class
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
 * This is the model class for table "reelforge_sample".
 *
 * The followings are the available columns in table 'reelforge_sample':
 * @property string $reel_auto_id
 * @property string $reel_incarntation
 * @property string $reel_station
 * @property string $reel_date
 * @property string $reel_time
 * @property string $reel_rank
 * @property string $reel_analysis_id
 * @property integer $detections_seen
 * @property integer $detections_required
 * @property integer $rank_analysis
 * @property string $company_id
 * @property string $brand_id
 * @property string $incantation_id
 * @property string $station_id
 * @property string $industry_id
 * @property string $rate
 * @property string $adtype
 * @property string $comment
 * @property string $trp
 * @property integer $entry_type_id
 */
class ReelforgeSample extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ReelforgeSample the static model class
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
		return 'reelforge_sample';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reel_incarntation, comment', 'required'),
			array('detections_seen, detections_required, rank_analysis, entry_type_id', 'numerical', 'integerOnly'=>true),
			array('reel_incarntation, reel_station', 'length', 'max'=>100),
			array('reel_rank, company_id, brand_id, incantation_id, station_id, industry_id, rate, adtype, trp', 'length', 'max'=>6),
			array('reel_analysis_id', 'length', 'max'=>11),
			array('comment', 'length', 'max'=>20),
			array('reel_date, reel_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('reel_auto_id, reel_incarntation, reel_station, reel_date, reel_time, reel_rank, reel_analysis_id, detections_seen, detections_required, rank_analysis, company_id, brand_id, incantation_id, station_id, industry_id, rate, adtype, comment, trp, entry_type_id', 'safe', 'on'=>'search'),
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
			'reel_auto_id' => 'Reel Auto',
			'reel_incarntation' => 'Reel Incarntation',
			'reel_station' => 'Reel Station',
			'reel_date' => 'Reel Date',
			'reel_time' => 'Reel Time',
			'reel_rank' => 'Reel Rank',
			'reel_analysis_id' => 'Reel Analysis',
			'detections_seen' => 'Detections Seen',
			'detections_required' => 'Detections Required',
			'rank_analysis' => 'Rank Analysis',
			'company_id' => 'Company',
			'brand_id' => 'Brand',
			'incantation_id' => 'Incantation',
			'station_id' => 'Station',
			'industry_id' => 'Industry',
			'rate' => 'Rate',
			'adtype' => 'Adtype',
			'comment' => 'Comment',
			'trp' => 'Trp',
			'entry_type_id' => 'Entry Type',
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

		$criteria->compare('reel_auto_id',$this->reel_auto_id,true);
		$criteria->compare('reel_incarntation',$this->reel_incarntation,true);
		$criteria->compare('reel_station',$this->reel_station,true);
		$criteria->compare('reel_date',$this->reel_date,true);
		$criteria->compare('reel_time',$this->reel_time,true);
		$criteria->compare('reel_rank',$this->reel_rank,true);
		$criteria->compare('reel_analysis_id',$this->reel_analysis_id,true);
		$criteria->compare('detections_seen',$this->detections_seen);
		$criteria->compare('detections_required',$this->detections_required);
		$criteria->compare('rank_analysis',$this->rank_analysis);
		$criteria->compare('company_id',$this->company_id,true);
		$criteria->compare('brand_id',$this->brand_id,true);
		$criteria->compare('incantation_id',$this->incantation_id,true);
		$criteria->compare('station_id',$this->station_id,true);
		$criteria->compare('industry_id',$this->industry_id,true);
		$criteria->compare('rate',$this->rate,true);
		$criteria->compare('adtype',$this->adtype,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('trp',$this->trp,true);
		$criteria->compare('entry_type_id',$this->entry_type_id);

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

	public function getIncantationName()
	{
		if(isset($this->incantation_id) && $this->incantation_id!=0){
			return Incantation::model()->find('incantation_id=:a', array(':a'=>$this->incantation_id))->incantation_name;
		}else{
			return 'Not Found';
		}
	}

	public function getEntryType()
	{
		if(isset($this->entry_type_id) && $this->entry_type_id!=0){
			return DjmentionsEntryTypes::model()->find('entry_type_id=:a', array(':a'=>$this->entry_type_id))->entry_type;
		}else{
			return 'Not Found';
		}
	}

	public function getLength()
	{
		if(isset($this->incantation_id) && $this->incantation_id!=0){
			return Incantation::model()->find('incantation_id=:a', array(':a'=>$this->incantation_id))->incantation_length;
		}else{
			return 'Not Found';
		}
	}
}