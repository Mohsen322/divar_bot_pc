function getUserInfo(field) {
	if (typeof Telegram !== 'undefined' && Telegram.WebApp && Telegram.WebApp.initDataUnsafe) {
		const initData = Telegram.WebApp.initDataUnsafe;

		if (field.startsWith('user.')) {
			const userField = field.split('.')[1];
			return initData.user && initData.user[userField] ? initData.user[userField] : null;
		}

		if (field.startsWith('chat.')) {
			const chatField = field.split('.')[1];
			return initData.chat && initData.chat[chatField] ? initData.chat[chatField] : null;
		}

		return initData[field] || null;
	} else {
		console.error('Telegram WebApp is not available or initDataUnsafe is not accessible.');
		return null;
	}
}

const user_id = getUserInfo('user.id');
const first_name = getUserInfo('user.first_name');
const last_name = getUserInfo('user.last_name');
const username = getUserInfo('user.username');
const is_premium = getUserInfo('user.is_premium');
const photo_url = getUserInfo('user.photo_url');
const chat_id = getUserInfo('chat.id');
const startParam = getUserInfo('start_param');
const authDate = getUserInfo('auth_date');
const allows_write_to_pm = getUserInfo('user.allows_write_to_pm');

$(document).on('input', '.numberFormat', function(event){
	var value = $(this).val();
	value = value.replace(/,/g, ''); // delete commas
	if (!isNaN(value)) {
		var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
		$(this).val(formattedValue);
	}
});	

// نمایش لودینگ اسپینر هنگام شروع درخواست AJAX
$(document).ajaxStart(function() {
	$('#loading').show();
});

// مخفی کردن لودینگ اسپینر پس از اتمام درخواست AJAX
$(document).ajaxStop(function() {
	$('#loading').hide();
});	

$("#show_filters").click(function(e) {
	$("#filter_options").slideToggle();
});

$("#profile_pic").click(function(e) {
	$("#profile_details").slideToggle();
});

var $goToTopButton = $('#go_to_top');

$(window).scroll(function() {
	if ($(this).scrollTop() > 350) {
		$goToTopButton.addClass('go_to_top_show');
	} else {
		$goToTopButton.removeClass('go_to_top_show');
	}
});

$goToTopButton.click(function() {
	$('html, body').animate({ scrollTop: 0 }, 'smooth');
});