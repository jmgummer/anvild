<?php

/**
* Tracker Component Class
* This Class Is Used To Track the Footprint of Every Action on this Site
* If You Break This File Then Tracking Exits
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

class Tracker{

	/** 
	*
	* @return  Log Users
	* @throws  InvalidArgumentException
	* @todo    Logs all users movement throughout the application
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function Utrack()
	{
		/* If the user is not logged in just terminate and redirect to login page */
		if(Yii::app()->user->isGuest){
			Yii::app()->user->logout();
			if(isset($_SESSION)){
				session_start();
				session_destroy();
			}
			$site_url = "../../reelforge_back/";
			$this->redirect($site_url);
		}
		
		$visitor_ip = GetHostByName(Tracker::ForceIP());
		$visitor_browser = Tracker::getBrowserType();
		$visitor_hour = date("h");
		$visitor_minute = date("i");
		$visitor_day = date("d");
		$visitor_month = date("m");
		$visitor_year = date("Y");
		$visitor_date = date("Y-m-d H:i:s");
		$visitor_refferer = GetHostByName(Tracker::Referrer());
		$visited_page = $visitor_page = Tracker::selfURL();
		$company_name = Yii::app()->user->company_name;
		$platform = Tracker::Platform();
		$userid = Yii::app()->user->user_id;
		$usertype = Yii::app()->user->usertype;

		//write the required data to database

		$model = new VisitorsTable;
		$model ->visitor_ip = $visitor_ip;
		$model ->visitor_browser = $visitor_browser;
		$model ->visitor_hour = $visitor_hour;
		$model ->visitor_minute = $visitor_minute;
		$model ->visitor_day = $visitor_day;
		$model ->visitor_month = $visitor_month;
		$model ->visitor_year = $visitor_year;
		$model ->visitor_date = $visitor_date;
		$model ->visitor_refferer = $visitor_refferer;
		$model ->visitor_page = $visitor_page;
		$model ->company_name = $company_name;
		$model ->platform = $platform;
		$model ->userid = $userid;
		$model ->usertype = $usertype;
		$model->save();

		if($usertype=='adflite'){
			if(!isset(Yii::app()->user->adflite_clients) || !isset(Yii::app()->user->adflite_reports)){
				Yii::app()->user->logout();
				if(isset($_SESSION)){
					session_start();
					session_destroy();
				}
			}
		}
	}

	/** 
	*
	* @return  Return the Browser Type
	* @throws  InvalidArgumentException
	* @todo    This Function is Used to Return the Browser Type
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function getBrowserType () 
	{
		if (!empty($_SERVER['HTTP_USER_AGENT']))
		{
		   $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
		}
		else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT']))
		{
		   $HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
		}
		else if (!isset($HTTP_USER_AGENT))
		{
		   $HTTP_USER_AGENT = '';
		}
		if (preg_match('/Opera/ ', $HTTP_USER_AGENT))
		{
		   $browser_agent = 'opera';
		}
		else if (preg_match('/MSIE/', $HTTP_USER_AGENT))
		{
		   $browser_agent = 'ie';
		}
		else if (preg_match('/OmniWeb/', $HTTP_USER_AGENT))
		{
		   $browser_agent = 'omniweb';
		}
		else if (preg_match('/Netscape/', $HTTP_USER_AGENT))
		{
		   $browser_agent = 'netscape';
		}
		else if (preg_match('/Mozilla/', $HTTP_USER_AGENT))
		{
		   $browser_agent = 'mozilla';
		}
		else if (preg_match('/Konqueror/', $HTTP_USER_AGENT))
		{
		   $browser_agent = 'konqueror';
		}
		else
		{
		   $browser_version = 0;
		   $browser_agent = 'other';
		}
		return $browser_agent;
	}

	/** 
	*
	* @return  Return the page that the user is on
	* @throws  InvalidArgumentException
	* @todo    This Function is used to return the page that the user is on
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	* 	I totally feel like GOOGLE as I do this, but its good stuff, we need to know
	*	what everyone is doing on the application, just incase. This is a Business Application!
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function selfURL() {
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$protocol = Tracker::strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
		return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
	}

	/** 
	*
	* @return  Return the Server Protocol
	* @throws  InvalidArgumentException
	* @todo    Takes the Server Protocol Variable, strips off a few things and Voila!
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function strleft($s1, $s2) { return substr($s1, 0, strpos($s1, $s2)); }

	/**
	*
	* @return  Return Pagination
	* @throws  InvalidArgumentException
	* @todo    Returns number of Pages!
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function paginate($start,$limit,$total,$filePath,$otherParams) {
        global $lang;

        $allPages = ceil($total/$limit);

        $currentPage = floor($start/$limit) + 1;

        $pagination = "";
        if ($allPages>10) {
                $maxPages = ($allPages>9) ? 9 : $allPages;

                if ($allPages>9) {
                        if ($currentPage>=1&&$currentPage<=$allPages) {
                                $pagination .= ($currentPage>4) ? " ... " : " ";

                                $minPages = ($currentPage>4) ? $currentPage : 5;
                                $maxPages = ($currentPage<$allPages-4) ? $currentPage : $allPages - 4;

                                for($i=$minPages-4; $i<$maxPages+5; $i++) {
                                        $pagination .= ($i == $currentPage) ? "<a href=\"#\"
                                        class=\"current\">".$i."</a> " : "<a href=\"".$filePath."?
                                        start=".(($i-1)*$limit).$otherParams."\">".$i."</a> ";
                                }
                                $pagination .= ($currentPage<$allPages-4) ? " ... " : " ";
                        } else {
                                $pagination .= " ... ";
                        }
                }
        } else {
                for($i=1; $i<$allPages+1; $i++) {
                $pagination .= ($i==$currentPage) ? "<a href=\"#\" class=\"current\">".$i."</a> "
                : "<a href=\"".$filePath."?start=".(($i-1)*$limit).$otherParams."\">".$i."</a> ";
                }
        }

        if ($currentPage>1) $pagination = "<a href=\"".$filePath."?
        start=0".$otherParams."\">FIRST</a> <a href=\"".$filePath."?
        start=".(($currentPage-2)*$limit).$otherParams."\"><</a> ".$pagination;
        if ($currentPage<$allPages) $pagination .= "<a href=\"".$filePath."?
        start=".($currentPage*$limit).$otherParams."\">></a> <a href=\"".$filePath."?
        start=".(($allPages-1)*$limit).$otherParams."\">LAST</a>";

        echo '<div class="pages">' . $pagination . '</div>';
	}

	/** 
	*
	* @return  Return Users IP Address
	* @throws  InvalidArgumentException
	* @todo    This function obtains the users IP even if its IPV6, The Second is commonsense, Right!
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function ForceIP()
	{
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
		    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		    $ip_address = $_SERVER['REMOTE_ADDR'];
		}
		return $ip_address;
	}

	/** 
	*
	* @return  Return Referrer
	* @throws  InvalidArgumentException
	* @todo    Use This Function to Obtain the Page from which the user is referred
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function Referrer()
	{
		if(isset($_SERVER['HTTP_REFERER'])) {
			$Referrer = $_SERVER['HTTP_REFERER'];
		}else{
			$Referrer = '';
		}
		return $Referrer;
	}

	/** 
	*
	* @return  Return Platform
	* @throws  InvalidArgumentException
	* @todo    Use This Function to Obtain the Operating Platform that the user is running on, Browser Type and Operating System
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function Platform()
	{
		if (!empty($_SERVER['HTTP_USER_AGENT']))
		{
		   $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
		}
		else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT']))
		{
		   $HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
		}
		else if (!isset($HTTP_USER_AGENT))
		{
		   $HTTP_USER_AGENT = '';
		}
		if (preg_match('/linux/i', $HTTP_USER_AGENT))
		{
		   $platform = 'linux';
		}
		else if (preg_match('/macintosh|mac os x/i', $HTTP_USER_AGENT))
		{
		   $platform = 'mac';
		}
		else if (preg_match('/windows|win32/i', $HTTP_USER_AGENT))
		{
		   $platform = 'windows';
		}
		else
		{
		   $platform = 'other';
		}
		return $platform;
	}
}