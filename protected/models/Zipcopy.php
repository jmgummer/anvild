<?php

/**
 * This is the model class for table "zipcopy".
 *
 * The followings are the available columns in table 'zipcopy':
 * @property integer $id
 * @property integer $clientid
 * @property integer $clienttype
 * @property string $dateadded
 * @property string $industryid
 * @property string $subindustryid
 * @property string $startdate
 * @property string $enddate
 * @property integer $copystatus
 * @property string $filename
 * @property string $ziplocation
 * @property string $expirydate
 *
 * The followings are the available model relations:
 * @property Zipfiles[] $zipfiles
 */
class Zipcopy extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'zipcopy';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('clientid, clienttype, dateadded, industryid, subindustryid, startdate, enddate, copystatus, filename, ziplocation, expirydate, filetypes', 'required'),
			array('clientid, copystatus', 'numerical', 'integerOnly'=>true),
			array('industryid', 'length', 'max'=>100),
			array('subindustryid, filename, ziplocation', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, clientid, clienttype, dateadded, industryid, subindustryid, startdate, enddate, copystatus, filename, ziplocation, expirydate, filetypes', 'safe', 'on'=>'search'),
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
			'zipfiles' => array(self::HAS_MANY, 'Zipfiles', 'zipid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'clientid' => 'Clientid',
			'clienttype' => 'Clienttype',
			'dateadded' => 'Dateadded',
			'industryid' => 'Industryid',
			'subindustryid' => 'Subindustryid',
			'startdate' => 'Startdate',
			'enddate' => 'Enddate',
			'copystatus' => 'Copystatus',
			'filename' => 'Filename',
			'ziplocation' => 'Ziplocation',
			'expirydate' => 'Expirydate',
			'filetypes'=>'Filetypes',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('clientid',$this->clientid);
		$criteria->compare('clienttype',$this->clienttype,true);
		$criteria->compare('dateadded',$this->dateadded,true);
		$criteria->compare('industryid',$this->industryid,true);
		$criteria->compare('subindustryid',$this->subindustryid,true);
		$criteria->compare('startdate',$this->startdate,true);
		$criteria->compare('enddate',$this->enddate,true);
		$criteria->compare('copystatus',$this->copystatus);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('ziplocation',$this->ziplocation,true);
		$criteria->compare('expirydate',$this->expirydate,true);
		$criteria->compare('filetypes',$this->filetypes,true);
		

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
	 * @return Zipcopy the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getIndustry(){
		if(isset($this->industryid) && $this->industryid!=0){
			return AnvilIndustry::model()->find('industry_id=:a', array(':a'=>$this->industryid))->industry_name;
		}else{
			return '-';
		}
	}

	public function getSubIndustries(){
		$subarray = array();
		$setsubs = $this->subindustryid;
		$sql_sub_industry="SELECT *  from  sub_industry where auto_id IN ($setsubs) order by sub_industry_name asc";
		if($subs = Yii::app()->db3->createCommand($sql_sub_industry)->queryAll()){
			foreach ($subs as $value) {
				$subarray[]=ucwords(strtolower($value["sub_industry_name"]));  
			}
			return implode(',', $subarray);
		}else{
			return '-';
		}
	}

	public function getCopyStatus(){
		if($this->copystatus==0){
			return 'Pending';
		}
		if($this->copystatus==1){
			return 'Copying ...';
		}
		if($this->copystatus==2){
			return 'Completed';
		}
	}

	public function getZipcount(){
		$zipid = $this->id;
		$sql = "SELECT count(*) AS zipcount FROM zipfiles WHERE zipid=$zipid";
		if($s_count = Yii::app()->db3->createCommand($sql)->queryRow()){
			return $s_count['zipcount'];
		}else{
			return 0;
		}
	}

	public function getDownloadLink(){
		$cd_name = $this->filename;
		$file = 'http://media.reelforge.com/dload?zip='.$cd_name;
		$count = $this->Zipcount;
		if($this->copystatus==2){
			return "<a href='$file' target='blank'>Download Zip - ($count Files)</a>";
		}else{
			return "<a>Zipping ($count Files)</a>";
		}
	}
}
