<?php

/**
 * This is the model class for table "station_programme".
 *
 * The followings are the available columns in table 'station_programme':
 * @property integer $programme_id
 * @property string $programme_name
 * @property string $prog_desc
 * @property integer $station_id
 * @property string $start_time
 * @property string $end_time
 * @property string $start_date
 * @property string $end_date
 * @property integer $programme_type
 * @property integer $weekday
 * @property integer $status
 */
class StationProgramme extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'station_programme';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('programme_name, station_id, weekday', 'required'),
			array('station_id, programme_type, weekday, status', 'numerical', 'integerOnly'=>true),
			array('programme_name', 'length', 'max'=>120),
			array('prog_desc', 'length', 'max'=>500),
			array('start_time, end_time, start_date, end_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('programme_id, programme_name, prog_desc, station_id, start_time, end_time, start_date, end_date, programme_type, weekday, status', 'safe', 'on'=>'search'),
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
			'programme_id' => 'Programme',
			'programme_name' => 'Programme Name',
			'prog_desc' => 'Prog Desc',
			'station_id' => 'Station',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'start_date' => 'Start Date',
			'end_date' => 'End Date',
			'programme_type' => 'Programme Type',
			'weekday' => 'Weekday',
			'status' => 'Status',
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

		$criteria->compare('programme_id',$this->programme_id);
		$criteria->compare('programme_name',$this->programme_name,true);
		$criteria->compare('prog_desc',$this->prog_desc,true);
		$criteria->compare('station_id',$this->station_id);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('programme_type',$this->programme_type);
		$criteria->compare('weekday',$this->weekday);
		$criteria->compare('status',$this->status);

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
	 * @return StationProgramme the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function GetProgram($station_id,$time,$date)
	{
		$weekday = date( "w", strtotime($date));
		if($weekday == '0'){
			$weekday = 7;
		}
		if($station_id ==180 || $station_id ==181 || $station_id ==182 || $station_id ==183){
			$wk = '';
		}else{
			$wk = " and weekday = $weekday";
		}

		$qry="select * from station_programme where station_id= $station_id and (start_time<='$time' and end_time>='$time') and (start_date<='$date' and end_time>='$date') $wk";

		if($res = StationProgramme::model()->findBySql($qry)){
			$programme_name = $res->programme_name;
			$station_id 	= $res->station_id;
			$prog_desc 		= $res->prog_desc;
			if($station_id ==180 || $station_id ==181 || $station_id ==182 || $station_id ==183){
				$programme_name = $prog_desc.': '.$programme_name;
			}
		}else{
			$programme_name = '';
		}
		return $programme_name;
	}
}
