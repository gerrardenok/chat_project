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
	  var dd = date.getDate()
	  if ( dd < 10 ) dd = '0' + dd;
	  var mm = date.getMonth()+1
	  if ( mm < 10 ) mm = '0' + mm;
	  var yy = date.getFullYear() % 100;
	  if ( yy < 10 ) yy = '0' + yy;
	  return dd+'.'+mm+'.'+yy+' '+date.getHours()+':'+date.getMinutes();
	}

	return formatDate(date);
}

function clearChatbox (argument) {
	$('#chatbox').empty();
}

function drawUser (user) {
	if (user.status == 1) {
		$('<div class="user online" title="'+user.login+'" id="'+user.id+'">'+userShorterName(user.login)+'</div>').appendTo('#peoplebox');	
	} else {
		$('<div class="user offline" title="'+user.login+'" id="'+user.id+'">'+userShorterName(user.login)+'</div>').appendTo('#peoplebox');
	}
}

function drawUsers (users) {
	for(i = 0; i < users.length; i++) {
		drawUser(users[i]);
	}
}

function clearUsers() {
	$('#peoplebox').empty();
}


function clearMessege(messege) {
	if (messege.length > 255) {
		return messege.substr(0, 255); 
	} 
	return messege;
}
		
