// $model = new Story('search');
// 			$model->unsetAttributes();
// 			if(isset($_POST['Story']))
// 			{
// 				$model->attributes=Yii::app()->input->stripClean($_POST['Story']);
// 			}
// 			$model->Client_ID = Yii::app()->user->company_id;

// $this->widget('bootstrap.widgets.TbGridView', array(
//     'id'=>'patients',
//     'type'=>'striped condensed',
//     'dataProvider'=>$model->search(),
//     'filter'=>$model,
//     'filterPosition'=>'none',
//     'template'=>"{items}\n{pager}",
//     'selectableRows'=>10,
//     'emptyText'=>'No Data Exists',
//     'columns'=>array(
//         // array('header'=>'#','value'=>'$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)','htmlOptions'=>array('width'=>'10%')),
//         array('name' =>'StoryDate' ,'header'=>'Date'),
//         array('name'=>'Publication','header'=>'Publication'),
//         array('name'=>'journalist','header'=>'Journalist'),
//         array('name'=>'Title','header'=>'Headline/Subject'),
//         array('name' => 'StoryPage', 'header' => 'StoryPage', 'type' => 'raw', 'value' =>'CHtml::link("New Case",Yii::app()->createUrl("case/new",array("id"=>$data->id)), array("class"=>"btn btn-primary btn-small"))'),
//         array('name'=>'PublicationType','header'=>'Publication Type'),
//         array('name'=>'picture','header'=>'Picture'),
//         array('name'=>'Tonality','header'=>'Effect'),
//     ),
// ));
