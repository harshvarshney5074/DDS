$(document).ready(function(){
	$.ajax({
		url:"http://localhost/DDS1/reports/charts/data1.php",
		method:"GET",
		success:function(data){
			console.log(data);
			var name=[];
			var age=[];
			for(var i in data){
				name.push(data[i].institute_name);
				age.push(data[i].receive_count);
			}
			var chartdata={
				labels:name,
				datasets:[
				{
					label:'Receive Count',
					backgroundColor:'rgba(0,76,153,0.75)',
					borderColor:'rgba(0,76,153,0.75)',
					hoverBackgroundColor:'rgba(0,76,153,1)',
					hoverBorderColor:'rgba(0,76,153,1)',
					data:age
				}
			]
			};
			var ctx=$('#mycanvas1');
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