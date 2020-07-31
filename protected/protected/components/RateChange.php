<?php

class RateChange{
	public function ProcessEntries($agencyid,$ratekeys,$invoice_id){
		$api = Yii::app()->params['publicapi'];
		$ratekeys = json_encode($ratekeys);
		$save = $this->SaveEntries($agencyid,$ratekeys,$invoice_id);
		// $cmd = "curl -X POST -F 'ratechange=true' -F 'agencyid=$agencyid' -F 'invoice_id=$invoice_id' -F 'ratekeys=$ratekeys'  beta.reelforge.com/apis/ratechange.php";
		// $runner = exec($cmd);
		// return $runner;
	}

	public function AddInvoice($brandid,$agencyid,$startdate,$enddate,$pdf_file,$invoice_id){
		$api = Yii::app()->params['publicapi'];
		$call = "$api?rateinvoice=true&agencyid=$agencyid&brandid=$brandid&startdate=$startdate&enddate=$enddate&pdf_file=$pdf_file&invoice_id=$invoice_id";
		$attempt = file_get_contents($call);
		return $attempt;
	}

	public function LogInvoice($agencyid){
		$api = Yii::app()->params['publicapi'];
		$call = "$api?loginvoice=true&agencyid=$agencyid";
		$attempt = file_get_contents($call);
		return $attempt;
	}

	public function UpdateInvoice($last_update,$update_number,$pdf_file,$invoice_id){
		$api = Yii::app()->params['publicapi'];
		// $attempt = $this->UpdateEntries($last_update,$update_number,$pdf_file,$invoice_id);
		// $call = "$api?updateinvoice=true&invoice_id=$invoice_id&update_number=$update_number&pdf_file=$pdf_file&last_update=$last_update";
		// $attempt = file_get_contents($call);
		// return $attempt;

		$post['last_update'] = $last_update;
    	$post['update_number'] = $update_number;
    	$post['pdf_file'] = $pdf_file;
    	$post['invoice_id'] = $invoice_id;

    	$url = Yii::app()->params->anvil_api . "update_invoice";
		$data = urldecode(http_build_query($post));
		$output = json_decode(Yii::app()->curl->post($url, $data), true);

		return $output;
	}

	public function UpdateReconciliation($inv_rate,$auto_id,$inv_id){
		$api = Yii::app()->params['publicapi'];
		// $attempt = $this->UpdateReconciliationEntries($inv_id,$inv_rate,$auto_id);
		// $call = "$api?updatereconciliation=true&inv_rate=$inv_rate&auto_id=$auto_id&inv_id=$inv_id";
		// $attempt = file_get_contents($call);
		// return $attempt;

		$post['inv_rate'] = $inv_rate;
    	$post['auto_id'] = $auto_id;
    	$post['inv_id'] = $inv_id;

    	$url = Yii::app()->params->anvil_api . "update_reconciler";
		$data = urldecode(http_build_query($post));
		$output = json_decode(Yii::app()->curl->post($url, $data), true);

		return $output;
	}

	public function SaveEntries($agencyid,$ratekeys,$invoice_id){
    	$rateitems = json_decode($ratekeys,true);
    	// $invoiceitems = $this->AddInvoiceItems($agencyid,$rateitems,$invoice_id);
    	// $invoicerecon = $this->AddInvoiceReconciler($rateitems,$invoice_id);

    	$post['agencyid'] = $agencyid;
    	$post['rateitems'] = $rateitems;
    	$post['invoice_id'] = $invoice_id;

    	$url = Yii::app()->params->anvil_api . "saveinvoice_reconciler";
		$data = urldecode(http_build_query($post));
		$output = json_decode(Yii::app()->curl->post($url, $data), true);	

    }

    // public function UpdateEntries($agencyid,$ratekeys,$invoice_id){
    // 	$rateitems = json_decode($ratekeys,true);
    // 	$invoiceitems = $this->AddInvoiceItems($agencyid,$rateitems,$invoice_id);
    // 	$invoicerecon = $this->AddInvoiceReconciler($rateitems,$invoice_id);
    // }

