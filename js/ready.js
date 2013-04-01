
	$(function(){

		//判断是否登陆
		if($Jason.isLogin){
		$("#comment-txt").removeAttr('disabled');
		$("#comment-txt").val("");
		}

		//搜狗通行证
		$("#login").click(function(){
			showLoginBox();
		}); 

		function showLoginBox(){
		try{
		$.fancybox({'type':'ajax','ajax':{'url':'../loginbox.php?_' + new Date().getTime()},'padding':'0', 'scrolling':'no','centerOnScroll':true});
		} catch(e){
		return;
		}
		}
		function logOut(){
		PassportSC.logoutHandle(document.getElementById('welcomeinfo'), logoutApp, logoutApp);
		}
		function logoutApp() {
		top.window.location.href = currentLocation;
		}
		function psfocus(n){
		var id = n <= 2 ?"i_text_1":"i_text_2";
		if(n == 1 || n == 3){
		$("#"+id).addClass("h_i_text");
		$("#"+id).removeClass("i_text");
		}
		else{
		$("#"+id).addClass("i_text");
		$("#"+id).removeClass("h_i_text");
		}
		};

		setTimeout(function(){initToolsApp("sextfpath");}
			,500);
	});
