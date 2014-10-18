<?php

/**
 * This is the model class for table "event".
 *
 * The followings are the available columns in table 'event':
 * @property string $eventId
 * @property string $ownerId
 * @property string $name
 * @property string $descr
 * @property string $date
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $road
 * @property string $lat
 * @property string $lon
 * @property integer $limits
 * @property string $county
 * @property string $village
 * @property integer $categoryId
 *
 * The followings are the available model relations:
 * @property Categories $category
 * @property User $owner
 * @property User[] $users
 */
class Event extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('eventId, ownerId, name, descr, date, country, state, lat, lon, categoryId', 'required','message'=>'brak wypełnionego pola ','on'=>array('create','update')),
                        array('eventId, ownerId, name, descr, date, country, state,county,limits, lat, lon, categoryId','filter', 'filter'=>array($this,'empty2Null')),
                        array('date', 'date', 'format'=>'yyyy-MM-dd HH:mm:ss','message'=>'format daty jest niepoprawny (yyyy-MM-dd HH:mm:ss)', 'on'=>array('search','create','update')),
			array('limits, categoryId', 'numerical', 'integerOnly'=>true,'message'=>'{attribute} musi mieć wartość liczbową'),
			array('eventId, ownerId', 'length', 'max'=>38),
			array('name, lat, lon', 'length', 'max'=>50),
			array('descr', 'length', 'max'=>200),
			array('country, state, city, road, county, village', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('eventId, ownerId, name, descr, date, country, state, city, road, lat, lon, limits, county, village, categoryId', 'safe'),
                        array('name, descr, date, country, state, city, road, lat, lon, limits, county, village, categoryId', 'filter', 'filter'=>array($this,'empty2Null'),'on'=>'search'),
		);
	}
