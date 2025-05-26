$(document).ready(function(){
	$.ajax({
		url:"http://localhost/DDS1/reports/charts/data5.php",
		method:"GET",
		success:function(data){
			console.log(data);
			var name=[];
			var age=[];
			for(var i in data){
				name.push(data[i].Discipline);
				age.push(data[i].count);
			}
			var chartdata={
				labels:name,
				datasets:[
				{
					label:'Discipline Count',
					backgroundColor:'rgba(0,76,153,0.75)',
					borderColor:'rgba(0,76,153,0.75)',
					hoverBackgroundColor:'rgba(0,76,153,1)',
					hoverBorderColor:'rgba(0,76,153,1)',
					data:age
				}
			]
			};
			var ctx=$('#mycanvas5');
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