<script type="text/javascript">		
	var xmlHttp;

	function ajaxPost(request = "", count = 0) {
		error.innerHTML = "";
		try{
			xmlHttp = new XMLHttpRequest();
			} catch(e){
			try{
			xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
				} catch(e){
					try{
					xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
					} catch(e){
				alert("Error!");
				return;
				}
			}
		}
		xmlHttp.onreadystatechange = function() {
			if(xmlHttp.readyState == 4){
				// Validation variables
				// An error text can be used for real-timefeedback to users and for debugging
				
				alert(xmlHttp.responseText);
				// error.innerHTML = xmlHttp.responseText;
				var duce = jQuery.parseJSON(xmlHttp.responseText);

                //innerHTML doesn't work with Chrome, so jQuery is used
				if (duce.success != true)
					$("#error").html(duce.errorType);
				else
                    $("#error").html("Please wait...");
				
				if (duce.redirect != "no")
				{
					// This page should redirect users if inserting is successful
					window.location = duce.redirect;
				}
			}
		}

        double_confirm = 'no';
        single_confirm = 'no';

        controller = getAllUrlParams().controller;
        action = getAllUrlParams().action;

        switch (controller) {
        case 'game_menu':
            switch (action) {
            case 'setting':
                        query_php = "models/game_menu/query_user_update.php";
                        post_parameter = "&username=" + document.getElementById("username").innerHTML + "&email=" + document.getElementById("email").value + "&nickname=" + document.getElementById("nickname").value;
            break;
            case 'setting_session':
                switch (request) {
                case 'destroy':
                    query_php = "models/game_menu/query_session_destroy.php";
                    post_parameter = "session_id=" + document.getElementById("sid"+count).innerHTML;
                break;
                case 'destroyall':
                    single_confirm = 'yes';
                    single_confirm_text = "You are about to destroy all sessions from other locations!";
                    query_php = "models/game_menu/query_session_destroyall.php";
                    post_parameter = "";
                break;
                }
            break;
            }
        break;
        case 'admin':
            switch (action) {
            case 'ip_block':
                switch (request) {
                case 'update':
                    query_php = "models/admin/query_ip_block_update.php";
                    post_parameter = "ip=" + document.getElementById("ipname"+count).innerHTML + "&ban_time=" + document.getElementById("datepicker"+count).value + "&remarks=" + document.getElementById("remarks"+count).value;
                break;
                case 'delete':
                    single_confirm = 'yes';
                    single_confirm_text = "You are about to lift IP block for "+document.getElementById("ipname"+count).innerHTML;
                    query_php = "models/admin/query_ip_block_delete.php";
                    post_parameter = "ip=" + document.getElementById("ipname"+count).innerHTML;
                break;
                case 'create':
                    query_php = "models/admin/query_ip_block_create.php";
                    post_parameter = "ip=" + document.getElementById("ipname0").value + "&ban_time=" + document.getElementById("datepicker0").value + "&remarks=" + document.getElementById("remarks0").value;
                break;
                case 'purge':
                    single_confirm = 'yes';
                    single_confirm_text = "You are about to purge all expired IP blocks with no remarks!";
                    query_php = "models/admin/query_ip_block_purge.php";
                    post_parameter = "";
                break;
                }
            break;
            case 'user':
                switch (request) {
                    case 'ban':
                        single_confirm = 'yes';
                        single_confirm_text = "You are about to manage ban for user "+document.getElementById("username"+count).value;
                        query_php = "models/admin/query_user_ban.php";
                        post_parameter = "uid=" + document.getElementById("uid"+count).innerHTML + "&ban_time=" + document.getElementById("datepicker"+count).value;
                    break;
                    case 'update':
                        single_confirm = 'yes';
                        single_confirm_text = "You are about to update user "+document.getElementById("username"+count).value;
                        query_php = "models/admin/query_user_update.php";
                        post_parameter = "uid=" + document.getElementById("uid"+count).innerHTML + "&username=" + document.getElementById("username"+count).value + "&email=" + document.getElementById("email"+count).value + "&nickname=" + document.getElementById("nickname"+count).value;
                    break;
                    case 'role':
                        double_confirm = 'yes';
                        single_confirm_text = "You are about to change role for user "+document.getElementById("username"+count).value+" to "+document.getElementById("role"+count).value;
                        double_confirm_text = "Are you sure?";
                        query_php = "models/admin/query_user_role.php";
                        post_parameter = "uid=" + document.getElementById("uid"+count).innerHTML + "&role=" + document.getElementById("role"+count).value;
                    break;
                    case 'resetticks':
                        single_confirm = 'yes';
                        single_confirm_text = "Set current AP and HP of user "+document.getElementById("username"+count).value+" to full.";
                        query_php = "models/admin/query_user_resetticks.php";
                        post_parameter = "uid=" + document.getElementById("uid"+count).innerHTML;
                    break;
                }
            break;
            case 'session':
                switch (request) {
                    case 'destroy':
                        query_php = "models/admin/query_session_destroy.php";
                        post_parameter = "session_id=" + document.getElementById("sid"+count).innerHTML + "&target_uid=" + document.getElementById("uid"+count).innerHTML;
                    break;
                    case 'destroyall':
                        single_confirm = 'yes';
                        single_confirm_text = "You are about to destroy all sessions for user "+document.getElementById("username").innerHTML;
                        query_php = "models/admin/query_session_destroyall.php";
                        post_parameter = "target_uid=" + document.getElementById("uid").innerHTML;
                    break;
                    case 'search':
                        query_php = "models/admin/query_session_search.php";
                        post_parameter = "cred=" + document.getElementById("cred").value + "&limit=" + document.getElementById("limit").value + "&offset=" + document.getElementById("offset").value;
                    break;
                }
            break;
            case 'user_log':
            break;
            }
        break;
        case 'portal':
        default:
            switch (action) {
                case 'register':
                    query_php = "models/portal/query_register.php";
                    post_parameter = "username=" + join.inputUser.value + "&password=" + join.inputPassword.value + "&email=" + join.inputEmail.value + "&nickname=" + join.inputNickname.value;
                break;
                case 'home':
                default:
                    query_php = "models/portal/query_signin.php";
                    post_parameter = "cred=" + signin.inputCred.value + "&password=" + signin.inputPassword.value;
                break;
            }
        break;
        }

        if (double_confirm == 'yes') {
            if (confirm (single_confirm_text)) {
                if (confirm (double_confirm_text)) {
                    xmlHttp.open("POST", query_php, true);
                    xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                    // Get value of fields directly from using var count
                    xmlHttp.send(post_parameter);
                }
            }
        }
        else if (single_confirm == 'yes') {
            if (confirm (single_confirm_text)) {
                xmlHttp.open("POST", query_php, true);
                xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                // Get value of fields directly from using var count
                xmlHttp.send(post_parameter);
            }
        }
        else {
			xmlHttp.open("POST", query_php, true);
			xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			// Get value of fields directly from using var count
			xmlHttp.send(post_parameter);
        }
	};

    // Code snippet by Yaphi Berhanu
    // https://www.sitepoint.com/get-url-parameters-with-javascript/
    function getAllUrlParams(url) {

        // get query string from url (optional) or window
        var queryString = url ? url.split('?')[1] : window.location.search.slice(1);

        // we'll store the parameters here
        var obj = {};

        // if query string exists
        if (queryString) {

        // stuff after # is not part of query string, so get rid of it
        queryString = queryString.split('#')[0];

        // split our query string into its component parts
        var arr = queryString.split('&');

        for (var i=0; i<arr.length; i++) {
            // separate the keys and the values
            var a = arr[i].split('=');

            // in case params look like: list[]=thing1&list[]=thing2
            var paramNum = undefined;
            var paramName = a[0].replace(/\[\d*\]/, function(v) {
            paramNum = v.slice(1,-1);
            return '';
            });

            // set parameter value (use 'true' if empty)
            var paramValue = typeof(a[1])==='undefined' ? true : a[1];

            // (optional) keep case consistent
            paramName = paramName.toLowerCase();
            paramValue = paramValue.toLowerCase();

            // if parameter name already exists
            if (obj[paramName]) {
            // convert value to array (if still string)
            if (typeof obj[paramName] === 'string') {
                obj[paramName] = [obj[paramName]];
            }
            // if no array index number specified...
            if (typeof paramNum === 'undefined') {
                // put the value on the end of the array
                obj[paramName].push(paramValue);
            }
            // if array index number specified...
            else {
                // put the value at that index number
                obj[paramName][paramNum] = paramValue;
            }
            }
            // if param name doesn't exist yet, set it
            else {
            obj[paramName] = paramValue;
            }
        }
        }

        return obj;
    }

    
</script>