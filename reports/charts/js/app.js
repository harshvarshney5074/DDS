$(document).ready(function(){
	$.ajax({
		url:"http://localhost/DDS1/charts/data.php",
		method:"GET",
		success:function(data){
			console.log(data);
			var name=[];
			var age=[];
			for(var i in data){
				name.push(data[i].Journal_name);
				age.push(data[i].Count);
			}
			var chartdata={
				labels:name,
				datasets:[
				{
					label:'Article Count',
					backgroundColor:'rgba(0,76,153,0.75)',
					borderColor:'rgba(200,200,200,0.75)',
					hoverBackgroundColor:'rgba(200,200,200,1)',
					hoverBorderColor:'rgba(200,200,200,1)',
					data:age
				}
			]
			};
			var ctx=$('#mycanvas');
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