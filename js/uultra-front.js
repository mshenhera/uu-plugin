if(typeof $ == 'undefined'){
	var $ = jQuery;
}
(function($) {
    jQuery(document).ready(function () { 
	
	   "use strict";
	   
	   jQuery('.uultra-tooltip').qtip();
       
		jQuery('.rating-s').click(function() {
			
			
			var data_id =  jQuery(this).attr("data-id");
			var data_target =  jQuery(this).attr("data-target");
			var data_vote =  jQuery(this).attr("data-vote");		

		
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {action: "rating_vote", data_id: data_id , data_target: data_target , data_vote: data_vote },
				
				success: function(data){
					
					alert(data);
					
					}
			});
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
				
        });
		
		
		$('#uultra-reset-search').click(function(){
    		window.location = window.location;
   		 });
		
		jQuery('#uu-send-private-message').click(function() {
			
			
			$( "#uu-pm-box" ).slideDown();	
			 return false;
    		e.preventDefault();
				
        });
		
		jQuery('#uu-close-private-message-box').click(function() {		
			
			jQuery( "#uu-pm-box" ).slideUp();	
			
			
			 return false;
    		e.preventDefault();
				
        });
		
		jQuery('#uu-send-private-message').click(function() {			
			
			$( "#uu-upload-avatar-box" ).slideDown();	
			 return false;
    		e.preventDefault();
				
        });
		
		
		jQuery('#uu-send-private-message-confirm').click(function() {
			
			
			var receiver_id =  jQuery(this).attr("data-id");
			
			var uu_subject =  jQuery('#uu_subject').val();
			var uu_message =   jQuery('#uu_message').val();
			
			if(uu_subject==""){alert(uu_subject_empty); jQuery('#uu_subject').focus(); return;}
			if(uu_message==""){alert(uu_message_empty);  jQuery('#uu_message').focus(); return;}

			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "send_private_message", "receiver_id": receiver_id , "uu_subject": uu_subject , "uu_message": uu_message },
				
				success: function(data){
					
					 jQuery('#uu_subject').val("");
					 jQuery('#uu_message').val("");
					
					jQuery("#uu-message-noti-id").html(data);
					jQuery("#uu-message-noti-id").slideDown();
					setTimeout("hidde_noti('uu-message-noti-id')", 3000)	;
					
					
					}
			});
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 

				
        });
		
		
		
		
		$('#uu-reply-private-message-confirm').click(function() {
			
			
			var message_id =  jQuery(this).attr("message-id");			
			var uu_message =   $('#uu_message').val();
			
			if(uu_message==""){alert(uu_message_empty);  $('#uu_message').focus(); return;}

			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "reply_private_message", "message_id": message_id ,  "uu_message": uu_message },
				
				success: function(data){
					
										
					$("#uu-message-noti-id").html(data);
					jQuery("#uu-message-noti-id").slideDown();
					setTimeout("hidde_noti('uu-message-noti-id')", 3000)	;
					
					 window.location.reload();
					
					
					}
			});
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 

				
        });
		
		$('.uu-private-message-change-status').click(function() {
			
			
			var message_id =  jQuery(this).attr("message-id");			
			var message_status =  jQuery(this).attr("message-status");			
			
			
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "message_change_status", "message_id": message_id ,  "message_status": message_status },
				
				success: function(data){
					
										
					$("#uu-message-noti-id").html(data);
					jQuery("#uu-message-noti-id").slideDown();
					setTimeout("hidde_noti('uu-message-noti-id')", 3000)	;
					window.location.reload();	
					
					
					}
			});
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 

				
        });
		
		$('.uu-private-message-delete').click(function() {
			
			
			var message_id =  jQuery(this).attr("message-id");			
			
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "message_delete", "message_id": message_id  },
				
				success: function(data){
					
										
					$("#uu-message-noti-id").html(data);
					jQuery("#uu-message-noti-id").slideDown();
					setTimeout("hidde_noti('uu-message-noti-id')", 3000)	;
					window.location.reload();
					
					
					}
			});
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 

				
        });
		
		$(document).on("click", ".btn-gallery-conf", function(e) {
			
			e.preventDefault();		
			
			
				var gal_id =  jQuery(this).attr("data-id");	
				var gal_name= $("#uultra_gall_name_edit_"+gal_id).val()	;
				var gal_desc =  $("#uultra_gall_desc_edit_"+gal_id).val();
				var gal_visibility =  $("#uultra_gall_visibility_edit_"+gal_id).val();
									
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "edit_gallery_confirm", "gal_id": gal_id , "gal_name": gal_name , "gal_desc": gal_desc , "gal_visibility": gal_visibility },
					
					success: function(data){					
						
												
						$( "#gallery-edit-div-"+gal_id ).slideUp();
						reload_gallery_list();
						
						
						}
				});
			
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
		//$(document).on("click", "a[href='#uultra-forgot-link']", function(e) {
			
			$(document).on("click", ".xoouserultra-login-forgot-link", function(e) {
		
			
			e.preventDefault();
			$( "#xoouserultra-forgot-pass-holder" ).slideDown();
			
			//alert("ok");
			 return false;
    		e.preventDefault();
			 
				
        });
		
		$(document).on("click", "#xoouserultra-reset-confirm-pass-btn", function(e) {		
			
			e.preventDefault();			
		
		
				var p1= $("#preset_password").val()	;
				var p2= $("#preset_password_2").val()	;
				var u_key= $("#uultra_reset_key").val()	;
				
									
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "confirm_reset_password", "p1": p1, "p2": p2, "key": u_key },
					
					success: function(data){
						
					
						jQuery("#uultra-reset-p-noti-box").html(data);
						jQuery("#uultra-reset-p-noti-box").slideDown();
						//setTimeout("hidde_noti('uultra-reset-p-noti-box')", 3000)	;			
												
												
						
						}
				});
			
			
			
			 return false;
    		e.preventDefault();
			 
				
        });
		
		$(document).on("click", "#xoouserultra-forgot-pass-btn-confirm", function(e) {		
			
			e.preventDefault();			
		
		
				var email= $("#user_name_email").val()	;
				
									
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "send_reset_link", "user_login": email },
					
					success: function(data){
						
					
						jQuery("#uultra-signin-ajax-noti-box").html(data);
						jQuery("#uultra-signin-ajax-noti-box").slideDown();
						setTimeout("hidde_noti('uultra-signin-ajax-noti-box')", 3000)	;			
												
												
						
						}
				});
			
			
			
			 return false;
    		e.preventDefault();
			 
				
        });
		
		//send friend request				
		$(document).on("click", "#uu-send-friend-request", function(e) {
			
			
			var user_id =  jQuery(this).attr("user-id");		
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "send_friend_request", "user_id": user_id },
				
				success: function(data){					
										
					alert(data);
					
					
					
					}
			});
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 

				
        });
		
		
		
		//follow				
		$(document).on("click", "#uu-follow-request", function(e) {			
			
			var user_id =  jQuery(this).attr("user-id");		
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "send_follow_request", "user_id": user_id },
				
				success: function(data){	
				
				alert(data)	;				
					}
			});
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();			 

				
        });
		
		//follow				
		$(document).on("click", ".uultra-btn-follow-request", function(e) {			
			
			var user_id =  jQuery(this).attr("user-id");		
			
						
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "send_follow_request", "user_id": user_id },
				
				success: function(data){
					
					alert(data)	;				
					}
			});
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();			 

				
        });
		
		//follow				
		$(document).on("click", ".uultra-btn-unfollow-request", function(e) {			
			
			var user_id =  jQuery(this).attr("user-id");		
			
			
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "send_unfollow_request", "user_id": user_id },
				
				success: function(data){	
				
						alert(data)	;				
					}
			});
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();			 

				
        });
		
		//follow	nav			
		$(document).on("click", "#uu-follow-request-header", function(e) {			
			
			var user_id =  jQuery(this).attr("user-id");		
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "send_follow_request", "user_id": user_id },
				
				success: function(data){
					alert(data);					
					}
			});
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();			 

				
        });
		
		//like item
				
		$(document).on("click", "#uu-like-item", function(e) {
			
			e.preventDefault();			
			
			var item_id =  jQuery(this).attr("item-id");
			var module =  jQuery(this).attr("data-module");	
			var vote =  jQuery(this).attr("data-vote");		
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "like_item", "item_id": item_id , "module": module , "vote": vote },
				
				success: function(data){					
										
					$("#uu-like-sore-id-"+item_id).html(data);					
					setTimeout("get_total_likes('"+item_id+"','"+module+"')", 1100)	;
					
					
					
					}
			});
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 

				
        });
		
		
		$(document).on("click", "#uu-approvedeny-friend", function(e) {
			
			e.preventDefault();			
			
			var item_id =  jQuery(this).attr("item-id");			
			var item_action =  jQuery(this).attr("action-id");		
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "friend_request_action", "item_id": item_id , "item_action": item_action },
				
				success: function(data){
								
					reload_friend_request();								
					reload_my_friends();
					
					
					}
			});
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 

				
        });
		
		//reset password				
		$(document).on("click", "#xoouserultra-backenedb-eset-password", function(e) {			
			
			var p1 =   $('#p1').val();
			var p2 =   $('#p2').val();		
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "confirm_reset_password_user", "p1": p1 , "p2": p2 },
				
				success: function(data){					
										
					$("#uultra-p-reset-msg").html(data);
					
					
					
					}
			});
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();			 

				
        });
		
		//update email				
		$(document).on("click", "#xoouserultra-backenedb-update-email", function(e) {			
			
			var email =   $('#email').val();			
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "confirm_update_email_user", "email": email },
				
				success: function(data){					
										
					$("#uultra-p-changeemail-msg").html(data);
					
					
					
					}
			});
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();			 

				
        });
		
		
	    var uu_subject = $( "#uu_subject" ),
		uu_message = $( "#uu_message" ),
		
		allFields = $( [] ).add( uu_subject ).add( uu_message ),
		tips = $( ".validateTips" );
		function updateTips( t ) {
		tips
		.text( t )
		.addClass( "ui-state-highlight" );
		setTimeout(function() {
		tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
		}
		
		function checkLength( o, n, min, max )
		{
		if ( o.val().length > max || o.val().length < min ) {
		o.addClass( "ui-state-error" );
		updateTips( "Length must be between " +
		min + " and " + max + "." );
		return false;
		} else {
		return true;
		}
		}
		
		function checkLengthDirectory( o, n, min, max )
		{
		if ( o.val().length > max || o.val().length < min ) {
		o.addClass( "ui-state-error" );
		updateTips( "Length must be between " +
		min + " and " + max + "." );
		return false;
		} else {
		return true;
		}
		}
		
		
		jQuery( "#uultra-dialog-form" ).dialog({
			autoOpen: false,
			height: 570,																				
			width: 530,
			modal: true,
			buttons: {
			"Send Message": function() {
			var bValid = true;
			allFields.removeClass( "ui-state-error" );
			bValid = bValid && checkLength( uu_subject, "uu_subject", 3, 50 );
			bValid = bValid && checkLength( uu_message, "uu_message", 3, 1000 );
		
			if ( bValid ) {			
				
				
				send_pm_confirm();
				
			
			
			}
			},
			Cancel: function() {
			$( this ).dialog( "close" );
			}
			},
			close: function() {
			allFields.val( "" ).removeClass( "ui-state-error" );
			}
			});
			
	
	    jQuery( "#uultra-dialog-form-contact-admin" ).dialog({
			autoOpen: false,
			height: 570,																				
			width: 530,
			modal: true,
			buttons: {
			"Send Message": function() {
			var bValid = true;
			allFields.removeClass( "ui-state-error" );
			bValid = bValid && checkLength( uu_subject, "uu_subject", 3, 50 );
			bValid = bValid && checkLength( uu_message, "uu_message", 3, 1000 );
		
			if ( bValid ) {			
				
				
				send_pm_to_admin_confirm();
				
			
			
			}
			},
			Cancel: function() {
			$( this ).dialog( "close" );
			}
			},
			close: function() {
			allFields.val( "" ).removeClass( "ui-state-error" );
			}
			});
			
			
		  
			
			
		//added for directory messaging option
		 jQuery( ".uultra-dialog-form-directory" ).dialog({
			autoOpen: false,
			height: 570,																				
			width: 530,
			modal: true,
			buttons: {
			"Send Message": function() {
				
			var recipient_id =  jQuery(this).attr("user-id");
			
			var uu_subject = jQuery("#uu_subject" + "_" +recipient_id);
			var uu_message = jQuery("#uu_message" + "_" +recipient_id);

			var bValid = true;
			uu_subject.removeClass( "ui-state-error" );
			uu_message.removeClass( "ui-state-error" );
			
			bValid = bValid && checkLengthDirectory( uu_subject, "uu_subject_"+recipient_id, 3, 50 );
			bValid = bValid && checkLengthDirectory( uu_message, "uu_message_"+recipient_id, 3, 1000 );
		
			if ( bValid ) {	
				
				
				send_pm_confirm_directory(recipient_id, uu_subject.val(), uu_message.val());
				
			
			
			}
			},
			Cancel: function() {
			$( this ).dialog( "close" );
			}
			},
			close: function() {
			allFields.val( "" ).removeClass( "ui-state-error" );
			}
			});
			
		
		//this will display the message box to contact the admin
		$(document).on("click", "#uultra-send-private-message-box-to-admin", function(e) {
			
			e.preventDefault();	
			
					
			$("#uultra-dialog-form-contact-admin" ).dialog( "open" );					
		     	
			 return false;
    		e.preventDefault();
			 

				
        });
		
		
		//this is displayed when clicking on the "send message button"
		$(document).on("click", "#uultra-send-private-message-box", function(e) {
			
			e.preventDefault();	
			
			var recipient_id =  jQuery(this).attr("user-id");

			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "show_my_messages_msg_box_to_user", "recipient_id": recipient_id  },
				
				success: function(data){
					
					$("#uultra-msg-history-list").html(data);					
					$("#uultra-dialog-form" ).dialog( "open" );
					
					
					}
			});
			
			
		     	
			 return false;
    		e.preventDefault();
			 

				
        });
		
		//this is applied only when using the users directory features
		$(document).on("click", ".uultra-directory-send-private-message-box", function(e) {
			
			e.preventDefault();	
			
			var recipient_id =  jQuery(this).attr("user-id");
			
		   jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "show_my_messages_msg_box_to_user_directory", "recipient_id": recipient_id  },
				
				success: function(data){
					
					$("#uultra-msg-history-list").html(data);	
					xoo_private_messages_history_directory(recipient_id);				
					$("#uultra-dialog-form-"+recipient_id ).dialog( "open" );
					
					
					}
			});
			
			
		     	
			 return false;
    		e.preventDefault();
			 

				
        });
		
		
		//this will crop the image and redirect
		jQuery(document).on("click touchstart", "#uultra-confirm-image-cropping", function(e) {
			
			e.preventDefault();			
			
			var x1 = $('#x1').val();
			var y1 = $('#y1').val();
			
			
			var w = jQuery('#w').val();
			var h = jQuery('#h').val();
			var image_id = $('#image_id').val();			
			
			if(x1=="" || y1=="" || w=="" || h==""){
				alert("You must make a selection first");
				return false;
			}
			
			
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "uultra_crop_bg_user_profile_image", "x1": x1 , "y1": y1 , "w": w , "h": h  , "image_id": image_id},
				
				success: function(data){					
					//redirect				
					var site_redir = jQuery('#site_redir').val();
					window.location.replace(site_redir);	
								
					
					
					}
			});
			
					
					
		     	
			 return false;
    		e.preventDefault();
			 

				
        });
		
		//this will crop the avatar and redirect
		jQuery(document).on("click touchstart", "#uultra-confirm-avatar-cropping", function(e) {
			
			e.preventDefault();			
			
			var x1 = jQuery('#x1').val();
			var y1 = jQuery('#y1').val();
			
			
			var w = jQuery('#w').val();
			var h = jQuery('#h').val();
			var image_id = $('#image_id').val();			
			
			if(x1=="" || y1=="" || w=="" || h==""){
				alert("You must make a selection first");
				return false;
			}
			
			
			jQuery('#uultra-cropping-avatar-wait-message').show();
			
			
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "uultra_crop_avatar_user_profile_image", "x1": x1 , "y1": y1 , "w": w , "h": h  , "image_id": image_id},
				
				success: function(data){					
					//redirect				
					var site_redir = jQuery('#site_redir').val();
					window.location.replace(site_redir);	
								
					
					
					}
			});
			
					
					
		     	
			 return false;
    		e.preventDefault();
			 

				
        });
		
		function send_pm_confirm_directory (receiver_id, uu_subject, uu_message){
			
			
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "send_private_message", "receiver_id": receiver_id , "uu_subject": uu_subject , "uu_message": uu_message },
				
				success: function(data){
					
					 $('#uu_subject_'+receiver_id).val("");
					 $('#uu_message_'+receiver_id).val("");					 
					 xoo_private_messages_history_directory(receiver_id);	
					
					
					
					}
			});
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 

				
        }
		
		
		function send_pm_confirm (){
			
			
			var receiver_id =  $('#receiver_id').val();
			
			var uu_subject =   $('#uu_subject').val();
			var uu_message =   $('#uu_message').val();
			
			if(uu_subject==""){alert(uu_subject_empty); $('#uu_subject').focus(); return;}
			if(uu_message==""){alert(uu_message_empty);  $('#uu_message').focus(); return;}

			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "send_private_message", "receiver_id": receiver_id , "uu_subject": uu_subject , "uu_message": uu_message },
				
				success: function(data){
					
					 $('#uu_subject').val("");
					 $('#uu_message').val("");
					 
					 xoo_private_messages_history();
					
					
					
					
					}
			});
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 

				
        }
		
		function send_pm_to_admin_confirm ()
		{		
					
			var uu_subject =   $('#uu_subject').val();
			var uu_message =   $('#uu_message').val();
			
			if(uu_subject==""){alert(uu_subject_empty); $('#uu_subject').focus(); return;}
			if(uu_message==""){alert(uu_message_empty);  $('#uu_message').focus(); return;}

			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "send_private_message_to_admin", "uu_subject": uu_subject , "uu_message": uu_message },
				
				success: function(data){
					
					 $('#uu_subject').val("");
					 $('#uu_message').val("");			
					
					}
			});
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 

				
        }
		
		//SITE WIDE- UPLOAD PHOTO OPEN BOX
		$(document).on("click", "#uultra-upload-photo-site-wide_blocked", function(e) {
			
			e.preventDefault();				
			
			
			if($('#uultra-wall-photo-uploader-box').is(':visible')) 
			{
				$( "#uultra-wall-photo-uploader-box" ).slideUp();
    
			}else{
				
			
				$( "#uultra-wall-photo-uploader-box" ).slideDown();
				
			}
			 return false;
    		e.preventDefault();
			 
				
        });
		
		//WALL	POST MESSAGE IN SITE-WIDE	
		$(document).on("click", "#uultra-site-wide-wall-post-commment", function(e) {
			
			e.preventDefault();				
			
			var wall_profile_id =  jQuery(this).attr("data-id");	
			var wall_message= $("#uultra-txtMessage").val()	;
			var wall_image= $("#uultra-site-wide-wall-image-share").val()	;
			
			if( $("#uultra-no-logged-flag-btn").val()=='nologgedin')			
			{
				$( "#uultra-not-loggedin-message" ).show();
				return;
			
			}
			
			if(wall_message!="" && wall_profile_id!="")
			{
									
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wall_post_message", "wall_message": wall_message , "wall_profile_id": wall_profile_id, "wall_image": wall_image  },
					
					success: function(data){
						
						
						$("#uultra-site-wide-wall-image-share").val("");						
						//$( "#uultra-img-to-share-id-refresh" ).slideUp();
						
						 $( "#uultra-img-to-share-id-refresh" ).slideUp( "slow", function() {
							// Animation complete.
							xoo_load_wallmessags_site_wide()	;	
						});
						
						
						}
				});
				
				
			}
			
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
		//WALL	POST MESSAGE IN PROFILE	
		$(document).on("click", "#uultra-wall-post-commment", function(e) {
			
			e.preventDefault();				
			
			var wall_profile_id =  jQuery(this).attr("data-id");	
			var wall_message= $("#uultra-txtMessage").val()	;
			
			
			if( $("#uultra-no-logged-flag-btn").val()=='nologgedin')			
			{
				$( "#uultra-not-loggedin-message" ).show();
				return;
			
			}
			
			if(wall_message!="" && wall_profile_id!="")
			{
									
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wall_post_message", "wall_message": wall_message , "wall_profile_id": wall_profile_id  },
					
					success: function(data){
						
						xoo_load_wallmessags_instant(wall_profile_id)	;				
						
						
						}
				});
				
				
			}
			
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
		
		
		//WALL REPLY	
		$(document).on("click", "#uultra-wall-post-reply", function(e) {
			
			e.preventDefault();				
			
			var wall_comment_id =  jQuery(this).attr("data-comment-id");	
			var wall_reply_message= $("#uultra-reply-to_comment-"+wall_comment_id).val()	;
			
			
			if( $("#uultra-no-logged-flag-btn").val()=='nologgedin')			
			{
				$( "#uultra-not-loggedin-message-reply-"+wall_comment_id).show();
				return;			
			}
			
			if(wall_comment_id!="" && wall_reply_message!="")
			{
									
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wall_post_reply", "wall_comment_id": wall_comment_id , "wall_reply_message": wall_reply_message  },
					
					success: function(data){
						
						//reload replies
						
						xoo_load_wall_replies_instant(wall_comment_id)	;
						jQuery("#uultra-reply-to_comment-"+wall_comment_id).val("");				
						
						
						}
				});
				
				
			}
			
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
		//WALL DELETE MESSAGE				
		$("body").on("click", ".uultra-wall-delete-message", function(e) {
			
			e.preventDefault();				
			
			
			var wall_comment_id =  jQuery(this).attr("data-comment-id");	
			
			if(wall_comment_id!="" && wall_comment_id!="")
			{
									
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wall_delete_inline_comment",  "wall_comment_id": wall_comment_id },
					
					success: function(data){
						
						$("#uultra-whole-comments-holder-"+wall_comment_id).slideUp();
						
						}
				});
				
				
			}
			
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
		//WALL DELETE REPLY				
		$("body").on("click", ".uultra-wall-delete-reply", function(e) {
			
			e.preventDefault();				
			
			var wall_reply_id =  jQuery(this).attr("data-reply-id");
			var wall_comment_id =  jQuery(this).attr("data-comment-id");	
			
			if(wall_reply_id!="" && wall_reply_id!="")
			{
									
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wall_delete_reply", "wall_reply_id": wall_reply_id , "wall_comment_id": wall_comment_id },
					
					success: function(data){
						
						//reload replies
						
						xoo_load_wall_replies_instant(wall_comment_id)	;
						
						}
				});
				
				
			}
			
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
		//WALL EDIT REPLY				
		$("body").on("click", ".uultra-wall-edit-reply", function(e) {
			
			e.preventDefault();				
			
			var wall_reply_id =  jQuery(this).attr("data-reply-id");
			var wall_comment_id =  jQuery(this).attr("data-comment-id");	
			
			if(wall_reply_id!="" && wall_reply_id!="")
			{
									
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wall_edit_reply", "wall_reply_id": wall_reply_id , "wall_comment_id": wall_comment_id },
					
					success: function(data){
						
						//reload replies
						$("#uultra-edit-reply-box-"+wall_reply_id).html(data);
						$("#uultra-edit-reply-box-"+wall_reply_id).slideDown();
						
						
						}
				});
				
				
			}
			
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
		//WALL EDIT UPDATE				
		$("body").on("click", ".uultra-wall-edit-update-inline", function(e) {
			
			e.preventDefault();				
			
			
			var wall_comment_id =  jQuery(this).attr("data-comment-id");	
			
			if(wall_comment_id!="" )
			{
									
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wall_edit_update_form",  "wall_comment_id": wall_comment_id },
					
					success: function(data){
						
						//reload replies
						$("#uultra-edit-update-box-"+wall_comment_id).html(data);
						$("#uultra-edit-update-box-"+wall_comment_id).slideDown();
						
						
						}
				});
				
				
			}
			
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
		
		//WALL EDIT COMMENT CONFIRM	btn			
		$("body").on("click", ".uultra-button-edit-comment-wall-edition-confirm", function(e) {
			
			e.preventDefault();				
			
			var wall_reply_id =  jQuery(this).attr("data-reply-id");			
			var wall_text = $("#uultra-edit-comment-text-box-"+wall_reply_id).val();
			
			$("#uultra-empty-message-comment-id-"+wall_reply_id).hide();
			
			if(wall_text==""){
				
				$("#uultra-empty-message-comment-id-"+wall_reply_id).show();
				return
				
			}
			
		//	alert("Called");
						
			if(wall_reply_id!="")
			{
									
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wall_edit_comment_confirm", "wall_reply_id": wall_reply_id , "wall_text": wall_text },
					
					success: function(data){
						
						//reload replies
						$("#uultra-flb-comment-text-inline-"+wall_reply_id).html(data);
						$("#uultra-edit-update-box-"+wall_reply_id).slideUp();
						
						
						}
				});
				
				
			}
			
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
		//WALL EDIT REPLY CONFIRM	btn			
		$("body").on("click", ".uultra-button-edit-reply-wall-edition-confirm", function(e) {
			
			e.preventDefault();				
			
			var wall_reply_id =  jQuery(this).attr("data-reply-id");			
			var wall_text = $("#uultra-edit-reply-text-box-"+wall_reply_id).val();
			
			$("#uultra-empty-message-reply-id-"+wall_reply_id).hide();
			
			if(wall_text==""){
				
				$("#uultra-empty-message-reply-id-"+wall_reply_id).show();
				return
				
			}
						
			if(wall_reply_id!="")
			{
									
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wall_edit_reply_confirm", "wall_reply_id": wall_reply_id , "wall_text": wall_text },
					
					success: function(data){
						
						//reload replies
						$("#uultr-reply-text-box-id-"+wall_reply_id).html(data);
						$("#uultra-edit-reply-box-"+wall_reply_id).slideUp();
						
						
						}
				});
				
				
			}
			
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
		//WALL EDIT REPLY	CLOSE			
		$("body").on("click", ".uultra-close-edit-reply-box", function(e) {
			
			e.preventDefault();				
			
			var wall_reply_id =  jQuery(this).attr("data-reply-id");
			$("#uultra-edit-reply-box-"+wall_reply_id).slideUp();
			$("#uultra-edit-reply-box-"+wall_reply_id).html("");
						
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
		//WALL EDIT COMMENT	CLOSE			
		$("body").on("click", ".uultra-close-edit-comment-box", function(e) {
			
			e.preventDefault();						
			var wall_comment_id =  jQuery(this).attr("data-reply-id");
			$("#uultra-edit-update-box-"+wall_comment_id).slideUp();
			$("#uultra-edit-update-box-"+wall_comment_id).html("");						
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
		
		//EMOTICON	
		$(document).on("click", ".uultra-emoti-msg-ico", function(e) {
			
			e.preventDefault();				
			
			var icon_id =  jQuery(this).attr("icoid");
			var messsage= $("#uu_message").val();		
			
			//alert(messsage);
			$("#uu_message").val(messsage + ' ' + icon_id);
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
		//EMOTICON	DIRECTORY
		$(document).on("click", ".uultra-emoti-msg-ico-directory", function(e) {
			
			e.preventDefault();				
			
			var icon_id =  jQuery(this).attr("icoid");
			var user_id =  jQuery(this).attr("user-id");
			var messsage= $("#uu_message_"+user_id).val();		
			
			//alert(messsage);
			$("#uu_message_"+user_id).val(messsage + ' ' + icon_id);
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
		
		$("#xoouserultra-registration-form").validationEngine({promptPosition: 'inline'});
		
 
       
    }); //END READY
})(jQuery);



function xoo_private_messages_history ()
{
	
	var receiver_id = jQuery('#receiver_id').val();
	
	jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "show_my_messages_msg_box_to_user", "recipient_id": receiver_id   },
				
				success: function(data){					
										
					jQuery("#uultra-msg-history-list").html(data);
					
					}
			});
		
}

//specialy added for new users table.
function xoo_private_messages_history_directory (receiver_id)
{
	
	
	jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "show_my_messages_msg_box_to_user", "recipient_id": receiver_id   },
				
				success: function(data){					
										
					jQuery("#uultra-msg-history-list-"+receiver_id).html(data);
					
					}
			});
		
}

