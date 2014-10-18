/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


    //create datePicker on popup
       var newDateCal = function (obj) {
        alert('dd')
            var id = $(obj).attr('id');
             var $input = $('#'+id);
        var datePicker = id+'picker';
        alert(datePicker)
        if($input.length>0)
            $('#'+id).datetimepicker({format: 'Y-m-d H:i',step:10,lang:'pl',id:datePicker,dateTimeFormat:''});
 
        }
        
        
function ShowInfoPopup(content){
    content = "<div class='infopopup'><a onclick='removeBlock()'>X</a>"+content+"</div>";
    
    $.blockUI({ message: content ,
     css: { margin:'0 auto',width:'100px' } }
    ); 
    
   
}
//removing UI loading circle
 function removeBlock(){
        
        $.unblockUI();
        
        return false; 
        
    }