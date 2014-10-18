<?php
/* @var $this EventController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Events',
);

$this->menu=array(
	array('label'=>'Create Event', 'url'=>array('create')),
	array('label'=>'Manage Event', 'url'=>array('admin')),
);
?>

<h1>Events</h1>

<p>Wydarzenia stworzone przez Ciebie:</p>
<?php 
foreach($ownedEvents as $event){
    echo "<p>";
    echo CHtml::link(CHtml::encode($event->name), array('event/view', 'id'=>$event->eventId));
    echo "</p>";
    
}

?>