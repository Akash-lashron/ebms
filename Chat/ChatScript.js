hideChat(0);

$('#prime').click(function() {
  toggleFab();
});

//alert(GlobChatLocStr);
//Toggle chat and links
function toggleFab() {
	$('.chat').toggleClass('is-visible');
	$('.helpdesk').toggleClass('chat-icon chat-icon-close');
	if(($('#chat_body').css('display') === 'none')&&($('#chat_form').css('display') === 'none')){
		$('#chat_fullscreen_loader').css('display', 'block');
  		$('#chat_body').css('display', 'block');
		$('.chat_fullscreen').css('display', 'block');
		$('#chat_ccno').children('option:not(:first)').remove();
		$.ajax({ 
			type: 'POST', 
			url: GlobChatLocStr+'FindAllCCNo.php', 
			data: { temp : 4 }, 
			dataType: 'json',
			success: function (data) { 
				if(data != null){
					$.each(data, function(index, element) {	
						$("#chat_ccno").append('<option data-sheetid="'+element.sheet_id+'" value="'+element.computer_code_no+'">'+element.computer_code_no+'</option>');
					});
				}
			}
		});
	}else{
		$('#chat_body').css('display', 'none');
    	$('#chat_form').css('display', 'none');
		$('.chat_fullscreen').css('display', 'none');
	}
	$('.prime').toggleClass('is-active');
  	$('.prime').toggleClass('is-visible');
  	$('#prime').toggleClass('is-float');
  	$('.fab').toggleClass('is-visible');
	
}


$('#chat_third_screen').click(function(e) { // FIRST
	var Ccno = $('#chat_ccno').val();
	if(Ccno == ""){
		BootstrapDialog.alert("CCNO. should not be empty");
		event.preventDefault();
		return false;
	}else{
		hideChat(3);
		$("#chat_cc_div").html('');
		$("#chat_from_email").val('');
		$("#chat_cc").val('');
		$.ajax({ 
			type: 'POST', 
			url: GlobChatLocStr+'FindAssignedStaffData.php', 
			data: { Ccno : Ccno }, 
			dataType: 'json',
			success: function (data) {  
				var Result1 = data['Cc']; 
				if(data['Cc'] != null){ 
					var Str = "";
					$.each(data['Cc'], function(index, element) {	
						Str = Str + '<div class="chat-emailbox">'+element+'</div>';
					});
					$("#chat_cc_div").html(Str);
					$("#chat_cc").val(data['Cc']);
				}
				if(data['From'] != null){ 
					$.each(data['From'], function(index, element) {	
						$("#chat_from_email").val(element);
					});
				}
			}
		});
	}
});

$('#chat_fourth_screen').click(function(e) { //FOURTH
    hideChat(4);
});

$('#chat_fullscreen_loader').click(function(e) {
	$('.fullscreen').toggleClass('zmdi-window-maximize');
    $('.fullscreen').toggleClass('zmdi-window-restore');
    $('.chat').toggleClass('chat_fullscreen');
   // $('.fab').toggleClass('is-hide');
    $('.header_img').toggleClass('change_img');
    $('.img_container').toggleClass('change_img');
    $('.chat_header').toggleClass('chat_header2');
    $('.fab_field').toggleClass('fab_field2');
    $('.chat_converse').toggleClass('chat_converse2');
});


$(document).on("click","#chat_send",function(event) { 
	var ChatToEmailList = $('#chat_send_to').val();
	var ChatCcEmailList = $('#chat_cc').val();
	var ChatMessage 	= $('#chat_message').val();
	var ChatFromMail 	= $('#chat_from_email').val();
	var ChatFromPwd 	= $('#chat_from_pwd').val(); 
	var Chatccno 	= $('#chat_ccno').val(); 
	if(ChatFromMail == ""){
		BootstrapDialog.alert("From email id should not be empty");
		event.preventDefault();
		return false;
	}else if(ChatFromPwd == ""){
		BootstrapDialog.alert("Email password should not be empty");
		event.preventDefault();
		return false;	
	}else if(ChatToEmailList == ""){
		BootstrapDialog.alert("Send to email id should not be empty");
		event.preventDefault();
		return false;	
	}else if(ChatCcEmailList == ""){
		BootstrapDialog.alert("CC to email id should not be empty");
		event.preventDefault();
		return false;	
	}else if(ChatMessage == ""){
		BootstrapDialog.alert("Message should not be empty");
		event.preventDefault();
		return false;	
	}else{
		$(".SendIcon").hide();
		$(".SpinIcon").removeClass('hide');
		$('#chat_send').css('pointer-events','none');
		$.ajax({ 
			type: 'POST', 
			url: GlobChatLocStr+'mail/SendChatMail.php', 
			data: { ChatToEmailList: ChatToEmailList, ChatCcEmailList: ChatCcEmailList, ChatMessage: ChatMessage, ChatFromMail: ChatFromMail, ChatFromPwd: ChatFromPwd, Chatccno: Chatccno }, 
			//dataType: 'json',
			success: function (data) { 
				if(data != null){ 
					BootstrapDialog.alert(data);
					$('#chat_message').val('');
					$('#chat_from_pwd').val(''); 
					$(".SendIcon").show();
					$(".SpinIcon").addClass('hide');
					$('#chat_send').css('pointer-events','');
					hideChat(4);
				}
			}
		});
	}
});

function hideChat(hide) {
	switch (hide) {
		case 0:
			$('#chat_converse').css('display', 'none');
            $('#chat_body').css('display', 'none');
            $('#chat_form').css('display', 'none');
            $('.chat_login').css('display', 'none');
            $('.chat_fullscreen_loader').css('display', 'none');
            $('#chat_fullscreen').css('display', 'none');
            break;
      	case 1:
            $('#chat_converse').css('display', 'block');
            $('#chat_body').css('display', 'none');
            $('#chat_form').css('display', 'none');
            $('.chat_login').css('display', 'none');
            $('.chat_fullscreen_loader').css('display', 'block');
            break;
      	case 2:
            $('#chat_converse').css('display', 'none');
            $('#chat_body').css('display', 'block');
            $('#chat_form').css('display', 'none');
            $('.chat_login').css('display', 'none');
            $('.chat_fullscreen_loader').css('display', 'block');
            break;
      	case 3:
            $('#chat_converse').css('display', 'none');
            $('#chat_body').css('display', 'none');
            $('#chat_form').css('display', 'block');
            $('.chat_login').css('display', 'none');
            $('.chat_fullscreen_loader').css('display', 'block');
            break;
      	case 4:
            $('#chat_converse').css('display', 'none');
            $('#chat_body').css('display', 'block');
            $('#chat_form').css('display', 'none');
            $('.chat_login').css('display', 'none');
            $('.chat_fullscreen_loader').css('display', 'block');
            $('#chat_fullscreen').css('display', 'block');
            break;
	  	case 5:
            $('#chat_converse').css('display', 'none');
            $('#chat_body').css('display', 'block');
            $('#chat_form').css('display', 'none');
            $('.chat_login').css('display', 'none');
            $('.chat_fullscreen_loader').css('display', 'none');
            $('#chat_fullscreen').css('display', 'none');
            break;
    }
}