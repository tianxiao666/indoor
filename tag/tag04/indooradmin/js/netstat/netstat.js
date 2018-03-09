var imgUrl='http://tyzf.gd.ct10000.com/api.php?r=netStatAPI/JsCollect';
var js=document.getElementsByTagName('script');
var jsSrc=js[js.length-1].src;

// get script info
//var site_id=js[js.length-1].src.split('?')[1].split('=')[1];
var site_id;
function getSrcInfo()
{
	var scriptSrc = jsSrc.split(/\?/);
	if (scriptSrc.length <2 || !scriptSrc[1])
	{
		alert('请在js的src后加入?id=应用标识');
		return false;
	}
	
	// set site id
	var srcInfo = scriptSrc[1].split('&');
	var siteIdInfo = srcInfo[0].split('=');
	if (siteIdInfo.length <2 || !siteIdInfo[1])
	{
		alert('请在js的src后加入?id=e家应用标识');
		return false;
	}
	site_id = siteIdInfo[1];
	
	// set message
	if (srcInfo.length >1)
	{
		var messageInfo = srcInfo[1].split('=');
		if (messageInfo.length >1 && messageInfo[1])
		{
			imgUrl+='&message='+messageInfo[1];
		}
	}
	
	return true;
}

/*====================================================================
 浏览器参数对象
====================================================================*/
Browser = {};
Browser.userAgent = navigator.userAgent.toLowerCase();	//获取浏览器特征字符串

//获取浏览器类型、语言、版本
if(window.ActiveXObject){
	Browser.type = 'ie';
	Browser.ie = true;
	Browser.lang = navigator.userLanguage;
	tempVer= new RegExp('msie (\\d+\\.\\d+);');
	tempVer.test(Browser.userAgent);
	Browser.ver = RegExp['$1'];
	tempVer = null;
} else if(-1 < Browser.userAgent.indexOf('firefox')) {
	Browser.type = 'firefox';
	Browser.firefox = true;
	Browser.lang = navigator.language;
	Browser.ver =  Browser.userAgent.substr(Browser.userAgent.indexOf("firefox")+8);
} else if(-1 < Browser.userAgent.indexOf('opera')) {
	Browser.type = 'opera';
	Browser.opera = true;
	Browser.lang = navigator.language;
	if ('Opera' == navigator.appName) {	//Opera标准模式
		Browser.ver = parseFloat(navigator.appVersion);
	} else {	//Opera伪装其他浏览器模式
		tempVer= new RegExp('opera (\\d+\\.\\d+)');
		tempVer.test(Browser.userAgent);
		Browser.ver = parseFloat(RegExp['$1']);
		tempVer = null;
	}
} else if(-1 < Browser.userAgent.indexOf('AppleWebKit')) {
	Browser.type = 'safari';
	Browser.safari = true;
	Browser.lang = navigator.language;
	Browser.ver = 1;
} else if(-1 < Browser.userAgent.indexOf('konqueror')) {
	Browser.type = 'konqueror';
	Browser.konqueror = true;
	Browser.lang = navigator.language;
	Browser.ver = 1;
} else {
	Browser.type = 'unknown';
	Browser.lang = 'zh-cn';
	Browser.ver = 1;
};

//获取操作系统平台
tempPlatform = navigator.platform.toLowerCase();	//获取操作系统平台特征字符串
if (-1 < tempPlatform.indexOf('win')) {
	Browser.platform = 'windows';
} else if(-1 < tempPlatform.indexOf('mac')) {
	Browser.platform = 'mac';
} else if(-1 < tempPlatform.indexOf('linux')) {
	Browser.platform = 'linux';			
} else {
	Browser.platform = 'unknown';
};
tempPlatform = null;

/*====================================================================
 浏览器插件对象检测
====================================================================*/
var javascriptVersion1_1 = true;
var detectableWithVB = false;
var pluginFound = false;

function goURL(daURL) {
    if(javascriptVersion1_1) {
	window.location.replace(daURL);
    } else {
	window.location = daURL;
    };
    return;
};

function redirectCheck(pluginFound, redirectURL, redirectIfFound) {
    if( redirectURL && ((pluginFound && redirectIfFound) || 
	(!pluginFound && !redirectIfFound)) ) {
	goURL(redirectURL);
	return pluginFound;
    } else {
	return pluginFound;
    };	
};

function canDetectPlugins() {
    if( detectableWithVB || (navigator.plugins && navigator.plugins.length > 0) ) {
	return true;
    } else {
	return false;
    };
};

function detectFlash(redirectURL, redirectIfFound) {
    pluginFound = detectPlugin('Shockwave','Flash'); 
    if(!pluginFound && detectableWithVB) {
	pluginFound = detectActiveXControl('ShockwaveFlash.ShockwaveFlash.1');
    };
    return redirectCheck(pluginFound, redirectURL, redirectIfFound);
};

function detectDirector(redirectURL, redirectIfFound) { 
    pluginFound = detectPlugin('Shockwave','Director'); 
    if(!pluginFound && detectableWithVB) {
	pluginFound = detectActiveXControl('SWCtl.SWCtl.1');
    };
    return redirectCheck(pluginFound, redirectURL, redirectIfFound);
};

function detectQuickTime(redirectURL, redirectIfFound) {
    pluginFound = detectPlugin('QuickTime');
    if(!pluginFound && detectableWithVB) {
	pluginFound = detectQuickTimeActiveXControl();
    };
    return redirectCheck(pluginFound, redirectURL, redirectIfFound);
};

