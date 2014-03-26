function SaveEvent(object,url) {
    //var eventDescription=
    var variables = new Object;
    
    var data = $(object).parent().children().serialize();

    variables.eventDate = $(object).parent().find("input[name=data]").val();
    variables.eventDate = CheckDate(variables.eventDate);
    
    variables.eventPosition = $(object).parent().next().html();
 
    variables.eventDescription = $(object).parent().find("textarea").val();
    variables.eventName = $(object).parent().find("input[name=NazwaWydarzenia]").val();
    variables.eventLimit = $(object).parent().find("input[name=placeLimit]").val();
    variables.country = $(object).parent().find(".country").children().text();
    variables.village =$(object).parent().find(".village").children().text();
    variables.county = $(object).parent().find(".county").children().text();
    variables.city = $(object).parent().find(".city").children().text();
    variables.street = $(object).parent().find(".road").children().text();
    variables.state= $(object).parent().find(".state").children().text();
    if (variables.street =="" || variables.street == undefined) {
        variables.street = $(object).parent().find("input[name=Ulica]").val();

    }
    variables.category = $(object).parent().find(".categoryId").children().find('option:selected').val();
    console.log(variables.street);
 
    variables.lat = variables.eventPosition.substring(0,variables.eventPosition.indexOf('|'));
    variables.lon = variables.eventPosition.substring(variables.eventPosition.indexOf('|')+1);

    for(var variable in variables){
        
        if(variables[variable]==undefined)
            variables[variable]="";
        
    }

    $.ajax({
        type: "POST",
        //contentType: "application/json; charset=utf-8",
        url: url,
        data: {'lat':variables.lat,'lon':variables.lon, 'date':variables.eventDate,'descr':variables.eventDescription,'name':variables.eventName,'limits':variables.eventLimit,'country':variables.country,'city':variables.city,'road':variables.street,'county':variables.county,'village':variables.village,'state':variables.state,'categoryId':variables.category},        
        beforeSend:
            function () {

               ShowLoadingDiv();

            },
        success:
         function (msg) {
             alert(msg)
             if(msg.indexOf("zapisane")!=-1){
                 $(object).parent().find(".successEventSaved").text(eventName+ " zostało pomyślne zapisane.");

                 
             }
             $(object).parent().find('.errorsEvent').each(function(){
                 
                 $(this).remove();
                 
                 
             })
             
             var errors = JSON.parse(msg);
             alert(errors)
             for (var key in errors){
                 var klasa= "."+key
         
                 var html = $(object).parent().find(klasa).html();
              
                 $(object).parent().find(klasa).html("<span class='errorsEvent'>"+ errors[key]+"</span>"+html);
                 
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

function CheckDate(date){

    if(date.substring(date.indexOf(' ')).length>0 && date.indexOf(' ')!=-1){
        
        date =date+ " 00:00:00";
        
        
    }
    return date;
    
    
}

function ShowLoadingDiv(){
    //hide fucking all
    //show new div
    $.blockUI({ message: '<img src="../../../images/ajax-loader.gif" />' ,
     css: { margin:'0 auto',width:'100px' } }
    ); 
    
}
function limitMiejsc(object) {

    if ($(object).is(":checked")) {

        $(object).parent().next().fadeIn();

    }
    else {
        $(object).parent().next().fadeOut();
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

function countOpis(object,max){

    if($(object).val().length>max){
        
        $(object).before("Przekroczony maksymalny rozmiar opisu.</br>")
        
        
    }

}

function HideDivAndOpacityToOne() {

    $('#Downloading').remove();
    $("#MainContainer").css({ // this is just for style		
        "opacity": "1"
    });;

}
