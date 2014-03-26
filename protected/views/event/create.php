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


<h1>Create Event</h1>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->request->baseUrl.'/css/jsDatePick_ltr.min.css'; ?>" />

<script type="text/javascript">
    var dateId=0;
    var lastZIndex=752;
        var newDateCal = function (obj) {
            var id = $(obj).attr('id')
            new JsDatePick({
                useMode: 2,
                target: id,
                dateFormat: "%Y-%m-%d",
                imgPath:"img/"
                /*selectedDate:{				This is an example of what the full configuration offers.
                    day:5,						For full documentation about these settings please see the full version of the code.
                    month:9,
                    year:2006
                },
                yearsRange:[1978,2020],
                limitToToday:false,
                cellColorScheme:"beige",
                dateFormat:"%m-%d-%Y",
                
                weekStartDay:1*/
            })
            
            $(obj).click();
        }

   
        
        function CreateCategoryComboBox(categoriesString) {

            var cat = JSON.parse(categoriesString);
            ret = "<select>";
           for(var i=0;i<cat.length;i++){

                ret += "<option value=\"" + cat[i].categoryId + "\">" + cat[i].description + "</option>";

           }
            

            ret += "</select>";
            return ret;

        }

        var map, mappingLayer, vectorLayer, selectMarkerControl, selectedFeature;
        var toProjection = new OpenLayers.Projection("EPSG:4326");   
        var fromProjection = new OpenLayers.Projection("EPSG:900913"); 
        
        window.onload = init;
        function createContentDiv(city, country, state, street, streetNumber,county, village,dateId) {
            dateId = dateId + "date"
            var contentForNewEvent = "<div class=\"infoWindowContent\" id='content' ><div class=\"successEventSaved\"></div><form>\
            <p class='name'>Nazwa: <input type=\"text\" name=\"NazwaWydarzenia\"></input></p>\
               <p class='descr'>Opis: <textarea class=\"OpisText\" onkeyup=\"countOpis(this,"+maxDescr+")\"></textarea></p>"+
             "<p class='date'>Data:<input type=\"text\" size=\"12\" id=\""+dateId+"\" name='data' onclick='newDateCal(this)' ></input></p>" +
             "<p class='limits' >Limit miejsc:<input type=\"checkbox\" value='1' onclick=\"limitMiejsc(this)\">Tak</input></p>\
                <p style=\"display:none;\" class='miejsca'>Ilość miejsc: <input type=\"text\" name=\"placeLimit\" class=\"d\"></input></p>";

            if (country != null && country != undefined && country != "") {

                contentForNewEvent += "<p class='country'>Kraj: <span>" + country + "</span></p>";

            }
            if (city != null && city != undefined && city != ""){

                contentForNewEvent += "<p class='city'>Miasto: <span>" + city + "</span></p>";

            }
            else if(village !=null && village != undefined && village !=""){
                
                contentForNewEvent += "<p class='village'>Wieś: <span>" + village + "</span></p>";
                
            }
            else if(county !=null && county != undefined && county !=""){
                
                contentForNewEvent += "<p class='county'><span>" + county + "</span></p>";
                
            }
            if (state != null && state != undefined && state != "") {

                contentForNewEvent += "<p class='state'>Wojewodztwo: <span>" + state + "</span></p>";

            }
            if (street != null && street != undefined && street != "") {
                if (streetNumber == "" || streetNumber == undefined || streetNumber == null) {
                    contentForNewEvent += "<p class='road'>Ulica: <input name=\"Ulica\" value=\"" + street + "\" onkeyup=\"return Change(this,'" + street + "')\" onblur=\"return Change(this,'" + street + "')\" /></p>";
                    //StreetChange(parent.children(), street)
                }
                else
                    contentForNewEvent += "<p class='road'>Ulica: <span>" + street + " " + streetNumber + "</span></p>";

            }
            contentForNewEvent +="<p class='categoryId'>Kategoria: " + CreateCategoryComboBox(categories) + "</p>"
            contentForNewEvent += "</form><a onclick=\"SaveEvent(this,'"+url+"')\">Save</a></div>";

            return contentForNewEvent;
        }
        

        function Change(object, prefix) {
      
            if (!(object.value.match('^' + prefix))) {
                object.value = prefix + object.value;
            }

        }
        function processGeocodeResults(req,feature,lat,lon) {
                
                if (req.readyState == 4 && req.status == 200) {
                    if (req.responseText == "Not found") {
                        alert('not found');
                    }
                    else {
                        var info = eval("(" + req.responseText + ")");
                        
                        preparePopup(info,feature,lat,lon);

                    }
                }

            }
            function destroyPopup(feature){
              
              
             map.removePopup(feature.popup);
            feature.popup.destroy();
            feature.popup = null;
            
        }
            
            function preparePopup(jsonResults,feature,lat,lon) {
                    
                var city = jsonResults.address.city
                var country = jsonResults.address.country;
                var state = jsonResults.address.state;
                var street = jsonResults.address.road;
                var roadNumber = jsonResults.address.house_number;
                var county = jsonResults.address.county;
                var village = jsonResults.address.village;

                // var street = SearchForStreet(results[0])
                

                var content = createContentDiv(city, country, state, street, roadNumber,county,village,++dateId)
                
                //alert(JSON.stringify(jsonResults));
                popup = new OpenLayers.Popup.FramedCloud("tempId", feature.geometry.getBounds().getCenterLonLat(),
                                    null,
                                    content + "<span style=\"display:none;\">" + lat + "|" + lon + "</span>",
                                    null, true,function(e){destroyPopup(feature);OpenLayers.Event.stop(e);return false;});
                
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

     
        function onFeatureSelect(feature) {

            selectedFeature = feature;
            
            
            if(feature.popup==undefined){
             
            var lat = selectedFeature.attributes.Lat;
            var lon = selectedFeature.attributes.Lon;

            //here handle funcking ReverseGeocode results

            var httpreq = "http://nominatim.openstreetmap.org/reverse?format=json&lat=" + lat + "&lon=" + lon + "&zoom=18&addressdetails=1&accept-language=pl";
            var req = createXmlRequestObj();
            
   
            req.addEventListener("loadstart",ShowLoadingDiv,false);
            req.addEventListener("loadend",function(){$.unblockUI();},false);
            req.onreadystatechange = function(){processGeocodeResults(req,feature,lat,lon)};
           
            req.open("GET", httpreq, true);
            
            req.send(null);

            
            }
            else
                map.addPopup(feature.popup);
            

             

        }
        
     

        function createXmlRequestObj() {
            if (window.XMLHttpRequest) {
                try {
                    req = new XMLHttpRequest();
                } catch (e) {
                    req = false;
                }
            } else if (window.ActiveXObject) {
                try {
                    req = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e) {
                    try {
                        req = new ActiveXObject("Microsoft.XMLHTTP");
                    } catch (e) {
                        req = false;
                    }
                }
            }
            return req;

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
            selectMarkerControl = new OpenLayers.Control.SelectFeature(vectorLayer, { onSelect: onFeatureSelect, onUnselect: onFeatureUnselect });
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

                placeMarker(lonlat1, map);
                Event.stop(e);
            });
            
            //map.calculateBounds();

            var arr = map.calculateBounds(map.center,map.resolution);
            arr = arr.toString().split(',')
            var minLongitude =arr[0]
            var minLatitude = arr[1]
            var maxLongitude = arr[2];
            var maxLatitude = arr[3];
            alert("dupa" + minLongitude + " " + minLatitude)
            
            var minLonLat = new OpenLayers.LonLat(minLongitude,minLatitude).transform(fromProjection,toProjection)
            alert(minLonLat);
            
            
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

    <div id="map-canvas" style="height: 1000px;width: 1000px;"></div>