$(function(){
	
	var note = $('#note'),
		ts = new Date(2012, 0, 1),
		newYear = true;
	
	if((new Date()) > ts){
		// The new year is here! Count towards something else.
		// Notice the *1000 at the end - time must be in milliseconds
		ts = (new Date()).getTime() + 60*24*60*60*1000;
		newYear = false;
	}
		
	$('#countdown').countdown({
		timestamp	: ts,
		callback	: function(days, hours, minutes, seconds){
			
			var message = "";
			
			message += days + " ngày" + ( days==1 ? '':'' ) + ", ";
			message += hours + " giờ" + ( hours==1 ? '':'' ) + ", ";
			message += minutes + " phút" + ( minutes==1 ? '':'' ) + " và ";
			message += seconds + " giây" + ( seconds==1 ? '':'' ) + " <br />";
			
			if(newYear){
				message += "left until the new year!";
			}
			else {
				message += "Chúng tôi sẽ trở lại sau 60 ngày nữa!";
			}
			
			note.html(message);
		}
	});
	
});