function detectReal(redirectURL, redirectIfFound) {
    pluginFound = detectPlugin('RealPlayer');
    if(!pluginFound && detectableWithVB) {
	pluginFound = (detectActiveXControl('rmocx.RealPlayer G2 Control') ||
		       detectActiveXControl('RealPlayer.RealPlayer(tm) ActiveX Control (32-bit)') ||
		       detectActiveXControl('RealVideo.RealVideo(tm) ActiveX Control (32-bit)'));
    };
    return redirectCheck(pluginFound, redirectURL, redirectIfFound);
};

function detectWindowsMedia(redirectURL, redirectIfFound) {
    pluginFound = detectPlugin('Windows Media');
    if(!pluginFound && detectableWithVB) {
	pluginFound = detectActiveXControl('MediaPlayer.MediaPlayer.1');
    };
    return redirectCheck(pluginFound, redirectURL, redirectIfFound);
};

function detectPlugin() {
    var daPlugins = detectPlugin.arguments;
    var pluginFound = false;
    if (navigator.plugins && navigator.plugins.length > 0) {
	var pluginsArrayLength = navigator.plugins.length;
	for (pluginsArrayCounter=0; pluginsArrayCounter < pluginsArrayLength; pluginsArrayCounter++ ) {
	    var numFound = 0;
	    for(namesCounter=0; namesCounter < daPlugins.length; namesCounter++) {
		if( (navigator.plugins[pluginsArrayCounter].name.indexOf(daPlugins[namesCounter]) >= 0) || 
		    (navigator.plugins[pluginsArrayCounter].description.indexOf(daPlugins[namesCounter]) >= 0) ) {
		    numFound++;
		};   
	    };

		if(numFound == daPlugins.length) {
		pluginFound = true;
		break;
	    };
	};
    };
    return pluginFound;
};

if ((navigator.userAgent.indexOf('MSIE') != -1) && (navigator.userAgent.indexOf('Win') != -1)) {
    document.writeln('<scr' + 'ipt language="VBscript">');
    
    document.writeln('detectableWithVB = False');
    document.writeln('If ScriptEngineMajorVersion >= 2 then');
    document.writeln('  detectableWithVB = True');
    document.writeln('End If');
    
    document.writeln('Function detectActiveXControl(activeXControlName)');
    document.writeln('  on error resume next');
    document.writeln('  detectActiveXControl = False');
    document.writeln('  If detectableWithVB Then');
    document.writeln('     detectActiveXControl = IsObject(CreateObject(activeXControlName))');
    document.writeln('  End If');
    document.writeln('End Function');
    
    document.writeln('Function detectQuickTimeActiveXControl()');
    document.writeln('  on error resume next');
    document.writeln('  detectQuickTimeActiveXControl = False');
    document.writeln('  If detectableWithVB Then');
    document.writeln('    detectQuickTimeActiveXControl = False');
    document.writeln('    hasQuickTimeChecker = false');
    document.writeln('    Set hasQuickTimeChecker = CreateObject("QuickTimeCheckObject.QuickTimeCheck.1")');
    document.writeln('    If IsObject(hasQuickTimeChecker) Then');
    document.writeln('      If hasQuickTimeChecker.IsQuickTimeAvailable(0) Then ');
    document.writeln('        detectQuickTimeActiveXControl = True');
    document.writeln('      End If');
    document.writeln('    End If');
    document.writeln('  End If');
    document.writeln('End Function');
    document.writeln('</scr' + 'ipt>');
};

//获取浏览器插件
/*Browser.Plugins={};
Browser.Plugins.Java = navigator.javaEnabled() ? 'Y' : 'N';
Browser.Plugins.Alexa = 'N';		//暂未做检测

if (Browser.ie) {
	Browser.Plugins.Flash = detectFlash() ? 'Y' : 'N';
	Browser.Plugins.WinMedia = detectWindowsMedia() ? 'Y' : 'N';
	Browser.Plugins.RealPlayer = detectReal() ? 'Y' : 'N';
} else {
	Browser.Plugins.Flash = navigator.mimeTypes["application/x-shockwave-flash"] ? 'Y' : 'N';
	Browser.Plugins.WinMedia = navigator.mimeTypes["application/x-mplayer2"] ? 'Y' : 'N';
	Browser.Plugins.RealPlayer = navigator.mimeTypes["audio/x-pn-realaudio-plugin"] ? 'Y' : 'N';
}// end if
*/

/*====================================================================
 访客信息提交
====================================================================*/
if (getSrcInfo() && document.title)
{
	imgUrl+='&SITE_ID='+site_id;
	//imgUrl+='&Java='+Browser.Plugins.Java;
	//imgUrl+='&Alexa='+Browser.Plugins.Alexa;
	//imgUrl+='&Flash='+Browser.Plugins.Flash;
	//imgUrl+='&WinMedia='+Browser.Plugins.WinMedia;
	//imgUrl+='&RealPlayer='+Browser.Plugins.RealPlayer;
	imgUrl+='&SITE='+document.title;
	imgUrl+='&PageUrl='+window.location;
	imgUrl+='&Referrer='+ (null == document.referrer ? '' : document.referrer);
	//imgUrl+='&ScreenSize='+screen.width+'x'+screen.height;
	//imgUrl+='&ScreenColor='+screen.colorDepth;
	//imgUrl+='&BrowserLang='+Browser.lang;
	imgUrl+='&Sid='+ Math.random();
//	document.write('<img src=\''+imgUrl+'\' />');
	(new Image()).src = imgUrl;
}

