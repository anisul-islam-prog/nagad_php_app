
  function wic_form_validation(){
      var wic_status =  $('#wic_provision_status').val();
      var collection_date =  $('#collection_date').val();
      var delivered_by_agent =  $('#delivered_by_agent').val();
      var delivery_status =  $('#delivered').is(':checked');
      var ch_provision_status =  $('#ch_provision_status').is(':checked');
      
     //alert(delivery_status);
      
      var provision_send_wic =  $('#submit_by_wic').val();
      
      //alert(provision_send_wic);
      
       var delivered_from =  $('#delivered_from').val();
      
      //alert(delivered_from);
     
      if(wic_status==0){ // When Fresh 
        alert ('Please select Wic Provision Status'); 
         return false;
      }
      else if(wic_status==1){ // When yes
          if(collection_date==''){
            alert ('Please select Collection Date'); 
            return false;
          }
          else if(delivery_status == false && ch_provision_status==true) {
            alert ('Please select Delivered Checkbox '); 
            return false;
         }
         else if((delivery_status == true)){
            if(delivered_by_agent=='' || delivered_by_agent==' ' || delivered_by_agent==null){
            alert ('Please Select Delivered By .'); 
            return false;
           }
         }
      }
      else if(wic_status==2 || wic_status==3){ // When Not Done 
          var wic_remarks = $('#wic_remarks').val();
          if(wic_remarks=='' || wic_remarks==' ' || wic_remarks==null){
             alert ('Please Insert Wic Remarks for Not Done Provision Status .'); 
             return false;
            }
          if(delivery_status==true){
             alert ('Can only deliver when WIC Provision status is Done.'); 
             return false;
          }  
         
      }
      
      else if(provision_send_wic=='' || provision_send_wic==' ' || provision_send_wic==null){
            alert('Please Select Provision Submit By '); 
            return false;
        
      }
      /*
      else if((ch_provision_status == true) && (delivery_status == false)) {
         alert ('Please select Delivered Checkbox '); 
         return false;
      }
      */
    
     else if((ch_provision_status == false) && (delivery_status == true)) {
         
            alert ('Can not deliver. Reason : Channel Operation varification Failed .'); 
             return false;
        
      }
  
//     else if(delivery_status == true) {
//         if(delivered_by_agent=='' || delivered_by_agent==' ' || delivered_by_agent==null){
//            alert ('Please Select Delivered By .'); 
//            return false;
//           }
//      }
     
     
    
    else{
      return true ;  
   }
   
  
      
} // wic_form_validation
  
    
   
    




