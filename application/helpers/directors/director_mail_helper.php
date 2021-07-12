<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

function sendNoteMail($emails, $data, $Assignnames) {
    $CI = & get_instance();
    $CI->load->library('email');

    $subject = empty($data['client_name']) ? 'No Name' : $data['client_name'];

    $sContent = "<p style='font-size:16px;'>Hello,</p>";
	$sContent .= "<p style='font-size:16px;'>Client Name:&nbsp;".$data['client_name']."</p>";
	$sContent .= "<p style='font-size:16px;'>Assigned Users : &nbsp;".$Assignnames."</p>";
	$sContent .= "<p style='font-size:16px;'>".$data['note']."</p>";
	$sContent .= "<p style='font-size:16px;'>Thanks,</p>";
	$sContent .= "<p style='font-size:16px;'>".$CI->session->userdata('name')."</p>";

    $CI->email->set_mailtype("html");
    $CI->email->from('office@unitassistant.com', 'Unit Assistant');
    $CI->email->to($emails);
    $CI->email->subject($subject);
    $CI->email->message($sContent);
    $status = $CI->email->send();
    return $status;
}

function directorMail($aDatas){
	$CI = get_instance();

	$sPersonalUnitAppMsg = '';
	$sPersonalUnitWebMsg = '';
	if (isset($aDatas['package']) && $aDatas['package'] == 'N') {
		if ( isset($aDatas['personal_unit_app']) && $aDatas['personal_unit_app'] == '1') {
			$sPersonalUnitAppMsg = '$' . pack_personal_unit_app_val;
		}
		if (isset($aDatas['personal_website']) && $aDatas['personal_website'] == '1') {
			$sPersonalUnitWebMsg = '$' . pack_personal_website_val;
		}
	}else{
		if (isset($aDatas['personal_unit_app']) && $aDatas['personal_unit_app'] == '1') {
			$sPersonalUnitAppMsg = '$' . personal_unit_app_val;
		}
		if (isset($aDatas['personal_website']) && $aDatas['personal_website'] == '1') {
			$sPersonalUnitWebMsg = '$' . personal_website_val;
		}
	}

	$sPersonalUnitAppCanadaMsg = (isset($aDatas['personal_unit_app_ca']) && $aDatas['personal_unit_app_ca'] == '1') ? '$' . personal_unit_app_canada : 0;
	$sWebsiteLinkMsg = empty($aDatas['website_link']) ? '' : $aDatas['website_link'];
	$sPersonalUrlMsg = (isset($aDatas['personal_url']) && $aDatas['personal_url'] == '1') ? '$' . personal_url_val : 0;
	$sSubscriptionUpdatesMsg = (isset($aDatas['subscription_updates']) && $aDatas['subscription_updates'] == '1') ? '$' . subscription_updates_val : 0;
	$sAppColorMsg = (isset($aDatas['app_color']) && $aDatas['app_color'] == '1') ? '$' . app_color_val : 0;

	
	$lg = (isset($aDatas['newsletters_design']) && $aDatas['newsletters_design']=='CE') ? 'E' : $aDatas['newsletters_design'];
	$decrypted = decryptIt($aDatas['cv_account']);
	$mask = empty($encrypted) ? '' : maskCreditCard($decrypted);
	$sNewsLetterDesign=(($aDatas['emailing']=='1') ? '$'.emailing_1 : (($aDatas['emailing']=='2') ? '$'.emailing_2 : 0));

	$printingT = newsletter_print_option($aDatas);

	if($aDatas['select_email'] == 'W' && $lg == 'E') {
        $mailContent = $aDatas['welcome_email_english'];
    }elseif($aDatas['select_email'] == 'W' && $lg == 'S'){
        $mailContent = $aDatas['welcome_email_spanish'];
    }elseif($aDatas['select_email'] == 'W' && $lg == 'CE') {
        $mailContent = $aDatas['welcome_email_canada_english'];
    }elseif($aDatas['select_email'] == 'W' && $lg == 'F') {
        $mailContent = $aDatas['welcome_email_french'];
    }elseif($aDatas['select_email'] == 'C' && $lg == 'S'){
        $mailContent = $aDatas['current_email_spanish'];
    }elseif($aDatas['select_email'] == 'C' && $lg == 'E'){
        $mailContent = $aDatas['current_email_english'];
    }elseif($aDatas['select_email'] == 'C' && $lg == 'CE'){
        $mailContent = $aDatas['current_email_canada_english'];
    }elseif($aDatas['select_email'] == 'C' && $lg == 'F'){
        $mailContent = $aDatas['current_email_french'];
    }

    $sFname = explode(" ", $aDatas['name']);

	$subject = mail_subject[$lg];
	$message = '<html><body>';
	$message .= '<p style="font-size:16px;">'.hello[$lg].' '.$sFname[0].',</p>';
	$message .= $mailContent . "</br>";
	$message .= '<p style="font-size:16px;">'.warmly[$lg].',<br>'.UpdateBy().'</p><br><br>';

	if (!empty($aDatas['contact'])) {
	    $message .= mailFormat(contact[$lg], strip_tags($aDatas['contact']));
	}

	if (!empty($aDatas['name'])) {
	    $message .= mailFormat(name[$lg], strip_tags($aDatas['name']));
	}

	if (!empty($aDatas['consultant_number'])) {
	    $message .= mailFormat(consultant_number[$lg], strip_tags($aDatas['consultant_number']));
	}

	if (!empty($aDatas['mk_director'])) {
	    $message .= mailFormat(mk_director[$lg], strip_tags($aDatas['mk_director']));
	}

	if (!empty($aDatas['intouch_password'])) {
	    $message .= mailFormat(intouch_password[$lg], strip_tags($aDatas['intouch_password']));
	}

	if (!empty($aDatas['unit_number'])) {
	    $message .= mailFormat(unit_number[$lg], strip_tags($aDatas['unit_number']));
	}

	if (!empty($aDatas['package'])) {
	    $message .= mailFormat(package[$lg], strip_tags($aDatas['package']));
	}

	if (!empty($aDatas['total_text_program'])) {
	    $message .= mailFormat(total_text_program[$lg], $aDatas['total_text_program']);
	}

	if (!empty($aDatas['total_text_program7'])) {
	    $message .= mailFormat(total_text_program7[$lg], $aDatas['total_text_program7']);
	}

	if (!empty($aDatas['unit_size'])) {
	    $message .= mailFormat(unit_size[$lg], $aDatas['unit_size']);
	}

	if (!empty($aDatas['hidden_newsletter'])) {
	    $message .= mailFormat(hidden_newsletter[$lg], $aDatas['hidden_newsletter']);
	}

	if (!empty($aDatas['distribution_one'])) {
	    $message .= mailFormat(distribution_one[$lg], $aDatas['distribution_one']);
	}

	if (!empty($aDatas['package_value'])) {
	    $message .= mailFormat(package_value[$lg], $aDatas['package_value']);
	}

	if (!empty($aDatas['distribution_two'])) {
	    $message .= mailFormat(distribution_two[$lg], $aDatas['distribution_two']);
	}

	if (!empty($aDatas['closing_ecards'])) {
	    $message .= mailFormat(closing_ecards[$lg], $aDatas['closing_ecards']);
	}

	if (!empty($aDatas['birthday_one']) && $aDatas['birthday_one']==2) {
	    $message .= mailFormat(birthday_one[$lg], 'X - 2 pts');
	}

	if (!empty($aDatas['birthday_one']) && $aDatas['birthday_one']==1) {
	    $message .= mailFormat(birthday_one1[$lg], 'X - 1 pts');
	}

	if (!empty($aDatas['anniversary_one']) && $aDatas['anniversary_one']==2) {
	    $message .= mailFormat(anniversary_one[$lg], 'X - 2 pts');
	}

	if (!empty($aDatas['anniversary_one']) && $aDatas['anniversary_one']==1) {
	    $message .= mailFormat(anniversary_two[$lg], 'X - 1 pts');
	}

	if (!empty($aDatas['status_one'])) {
	    $message .= mailFormat(status_one[$lg], 'X - 1 pts');
	}

	if (!empty($aDatas['status_two'])) {
	    $message .= mailFormat(status_two[$lg], 'X - 1 pts');
	}

	if (!empty($aDatas['status_three'])) {
	    $message .= mailFormat(status_three[$lg], 'X - 1 pts');
	}

	if (!empty($aDatas['status_four'])) {
	    $message .= mailFormat(status_four[$lg], 'X - 1 pts');
	}

	if (!empty($aDatas['status_five'])) {
	    $message .= mailFormat(status_five[$lg], 'X - 1 pts');
	}

	if (!empty($aDatas['status_six'])) {
	    $message .= mailFormat(status_six[$lg], 'X - 1 pts');
	}

	if (!empty($aDatas['status_seven'])) {
	    $message .= mailFormat(status_seven[$lg], 'X - 2 pts');
	}

	if (!empty($aDatas['amount_box'])) {
	    $message .= mailFormat(amount_box[$lg], $aDatas['amount_box']);
	}

	if (!empty($aDatas['accumulative'])) {
	    $message .= mailFormat(accumulative[$lg], 'X - 1 pts');
	}

	if (!empty($aDatas['monthly'])) {
	    $message .= mailFormat(monthly[$lg], 'X - 1 pts');
	}

	if (!empty($aDatas['status_seven0'])) {
	    $message .= mailFormat(status_seven0[$lg], 'X - 1 pts');
	}

	if (!empty($aDatas['status_eight'])) {
	    $message .= mailFormat(status_eight[$lg], 'X - 1 pts');
	}

	if (!empty($aDatas['status_nine'])) {
	    $message .= mailFormat(status_nine[$lg], 'X - 1 pts');
	}

	if (!empty($aDatas['last_one']) && $aDatas['last_one'] == '2') {
	    $message .= mailFormat(last_one[$lg], 'X - 2 pts');
	}

	if (!empty($aDatas['last_one']) && $aDatas['last_one'] == '1') {
	    $message .= mailFormat(last_two[$lg], 'X - 1 pts');
	}

	if (!empty($aDatas['gift_one']) && $aDatas['gift_one'] == 3) {
	    $message .= mailFormat(gift_one[$lg], 'X - 3 pts');
	}

	if (!empty($aDatas['gift_five']) && $aDatas['gift_five'] == '2') {
	    $message .= mailFormat(gift_five[$lg], 'X - 2 pts');
	}

	if (!empty($aDatas['star_program']) && $aDatas['star_program'] == '2') {
	    $message .= mailFormat(star_program[$lg], 'X - 2 pts');
	}

	if (!empty($aDatas['consultant_one'])) {
	    $message .= mailFormat(consultant_one[$lg], 'X - 1 pts');
	}

	if (!empty($aDatas['consultant_two'])) {
	    $message .= mailFormat(consultant_two[$lg], 'X - 1 pts');
	}

	if (!empty($aDatas['consultant_three'])) {
	    $message .= mailFormat(consultant_three[$lg], 'X - 1 pts');
	}

	if (!empty($aDatas['consultant_five'])) {
	    $message .= mailFormat(consultant_five[$lg], 'X - 1 pts');
	}

	if (!empty($aDatas['consultant_six'])) {
	    $message .= mailFormat(consultant_six[$lg], 'X - 1 pts');
	}

	if (!empty($sNewsLetterDesign)) {
	    $message .= mailFormat(newsletter_design[$lg], strip_tags($sNewsLetterDesign));
	}

	if (!empty($aDatas['email_newsletter'])) {
	    $message .= mailFormat(email_news[$lg], '$'.email_newsletter);
	}

	if (!empty($aDatas['digital_biz_card'])) {
	    $message .= mailFormat(digitalBizCard[$lg], '$'.DIGITAL_BIZ_CARD);
	}

	if (!empty($aDatas['canada_service'])) {
	    $message .= mailFormat(canadaService[$lg], '$'.canada_service);
	}

	if (!empty($aDatas['facebook'])) {
	    $message .= mailFormat(facebook_news[$lg], '$'.facebook);
	}

	if (!empty($aDatas['facebook_everything'])) {
	    $message .= mailFormat(facebook_everything_news[$lg], '$'.facebook_everything);
	}

	if (!empty($aDatas['other_language_newsletter'])) {
	    $message .= mailFormat(other_lang_newsletter[$lg], '$'.other_language_newsletter);
	}

	if (!empty($aDatas['newsletter_color'])) {
   		$message .= mailFormat(newsletter_color_constant_val[$lg], $aDatas['newsletter_color']);
	}

	if (!empty($aDatas['newsletter_black_white'])) {
	    $message .= mailFormat(newsletter_black_white[$lg], $aDatas['newsletter_black_white']);
	}

	if (!empty($printingT)) {
	    $message .= mailFormat(print_option[$lg], $printingT);
	}

	if (!empty($aDatas['email'])) {
	    $message .= mailFormat(email[$lg], strip_tags($aDatas['email']));
	}

	if (!empty($aDatas['cell_number'])) {
	    $message .= mailFormat(cell_number[$lg], strip_tags($aDatas['cell_number']));
	}

	if (!empty($aDatas['reffered_by'])) {
	    $message .= mailFormat(reffered_by[$lg], strip_tags($aDatas['reffered_by']));
	}

	if (!empty($aDatas['first_bill_date'])) {
	    $message .= mailFormat(first_bill_date[$lg], strip_tags($aDatas['first_bill_date']));
	}

	if (!empty($decrypted)) {
	    $message .= mailFormat(account_number[$lg], strip_tags($decrypted));
	}


	if (!empty($aDatas['cu_routing'])) {
	    $message .= mailFormat(cu_routing[$lg], strip_tags($aDatas['cu_routing']));
	}

	if (!empty($sPersonalUnitAppMsg)) {
	    $message .= mailFormat(personal_unit_app[$lg], $sPersonalUnitAppMsg);
	}

	if (!empty($sPersonalUnitAppCanadaMsg)) {
	    $message .= mailFormat(personal_unit_app_ca[$lg], strip_tags($sPersonalUnitAppCanadaMsg));
	}

	if (!empty($sPersonalUnitWebMsg)) {
	    $message .= mailFormat(personal_website[$lg], $sPersonalUnitWebMsg);
	}

	if (!empty($sWebsiteLinkMsg)) {
	    $message .= mailFormat(website_link[$lg], $sWebsiteLinkMsg);
	}

	if (!empty($sPersonalUrlMsg)) {
	    $message .= mailFormat(personal_url[$lg], $sPersonalUrlMsg);
	}

	if (!empty($sSubscriptionUpdatesMsg)) {
	    $message .= mailFormat(subscription_updates[$lg], $sSubscriptionUpdatesMsg);
	}

	if (!empty($sAppColorMsg)) {
	    $message .= mailFormat(app_color[$lg], $sAppColorMsg);
	}

	if (!empty($mask)) {
	    $message .= mailFormat(mask[$lg], strip_tags(mysqli_real_escape_string($sConnection, $mask)) );
	}

	if (!empty($aDatas['package_pricing'])) {
	    $message .= mailFormat(total_charges[$lg], $aDatas['package_pricing']);
	}
	$message .= "</body></html>";
	
	$CI->email->set_mailtype("html");
    $CI->email->from('office@unitassistant.com', 'Unit Assistant Team');
    $CI->email->to($aDatas['email']);
    $CI->email->bcc('office@unitassistant.com');
    $CI->email->subject($subject);
    $CI->email->message($message);
    $status = $CI->email->send();

    if ($status) {
    	return $message;
    }
}

