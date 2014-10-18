

<h1>View User <?php echo $model->name; ?></h1>

<p>Wydarzenia, w których bierze udział:</p>
<?php 
foreach($events as $event){
    echo CHtml::link(CHtml::encode($event->name), array('event/view', 'id'=>$event->eventId));

    
}

?>
<p>Wydarzenia stworzone przez usera</p>
<?php 
foreach($ownedEvents as $event){
    echo "<p>";
    echo CHtml::link(CHtml::encode($event->name), array('event/view', 'id'=>$event->eventId));
    echo "</p>";
    
}

?>