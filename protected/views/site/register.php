<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<h1>Login</h1>

<p>Please fill out the following form with your login credentials:</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Pola z <span class="required">*</span> są wymagane</p>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name'); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'retypepassword'); ?>
		<?php echo $form->passwordField($model,'retypepassword'); ?>
		<?php echo $form->error($model,'retypepassword'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>
        <div class="row">
		<?php echo $form->radioButtonList($model,'gender',array('m'=>'Male','f'=>'Female')); ?>
		<?php echo $form->error($model,'gender'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Rejestruj'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
