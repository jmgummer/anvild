<?php
/**
* POP PDF File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version     v.1.0
* @since       July 2008
*/
$anvil_header = Yii::app()->request->baseUrl . '/images/anvil_header.jpg';
echo '<img src="'.$anvil_header.'" width="100%" />';
$grand_total = 0;
foreach ($brands as $found_brands) {
    $fbrand_id = $found_brands['brand_id'];
    $fbrand_name = $found_brands['brand'];
    echo '<h2>'.$fbrand_name.'</h2>';
    $temp_select = 'SELECT * FROM '.$temp_table.' WHERE brand_id='.$fbrand_id.' order by this_date';
    
    if($data = Yii::app()->db3->createCommand($temp_select)->queryAll())
    {
        if($fbrand_id==$active_tab){
            echo '<div class="tab-pane fade active in" id="'.$fbrand_id.'">';
        }else{
            echo '<div class="tab-pane fade" id="'.$fbrand_id.'">';
        }
        echo '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
        echo '<tr><td>Ad Name</td><td>Date</td><td>Newspaper</td><td>Page</td><td>Rate(Kshs)</td></tr>';
        $sum = 0;
        foreach ($data as $result) {
            echo '<tr>';
            echo '<td>'.$result['brand'].'</td>';
            echo '<td>'.$result['this_date'].'</td>';
            echo '<td>'.$result['Media_House_List'].'</td>';
            echo '<td>'.$result['page'].'</td>';
            echo '<td>'.number_format($result['ave']).'</td>';
            echo '</tr>';
            $sum = $sum + $result['ave'];
        }
        echo '</table>';
        echo '<div class="row-fluid clearfix">';
        
        $total = count($data);
        echo '<p class="pull-left"><strong>Brand TOTAL ('.$fbrand_name.') | Total Number of Ads '.$total.'</strong></p>';
        echo '<p class="pull-right"><strong>'.number_format($sum).'</strong></p>';
        echo '</div>';
        echo '</div>';
        $grand_total = $grand_total + $sum;
    }

}
echo '<div class="row-fluid clearfix"><hr class="simple"></hr><p class="pull-left"><strong>Grand TOTAL : '.number_format($grand_total).'</strong></p></div>';
echo '</div>';
echo '</div>';
?>