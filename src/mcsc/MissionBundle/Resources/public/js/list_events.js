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
    // setup some local variables
    var $form = $("#filters");

    // Let's select and cache all the fields
    var $inputs = $form.find("input, select, button, textarea, checkbox");

    // Serialize the data in the form
    var serializedData = $form.serialize();

    // Let's disable the inputs for the duration of the Ajax request.
    // Note: we disable elements AFTER the form data has been serialized.
    // Disabled form elements will not be serialized.
    $inputs.prop("disabled", true);

    // Fire off the request to /form.php
    request = $.ajax({
        url: "/ajax/events_list",
        type: "post",
        data: serializedData
    });
    console.log(serializedData);
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
        // Log the error to the console
    	alert("Problem z komunikacją, spróbuj odświeżyć stronę.")
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
	$('#event_table').html($('#event_table_template').html());
	console.log(somvar);
	var responseLength = response['events'].length;
	for (var i = 0; i < responseLength; i++) {
		console.log(response['events'][i]);
		
		newRow = "<tr>" +
        "<td>" + response['events'][i]['name'] +"</td>" +
        "<td>" + response['events'][i]['author'] +"</td>" +
        "<td>" + response['events'][i]['game'] +"</td>" +
        "<td>" + response['events'][i]['type'] +"</td>" +
        "<td>" + response['events'][i]['start_date']['human'] +"</td>" +
        "<td>" + response['events'][i]['dead_line']['human'] +"</td>" +
        "<td>" + response['events'][i]['free_slots'] +"/" + response['events'][i]['slots']+ "</td>" +
        "<td>" + "<a href='" + response['events'][i]['url']+"'>" +
        		'<button type="button" class="btn btn-link">Wyświetl</button></a>'
        if (response['events'][i]['can_edit'])  newRow += "<a href='" + response['events'][i]['edit_url']+"'>" +
        		'<button type="button" class="btn btn-link">Edytuj</button></a>';
        newRow += "</td></tr>";
	   
		
		$('#event_table tbody').append(newRow);
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

