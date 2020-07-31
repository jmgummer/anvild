<?php

/**
 * This is the model class for table "brand_agency".
 *
 * The followings are the available columns in table 'brand_agency':
 * @property integer $auto_id
 * @property integer $brand_id
 * @property integer $agency_id
 * @property string $start_date
 * @property string $end_date
 * @property string $date_modified
 * @property integer $editor_id
 * @property string $comments
 */
class BrandAgency extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'brand_agency';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// array('brand_id, agency_id, start_date, date_modified, editor_id', 'required'),
			array('brand_id, agency_id, editor_id', 'numerical', 'integerOnly'=>true),
			array('start_date, end_date, comments', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('auto_id, brand_id, agency_id, start_date, end_date, date_modified, editor_id, comments', 'safe', 'on'=>'search'),
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
			'agency_id' => 'Agency',
			'start_date' => 'Start Date',
			'end_date' => 'End Date',
			'date_modified' => 'Date Modified',
			'editor_id' => 'Editor',
			'comments' => 'Comments',
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
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('agency_id',$this->agency_id);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('date_modified',$this->date_modified,true);
		$criteria->compare('editor_id',$this->editor_id);
		$criteria->compare('comments',$this->comments,true);

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
	 * @return BrandAgency the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
