function searchPatients(searchValue, ownClinic, isBuzzyDoc, clinicId){
	if(!searchValue){
		return false;
	}
	if(isBuzzyDoc==0){
		ownClinic = 0;
	}
$('.innerContentBox').hide();
$('#patientSearchResults').show();	
$.ajax({
	  type: "POST",
	  dataType: 'json',
	  url: "/PatientManagement/getpatients",
	    beforeSend: function() {
	    	$('#patient-loading-div').show();
	    },
	  data: {"searchString":searchValue,"ownClinic":ownClinic,"is_buzzydoc":isBuzzyDoc,"clinic_id":clinicId},
	  success: function(data) {
		  $('#patient-loading-div').hide();
		$('#registered_patients,#unregistered_patients').text('');
	    if(data.customer_search_results.length>0){
	    	$('#registered_patients').append('<h1>Registered Patient</h1>');
	    	$('#registered_patients').html(data.customer_search_results);
	    }
	    
	    if(data.unreg_customer_search_results.length>0){
	    	$('#unregistered_patients').append('<h1>New/unregistered cards</h1>');
	    	$('#unregistered_patients').html(data.unreg_customer_search_results);
		    }
	    
	    if(data.customer_search_results.length==0 && data.unreg_customer_search_results.length==0){
	    	$('#registered_patients').append('<h1>No Record Found</h1>');
	    }
	  }
	});
return false;
}

function submitSearchForm(userId,card_number){
	if(userId || card_number){
		$('#search_patient_form > #card_number').val(card_number);
		$('#search_patient_form > #user_id').val(userId);
                $('#search_patient_form > #quick_assign').val(0);
		$('#search_patient_form').submit();
	}
}
function submitQuickSearchForm(userId,card_number,quicksearch){
	if(userId || card_number){
		$('#quick_search_patient_form > #card_number').val(card_number);
		$('#quick_search_patient_form > #user_id').val(userId);
                $('#quick_search_patient_form > #quick_assign').val(quicksearch);
		$('#quick_search_patient_form').submit();
	}
}