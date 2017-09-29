// Variable to hold request
var request;
var events_list;

// Bind to the submit event of our form
$("#filters").change(function(event){
	loadData();
    // Prevent default posting of form
    event.preventDefault();
    
});

function loadData()
{

    // Abort any pending request
    if (request) {
        request.abort();
    }
    
    // Fire off the request to /form.php
    request = $.ajax({
        url: "/ajax/event/" + event_id,
        type: "post"
    });
    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
    	if (validate(response) == false) return false;
        // Log a message to the console
    	console.log(response);
        htmlFromResponse(response);
        events_list = response['events'];
    });

    // Callback handler that will be called on failure
    request.fail(function (jqXHR, textStatus, errorThrown){
    	alert("Problem z komunikacją, spróbuj odświeżyć stronę.");
        console.error(
            "The following error occurred: "+
            textStatus, errorThrown
        );
    });

    // Callback handler that will be called regardless
    // if the request failed or succeeded
    request.always(function () {
        // Reenable the inputs
        $inputs.prop("disabled", false);
    });

}
function htmlFromResponse(response){
	

	
	var somvar = $("#event_table").html();
	$('#event_slot').html($('#event_slot_template').html());
	console.log(somvar);
	var responseLength = response['data'].length;
	for (var i = 0; i < responseLength; i++) {
		console.log(response['data'][i]);
		
		section = '<div class="panel panel-default">'+
		  		  '<div class="panel-heading">'+response['data'][i]['section_name']+'</div>'+
		  		  '<ul class="list-group">';
		  		  var slots_num = response['data'][i]['members'].length;
				  for (var j = 0; j < slots_num; j++) 
				  {
					  section +='<li style="display: flex; justify-content: space-around;" class="list-group-item">'+response['data'][i]['members'][j]['function'] + ' ';
					  if (response['data'][i]['members'][j]['player'] != null)  
						  section += response['data'][i]['members'][j]['player'] + ' ';
					  else section += "Wolny "
						  
					  if (response['data'][i]['members'][j]['can_join']) 
					  {
						  section +="<a href='javascript:joinSlot("+response['data'][i]['members'][j]['slot_id']+")'>" +
						  		'<button type="button" class="btn btn-primary">Wpisz się</button></a>';
					  }
					  if (response['data'][i]['members'][j]['can_exit']) 
					  {
						  section +="<a href='javascript:exitSlot("+response['data'][i]['members'][j]['slot_id']+")'>" +
						  		'<button type="button" class="btn btn-warning">Wypisz</button></a>';
					  }
					  section +='</li>';
				  }
		section +='</ul>'+
		  		  '</div>';
	   
		
		$('#event_slot').append(section);
	}
	
}
function validate(response)
{
	 if (response['result'] == "error") 
	 {
		 alert(response['error_msg'])
		 return false;
	 }

    return true;
}

function exitSlot(slot_id)
{
    if (request) {
        request.abort();
    }
    
    // Fire off the request to /form.php
    request = $.ajax({
    	url: "/ajax/event/"+event_id+"/exit/" + slot_id,
        type: "post"
    });
    
    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
    	if (validate(response) == false) return false;
        // Log a message to the console
    	console.log(response);
    	loadData();
        });

    // Callback handler that will be called on failure
    request.fail(function (jqXHR, textStatus, errorThrown){
    	alert("Problem z komunikacją, spróbuj odświeżyć stronę.");
        console.error(
            "The following error occurred: "+
            textStatus, errorThrown
        );
    });
    
}

function joinSlot(slot_id)
{
    if (request) {
        request.abort();
    }
    
    // Fire off the request to /form.php
    request = $.ajax({
    	url: "/ajax/event/"+event_id+"/join/" + slot_id,
        type: "post"
    });
    
    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
    	if (validate(response) == false) return false;
        // Log a message to the console
    	console.log(response);
    	loadData();
        });

    // Callback handler that will be called on failure
    request.fail(function (jqXHR, textStatus, errorThrown){
    	alert("Problem z komunikacją, spróbuj odświeżyć stronę.");
        console.error(
            "The following error occurred: "+
            textStatus, errorThrown
        );
    });
    
}

