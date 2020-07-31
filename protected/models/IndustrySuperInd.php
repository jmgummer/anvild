<?php

/**
* IndustrySuperInd Model Class
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
 * This is the model class for table "industry_super_ind".
 *
 * The followings are the available columns in table 'industry_super_ind':
 * @property integer $super_ind_id
 * @property string $super_ind_name
 * @property string $super_ind_hash
 */
class IndustrySuperInd extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IndustrySuperInd the static model class
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
		return 'industry_super_ind';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('super_ind_hash', 'required'),
			array('super_ind_hash', 'length', 'max'=>11),
			array('super_ind_name', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('super_ind_id, super_ind_name, super_ind_hash', 'safe', 'on'=>'search'),
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
			'super_ind_id' => 'Super Ind',
			'super_ind_name' => 'Super Ind Name',
			'super_ind_hash' => 'Super Ind Hash',
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

		$criteria->compare('super_ind_id',$this->super_ind_id);
		$criteria->compare('super_ind_name',$this->super_ind_name,true);
		$criteria->compare('super_ind_hash',$this->super_ind_hash,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}