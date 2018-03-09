//default.js
function verifyOneCheckMinimum(formName, msg, prefix)
{
    n = "all";
    if (prefix){
        n = prefix + n;
    }
    f = document.getElementById(formName);
    i = 0;
    j = 0;
    while (e = f.elements[i])
    {
        if (e.type == "checkbox" && e.id != n && e.checked)
        {
            if (!prefix || e.id.indexOf(prefix) != -1){
                j++;
            }
        }
        i++;
    }

    result = (j > 0);

    if (!result && msg)
    {
        alert(msg);
		alert(result);
    }

    return result;
}

function submitForm(formName, opValue, msg)
{
    if (msg)
    {
        if (!verifyOneCheckMinimum(formName))
        {
            alert(msg);
            return false;
        }
    }

    f = document.getElementById(formName);
    f.op.value = opValue;
    return true;
}

function toggleAllChecks(formName, prefix)
{
    n = "all";

    if (prefix)
    {
        n = prefix + n;
    }

    i = 0;
    e = document.getElementById(n);
    s = e.checked;
    f = document.getElementById(formName);

    while (e = f.elements[i])
    {
        if (e.type == "checkbox" && e.id != n)
        {
            if (!prefix || e.id.indexOf(prefix) != -1)
            {
                e.checked = s;
            }
        }

        i++;
    }
}



/**
 * opens the window where users can choose their own template. The destination url is hardcoded
 */
function openTemplateChooserWindow()
{
	TemplateSelectorWindow = window.open( '?op=blogTemplateChooser', 'TemplateChooser','scrollbars=yes,resizable=yes,toolbar=no,height=600,width=450');
}

/**
 * tells the parent window which template we chose
 */
function blogTemplateSelector( templateId )
{
	templateSelectList = parent.opener.document.blogSettings.blogTemplate;
	
	// loop throough the array with the different template sets and if we find the
	// one that the use just selected, then automatically select it and quit the loop
	for( i = 0; i < templateSelectList.options.length; i++ ) {
		if( templateSelectList.options[i].value == templateId ) {
			templateSelectList.selectedIndex = i;
			break;
		}
	}
	
	window.close();
}

/**
 * the functions below are used in the "global settings" page, so that 
 * whole blocks of the html page can appear and disappear when needed
 */
// there is no current section selected
var currentSection='';
sections = ["general","summary","templates","urls","email","uploads","helpers","interfaces","security","bayesian","resources","search"];

function _toggle( sectionId )
{
 // get the dom object with such section
 element = document.getElementById( sectionId );
 
 currentStatus = element.style.display;
 window.alert('sectionId = '+sectionId+' - current status ='+currentStatus);
 
 // and toggle its visibility
 if( element.style.display == 'none' )
   element.style.display = 'block';
 else
   element.style.display = 'none';
  
 return true;
}

function toggleSection(sectionId)
{
 // if no section selected, do nothing
 if( sectionId == 'none' )
   return;

 toggleAll( false );
 
 // and toggle the new one
 _toggle(sectionId);

 // now we have a new current section
 currentSection = sectionId;
   
 return true;  
}

function toggleAll( enabled )
{
  if( enabled ) statusString = 'block';
  else statusString = 'none';
  
  for( i = 0; i < sections.length; i++ ) {
    element = document.getElementById( sections[i] );
    element.style.display = statusString;
  }
}

/**
 * automatically selects all the elements of a list
 */
function listSelectAll(listId)
{
	list = document.getElementById( listId );
	for( i = 0; i < list.options.length; i++ ) {
		list.options[i].selected = true;
	}

	return true;
}

/**
 * then menu jump
 */
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

//func.js

//功能：判断输入的参数串 str 是否为空
//返回值： 如果str是空字符则返回True ，否则返回False
function isEmpty(str) {
	blankStr =" ";  //包含英文空格和一个中文空格	
	var strLength = str.length;
	if(strLength==0) {  //str =="" （等于空字符串）		
		return true;
	}
	else {  //str 含有字符，还必须验证str是否全是空格字符
		var j=0;
		for (i=0;i<strLength;i++) {
			if(blankStr.indexOf(str.substring(i,i+1))>=0) {
				j++;
			}
		}
		if(j==strLength)  //输入了的字符全是空格
			return true;		
		else 
			return false;	
	}
}

