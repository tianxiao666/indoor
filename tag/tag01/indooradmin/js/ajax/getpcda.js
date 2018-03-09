//获取国家
function ajaxGetCountry(country_id, img_id)
{
	if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeIn("slow");
	$.ajax({
		url : "ea.php?r=GetProvCityDist/getAreaName",
		type : "get",
		dataType : "text",
		data : {
			returnvalue : "country"
		},
		success : function(data){
			$("#"+country_id).empty();
			$("#"+country_id).append(data);
		},
		error : function(data){
			alert(data);
			return false;
		},
		complete : function(){
			if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
		}
	});
	return true;
}
//获取省份
function ajaxGetProv(country_id,prov_id, img_id,default_prov_id)
{
	if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeIn("slow");
	$.ajax({
		url : "ea.php?r=GetProvCityDist/getAreaName",
		type : "get",
		dataType : "text",
		data : {
			country : $("#"+country_id).val(),
			returnvalue : "prov",
			default_value:$("#"+default_prov_id).val()
		},
		success : function(data){
			$("#"+prov_id).empty();
			$("#"+prov_id).append(data);
		},
		error : function(data){
			alert(data);
			return false;
		},
		complete : function(){
			if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
		}
	});
	return true;
}
//获取市
function ajaxGetCity(country_id,prov_id, city_id, img_id, default_city_id)
{
	if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeIn("slow");
	$.ajax({
		url : "ea.php?r=GetProvCityDist/getAreaName",
		type : "get",
		dataType : "text",
		data : {
			country : $("#"+country_id).val(),
			prov : $("#"+prov_id).val(),
			returnvalue : "city",
			default_value:$("#"+default_city_id).val()
		},
		success : function(data){
			$("#"+city_id).empty();
			$("#"+city_id).append(data);
		},
		error : function(data){
			alert(data);
			return false;
		},
		complete : function(){
			if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
		}
	});
	return true;
}
//获取区
function ajaxGetDistrict(country_id,prov_id, city_id, dist_id, img_id, default_district_id)
{
	if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeIn("slow");
	$.ajax({
		url : "ea.php?r=GetProvCityDist/getAreaName",
		type : "get",
		dataType : "text",
		data : {
			country : $("#"+country_id).val(),
			prov : $("#"+prov_id).val(),
			city : $("#"+city_id).val(),
			returnvalue : "district",
			default_value:$("#"+default_district_id).val()
		},
		success : function(data){
			$("#"+dist_id).empty();
			$("#"+dist_id).append(data);
		},
		error : function(data){
			alert(data);
			return false;
		},
		complete : function(){
			if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeOut("slow");
		}
	});
	return true;
}

//获取景区
function ajaxGetAreaList(area_id,country_id,prov_id, city_id, dist_id, img_id)
{
	if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeIn("slow");
	$.ajax({
		type : "get",
		url : "ea.php?r=GetProvCityDist/ajaxAreaList",
		dataType : "text",
		data : {
			country : $("#"+country_id).val(),
			prov : $("#"+prov_id).val(),
			city : $("#"+city_id).val(),
			district : $("#"+dist_id).val()
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

//获取景点
function ajaxGetSpotList(area_id,spot_id, img_id)
{
	if(typeof(img_id)!="undefined"&&img_id!=null) $("#"+img_id).fadeIn("slow");
	$.ajax({
		type : "get",
		url : "ea.php?r=GetProvCityDist/ajaxSpotList",
		dataType : "text",
		data : {
			areaid : $("#"+area_id).val()
		},
		success : function(msg){
			$("#"+spot_id).empty();
			$("#"+spot_id).append(msg);
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
			name : $("#"+area_name).val()
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