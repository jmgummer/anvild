<?php

/**
* Invoice Model Class
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
 * This is the model class for table "invoice".
 *
 * The followings are the available columns in table 'invoice':
 * @property integer $invoice_id
 * @property integer $brand_id
 * @property string $inv_date_created
 * @property string $inv_time
 * @property integer $agency_id
 * @property string $inv_start_date
 * @property string $inv_end_date
 * @property string $pdf_file
 * @property integer $update_number
 * @property string $last_update
 */
class Invoice extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'invoice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// array('brand_id, inv_date_created, inv_time, agency_id, inv_start_date, inv_end_date, pdf_file', 'required'),
			array('brand_id, agency_id, update_number', 'numerical', 'integerOnly'=>true),
			array('pdf_file', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('invoice_id, brand_id, inv_date_created, inv_time, agency_id, inv_start_date, inv_end_date, pdf_file, update_number, last_update', 'safe', 'on'=>'search'),
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
			'invoice_id' => 'Invoice',
			'brand_id' => 'Brand',
			'inv_date_created' => 'Inv Date Created',
			'inv_time' => 'Inv Time',
			'agency_id' => 'Agency',
			'inv_start_date' => 'Inv Start Date',
			'inv_end_date' => 'Inv End Date',
			'pdf_file' => 'Pdf File',
			'update_number' => 'Update Number',
			'last_update' => 'Last Update',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('invoice_id',$this->invoice_id);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('inv_date_created',$this->inv_date_created,true);
		$criteria->compare('inv_time',$this->inv_time,true);
		$criteria->compare('agency_id',$this->agency_id);
		$criteria->compare('inv_start_date',$this->inv_start_date,true);
		$criteria->compare('inv_end_date',$this->inv_end_date,true);
		$criteria->compare('pdf_file',$this->pdf_file,true);
		$criteria->compare('update_number',$this->update_number);
		$criteria->compare('last_update',$this->last_update,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db3;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Invoice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getBrand()
	{
		if($this->brand_id!=null or $this->brand_id!=0){
			if($brand = BrandTable::model()->find('brand_id=:a', array('a'=>$this->brand_id))){
				return $brand->brand_name;
			}else{
				return 'Unknown Brand';
			}
		}else{
			return 'Unknown Brand';
		}
	}

	public function getDatespan()
	{
		$span = $this->inv_start_date. ' - '.$this->inv_end_date;
		return $span;
	}

	public function getFile()
	{
		if($this->pdf_file!=null || $this->pdf_file!=''){
			$file = '<a href="http://www.reelforge.com'.$this->pdf_file.'" target="_blank"><i class="fa fa-file-pdf-o" style="color:red;"></i> </a>';
		}else{
			$file = 'Not Available';
		}
		echo $file;
	}
}
