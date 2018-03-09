//获取国家
function ajaxGetCountry(country_id, img_id)
{
	if(img_id!="") $("#"+img_id).slideDown();
	$.ajax({
		url : "ea.php?r=GetProvCityDist/GetAdminRegion",
		type : "get",
		dataType : "json",
		success : function(data){
			$("#"+country_id).empty().append('<option value="">--请选择--</option>');
			if(typeof(data.country)!="object") return false;
			for(p in data.country)
			{
				$("#"+country_id).append('<option value="'+p+'">'+data.country[p]+'</option>');
			}
		},
		complete : function(){
			if(img_id!="") $("#"+img_id).slideUp();
		}
	});
}
//获取省份
function ajaxGetProv(country_id,prov_id, img_id)
{
	if(img_id!="") $("#"+img_id).slideDown();
	$.ajax({
		url : "ea.php?r=GetProvCityDist/GetAdminRegion",
		type : "get",
		dataType : "json",
		data : {
			country : $("#"+country_id).val()
		},
		success : function(data){
			$("#"+prov_id).empty().append('<option value="">--全国--</option>');
			if(typeof(data.prov)!="object") return false;
			for(p in data.prov)
			{
				$("#"+prov_id).append('<option value="'+p+'">'+data.prov[p]+'</option>');
			}
		},
		complete : function(){
			if(img_id!="") $("#"+img_id).slideUp();
		}
	});
}
//获取市
function ajaxGetCity(country_id,prov_id, city_id, img_id)
{
	if(img_id!="") $("#"+img_id).slideDown();
	$.ajax({
		url : "ea.php?r=GetProvCityDist/GetAdminRegion",
		type : "get",
		dataType : "json",
		data : {
			country : $("#"+country_id).val(),
			prov : $("#"+prov_id).val()
		},
		success : function(data){
			$("#"+city_id).empty().append('<option value="">--全部--</option>');
			if(typeof(data.city)!="object") return false;
			for(p in data.city)
			{
				$("#"+city_id).append('<option value="'+p+'">'+data.city[p]+'</option>');
			}
		},
		complete : function(){
			if(img_id!="") $("#"+img_id).slideUp();
		}
	});
}
//获取区
function ajaxGetDistrict(country_id,prov_id, city_id, dist_id, img_id)
{
	if(img_id!="") $("#"+img_id).slideDown();
	$.ajax({
		url : "ea.php?r=GetProvCityDist/GetAdminRegion",
		type : "get",
		dataType : "json",
		data : {
			country : $("#"+country_id).val(),
			prov : $("#"+prov_id).val(),
			city : $("#"+city_id).val()
		},
		success : function(data){
			$("#"+dist_id).empty().append('<option value="">--全部--</option>');
			if(typeof(data.district)!="object")
			{
				return false;
			}
			for(p in data.district)
			{
				$("#"+dist_id).append('<option value="'+p+'">'+data.district[p]+'</option>');
			}
		},
		complete : function(){
			if(img_id!="") $("#"+img_id).slideUp();
		}
	});
}
