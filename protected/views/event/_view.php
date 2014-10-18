<div id="participants">
<P>Uczestnicy</p>

<?php 
foreach($users as $user){
    echo CHtml::link(CHtml::encode($user->name), array('user/view', 'id'=>$user->userid));

    
}

?>
</div>
<div id ="maininfo">
<h1><?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
            array(
                'value'=>CHtml::link(CHtml::encode($model->owner->name), array('user/view', 'id'=>$model->owner->userid)),
                'type'=>'raw',
                'name'=>'Stworzone'
            ),
            array(
   'visible'=>$model->descr== null ? false : true, 
   'name'=>'descr',
   'value' => $model->descr
)
		,
		'date',
		'country',
		array(
   'visible'=>$model->state== null ? false : true, 
   'name'=>'state',
   'value' => $model->state
),
		array(
   'visible'=>$model->city== null ? false : true, 
   'name'=>'city',
   'value' => $model->city
),
            array(
   'visible'=>$model->road== null ? false : true, 
   'name'=>'road',
   'value' => $model->road
),
 array(
   'visible'=>$model->limits== null ? false : true, 
   'name'=>'limits',
   'value' => $model->limits
	)
))); ?>

<div id="map" class="mapOnSingleEvent"></div>
</div>

<script type="text/javascript">
    var latLon = JSON.parse(jsonLatLon);
    alert(latLon)
    window.onload = init;
function init() {
           
            map = new OpenLayers.Map('map');
            mappingLayer = new OpenLayers.Layer.OSM("Simple OSM Map");
             vectorLayer = new OpenLayers.Layer.Vector("Vector Layer", { projection: "EPSG:4326" });
             selectMarkerControl = new OpenLayers.Control.SelectFeature(vectorLayer, {  });
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
                new OpenLayers.LonLat(latLon.lon, latLon.lat).transform(
                    new OpenLayers.Projection("EPSG:4326"),
                    map.getProjectionObject())

                , 5
            );
    
            placeMarker(latLon,map)
           
       
           
        }
        function placeMarker(position, map) {

            var lat = position.lat
            var lon = position.lon
            
            var lonLat = new OpenLayers.Geometry.Point(lon, lat);
            
            lonLat.transform("EPSG:4326", map.getProjectionObject());

            var feature = new OpenLayers.Feature.Vector(lonLat, { Lat: lat, Lon: lon }
                                    );
            vectorLayer.addFeatures(feature);
        }
        
        </script>
        