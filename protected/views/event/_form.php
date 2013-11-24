<?php
/* @var $this EventController */
/* @var $model Event */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'event-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'eventId'); ?>
		<?php echo $form->textField($model,'eventId',array('size'=>38,'maxlength'=>38)); ?>
		<?php echo $form->error($model,'eventId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ownerId'); ?>
		<?php echo $form->textField($model,'ownerId',array('size'=>38,'maxlength'=>38)); ?>
		<?php echo $form->error($model,'ownerId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'descr'); ?>
		<?php echo $form->textField($model,'descr',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'descr'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'country'); ?>
		<?php echo $form->textField($model,'country',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'country'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'state'); ?>
		<?php echo $form->textField($model,'state',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'state'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'city'); ?>
		<?php echo $form->textField($model,'city',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'city'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'road'); ?>
		<?php echo $form->textField($model,'road',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'road'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'road_num'); ?>
		<?php echo $form->textField($model,'road_num'); ?>
		<?php echo $form->error($model,'road_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lat'); ?>
		<?php echo $form->textField($model,'lat',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'lat'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lon'); ?>
		<?php echo $form->textField($model,'lon',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'lon'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'limits'); ?>
		<?php echo $form->textField($model,'limits'); ?>
		<?php echo $form->error($model,'limits'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->