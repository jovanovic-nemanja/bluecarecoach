(function($) {
  	'use strict';

  	$(document).ready(function() {
  		getNotificationdata();
  	});

  	function getNotificationdata() {
  		window.toastr.clear();
  		$('.reminder_badge').hide();

  		$.ajax({
	        url: "/getNotificationdata",
	        data: {},
	        type: 'GET',
	        success: function(result, status) {
	          	if (result) {
	          		var leng = result.length;
	          		if (leng > 0) {
	          			$('.reminder_badge').show();	
	          		}
	          		
	          		$.each(result, function (i, val) {
			            var mes = '<input type="hidden" id="notificationId" value="' + val.id + '"/>' + 
			                'Resident Name:  ' + val.resident_name + '</br>' + val.contents + '</br>' + '</br>' +
			                '<button class="btn btn-success error" id="error">Confirm</button>';
			            var title = val.contents;
			            toastr.options = {
						  	"closeButton": true,
						  	"debug": false,
						  	"newestOnTop": false,
						  	"progressBar": false,
						  	"positionClass": "toast-top-right",
						  	"onclick": null,
						  	"showDuration": "59000",
						  	"hideDuration": "59000",
						  	"timeOut": "59000",
						  	"extendedTimeOut": "59000",
						};
			            toastr.error(mes, title); //info, success, warning, error
			        });

			        $('.error').click(function () {
			        	var notificationId = $(this).parent().children().val();
			        	updateIsread(notificationId);
			        });
	          	}
	        }
	  	});
  	}

  	function updateIsread(notificationId) {
  		if (notificationId) {
  			$.ajax({
  				url: "/updateIsread",
  				data: { notificationId: notificationId },
  				type: "GET",
  				success: function (result, status) {
  					getNotificationdata();
  				}
  			})
  		}
  	}

  	$(function() {
    	setInterval(function() { 
	      	getNotificationdata();
	    }, 60000);
  	});
})(jQuery);