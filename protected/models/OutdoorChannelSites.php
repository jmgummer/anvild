<?php

/**
* OutdoorChannelSites Model Class
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
 * This is the model class for table "outdoor_channel_sites".
 *
 * The followings are the available columns in table 'outdoor_channel_sites':
 * @property integer $auto_id
 * @property integer $company_id
 * @property string $site_name
 * @property string $site_location
 * @property integer $site_town
 * @property string $site_type
 */
class OutdoorChannelSites extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OutdoorChannelSites the static model class
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
		return 'outdoor_channel_sites';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id, site_name, site_location, site_town, site_type', 'required'),
			array('company_id, site_town', 'numerical', 'integerOnly'=>true),
			array('site_name, site_location', 'length', 'max'=>50),
			array('site_type', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('auto_id, company_id, site_name, site_location, site_town, site_type', 'safe', 'on'=>'search'),
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
			'company_id' => 'Company',
			'site_name' => 'Site Name',
			'site_location' => 'Site Location',
			'site_town' => 'Site Town',
			'site_type' => 'Site Type',
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
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('site_name',$this->site_name,true);
		$criteria->compare('site_location',$this->site_location,true);
		$criteria->compare('site_town',$this->site_town);
		$criteria->compare('site_type',$this->site_type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}