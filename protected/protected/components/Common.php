<?php

/**
* Common Component Class
* This Class Is Used To Return General, Common PHP Functions Running Across the Whole Application
* DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
* 
* @package     Anvil
* @subpackage  Components
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version     v.1.0
* @since       July 2008
*/

class Common{

    /** 
    *
    * @return  Return Dropdown for Months
    * @throws  InvalidArgumentException
    * @todo    Use This Function to create a Dropdown for Months
    *
    * @since   2008
    * @author  Steve Ouma Oyugi - Reelforge Development Team
    * @edit    2014-07-08 
    *   DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
    */

	public static function monthDropdown($name="month", $selected=null)
	{
        $dd = '<select class="form-control" name="'.$name.'" id="'.$name.'">';

        $months = array(
                1 => 'january',
                2 => 'february',
                3 => 'march',
                4 => 'april',
                5 => 'may',
                6 => 'june',
                7 => 'july',
                8 => 'august',
                9 => 'september',
                10 => 'october',
                11 => 'november',
                12 => 'december');
        /*** the current month ***/
        $selected = is_null($selected) ? date('n', time()) : $selected;

        for ($i = 1; $i <= 12; $i++)
        {
        	if($i<10){
        		$dd .= '<option value="0'.$i.'"';
        	}else{
        		$dd .= '<option value="'.$i.'"';
        	}
                
            if ($i == $selected)
            {
                    $dd .= ' selected';
            }
            /*** get the month ***/
            $dd .= '>'.ucfirst($months[$i]).'</option>';
        }
        $dd .= '</select>';
        return $dd;
	}

    /** 
    *
    * @return  Return Dropdown for Years Starting 2008
    * @throws  InvalidArgumentException
    * @todo    Use This Function to create a Dropdown for Years
    *
    * @since   2008
    * @author  Steve Ouma Oyugi - Reelforge Development Team
    * @edit    2014-07-08 
    *   create dropdown for Years Starting 2008 - represents the begining of Reelforge Data Collection
    *   DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
    */

	public static function YearDropdown($start_year='2008', $id='year', $selected=null)
    {

        /*** the current year ***/
        $selected = is_null($selected) ? date('Y') : $selected;

        /*** range of years ***/
        $end_year = date('Y');
        $r = range($start_year, $end_year);

        /*** create the select ***/
        $select = '<select class="form-control" name="'.$id.'" id="'.$id.'">';
        foreach( $r as $year )
        {
            $select .= "<option value=\"$year\"";
            $select .= ($year==$selected) ? ' selected="selected"' : '';
            $select .= ">$year</option>\n";
        }
        $select .= '</select>';
        return $select;
    }

    /** 
    *
    * @return  Return Dropdown for Days
    * @throws  InvalidArgumentException
    * @todo    Use This Function to create a Dropdown for Days
    *
    * @since   2008
    * @author  Steve Ouma Oyugi - Reelforge Development Team
    * @edit    2014-07-08 
    *   DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
    */

    public static function createDays($id='day_select', $selected=null)
    {
        /*** range of days ***/
        $r = range(1, 31);

        /*** current day ***/
        $selected = is_null($selected) ? date('d') : $selected;

        $select = "<select name=\"$id\" id=\"$id\">\n";
        foreach ($r as $day)
        {
            $select .= "<option value=\"$day\"";
            $select .= ($day==$selected) ? ' selected="selected"' : '';
            $select .= ">$day</option>\n";
        }
        $select .= '</select>';
        return $select;
    }

    /** 
    *
    * @return  Return Dropdown for Hours
    * @throws  InvalidArgumentException
    * @todo    Use This Function to create a Dropdown for Hours
    *
    * @since   2008
    * @author  Steve Ouma Oyugi - Reelforge Development Team
    * @edit    2014-07-08 
    *   DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
    */

    public static function createHours($id='hours_select', $selected=null)
    {
        /*** range of hours ***/
        $r = range(1, 12);

        /*** current hour ***/
        $selected = is_null($selected) ? date('h') : $selected;

        $select = "<select name=\"$id\" id=\"$id\">\n";
        foreach ($r as $hour)
        {
            $select .= "<option value=\"$hour\"";
            $select .= ($hour==$selected) ? ' selected="selected"' : '';
            $select .= ">$hour</option>\n";
        }
        $select .= '</select>';
        return $select;
    }

