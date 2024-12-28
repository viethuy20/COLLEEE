// select box
$(".exchanges__select__tab select").change(function(){
	var extraction_val = $(".exchanges__select__tab select").val();

	// すべて を選択
	if(extraction_val == "tab_all"){
		$('#tab_all').css('display', 'block');
		$('#tab_bank').css('display', 'none');
		$('#tab_e-money').css('display', 'none');
		$('#tab_gift').css('display', 'none');
		$('#tab_other').css('display', 'none');

	// 銀行 を選択
	}else if(extraction_val == "tab_bank") {
		$('#tab_all').css('display', 'none');
		$('#tab_bank').css('display', 'block');
		$('#tab_e-money').css('display', 'none');
		$('#tab_gift').css('display', 'none');
		$('#tab_other').css('display', 'none');

	// 電子マネー を選択
	}else if(extraction_val == "tab_e-money") {
		$('#tab_all').css('display', 'none');
		$('#tab_bank').css('display', 'none');
		$('#tab_e-money').css('display', 'block');
		$('#tab_gift').css('display', 'none');
		$('#tab_other').css('display', 'none');

	// ギフト券 を選択
	}else if(extraction_val == "tab_gift") {
		$('#tab_all').css('display', 'none');
		$('#tab_bank').css('display', 'none');
		$('#tab_e-money').css('display', 'none');
		$('#tab_gift').css('display', 'block');
		$('#tab_other').css('display', 'none');

	// 他社ポイント を選択
	}else if(extraction_val == "tab_other") {
		$('#tab_all').css('display', 'none');
		$('#tab_bank').css('display', 'none');
		$('#tab_e-money').css('display', 'none');
		$('#tab_gift').css('display', 'none');
		$('#tab_other').css('display', 'block');
	}
});