function PopUp_win(wi,he,file,target){
	window.open(file ,target,"scrollbars=1,resizable=1,width=" + wi + ",height=" + he + ",left=100,top=100");
	if(navigator.appName.charAt(0) == "N" && navigator.appVersion.charAt(0) >= 3){
		file.focus();
	}
}