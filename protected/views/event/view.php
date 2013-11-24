<?php
/* @var $this EventController */
/* @var $model Event */

$this->breadcrumbs=array(
	'Events'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Event', 'url'=>array('index')),
	array('label'=>'Create Event', 'url'=>array('create')),
	array('label'=>'Update Event', 'url'=>array('update', 'id'=>$model->eventId)),
	array('label'=>'Delete Event', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->eventId),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Event', 'url'=>array('admin')),
);
?>

<h1>View Event #<?php echo $model->eventId; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'eventId',
		'ownerId',
		'name',
		'descr',
		'date',
		'country',
		'state',
		'city',
		'road',
		'road_num',
		'lat',
		'lon',
		'limits',
	),
)); ?>