function xoo_load_wallmessags (user_id)
{
	var howmany =  jQuery("#howmany_messages").val()	;
	
	jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "wall_reload_whole_messages", "user_id": user_id , "howmany": howmany  },
				
				success: function(data){					
										
					jQuery("#uultra-wall-messages").html(data);
					
					jQuery("#uultra-wall-messages").slideDown();
					
					}
			});
		
}
function xoo_load_wall_replies_instant (comment_id)
{
	
	jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "reload_whole_replies", "comment_id": comment_id   },
				
				success: function(data){					
										
					jQuery("#uultra-replies-list-"+comment_id).html(data);
					
					}
			});
		
}

function xoo_load_wallmessags_site_wide()
{
	var howmany =  jQuery("#howmany_messages").val()	;
	
	jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "reload_site_wide_wall","howmany": howmany,  "ajax_call_reload": 'yes'  },
				
				success: function(data){					
										
					jQuery("#uultra-site-wide-wall-container").html(data);
					
					}
			});
		
}

function xoo_load_wallmessags_instant (user_id)
{
	var howmany =  jQuery("#howmany_messages").val()	;
	
	jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "wall_reload_whole_messages", "user_id": user_id , "howmany": howmany  },
				
				success: function(data){					
										
					jQuery("#uultra-wall-messages").html(data);
					
					}
			});
		
}
function get_total_likes (item_id, module)
{
	jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "get_item_likes_amount_only", "item_id": item_id , "module": module  },
				
				success: function(data){		
				
		//	alert("Item id: " + item_id);			
										
					jQuery("#uu-like-sore-id-"+item_id).html(data);
					
								
													
					
					}
			});
			
		
}

function reload_my_friends ()
{
	jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "show_all_my_friends"  },
				
				success: function(data){					
										
								jQuery("#uultra-my-friends-list").html(data);
					
					}
			});
			
		
}

function reload_friend_request ()
{
	jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "show_friend_request"  },
				
				success: function(data){					
										
								jQuery("#uultra-my-friends-request").html(data);
					
					}
			});
			
		
}

function hidde_noti (div_d)
{
		jQuery("#"+div_d).slideUp();		
		
}

// Adding jQuery Datepicker
jQuery(function() {
	jQuery( ".xoouserultra-datepicker" ).datepicker({changeMonth:true,changeYear:true,yearRange:"1940:2017"});

	jQuery("#ui-datepicker-div").wrap('<div class="ui-datepicker-wrapper" />');
});