function getHiddenNewsletterName($hidden_newsletter){
	return (($hidden_newsletter == 'SB') ? 'Simple Both' : (($hidden_newsletter == 'AB') ? 'Advanced Both' : (($hidden_newsletter == 'SS') ? 'Simple Spanish' : (($hidden_newsletter == 'AS') ? 'Advanced Spanish' : (($hidden_newsletter == 'AE') ? 'Advanced English' : (($hidden_newsletter == 'AB') ? 'Advanced Both' : (($hidden_newsletter == 'AC') ? 'Advanced  English & French' : (($hidden_newsletter == 'SE') ? 'Simple English' : (($hidden_newsletter == 'no') ? 'Unsubscribed' : 'Unsubscribed ')))))))));
}

function sendDesignChangedMail($data){
	$CI = & get_instance();
	$sNewletter = getHiddenNewsletterName($data['hidden_newsletter']);
	$sDirectorState = (($data['design_two'] == 'Y') ? 'IN' : (($data['design_two'] == 'N') ? 'OUT' : ''));
	$subject = 'Unit Assistant - Newsletter design changed';
	$sMessage = '<html><body>';
	if (!empty($data['name'])) {
        $sMessage .= mailFormat('Client Name', $data['name']); 
    }

    if (!empty($sNewletter)) {
        $sMessage .= mailFormat('Newsletter Design', $sNewletter); 
    }

    if (!empty($sDirectorState)) {
        $sMessage .= mailFormat('STATS IN- I O- OUT', $sDirectorState); 
    }

    if (!empty($data['wholesale_amount'])) {
        $sMessage .= mailFormat('Wholesale -Show wholesale amounts', $data['wholesale_amount']); 
    }

    if (!empty($data['wholesale_section'])) {
        $sMessage .= mailFormat('Show Directors name', $data['wholesale_section']); 
    }

    if (!empty($data['court_sale'])) {
        $sMessage .= mailFormat('Court of Sales~Totals for consultant showing', $data['court_sale']); 
    }

    if (!empty($data['court_sale_director'])) {
        $sMessage .= mailFormat('CourT of Sales~Director out of stats', $data['court_sale_director']); 
    }

    if (!empty($data['court_sharing'])) {
        $sMessage .= mailFormat('Court of Sharing~Commission showing for consultants', $data['court_sharing']); 
    }
    
    if (!empty($data['court_sharing_director'])) {
        $sMessage .= mailFormat('Court of Sharing - Show Director Name', $data['court_sharing_director']); 
    }

    if (!empty($data['birthday_rec'])) {
        $sMessage .= mailFormat('Birthday Recognition- show them', $data['birthday_rec']); 
    }

    if (!empty($data['birthday_anniversary'])) {
        $sMessage .= mailFormat('Birthday/Anniversary Dates', $data['birthday_anniversary']); 
    }

    if (!empty($data['wholesale_remove_name'])) {
        $sMessage .= mailFormat('Wholesale - Remove any names under', $data['wholesale_remove_name']); 
    }

    if (!empty($data['wholesale_remove'])) {
        $sMessage .= mailFormat('Wholesale - Remove any amounts under this amount but leave names Amount', $data['wholesale_remove']); 
    }

    if (!empty($data['special_news_request'])) {
        $sMessage .= mailFormat('Special Newsletter Requests', $data['special_news_request']); 
    }

    if (!empty($data['beatly_url'])) {
        $sMessage .= mailFormat('English Etiny Url', $data['beatly_url']); 
    }

    if (!empty($data['beatly_url_one'])) {
        $sMessage .= mailFormat('Spanish Etiny Url', $data['beatly_url_one']); 
    }

	$sMessage .= '</html></body>';

	$CI->email->set_mailtype("html");
    $CI->email->from('office@unitassistant.com', 'Unit Assistant Team');
    $CI->email->to('cherylt@unitassistant.com');
    $CI->email->subject($subject);
    $CI->email->message($sMessage);
    $send = $CI->email->send();
    return $send;
}

