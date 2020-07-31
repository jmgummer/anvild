<?php

/**
* Incantation Model Class
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
 * This is the model class for table "incantation".
 *
 * The followings are the available columns in table 'incantation':
 * @property integer $incantation_id
 * @property string $incantation_name
 * @property string $incantation_file
 * @property integer $incantation_brand_id
 * @property string $incantation_description
 * @property integer $incantation_language
 * @property integer $incantation_company_id
 * @property string $incantation_date
 * @property string $incantation_time
 * @property integer $incantation_length
 * @property integer $incantation_entry_type_id
 * @property integer $active
 * @property string $file_path
 * @property integer $mpg
 * @property string $mpg_path
 */
class Incantation extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Incantation the static model class
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
		return 'incantation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('incantation_description, incantation_language, incantation_entry_type_id, file_path, mpg, mpg_path', 'required'),
			array('incantation_brand_id, incantation_language, incantation_company_id, incantation_length, incantation_entry_type_id, active, mpg', 'numerical', 'integerOnly'=>true),
			array('incantation_name, incantation_file, file_path, mpg_path', 'length', 'max'=>100),
			array('incantation_date, incantation_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('incantation_id, incantation_name, incantation_file, incantation_brand_id, incantation_description, incantation_language, incantation_company_id, incantation_date, incantation_time, incantation_length, incantation_entry_type_id, active, file_path, mpg, mpg_path', 'safe', 'on'=>'search'),
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
			'incantation_id' => 'Incantation',
			'incantation_name' => 'Incantation Name',
			'incantation_file' => 'Incantation File',
			'incantation_brand_id' => 'Incantation Brand',
			'incantation_description' => 'Incantation Description',
			'incantation_language' => 'Incantation Language',
			'incantation_company_id' => 'Incantation Company',
			'incantation_date' => 'Incantation Date',
			'incantation_time' => 'Incantation Time',
			'incantation_length' => 'Incantation Length',
			'incantation_entry_type_id' => 'Incantation Entry Type',
			'active' => 'Active',
			'file_path' => 'File Path',
			'mpg' => 'Mpg',
			'mpg_path' => 'Mpg Path',
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

		$criteria->compare('incantation_id',$this->incantation_id);
		$criteria->compare('incantation_name',$this->incantation_name,true);
		$criteria->compare('incantation_file',$this->incantation_file,true);
		$criteria->compare('incantation_brand_id',$this->incantation_brand_id);
		$criteria->compare('incantation_description',$this->incantation_description,true);
		$criteria->compare('incantation_language',$this->incantation_language);
		$criteria->compare('incantation_company_id',$this->incantation_company_id);
		$criteria->compare('incantation_date',$this->incantation_date,true);
		$criteria->compare('incantation_time',$this->incantation_time,true);
		$criteria->compare('incantation_length',$this->incantation_length);
		$criteria->compare('incantation_entry_type_id',$this->incantation_entry_type_id);
		$criteria->compare('active',$this->active);
		$criteria->compare('file_path',$this->file_path,true);
		$criteria->compare('mpg',$this->mpg);
		$criteria->compare('mpg_path',$this->mpg_path,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}