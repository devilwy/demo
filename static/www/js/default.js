//设为首页
function sethome(obj,url){
	try{
		obj.style.behavior = 'url(#default#homepage)';
		obj.sethomepage(url);
	}catch(e){
		if(window.netscape){
			try{
				netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
			}catch(e){
				alert("感谢您光临本站\n\n\t您正在使用的浏览器无法正确添加到设为主页上\n\n\t请您手动进行设置！");
				return false;
			}
			var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
			prefs.setCharPref('browser.startup.homepage', url);
		}
	}
	return false;
}
function addCookie() {
		if (document.all) {
				window.external.addFavorite('http://www.ckgsb.com', 'ckgsb多人在线视频');
		}
		else if (window.sidebar) {
				window.sidebar.addPanel('ckgsb多人在线视频', 'http://www.ckgsb.com', "");
		}
	};