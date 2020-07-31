<?php

/**
* IndustrySect Model Class
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
 * This is the model class for table "industry_sect".
 *
 * The followings are the available columns in table 'industry_sect':
 * @property integer $sect_id
 * @property string $sect_name
 * @property integer $sub_ind_id
 * @property integer $super_ind_id
 * @property string $sect_hash
 */
class IndustrySect extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IndustrySect the static model class
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
		return 'industry_sect';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sub_ind_id, super_ind_id, sect_hash', 'required'),
			array('sub_ind_id, super_ind_id', 'numerical', 'integerOnly'=>true),
			array('sect_hash', 'length', 'max'=>11),
			array('sect_name', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('sect_id, sect_name, sub_ind_id, super_ind_id, sect_hash', 'safe', 'on'=>'search'),
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
			'sect_id' => 'Sect',
			'sect_name' => 'Sect Name',
			'sub_ind_id' => 'Sub Ind',
			'super_ind_id' => 'Super Ind',
			'sect_hash' => 'Sect Hash',
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

		$criteria->compare('sect_id',$this->sect_id);
		$criteria->compare('sect_name',$this->sect_name,true);
		$criteria->compare('sub_ind_id',$this->sub_ind_id);
		$criteria->compare('super_ind_id',$this->super_ind_id);
		$criteria->compare('sect_hash',$this->sect_hash,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}