//功能：判断输入的参数串 str 是否为整数
//返回值： 如果str是整数，则返回True ，否则返回 False
function isInt(str) {
	var theMask = "0123456789";
	var strLength=str.length;
	if (strLength==0)
		return false;
    for(var i=0;i<str.length;i++) {
    	if(theMask.indexOf(str.substring(i,i+1))==-1) { //发现str中含有非数字符号
		    return false;
	    }
    }
    return true;
}

//功能：判断输入的参数串 str 是否为实数 （检测不是很严格）
//返回值： 如果str是实数，则返回True ，否则返回 False
function isReal(str) {
	var theMask = "0123456789.";
	var strLength=str.length;
	if (strLength==0)
		return false;
    for(var i=0;i<str.length;i++) {
    	if(theMask.indexOf(str.substring(i,i+1))==-1) {
		    return false;
	    }
    }
    return true;
}

//功能： 判断参数 str 是否为合法的标识符。可用来判断是否含有中文字符，如果含有中问则返回值为 False
//返回值： 如果str是合法的英文标识符则返回True ，否则返回 False （例如含有中文字符时）
function isEnglishString(str) {
  var theMask = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    for(var i=0;i<str.length;i++) {
    	if(theMask.indexOf(str.substring(i,i+1))==-1) {
		    return false; //一旦发现非法的字符则返回False
		    break;
	    }
    }
    return true;
 }

//功能： 判断参数 str 是否为合法的日期格式（规定格式如: 2002-11-24）
//返回值： 如果日期 str 是合法则返回True ，否则返回 False 
function isDate(str)  {
  var the1st =str.indexOf('-');
  var the2st =str.lastIndexOf('-');
  if(the1st==the2st) {alert("日期格式不正确！应该为：年-月-日 \n  例如：2001-12-11");return false;}
  else {
     var y=str.substring(0,the1st);
     var m=str.substring(the1st+1,the2st);
     var d=str.substring(the2st+1,str.length);
     var maxdays=31;
     if((isInt(y)==false)||(isInt(m)==false)||(isInt(d)==false)) {alert("日期格式不正确！应该为：年-月-日 \n 例如：2001-11-24"); return false;}
     else if(y.length<4) {alert("年份不正确！\n\n日期格式为：年-月-日   \n 例如：2002-11-24");return false;}
     else if((m<0)||(m>12)) {alert("月份不正确！\n\n日期格式为：年-月-日 \n 例如：2002-12-11");return false;}
     else if(m==4||m==6||m==9||m==11)  maxdays=30;
          else if(m==2) {
              if(y%4>0) maxdays=28;
              else if(y%100==0&&y%400>0) maxdays=28;
              else maxdays=29;
              }
          else maxdays=31;
     if(d>maxdays) {alert("该月份天数不正确！\n\n日期格式为：年-月-日 \n 例如：2002-11-24");return false;}
     else {return true;}
  }
 }
