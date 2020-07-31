<?php

/**
* VisitorsTable Model Class
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
 * This is the model class for table "visitors_table".
 *
 * The followings are the available columns in table 'visitors_table':
 * @property integer $ID
 * @property string $visitor_ip
 * @property string $visitor_browser
 * @property integer $visitor_hour
 * @property integer $visitor_minute
 * @property string $visitor_date
 * @property integer $visitor_day
 * @property integer $visitor_month
 * @property integer $visitor_year
 * @property string $visitor_refferer
 * @property string $visitor_page
 * @property string $company_name
 */
class VisitorsTable extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return VisitorsTable the static model class
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
		return Yii::app()->db;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'visitors_table';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('visitor_hour, visitor_minute, visitor_day, visitor_month, visitor_year', 'numerical', 'integerOnly'=>true),
			array('visitor_ip', 'length', 'max'=>32),
			array('visitor_browser, visitor_refferer, visitor_page', 'length', 'max'=>255),
			array('company_name, platform', 'length', 'max'=>100),
			array('userid, usertype', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, visitor_ip, visitor_browser, visitor_hour, visitor_minute, visitor_date, visitor_day, visitor_month, visitor_year, visitor_refferer, visitor_page, company_name, platform', 'safe', 'on'=>'search'),
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
			'ID' => 'ID',
			'visitor_ip' => 'Visitor Ip',
			'visitor_browser' => 'Visitor Browser',
			'visitor_hour' => 'Visitor Hour',
			'visitor_minute' => 'Visitor Minute',
			'visitor_date' => 'Visitor Date',
			'visitor_day' => 'Visitor Day',
			'visitor_month' => 'Visitor Month',
			'visitor_year' => 'Visitor Year',
			'visitor_refferer' => 'Visitor Refferer',
			'visitor_page' => 'Visitor Page',
			'company_name' => 'Company Name',
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

		$criteria->compare('ID',$this->ID);
		$criteria->compare('visitor_ip',$this->visitor_ip,true);
		$criteria->compare('visitor_browser',$this->visitor_browser,true);
		$criteria->compare('visitor_hour',$this->visitor_hour);
		$criteria->compare('visitor_minute',$this->visitor_minute);
		$criteria->compare('visitor_date',$this->visitor_date,true);
		$criteria->compare('visitor_day',$this->visitor_day);
		$criteria->compare('visitor_month',$this->visitor_month);
		$criteria->compare('visitor_year',$this->visitor_year);
		$criteria->compare('visitor_refferer',$this->visitor_refferer,true);
		$criteria->compare('visitor_page',$this->visitor_page,true);
		$criteria->compare('company_name',$this->company_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}