    public function UpdateEntries($last_update,$update_number,$pdf_file,$invoice_id){
    	$sql = "UPDATE invoice set last_update='$last_update',update_number='$update_number', pdf_file='$pdf_file' where invoice_id='$invoice_id'";
    	$insertsql = Yii::app()->db3->createCommand($sql)->execute();
		if($insertsql){
			return 'Updated';
		}else{
			return 'Failed';
		}
    }

    public function UpdateReconciliationEntries($inv_id,$inv_rate,$auto_id){
    	// $conn = $this->dbconnect;
    	$sql_update="UPDATE invoice_reconciler set inv_rate='$inv_rate' where auto_id=$auto_id AND inv_id=$inv_id";
		$insertsql = Yii::app()->db3->createCommand($sql_update)->execute();
		if($insertsql){
			return 'Updated';
		}else{
			return 'Failed';
		}
    }


    public function AddInvoiceItems($agencyid,$array,$invoice_id){
    	// Reversing The Previous Fix
    	$array['ad_name'] = str_replace('_', ' ',$array['ad_name']);
		$array['brand_name'] = str_replace('_', ' ',$array['brand_name']);
		$array['entry_type'] = str_replace('_', ' ',$array['entry_type']);
		$agency_id 	= $agencyid;
		$rcvalue 	= $array['rcvalue'];
		$startdate 	= $array['startdate'];
		$enddate 	= $array['enddate'];
		$day 		= $array['day'];
		$reportdate 	= $array['date'];
		$reporttime 	= $array['time'];
		$brandname 	= $array['brand_name'];
		$adname 		= $array['ad_name'];
		$entrytype 	= $array['entry_type'];
		$stationid 	= $array['station_id'];
		$tablename 	= $array['table_name'];
		$tableid 	= $array['table_id'];
		$duration 	= $array['duration'];
		$acvalue 	= $array['value'];
		$sql = "INSERT INTO invoice_items (agency_id,rcvalue,startdate,enddate,day,reportdate,reporttime,brandname,adname,entrytype,stationid,tablename,tableid,duration,acvalue) VALUES('$agency_id','$rcvalue','$startdate','$enddate','$day','$reportdate','$reporttime','$brandname','$adname','$entrytype','$stationid','$tablename','$tableid','$duration','$acvalue') ";
		$insertsql = Yii::app()->db3->createCommand($sql)->execute();
		if($insertsql){
			return true;
		}else{
			return false;
		}
	}

    public function AddInvoiceReconciler($array,$invoice_id){
    	// Reversing The Previous Fix
    	$array['ad_name'] = str_replace('_', ' ',$array['ad_name']);
		$array['brand_name'] = str_replace('_', ' ',$array['brand_name']);
		$array['entry_type'] = str_replace('_', ' ',$array['entry_type']);
		$ratecard_rate 	= $array['rcvalue'];
		$inv_date 		= $array['date'];
		$inv_time 		= $array['time'];
		$brand_name 	= $array['brand_name'];
		$ad_name 		= $array['ad_name'];
		$entry_type 	= $array['entry_type'];
		$station_id 	= $array['station_id'];
		$table_name 	= $array['table_name'];
		$table_id 		= $array['table_id'];
		$duration 		= $array['duration'];
		$inv_rate 		= $array['value'];
		$sql = "INSERT INTO invoice_reconciler (ratecard_rate,inv_date,inv_time,brand_name,ad_name,entry_type,station_id,table_name,table_id,duration,inv_rate,inv_id) VALUES ('$ratecard_rate','$inv_date','$inv_time','$brand_name','$ad_name','$entry_type','$station_id','$table_name','$table_id','$duration','$inv_rate','$invoice_id') ";
		$insertsql = Yii::app()->db3->createCommand($sql)->execute();
		if($insertsql){
			return true;
		}else{
			return false;
		}
    }

}