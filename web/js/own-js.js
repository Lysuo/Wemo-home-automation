function ajaxCall() {
	
	$.ajax({
        type: 'GET' ,
        url: "getState.php",
        contentType: "application/x-www-form-urlencoded;charset=utf-8",
		data:{'ip': $('#ip_wha').val()},
        datatype: "json",
        async: true,
	
	success : function(data) {
	  data=JSON.parse(data);
	  console.log(data);
	  
	  $('#results').text("");
	  var res = "";
	  $.each(data, function(i, item){
        res += "<strong>" +i+ "</strong>: " + item + " <br/>";
		if (i=="State") {
			if (item == "ON") {
				$('#switch').attr('style', 'display: visible;');
				$('#switch').attr('value', 'Turn OFF');
			} else if (item == "OFF") {
				$('#switch').attr('style', 'display: visible;');
				$('#switch').attr('value', 'Turn ON');
			} else {
				$('#switch').attr('style', 'display: none;');
			}
		}
      });
	  
	  $(res).appendTo('#results');  
	  
	  
	},	
	fail : function() {
		alert( "error" );
	},	
	always : function() {
		alert( "finished" );
	}
	});
}


function switchState() {
	var state = $('#switch').val();
	var url = "";
	console.log(state);
	
	if (state == "Turn ON") {
		url = "turnOn.php";
	} else if (state == "Turn OFF") {
		url = "turnOff.php";
	}
	switchWemo(url);
}
	
function switchWemo(urlSwitch) {
		
	$.ajax({
        type: 'GET' ,
        url: urlSwitch,
        contentType: "application/x-www-form-urlencoded;charset=utf-8",
		data:{'ip': $('#ip_wha').val()},
        datatype: "json",
        async: true,
	
	success : function(data) {
	  data=JSON.parse(data);
	  console.log(data);
	  
	  $('#results').text("");
	  var res = "";
	  $.each(data, function(i, item){
        res += "<strong>" +i+ "</strong>: " + item + " <br/>";
		if (i=="State") {
			if (item == "ON") {
				$('#switch').attr('style', 'display: visible;');
				$('#switch').attr('value', 'Turn OFF');
			} else if (item == "OFF") {
				$('#switch').attr('style', 'display: visible;');
				$('#switch').attr('value', 'Turn ON');
			} else {
				$('#switch').attr('style', 'display: none;');
			}
		}
      });
	  
	  $(res).appendTo('#results');  
	  
	  
	},	
	fail : function() {
		alert( "error" );
	},	
	always : function() {
		alert( "finished" );
	}
	});
}
