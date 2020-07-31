<?php

/**
* Ratecard Model Class
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
 * This is the model class for table "ratecard".
 *
 * The followings are the available columns in table 'ratecard':
 * @property integer $auto_id
 * @property integer $Media_House_ID
 * @property string $color_code
 * @property integer $rate
 */
class Ratecard extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Ratecard the static model class
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
		return 'ratecard';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Media_House_ID, color_code, rate', 'required'),
			array('Media_House_ID, rate', 'numerical', 'integerOnly'=>true),
			array('color_code', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('auto_id, Media_House_ID, color_code, rate', 'safe', 'on'=>'search'),
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
			'Media_House_ID' => 'Media House',
			'color_code' => 'Color Code',
			'rate' => 'Rate',
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
		$criteria->compare('Media_House_ID',$this->Media_House_ID);
		$criteria->compare('color_code',$this->color_code,true);
		$criteria->compare('rate',$this->rate);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}