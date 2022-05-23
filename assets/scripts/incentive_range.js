var $j = $.noConflict(true);
$j(document).ready(function(){
 
    var counter = 2;
 
    $j("#addButton").click(function () {
 
	if(counter>10){
            alert("Only 10 textboxes allow");
            return false;
	}   
 
	var newTextBoxDiv = $j(document.createElement('div'))
	     .attr("id", 'TextBoxDiv' + counter).attr("class","div_per");
 
             
 
	newTextBoxDiv.after().html('<h4>Range - '+counter+'</h4><label>From  : </label>' +
	      '<input type="text" name="from' + counter + 
	      '" id="from' + counter + '" value="" > <label>To  : </label>' +
	      '<input type="text" name="to' + counter + 
	      '" id="to' + counter + '" value="" > <label>Value  : </label>' +
	      '<input type="text" name="value' + counter + 
	      '" id="value' + counter + '" value="" >');
 
	newTextBoxDiv.appendTo("#TextBoxesGroup");
        
        var total_range = document.getElementById("total_range").value ;
        total_range = +total_range + 1;
        var field = document.getElementById("total_range");
        field.value = total_range ; 
        //alert(total_range);
 
	counter++;
     });
 
     $j("#removeButton").click(function () {
	if(counter==1){
          alert("No more textbox to remove");
          return false;
       }   
       
       var total_range = document.getElementById("total_range").value ;
        total_range = +total_range - 1;
        var field = document.getElementById("total_range");
        field.value = total_range ; 
        //alert(total_range);
       
	counter--;
 
        $j("#TextBoxDiv" + counter).remove();
 
     });
 
     $j("#getButtonValue").click(function () {
 
	var msg = '';
	for(i=1; i<counter; i++){
   	  msg += "\n Textbox #" + i + " : " + $j('#textbox' + i).val();
	}
    	  alert(msg);
     });
  });