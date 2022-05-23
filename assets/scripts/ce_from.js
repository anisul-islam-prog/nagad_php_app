
  function ce_form_validation(){
      var ce_status =  $('#call_status').val();
      
      if(ce_status==2){ // When Followup 
          var followup_date = $('#follow_up_date').val();
          var remarks = $('#remarks').val(); 
          if(followup_date=='' || followup_date==' ' || followup_date==null){
             alert ('Please select Follow Up date'); 
             return false;
            }
          else if(remarks=='' || remarks==' ' || remarks==null){
             alert ('Please insert Remarks for Follow Up Customers'); 
             return false;
            }
          else {
             return true ;  
            }
      }
      else if(ce_status==3){ // When Followup 
          var collection_date = $('#collection_date').val();
          if(collection_date=='' || collection_date==' ' || collection_date==null){
             alert ('Please select Collection date'); 
             return false;
            }
          else {
             return true ;  
            }
          
      }
      else if(ce_status==4){
          var remarks = $('#remarks').val();  
           if(remarks=='' || remarks==' ' || remarks==null){
             alert ('Please insert Remarks for Not Interested Customers'); 
             return false;
            }
          else {
             return true ;  
            }
      }
      
      else{
          alert ('Please Select Call Status'); 
          return false;
      }
  
    } // ce_form_validation
   
    