//功能： 判断参数 str 是否为合法的时间格式（规定格式如: 11:30）
//返回值： 如果时间 str 是合法则返回True ，否则返回 False 
function isTime(str)  {
  var the1st =str.indexOf(':');
     var h=str.substring(0,the1st);
     var m=str.substring(the1st+1,str.length);     
	// alert(h);
	// m=Math.abs(m);
	//alert(m);
	 
     if((isInt(h)==false)||(isInt(m)==false)) {alert("时间格式不正确！应该为：小时:分钟 \n 例如：11:30"); return false;}
	 else if((h<0)||(h>24)) {alert("小时不正确！\n\n时间格式为：小时:分钟 \n 例如：11:30");return false;}
     else if((m<0)||(m>60)) {alert("分钟不正确！\n\n日期格式为：小时:分钟 \n 例如：11:30");return false;}
     else {return true;}
}
//功能：在输入时候检测是否存在非法字符，如果存在则提醒用户，并删除最后输入字符
//用法：在input的onKeyup事件中调用inputMask(this,format)
//      其中format为'int','real','posInt','posReal'
//      如果为了严格检查，还可以在onChange事件中检查多一次
//Init:2002-08-08
//Modify:2002-08-10
function inputMask(obj,format) {
	var posRealMask =  "0123456789.";
	var posIntMask = "0123456789";
	var realMask =  "0123456789.-";
	var intMask = "0123456789-";

	var objLength = obj.value.length;
	if (objLength==0)
		return false;
	else if (format == "posInt" || format == "posint") {
		if (posIntMask.indexOf(obj.value.charAt(objLength-1))==-1) {
//			alert("该输入框只能输入正整数");
			obj.value=obj.value.substring(0,objLength-1);
			inputMask(obj,'posInt'); //递归查找是否存在非法字符
			return false;
		}
		return true;
	}//end if int
	else if (format == "posReal" || format == "posreal") {
		if (obj.value.charAt(objLength-1)==".") {  
		//如果出现小数点，则往前查询是否存在另外的小数点，确保不会有重复
			for(var i=0;i<objLength-1;i++) {
				if (obj.value.charAt(i)==".") {
//					alert("小数点不能出现两次");
					obj.value=obj.value.substring(0,objLength-1);
					return false;
				}
			}
		}
		if(posRealMask.indexOf(obj.value.charAt(objLength-1))==-1) {
//			alert("该输入框只能输入正实数");
			obj.value=obj.value.substring(0,objLength-1);
			inputMask(obj,'posReal'); //递归查找是否存在非法字符
			return false;
		}
		return true;
	}  //end if posReal
	else if (format == "int" || format == "Int") {
		if (obj.value.charAt(objLength-1)=="-" && objLength>1) {
		//确保负号不在第一位后出现
//			alert("负号不能在此处出现");
			obj.value=obj.value.substring(0,objLength-1);
			inputMask(obj,'int'); //递归查找是否有重复的负号
			return false;
		}
		if(intMask.indexOf(obj.value.charAt(objLength-1))==-1) {
//			alert("该输入框只能输入正负整数");
			obj.value=obj.value.substring(0,objLength-1);
			inputMask(obj,'int'); //递归查找是否存在非法字符
			return false;
		}
		return true;
	}//end if int
	else if (format == "real" || format == "Real") {
		if (obj.value.charAt(objLength-1)==".") {  
		//如果出现小数点，则往前查询是否存在另外的小数点，确保不会有重复
			for(var i=0;i<objLength-1;i++) {
				if (obj.value.charAt(i)==".") {
//					alert("小数点不能出现两次");
					obj.value=obj.value.substring(0,objLength-1);
					return false;
				}
			}
		}
		if (obj.value.charAt(objLength-1)=="-" && objLength>1) {
		//确保负号不在第一位后出现
//			alert("负号不能在此处出现");
			obj.value=obj.value.substring(0,objLength-1);
			inputMask(obj,'real'); //递归查找是否有重复的负号
			return false;
		}
		if(realMask.indexOf(obj.value.charAt(objLength-1))==-1) {
//			alert("该输入框只能输入正负实数");
			obj.value=obj.value.substring(0,objLength-1);
			inputMask(obj,'real'); //递归查找是否存在非法字符
			return false;
		}
		return true;
	}  //end if real
}//end Function inputMast(obj,format)

//功能检查输入的EMAIL是否正确
function isemail (s)
{
        // Writen by david, we can delete the before code
        if (s.length > 100){
                window.alert("email地址长度不能超过100位!");
                return false;
        }
         var regu = "^(([0-9a-zA-Z]+)|([0-9a-zA-Z]+[_.0-9a-zA-Z-]*))@([a-zA-Z0-9-]+[.])+([a-zA-Z]{2}|net|NET|com|COM|gov|GOV|mil|MIL|org|ORG|edu|EDU|int|INT)$"
		 var re = new RegExp(regu);
         if (s.search(re) != -1) {
               return true;
         } else {
               window.alert ("请输入有效合法的E-mail地址 ！")
               return false;
         }
}

//-----------------
function cancelaction(b,tmsg){
   if(b){
       event.returnValue=false;
       alert(tmsg);
   }
}

function checkedCheckbox(form_name,box_name)
{
	var j=0;
	for(i=0;i<form_name.elements .length;i++){
			var e=form_name.elements[i];
			if((e.name==box_name)&&(e.checked==true)) {
					j++;                        
			}
	}
	if (j==0){
		return false;
	}
	return true;
}

