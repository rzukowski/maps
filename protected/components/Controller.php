<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/layout';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
        
        public function beforeAction($action) {
    if( parent::beforeAction($action) ) {
       $model=new Event;
       $labels = $model->attributeLabels();
       $jsonLabels = json_encode($labels,JSON_UNESCAPED_UNICODE);
       $jsonLabels = addslashes($jsonLabels);
                $max = $model->rules();
                $max = $max[5]["max"];

            $categories = Categories::model()->findAllBySql('select * from categories order by description ASC');
        $categoriesArr = array_map(function($x){ return $x->getAttributes(array('categoryId','description'));},$categories);
            $jsonCat = json_encode($categoriesArr);
            $jsonCat = addslashes($jsonCat);
            Yii::app()->clientScript->registerScript('categories',"var categories=\"".$jsonCat."\";", CClientScript::POS_HEAD);
            Yii::app()->clientScript->registerScript('maxDescr',"var maxDescr=\"".$max."\";", CClientScript::POS_HEAD);
            Yii::app()->clientScript->registerScript('polishLabels',"var polishLabels=\"".$jsonLabels."\";", CClientScript::POS_HEAD);
        return true;
    }
    return false;
}
        
}