<?php
/* @var $this EventController */
/* @var $model Event */

$this->breadcrumbs=array(
	'Events'=>array('index'),
	$model->name=>array('view','id'=>$model->eventId),
	'Update',
);

$this->menu=array(
	array('label'=>'List Event', 'url'=>array('index')),
	array('label'=>'Create Event', 'url'=>array('create')),
	array('label'=>'View Event', 'url'=>array('view', 'id'=>$model->eventId)),
	array('label'=>'Manage Event', 'url'=>array('admin')),
);
?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/openlayersimplementation.js" ></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/eventsajax.js" ></script>
 <script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/eventsajax.js" ></script>

<h1>Update Event <?php echo $model->eventId; ?></h1>
<?php echo CHtml::link(CHtml::encode($model->name), array('event/view', 'id'=>$model->eventId)); ?>

<div id="updateForm">
<?php $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
<div id="map-canvas" style="height: 1000px;width: 1000px;"></div>

<script type="text/javascript">
    
    console.log(polishLabels)
    var hrefToEventView = '<?php echo CHtml::link('LinkText',array('event/view','id'=>'idelement')); ?>';
 var hrefToUserView = '<?php echo CHtml::link('LinkText',array('user/view','id'=>'idelement')); ?>';
    var form = $("div#updateForm").html();
    
    
     var map, mappingLayer, vectorLayer, selectMarkerControl, selectedFeature;
        var toProjection = new OpenLayers.Projection("EPSG:4326");   
        var fromProjection = new OpenLayers.Projection("EPSG:900913"); 
        
        window.onload = init;
        
        function onFeatureSelect(feature,map){
               
                //lonlat1 = new OpenLayers.LonLat(lonlat.lon, lonlat.lat).transform(fromProjection, toProjection);
                if(feature.popup!=null){
                    destroyPopup(map,feature);
                    
                }    
                
                getFullEvent(feature.attributes["eventId"],'<?php echo Yii::app()->createAbsoluteUrl("event/getFullEvent"); ?>',function(data){
                         
                    var content = createContentDivUpdatePage(data,'<?php echo Yii::app()->createAbsoluteUrl("event/update").'&id='.$model->eventId; ?>');
                    
                    
                    createPopup(content,feature,map,function(){});
                    var $obj = $(feature.popup.contentDiv).children('.infoWindowContent').first()
                    var $input = $obj.find("input[name='data']").first();
                    newDateCal($input)
                    selectMarkerControl.unselectAll();      
                    } );
                    }

        function onFeatureUnselect(feature) {
            
            //map.removePopup(feature.popup);
            //feature.popup.destroy();
            //feature.popup = null;
        }

        function init() {
            
            map = new OpenLayers.Map('map-canvas');
            mappingLayer = new OpenLayers.Layer.OSM("Simple OSM Map");
             
            map.addLayer(mappingLayer);

           
            vectorLayer = new OpenLayers.Layer.Vector("Vector Layer", { projection: "EPSG:4326" });
            selectMarkerControl = new OpenLayers.Control.SelectFeature(vectorLayer, { onSelect: function(feature){alert('dd');onFeatureSelect(feature,map);}, onUnselect: onFeatureUnselect });
            map.addControl(selectMarkerControl);
            var drag= new OpenLayers.Control.DragFeature(vectorLayer,{onComplete:function(feature,pixel){
                   
                    var lonlat = map.getLonLatFromPixel(pixel);
                
                var lonlat1 = new OpenLayers.LonLat(lonlat.lon, lonlat.lat).transform(fromProjection, toProjection);
                feature.attributes["Lon"]=lonlat1.lon;
                feature.attributes["Lat"]=lonlat1.lat;
                var jsonResults;
              getOpenLayersData(feature,map,5,function(data){jsonResults=data;step0();})
              
              function step0(){
                  var saveElement = document.getElementById("SaveEvent");
                  var popupData = GetDataFromPopup(saveElement);
                  
               popupData.Lat =lonlat1.lat;   
               popupData.Lon = lonlat1.lon;   
                popupData.country = jsonResults.address.country;
                popupData.state = jsonResults.address.state;
                popupData.street = jsonResults.address.road;
                popupData.roadNumber = jsonResults.address.house_number;
                popupData.county = jsonResults.address.county;
                popupData.village = jsonResults.address.village;
                popupData.city  = jsonResults.address.city;
                
                var content = createContentDivUpdatePage(JSON.stringify(popupData),'<?php echo Yii::app()->createAbsoluteUrl("event/update").'&id='.$model->eventId; ?>')
                  destroyPopup(map,feature);
                  createPopup(content,feature,map,function(){
                      destroyPopup(map,feature);
                      vectorLayer.removeFeatures(feature);
                      step1();
                   }
                );
                  
              }
                    
            }});
            
            
            map.addControl(drag);
            drag.activate();
            selectMarkerControl.activate();
            map.addLayer(vectorLayer);

            map.setCenter(
                new OpenLayers.LonLat(21, 52).transform(
                    new OpenLayers.Projection("EPSG:4326"),
                    map.getProjectionObject())

                , 5
            );
            
            //jsonLatLon passed from controler
            lonlat = JSON.parse(jsonLatLon);
            
            step1();
            function step1(){
            console.log("lonlat from update page "+JSON.stringify(lonlat))
            var feature = placeMarker(lonlat, map,'<?php echo $model->eventId; ?>',vectorLayer);
                 
               
                
                onFeatureSelect(feature,map);
                
                    }        
            
            //map.calculateBounds();

            
            
        }

    
    </script>
    
    
    
   