function ResizeImage(obj, MaxW, MaxH)
{
	//check it;
	if(typeof(obj)!="object" || isNaN(MaxW) || isNaN(MaxH))
		return null;
	//run;
	var intWidth=obj.width;
	var intHeight=obj.height;
	//alert(intWidth);
	//get cal;
	if(intWidth>MaxW || intHeight>MaxH)
	{
		var a=MaxW/intWidth;
		var b=MaxH/intHeight;
		if(b<a)
			a=b;
		intWidth*=a;
		intHeight*=a;
		obj.width=intWidth;
		obj.height=intHeight;
	}
	else if(intWidth==0 && intHeight==0)
	{
		obj.width=MaxW;
	}
	obj=null;
	intWidth=null;
	intHeight=null;
	return null;	
}

function isWhiteWpace (s)
{
  var whitespace = " \t\n\r";
  var i;
  for (i = 0; i < s.length; i++){   
     var c = s.charAt(i);
     if (whitespace.indexOf(c) >= 0) {
		  return true;
	  }
   }
   return false;
}

//=============处理图象==================
function checkimages(){
	var i;
	var topic=document.getElementById("viewtopic")
	if (topic){
		var topicimages=topic.getElementsByTagName("img")
			if(topicimages){
				var iarray=topicimages;
				for(i in iarray){
					if(iarray[i].width>600){
						iarray[i].width=600;
					}
				}
			}
	}
}

//imgad.js

<!--

var iWritedCount= 0;
var aDoc,i
try 
{
	if(strLineData.length==0){
		strLineData="";
	}	
}
catch(e)
{
	var strLineData="";
}
aDoc = strLineData.split("|*|");//数据分隔符

aWritedImg = new Array(aDoc.length);
for(i=0; i<aWritedImg.length; i++ ){
	
	aWritedImg[i]=0;
}
function WriteImg()
{
	
	var i,z;
	do{
		i = parseInt( Math.random( )*(aDoc.length-1) );
	}while( aWritedImg[ i ] > 0 && iWritedCount<aDoc.length-1);
	
	if( iWritedCount >= aDoc.length-1 )
	{
		return;
	}
	aWritedImg[ i ] ++;
	iWritedCount ++;
	switch( GetFileExt( aDoc[i] ) )
	{
		
		case ".swf":
			
			document.write('<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width=778 height=90>' + 
 							'<PARAM NAME=movie VALUE="' + path + GetFileSrc(aDoc[i]) + '"> <PARAM NAME=quality VALUE=high><param name=wmode value=opaque>' + 
 							'<EMBED src="' + path + GetFileSrc(aDoc[i]) + '" quality=high TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" width=778 height=90></EMBED></OBJECT>');
			break;
			
		case ".gif":
		case ".jpg":
			document.write('<a href="'+GetFileUrl(aDoc[i])+'" target="_blank"><img src="' + path + GetFileSrc(aDoc[i]) + '" border=0></a>');

			break;
			
		default:
			break;
	}
	
}
function GetFileExt( str )
{
	var i = str.lastIndexOf(".");
	if( i != -1 )
		return str.substr(i).toLowerCase( );
	else
		return "";
}
function GetFileUrl( str )
{
	var s = str.indexOf("|@|");
	if( s != -1 )
		return str.substr(0,s);
	else
		return "";
}
function GetFileSrc( str )
{
	var g = str.lastIndexOf("|@|");
	if( g != -1 )
		return str.substr(g+3);
	else
		return "";
}
function showTags(objC)
{
	var objField=document.getElementById("tagfield");
	var colField=objField.getElementsByTagName("TR");
	for(i=0;i<colField.length;i++)
	{
		if(colField[i].style.display=="none")
			colField[i].style.display="";
	}
	objC.innerHTML="";
	return false;
}

