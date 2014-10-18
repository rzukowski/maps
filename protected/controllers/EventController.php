<?php

class EventController extends Controller
{
	/**
	 * @var string the default layout for the views. polishLabelsDefaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('admin','create','index','view','getDetails','getFullEvent','saveto'),
				'users'=>array('@'),
			),
                   array('allow',
                        'actions'=>array('update'),
                        'expression' => array('EventController','allowOnlyOwner')),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
 public function allowOnlyOwner(){
       
            $event = Event::model()->findByPk($_POST["Event"]["id"]);
            $bool = ($event->ownerId === Yii::app()->user->getId());
            return $bool;
        
    }
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
            $events = $this->loadModel($id);
            $owner = $events->owner;
            $latLonArr = new ArrayObject();
            $latLonArr= array('lat'=>$events->lat,'lon'=>$events->lon);
            $jsonLatLon = json_encode($latLonArr);
            $jsonLatLon = addslashes($jsonLatLon);
            
            Yii::app()->clientScript->registerScript('jsonLatLon',"var jsonLatLon=\"".$jsonLatLon."\";", CClientScript::POS_HEAD);
  
            $users = User::model()->with(
                    array('events'=>array(
                        'select'=>false,
                        'condition'=>'events_events.eventId=:eventId',
                        'params'=>array(':eventId'=>$id)
    
                    ))
                    )->findAll();
 
            
            $usersArr = CHtml::listData( $users, 'userid' , 'name');
		$this->render('view',array(
			'model'=>$events,
                        'users'=>$users,
                        'usersArr'=>$usersArr
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
            
            
            
            $model=new Event;
                $max = $model->rules();
                $max = $max[5]["max"];

            $categories = Categories::model()->findAllBySql('select * from categories order by description ASC');
        $categoriesArr = array_map(function($x){ return $x->getAttributes(array('categoryId','description'));},$categories);
            $jsonCat = json_encode($categoriesArr);
            $jsonCat = addslashes($jsonCat);
            Yii::app()->clientScript->registerScript('categories',"var categories=\"".$jsonCat."\";", CClientScript::POS_HEAD);
            //Yii::app()->clientScript->registerScript('maxDescr',"var maxDescr=\"".$max."\";", CClientScript::POS_HEAD);
            
            Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/scripts/uiblock.js', CClientScript::POS_HEAD);
           Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/eventView.css');
            
            
       
            Yii::app()->clientScript->registerScript('url',"var url=\"".Yii::app()->createUrl('event/create')."\";", CClientScript::POS_HEAD);
            Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/protected/views/event/jsDatePick.min.1.3.js', CClientScript::POS_HEAD);
            
            
             if(isset($_POST['Event']))
		{
                   
			$model->attributes=$_POST['Event'];
                        $userid = Yii::app()->user->getId();
                    $uid=Yii::app()->db->createCommand("SELECT UUID()")->queryRow();
                    $model->ownerId=$userid;
                    $model->eventId=$uid["UUID()"];
                        if($model->validate()){
                            
                            if($model->save())
				 echo "Wydarzenie ".$model->name ." pomyślnie zostało zapisane";
                            
                        }
                        else {
                        $errors = $model->getErrors();
                    $arr = array_map(function($el){ return $el[0]; }, $errors);
                    //$impl = implode(",",$arr);
                    $impl = json_encode($arr);
                    echo $impl; 
                        }
			Yii::app()->end();
		}

		// Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($model);

		if(isset($_POST['Event']))
		{
			$model->attributes=$_POST['Event'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->eventId));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		

		if(isset($_POST['Event']))
		{
                    $model = $this->loadModel($id);
                    unset($_POST["Event"]['ownerId']);
                    unset($_POST["Event"]['id']);
                    
			$model->attributes=$_POST['Event'];
                        if($model->validate())
                            {
                            
                            if($model->save())
                            {
                                
                                $latLonArr= array('lat'=>$model->lat,'lon'=>$model->lon,'mssg'=>"Wydarzenie ".$model->name ." pomyślnie zostało zapisane");
                                $jsonLatLon = json_encode($latLonArr,JSON_UNESCAPED_UNICODE);
                                //$jsonLatLon = addslashes($jsonLatLon);
                               
				echo $jsonLatLon;
                                 Yii::app()->end();
                            }
                            else
                            {
                                echo "Error";
                                 Yii::app()->end();
                            }
                        }
                        else {
                        $errors = $model->getErrors();    
                        }
			
		}
                $jsonArr = json_encode($errors,JSON_UNESCAPED_UNICODE);
               echo $jsonArr;
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists ONLY current user events.
	 */
	public function actionIndex()
	{
        $userid=Yii::app()->user->getId();
        $this->redirect(array('user/view','id'=>$userid));
           
            
            $userControl->actionView($userid);
              $events = Event::model()->findAll(array(
                        'select'=>array('name','eventId'),
                        'condition'=>'ownerId=:userId',
                        'params'=>array(':userId'=>$userid)
    
                  
              ));

		$this->render('index',array(
			'events'=>$events,
		));
	}
        
