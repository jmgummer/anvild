<?php

/**
* AnvilMediahouse Model Class
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
 * This is the model class for table "mediahouse".
 *
 * The followings are the available columns in table 'mediahouse':
 * @property integer $Media_House_ID
 * @property string $Media_House_List
 * @property string $Media_ID
 * @property string $media_code
 */
class AnvilMediahouse extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AnvilMediahouse the static model class
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
		return 'mediahouse';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('media_code', 'required'),
			array('Media_ID', 'length', 'max'=>30),
			array('media_code', 'length', 'max'=>3),
			array('Media_House_List', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Media_House_ID, Media_House_List, Media_ID, media_code', 'safe', 'on'=>'search'),
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
			'Media_House_ID' => 'Media House',
			'Media_House_List' => 'Media House List',
			'Media_ID' => 'Media',
			'media_code' => 'Media Code',
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

		$criteria->compare('Media_House_ID',$this->Media_House_ID);
		$criteria->compare('Media_House_List',$this->Media_House_List,true);
		$criteria->compare('Media_ID',$this->Media_ID,true);
		$criteria->compare('media_code',$this->media_code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function PrintMediaDaily()
	{
		$empty_array = array();
		$reelmedia_selects = "SELECT Media_House_List FROM mediahouse mh, newspaper_type_assignment nta 
		WHERE mh.Media_House_ID=nta.Media_House_ID and nta.newspaper_type_id=1";
		if($mediahouse_names = Mediahouse::model()->findAllBySql($reelmedia_selects)){
			$prefix = '';
			$fruitList = '';
			foreach ($mediahouse_names as $fruit)
			{
			    $fruitList .= $prefix . '"' . $fruit->Media_House_List . '"';
			    $prefix = ', ';
			}
			$sql_print="SELECT * FROM mediahouse  where  Media_House_List IN ($fruitList) order by Media_House_List asc";
			return CHtml::listData(AnvilMediahouse::model()->findAllBySql($sql_print),'Media_House_ID','Media_House_List');
		}else{
			return $empty_array;
		}
	}

	public static function PrintMediaWeekly()
	{
		$empty_array = array();
		$reelmedia_selects = "SELECT Media_House_List FROM mediahouse mh, newspaper_type_assignment nta 
		WHERE mh.Media_House_ID=nta.Media_House_ID and nta.newspaper_type_id=2";
		if($mediahouse_names = Mediahouse::model()->findAllBySql($reelmedia_selects)){
			$prefix = '';
			$fruitList = '';
			foreach ($mediahouse_names as $fruit)
			{
			    $fruitList .= $prefix . '"' . $fruit->Media_House_List . '"';
			    $prefix = ', ';
			}
			$sql_print="SELECT * FROM mediahouse  where  Media_House_List IN ($fruitList) order by Media_House_List asc";
			return CHtml::listData(AnvilMediahouse::model()->findAllBySql($sql_print),'Media_House_ID','Media_House_List');
		}else{
			return $empty_array;
		}
	}

	public static function PrintMediaMonthly()
	{
		$empty_array = array();
		$reelmedia_selects = "SELECT Media_House_List FROM mediahouse mh, newspaper_type_assignment nta 
		WHERE mh.Media_House_ID=nta.Media_House_ID and nta.newspaper_type_id=3";
		if($mediahouse_names = Mediahouse::model()->findAllBySql($reelmedia_selects)){
			$prefix = '';
			$fruitList = '';
			foreach ($mediahouse_names as $fruit)
			{
			    $fruitList .= $prefix . '"' . $fruit->Media_House_List . '"';
			    $prefix = ', ';
			}
			$sql_print="SELECT * FROM mediahouse  where  Media_House_List IN ($fruitList) order by Media_House_List asc";
			return CHtml::listData(AnvilMediahouse::model()->findAllBySql($sql_print),'Media_House_ID','Media_House_List');
		}else{
			return $empty_array;
		}
	}

	public static function PrintMediaQuarterly()
	{
		$empty_array = array();
		$reelmedia_selects = "SELECT Media_House_List FROM mediahouse mh, newspaper_type_assignment nta 
		WHERE mh.Media_House_ID=nta.Media_House_ID and nta.newspaper_type_id=4";
		if($mediahouse_names = Mediahouse::model()->findAllBySql($reelmedia_selects)){
			$prefix = '';
			$fruitList = '';
			foreach ($mediahouse_names as $fruit)
			{
			    $fruitList .= $prefix . '"' . $fruit->Media_House_List . '"';
			    $prefix = ', ';
			}
			$sql_print="SELECT * FROM mediahouse  where  Media_House_List IN ($fruitList) order by Media_House_List asc";
			return CHtml::listData(AnvilMediahouse::model()->findAllBySql($sql_print),'Media_House_ID','Media_House_List');
		}else{
			return $empty_array;
		}
	}

	public static function PrintMediaBiMonthly()
	{
		$empty_array = array();
		$reelmedia_selects = "SELECT Media_House_List FROM mediahouse mh, newspaper_type_assignment nta 
		WHERE mh.Media_House_ID=nta.Media_House_ID and nta.newspaper_type_id=5";
		if($mediahouse_names = Mediahouse::model()->findAllBySql($reelmedia_selects)){
			$prefix = '';
			$fruitList = '';
			foreach ($mediahouse_names as $fruit)
			{
			    $fruitList .= $prefix . '"' . $fruit->Media_House_List . '"';
			    $prefix = ', ';
			}
			$sql_print="SELECT * FROM mediahouse  where  Media_House_List IN ($fruitList) order by Media_House_List asc";
			return CHtml::listData(AnvilMediahouse::model()->findAllBySql($sql_print),'Media_House_ID','Media_House_List');
		}else{
			return $empty_array;
		}
	}

	public static function PrintMediaWeekend()
	{
		$empty_array = array();
		$reelmedia_selects = "SELECT Media_House_List FROM mediahouse mh, newspaper_type_assignment nta 
		WHERE mh.Media_House_ID=nta.Media_House_ID and nta.newspaper_type_id=6";
		if($mediahouse_names = Mediahouse::model()->findAllBySql($reelmedia_selects)){
			$prefix = '';
			$fruitList = '';
			foreach ($mediahouse_names as $fruit)
			{
			    $fruitList .= $prefix . '"' . $fruit->Media_House_List . '"';
			    $prefix = ', ';
			}
			$sql_print="SELECT * FROM mediahouse  where  Media_House_List IN ($fruitList) order by Media_House_List asc";
			return CHtml::listData(AnvilMediahouse::model()->findAllBySql($sql_print),'Media_House_ID','Media_House_List');
		}else{
			return $empty_array;
		}
	}

}