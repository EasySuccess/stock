function UpdateCourse( _std_id , _c_id ){
	alert("test2");	
	if(_c_id=='-1'){
		alert("請選擇課程!");
	}else{
		$.ajax({
				url:'events_OK.php',
				type:'get',
				data:{
					action:"update",
					std_id: _std_id,
					c_id: _c_id
				},
				success:function(txt){
					alert("test");	
					//setTimeout("location.href='index.php'",5000);
				}
		});
	}
}