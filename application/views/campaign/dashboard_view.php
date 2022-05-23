<h1 class="heading"><?php if(1)  echo ''; ?>Dashboard</h1>
<script type="text/javascript">
  
</script>


<?php if ($message_success != '') { echo '<div class="alert alert-success"><a data-dismiss="alert" class="close">&times;</a>'. $message_success .'</div>'; } ?>
<?php if ($message_error != '') { echo '<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>'. $message_error .'</div>'; } ?>

<?php echo validation_errors('<div class="alert alert-danger"><a data-dismiss="alert" class="close">&times;</a>', '</div>'); ?>

<div class="clearfix"></div>

<form id="dashboard_form" style="margin-bottom: 3px; margin-left: 15px;" action="" method="POST"> 
    <div class="row">
        <div class="controls col-xs-4 col-md-2">
        	<select name="brand_name" id="brand_name" class="chosen-select form-control">
        	
        		<option value="airtel">Airtel</option>
        		<option value="robi">Robi</option>
        		
        	</select>
        </div>

        <div class="col-xs-4 col-md-2">
            <select name="report_duration" id="report_duration" class="chosen-select form-control">
        		
        		<option value="daily">Daily</option>
				<option value="weekly">Weekly </option>
        		<option value="monthly">Monthly </option>        		
        	</select>
        </div>

        

        <input type="submit" name="get_dashboard" value="Get Chart" class="btn btn-sm btn-success" /> 
		<input type="button" name="get_odf" id="export_pdf" value="Export PDF" class="btn btn-sm btn-success" />		

    </div>
</form>

<div class="col-sm-12">

  <div class="row-fluid">
            <div class="span12">
			<div class="row">
			<div class="col-sm-4">
			<div id="campaignbydeptpiechart" style="width: 300px;height:300px;"></div>  
			</div>
			<div class="col-sm-8">
			<div id="campaignbybrandlinechart" style="width: 600px;height:300px;"></div>  
			</div>
			</div>
			
			<div id="chartContainer" style="height:400px; "></div> 
			
			
			
			
			
            </div>
        </div>
   
</div>

