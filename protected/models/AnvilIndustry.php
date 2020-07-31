<?php

/**
* AnvilIndustry Model Class
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
 * This is the model class for table "industry".
 *
 * The followings are the available columns in table 'industry':
 * @property integer $industry_id
 * @property string $industry_name
 * @property integer $industry_hash
 */
class AnvilIndustry extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AnvilIndustry the static model class
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
		return 'industry';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('industry_hash', 'required'),
			array('industry_hash', 'numerical', 'integerOnly'=>true),
			array('industry_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('industry_id, industry_name, industry_hash', 'safe', 'on'=>'search'),
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
			'industry_id' => 'Industry',
			'industry_name' => 'Industry Name',
			'industry_hash' => 'Industry Hash',
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

		$criteria->compare('industry_id',$this->industry_id);
		$criteria->compare('industry_name',$this->industry_name,true);
		$criteria->compare('industry_hash',$this->industry_hash);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}