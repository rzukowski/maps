<?php
/* @var $this EventController */
/* @var $model Event */
/* @var $form CActiveForm */
?>

<script type="text/javascript">
    var toProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
        var fromProjection = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
   var lastZIndex=752;
   function placeMarkers(stringArr){
       var jsonArray = JSON.parse(stringArr);
 
       
       for(var ii=0;ii<jsonArray.length;ii++){
           
        console.log(jsonArray[ii]["lon"] + " "+jsonArray[ii]["lat"])
        var arrayTheSameLatLon = [];
     
        //check if two events with same lat and lon were send in one response
        
        for(var zz =ii+1;zz<jsonArray.length;zz++){
            
            if(objectsAreSame(jsonArray[ii],jsonArray[zz],"eventId")){
                
                arrayTheSameLatLon.push(jsonArray[zz])
                jsonArray.splice(zz,1)
                zz--;
                
            }

        }
        
        lonlat1 = new OpenLayers.LonLat(jsonArray[ii]["lon"],jsonArray[ii]["lat"])
        if(arrayTheSameLatLon.length!=0){
            arrayTheSameLatLon.push(jsonArray[ii]);
            placeMultiplyMarkers(arrayTheSameLatLon,lonlat1);
        }
        //placeMarker(lonlat1, map,jsonArray[ii]["eventId"]);
        
       }
       
   }
function placeMultiplyMarkers(arrObj,position){
    var lat = position.lat;
            var lon = position.lon;
             
             var lonLat = new OpenLayers.Geometry.Point(position.lon, position.lat);
    lonLat.transform("EPSG:4326", map.getProjectionObject());
    var featuresArr=[]
    for(var ii=0;ii<arrObj.length;ii++){
        featuresArr[ii] = new OpenLayers.Feature.Vector(lonLat, { Lat: lat, Lon: lon });
        vectorLayer.addFeatures(featuresArr[ii])

        
    }
    
    for(var ii=0;ii<featuresArr.length;ii++){
        
      if(ii!=0)
       featuresArr[ii].attributes= {"eventId":arrObj[ii]["eventId"],"nextFeature":featuresArr[ii-1]};
      else
         featuresArr[ii].attributes= {"eventId":arrObj[ii]["eventId"]}; 
       
    }

    
}
   
   
        function placeMarker(position, map,eventId) {
             var lat = position.lat;
            var lon = position.lon;
             
             var lonLat = new OpenLayers.Geometry.Point(position.lon, position.lat);
             lonLat.transform("EPSG:4326", map.getProjectionObject());
            
            console.log(lat + " "+lon);
            var feature = new OpenLayers.Feature.Vector(lonLat, { Lat: lat, Lon: lon });
            vectorLayer.addFeatures(feature);
            
            feature.attributes = {"eventId":eventId}
        }
     
   function objectsAreSame(x, y,ignoredProperty) {
        var objectsAreSame = true;
        for(var propertyName in x) {
            if(propertyName==ignoredProperty)
            continue;
           if(x[propertyName] !== y[propertyName]) {
              objectsAreSame = false;
              break;
           }
        }
        return objectsAreSame;
}
    window.onload = init;
