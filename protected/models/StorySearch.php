<?php

/**
* StorySearch Model Class
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
 * StorySearch class.
 * StorySearch is the data structure for keeping
 * Story Search form data. It is used by the 'search' action of many controllers.
 */
class StorySearch extends CFormModel
{
	public $search_text;
	public $startdate;
	public $addate;
	public $enddate;
	public $country;
	public $storytype;
	public $storycategory;
	public $news_section;
	public $create_sheet;
	public $create_pdf;
	public $industry;
	public $industryreports;
	public $publications;
	public $brands;
	public $tv_station;
	public $radio_station;
	public $adtype;
	public $print;
	public $year;
	public $month;
	public $date;
	public $channel;
	public $region;
	public $subindustry;
	public $graphvalue;
	public $printtype;
	public $mediatype;
	public $mediaoptions;
	public $company;
	public $entrytype;
	public $logdate;
	public $station;
	public $reportformat;



	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('enddate,startdate,addate', 'required'),
			array('create_sheet,create_pdf', 'numerical', 'integerOnly'=>true),
			array('country,search_text,country,storytype,storycategory,news_section,enddate, startdate,industry,create_pdf,mediatype,mediaoptions,
				create_sheet,industryreports, publications, year, month, date, channel, region, subindustry,graphvalue,printtype, company, reportformat', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'searchtext'=>'Search Text',
			'addate'=>'Date',
			'startdate'=>'Beginning',
			'enddate'=>'Ending',
			'country'=>'Select Country(default Kenya)',
			'storytype'=>'Type of Story',
			'storycategory'=>'Category of Story',
			'create_pdf'=>'Create PDF Report',
			'create_sheet'=>'Create Spreadsheet',
			'industryreports'=>'Report Type',
			'publications'=>'Publications',
			'brands'=>'Brands',
			'tv_station'=>'TV Station',
			'radio_station'=>'Radio Station',
			'adtype'=>'Type of Ad',
			'year'=>'Year',
			'month'=>'Month',
			'date'=>'Date',
			'channel'=>'Outdoor Channel Company',
			'subindustry'=>'Sub Industry',
			'graphvalue'=>'Graph Values',
			'mediatype'=>'Media Type',
			'mediaoptions'=>'Media Options',
			'company'=>'Select Company',
			'industry'=>'Select Industry',
			'logdate'=>'Date',
			'station'=>'Station',
			'reportformat'=>'Report Format'
		);
	}

	public static function getPrintList($media_id)
	{
		return CHtml::listData(Mediahouse::model()->findAll('Media_ID=:a order by Media_House_List', array(':a'=>$media_id)),'Media_House_ID','Media_House_List');
	}

	public static function getElecList()
	{
		return CHtml::listData(Mediahouse::model()->findAll('Media_ID<>"mp01" order by Media_House_List'),'Media_House_ID','Media_House_List');
	}

	public static function Channels($coid)
	{
		$sql_site="SELECT distinct(billboard_company.company_id) as company_id, billboard_company.company_name as company_name 
		from  billboard_company, outdoor_channel_entries, brand_table 
		where outdoor_channel_entries.company_id=billboard_company.company_id 
		and outdoor_channel_entries.brand_id=brand_table.brand_id 
		and brand_table.company_id='$coid' 
		order by company_name";
		return CHtml::listData($companies = Yii::app()->db3->createCommand($sql_site)->queryAll(),'company_id','company_name');
	}

	public static function Industries($coid,$level)
	{
		if($level==3) {
			$sql_industry="SELECT distinct industry.industry_name, industry.industry_id  from industry ,  industryreport where  
			industryreport.company_id IN(SELECT distinct company_id from brand_table, agency_brand where agency_brand.agency_id='$coid' and brand_table.brand_id=agency_brand.brand_id)   and
			industry.industry_id =industryreport.industry_id
			order by industry_name asc";	
		} else {
			$sql_industry="SELECT distinct industry.industry_name, industry.industry_id  from industry ,  industryreport where  
			industryreport.company_id='$coid'   and
			industry.industry_id =industryreport.industry_id
			order by industry_name asc";	
		} 
		return CHtml::listData($companies = Yii::app()->db3->createCommand($sql_industry)->queryAll(),'industry_id','industry_name');
	}

	public static function AllIndustries()
	{
		$sql_industry="SELECT distinct industry.industry_name, industry.industry_id  from industry ,  industryreport where  
			industry.industry_id =industryreport.industry_id
			order by industry_name asc";
		return CHtml::listData($companies = Yii::app()->db3->createCommand($sql_industry)->queryAll(),'industry_id','industry_name');
	}

	public static function AgencyCompanies($id){
		if(Yii::app()->user->rpts_only==1){
			$agency_companies = "SELECT company_id, company_name from user_table where level=6 order by company_name asc";
		}else{
			$agency_companies = "SELECT distinct(brand_table.company_id), company_name from brand_table, agency_brand, user_table where agency_brand.agency_id='$id' and brand_table.brand_id=agency_brand.brand_id and user_table.company_id=brand_table.company_id  and brand_table.status=1  order by company_name asc";
		}
		return CHtml::listData($companies = Yii::app()->db3->createCommand($agency_companies)->queryAll(),'company_id','company_name');
	}

	public static function AdfliteCompanies($id){
		$adflite_companies="SELECT * from user_table,adflite_user_client_logs where  adflite_user_client_logs.adflite_id='$id' and adflite_user_client_logs.company_id=user_table.company_id  order by company_name asc";	
		return CHtml::listData($companies = Yii::app()->db3->createCommand($adflite_companies)->queryAll(),'company_id','company_name');
	}

	public static function AdfliteStations($id){
		$sql_station="SELECT distinct(station.station_name),station.station_id,station.station_code from station, adflite_my_stations 
		where station.online=1 and adflite_my_stations.adflite_id='$id' and station.station_id=adflite_my_stations.station_id order by station.station_name asc";
		return CHtml::listData($stations = Yii::app()->db3->createCommand($sql_station)->queryAll(),'station_id','station_name');
	}

	public static function AdfliteCompetitorStations($id){
		$sql_station="SELECT distinct(station.station_name),station.station_id,station.station_code from station, adflite_client
		where station.online=1 and adflite_client.adflite_id='$id' and station.station_id=adflite_client.station_id  order by station.station_name asc";
		return CHtml::listData($stations = Yii::app()->db3->createCommand($sql_station)->queryAll(),'station_id','station_name');
	}

	public static function AgencySelect(){
		return CHtml::listData(AgencyCompany::model()->findAll(),'agency_id','agency_name');
	}

	public static function Regions()
	{
		return CHtml::listData();
	}
}