function ShowMPImages(ImgArr,subsname,mode,value,baseurl)
{
	var alttext;
	var result="";
	var baseurl=baseurl;
	var i=0;
	var space=0;
	var z=10;
	var imgPath=new Array("1.gif","2.gif","3.gif","4.gif");
	
	//
	if (mode=='M')	{	var Path_img=baseurl+'imgs/money';var alttext=subsname+'的积分为：'+value;}
	if(mode=='P')	{  var Path_img=baseurl+'imgs/popular';var alttext=subsname+'的受欢迎程度为：'+value;}
	result='<a title="'+alttext+'"><div style="position:absolute; width:100px; height:20px; z-index:1; ">';
	
	for(i=4;i>0;i--)
	{
		for(var j=0;j<ImgArr[i];j++)
		{
			space+=6;
			var a=i-1;
			result+='<div style="position:absolute; width:20px; height:20px; z-index:'+z+
					'; left:'+space+'px; "><img src='+Path_img+imgPath[a]+' alt="'+alttext+'" border=0></div></a>';
			z--;
		}
	}
	result+='</div>';
	document.write(result);	
}	
//我的门户菜单

	var curMenu=null;
	var curMenuItem=null;
	var lastMenu=null;
	var menupair=new Object();
	var remenupair=new Object();
	var menuon=false;
	
	//config options;
	
	var curMenuStyle="memucur";
	var curMenuItemStyle="highlight";
	var menuHighLightStyle="highlight";
	var menuDefaultStyle="";
	var menuItemsActiveStyle="active";
	
	menupair["link"]="linkMenu";
	menupair["express"]="expressMenu";
	menupair["myphoto"]="myphotoMenu";
	menupair["myblog"]="myblogMenu";
	menupair["mycontact"]="mycontactMenu";
	menupair["profile"]="profileMenu";
	menupair["mymailbox"]="mymailboxMenu";
	menupair["myrss"]="myrssMenu";
	
	//end config
	
	for(var i in menupair)
	{
		remenupair[menupair[i]]=i;
	}
	
	function setCurMenu()
	{
		if(!menuon && lastMenu!=null && lastMenu!=curMenu)
		{
			document.getElementById(lastMenu).className="";
			showMenu(curMenu);
		}
	}
	
	function setMenuInit(aId,aItemId)
	{
		if(typeof(aId)!="string" || typeof(aItemId)!="string")
			return;
		//init global;
		var oMenuBar=document.getElementById("menubar");
		var oMenuItems=document.getElementById("menuitems");
		oMenuBar.onmouseover=function()
		{
			menuon=true;
		}
		oMenuBar.onmouseout=function()
		{
			menuon=false;
			setTimeout("setCurMenu()",500);
		}
		oMenuItems.onmouseover=function()
		{
			menuon=true;
			if(lastMenu!=curMenu)
				document.getElementById(lastMenu).className=menuHighLightStyle;
		}
		oMenuItems.onmouseout=function()
		{
			menuon=false;
			setTimeout("setCurMenu()",500);
		}
		curMenu=aId;
		lastMenu=aId;
		curMenuItem=aItemId;
		var oMenu=document.getElementById(curMenu);
		var oMenuItem=document.getElementById(aItemId);
		oMenu.className=curMenuStyle;
		oMenuItem.className=curMenuItemStyle;
		showMenu(curMenu);
		for(var i in menupair)
		{
			var oT=document.getElementById(i);
			var oTm=document.getElementById(menupair[i]);
			oT.onmouseover=function()
			{
				showMenu(this.id);
			}
			oTm.onmouseover=function()
			{
				if(remenupair[this.id]!=curMenu)
					document.getElementById(remenupair[this.id]).className=menuHighLightStyle;
				menuon=true;
			}
			oTm.onmouseout=function()
			{
				if(remenupair[this.id]!=curMenu)
					document.getElementById(remenupair[this.id]).className=menuDefaultStyle;
			}
		}
	}
	
	//show a items;
	
	function showMenu(aId)
	{
		if(typeof(aId)!="string")
			return;
		if(lastMenu!=null)
		{
			oMenu=document.getElementById(menupair[lastMenu]);
			oMenu.className=menuDefaultStyle;
		}
		lastMenu=aId;
		targetMenu=document.getElementById(menupair[aId]);
		targetMenu.className=menuItemsActiveStyle;
	}
//我的门户菜单

//评分下拉框
function ShowHPSelect(usepopular,startpopular,increase)
{
	var b;
	var selecthtml="";
	
	if (usepopular>=startpopular){
		var c=usepopular-startpopular;
		b=Math.floor(c/increase)+1;
		selecthtml+='<SELECT name=hit_point>';
		for(i=b;i>=-b;i--) {
			selecthtml+='<option value='+i;
			if (0==i) selecthtml+=' selected';
			if (i<0) selecthtml+='>'+Math.abs(i)+'个鸡蛋</option>';
			if (i==0) selecthtml+='>不评分</option>';
			if (i>0) selecthtml+='>'+i+'朵鲜花</option>';
		}
		selecthtml+=' </SELECT>';
	}
	document.write(selecthtml);
}	
	

