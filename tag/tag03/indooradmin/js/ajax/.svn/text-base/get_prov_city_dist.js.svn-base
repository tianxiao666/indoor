

function ajaxGetProv(prov_id, img_id)
{
	if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeIn("slow");
	$.ajax({
		url : "ea.php?r=GetProvCityDist/getAreaName",
		type : "get",
		dataType : "text",
		data : {
			country : "ZHONGGUO",
			returnvalue : "prov",
		},
		success : function(data){
			$("#"+prov_id).html(data);
		},
		error : function(data){
			if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
			return false;
		},
		complete : function(){
			if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
		}
	});
	return true;
}

function ajaxGetCity(prov_id, city_id, img_id)
{
	if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeIn("slow");
	$.ajax({
		url :  "ea.php?r=GetProvCityDist/getAreaName",
		type : "get",
		dataType : "text",
		data : {
			country : "ZHONGGUO",
			prov : $("#"+prov_id).val(),
			returnvalue : "city"
		},
		success : function(data){
			$("#"+city_id).html(data);
		},
		error : function(data){
			if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
			return false;
		},
		complete : function(){
			if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
		}
	});
	return true;
}

function ajaxGetDistrict(prov_id, city_id, dist_id, img_id)
{
	if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeIn("slow");
	$.ajax({
		url : "ea.php?r=GetProvCityDist/getAreaName",
		type : "get",
		dataType : "text",
		data : {
			country : "ZHONGGUO",
			prov : $("#"+prov_id).val(),
			city : $("#"+city_id).val(),
			returnvalue : "district"
		},
		success : function(data){
			$("#"+dist_id).html(data);
		},
		error : function(data){
			if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
			return false;
		},
		complete : function(){
			if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
		}
	});
	return true;
}


function ajaxGetAreaList(area_id, name_id,prov_id, city_id, dist_id, img_id)
{
	if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeIn("slow");
	$.ajax({
		url : "ea.php?r=GetProvCityDist/ajaxAreaList",
		type : "get",
		dataType : "text",
		data : {
			country : "ZHONGGUO",
			prov : $("#"+prov_id).val(),
			city : $("#"+city_id).val(),
			district : $("#"+dist_id).val(),
		},
		success : function(data){
			$("#"+area_id).html(data);
		},
		error : function(data){
			if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
			return false;
		},
		complete : function(){
			if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
		}
	});
}
//ajax模糊查询景区（根据景区名）
function ajaxGetAreas(area_name,area_id,img_id)
{
	if(!area_name)
		return false;
	if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeIn("slow");
	$.ajax({
		type : "get",
		url : "ea.php?r=GetProvCityDist/AjaxGetAreas",
		dataType : "text",
		data : {
			name : $("#"+area_name).val(),
		},
		success : function(msg){
			$("#"+area_id).empty();
			$("#"+area_id).append(msg);
		},
		error : function(data){
			alert(data);
			return false;
		},
		complete : function(){
			$("#"+img_id).fadeOut("slow");
		}
	});
	return true;
}

//
function ajaxGetAreaProd(area_id, prod_id, img_id)
{
	if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeIn("slow");
	$.ajax({
		url : "ea.php?r=TelOrder/GetProds",
		type : "post",
		dataType : "json",
		data : {
			"AREA_ID" : $("#"+area_id).val(),
			AJAX : true,
		},
		success : function(data){
			$("#"+prod_id).html('<option value="">---</option>');
			//
			if(typeof(data.ERROR)!="undefined" || data.ERROR!=null)
			{
				$("#"+prod_id).html('<option value="">'+data.ERROR+'</option>');
				if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
				return false;
			}
			for(var prod in data)
			{
				$("#"+prod_id).html($("#"+prod_id).html()+'<option value="'+data[prod].LSPROD_ID+'">'+data[prod].LSPROD_NAME+'</option>');
			}
		},
		error : function(data){
			alert("系统错误，请刷新后重试");
			if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
			return false;
		},
		complete : function(){
			if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
		}
	});
}

