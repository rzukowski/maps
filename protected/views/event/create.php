    <?php
/* @var $this EventController */
/* @var $model Event */

$this->breadcrumbs=array(
	'Events'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Event', 'url'=>array('index')),
	array('label'=>'Manage Event', 'url'=>array('admin')),
);
?>

<div id="datePickerHtml" >
    
    
    <input type="text" value="2014/03/15 05:06" id="datetimepicker"/><br><br>
    
</div>

<h1>Create Event</h1>



<script type="text/javascript">
   
    
var dateId=0;

        var newDateCal = function (obj) {
            alert('ddd')
            var id = $(obj).attr('id');
             var $input = $('#'+id);
        var datePicker = id+'picker';
        if($input.length>0)
            $('#'+id).datetimepicker({value:'2015/04/15 05:03',step:10,lang:'pl',id:datePicker});
       
          
        }
        
       
        var map, mappingLayer, vectorLayer, selectMarkerControl, selectedFeature;
        var toProjection = new OpenLayers.Projection("EPSG:4326");   
        var fromProjection = new OpenLayers.Projection("EPSG:900913"); 
        
        window.onload = init;
        
        

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
            selectMarkerControl = new OpenLayers.Control.SelectFeature(vectorLayer, { onSelect: function(feature){onFeatureSelect(feature);}, onUnselect: onFeatureUnselect });
            map.addControl(selectMarkerControl);
                
            selectMarkerControl.activate();
            map.addLayer(vectorLayer);

            map.setCenter(
                new OpenLayers.LonLat(21, 52).transform(
                    new OpenLayers.Projection("EPSG:4326"),
                    map.getProjectionObject())

                , 5
            );

            map.events.register('click', map, function (e) {

                var lonlat = map.getLonLatFromPixel(e.xy);
                
                lonlat1 = new OpenLayers.LonLat(lonlat.lon, lonlat.lat).transform(fromProjection, toProjection);

                placeMarker(lonlat1, map,"",vectorLayer);
                Event.stop(e);
            });
            
            //map.calculateBounds();

            
            
        }

        function onFeatureSelect(feature){
           var jsonResults;
           if(feature.popup==undefined){
               
               getOpenLayersData(feature,map,5,function(data){jsonResults=data;step0();})
               
               
           }
           else{
               
               map.addPopup(feature.popup);
               
               
           }
               function step0(){
                   console.log(jsonResults)
               
               var lat=feature.attributes.Lat;
               
               var lon=feature.attributes.Lon;
               
               var city = jsonResults.address.city;
                var country = jsonResults.address.country;
                var state = jsonResults.address.state;
                var street = jsonResults.address.road;
                var roadNumber = jsonResults.address.house_number;
                var county = jsonResults.address.county;
                var village = jsonResults.address.village;
                
                // var street = SearchForStreet(results[0])
                

                var content = createContentDivCreatePage(city, country, state, street, roadNumber,county,village,lat,lon,++dateId)
                
                        createPopup(content,feature,map);
                        
                        //attach date picker to input element
                        var $obj = $(feature.popup.contentDiv).children('.infoWindowContent').first()//.children("input")//.children("input[name='date']").first();
                        var $input = $obj.find("input[name='data']").first();
                        newDateCal($input)
                        
                        
            
    }
            
            
        }
        

        
       
    </script>

    <div id="map-canvas" style="height: 1000px;width: 1000px;"></div>