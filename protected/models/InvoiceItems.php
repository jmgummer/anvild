<?php

/**
 * This is the model class for table "invoice_items".
 *
 * The followings are the available columns in table 'invoice_items':
 * @property integer $id
 * @property integer $agency_id
 * @property string $rcvalue
 * @property string $startdate
 * @property string $enddate
 * @property string $day
 * @property string $reportdate
 * @property string $reporttime
 * @property string $brandname
 * @property string $adname
 * @property string $entrytype
 * @property integer $stationid
 * @property string $tablename
 * @property integer $tableid
 * @property string $duration
 * @property string $datecreated
 */
class InvoiceItems extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'invoice_items';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('agency_id, rcvalue, stationid, tableid', 'required'),
			array('agency_id, stationid, tableid', 'numerical', 'integerOnly'=>true),
			array('rcvalue', 'length', 'max'=>10),
			array('startdate, enddate, day, reportdate, reporttime, tablename', 'length', 'max'=>30),
			array('brandname, adname, entrytype, duration', 'length', 'max'=>255),
			array('acvalue','safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, agency_id, rcvalue, startdate, enddate, day, reportdate, reporttime, brandname, adname, entrytype, stationid, tablename, tableid, duration, datecreated', 'safe', 'on'=>'search'),
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
			'agency_id' => 'Agency',
			'rcvalue' => 'Rcvalue',
			'startdate' => 'Startdate',
			'enddate' => 'Enddate',
			'day' => 'Day',
			'reportdate' => 'Reportdate',
			'reporttime' => 'Reporttime',
			'brandname' => 'Brandname',
			'adname' => 'Adname',
			'entrytype' => 'Entrytype',
			'stationid' => 'Stationid',
			'tablename' => 'Tablename',
			'tableid' => 'Tableid',
			'duration' => 'Duration',
			'datecreated' => 'Datecreated',
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
		$criteria->compare('agency_id',$this->agency_id);
		$criteria->compare('rcvalue',$this->rcvalue,true);
		$criteria->compare('startdate',$this->startdate,true);
		$criteria->compare('enddate',$this->enddate,true);
		$criteria->compare('day',$this->day,true);
		$criteria->compare('reportdate',$this->reportdate,true);
		$criteria->compare('reporttime',$this->reporttime,true);
		$criteria->compare('brandname',$this->brandname,true);
		$criteria->compare('adname',$this->adname,true);
		$criteria->compare('entrytype',$this->entrytype,true);
		$criteria->compare('stationid',$this->stationid);
		$criteria->compare('tablename',$this->tablename,true);
		$criteria->compare('tableid',$this->tableid);
		$criteria->compare('duration',$this->duration,true);
		$criteria->compare('datecreated',$this->datecreated,true);

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
	 * @return InvoiceItems the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
