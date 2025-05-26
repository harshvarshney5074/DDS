$(document).ready(function(){
	$.ajax({
		url:"http://localhost/DDS1/reports/charts/data3.php",
		method:"GET",
		success:function(data){
			console.log(data);
			var name=[];
			var age=[];
			for(var i in data){
				name.push(data[i].Document_type);
				age.push(data[i].count);
			}
			var chartdata={
				labels:name,
				datasets:[
				{
					label:'Document-type Count',
					backgroundColor:'rgba(0,76,153,0.75)',
					borderColor:'rgba(0,76,153,0.75)',
					hoverBackgroundColor:'rgba(0,76,153,1)',
					hoverBorderColor:'rgba(0,76,153,1)',
					data:age
				}
			]
			};
			var ctx=$('#mycanvas3');
			var barGraph=new Chart(ctx,{
				type:'bar',
				data:chartdata
			});
		},
		error:function(data){
			consol.log(data);
		}
	});
});