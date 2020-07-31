<?php

/**
* Industry Model Class
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
 * @property integer $Industry_ID
 * @property string $Industry_List
 * @property string $industry_hash
 * @property integer $sup_ind_id
 * @property integer $sect_id
 * @property integer $sub_ind_id
 * @property string $keywords
 */
class Industry extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Industry the static model class
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
			array('industry_hash, sup_ind_id, sect_id, sub_ind_id, keywords', 'required'),
			array('sup_ind_id, sect_id, sub_ind_id', 'numerical', 'integerOnly'=>true),
			array('industry_hash', 'length', 'max'=>11),
			array('Industry_List', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Industry_ID, Industry_List, industry_hash, sup_ind_id, sect_id, sub_ind_id, keywords', 'safe', 'on'=>'search'),
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
			'Industry_ID' => 'Industry',
			'Industry_List' => 'Industry List',
			'industry_hash' => 'Industry Hash',
			'sup_ind_id' => 'Sup Ind',
			'sect_id' => 'Sect',
			'sub_ind_id' => 'Sub Ind',
			'keywords' => 'Keywords',
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

		$criteria->compare('Industry_ID',$this->Industry_ID);
		$criteria->compare('Industry_List',$this->Industry_List,true);
		$criteria->compare('industry_hash',$this->industry_hash,true);
		$criteria->compare('sup_ind_id',$this->sup_ind_id);
		$criteria->compare('sect_id',$this->sect_id);
		$criteria->compare('sub_ind_id',$this->sub_ind_id);
		$criteria->compare('keywords',$this->keywords,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getSuperIndustry()
	{
		if($superindustry = IndustrySuperInd::model()->find('super_ind_id=:a', array(':a'=>$this->sup_ind_id))){
			return $superindustry->super_ind_name;
		}else{
			return 'Not Found';
		}
	}

	public function getIndustrySect()
	{
		if($sect = IndustrySect::model()->find('super_ind_id=:a', array(':a'=>$this->sect_id))){
			return $sect->sect_name;
		}else{
			return 'Not Found';
		}
	}

	public function getSubIndName()
	{
		if($subind = IndustrySubInd::model()->find('super_ind_id=:a', array(':a'=>$this->sub_ind_id))){
			return $subind->super_ind_name;
		}else{
			return 'Not Found';
		}
	}

	public function getConcatName()
	{
		return $this->SuperIndustry.' :: '.$this->Industry_List;
	}

	public function getIndustryList()
	{
		$sql = 'SELECT Industry_List, industry.Industry_ID, sup_ind_id, sect_id, sub_ind_id FROM industry,industry_company where industry_company.company_id='.Yii::app()->user->company_id.' and industry_company.industry_id=industry.Industry_ID order by sub_ind_id, Industry_List';
		return CHtml::listData(Industry::model()->findAllBySql($sql),'Industry_ID','ConcatName');
	}
}