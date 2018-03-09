/*
*
*$("selector").popup(opts) : return formatted string (and print)
*auth iorichina
*
*$("selector").popup();
*
*/


$.fn.popup = function(opts)
{	
		var opts = $.extend({},$.fn.popup.defaults,opts);
		var obj = $("#divPopup");
		$.popup_init(obj);
		//close
		if(opts.close)
		{
			$.popup_close(obj,opts);
			return false;
		}
		obj.find(".pop_Content_close").click(function(){
			$.popup_close(obj,opts);
			return false;
		});
		//width height
		if(!isNaN(opts.width) && opts.height!="auto")
		{
			opts.width = opts.width+"px";
		}
		if(!isNaN(opts.height) && opts.height!="auto")
		{
			opts.height = opts.height+"px";
		}
		//css
		if(opts.top)obj.css({"top":opts.top});
		if(opts.left)obj.css({"left":opts.left});
		if(opts.right)obj.css({"right":opts.right});
		if(opts.bottom)obj.css({"bottom":opts.bottom});
		
		obj.find(".popup").css({"width":opts.width});
		//set content
		if(opts.title!=false || obj.find(".p-title-content").html()=="")
		{
			obj.find(".p-title-content").html(opts.title);
		}
		switch(opts.type)
		{
			case 1:
				obj.find(".frmPopup").attr("src",opts.url).css({"height":opts.height}).show();
				break;
			case 2:
				obj.find(".content").html(opts.content).css({"height":opts.height}).show();
				break;
		}
		//action
		if(opts.now==true)
		{
			$.popup_show(obj);
		}
		else
		{
			$(this).click(function(){
				$.popup_show(obj);
				return false;
			});
		}
		
		return false;
};
$.fn.popup.defaults =
{
	width : 200,//宽度
	height : 200,//高度，不算边框的高度
	left:"40%",//距离左边的距离
	top:"20%",//距离顶端的距离
	right:false,//距离右边的距离
	bottom:false,//距离底端的距离
	title:false,//显示的标题内容
	type:1,// 1=iframe， 2=显示纯文本
	url:"",//如果type=1，此参数作为iframe的src属性值
	content:"",// type=2时，显示的纯文本内容
	now:false,//是否立即显示
	close_callback: false,
	close:false //如果提供该参数，并且值为true，则关闭弹出窗口
};
$.extend({
	popup_init : function(obj)
	{
		obj.find(".p-title-content").html("");
		obj.find(".content").html("").hide();
		obj.find(".frmPopup").attr("src","").hide();
	},
	popup_close : function(obj,opts)
	{
		obj.hide();
		$.popup_init(obj);
		if(typeof(opts.close_callback)=="function")
		{
			opts.close_callback();
		}
		else if(opts.close_callback!="")
		{
			if(opts.close_callback.substr(opts.close_callback.length-1)==")")
			{
				eval(opts.close_callback);
			}
			else
			{
				eval(opts.close_callback+"()");
			}
		}
		return false;
	},
	popup : function(opts)
	{
		var opts = $.extend({},$.fn.popup.defaults,opts);
		var obj = $("#divPopup");
		$.popup_init(obj);
		//close
		if(opts.close)
		{
			$.popup_close(obj,opts);
			return false;
		}
		obj.find(".pop_Content_close").click(function(){
			$.popup_close(obj,opts);
			return false;
		});
		//width height
		if(!isNaN(opts.width) && opts.height!="auto")
		{
			opts.width = opts.width+"px";
		}
		if(!isNaN(opts.height) && opts.height!="auto")
		{
			opts.height = opts.height+"px";
		}
		//css
		if(opts.top)obj.css({"top":opts.top});
		if(opts.left)obj.css({"left":opts.left});
		if(opts.right)obj.css({"right":opts.right});
		if(opts.bottom)obj.css({"bottom":opts.bottom});
		
		obj.find(".popup").css({"width":opts.width});
		//set content
		if(opts.title!=false || obj.find(".p-title-content").html()=="")
		{
			obj.find(".p-title-content").html(opts.title);
		}
		switch(opts.type)
		{
			case 1:
				obj.find(".frmPopup").attr("src",opts.url).css({"height":opts.height}).show();
				break;
			case 2:
				obj.find(".content").html(opts.content).css({"height":opts.height}).show();
				break;
		}
		//action
		if(opts.now==true)
		{
			$.popup_show(obj);
		}
		else
		{
			$(this).click(function(){
				$.popup_show(obj);
				return false;
			});
		}
		
		return false;
	},
	popup_show: function(obj)
	{
		obj.slideDown();
		obj.easydrag();
		obj.setHandler("drag_handler");
		return false;
	}
});