function SaveEvent(object,url) {
    //var eventDescription=
    
    var data = $(object).parent().children().serialize();
    alert(data)
    var eventDate = $(object).parent().find("input[name=data]").val();
    eventDate = CheckDate(eventDate);
    
    var eventPosition = $(object).parent().next().html();
 
    var eventDescription = $(object).parent().find("textarea").val();
    var eventName = $(object).parent().find("input[name=NazwaWydarzenia]").val();
    var eventLimit = $(object).parent().find("input[name=placeLimit]").val()
    var country = $(object).parent().find(".Kraj").children().text();
    var village =$(object).parent().find(".Village").children().text();
    var county = $(object).parent().find(".County").children().text();
    var city = $(object).parent().find(".Miasto").children().text();
    var street = $(object).parent().find(".Ulica").children().text();
    var state= $(object).parent().find(".State").children().text();
    if (street =="") {
        street = $(object).parent().find("input[name=Ulica]").val();

    }
  
    var wojewodztwo = $(object).parent().find(".Wojewodztwo").children().text();
    var category = $(object).parent().find(".Kategoria").children().find('option:selected').val();
 
    var lat = eventPosition.substring(0,eventPosition.indexOf('|'));
    var lon = eventPosition.substring(eventPosition.indexOf('|'));

  

    $.ajax({
        type: "POST",
        //contentType: "application/json; charset=utf-8",
        url: url,
        data: {'lat':"'"+lat+"'",'lon':"'"+lon+"'", 'eventDate':"'" + eventDate + "'",'eventDescription':"'" + eventDescription + "'",'eventName':"'" + eventName + "'",'eventLimit':"'" + eventLimit + "'",'country':"'" + country + "'",'city':"'"+city+"'",'street':"'"+street+"'",'category':"'"+category+"'",'county':"'"+county+"'",'village':"'"+village+"'",'state':"'"+state+"'"},        
        beforeSend:
            function () {

               // ShowLoadingDiv();

            },
        success:
         function (msg) {
             alert(msg);

           //  loadPopupBox2("#popup_boxEvents");
           //  $("#popup_boxEvents").append("Nowe wydarzenie: " + msg.d + " zostało zapisane");


         },
        complete:
           function () {
           //    HideDivAndOpacityToOne();
          
           },
        error:
         function (XMLHttpRequest, textStatus, errorThrown) {
             alert('error');
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
    }

    for (key in months) {

        if (dateString.indexOf(key) != -1)
            dateString = dateString.replace(key, months[key]);


    }

    return dateString;
}

function HideDivAndOpacityToOne() {

    $('#Downloading').remove()
    $("#MainContainer").css({ // this is just for style		
        "opacity": "1"
    });;

}
