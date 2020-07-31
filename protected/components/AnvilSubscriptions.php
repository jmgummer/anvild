<?php

/**
* Anvil Subscriptions Component Class
* This Class Is Used To Return The Users/Company Subscriptions
* DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
* 
* @package     Anvil
* @subpackage  Components
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/

class AnvilSubscriptions{
	/**
	*
	* @return  Return Agency Subscriptions
	* @throws  InvalidArgumentException
	* @todo    Use This Function to Create Electronic Table Head
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/
	public static function AgencySubscribed($company_id)
	{
		if($subscriptions = AgencyReport::model()->findAll('agency_id=:a', array(':a'=>$company_id))){
			$prefix = '';
			$reports = '';
			foreach ($subscriptions as $key)
			{
				$reports .= $prefix . $key->report_id;
				$prefix = ', ';
			}
		}else{
			$reports = '';
		}
		return $reports;
	}

	public static function CheckKeywordSubscription($company_id){
		$sql = "SELECT * FROM user_table WHERE company_id=$company_id AND keysubscription=1";
		if($query =UserTable::model()->findBySql($sql)){
			return true;
		}else{
			return false;
		}
	}

	public static function AdfliteCompanies($company_id)
	{
		if($subscriptions = AdfliteUserClientLogs::model()->findAll('adflite_id=:a', array(':a'=>$company_id))){
			$prefix = '';
			$clients = '';
			foreach ($subscriptions as $key)
			{
				$clients .= $prefix . $key->company_id;
				$prefix = ', ';
			}
		}else{
			$clients = '';
		}
		return $clients;
	}

	public static function AdfliteSubscriptions($company_id)
	{
		if($subscriptions = AdfliteReports::model()->findAll('adflite_id=:a', array(':a'=>$company_id))){
			$prefix = '';
			$reports = '';
			foreach ($subscriptions as $key)
			{
				$reports .= $prefix . $key->report_id;
				$prefix = ', ';
			}
		}else{
			$reports = '';
		}
		return $reports;
	}

	/**
	*
	* @return  Return Client Subscriptions
	* @throws  InvalidArgumentException
	* @todo    Use This Function to Create Electronic Table Head
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function ClientSubscribed($client_id)
	{
		if($subscriptions = ClientReportsAssignment::model()->findAll('co_id=:a', array(':a'=>$client_id))){
			$prefix = '';
			$reports = '';
			foreach ($subscriptions as $key)
			{
				$reports .= $prefix . $key->report_id;
				$prefix = ', ';
			}
		}else{
			$reports = '';
		}
		return $reports;
	}

	/**
	*
	* @return  Check if user/company is subscribed
	* @throws  InvalidArgumentException
	* @todo    Use This Function to Create Electronic Table Head
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function IfSubscribed($subscriptions_list,$check)
	{
		$subscribedlist = explode(',',$subscriptions_list);
		if (in_array($check, $subscribedlist)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}