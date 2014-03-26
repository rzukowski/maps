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
			array('eventId, ownerId, name, descr, date, country, state, lat, lon, categoryId', 'required','message'=>'brak wypełnionego pola: {attribute}'),
                        array('eventId, ownerId, name, descr, date, country, state,county,limits, lat, lon, categoryId','filter', 'filter'=>array($this,'empty2Null')),
			array('limits, categoryId', 'numerical', 'integerOnly'=>true,'message'=>'{attribute} musi mieć wartość liczbową'),
			array('eventId, ownerId', 'length', 'max'=>38),
			array('name, lat, lon', 'length', 'max'=>50),
			array('descr', 'length', 'max'=>200),
			array('country, state, city, road, county, village', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('eventId, ownerId, name, descr, date, country, state, city, road, lat, lon, limits, county, village, categoryId', 'safe', 'on'=>'search'),
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
			'limits' => 'Limits',
			'county' => 'Powiat',
			'village' => 'Wieś'
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