<style>
.canvasjs-chart-credit{display:none;}
</style>
<script>
$(document).ready(function() {
       $("#dashboard_form").submit(function(e){
		   e.preventDefault();
		   var brand_name = $("#brand_name").val();
		   var duration  =  $("#report_duration").val();
		   
		   var destination = "<?php echo base_url(); ?>dnd_controllers/campaign/getJsonCampaignsCountByDept/";
	var dataPoints2;
	
	
	
	
	
	//alert(destination);
	$.ajax({
		method:"POST",
		dataType:"json",
		url:destination,
		data:{
			brand_name:brand_name,
			duration:duration
			},
		success: function(data) {
			
			dataPoints2=data;
			var chart = new CanvasJS.Chart("campaignbydeptpiechart",
	{
		theme: "theme2",
		title:{
			text: "" 
		},
		data: [
		{
			type: "pie",
			showInLegend: true,
			toolTipContent: "{y}",
			yValueFormatString: "",
			legendText: "{indexLabel}",
			dataPoints: dataPoints2
		}
		]
	});
	
	
	chart.render();
			
				   
		}
	});
	
	
		var destination3 = "<?php echo base_url(); ?>dnd_controllers/campaign/getJsonCampaignsCountByBrandName/"; 
	
	
	
	
	
	
	//alert(destination);
	$.ajax({
		method:"POST",
		dataType:"json",
		url:destination3,
		data:{brand_name:brand_name,
			duration:duration},
		success: function(data) {
			
			
			
   for (var keys in  data) {
	   data[keys].x=new Date(data[keys].x);
	   data[keys].y=parseInt(data[keys].y);
	   
   }
   
   var chart2 = new CanvasJS.Chart("campaignbybrandlinechart", {
	animationEnabled: true,  
	title:{
		text: "Campaign Count  by BrandName and Day"
	},
	axisX: {
		interval: 1,
		intervalType: "day"
	},
	axisY: {
		title: "Number of Campaigns",
		valueFormatString: "#0,,.",
		suffix: "",
		stripLines: [{
			value: 3366500,
			label: "Average"
		}]
	},
	data: [{
		yValueFormatString: "#,### Campaigns",
		xValueFormatString: "DDDD MMMM YYYY",
		type: "spline",
		dataPoints:data
	}]
});
chart2.render();
   
			
	
	
	
			
				   
		}
	});
	
	
	
		var destination2 = "<?php echo base_url(); ?>dnd_controllers/campaign/getJsonCampaignsCountByDeptAndDuration/";
	var report_duration = $('#report_duration').val();
	
	$.ajax({
		method:"POST",
		dataType:"json",
		url:destination2,
		data:{brand_name:brand_name,
			duration:duration},
		success: function(data2) {
			
			for (var key in  data2) {
   // if (data2.dataPoints.hasOwnProperty(key)) {
	var datapoint=data2[key].dataPoints;
   for (var keys in  datapoint) {
	   datapoint[keys].x=new Date(datapoint[keys].x);
	   
	   
   }
   // }
}

console.log(data2);





var chart3 = new CanvasJS.Chart("chartContainer", { 
	animationEnabled: true,
	title:{
		text: "Robi Contact Policy Campaign History",
		fontFamily: "arial black",
		fontColor: "#695A42"
	},
	axisX: {
		interval: 1,
		intervalType: "day"
	},
	axisY:{
		valueFormatString:"#",
		gridColor: "#B6B1A8",
		tickColor: "#B6B1A8"
	},
	toolTip: {
		shared: true,
		content: toolTipContent
	},
	data: data2
});
chart3.render();
				   
		}
	});
	
	
	
	
	
	
	
	
   

    });
	
	
	
	
	$("#export_pdf").click(function(event) {
		event.preventDefault();
		
		var canvas = $("#campaignbydeptpiechart .canvasjs-chart-canvas").get(0);
		var canvas2 = $("#campaignbybrandlinechart .canvasjs-chart-canvas").get(0);
		var canvas3 = $("#chartContainer .canvasjs-chart-canvas").get(0);
		var dataURL = canvas.toDataURL();
		var dataURL2 = canvas2.toDataURL();
		var dataURL3 = canvas3.toDataURL();
		
		
		var pdf = new jsPDF();
		pdf.addImage(dataURL, 'JPEG', 55, 0, 0, 0);
		pdf.addImage(dataURL2, 'JPEG', 10, 100,200,90);
		pdf.addImage(dataURL3, 'JPEG', 10, 200,200,100); 
		pdf.save("download.pdf");
		
            
        });
	
	
	function toolTipContent(e) {
	var str = "";
	var total = 0;
	var str2, str3;
	for (var i = 0; i < e.entries.length; i++){
		var  str1 = "<span style= \"color:"+e.entries[i].dataSeries.color + "\"> "+e.entries[i].dataSeries.name+"</span>: <strong>"+e.entries[i].dataPoint.y+"</strong><br/>";
		total = e.entries[i].dataPoint.y + total;
		str = str.concat(str1);
	}
	str2 = "<span style = \"color:DodgerBlue;\"><strong>"+(e.entries[0].dataPoint.x).getDate()+"-"+(e.entries[0].dataPoint.x).getMonth()+"-"+(e.entries[0].dataPoint.x).getFullYear()+"</strong></span><br/>";
	total = Math.round(total * 100) / 100;
	str3 = "<span style = \"color:Tomato\">Total:</span><strong> "+total+"</strong><br/>";
	return (str2.concat(str)).concat(str3); 
}
	 });
	  
	


