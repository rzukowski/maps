<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $userid
 * @property string $name
 * @property string $email
 * @property string $salt
 * @property string $hashedPass
 * @property string $gender
 * @property string $lastLogin
 *
 * The followings are the available model relations:
 * @property Event[] $events
 */
class User extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, email, hashedPass', 'required'),
                        array('email', 'unique', 'className' => 'User', 'attributeName' => 'email', 'message'=>'Email jest już w użyciu'),
                        array('name', 'unique', 'className' => 'User', 'attributeName' => 'name', 'message'=>'Nazwa jest już w użyciu'),
                    
			// The following rule is used by search().email
			// @todo Please remove those attributes that should not be searched.
			array('userid, name, email, salt, hashedPass, gender, lastLogin', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'events' => array(self::MANY_MANY, 'Event', 'event_participants(userId, eventId)'),
                    'ownEvents'=>array(self::HAS_MANY,'Event','ownerId')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'userid' => 'Userid',
			'name' => 'Name',
			'email' => 'Email',
			'salt' => 'Salt',
			'hashedPass' => 'Hashed Pass',
			'gender' => 'Gender',
			'lastLogin' => 'Last Login',
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

		$criteria->compare('userid',$this->userid,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('hashedPass',$this->hashedPass,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('lastLogin',$this->lastLogin,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        public function beforeSave(){
            $this->hashedPass=hash_hmac(Yii::app()->params['encryptionAlg'], $this->hashedPass,
            Yii::app()->params['encryptionKey']);
            return parent::beforeSave();
            
            
        }
}