function empty2null($value) {
   return ($value===''||$value==="''") ? null : $value;
}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'category' => array(self::BELONGS_TO, 'Categories', 'categoryId'),
			'owner' => array(self::BELONGS_TO, 'User', 'ownerId'),
			'participants' => array(self::MANY_MANY, 'User', 'event_participants(eventId, userId)')
                    );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => 'Nazwa',
			'descr' => 'Opis',
			'date' => 'Data',
			'country' => 'Kraj',
			'state' => 'Województwo',
			'city' => 'Miasto',
			'road' => 'Adres',
			'lat' => 'Lat',
			'lon' => 'Lon',
			'limits' => 'Limit',
			'county' => 'Powiat',
			'village' => 'Wieś',
                        'freePlaces' => 'Wolne miejsca',
                        'category' => 'kategoria',
                    'owner'=>'Stworzone przez'
                    
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
                

		$criteria->compare('name',$this->name,true);
		$criteria->compare('descr',$this->descr,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('road',$this->road,true);
		$criteria->compare('lat',$this->lat,true);
		$criteria->compare('lon',$this->lon,true);
		$criteria->compare('limits',$this->limits);
		$criteria->compare('county',$this->county,true);
		$criteria->compare('village',$this->village,true);
		$criteria->compare('categoryId',$this->categoryId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public static function SearchEvents( $country,$userid,$descr,$city,$name,$state,$road, $village,$county,$limits,$firstDate,$secondDate,$minLat,$maxLat,$minLon,$maxLon,$category,$listPerPage,$toSkipp){
            
            $events = Yii::app()->db->createCommand()
                        ->select('lat, lon, eventId,name')
                        ->from('event')
                        ->where('((:country IS NULL OR country like CONCAT("%",:country,"%")) '
                        .'AND (ownerId != :ownerId) '
                        .'AND (:descr IS NULL OR descr like CONCAT("%",:descr,"%"))'
                        .'AND (:city IS NULL OR city like CONCAT("%",:city,"%"))'
                        .'AND (:name IS NULL OR name like CONCAT("%",:name,"%"))'
                        .'AND (:state IS NULL OR state like CONCAT("%",:state,"%"))'
                        .'AND (:road IS NULL OR road like CONCAT("%",:road,"%"))'
                        .'AND (:village IS NULL OR village like CONCAT("%",:village,"%"))'
                        .'AND (:county IS NULL OR county like CONCAT("%",:county,"%"))'
                        .'AND ((:firstDate IS NULL OR :secondDate IS NULL) OR (TIMEDIFF(:firstDate,date)<=0 AND TIMEDIFF(:secondDate,date)>0))'
                         .'AND (CAST(lat as DECIMAL(10,7)) >= CAST(:minLat as DECIMAL(10,7)) AND CAST(lat as DECIMAL(10,7))<= CAST(:maxLat as DECIMAL(10,7)) AND CAST(lon as DECIMAL(10,7)) >= CAST(:minLon as DECIMAL(10,7)) AND CAST(lon as DECIMAL(10,7)) <= CAST(:maxLon as DECIMAL(10,7))) '
                         .'AND (:category IS NULL OR (categoryId = :category))'
                        .'AND ((:limits = 0 ) OR (:limits =1 AND limits > (SELECT COUNT(*) from event_participants e where e.eventId = eventId) ) ))',array(':country'=>$country,':ownerId'=>$userid,':descr'=>$descr,':city'=>$city,':name'=>$name,':state'=>$state,':road'=>$road,
                            ':village'=>$village,':county'=>$county,':limits'=>$limits,':firstDate'=>$firstDate,':secondDate'=>$secondDate,
                            ':minLat'=>$minLat,':maxLat'=>$maxLat,':minLon'=>$minLon,':maxLon'=>$maxLon,':category'=>$category))
                         ->limit($listPerPage,$toSkipp )
                        ->queryAll();
            return $events;
        }
        
         public static function CountEvents( $country,$userid,$descr,$city,$name,$state,$road ,$village,$county,$limits,$firstDate,$secondDate,$minLat,$maxLat,$minLon,$maxLon,$category){
            /*
             $events = Yii::app()->db->createCommand()
                        ->select('count(*)')
                        ->from('event')
                        ->where('((:country IS NULL OR country like CONCAT("%",:country,"%")) '
                        .'AND (ownerId != :ownerId) '
                        .'AND (:descr IS NULL OR descr like CONCAT("%",:descr,"%"))'
                        .'AND (:city IS NULL OR city like CONCAT("%",:city,"%"))'
                        .'AND (:name IS NULL OR name like CONCAT("%",:name,"%"))'
                        .'AND (:state IS NULL OR state like CONCAT("%",:state,"%"))'
                         .'AND (:road IS NULL OR road like CONCAT("%",:road,"%"))'
                         .'AND (:village IS NULL OR village like CONCAT("%",:village,"%"))'
                        .'AND (:county IS NULL OR county like CONCAT("%",:county,"%"))'
                        .'AND ((:limits = 0 ) OR (:limits =1 AND limits > (SELECT COUNT(*) from event_participants e where e.eventId = eventId) ) ))',
                                array(':country'=>$country,':ownerId'=>$userid,':descr'=>$descr,':city'=>$city,':name'=>$name,':state'=>$state,
                                   ':road'=>$road,':village'=>$village,
                            ':limits'=>$limits
                            ))
                        ->queryAll();
             */
            $events = Yii::app()->db->createCommand()
                        ->select('count(*)')
                        ->from('event')
                        ->where('((:country IS NULL OR country like CONCAT("%",:country,"%")) '
                        .'AND (ownerId != :ownerId) '
                        .'AND (:descr IS NULL OR descr like CONCAT("%",:descr,"%"))'
                        .'AND (:city IS NULL OR city like CONCAT("%",:city,"%"))'
                        .'AND (:name IS NULL OR name like CONCAT("%",:name,"%"))'
                        .'AND (:state IS NULL OR state like CONCAT("%",:state,"%"))'
                        .'AND (:road IS NULL OR road like CONCAT("%",:road,"%"))'
                        .'AND (:village IS NULL OR village like CONCAT("%",:village,"%"))'
                        .'AND (:county IS NULL OR county like CONCAT("%",:county,"%"))'
                        .'AND ((:firstDate IS NULL AND :secondDate IS NULL) OR (:firstDate IS NULL AND TIMEDIFF(:secondDate,date)>0) OR (TIMEDIFF(:firstDate,date)<=0 AND :secondDate IS NULL) OR (TIMEDIFF(:firstDate,date)<=0 AND TIMEDIFF(:secondDate,date)>0))'
                         .'AND (CAST(lat as DECIMAL(10,7)) >= CAST(:minLat as DECIMAL(10,7)) AND CAST(lat as DECIMAL(10,7))<= CAST(:maxLat as DECIMAL(10,7)) AND CAST(lon as DECIMAL(10,7)) >= CAST(:minLon as DECIMAL(10,7)) AND CAST(lon as DECIMAL(10,7)) <= CAST(:maxLon as DECIMAL(10,7))) '
                         .'AND (:category IS NULL OR (categoryId = :category))'
                        .'AND ((:limits = 0 ) OR (:limits =1 AND limits > (SELECT COUNT(*) from event_participants e where e.eventId = eventId) ) ))',array(':country'=>$country,':ownerId'=>$userid,':descr'=>$descr,':city'=>$city,':name'=>$name,':state'=>$state,':road'=>$road,
                            ':village'=>$village,':county'=>$county,':limits'=>$limits,':firstDate'=>$firstDate,':secondDate'=>$secondDate,
                            ':minLat'=>$minLat,':maxLat'=>$maxLat,':minLon'=>$minLon,':maxLon'=>$maxLon,':category'=>$category))
                        ->queryAll();
          
              
            return $events;
        }
        
        public static function GetEventById($eventId){
            $getEvents = Yii::app()->db->createCommand()
                        ->select('e.*, (e.limits - COUNT(ev.userId)) as \'freePlaces\', c.description as category, u.name as \'owner\'')
                        ->from('event e')
                        ->join('event_participants ev', 'e.eventId = ev.eventId')
                        ->join('categories c','e.categoryId=c.categoryId')
                        ->join('user u','e.ownerId = u.userid')
                        ->where('e.eventId = :eventId',array(':eventId'=>$eventId))
                        ->queryAll();
            return $getEvents;
            
            
        }
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Event the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