    public static function POFTempTable()
    {
        /* Create Temp table */
        $temp_table="anvil_log_temp_" . date("Ymdhis");
        $temp_sql="CREATE TEMPORARY  TABLE `".$temp_table."` (
        `id` INT  AUTO_INCREMENT PRIMARY KEY ,
        `auto_id` INT,
        `brand_id` INT  ,
        `entry_type_id` INT  ,
        `incantation_id` INT  ,
        `station_id` INT  ,
        `date` varchar(30)  ,
        `time` varchar(30)  ,
        `comment` varchar(30)  ,
        `rate` INT  ,
        `brand_name` varchar(255),
        `entry_type` varchar(255),
        `incantation_name` varchar(255),
        `duration` varchar(255),
        `file` varchar(255),
        `video_file` varchar(255),
        `program_name` text 
        ) ENGINE = MYISAM ;";
        Yii::app()->db3->createCommand($temp_sql)->execute();

        return $temp_table;
    }

    public static function MediaHouseTempTable()
    {
        /* Create Temp table */
        $temp_table="mediahouse_log_temp_" . date("Ymdhis");
        $temp_sql="CREATE TEMPORARY  TABLE `".$temp_table."` (
        `id` INT  AUTO_INCREMENT PRIMARY KEY ,
        `adid` INT ,
        `station` text,
        `station_id` INT  ,
        `brand_id` INT  ,
        `ad_name` text  ,
        `brand_name` text  ,
        `program` text ,
        `date` text  ,
        `time` text  ,
        `type` text  ,
        `duration` text  ,
        `rate` text  ,
        `date_time` text,
        `active` INT,
        `tabletype` text,
        `adtype` text,
        `comment` text,
        `score` text,
        `file` text,
        `videofile` text ,
        `audience` text ,
        `grp` text 
        ) ENGINE = MYISAM ;";
        Yii::app()->db3->createCommand($temp_sql)->execute();

        return $temp_table;
    }

    public static function POFCleaner($company_id,$temp_table){
        $subscription = "SELECT * FROM client_subscriptions WHERE company_id = $company_id and report_id = 1 order by id desc";
        if($period = Yii::app()->db3->createCommand($subscription)->queryRow()){
            $startdate = $period['start_date'];
            $enddate = $period['end_date'];
            $cleaner_sql = "DELETE FROM $temp_table WHERE (date < '$startdate' OR date > '$enddate')";
            $deletesql = Yii::app()->db3->createCommand($cleaner_sql)->execute();
        }
    }

    public static function CompetitorPOFTempTable()
    {
        /* Create Temp table */
        $temp_table="anvil_log_temp_" . date("Ymdhis");
        $temp_sql="CREATE TEMPORARY  TABLE `".$temp_table."` (
        `id` INT  AUTO_INCREMENT PRIMARY KEY ,
        `auto_id` INT,
        `brand_id` INT  ,
        `entry_type_id` INT  ,
        `incantation_id` INT  ,
        `station_id` INT  ,
        `date` varchar(30)  ,
        `time` varchar(30)  ,
        `comment` varchar(30)  ,
        `rate` INT  ,
        `brand_name` varchar(255),
        `entry_type` varchar(255),
        `incantation_name` varchar(255),
        `duration` varchar(255),
        `file` varchar(255),
        `video_file` varchar(255),
        `station_name` varchar(255),
        `station_type` varchar(255),
        `company_name` varchar(255)
        ) ENGINE = MYISAM ;";
        Yii::app()->db3->createCommand($temp_sql)->execute();

        return $temp_table;
    }

    public static function RateTempTable()
    {
        /* Create Temp table */
        $temp_table="rates_log_temp_" . date("Ymdhis");
        $temp_sql="CREATE TEMPORARY  TABLE `".$temp_table."` (
        `id` INT  AUTO_INCREMENT PRIMARY KEY ,
        `auto_id` INT,
        `brand_id` INT  ,
        `entry_type_id` INT  ,
        `incantation_id` INT  ,
        `station_id` INT  ,
        `date` varchar(30)  ,
        `time` varchar(30)  ,
        `comment` varchar(30)  ,
        `rate` INT  ,
        `brand_name` varchar(255),
        `entry_type` varchar(255),
        `incantation_name` varchar(255),
        `duration` varchar(255),
        `file` varchar(255),
        `video_file` varchar(255),
        `program_name` text 
        ) ENGINE = MYISAM ;";
        Yii::app()->db3->createCommand($temp_sql)->execute();
        return $temp_table;
    }

    public static function DashboardTempTable()
    {
        /* Create Temp table */
        $temp_table="dashboard_log_temp_" . date("Ymdhis");
        $temp_sql="CREATE TEMPORARY TABLE $temp_table (  
        `auto_id` int(11) NOT NULL auto_increment,  
        `reel_auto_id` int,
        `company_name` varchar (100) NOT NULL ,
        `company_id` INT NOT NULL ,
        `brand_name` varchar (100) ,
        `brand_id` INT ,
        `reel_date` date default NULL,
        `reel_time` time default NULL,
        `station_name` varchar (100) ,
        `station_id` INT ,
        `station_type` varchar (100) ,
        `rate` INT ,
        PRIMARY KEY  (`auto_id`)) ENGINE = MYISAM ; ";
        Yii::app()->db3->createCommand($temp_sql)->execute();
        /* Add an index to the table */
        $sql_index_temp=" ALTER TABLE `$temp_table` ADD INDEX (`company_id`, `brand_id`, `reel_date` ,`station_id`)  ";
        Yii::app()->db3->createCommand($sql_index_temp)->execute();
        /* Return the Table */
        return $temp_table;
    }

    public static function PrintTempTable(){
        /* Create Temp table */
        $temp_table="print_temp_" . date("Ymdhis");
        $temp_sql="CREATE TEMPORARY TABLE $temp_table (  
        `auto_id` int(11) NOT NULL auto_increment,  
        `Media_House_List` varchar (100),
        `media_house_id` varchar (100) NOT NULL ,
        `print_id` INT NOT NULL ,
        `file` varchar (100) ,
        `page` varchar (100) ,
        `col` varchar (100),
        `centimeter` varchar (100),
        `ave` varchar (100) ,
        `brand_id` INT ,
        `this_date` varchar (100) ,
        `brand` varchar (100) ,
        PRIMARY KEY  (`auto_id`)) ENGINE = MYISAM ";
        Yii::app()->db3->createCommand($temp_sql)->execute();
        /* Return the Table */
        return $temp_table;
    }

    public static function BrandAirplayTempTable(){
        /* Create Temp table */
        $temp_table="brand_airplay_temp_" . date("Ymdhis");
        $temp_sql="CREATE TEMPORARY TABLE $temp_table (  
        `auto_id` int(11) NOT NULL auto_increment,  
        `brand_id` varchar (100),
        `company_id` varchar (100) ,
        `brand_name` varchar (100) ,
        `rate` varchar (100) ,
        `station_id` varchar (100) ,
        `date` varchar (100) ,
        `duration` varchar (100),
        PRIMARY KEY  (`auto_id`)) ENGINE = MYISAM ";
        Yii::app()->db3->createCommand($temp_sql)->execute();
        /* Return the Table */
        return $temp_table;
    }

    public static function OutdoorTempTable(){
        /* Create Temp table */
        $temp_table="outdoor_temp_" . date("Ymdhis");
        $temp_sql="CREATE TEMPORARY TABLE $temp_table (  
        `auto_id` int(11) NOT NULL auto_increment,  
        `company_name` varchar (100) ,
        `brand_name` varchar (100) ,
        `inspection_date` varchar (100) ,
        `inspection_time` varchar (100) ,
        `quality` varchar (100) ,
        `comment` varchar (100) ,
        `inspector_name` varchar (100) ,
        `audience_male` varchar (100) ,
        `audience_female` varchar (100) ,
        `audience_children` varchar (100) ,
        `entry_type` varchar (100) ,
        `length` varchar (100) ,
        `incantation_id` varchar (100) ,
        `site_name` varchar (100) ,
        `site_location` varchar (100) ,
        `site_town` varchar (100) ,
        `site_type` varchar (100) ,
        `town_name` varchar (100) ,
        `province_name` varchar (100) ,
        PRIMARY KEY  (`auto_id`)) ENGINE = MYISAM ";
        Yii::app()->db3->createCommand($temp_sql)->execute();
        /* Return the Table */
        return $temp_table;
    }

    public static function BugLogger($message){
        $email = 'sit@reelforge.com';
        Yii::app()->mailer->Host =  '192.168.0.45';
        Yii::app()->mailer->IsSMTP();
        Yii::app()->mailer->From = 'noreply@reelforge.com';
        Yii::app()->mailer->FromName = 'Reelforge SIT';
        Yii::app()->mailer->AddBCC($email);
        Yii::app()->mailer->AddBCC('faith.muthoka@reelforge.com');
        Yii::app()->mailer->Subject = 'Anvild Bug Report';
        Yii::app()->mailer->Body = $message;
        Yii::app()->mailer->IsHTML(true);
        if(Yii::app()->mailer->Send()){
            return TRUE;
        }else{
            return FALSE;
        }
    }


    /* Check Session Status */
    public static function CheckSession()
    {
        if ( php_sapi_name() !== 'cli' ) {
            if ( version_compare(phpversion(), '5.4.0', '>=') ) {
                return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }

    public static function sec2hms ($sec, $padHours = false)
    {
        // holds formatted string
        $hms = "";

        // there are 3600 seconds in an hour, so if we
        // divide total seconds by 3600 and throw away
        // the remainder, we've got the number of hours
        $hours = intval(intval($sec) / 3600);

        // add to $hms, with a leading 0 if asked for
        $hms .= ($padHours) ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':' : $hours. ':';

        // dividing the total seconds by 60 will give us
        // the number of minutes, but we're interested in
        // minutes past the hour: to get that, we need to
        // divide by 60 again and keep the remainder
        $minutes = intval(($sec / 60) % 60);

        // then add to $hms (with a leading 0 if needed)
        $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';

        // seconds are simple - just divide the total
        // seconds by 60 and keep the remainder
        $seconds = intval($sec % 60);

        // add to $hms, again with a leading 0 if needed
        $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

        // done!
        return $hms;
    }

    public static function IpRange( $ip, $range ) {
        if ( strpos( $range, '/' ) == false ) {
            $range .= '/32';
        }
        // $range is in IP/CIDR format eg 127.0.0.1/24
        list( $range, $netmask ) = explode( '/', $range, 2 );
        $range_decimal = ip2long( $range );
        $ip_decimal = ip2long( $ip );
        $wildcard_decimal = pow( 2, ( 32 - $netmask ) ) - 1;
        $netmask_decimal = ~ $wildcard_decimal;
        return ( ( $ip_decimal & $netmask_decimal ) == ( $range_decimal & $netmask_decimal ) );
    }

    public static function decbin32 ($dec) {
        return str_pad(decbin($dec), 32, '0', STR_PAD_LEFT);
    }

    public static function ip_in_range($ip, $range) {
        if(strpos($range, '/') !== false) {
            list($range, $netmask) = explode('/', $range, 2);
            if(strpos($netmask, '.') !== false) {
                $netmask = str_replace('*', '0', $netmask);
                $netmask_dec = ip2long($netmask);
                return ( (ip2long($ip) & $netmask_dec) == (ip2long($range) & $netmask_dec) );
            }else{
                $x = explode('.', $range);
                while(count($x)<4) $x[] = '0';
                list($a,$b,$c,$d) = $x;
                $range = sprintf("%u.%u.%u.%u", empty($a)?'0':$a, empty($b)?'0':$b,empty($c)?'0':$c,empty($d)?'0':$d);
                $range_dec = ip2long($range);
                $ip_dec = ip2long($ip);
                $wildcard_dec = pow(2, (32-$netmask)) - 1;
                $netmask_dec = ~ $wildcard_dec;
                return (($ip_dec & $netmask_dec) == ($range_dec & $netmask_dec));
            }
        }else{
            if(strpos($range, '*') !==false) { // a.b.*.* format
                $lower = str_replace('*', '0', $range);
                $upper = str_replace('*', '255', $range);
                $range = "$lower-$upper";
            }

            if(strpos($range, '-')!==false) { // A-B format
                list($lower, $upper) = explode('-', $range, 2);
                $lower_dec = (float)sprintf("%u",ip2long($lower));
                $upper_dec = (float)sprintf("%u",ip2long($upper));
                $ip_dec = (float)sprintf("%u",ip2long($ip));
                return ( ($ip_dec>=$lower_dec) && ($ip_dec<=$upper_dec) );
            }

            echo 'Range argument is not in 1.2.3.4/24 or 1.2.3.4/255.255.255.0 format';
            return false;
        }
    }

    public static function CountryCurrency($countryid){
        $sql = "SELECT * FROM country WHERE country_id = $countryid";
        if($country = Country::model()->findBySql($sql)){
            return $country->currency;
        }else{
            return 'KES';
        }
    }

}