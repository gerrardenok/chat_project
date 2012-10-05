function goToServer(data, func, datatype) {
	$.ajax({
	type: "POST",
	url: "ajax.php",
	data: data,
	success: function(response){
		switch (datatype) {
			case 'json': {
				// console.log(response);
				var result = eval(response);
				func(result);
				break;
			};
			default : break;

			}
		}
	});
}	

function userShorterName (name) {
	if (name.length >= 8) {
		return name.substr(0, 8)+'...';
	} 
	return name;
}


function getCurrentTime() {
	var date = new Date();
	function formatDate(date) {
		var date = date.getUTCFullYear() + '-' + ('00' + (date.getUTCMonth()+1)).slice(-2) + 
		'-' + '0' + date.getUTCDate() + ' ' + ('00' + date.getUTCHours()).slice(-2) + ':' + ('00' + date.getUTCMinutes()).slice(-2) + 
		':' + ('00' + date.getUTCSeconds()).slice(-2);
		return date;
	}

	return formatDate(date);
}

function clearChatbox (argument) {
	$('#chatbox').empty();
}

// function drawUser (user) {
// 	if (user.status == 1) {
// 		$('<div class="user online" title="'+user.login+'" id="'+user.id+'">'+userShorterName(user.login)+'</div>').appendTo('#peoplebox');	
// 	} else {
// 		$('<div class="user offline" title="'+user.login+'" id="'+user.id+'">'+userShorterName(user.login)+'</div>').appendTo('#peoplebox');
// 	}
// }

// function drawUsers (users) {
// 	for(i = 0; i < users.length; i++) {
// 		drawUser(users[i]);
// 	}
// }

function clearUsers() {
	$('#peoplebox').empty();
}

function showActiveUser(id) {
	var currentClass = $('#'+id).attr('class');
	console.log(currentClass);
	$('#'+id).addClass(currentClass+' '+'active');
}

function hideActiveUser(id) {
	$('#'+id).removeClass('active');
}


function clearMessege(messege) {
	if (messege.length > 255) {
		return messege.substr(0, 255); 
	} 
	return messege;
}
		
