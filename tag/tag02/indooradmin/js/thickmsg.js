//������
function doAlert(){
	 var winW = $(window).width();
	 var winH = $(window).height();
	 var sroT = $(window).scrollTop();
	 var sroL = $(window).scrollLeft();
	 var objW = $("#msg").width();
	 var objH = $("#msg").height();
	 var left = (winW - objW) / 2 + sroL;
	 var top = (winH - objH) / 2 + sroT;
	 $("#msg").css("left",left).css("top",top);
	 var name = $("#hidform").val();
	 var areastornote = $('#AREA_SHORTNOTE').val();
	  if(areastornote)
		 {
			 var realLength = 0, len = areastornote.length, charCode = -1;
			    for (var i = 0; i < len; i++) {
			        charCode = areastornote.charCodeAt(i);
			        if (charCode >= 0 && charCode <= 128) realLength += 1;
			        else realLength += 2;
			    }
			  if(realLength>100)
			  {
				  alert("���ȳ���100���ַ������޸ľ�����Ϣ");
				  return false;
			  }
			 
		 }
	  var city_e_longitude = $('#city_E_Longitude').val();
	  	var city_n_latitude = $('#city_N_Latitude').val();
	  	var city_w_longitude = $('#city_W_Longitude').val();
	  	var city_s_latitude = $('#city_S_Latitude').val();
  	if((city_e_longitude && city_n_latitude && city_w_longitude && city_s_latitude) && !(e_longitude && w_longitude && n_latitude && s_latitude))
  	{
  		if(parseFloat($('#ENT_LONGITUDE').val())>city_e_longitude || parseFloat($('#ENT_LONGITUDE').val())<city_w_longitude )
		{
			if(!confirm('����ֵ����ѡ�������!�Ƿ������ӣ�'))
			{
				return false;
			}
		}
		if(parseFloat($('#ENT_LATITUDE').val())>city_n_latitude || parseFloat($('#ENT_LATITUDE').val())<city_s_latitude )
		{
			if(!confirm('γ��ֵ����ѡ�������!�Ƿ������ӣ�'))
			{
				return false;
			}
		}
  	}else{
  		if(parseFloat($('#ENT_LONGITUDE').val())>e_longitude || parseFloat($('#ENT_LONGITUDE').val())<w_longitude )
		{
			if(!confirm('����ֵ����ѡ�������!�Ƿ������ӣ�'))
			{
				return false;
			}
		}
		if(parseFloat($('#ENT_LATITUDE').val())>n_latitude || parseFloat($('#ENT_LATITUDE').val())<s_latitude )
		{
			if(!confirm('γ��ֵ����ѡ�������!�Ƿ������ӣ�'))
			{
				return false;
			}
		}
  	}
  	var pic_id = $('#PIC_ID').val();
  	if(pic_id==0){
	  	var radios = document.getElementsByName('CDAOLS_AREA[Default]');
		var pic = document.getElementsByName('PIC[]');
		var is_picture = false;//�ж��Ƿ��ϴ���ͼƬ
		for (var x=0; x<pic.length; x++) {
			if(pic[x].value != ''){
				is_picture = true;
			    if( !pic[x].value.match( /.jpg|.gif|.png|.bmp/i ) ){   
			    	EditAlertTips('ͼƬ'+(x+1)+'��ʽ��Ч��');   
			        return false;   
			    } 
			}
			if(radios[x].checked && pic[x].value == ''){
				EditAlertTips('ͼƬΪ�ղ�������Ĭ��ͼ��');
				return false; 
			}
		}	
		
		if(is_picture){
			var defalt_checked = false;
			for (var x=0; x<radios.length; x++) {
				defalt_checked = defalt_checked || radios[x].checked;
			}
			if (!defalt_checked) {
				EditAlertTips("����ѡ��һ��Ĭ��ͼ��");
			    return false;
			}
		}
  	}
	 if($(name).submit()){
		 return false;
	 }
	 
	 $("#msg").css("left",left).css("top",top).fadeIn(200);
	}
	

	function moveWin(){
	 var winW = $(window).width();
	 var winH = $(window).height();
	 var sroT = $(window).scrollTop();
	 var sroL = $(window).scrollLeft();
	 var objW = $("#msg").width();
	 var objH = $("#msg").height();
	 var left = (winW - objW) / 2 + sroL;
	 var top = (winH - objH) / 2 + sroT;
	 $("#msg").animate({"left":left,"top":top},500).dequeue();
	}

	$(function(){
		 $(window).resize(function(){
		 	 moveWin();
			 }).scroll(function(){
		 	 moveWin();
		 });
		 $("#winaction").click(function(){
		  		$("#msg").fadeOut(200);
		 	});
		});