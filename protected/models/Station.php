<?php

/**
* Station Model Class
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
 * This is the model class for table "station".
 *
 * The followings are the available columns in table 'station':
 * @property integer $station_id
 * @property string $station_name
 * @property string $karf_name
 * @property integer $frequency
 * @property integer $server_id
 * @property integer $country_id
 * @property integer $region_id
 * @property string $station_code
 * @property string $country_code
 * @property string $station_type
 * @property string $online
 * @property integer $serverport
 * @property string $station_status
 * @property string $contact_person
 * @property string $address
 * @property string $email
 * @property string $language_type
 */
class Station extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Station the static model class
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
		return 'station';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('karf_name, frequency, server_id, serverport, contact_person, address, email', 'required'),
			array('frequency, server_id, country_id, region_id, serverport, subscription_req', 'numerical', 'integerOnly'=>true),
			array('station_name, karf_name, contact_person, address, email', 'length', 'max'=>100),
			array('station_code', 'length', 'max'=>3),
			array('country_code', 'length', 'max'=>2),
			array('station_type', 'length', 'max'=>5),
			array('online, station_status, language_type', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('station_id, station_name, karf_name, frequency, server_id, country_id, region_id, station_code, country_code, station_type, online, serverport, station_status, contact_person, address, email, language_type', 'safe', 'on'=>'search'),
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
			'station_id' => 'Station',
			'station_name' => 'Station Name',
			'karf_name' => 'Karf Name',
			'frequency' => 'Frequency',
			'server_id' => 'Server',
			'country_id' => 'Country',
			'region_id' => 'Region',
			'station_code' => 'Station Code',
			'country_code' => 'Country Code',
			'station_type' => 'Station Type',
			'online' => 'Online',
			'serverport' => 'Serverport',
			'station_status' => 'Station Status',
			'contact_person' => 'Contact Person',
			'address' => 'Address',
			'email' => 'Email',
			'language_type' => 'Language Type',
			'subscription_req'=>'Subscription Required'
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

		$criteria->compare('station_id',$this->station_id);
		$criteria->compare('station_name',$this->station_name,true);
		$criteria->compare('karf_name',$this->karf_name,true);
		$criteria->compare('frequency',$this->frequency);
		$criteria->compare('server_id',$this->server_id);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('region_id',$this->region_id);
		$criteria->compare('subscription_req',$this->subscription_req);
		$criteria->compare('station_code',$this->station_code,true);
		$criteria->compare('country_code',$this->country_code,true);
		$criteria->compare('station_type',$this->station_type,true);
		$criteria->compare('online',$this->online,true);
		$criteria->compare('serverport',$this->serverport);
		$criteria->compare('station_status',$this->station_status,true);
		$criteria->compare('contact_person',$this->contact_person,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('language_type',$this->language_type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function CountryTV($this_country_code){
		/* Country ID From Session */
		
		if(isset(Yii::app()->user->country_id)){
			$country_id = Yii::app()->user->country_id;
		}else{
			$country_id = 1;
		}
		if(Yii::app()->user->usertype=='agency'){
			$coid = Yii::app()->user->company_id;
			$sql = "SELECT distinct agency_brand_station.station_id as station_id, station.station_name as station_name 
			FROM agency_brand_station 
			INNER JOIN station ON station.station_id = agency_brand_station.station_id
			WHERE agency_brand_station.agency_id = $coid
			and station.online=1 
			and station.station_status=1 and station_type='tv' and country_id='$country_id' 
			order by station.station_name asc;";
		}else{
			$company_id = Yii::app()->user->company_id;
			$sql = "SELECT distinct(station.station_name),station.station_id,station.station_code 
			from station inner join company_station_assignment on station.station_id=company_station_assignment.station_id 
			where station.online=1 and station.station_status=1 and station_type='tv' and country_id='$country_id' and company_id=$company_id 
			order by station.station_name asc";
		}
		if(Yii::app()->user->rpts_only==1){
			$sql = "SELECT distinct(station.station_name),station.station_id,station.station_code 
			from station where station.online=1 and station.station_status=1 and station_type='tv' and country_id='$country_id' 
			order by station.station_name asc";
		}
		
		return CHtml::listData(Station::model()->findAllBySql($sql),'station_id','station_name');
	}

	public static function CountryRadio($this_country_code){
		/* Country ID From Session */
		
		if(isset(Yii::app()->user->country_id)){
			$country_id = Yii::app()->user->country_id;
		}else{
			$country_id = 1;
		}
		if(Yii::app()->user->usertype=='agency'){
			$coid = Yii::app()->user->company_id;
			$sql = "SELECT distinct agency_brand_station.station_id as station_id, station.station_name as station_name 
			FROM agency_brand_station 
			INNER JOIN station ON station.station_id = agency_brand_station.station_id
			WHERE agency_brand_station.agency_id = $coid
			and station.online=1 
			and station.station_status=1 and station_type='radio' and country_id='$country_id' 
			order by station.station_name asc;";
		}else{
			$company_id = Yii::app()->user->company_id;
			$sql = "SELECT distinct(station.station_name),station.station_id,station.station_code 
			from station inner join company_station_assignment on station.station_id=company_station_assignment.station_id 
			where station.online=1 and station.station_status=1 and station_type='radio' 
			and country_id='$country_id' and company_id=$company_id 
			order by station.station_name asc";
		}
		if(Yii::app()->user->rpts_only==1){
			$sql = "SELECT distinct(station.station_name),station.station_id,station.station_code 
			from station where station.online=1 and station.station_status=1 and station_type='radio' and country_id='$country_id' 
			order by station.station_name asc";
		}
		return CHtml::listData(Station::model()->findAllBySql($sql),'station_id','station_name');
	}

	public static function AllStations($this_country_code){
		/* Country ID From Session */
		
		if(isset(Yii::app()->user->country_id)){
			$country_id = Yii::app()->user->country_id;
		}else{
			$country_id = 1;
		}
		if(Yii::app()->user->usertype=='agency'){
			$coid = Yii::app()->user->company_id;
			$sql = "SELECT distinct agency_brand_station.station_id as station_id,station.station_name as station_name 
			FROM agency_brand_station INNER JOIN station on agency_brand_station.station_id = station.station_id 
			WHERE brand_id in (SELECT distinct brand_id FROM agency_brand WHERE agency_id = ".$coid.") 
			and station.online=1  and station.station_status=1  and country_id='".$country_id."' order by station.station_name asc;";
		}else{
			$company_id = Yii::app()->user->company_id;
			$sql = "SELECT distinct(station.station_name),station.station_id,station.station_code 
			from station inner join company_station_assignment on station.station_id=company_station_assignment.station_id   
			where station.online=1  and station.station_status=1  and country_id='$country_id' and company_id=$company_id
			order by station.station_name asc";
		}
		return CHtml::listData(Station::model()->findAllBySql($sql),'station_id','station_name');
	}

	public static function StationList($level,$company_name=null,$country_id){
		if($level==2) {
			$sql_station="select station.station_name, station.station_id from station,editor_station_assignment where station.station_id=editor_station_assignment.station_id and editor_station_assignment.editor_id=
			(select company_id from user_table where company_name='$company_name') and country_id=$country_id and station.station_status=1s order by station_name asc";
		} else {
			$sql_station="select station.station_name, station.station_id from station where station.station_status=1 and country_id=$country_id order by station_name asc";
		}
		return CHtml::listData(Station::model()->findAllBySql($sql_station),'station_id','station_name');
	}

	public static function AdminStations(){
		$sql = "SELECT DISTINCT(station.station_name),station.station_id,station.station_code 
		FROM station WHERE station.online=1  AND station.station_status=1  ORDER BY station.station_name ASC";
		return CHtml::listData(Station::model()->findAllBySql($sql),'station_id','station_name');
	}

	public static function AdminTvStations(){
		$sql = "SELECT DISTINCT(station.station_name),station.station_id,station.station_code 
		FROM station WHERE station.online=1 AND station_type = 'tv' AND station.station_status=1  ORDER BY station.station_name ASC";
		return CHtml::listData(Station::model()->findAllBySql($sql),'station_id','station_name');
	}

	public static function AdminRadioStations(){
		$sql = "SELECT DISTINCT(station.station_name),station.station_id,station.station_code 
		FROM station WHERE station.online=1 AND station_type = 'radio' AND station.station_status=1  ORDER BY station.station_name ASC";
		return CHtml::listData(Station::model()->findAllBySql($sql),'station_id','station_name');
	}
}