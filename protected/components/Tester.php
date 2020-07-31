<?php

/**
 * 
 */
class Tester
{

	public static function NCCReports(){
		// require_once '/mpdf/autoload.php';
		date_default_timezone_set('Africa/Nairobi');
		ini_set('display_errors',1);
		ini_set('display_startup_errors',1);
		error_reporting(-1);
		//Prepare DB connection
		function OutdoorDB(){
			$servername = "192.168.0.4";
			$username = "root";
			$password = "Pambazuka08";
			$dbname = "rf_outdoor";
			$conn = new mysqli($servername, $username, $password, $dbname);
			if ($conn->connect_error) {
			    die("Connection failed: " . $conn->connect_error);
			}else{
				return $conn;
			}
		}
		$db = OutdoorDB();
		$countyname = "Nairobi";
		$companysql="SELECT DISTINCT company_name FROM nccreport WHERE county='Nairobi'";
		$companyquery = $db->query($companysql);
		if ($companyquery && $companyquery->num_rows>0) {
			while ($companyrow = $companyquery->fetch_assoc()) {
				
				if($companyrow['company_name']==null){
					echo $companyname = 'null';
					$company_sub_query = "company_name is null";
				}else{
					echo $companyname = $companyrow['company_name'];
					$company_sub_query = "company_name = '$companyname'";
				}
				echo "\n";
				// Generate Company Month Records
				$monthsql = "SELECT DISTINCT logmonth FROM nccreport WHERE county='$countyname' AND $company_sub_query";
				$monthquery = $db->query($monthsql);
				if ($monthquery && $monthquery->num_rows>0) {
					while ($monthrow = $monthquery->fetch_assoc()) {
						$html = "<html>";
						$logmonth = $monthrow['logmonth'];
						// Generate Each Company Records
						echo $companyrecords = "SELECT * FROM nccreport WHERE county='$countyname' AND $company_sub_query AND logmonth='$logmonth'";
						echo "\n";
						$company_records_query = $db->query($companyrecords);
						if ($company_records_query && $company_records_query->num_rows>0) {
							$recordcounter = 0;
							while ($recordrow = $company_records_query->fetch_assoc()) {
								$site_name = $recordrow['site_name'];
								$bbtype = $recordrow['bbtype'];
								$road_name = $recordrow['road_name'];
								$junction_name = $recordrow['junction_name'];
								$lattitude = $recordrow['lattitude'];
								$longitude = $recordrow['longitude'];
								$ward_name = $recordrow['ward_name'];
								$logdate = $recordrow['logdate'];
								$limage = $recordrow['limage'];
								$maplink = "https://maps.googleapis.com/maps/api/staticmap?zoom=17&size=600x300&maptype=map&markers=color:red%7C$lattitude,$longitude";
								// $html .= "<div >";
								$html .= '<div style="float: left; width: 45%;">';
								$html .= "<p>Company Name - $companyname</p>";
								$html .= "<p>Site Name - $site_name</p>";
								$html .= "<p>Road Name - $road_name</p>";
								$html .= "<p>Junction Name - $junction_name</p>";
								$html .= "<p>Lat/Long - $lattitude,$longitude</p>";
								$html .= "<p>Ward - $ward_name</p>";
								$html .= "<p>Log Date - $logdate</p>";
								$html .= "<p>Image Link - $limage</p>";
								$html .= "<p><a href='$maplink' target='blank'>Map Link</a></p>";
								$html .= "</div>";
								$html .= '<div style="float: right; width: 50%;">';
								$html .= "<p><img src='$limage' height='410px'></p>";
								$html .= "</div>";
								$html .= '<div style="clear: both; margin: 0pt; padding: 0pt; "></div>';
								$html .= "<hr>";
								if($recordcounter==2){
									$recordcounter = 0;
									$html .= '<p style="page-break-after: always;">&nbsp;</p>';
								}else{
									$recordcounter++;
								}
							}
						}
						$html .= "</html>";
						$monthname = str_replace('"', "", $companyname)."-".$logmonth.".html";
						$filename_html = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/nccdata/".$monthname;
						$filecontent = $html;
						if (!$handle = fopen($filename_html, 'w')) {
							echo "Cannot open file ($filename_html')";
						}else{
							Tester::StandardPDF($html,str_replace('html', '', $monthname),$companyname);
							if (fwrite($handle, $filecontent) === FALSE) 
							{
								echo "Cannot write to file ($filename_html)";
							}
							fclose($handle);
						}
						// exit();
					}
				}
				echo "\n\n";
			}
		}
	}
	
	public static function StandardPDF($html,$filename,$companyname){
		/* 
		** PDF Time
		*/
		$package = '';
		$pdf = Yii::app()->ePdf2->WriteOutput($html,array());
		$filename=str_replace(" ","_",$filename);
		$filename=preg_replace('/[^\w\d_ -]/si', '', $filename);
		$filename_pdf=$filename.'.pdf';
		$location = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/nccdata/pdf/".$filename_pdf;
		if(file_put_contents($location, $pdf)){
			echo "yeah";
		}
		return $fppackage;
	}
}