window.onload = function () {
	var destination = "<?php echo base_url(); ?>dnd_controllers/campaign/getJsonCampaignsCountByDept/"; 
	var dataPoints2;
	
	
	
	
	
	//alert(destination);
	$.ajax({
		method:"POST",
		dataType:"json",
		url:destination,
		data:{brand_name:"airtel",
			duration:"monthly"},
		success: function(data) {
			
			dataPoints2=data;
			var chart = new CanvasJS.Chart("campaignbydeptpiechart",
	{
		theme: "theme2",
		title:{
			text: "" 
		},
		data: [
		{
			type: "pie",
			showInLegend: true,
			toolTipContent: "{y}",
			yValueFormatString: "",
			legendText: "{indexLabel}",
			dataPoints: dataPoints2
		}
		]
	});
	
	
	chart.render();
			
				   
		}
	});
	
	
	
	
		var destination3 = "<?php echo base_url(); ?>dnd_controllers/campaign/getJsonCampaignsCountByBrandName/"; 
	
	
	
	
	
	
	//alert(destination);
	$.ajax({
		method:"POST",
		dataType:"json",
		url:destination3,
		data:{brand_name:"airtel",
			duration:"monthly"},
		success: function(data) {
			
			
			
   for (var keys in  data) {
	   data[keys].x=new Date(data[keys].x);
	   data[keys].y=parseInt(data[keys].y);
	   
   }
   
   var chart2 = new CanvasJS.Chart("campaignbybrandlinechart", {
	animationEnabled: true,  
	title:{
		text: "Campaign Count  by BrandName and Day"
	},
	axisX: {
		interval: 1,
		intervalType: "day"
	},
	axisY: {
		title: "Number of Campaigns",
		valueFormatString: "#0,,.",
		suffix: "",
		stripLines: [{
			value: 3366500,
			label: "Average"
		}]
	},
	data: [{
		yValueFormatString: "#,### Campaigns",
		xValueFormatString: "DDDD MMMM YYYY",
		type: "spline",
		dataPoints:data
	}]
});
chart2.render();
   
			
	
	
	
			
				   
		}
	});
	
	
	var destination2 = "<?php echo base_url(); ?>dnd_controllers/campaign/getJsonCampaignsCountByDeptAndDuration/";
	var report_duration = $('#report_duration').val();
	
	$.ajax({
		method:"POST",
		dataType:"json",
		url:destination2,
		data:{brand_name:"airtel",duration:"monthly"},
		success: function(data2) {
			
			for (var key in  data2) {
   // if (data2.dataPoints.hasOwnProperty(key)) {
	var datapoint=data2[key].dataPoints;
   for (var keys in  datapoint) {
	   datapoint[keys].x=new Date(datapoint[keys].x);
	   
	   
   }
   // }
}





var chart3 = new CanvasJS.Chart("chartContainer", { 
	animationEnabled: true,
	title:{
		text: "Robi Contact Policy Campaign History",
		fontFamily: "arial black",
		fontColor: "#695A42"
	},
	axisX: {
		interval: 1,
		intervalType: "day"
	},
	axisY:{
		valueFormatString:"#",
		gridColor: "#B6B1A8",
		tickColor: "#B6B1A8"
	},
	toolTip: {
		shared: true,
		content: toolTipContent
	},
	data: data2
});
chart3.render();
				   
		}
	});
	
	
	




function toolTipContent(e) {
	var str = "";
	var total = 0;
	var str2, str3;
	for (var i = 0; i < e.entries.length; i++){
		var  str1 = "<span style= \"color:"+e.entries[i].dataSeries.color + "\"> "+e.entries[i].dataSeries.name+"</span>: <strong>"+e.entries[i].dataPoint.y+"</strong><br/>";
		total = e.entries[i].dataPoint.y + total;
		str = str.concat(str1);
	}
	str2 = "<span style = \"color:DodgerBlue;\"><strong>"+(e.entries[0].dataPoint.x).getDate()+"-"+(e.entries[0].dataPoint.x).getMonth()+"-"+(e.entries[0].dataPoint.x).getFullYear()+"</strong></span><br/>";
	total = Math.round(total * 100) / 100;
	str3 = "<span style = \"color:Tomato\">Total:</span><strong> "+total+"</strong><br/>";
	return (str2.concat(str)).concat(str3); 
}
  }
  
	
	

	
	

</script>

       


