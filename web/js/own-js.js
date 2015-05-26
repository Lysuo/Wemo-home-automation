var devices = {};
var devicesRes;

$(function(){
	getDevices();
	//getState("device1", "192.168.0.100");
}); 

function getDevices() {
	$.ajax({
        type: 'GET' ,
        url: "api/getDevices.php",
        contentType: "application/x-www-form-urlencoded;charset=utf-8",
		data:{},
        datatype: "json",
        async: true,
	
	success : function(data) {
		devicesRes=JSON.parse(data);	
		
		for (var i = 0, dev; i < devicesRes.device.length; i++) {
		   dev = devicesRes.device[i];
		   devices[ dev.id ] = dev;
		}
		
		console.log(devices);		
		
		for (var i in devices) {
		   getState(i, devices[i].ip, devices[i].port);
		}
	
	},	
	fail : function() {
		alert( "error" );
	},	
	always : function() {
		alert( "finished" );
	}
	});
}

function getState(deviceID, ip, port) {
	
	$.ajax({
        type: 'GET' ,
        url: "api/getState.php",
        contentType: "application/x-www-form-urlencoded;charset=utf-8",
		data:{'ip': ip, 'port': port},
        datatype: "json",
        async: true,
	
	success : function(data) {
	  data=JSON.parse(data);
	  console.log(data);	  
	  
	  appendHTMLDevice(deviceID);
	  
	  $('#results-'+deviceID).text("");
	  var res = "";
	  $.each(data, function(i, item){
        res += "<strong>" +i+ "</strong>: " + item + " <br/>";
		if (i=="State") {
			if (item == "ON") {
				$('#switch-'+deviceID).attr('style', 'display: visible;');
				$('#switch-'+deviceID).attr('value', 'Turn OFF');
			} else if (item == "OFF") {
				$('#switch-'+deviceID).attr('style', 'display: visible;');
				$('#switch-'+deviceID).attr('value', 'Turn ON');
			} else {
				$('#switch-'+deviceID).attr('style', 'display: none;');
			}
		}
      });
	  
	  $(res).appendTo('#results-'+deviceID);
	  
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
		var deviceID = button.id.substring(7);		
		
		$('#'+button.id).attr('disabled', true);
		var url = "api/";
		
		if (state == "Turn ON") {
			url += "turnOn.php";
		} else if (state == "Turn OFF") {
			url += "turnOff.php";
		}
		switchWemo(url, deviceID);
}
	
function switchWemo(urlSwitch, deviceID) {	
		
	$.ajax({
        type: 'GET' ,
        url: urlSwitch,
        contentType: "application/x-www-form-urlencoded;charset=utf-8",
		data:{'ip': devices[deviceID].ip, 'port': devices[deviceID].port},
        datatype: "json",
        async: true,
	
	success : function(data) {
	  data=JSON.parse(data);
	  
	  $('#results-'+deviceID).text("");
	  var res = "";
	  $.each(data, function(i, item){
        res += "<strong>" +i+ "</strong>: " + item + " <br/>";
		if (i=="State") {
			$('#switch-'+deviceID).attr('disabled', false);
			if (item == "ON") {
				$('#switch-'+deviceID).attr('style', 'display: visible;');
				$('#switch-'+deviceID).attr('value', 'Turn OFF');
			} else if (item == "OFF") {
				$('#switch-'+deviceID).attr('style', 'display: visible;');
				$('#switch-'+deviceID).attr('value', 'Turn ON');
			} else {
				$('#switch-'+deviceID).attr('style', 'display: none;');
			}
		}
      });
	  
	  $(res).appendTo('#results-'+deviceID); 	  
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
