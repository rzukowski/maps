<?php

class EventController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
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
				'actions'=>array('admin','create','update','index','view','getDetails','getFullEvent','saveto'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('delete'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
            $events = $this->loadModel($id);
            $latLonArr = new ArrayObject();
            $latLonArr= array('lat'=>$events->lat,'lon'=>$events->lon);
            $jsonLatLon = json_encode($latLonArr);
            $jsonLatLon = addslashes($jsonLatLon);
            
            Yii::app()->clientScript->registerScript('categories',"var jsonLatLon=\"".$jsonLatLon."\";", CClientScript::POS_HEAD);
              /*
            $users= Event::model()->with(
                    array('participants'=>array(
 
                        'condition'=>'t.eventId =:eventId',
                        'params'=>array(':eventId'=>$id))
                    )
                        )->findAll();
                        */
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
            Yii::app()->clientScript->registerScript('maxDescr',"var maxDescr=\"".$max."\";", CClientScript::POS_HEAD);
            Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/protected/views/event/OpenLayers-2.13.1/OpenLayers.js', CClientScript::POS_HEAD);
            Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/scripts/uiblock.js', CClientScript::POS_HEAD);
           // Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/eventView.css');
            
            
            Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/protected/views/event/eventsajax.js', CClientScript::POS_HEAD);
            Yii::app()->clientScript->registerScript('url',"var url=\"".Yii::app()->createUrl('event/create')."\";", CClientScript::POS_HEAD);
            Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/protected/views/event/jsDatePick.min.1.3.js', CClientScript::POS_HEAD);
            
		
                
                if(isset($_POST['name'])) {
                $sql="SELECT UUID()";
                $uid=Yii::app()->db->createCommand($sql)->queryRow();
                $model->eventId=$uid["UUID()"];
                $model->name = $_POST['name'];
                $model->city = $_POST['city'];
                $model->country=$_POST['country'];
                $model->descr=$_POST['descr'];
                $model->lat=$_POST['lat'];
                $model->lon=$_POST['lon'];
                $model->limits=$_POST['limits'];
                $model->date=$_POST['date'];
                $model->categoryId=intval($_POST['categoryId']);
                $model->village=$_POST['village'];
                $model->county=$_POST['county'];
                $model->state=$_POST['state'];
                $model->road = $_POST['road'];
                $saved = $model->save();    
                if($saved){
                    echo "Wydarzenie ".$model->name ." pomyślnie zostało zapisane";
                
                }
                else{
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
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Event']))
		{
			$model->attributes=$_POST['Event'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->eventId));
		}

		$this->render('update',array(
			'model'=>$model,
		));
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
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Event');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
        
        public function actionGetDetails(){
        
           
            if(isset($_POST['Event'])){
                
                $model=new Event('search');
		$model->unsetAttributes(); 
			$model->attributes=$_POST['Event'];
                        CActiveForm::validate($model);
                        $country = $model->country;
                        $limits = $model->limits;
                        $descr = $model->descr;
                        $city = $model->city;
                        $name = $model->name;
                        $state=$model->state;
                        $road=$model->road;
                        $county=$model->county;
                        $village=$model->village;
                        $firstDate=$_POST['firstDate'];
                        $secondDate=$_POST['secondDate'];
                        $maxLat = $_POST['maxLat'];
                        $minLat = $_POST['minLat'];
                        $maxLon = $_POST['maxLon'];
                        $minLon = $_POST['minLon'];
                        $firstDate=($firstDate=="")?null:$firstDate;
                        $secondDate=($secondDate=="")?null:$secondDate;
                        $category = $model->categoryId;
                        $getEvents = Yii::app()->db->createCommand()
                        ->select('lat, lon, eventId')
                        ->from('event')
                        ->where('country like CONCAT("%",COALESCE(IFNULL(:country,null),"%"),"%")'
                        .'AND descr like CONCAT("%",COALESCE(IFNULL(:descr,null),"%"),"%")'
                        .'AND city like CONCAT("%",COALESCE(IFNULL(:city,null),"%"),"%")'
                        .'AND name like CONCAT("%",COALESCE(IFNULL(:name,null),"%"),"%")'
                        .'AND state like CONCAT("%",COALESCE(IFNULL(:state,null),"%"),"%")'
                        .'AND road like CONCAT("%",COALESCE(IFNULL(:road,null),"%"),"%")'
                        .'AND village like CONCAT("%",COALESCE(IFNULL(:village,null),"%"),"%")'
                        .'AND (:county IS NULL OR county like CONCAT("%",COALESCE(IFNULL(:county,null),"%"),"%"))'
                        .'AND ((:firstDate IS NULL OR :secondDate IS NULL) OR (TIMEDIFF(:firstDate,date)<=0 AND TIMEDIFF(:secondDate,date)>0))'
                         .'AND (CAST(lat as DECIMAL(10,7)) >= CAST(:minLat as DECIMAL(10,7)) AND CAST(lat as DECIMAL(10,7))<= CAST(:maxLat as DECIMAL(10,7)) AND CAST(lon as DECIMAL(10,7)) >= CAST(:minLon as DECIMAL(10,7)) AND CAST(lon as DECIMAL(10,7)) <= CAST(:maxLon as DECIMAL(10,7))) '
                         .'AND (:category IS NULL OR (categoryId = :category))'
                        .'AND ((:limits = 0 ) OR (:limits =1 AND limits > (SELECT COUNT(*) from event_participants e where e.eventId = eventId)))',array(':country'=>$country,':descr'=>$descr,':city'=>$city,':name'=>$name,':state'=>$state,':road'=>$road,
                            ':village'=>$village,':county'=>$county,':limits'=>$limits,':firstDate'=>$firstDate,':secondDate'=>$secondDate,
                            ':minLat'=>$minLat,':maxLat'=>$maxLat,':minLon'=>$minLon,':maxLon'=>$maxLon,':category'=>$category))
                        ->queryAll();
                        
                        
                        
                        $impl = json_encode($getEvents);
                        echo $impl;
                       Yii::app()->end();
                        
            
                
                }
             
            
        }
        
        public function actionGetFullEvent(){
            
            if(isset($_POST["eventId"])){
                
                $eventId = $_POST["eventId"];
                
                $getEvents = Yii::app()->db->createCommand()
                        ->select('e.*, (e.limits - COUNT(ev.userId)) as \'wolne miejsca\', c.description as kategoria, u.name as \'założone przez\'')
                        ->from('event e')
                        ->join('event_participants ev', 'e.eventId = ev.eventId')
                        ->join('categories c','e.categoryId=c.categoryId')
                        ->join('user u','e.ownerId = u.userid')
                        ->where('e.eventId = :eventId',array(':eventId'=>$eventId))
                        ->queryAll();
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
                $sql = "INSERT INTO event_participants VALUES(:ownerId,:eventId)";
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