function init() {
           
            map = new OpenLayers.Map('map');
            mappingLayer = new OpenLayers.Layer.OSM("Simple OSM Map");
             vectorLayer = new OpenLayers.Layer.Vector("Vector Layer", { projection: "EPSG:4326" });
             
             selectMarkerControl = new OpenLayers.Control.SelectFeature(vectorLayer, { onSelect: onFeatureSelect, onUnselect: onFeatureUnselect });
             
            map.addControl(selectMarkerControl);

            selectMarkerControl.activate();

            
             map.addLayer(vectorLayer);

            map.addLayer(mappingLayer);

           
           //vectorLayer = new OpenLayers.Layer.Vector("Vector Layer", { projection: "EPSG:4326" });
            ////selectMarkerControl = new OpenLayers.Control.SelectFeature(vectorLayer, { onSelect: onFeatureSelect, onUnselect: onFeatureUnselect });
           // map.addControl(selectMarkerControl);
                
           // selectMarkerControl.activate();
           //map.addLayer(vectorLayer);
            
            
            //transform from EPSG:900913 to EPSG:4326 (21,52 is LonLat)
            map.setCenter(
                new OpenLayers.LonLat(21, 52).transform(
                    new OpenLayers.Projection("EPSG:4326"),
                    map.getProjectionObject())

                , 5
            );
    
     map.events.register('click', map, function (e) {

                var lonlat = map.getLonLatFromPixel(e.xy);
                alert(lonlat)
                lonlat1 = new OpenLayers.LonLat(lonlat.lon, lonlat.lat).transform(fromProjection, toProjection);
                alert(lonlat1);
            });
           
        map.events.register("moveend", map, function() {
                               calculateBounds();
        }); 
            //map.calculateBounds();
               calculateBounds();
           
        }
        
        function calculateBounds(){

             var arr = map.calculateBounds(map.center,map.resolution);
            arr = arr.toString().split(',');
            var minLongitude =arr[0]
            var minLatitude = arr[1]
            var maxLongitude = arr[2];
            var maxLatitude = arr[3];
            //alert("dupa" + minLongitude + " " + minLatitude)
            
            var minLonLat = new OpenLayers.LonLat(minLongitude,minLatitude).transform(fromProjection,toProjection);
            var maxLonLat = new OpenLayers.LonLat(maxLongitude,maxLatitude).transform(fromProjection,toProjection);

            
            
            //alert(minLonLat[0]);
            var input = $('#SearchForm').find("input[name='minLon']");
            
            if(input.length!=0){
                
                $("#SearchForm").find("input[name='minLon']").val(minLonLat["lon"]);
                $("#SearchForm").find("input[name='minLat']").val(minLonLat["lat"])
                $("#SearchForm").find("input[name='maxLon']").val(maxLonLat["lon"])
                $("#SearchForm").find("input[name='maxLat']").val(maxLonLat["lat"])
                
            }
            else{
            $('<input type="hidden" name="minLon" value="'+minLonLat["lon"]+'" />').prependTo('#SearchForm');
           $('<input type="hidden" name="minLat" value="'+minLonLat["lat"]+'" />').prependTo('#SearchForm');
           $('<input type="hidden" name="maxLon" value="'+maxLonLat["lon"]+'" />').prependTo('#SearchForm');
           $('<input type="hidden" name="maxLat" value="'+maxLonLat["lat"]+'" />').prependTo('#SearchForm');
       }
    
    
    }
    
        function onFeatureSelect(feature){
        
        
        for(var f=0;f<vectorLayer.features.length;f++) {
        if(vectorLayer.features[f].attributes.latlon == feature.attributes["latlon"] && vectorLayer.features[f].popup==null) {
          selectMarkerControl.select(layer.features[f]);
          break;
        }
        }
        
         if(feature.attributes["nextFeature"]!=undefined){
         getFullEvent(feature.attributes["eventId"],feature)
       
         selectMarkerControl.select(feature.attributes["nextFeature"])

         
        }
        else
            getFullEvent(feature.attributes["eventId"],feature);
            
            
    }

    function onFeatureUnselect(){
        
        
    }
     </script>   