/*
获取景区产品，table显示
*/
function ajaxGetAreaProdTable(area_id, prod_id, img_id)
{
	if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeIn("slow");
	$.ajax({
		url : "ea.php?r=TelOrder/GetProds",
		type : "post",
		dataType : "json",
		data : {
			"AREA_ID" : $("#"+area_id).val(),
			AJAX : true,
		},
		success : function(data){
			//如果没有相应产品
			if(typeof(data.ERROR)!="undefined" || data.ERROR!=null)
			{
				$("#"+prod_id).html(data.ERROR);
				if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
				return false;
			}
			var content = "<span class='notice'>这里的‘提前通知景区的时间与方式’是指景区产品部通知景区</span><br /><table class='prods'>";
			content +="<tr class='title'>";
			content +="<td class='cur'>当前选择</td>";
			content +="<td class='title2'>产品名称</td>";
			content +="<td class='title3'>产品类型</td>";
			content +="<td class='time'>有效时间</td>";
			content +="<td class='title4'>优惠时间段</td>";
			content +="<td class='title5'>提前通知景区时间</td>";
			content +="<td class='title6'>基本价格</td>";
			content +="<td class='title7'>消费者类型</td>";
			content +="</tr>";
			for(var prod in data)
			{
				content +="<tr>";
				content +='<td><input type="radio" name="LSPROD_ID" value="'+data[prod].LSPROD_ID+'" id="LSPROD_ID'+prod+'" onchange="showProductPrice(this);" /></td>';
				content +='<td>'+data[prod].LSPROD_NAME+'</td>';
				content +='<td>'+data[prod].LSPROD_TYPE+'</td>';
				if(data[prod].LSPROD_BEGIN_TIME==null || data[prod].LSPROD_BEGIN_TIME=="null" || data[prod].LSPROD_BEGIN_TIME=="")
				{
					nSTART_DATE = "无开始时间";
				}
				else
				{
					nSTART_DATE=data[prod].LSPROD_BEGIN_TIME.substring(0,10);
				}
				if(data[prod].LSPROD_END_TIME==null || data[prod].LSPROD_END_TIME=="null" || data[prod].LSPROD_END_TIME=="")
				{
					nEND_DATE = "无结束时间";
				}
				else
				{
					nEND_DATE=data[prod].LSPROD_END_TIME.substring(0,10);
				}
				content +='<td>'+nSTART_DATE+'至'+nEND_DATE+'</td>';
				content +='<td>'+data[prod].START_TIME+'至'+data[prod].END_TIME+'</td>';
				content +='<td>'+data[prod].INFORM_DAY+'天</td>';
				content +='<td>'+data[prod].BASE_PRICE+'</td>';
				content +='<td>'+data[prod].CONSUMER_TYPE+'</td>';
				content +="</tr>";
			}
			content +="</table>";
			$("#"+prod_id).html(content);
			$(".prods").show();
		},
		error : function(data){
			alert("系统错误，请刷新后重试");
			if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
			return false;
		},
		complete : function(){
			if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
		}
	});
}
/*
获取产品对应的价格
*/
function ajaxGetProdPrice(price_id, LSPROD_ID, img_id)
{
	if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeIn("slow");
	$.ajax({
		url : "ea.php?r=TelOrder/AjaxGetPrice",
		type : "post",
		dataType : "json",
		data : {
			"LSPROD_ID" :LSPROD_ID,
		},
		success : function(data){
			//如果没有相应数据
			if(typeof(data.ERROR)!="undefined" || data.ERROR!=null)
			{
				$("#"+price_id).html('<option value="">'+data.ERROR+'</option>');
				if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
				return false;
			}
			var content = "<table class='prod_price'>";
			content +="<tr class='title'>";
			content +="<td>会员价</td>";
			content +="<td>随从人员价</td>";
			content +="<td>有效时间</td>";
			content +="<td>最少预约票数</td>";
			content +="<td>具体说明</td>";
			content +="</tr>";
			for(var p in data)
			{
				content +="<tr>";
				var mem = data[p].PRICE_AFTER_CHANGE;
				if(mem==null || mem=="null")
				{
					mem="无";
				}
				content +="<td>"+mem+"</td>";
				if((data[p].PARAM1==null || data[p].PARAM1=="null" || data[p].PARAM1=="" ))
				{
					if( data[p].DISCOUNT_TYPE==	"O")
					{
						content +="<td>"+data[p].PRICE_AFTER_CHANGE+"</td>";
					}
					else
					{
						content +="<td>随从人员无折扣</td>";
					}
				}
				else
				{
					content +="<td>"+data[p].PARAM1+"</td>";
				}
				if(data[p].START_DATE==null || data[p].START_DATE=="null" || data[p].START_DATE=="")
				{
					nSTART_DATE = "无开始时间";
				}
				else
				{
					nSTART_DATE=data[p].START_DATE.substring(0,10);
				}
				if(data[p].END_DATE==null || data[p].END_DATE=="null" || data[p].END_DATE=="")
				{
					nEND_DATE = "无结束时间";
				}
				else
				{
					nEND_DATE=data[p].END_DATE.substring(0,10);
				}
				content +="<td>"+nSTART_DATE+"至"+nEND_DATE+"</td>";
				var ticket_cnt = data[p].MIN_GROUP;
				if(ticket_cnt==null || ticket_cnt=="null")
				{
					ticket_cnt="无";
				}
				content +="<td>"+ticket_cnt+"</td>";
				var note = data[p].PRICE_NOTE;
				if(note==null || note=="null")
				{
					note="";
				}
				content +="<td>"+note+"</td>";
				content +="</tr>";
			}
			content +="</table>";
			$("#"+price_id).html(content);
			$(".prod_price").show();
		},
		error : function(data){
			alert("系统错误，请刷新后重试");
		},
		complete : function(){
			if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
		}
	});
}
/**
鑾峰彇浠锋牸
LSPROD_ID 鍖呭惈鏅尯浜у搧ID鐨勫厓绱爄d
LSPROD_CNT 鍖呭惈棰勮绁ㄦ暟鐨勫厓绱爄d
RESERVE_TIME 鍖呭惈棰勮鍒拌揪鏃堕棿鐨勫厓绱爄d
SUBS_ID 用户ID
鎴愬姛杩斿洖data鐨刯son鏁版嵁锛坥bject绫诲瀷锛?
*/
function ajaxGetPrice(LSPROD_ID, LSPROD_CNT, RESERVE_TIME, SUBS_ID, img_id)
{
	if(typeof(img_id)!="undefined"&&img_id!=null)  $("#"+img_id).fadeIn("slow");
	var alerts = "";
	if($("#"+LSPROD_ID).val()=="")
	{
		alerts += "ERROR:LSPROD\n";
	}
	if($("#"+LSPROD_CNT).val()=="")
	{
		alerts += "ERROR:LSPROD_CNT\n";
	}
	if($("#"+RESERVE_TIME).val()=="")
	{
		alerts += "ERROR:RESERVE_TIME\n";
	}
	if($("#"+SUBS_ID).val()=="")
	{
		alerts += "ERROR:SUBS\n";
	}
	if(alerts!="")
	{
		//alert(alerts);
		if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
		return alerts;
	}
	var ret;
	$.ajax({
		type : "POST",
		dataType : "json",
		url : "ea.php?r=TelOrder/GetPrice",
		data : {
			LSPROD_ID : $("#"+LSPROD_ID).val(),
			LSPROD_CNT: $("#"+LSPROD_CNT).val(),
			RESERVE_TIME:$("#"+RESERVE_TIME).val(),
			SUBS_ID : $("#"+SUBS_ID).val(),
			AJAX		: true,
		},
		success : function(data){
			showOrderPrice(data,"MEN_PRICE","TOTAL","BASE_PRICE","DISCOUNT","SAVED");
		},
		error : function(data){
			if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
			return false;
		},
		complete : function(){
			if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
		}
	});
}

