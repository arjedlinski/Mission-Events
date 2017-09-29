var Callsign_NATO = ['Alpha','Bravo','Charlie','Delta','Echo','Foxtrot','Golf','Hotel','India','Juliett ','Kilo','Lima','Mike','November','Oscar','Papa','Quebec','Romeo','Sierra','Tango',
                    'Uniform','Victor','Whiskey','X-Ray', 'Yankee', 'Zulu'];
var Callsign_RU = ['Moska', 'Putin']; //
 
var Function = ['Operator AT', 'Operator'];
 
var Degree = ['Szeregowy', 'Operator'];
 
var Callsign = Callsign_NATO.concat(Callsign_RU);

function removeButtonClicked(button)
{
        $collectionHolder = button.parent().parent().remove();
}

function addSectionButtons(selector)
{
	$(selector).parent().append('<button type="button" class="btn btn-primary"'+
								'onclick="addButtonClicked($(this));">Dodaj slot</button>');
	
	$(selector).parent().append('<button type="button" class="btn btn-danger button_remove_section"'+
								'">Usuń sekcje</button>');
}

function addSlotButtons(selector)
{
	$(selector).parent().append('<button type="button" class="btn btn-danger"'+ 
								'onclick="removeButtonClicked($(this));">Usuń slot</button>');
}

function addButtonClicked(button)
{
           var $collectionHolder = button.parent();
           $collectionHolder = $collectionHolder.children().children();
           var prototype = $collectionHolder.data('prototype');
           index = $collectionHolder.parent().parent().parent().parent().parent().parent().find(':input').length + 1
           var is_slot;
           
           if ($collectionHolder.prop('id') == "MissionInfo_missionSection")
           {
                   var newForm = prototype;
                   newForm = replaceAll(newForm, "MissionInfo[missionSection][__name__][section_name]",
                                   "MissionInfo[missionSection]["+index+"][section_name]");
                   
                   newForm = replaceAll(newForm, "MissionInfo[missionSection][__name__][missionSlots]",
                                   "MissionInfo[missionSection]["+index+"][missionSlots]");
 
                   newForm = replaceAll(newForm, "missionSection___name___missionSlots",
                                   "missionSection_"+index+"_missionSlots");
                       
                   newForm = replaceAll(newForm, "MissionInfo_missionSection___name___section_name",
                                   "MissionInfo_missionSection_"+index+"_section_name");
                     
                   is_slot = false;                                
           }
           else
           {
                   var newForm = prototype;
                   newForm =  replaceAll(newForm, "__name__", index);
                   is_slot = true;
           }
   
           button.parent().children().children().append(newForm);
           if (is_slot) addSlotButtons("[id$=missionSlots_"+index+"_function]")
           else addSectionButtons('#MissionInfo_missionSection_'+index+'_section_name');

           assign();
           
}
 

function assign()
{
 
    $('.button_remove_section').click(function () {
        $('#dialog_remove_section').data('holder', $(this)).dialog('open');
    });


       
    $("[id$=section_name]").autocomplete({
        source: Callsign 
    });
    
    
    $("[id$=function]").autocomplete({
        source: Function
    });
   
    // $(".degree_name").autocomplete({
    //    source: Degree
    //});
    //$( "#datepicker" ).datepicker();
   
    $($(".delete_slot")).click(function(e)
    {
        $(this).parent().parent().remove();
    });
       
 
};

function start()
{
    $(function() {
        $( "#dialog_remove_section" ).dialog({
          autoOpen: false,
          resizable: false,
          
          modal: true,
          buttons: {
            "Tak": function() {
              $(this).data('holder').parent().parent().remove();
              $( this ).dialog( "close" );
            },
            "Nie": function() {
              $( this ).dialog( "close" );
            }
          }
        });
      });
    
    
	addSectionButtons("[id$=section_name]"); 
	addSlotButtons("[id$=function]");
	$("[id$=MissionInfo_missionSection]").parent().parent().append('<button type="button" class="btn btn-primary"'+
			'onclick="addButtonClicked($(this));">Dodaj sekcje</button>');
	assign();
}

$(document).ready(start());