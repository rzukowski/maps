<?php 
if($model->owner->userid != Yii::app()->user->getId()){
    $this->renderPartial('_view', array('model'=>$model,'users'=>$users));

}
else{
    $this->renderPartial('update', array('model'=>$model,'users'=>$users));
}
?>