<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'SearchForm',
	'method'=>'get',
    'enableAjaxValidation'=>false,
        'enableClientValidation'=>'true',
    'action'=>CHtml::normalizeUrl(array('getDetails')),
    'htmlOptions'=>array(
                               'onsubmit'=>"return false;",/* Disable normal form submit */
                               'onkeypress'=>" if(event.keyCode == 13){ send(); } " /* Do ajax call when user presses enter key */
                             ),
)); ?>

	

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	
	<div class="row">
		<?php echo $form->label($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'country'); ?>
		<?php echo $form->textField($model,'country',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'state'); ?>
		<?php echo $form->textField($model,'state',array('size'=>50,'maxlength'=>50)); ?>
	</div>
        <div class="row">
		<?php echo $form->label($model,'county'); ?>
		<?php echo $form->textField($model,'county',array('size'=>50,'maxlength'=>50)); ?>
	</div>
	<div class="row">
		<?php echo $form->label($model,'city'); ?>
		<?php echo $form->textField($model,'city',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'road'); ?>
		<?php echo $form->textField($model,'road',array('size'=>50,'maxlength'=>50)); ?>
	</div>
    <div class="row">
		<?php
            $list = CHtml::listData(Categories::model()->findAll(array('order' => 'description')), 'categoryId', 'description');
                echo $form->dropDownList($model, 'categoryId', $list);
        ?>
	</div>
    <div class="row">
        
        
       <input id="ytEvent_limit" type="hidden" value="0"
            name="Event[limits]" />
        <input name="Event[limits]"
        id="Event_limit" value="1" type="checkbox" />
        
    </div>
    <div class="row">
        <p>Data</p>
       Od: <?php  $this->widget('zii.widgets.jui.EJuiDateTimePicker',array(

    'name'=>'firstDate',
    // additional javascript options for the date picker plugin
    'options'=>array(
        'showAnim'=>'fold',
        'dateFormat'=>'yy-mm-dd',
    ),
      
    'htmlOptions'=>array(
        'style'=>'height:20px;'
    ),
)); ?> 
        
       do: <?php  $this->widget('zii.widgets.jui.EJuiDateTimePicker',array(

    'name'=>'secondDate',
    // additional javascript options for the date picker plugin
    'options'=>array(
        'showAnim'=>'fold',
        'dateFormat'=>'yy-mm-dd',
    ),
    'htmlOptions'=>array(
        'style'=>'height:20px;'
    ),
)); ?> 
        
    </div>

	<div class="row buttons">
             <?php echo CHtml::Button('SUBMIT',array('onclick'=>'send();')); ?> 
		
 
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
<script type="text/javascript">
 
 
 function getFullEvent(eventId,feature){
     //alert(eventId)
     $.ajax({
   type: 'POST',
    url: '<?php echo Yii::app()->createAbsoluteUrl("event/getFullEvent"); ?>',
   data:{'eventId':eventId},
   timeout:80000,
success:function(data){
                //alert(data);
                prepareAndShowPopup(data,feature);
              },
   error: function(jqxhr,textStatus,errorThrown)  { // if error occured
     alert('fuckin alert')
    }
  });
   
 }
 
 function SaveToEvent(obj,url){
 
    var eventId = $(obj).parent().find('.eventId').html();
    
    alert(eventId)
 
 $.ajax({
   type: 'POST',
    url: url,
   data:{'eventId':eventId},
   timeout:80000,
success:function(data){
    ShowInfoPopup(data)
              },
   error: function(jqxhr,textStatus,errorThrown)  { // if error occured
     alert(textStatus + " " + errorThrown)
    }
  });
 

    }
    
 function prepareAndShowPopup(data,feature){
 var jsonObject = JSON.parse(data);
 var content="<div class='eventPopup'>";
 var notOnPopup=["eventId","ownerId",null,"","Lat","Lon"];
 
    for(var key in jsonObject){
        
        if(!contains(notOnPopup,key) && !contains(notOnPopup,jsonObject[key])){
            if(key=="Nazwa")
            {   var href = '<?php echo CHtml::link('LinkText',array('event/view','id'=>'idelement')); ?>';
                href = href.replace("idelement",jsonObject["eventId"]).replace("LinkText",jsonObject["Nazwa"]);
                content+="<p>"+href+"</p>"
            }
            content += "<p>"+key+": "+jsonObject[key]+"</p>";
        }
    }
         content +="<span class='eventId' style='visibility:hidden;'>"+jsonObject["eventId"]+"</span>";
         content +="<a onclick='SaveToEvent(this,\""+url+"\")'>Zapisz się</a>";
         content+="</div>";
     popup = new OpenLayers.Popup.FramedCloud("tempId", feature.geometry.getBounds().getCenterLonLat(),
                                    null,
                                    content ,
                                    null, true,function(e){destroyPopup(feature);OpenLayers.Event.stop(e)});
                
                lastZIndex++;
                feature.popup = popup;
                
                map.addPopup(feature.popup);
               AttachOnclickEventToPopup();
 
 }
 
  function AttachOnclickEventToPopup(){
 
                 $(".olPopup").each(function(){

                     $(this).click(function(){
                         
                         $(this).css('z-index',++lastZIndex)
                     });
                             
                });
            }
 
function send()
 {
 
   var data=$("#SearchForm").serialize();
 
 
  $.ajax({
   type: 'POST',
    url: '<?php echo Yii::app()->createAbsoluteUrl("event/getDetails"); ?>',
   data:data,
   timeout:80000,
success:function(data){
                //alert(data); 
                placeMarkers(data);
              },
   error: function(jqxhr,textStatus,errorThrown)  { // if error occured
     
    }
  });
 
}
function destroyPopup(feature){
             map.removePopup(feature.popup);
            feature.popup.destroy();
           feature.popup = null;
            
                
        }
 function contains(a, obj) {
    var i = a.length;
    while (i--) {
       if (a[i] === obj) {
           return true;
       }
    }
    return false;
}
</script>
