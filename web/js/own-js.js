var devices = {};

$(function(){

	devices["device1"] = "192.168.0.103";
	devices["device2"] = "192.168.0.102";
	getState("device1", devices["device1"]);
	getState("device2", devices["device2"]);
	
}); 


function getState(deviceName, ip) {
	
	$.ajax({
        type: 'GET' ,
        url: "api/getState.php",
        contentType: "application/x-www-form-urlencoded;charset=utf-8",
		data:{'ip': ip},
        datatype: "json",
        async: true,
	
	success : function(data) {
	  data=JSON.parse(data);
	  console.log(data);
	  
	  
	  appendHTMLDevice(deviceName);
	  
	  $('#results-'+deviceName).text("");
	  var res = "";
	  $.each(data, function(i, item){
        res += "<strong>" +i+ "</strong>: " + item + " <br/>";
		if (i=="State") {
			if (item == "ON") {
				$('#switch-'+deviceName).attr('style', 'display: visible;');
				$('#switch-'+deviceName).attr('value', 'Turn OFF');
			} else if (item == "OFF") {
				$('#switch-'+deviceName).attr('style', 'display: visible;');
				$('#switch-'+deviceName).attr('value', 'Turn ON');
			} else {
				$('#switch-'+deviceName).attr('style', 'display: none;');
			}
		}
      });
	  
	  $(res).appendTo('#results-'+deviceName);
	  
	},	
	fail : function() {
		alert( "error" );
	},	
	always : function() {
		alert( "finished" );
	}
	});
}


function appendHTMLDevice(device) {
	var html = '<br />';
	html += '<div class="panel panel-default"><div class="panel-body">';
	html += '	<span id="results-'+device+'"></span>	<br />';
	html += '	<input type="button" id="switch-'+device+'" value="Turn ON" onclick="switchState(this)" style="display: none;"/>';
	html += '</div></div>';
	
	$(html).appendTo('#container-main');
}


function switchState(button) {
		var state = $('#'+button.id).val();
		var deviceName = button.id.substring(7);		
		
		$('#'+button.id).attr('disabled', true);
		var url = "api/";
		
		if (state == "Turn ON") {
			url += "turnOn.php";
		} else if (state == "Turn OFF") {
			url += "turnOff.php";
		}
		switchWemo(url, deviceName);
}
	
function switchWemo(urlSwitch, deviceName) {	
		
	$.ajax({
        type: 'GET' ,
        url: urlSwitch,
        contentType: "application/x-www-form-urlencoded;charset=utf-8",
		data:{'ip': devices[deviceName]},
        datatype: "json",
        async: true,
	
	success : function(data) {
	  data=JSON.parse(data);
	  
	  $('#results-'+deviceName).text("");
	  var res = "";
	  $.each(data, function(i, item){
        res += "<strong>" +i+ "</strong>: " + item + " <br/>";
		if (i=="State") {
			$('#switch-'+deviceName).attr('disabled', false);
			if (item == "ON") {
				$('#switch-'+deviceName).attr('style', 'display: visible;');
				$('#switch-'+deviceName).attr('value', 'Turn OFF');
			} else if (item == "OFF") {
				$('#switch-'+deviceName).attr('style', 'display: visible;');
				$('#switch-'+deviceName).attr('value', 'Turn ON');
			} else {
				$('#switch-'+deviceName).attr('style', 'display: none;');
			}
		}
      });
	  
	  $(res).appendTo('#results-'+deviceName); 	  
	  //getState();	  
	},	
	fail : function() {
		alert( "error" );
	},	
	always : function() {
		alert( "finished" );
	}
	});
}
