<?php
/* @var $this EventController */
/* @var $model Event */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'eventId'); ?>
		<?php echo $form->textField($model,'eventId',array('size'=>38,'maxlength'=>38)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ownerId'); ?>
		<?php echo $form->textField($model,'ownerId',array('size'=>38,'maxlength'=>38)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'descr'); ?>
		<?php echo $form->textField($model,'descr',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'country'); ?>
		<?php echo $form->textField($model,'country',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'state'); ?>
		<?php echo $form->textField($model,'state',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'city'); ?>
		<?php echo $form->textField($model,'city',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'road'); ?>
		<?php echo $form->textField($model,'road',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'road_num'); ?>
		<?php echo $form->textField($model,'road_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lat'); ?>
		<?php echo $form->textField($model,'lat',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lon'); ?>
		<?php echo $form->textField($model,'lon',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'limits'); ?>
		<?php echo $form->textField($model,'limits'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->