        public function actionGetDetails(){
        
           
            if(isset($_POST['Event'])){
                
                $userid=Yii::app()->user->getId();
                $page = (isset($_POST['page']) ? $_POST['page'] : 1);
                $model=new Event('search');
		$model->unsetAttributes(); 
                        $firstDate=$_POST['firstDate'];
                        $secondDate=$_POST['secondDate'];
			$model->attributes=$_POST['Event'];
                        $country = $model->country;
                        $limits = $model->limits;
                        $descr = $model->descr;
                        $city = $model->city;
                        $name = $model->name;
                        $state=$model->state;
                        $road=$model->road;
                        $county=$model->county;
                        $village=$model->village;
                        $category = $model->categoryId;
                        
                        $validated = CActiveForm::validate($model);
                         $val = $model->validate();
                        
                        $maxLat = $_POST['maxLat'];
                        $minLat = $_POST['minLat'];
                        $maxLon = $_POST['maxLon'];
                        $minLon = $_POST['minLon'];
                        
                        //setting to null
                        $firstDate=($firstDate=="")?null:$firstDate;
                        $secondDate=($secondDate=="")?null:$secondDate;
                        $village = ($village =="")?"":$village;
                        $county= ($county=="")?null:$county;
                        $city = ($city=="")?null:$city;
                        $descr = ($descr=="")?null:$descr;
                        
                        
                        //$listPerPage = Yii::app()->params['listPerPage'];
                        $listPerPage=2;
                        
                      
                        $count = Event::CountEvents($country,$userid,$descr,$city,$name,$state,$road,$village, $county, $limits, $firstDate, $secondDate, $minLat, $maxLat, $minLon, $maxLon, $category);
                        
                        $toSkipp=($page-1)*$listPerPage;
                        
                        
                        
                        $getEvents = Event::SearchEvents($country,$userid,$descr,$city,$name,$state,$road,$village, $county, $limits, $firstDate, $secondDate, $minLat, $maxLat, $minLon, $maxLon, $category, $listPerPage, $toSkipp);
                        
                        $pagination = new Paginator();
                        $pagination->current_page=$page;
                        //$pagination->items_per_page=$listPerPage;
                        $pagination->items_per_page=$listPerPage;
                        $pagination->default_function_name="send";
                        $count=$count[0];
                        $pagination->items_total=$count["count(*)"];
                        $pagination->paginate();
                        $paginationString = $pagination->display_pages();
                        $arr = array($paginationString);
                     
                        array_push($arr,$getEvents);
                        $impl = json_encode($arr,JSON_UNESCAPED_UNICODE);
                        echo $impl;
                       Yii::app()->end();
                        
            
                
                }
             
            
        }
        
        public function actionGetFullEvent(){
            
            if(isset($_POST["eventId"])){
                
                $eventId = $_POST["eventId"];
                
                $getEvents = Event::GetEventById($eventId);
                $model=new Event();
                
                //replace key names using labels
                $labels = $model->attributeLabels();
                $getEvents = $getEvents[0];
                foreach($getEvents as $key => $value){
                    if(isset($labels[$key])){
                        $getEvents[$labels[$key]] = $getEvents[$key];
                        unset($getEvents[$key]);
                    }
                }
                
                $impl = json_encode($getEvents,JSON_UNESCAPED_UNICODE);
                        echo $impl;
                       Yii::app()->end();
                
            }
            
            
            
        }
        
        public function actionsaveto(){
            
            if(isset($_POST["eventId"])){
                $eventId=$_POST["eventId"];
                $userid = Yii::app()->user->getId();
                $sql = "SELECT Count(*) FROM event_participants where eventId = :eventId;"
                        . "INSERT INTO event_participants VALUES(:ownerId,:eventId)";
                $parameters = array(":eventId"=>$eventId,":ownerId"=>$userid);
                try {
                    $getEvents = Yii::app()->db->createCommand($sql)->execute($parameters);
                } catch (CDbException $e) {
                    
                    echo "Błąd przy zapisywaniu";
                    Yii::app()->end();
                }
                
                echo 'Zostałeś zapisany';
                Yii::app()->end();
            }
            echo "Błąd przy zapisywaniu";
            
        }
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
            
