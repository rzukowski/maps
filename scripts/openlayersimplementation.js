/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var toProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
        var fromProjection = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
   var lastZIndex=752;
  

 
  function AttachOnclickEventToPopup(){
 
                 $(".olPopup").each(function(){

                     $(this).click(function(){
                         
                         $(this).css('z-index',++lastZIndex)
                     });
                             
                });
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
    
     

 function contains(a, obj) {
    var i = a.length;
    while (i--) {
       if (a[i] === obj) {
           return true;
       }
    }
    return false;
}



  function createPopup(content,feature,map,destroyFunction) {
                    
                    if(typeof destroyFunction==="undefined")
                        var destroyFunction = destroyPopup;
               
                
                //alert(JSON.stringify(jsonResults));
                var popup = new OpenLayers.Popup.FramedCloud("tempId"+lastZIndex, feature.geometry.getBounds().getCenterLonLat(),
                                    null,
                                    content,
                                    null, true,function(e){destroyFunction(map,feature);OpenLayers.Event.stop(e);return false;});
                
                
                
              
                feature.popup = popup;
                
               
                
                
                map.addPopup(feature.popup);
               //AttachOnclickEventToPopup();
               
                $("#tempId"+lastZIndex).click(function(){

                    $(this).css('z-index',++lastZIndex)
        
                });
                lastZIndex++;
                
                
             }
             
 function createContentDivSearchPage(data,hrefToEventView,hrefToUserView,url){
     
      var jsonObject = JSON.parse(data);
 var content="<div class='eventPopup'>";
 var notOnPopup=["eventId","ownerId","categoryId",null,"","Lat","Lon"];
 
 var labels = JSON.parse(polishLabels);
 
 for(var key in labels){notOnPopup.push(key);}

 
 
 jsonObject = TranslateEventToEnglish(jsonObject);
    for(var key in jsonObject){
        
        if(!contains(notOnPopup,key) && !contains(notOnPopup,jsonObject[key])){
            if(key==labels.name)
            {   
                href = hrefToEventView.replace("idelement",jsonObject["eventId"]).replace("LinkText",jsonObject[labels["name"]]);
                content="<p>"+href+"</p>" + content
            }
            else if(key ==labels.owner){
                
                href = hrefToUserView.replace("idelement",jsonObject["ownerId"]).replace("LinkText",jsonObject[labels["owner"]]);
                content="<p>"+key+": " + href+"</p>" + content
                
                }

              
                else
                    content += "<p>"+key+": "+jsonObject[key]+"</p>";
        }
    }
        
         content +="<span class='eventId' style='visibility:hidden;'>"+jsonObject["eventId"]+"</span>";
         content +="<a onclick='SaveUserToEvent(this,\""+url+"\")'>Zapisz się</a>";
         content+="</div>";
     return content;
     
 }            
//create content for Popup
function createContentDivCreatePage(city, country, state, street, streetNumber,county, village,lat,lon,dateId) {
    
            dateId = dateId + "date"
            var contentForNewEvent = "<div class=\"infoWindowContent\" id='content' ><div class=\"successEventSaved\"></div><form>\
            <p class='name'>Nazwa: <input type=\"text\" name=\"NazwaWydarzenia\"></input></p>\
               <p class='descr'>Opis: <textarea class=\"OpisText\" onkeyup=\"countOpis(this,"+maxDescr+")\"></textarea></p>"+
             "<p class='date'>Data: <input type=\"text\" size=\"12\" id=\""+dateId+"\" name='data' ></input></p>" +
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
            contentForNewEvent +=  "<span style=\"display:none;\">" + lat + "|" + lon + "</span>";
            return contentForNewEvent;
        }
    function createContentDivUpdatePage(data,urlToSave){
     var date= dateId + "date";
  
 var jsonObject = JSON.parse(data);
 jsonObject = TranslateEventToEnglish(jsonObject);

               var eventId = jsonObject["eventId"]
               var name = jsonObject["name"];
               var country=jsonObject["country"];
               var city = jsonObject["city"];
               var village = jsonObject["village"];
               var county = jsonObject["county"];
               var state = jsonObject["state"];
               var street = jsonObject["road"];
               var lat = jsonObject["Lat"];
               var lon = jsonObject["Lon"];
               var descr = jsonObject["descr"];
               var eventDate = jsonObject["date"];
               var categoryId = jsonObject["categoryId"];
               var limits = jsonObject["limits"];
               var saveSuccessFunction = "function(data){alert(data);data = JSON.parse(data);lonlat = {'lat':data.lat,'lon':data.lon};console.log(JSON.stringify(lonlat))}";
               
            var content = "<div class=\"infoWindowContent\" id='content' ><div class=\"successEventSaved\"></div><form>\
            <p class='name'>Nazwa: <input type=\"text\" name=\"NazwaWydarzenia\" value=\""+name+"\"></input></p>\
               <p class='descr'>Opis: <textarea class=\"OpisText\" onkeyup=\"countOpis(this,"+maxDescr+")\" >"+descr+"</textarea></p>"+
             "<p class='date'>Data: <input type=\"text\" size=\"12\" id=\""+dateId+"\" value=\""+eventDate+"\" name='data' ></input></p>" +
             "<p class='limits' >Limit miejsc:<input type=\"checkbox\" value='1' onclick=\"limitMiejsc(this)\">Tak</input></p>"
             if(limits!=null){
               content +="<p style=\"display:none;\" class='miejsca'>Ilość miejsc: <input type=\"text\" name=\"placeLimit\" class=\"d\"></input></p>";
        }
          

                content += "<p class='country'>Kraj: <span>" + country + "</span></p>";

         
            if (city != null && city != undefined && city != ""){

                content += "<p class='city'>Miasto: <span>" + city + "</span></p>";

            }
            else if(village !=null && village != undefined && village !=""){
                
                content += "<p class='village'>Wieś: <span>" + village + "</span></p>";
                
            }
            else if(county !=null && county != undefined && county !=""){
                
                content += "<p class='county'><span>" + county + "</span></p>";
                
            }
            if (state != null && state != undefined && state != "") {

                content += "<p class='state'>Wojewodztwo: <span>" + state + "</span></p>";

            }
            if (street != null && street != undefined && street != "") {

                    content += "<p class='road'>Ulica: <span>" + street + "</span></p>";

            }
            content +="<p class='categoryId'>Kategoria: " + CreateCategoryComboBox(categories,categoryId) + "</p>"
            content += "</form><a id='SaveEvent' onclick=\"SaveEvent(this,'"+urlToSave+"',"+saveSuccessFunction+")\">Save</a></div>";
            content +=  "<span style=\"display:none;\">" + lat + "|" + lon + "</span>";
            content +=  "<span style=\"display:none;\">"+eventId + "</span>";
            return content;
             
 
 }
 
        //creates html compoBox
        function CreateCategoryComboBox(categoriesString,categoryId) {

            var cat = JSON.parse(categoriesString);
            var selected="";
            categoryId = (typeof categoryId == 'undefined')?'':categoryId;
            var ret = "<select>";
           for(var i=0;i<cat.length;i++)
           {
                if(cat[i].categoryId = categoryId)
                    selected = "selected =\"true\"";
                
                ret += "<option value=\"" + cat[i].categoryId + "\" "+selected+" >" + cat[i].description + "</option>";

           }
           
            

            ret += "</select>";
            return ret;

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
        
   
  
    
function CheckDate(date){
if(date==undefined)return date;
    if(date.match(/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/)){
        
        date = date + ":00";
        
        
    }
    return date;
    
    
}


function countOpis(object,max){

    if($(object).val().length>max){
        
        $(object).before("Przekroczony maksymalny rozmiar opisu.</br>")
        
        
    }

}


        
        //function to prevent the change of prefix value in input
        function Change(object, prefix) {
      
            if (!(object.value.match('^' + prefix))) {
                object.value = prefix + object.value;
            }

        }
            
        

function DateStringToDateNumber(dateString) {

    var months = {
        "STY": "01",
        "LUT": "02",
        "MAR": "03",
        "KWI": "04",
        "MAJ": "05",
        "CZE": "06",
        "LIP": "07",
        "SIE": "08",
        "WRZ": "09",
        "PAŹ": "10",
        "LIS": "11",
        "GRU":"12"
    };

    for (key in months) {

        if (dateString.indexOf(key) != -1)
            dateString = dateString.replace(key, months[key]);


    }

    return dateString;
}


            //removign popup from map
   function destroyPopup(map,feature){
              
              
             map.removePopup(feature.popup);
            feature.popup.destroy();
            feature.popup = null;
            
   }

        

 
function GetDataFromPopup(popupObject){
    
    var object = popupObject;
    var variables = new Object;
    
   
    variables.eventId = $(object).parent().next().next().html();
    
    
    variables.date = $(object).parent().find("input[name=data]").val();
    variables.date = CheckDate(variables.date);
    
    variables.eventPosition = $(object).parent().next().html();
 
    variables.descr = $(object).parent().find("textarea").val();
    variables.name = $(object).parent().find("input[name=NazwaWydarzenia]").val();
    variables.limits = $(object).parent().find("input[name=placeLimit]").val();
    variables.country = $(object).parent().find(".country").children().text();
    variables.village =$(object).parent().find(".village").children().text();
    variables.county = $(object).parent().find(".county").children().text();
    variables.city = $(object).parent().find(".city").children().text();
    variables.street = $(object).parent().find(".road").children().text();
    variables.state= $(object).parent().find(".state").children().text();
    if (variables.street =="" || variables.street == undefined) {
        variables.street = $(object).parent().find("input[name=Ulica]").val();

    }
    variables.categoryId = $(object).parent().find(".categoryId").children().find('option:selected').val();
    
 
    variables.lat = variables.eventPosition.substring(0,variables.eventPosition.indexOf('|'));
    variables.lon = variables.eventPosition.substring(variables.eventPosition.indexOf('|')+1);

    for(var variable in variables){
        
        if(variables[variable]==undefined)
            variables[variable]="";
        
    }
    return variables;
    
}

       
 //download all information about event on feature click
 function getFullEvent(eventId,url,onSuccess){
     //alert(eventId)
     $.ajax({
   type: 'POST',
    url: url,
   data:{'eventId':eventId},
   timeout:80000,
success:function(data){
                //alert(data);
                onSuccess(data);

              },
   error: function(jqxhr,textStatus,errorThrown)  { // if error occured
     alert('fuckin alert')
    }
  });
   
 }
 
    function getOpenLayersData(feature,map,timeout,callback) {

            selectedFeature = feature;
            
            var openLayersData="";
            
            var counter = 0;
            var maxLoops = timeout/0.5;
            

            
             
            var lat = selectedFeature.attributes.Lat;
            var lon = selectedFeature.attributes.Lon;

            //here handle funcking ReverseGeocode results

            var httpreq = "http://nominatim.openstreetmap.org/reverse?format=json&lat=" + lat + "&lon=" + lon + "&zoom=18&addressdetails=1&accept-language=pl";
            var req = createXmlRequestObj();
            
   
            req.addEventListener("loadstart",ShowLoadingDiv,false);
            //req.addEventListener("loadstart",ShowLoadingDiv,false);
            //req.addEventListener("loadend",function(){$.unblockUI();},false);
            req.addEventListener("loadend",function(){$.unblockUI();},false);
            req.onreadystatechange = function(){openLayersData=processGeocodeResults(req)};
           
            req.open("GET", httpreq, true);

            req.send();
            var time = window.setInterval(function(){CheckData()},500);
                function CheckData(){ 
                
                if(counter<maxLoops && typeof openLayersData!="undefined"){
                    
                    clearInterval(time);
                    callback(openLayersData);
                    
                }
                else if(counter==maxLoops){
                   
                    callback("Errors");
                    clearInterval(time);
                }
                else{
                    counter++;
                    //CheckData();
                    
                }      
            }

        }

    
   function GoToEventOnMap(eventId){

   
     for(var f=0;f<vectorLayer.features.length;f++) {
                if(vectorLayer.features[f].attributes.eventId == eventId) {
                    selectMarkerControl.select(vectorLayer.features[f]);
                break;
                }
                }
   
   
    }

function HideDivAndOpacityToOne() {

    $('#Downloading').remove();
    $("#MainContainer").css({ // this is just for style		
        "opacity": "1"
    });;

}
    

function limitMiejsc(object) {

    if ($(object).is(":checked")) {

        $(object).parent().next().fadeIn();

    }
    else {
        $(object).parent().next().fadeOut();
    }
}


        //process http request to open layers web-service
        function processGeocodeResults(req) {
                
                var jsonResults;
                if (req.readyState == 4 && req.status == 200) {
                    if (req.responseText == "Not found") {
                        alert('not found');
                        jsonResults="Error2";
                    }
                    else {
                        jsonResults = eval("(" + req.responseText + ")");
                        
                         return jsonResults;

                    }
                }

            }
            
        
  
//show on update page - before dragging feature (marker)
function TranslateEventToEnglish(data){
    
    labels = JSON.parse(polishLabels);
    
    for(var key in data){
      
        for(var keyEnglish in labels){
            
            var cos = labels[keyEnglish];
            
            if(labels[keyEnglish] == key){
                
                data[keyEnglish] = data[key];
            }
        }
      
    
    }

    return data;
}
 

    function placeEventsName(jsonArray){
       
       
       var results ="<div id='eventsNames'>";
       for(var ii=0;ii<jsonArray.length;ii++){
           
           results += "<span onclick='GoToEventOnMap(\""+jsonArray[ii]["eventId"]+"\")'>"+jsonArray[ii]["name"]+"</span>"
           
           
       }
       
      results +="</div>";
       
       $("#eventsResultsView").html(results);
       
   }
   
        function placeMarker(position, map,eventId,vectorLayer) {
             var lat = position.lat;
            var lon = position.lon;
             
             var lonLat = new OpenLayers.Geometry.Point(position.lon, position.lat);
             lonLat.transform("EPSG:4326", map.getProjectionObject());
            
            var feature = new OpenLayers.Feature.Vector(lonLat, { Lat: lat, Lon: lon });
            vectorLayer.addFeatures(feature);
            
            feature.attributes = {"eventId":eventId,"latlon":lat+lon,"Lat":lat,"Lon":lon};
            
            return feature;
        }
        
   function placeMarkers(jsonArray){
       
        vectorLayer.removeAllFeatures();
       
       for(var ii=0;ii<jsonArray.length;ii++){
        
        lonlat1 = new OpenLayers.LonLat(jsonArray[ii]["lon"],jsonArray[ii]["lat"])
        //var arrayTheSameLatLon = [];
     
        //check if two events with same lat and lon were send in one response
        /*
        for(var zz =ii+1;zz<jsonArray.length;zz++){
            
            if(objectsAreSame(jsonArray[ii],jsonArray[zz],"eventId")){
                
                arrayTheSameLatLon.push(jsonArray[zz])
                jsonArray.splice(zz,1)
                zz--;
                
            }

        }
        
        
        if(arrayTheSameLatLon.length!=0){
            arrayTheSameLatLon.push(jsonArray[ii]);
            placeMultiplyMarkers(arrayTheSameLatLon,lonlat1);
        }
        */
        placeMarker(lonlat1, map,jsonArray[ii]["eventId"],vectorLayer);
        
        
        
       }
       placeEventsName(jsonArray);
   }
   
   //save logged-in user to event
 function SaveUserToEvent(obj,url){
 
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
    
    
function SaveEvent(object,url,successFunction) {
    //var eventDescription=
    variables = GetDataFromPopup(object);
    
    

    $.ajax({
        type: "POST",
        //contentType: "application/json; charset=utf-8",
        url: url,
        data: {'Event':{'id':variables.eventId,'lat':variables.lat,'lon':variables.lon, 'date':variables.date,'descr':variables.descr,'name':variables.name,'limits':variables.limits,'country':variables.country,'city':variables.city,'road':variables.street,'county':variables.county,'village':variables.village,'state':variables.state,'categoryId':variables.categoryId}},
        beforeSend:
            function () {

               ShowLoadingDiv();

            },
        success:
         function (msg) {
             alert(msg)
             if(msg.indexOf("zapisane")!=-1){
                 
                 if(typeof successFunction=='undefined'){
                     
                 $(object).parent().find(".successEventSaved").text(variables.name+ " zostało pomyślne zapisane.");
             
                 }
                 else
                     successFunction(msg);
                 
             }
             else{
             $(object).parent().find('.errorsEvent').each(function(){
                 
                 $(this).remove();
                 
                 
             });
             
             var errors = JSON.parse(msg);
             alert(errors)
             for (var key in errors){
                 var klasa= "."+key
         
                // var html = $(object).parent().find(klasa).html();
              
                $(object).parent().find(klasa).prepend("<span class='errorsEvent'>"+ errors[key]+"</span>");
                 
             }
             }

           //  loadPopupBox2("#popup_boxEvents");
           //  $("#popup_boxEvents").append("Nowe wydarzenie: " + msg.d + " zostało zapisane");


         },
        complete:
           function () {
           $.unblockUI();
          
           },
        error:
         function (XMLHttpRequest, textStatus, errorThrown) {
             alert('error?');
             alert(errorThrown + " "+ textStatus)
             //HideLoadingDiv()

         }
    });
}
 
 
function ShowLoadingDiv(){
   
    $.blockUI({ message: '<img src="../../../images/ajax-loader.gif" />' ,
     css: { margin:'0 auto',width:'100px' } }
    ); 
    
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


    function onFeatureUnselect(){
        
        
    }