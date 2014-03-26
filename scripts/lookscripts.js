/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function ShowInfoPopup(content){
    content = "<div class='infopopup'><a onclick='removeBlock()'>X</a>"+content+"</div>";
    
    $.blockUI({ message: content ,
     css: { margin:'0 auto',width:'100px' } }
    ); 
    
   
}

 function removeBlock(){
        
        $.unblockUI();
        
        return false; 
        
    }