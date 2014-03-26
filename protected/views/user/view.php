

<h1>View User #<?php echo $model->userid; ?></h1>

<p>Wydarzenia:</p>
<?php 
foreach($events as $event){
    echo CHtml::link(CHtml::encode($event->name), array('event/view', 'id'=>$event->eventId));

    
}

?>
