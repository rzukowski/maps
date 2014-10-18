<?php
/* @var $this EventController */
/* @var $model Event */
/* @var $form CActiveForm */
?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/openlayersimplementation.js" ></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/scripts/eventsajax.js" ></script>
<script type="text/javascript">
    
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
        <label>Kategoria</label>
		<?php
            $list = CHtml::listData(Categories::model()->findAll(array('order' => 'description')), 'categoryId', 'description');
                echo $form->dropDownList($model, 'categoryId', $list);
        ?>
	</div>
    <div class="row">
        
        <label> Wolne miejsca: </label>
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
             <?php echo CHtml::Button('SUBMIT',array('onclick'=>'send(1);')); ?> 
		
 
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
<script type="text/javascript">
 var hrefToEventView = '<?php echo CHtml::link('LinkText',array('event/view','id'=>'idelement')); ?>';
 var hrefToUserView = '<?php echo CHtml::link('LinkText',array('user/view','id'=>'idelement')); ?>';
 window.onload = init;
function init() {
           var mapOptions = {
projection: new OpenLayers.Projection('EPSG:2180'),
units: 'm',
minResolution: '1',
maxResolution: '4000',
resolutions: [
3052.7655842634194,
1526.3827921317097,
763.1913960658549,
381.59569803292743,
190.79784901646372,
95.39892450823186,
47.69946225411593,
23.849731127057964,
11.924865563528982,
5.962432781764491,
2.9812163908822455,
1.4906081954411228
],
maxExtent: new OpenLayers.Bounds(150000, 120000, 920000, 800000),
maxScale: 1000000
};
            map = new OpenLayers.Map('map',mapOptions);
            mappingLayer = new OpenLayers.Layer.OSM("Simple OSM Map");
            layout = new OpenLayers.Layer.WMS( "Geoportal Ortofoto",
    "http://sdi.geoportal.gov.pl/wms_orto/wmservice.aspx", {layers:"ORTOFOTO",src:"EPSG:2180",isBaseLayer:true} );
    layout2 = new OpenLayers.Layer.WMS( "Geoportal Ortofoto",
    "http://sdi.geoportal.gov.pl/wms_prng/wmservice.aspx", {layers:"Fizjografia,Wsie",src:"EPSG:2180"} );
             vectorLayer = new OpenLayers.Layer.Vector("Vector Layer", { projection: "EPSG:2180" });
             
             selectMarkerControl = new OpenLayers.Control.SelectFeature(vectorLayer, { onSelect: function(f){getFullEvent(f.attributes["eventId"],'<?php echo Yii::app()->createAbsoluteUrl("event/getFullEvent"); ?>',
                     function(data){
                         
                    var content = createContentDivSearchPage(data,hrefToEventView,hrefToUserView,'<?php echo Yii::app()->createAbsoluteUrl("event/saveto"); ?>');
                    createPopup(content,f,map);
                             
                    }
                            )}, onUnselect: onFeatureUnselect });
             
            map.addControl(selectMarkerControl);

            selectMarkerControl.activate();

            
             map.addLayer(vectorLayer);

            map.addLayer(layout);
map.addLayer(layout2);
           
           //vectorLayer = new OpenLayers.Layer.Vector("Vector Layer", { projection: "EPSG:4326" });
            ////selectMarkerControl = new OpenLayers.Control.SelectFeature(vectorLayer, { onSelect: onFeatureSelect, onUnselect: onFeatureUnselect });
           // map.addControl(selectMarkerControl);
                
           // selectMarkerControl.activate();
           //map.addLayer(vectorLayer);
            
            
            //transform from EPSG:900913 to EPSG:4326 (21,52 is LonLat)
            alert(map.getProjectionObject())
           map.setCenter(
                new OpenLayers.LonLat(21, 52).transform(
                    new OpenLayers.Projection("EPSG:4326"),
                    map.getProjectionObject())

                , 10
            );
    
     map.events.register('click', map, function (e) {

                var lonlat = map.getLonLatFromPixel(e.xy);
                alert(lonlat)
                lonlat1 = new OpenLayers.LonLat(lonlat.lon, lonlat.lat).transform("EPSG:2180", "EPSG:4326");
                alert(lonlat1);
            });
           
        map.events.register("moveend", map, function() {
                               calculateBounds();
        }); 
            //map.calculateBounds();
               calculateBounds();
           
        }
 
    
 
 
 
 //func to download events names and place features on map
function send(curpage)
 {
 
   var data=$("#SearchForm").serialize();
   data +="&page="+curpage;

 
  $.ajax({
   type: 'POST',
    url: '<?php echo Yii::app()->createAbsoluteUrl("event/getDetails"); ?>',
   data:data,
   timeout:80000,
success:function(data){
               var jsonArr = JSON.parse(data)
                
                placeMarkers(jsonArr[1]);
                placePaginationLinks(jsonArr[0])
              },
   error: function(jqxhr,textStatus,errorThrown)  { // if error occured
     
    }
  });
 
}

function placePaginationLinks(jsonString){

$("#eventsResultsViewPagination").html(jsonString)


}


</script>