function sendAccountRoutingChangedMail($name, $nCvAccounts, $nCuRouting){
	$CI = & get_instance();
	$subject = 'Unit Assistant - Account/Routing changed';
	$sMessages = '<html><body>';
	if (!empty($name)) {
        $sMessages .= mailFormat('Client Name', $name); 
    }

    if (!empty($nCvAccounts)) {
    	$sMessages .= mailFormat('CV Account', $nCvAccounts); 
    }

    if (!empty($nCuRouting)) {
    	$sMessages .= mailFormat('CU Routing', $nCuRouting); 
    }

	$CI->email->set_mailtype("html");
    $CI->email->from('office@unitassistant.com', 'Unit Assistant Team');
    $CI->email->to('tamid@unitassistant.com');
    $CI->email->subject($subject);
    $CI->email->message($sMessages);
    $send = $CI->email->send();
    return $send;
}

function mailFormat($key, $val){
	return '<div class="row" style="border-bottom: 1px solid #ccc;">
            <div class="col-xs-6" style="width: 50%; float: left;">
                <p style="text-align: left;font-weight: 800;">'.$key.':</p>
            </div>
            <div class="col-xs-6" style="width: 50%; float: left;">
                <p style="text-align: left;font-weight: 600;">'.$val.'</p>
            </div>
            <div class="clearfix" style="clear: both;"></div>
        </div>';
}


?>