            $categories = Categories::model()->findAllBySql('select * from categories order by description ASC');
        $categoriesArr = array_map(function($x){ return $x->getAttributes(array('categoryId','description'));},$categories);
        
        
            Yii::app()->clientScript->registerScript('url',"var url=\"".Yii::app()->createUrl('event/saveto')."\";", CClientScript::POS_HEAD);
            Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/protected/views/event/OpenLayers-2.13.1/OpenLayers.js', CClientScript::POS_HEAD);
            Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/scripts/uiblock.js', CClientScript::POS_HEAD);
           // Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/eventView.css');
            
            Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/protected/views/event/json2.js', CClientScript::POS_HEAD);
            
		$model=new Event('search');
                $categories = new Categories();
		$model->unsetAttributes();  // clear any default values
		
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Event the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Event::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Event $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='event-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

 
class Paginator{
    var $items_per_page;
    var $items_total;
    var $current_page;
    var $num_pages;
    var $mid_range;
    var $low;
    var $high;
    var $limit;
    var $return;
    var $default_ipp = 2;
    var $default_function_name="Send";
    function Paginator()
    {
        $this->current_page = 1;
        $this->mid_range = 7;
        $this->items_per_page = $this->default_ipp;
    }
 
    function paginate()
    {
        if($this->default_function_name==null) $this->default_function_name=$default_function_name;
        if(!is_numeric($this->items_per_page) OR $this->items_per_page <= 0) $this->items_per_page = $this->default_ipp;
        $this->num_pages = ceil($this->items_total/$this->items_per_page);
        if($this->current_page < 1 Or !is_numeric($this->current_page)) $this->current_page = 1;
        if($this->current_page > $this->num_pages) $this->current_page = $this->num_pages;
        $prev_page = $this->current_page-1;
        $next_page = $this->current_page+1;
 
        if($this->num_pages > 10)
        {
            $this->return = ($this->current_page != 1 And $this->items_total >= 10) ? "<a class=\"paginate\" onclick=\"".$this->default_function_name."(".$prev_page.")\" >« Previous</a> ":"<span class=\"inactive\" href=\"#\">« Previous</span> ";
 
            $this->start_range = $this->current_page - floor($this->mid_range/2);
            $this->end_range = $this->current_page + floor($this->mid_range/2);
 
            if($this->start_range <= 0)
            {
                $this->end_range += abs($this->start_range)+1;
                $this->start_range = 1;
            }
            if($this->end_range > $this->num_pages)
            {
                $this->start_range -= $this->end_range-$this->num_pages;
                $this->end_range = $this->num_pages;
            }
            $this->range = range($this->start_range,$this->end_range);
 
            for($i=1;$i<=$this->num_pages;$i++)
            {
                if($this->range[0] > 2 And $i == $this->range[0]) $this->return .= " ... ";
                // loop through all pages. if first, last, or in range, display
                if($i==1 Or $i==$this->num_pages Or in_array($i,$this->range))
                {
                    $this->return .= ($i == $this->current_page) ? "<a  class=\"current\" onclick=\"".$this->default_function_name."(" . $i . ")\">". $i . "</a> ":"<a class=\"paginate\"  onclick=\"Send(" . $i . ")\">" . $i . "</a> ";
                }
                if($this->range[$this->mid_range-1] < $this->num_pages-1 And $i == $this->range[$this->mid_range-1]) $this->return .= " ... ";
            }
            $this->return .= ($this->current_page != $this->num_pages And $this->items_total >= 10)  ? "<a class=\"paginate\" href=\"".$this->default_function_name."("+$next_page+")\">Next »</a>\n":"<span class=\"inactive\" href=\"#\">» Next</span>\n";
           
        }
        else
        {
            for($i=1;$i<=$this->num_pages;$i++)
            {
                $this->return .= ($i == $this->current_page) ? "<a class=\"current\" href=\"#\">" . $i . "</a> ":"<a class=\"paginate\" onclick=\"".$this->default_function_name."(" .$i . ")\">" . $i . "</a> ";
            }
          
        }
        $this->low = ($this->current_page-1) * $this->items_per_page;
        $this->high = ($this->current_page * $this->items_per_page)-1;
        $this->limit = " LIMIT $this->low,$this->items_per_page";
    }

 
    function display_jump_menu()
    {
        for($i=1;$i<=$this->num_pages;$i++)
        {
            $option .= ($i==$this->current_page) ? "<option value=\"$i\" selected>$i</option>\n":"<option value=\"$i\">$i</option>\n";
        }
        return "<span class=\"paginate\">Page:</span><select class=\"paginate\" onchange=\"window.location='$_SERVER[PHP_SELF]?page='+this[this.selectedIndex].value+'&ipp=$this->items_per_page';return false\">$option</select>\n";
    }
 
    function display_pages()
    {
        return $this->return;
    }
}

class Position{
    var $lat;
    var $lon;
    public function __construct($Lat,$Lon) {
        $this->lat = $Lat;
        $this->lon = $Lon;
        
    }
    
    
    
}