<?php
/**
* Summary Processed File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version     v.1.0
* @since       July 2008
*/
$this->pageTitle=Yii::app()->name.' | Industry Summary Reports';
$this->breadcrumbs=array('Industry Summary Reports'=>array('misc/industrysummary'));
$enddate = $_POST['enddate'];
$startdate = $_POST['startdate'];

// $year_start = $_POST['year'];
// $month = $_POST['month'];
$industry = $_POST['industry'];
if(isset($_POST['country'])){
    $country = $_POST['country'];
}else{
    $country = Yii::app()->params['country_id'];
}

echo '<div class="row-fluid clearfix"><div class="col-md-12">';



if(isset($industry) && !empty($industry) && $industry!=''){
    if($industry=='all'){
        $company = $_POST['competitor_company'];
        $sql_industry="SELECT industry.industry_name, industry.industry_id  from industry ,  industryreport where
        industry.industry_id =industryreport.industry_id and
        industryreport.company_id='$company'
        order by industry_name asc";
        // Select All Industries that the company is subscribed to
        if($subs = Yii::app()->db3->createCommand($sql_industry)->queryAll()){
            $subsarray = array();
            foreach ($subs as $subskey) { $subsarray[] = $subskey['industry_id']; }
            $splitsubs = implode(', ', $subsarray);
            // Select All The Sub Industries which fall under the companies' industries
            $sql_sub_industry="SELECT auto_id FROM  sub_industry where industry_id IN ($splitsubs)  ORDER BY sub_industry_name asc";
            $sub_ids = Yii::app()->db3->createCommand($sql_sub_industry)->queryAll();
            $sub_industries = $sub_ids;
            $subids_array = array();
            foreach ($sub_ids as $idsskey) { $subids_array[] = $idsskey['auto_id']; }
            $set_subs = implode(', ', $subids_array);
            $brandarray = array();
            $sql_brands="SELECT * FROM  brand_table WHERE sub_industry_id IN ($set_subs) ORDER BY brand_name ASC";
            if($brands = BrandTable::model()->findAllBySql($sql_brands)){
                foreach ($brands as $key) { $brandarray[] = $key->brand_id; }
                $set_brands = implode(', ', $brandarray);
                $brand_query = "brand_id IN ($set_brands)";
                echo $records = IndustrySummaries::GetRecords($startdate,$enddate,$industry,$sub_industries,$brand_query,$country);
            }else{
                echo "No Brands Found, Please Try Later!";
            }
        }
    }elseif ($industry!='all' && isset($_POST['sub_industry_name'])) {
        $sub_industries = $_POST['sub_industry_name'];
        $set_subs = implode(', ', $_POST['sub_industry_name']);
        $brandarray = array();
        $sql_brands="SELECT * FROM  brand_table WHERE sub_industry_id IN ($set_subs) ORDER BY brand_name ASC";
        if($brands = BrandTable::model()->findAllBySql($sql_brands)){
            foreach ($brands as $key) { $brandarray[] = $key->brand_id; }
            $set_brands = implode(', ', $brandarray);
            $brand_query = "brand_id IN ($set_brands)";
            // echo $brand_query;
            echo $records = IndustrySummaries::GetRecords($startdate,$enddate,$industry,$sub_industries,$brand_query,$country);
        }else{
            echo "No Brands Found, Please Try Later!";
        }
    }else{
        echo "You Need to Select Industies and/or Sub Industries for this Query to Work";
    }
}else{
    echo "No Industry Selected at All";
}




echo '</div></div>';

?>