/**
鐢ㄤ簬鏄剧ず浜у搧璁㈠崟鐨勪环鏍?
mem_price_id 鐢ㄤ簬鏄剧ず浼氬憳浠锋牸鐨勫厓绱爄d
total_label_id 鐢ㄤ簬鏄剧ず鎬讳环鏍肩殑鍏冪礌id
original_price_id 鐢ㄤ簬鏄剧ず绁ㄩ潰鍘熶环鏍肩殑鍏冪礌id
follow_discount_id 鐢ㄤ簬鏄剧ず闅忎粠浜哄憳鐨勬姌鎵ｇ殑鍏冪礌id
saved_id 鐢ㄤ簬鏄剧ず涓虹敤鎴疯妭鐪佷簡澶氬皯閽辩殑鍏冪礌id
*/
function showOrderPrice(data, mem_price_id, total_label_id, original_price_id, follow_discount_id, saved_id)
{
	if( typeof(data.LS_PRODUCT)!='undefined' && data.LS_PRODUCT!=null)
	{
		//会员总价
		if(typeof(data.DETAIL.MEMPRICE)!='undefined' && data.DETAIL.MEMPRICE!=null)
		{
			$("#"+mem_price_id).html("￥"+(data.DETAIL.MEMPRICE.TOTALPRICE));
		}
		else
		{
			$("#"+mem_price_id).html("￥"+(data.LS_PRODUCT.BASE_PRICE));
		}
		//总价
		$("#"+total_label_id).html("￥"+(data.ACCOPRICE));
		//票面价格
		$("#"+original_price_id).html("￥"+(data.LS_PRODUCT.BASE_PRICE));
		//随行人员总价
		if(typeof(data.DETAIL.ENTPRICE)!='undefined' && data.DETAIL.ENTPRICE!=null)
		{
			$("#"+follow_discount_id).html("￥"+(data.DETAIL.ENTPRICE.TOTALPRICE));
		}
		else
		{
			$("#"+follow_discount_id).html("￥"+(data.LS_PRODUCT.BASE_PRICE));
		}
		//为用户节省
		$("#"+saved_id).html("本次订单为您节省了￥"+(Number(data.LS_PRODUCT.BASE_PRICE)*Number($("#LSPROD_CNT").val())-Number(data.ACCOPRICE))+"元");
	}
	else
	{
		$("#"+original_price_id).html("");
		$("#"+total_label_id).html("Free");
	}
}