<?php

/**
* UserIdentity Component Class
* UserIdentity represents the data needed to identity a user
* It contains the authentication method that checks if the provided
* data can identity the user.
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

class UserIdentity extends CUserIdentity
{
	/**
	*
	* @return boolean whether authentication succeeds.
	* @throws  InvalidArgumentException
	* @todo    Use This Function to Authenticate a user
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/
	
	public function authenticate()
	{
		/* Regular Clients Login */
		$pusername = $this->username;
		$hashedpass = md5($this->password);

		$sql_check = "SELECT * FROM client_users 
		INNER JOIN user_table ON client_users.co_id=user_table.company_id 
		WHERE client_users.username='$pusername' AND client_users.password='$hashedpass' 
		AND  client_users.user_status=1 AND client_users.plus_status=0 AND user_table.login=1";
		$client = AnvilClients::model()->findBySql($sql_check);

		/* Agency Clients Login */
		$sql_activate = "SELECT * from user_table where username='$this->username' and password=md5('$this->password') and level=3 and login=1 and plus_status=0";
		$agency = UserTable::model()->findBySql($sql_activate);

		/** 
		* Adflite Clients Login
		* Adflite is Used by Mediahouses Only
		*/

		$sql_adflite = "SELECT * from adflite_users where username='$this->username' and password=md5('$this->password')";
		$adflite = AdfliteUsers::model()->findBySql($sql_adflite);

		if($client==FALSE && $agency==FALSE && $adflite==FALSE){
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}else{
			
			if($client==TRUE){
				$this->username = 'admin';
				$company_id = $client->co_id;
				// Check the Country Assignment
				$this->setState('country_id', Yii::app()->params['country_id']);
				$this->setState('country_code', Yii::app()->params['country_code']);
				$this->setState('user_id', $client->users_id);
				$this->setState('client_name',$client->UserName);
				$this->setState('company_id',$company_id);
				$this->setState('company_name', $client->Company);
				$this->setState('company_level', $client->UserLevel);
				$this->setState('usertype','client');
				$this->setState('rpts_only',0);
				$subscriptions=AnvilSubscriptions::ClientSubscribed($company_id);
				$this->setState('subscriptions',$subscriptions);
				$this->errorCode=self::ERROR_NONE;
			}

			if($agency==TRUE){
				$this->setState('agencyusername',$this->username);
				$this->username = 'admin';
				$company_id = $agency->company_id;
				// Check the Country Assignment
				$this->setState('country_id', Yii::app()->params['country_id']);
				$this->setState('country_code', Yii::app()->params['country_code']);
				$this->setState('user_id', $company_id);
				$this->setState('client_name',$agency->company_rep_name);
				$this->setState('company_id',$company_id);
				$this->setState('company_name', $agency->company_name);
				$this->setState('company_level', $agency->level);
				$this->setState('usertype','agency');
				$this->setState('rpts_only',$agency->rpts_only);
				$subscriptions=AnvilSubscriptions::AgencySubscribed($company_id);
				$this->setState('subscriptions',$subscriptions);
				$this->errorCode=self::ERROR_NONE;
			}

			if($adflite==TRUE){
				$this->setState('adfliteusername',$this->username);
				$this->username = 'admin';
				$company_id = $adflite->adflite_id;
				$this->setState('country_id', Yii::app()->params['country_id']);
				$this->setState('country_code', Yii::app()->params['country_code']);
				$this->setState('user_id', $adflite->adflite_user_id);
				$this->setState('client_name',$adflite->ClientName);
				if($adflite_company = Adflite::model()->find('adflite_id=:a', array(':a'=>$company_id))){
					$this->setState('company_id',$adflite_company->adflite_id);
					$this->setState('company_name', $adflite_company->adflite_name);
				}else{
					$this->setState('company_id',$company_id);
					$this->setState('company_name', 'Unknown');
				}
				$this->setState('company_level', $adflite->user_level);
				$this->setState('usertype','adflite');
				$this->setState('rpts_only',0);
				$subscriptions=AnvilSubscriptions::AgencySubscribed($company_id);
				$this->setState('subscriptions',$subscriptions);
				$adfliteclients = AnvilSubscriptions::AdfliteCompanies($company_id);
				$this->setState('adflite_clients',$adfliteclients);
				$reports = AnvilSubscriptions::AdfliteSubscriptions($company_id);
				$this->setState('adflite_reports',$reports);
				$this->errorCode=self::ERROR_NONE;
			}
		}

		return !$this->errorCode;
	}
}