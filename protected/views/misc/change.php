<?php
/**
* Changes File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version     v.1.0
* @since       July 2008
*/
$level = Yii::app()->user->company_level;
$company_name = Yii::app()->user->company_name;
$coid=$this_company_id = Yii::app()->user->company_id;
$this->pageTitle=Yii::app()->name.' | Rate Change';
$this->breadcrumbs=array('Rate Change'=>array('misc/ratechange'));
$agency_id=Yii::app()->user->company_id;

echo '<div class="row-fluid clearfix">';
echo CHtml::link("New Rate Card Change",Yii::app()->createUrl("misc/newrates"), array("class"=>"btn btn-warning btn-sm"));
echo '<br>';
echo '</div>';

$dataProvider=new CActiveDataProvider('Invoice', array(
'criteria'=>array(
'condition'=>'agency_id=:a',
'params'=>array(':a'=>$agency_id,),
'order'=>'inv_date_created DESC',
),
'pagination'=>array('pageSize'=>50,),
));
echo '<br>';
 $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array('id'=>'invoices-form','type'=>'horizontal','enableClientValidation'=>true,'clientOptions'=>array('validateOnSubmit'=>true),'htmlOptions' => array('enctype' => 'multipart/form-data'),));
//Grid Function
$this->widget('bootstrap.widgets.TbGridView', array(
    'id'=>'dt_basic',
    'type'=>'condensed bordered hover',
    'dataProvider'=>$dataProvider,
    'template'=>"{items}\n{pager}",
    'selectableRows'=>50,
    'emptyText'=>'No Data Exists',
    'pager' => array('htmlOptions'=>array('class'=>'pagination')),
    'columns'=>array(
        array('header'=>'#','value'=>'$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)','htmlOptions'=>array('width'=>'4%')),
        array('name' =>'File' ,'header'=>'File','htmlOptions'=>array('width'=>'3%')),
        array('name' =>'Brand' ,'header'=>'Brand Name','htmlOptions'=>array('width'=>'50%')),
        array('name' =>'inv_date_created' ,'header'=>'Date Generated','htmlOptions'=>array('width'=>'15%')),
        array('name'=>'Datespan','header'=>'Period','htmlOptions'=>array('width'=>'18%')),
        array('name' => 'Edit', 'header' => 'Edit', 'type' => 'raw', 'value' =>'CHtml::link("Edit",Yii::app()->createUrl("misc/rateupdate",array("id"=>$data->invoice_id)), array("class"=>"btn btn-primary btn-xs"))','htmlOptions'=>array('width'=>'5%')),
        array('name' => 'Delete', 'header' => 'Delete', 'type' => 'raw', 'value' =>'CHtml::link("Delete",Yii::app()->createUrl("misc/ratedelete",array("id"=>$data->invoice_id)), array("class"=>"btn btn-danger btn-xs"))','htmlOptions'=>array('width'=>'5%')),
    ),
));


?>
<?php $this->endWidget(); ?>
<script>
$('table').removeClass('items');
</script>
<style type="text/css">
.table .btn{
	color: #fff;
}
.pagination .selected {
    border: none !important;
}
.selected:after{
    border-top: none !important;
    content: " " !important;
}
.pagination .pagination{
	float:right !important;
	margin-top: -5px !important;
	padding-left: 10px !important;
}
</style>