//功能检查输入的是否链接地址正确
function isUrl (strurl)
{
        // Writen by david, we can delete the before code
        if (strurl.length > 200)
        {
                window.alert("地址长度不能超过200位!");
                return false;
        }
//         var regu = "^(([0-9a-zA-Z]+)|([0-9a-zA-Z]+[_.0-9a-zA-Z-]*[0-9a-zA-Z]+))@([a-zA-Z0-9-]+[.])+([a-zA-Z]{2}|net|NET|com|COM|gov|GOV|mil|MIL|org|ORG|edu|EDU|int|INT)$"
       //  var regu = "((http)://)?(((([d]+.)+){3}[d]+(/[w./]+)?)|([a-z]w*((.w+)+){2,})([/][w.~]*)*)";
		 var regu = /^http:\/\/[\w\-]+\.[\w\-]+\.\S+\.htm$/ig;
         if (regu.test(strurl)) {
               return true;
         } else {
               window.alert ("请输入有效合法的链接地址 ！")
               return false;
         }

}
function playmusic()
{
	//alert("dddddddddddd");autostart="true"
	if (navigator.userAgent.indexOf("MSIE") != -1){
		thissound=document.getElementById("page_music");
		thissound.play();
	}		
}	

function stopmusic()
{
	if (navigator.userAgent.indexOf("MSIE") != -1){
		thissound=document.getElementById("page_music");
		thissound.stop();
	}
}
//找出控件地址
function getControlsTop(e){
	var t=e.offsetTop;
	while(e=e.offsetParent){
		t+=e.offsetTop;
	}
	return(t);
}
function getControlsLeft(e){
	var l=e.offsetLeft;
	while(e=e.offsetParent){
		l+=e.offsetLeft;
	}
	return(l);
}

function ptype(name,value){
  this.name=name;
  this.value=value;
}
function subtype(name,value){
  this.name=name;
  this.value=value;
}

function getPTypeOptions(selected){
  options = "";
  if(!selected) selected=0;
  var i;
   ptype = _ptype[0];
  if(!ptype) return;

  for(i=0;i<ptype.length;i++){
	 if(ptype[i].name!=""){ 
    options+="<option value="+ptype[i].value+(selected==ptype[i].value?" selected":"")+">"+ptype[i].name+"</option>";
	}
  }
  return options;
}
function getSubTypeOptions(type,selected,includeall){
  if(!type)
  {type=ptype.length-1;}
  else
  {type=type-1;}
  options = "";
  subtype = _subtype[type];
  if(!subtype) return;
  var i=0;
  //if(!includeall && position.length>1) i=1;
  for(;i<subtype.length;i++){
	  if(subtype[i].name!=""){
    options+="<option value="+subtype[i].value+(selected==subtype[i].value?" selected":"")+">"+subtype[i].name+"</option>";
	}
  }
  return options;
}
function handlePTypeChangeEvent(src,des,selected,includeall){
	
  if(!src.value) return;
 
  subtype = _subtype[src.value-1];
  if (src.value==0 || src.value==''){
	  subtype = _subtype[ptype.length-1];
  }
  alert(subtype);
  if(!subtype || !des) return;
  oOs = des.options;
  while(oOs.length>0){
    oOs.remove(0);
  }
  var i =0;
  //if(!includeall && position.length>1) i = 1;
  for(;i<subtype.length;i++){
	  if(subtype[i].name!=""){
    var oOption = document.createElement("OPTION");
    oOption.text=subtype[i].name;
    oOption.value=subtype[i].value;
    if(selected) if(subtype[i].value==selected) oOption.selected=true;
    oOs.add(oOption);
	}
  }
}

function ChangOption(strvalue,current_catg)
{
	$.ajax({
			type: "POST",
			url: "index.php?r=CACTaskMgr/Changeoption",
			data : "current_catg="+current_catg+"&project_id="+strvalue,
			success: function(data){
				$('#prj_catg_td').html(data);
			}
	});
	//var myAjax = new Ajax.Request('index.php',{method: 'post', parameters:pars,onComplete:media_reportMsg});
}
	function media_reportMsg(request)
	{
		var htmlDiv = document.getElementById("prj_catg_td");
		str=request.responseText;	
		alert(str);
		htmlDiv.innerHTML = str;	
		//ShowDataHtml('showmedia','op=MyMedia&tag=我的最爱','');
	}
//-->
