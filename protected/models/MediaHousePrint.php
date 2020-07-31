<?php

/**
* MediaHousePrint Model Class
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
 * This is the model class for table "media_house_print".
 *
 * The followings are the available columns in table 'media_house_print':
 * @property integer $media_house_id
 * @property string $media_house_list
 * @property string $media_code
 * @property integer $country_id
 * @property integer $newspaper_type_id
 */
class MediaHousePrint extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MediaHousePrint the static model class
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
		return Yii::app()->db2;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'media_house_print';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('media_code, country_id, newspaper_type_id', 'required'),
			array('media_house_id, country_id, newspaper_type_id', 'numerical', 'integerOnly'=>true),
			array('media_house_list', 'length', 'max'=>100),
			array('media_code', 'length', 'max'=>3),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('media_house_id, media_house_list, media_code, country_id, newspaper_type_id', 'safe', 'on'=>'search'),
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
			'media_house_id' => 'Media House',
			'media_house_list' => 'Media House List',
			'media_code' => 'Media Code',
			'country_id' => 'Country',
			'newspaper_type_id' => 'Newspaper Type',
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

		$criteria->compare('media_house_id',$this->media_house_id);
		$criteria->compare('media_house_list',$this->media_house_list,true);
		$criteria->compare('media_code',$this->media_code,true);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('newspaper_type_id',$this->newspaper_type_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}