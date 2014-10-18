<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class EventTest extends CDbTestCase
        
{   public $fixtures=array(
        'event'=>'Event',

    );
      
   public function testSearchByCountry()
   {
     
       $event = $this->event;
        // Arrange
        $eventToInsert = new Event;
        //$eventToInsert->setAttributes($this->event["sample1"]);
        //$eventToInsert->save(false);
 
        $model = Event::model()->findByPk($event["sample1"]["eventId"]);
 $this->assertEquals($model->name,$event["sample1"]["name"]);
       $searchedEvent = Event::SearchEvents($event["sample1"]["country"], "123", null, null, null, null, null, null, null, 0, null, null, '53', '56', '22', '26', 1, 10, 0);
       //$this->assertEquals($searchedEvent["name"],$event["sample1"]["name"]);
        // Assert
       $this->assertTrue(is_array($searchedEvent));
       $this->assertEquals(sizeof($searchedEvent),1);
       $this->assertEquals($searchedEvent[0]["name"],$event["sample1"]["name"]);
      //$this->assertEquals($event["sample1"]["name"],$searchedEvent["name"]);
   }

    public function testSearchByDate()
    {
        $event = $this->event;
        $searchedEvent = Event::SearchEvents(null, "123", null, null, null, null, null, null, null, 0, "2014-08-14 20:25:00","2014-08-16 00:00:00", '53', '56', '22', '26', 1, 10, 0);
        $searchedEvent_count = Event::CountEvents(null, "123", null, null, null, null, null, null, null, 0, "2014-08-14 20:25:00","2014-08-16 00:00:00", '53', '56', '22', '26', 1);
        
        $searchedEvent2 = Event::SearchEvents(null, "123", null, null, null, null, null, null, null, 0, "2014-08-13 20:25:00","2014-08-14 00:00:00", '53', '56', '22', '26', 1, 10, 0);
      
       $this->assertTrue(is_array($searchedEvent));
       $this->assertEquals(sizeof($searchedEvent),1);
       $this->assertEquals(sizeof($searchedEvent_count),1);
       $this->assertEquals(sizeof($searchedEvent2),0);
       
       $this->assertEquals($searchedEvent[0]["name"],$event["sample1"]["name"]);
        $this->assertEquals($searchedEvent_count[0]["count(*)"],'1');
       
       
       
    }
    
   
    
}

