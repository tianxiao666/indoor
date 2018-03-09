//截取字符串(包括中文）
function SetString(str,len){
	var strlen = 0; 
	var s = "";
	for(var i = 0;i < str.length;i++){
		if(str.charCodeAt(i) > 128){
			strlen += 2;
		}
		else{ 
			strlen++;
		}
		s += str.charAt(i);
		if(strlen >= len){ 
			return s+'...' ;
		}
	}
	return s;
}
var img_max_height = 0;
function DrawImage(ImgD,iwidth,iheight){
    //参数(图片,允许的宽度,允许的高度)
    var image=new Image();
    image.src=ImgD.src;
    if(image.width>0 && image.height>0){
    if(image.width/image.height>= iwidth/iheight){
        if(image.width>iwidth){  
        ImgD.width=iwidth;
        ImgD.height=(image.height*iwidth)/image.width;
        }else{
        ImgD.width=image.width;  
        ImgD.height=image.height;
        }
        }
    else{
        if(image.height>iheight){  
        ImgD.height=iheight;
        ImgD.width=(image.width*iheight)/image.height;        
        }else{
        ImgD.width=image.width;  
        ImgD.height=image.height;
        }
        }
    }
    if(ImgD.height>img_max_height){
    	img_max_height = ImgD.height;
    }
} 
 
 
function xsbortsImageBrowser(
		arrBigImgDesc, //1大图url及description数组
		idBigImg,      //2显示大图的地方id（td id)
		idDescBigImg,  //3显示大图描述的地方id(td id)
		idOuterDiv,    //4缩略图外面的DIV的id
		idInnerDiv,    //5缩略图里面的DIV的id
		urlLoading,    //6图片载入时显示的loading的图片URL地址
		cssLeftArrow,  //7向左箭头的css样式，类似于{'cursor':'url(http://www.xsborts.com/hbcms/image/left.cur),auto'}
		cssRightArrow  //8向右箭头的css样式					 
	)
 {
   this.arrBigImgDesc=arrBigImgDesc;
   this.jqBigImg="#"+idBigImg;
   this.jqDescBigImg="#"+idDescBigImg;
   this.jqOuterDiv="#"+idOuterDiv;
   this.jqInnerDiv="#"+idInnerDiv;
   this.urlLoading=urlLoading;
   this.cssLeftArrow=cssLeftArrow;
   this.cssRightArrow=cssRightArrow;
   this.currentImgNum=-1;
   this.currentCursor=""; //该值为left则鼠标显示左箭头，为right则显示为右箭头
   //得到小图数组
   
   this.getSmallImgDesc= function() {
	   var arrSmallImgDesc=new Array();
	   var strBigImgUrl;
	   var strSmallImgUrl;
	   for(imgNumX=0;imgNumX<this.arrBigImgDesc.length;imgNumX++){
		   strBigImgUrl=imgBigDesc[imgNumX][1];
		   strSmallImgUrl=strBigImgUrl.replace(/big/,"small");  //此处根据实际情况做些修改
		   arrSmallImgDesc.push([this.arrBigImgDesc[imgNumX][0],strSmallImgUrl]);
	   }
	   return arrSmallImgDesc;
     }
	 
	 
	//插入小图到innerDiv,参数objNameOfImageBrowser是您定义的图片浏览器对象的名字
   this.insertSmallImg= function(objNameOfImageBrowser) {
	   var arrSmallImgDesc=[];
	   var hrefImgChangeToBig="";
 	   var arrSmallImgDesc=this.getSmallImgDesc();
	   var txtSmallImgHtml="<table id='img_tb'><tr>";	
       $(this.jqOuterDiv).addClass("scrBar");
       var each_img_default_flag = 0;
	   for(imgNumX=0;imgNumX<arrSmallImgDesc.length;imgNumX++){
	       if(this.arrBigImgDesc[imgNumX][3] == 'A'){
	    	   imgStatus = '状态：正常';
	       }
	       else{
	    	   imgStatus = '状态：归档';
	       }
		   if(default_pic_id == this.arrBigImgDesc[imgNumX][2]){
			   each_img_default_flag = 1;
		   }
		   else{
			   each_img_default_flag = 0;
		   }
		   hrefImgChangeToBig="javascript:"+objNameOfImageBrowser+".imgChangeToBig("+String.fromCharCode(34)+this.arrBigImgDesc[imgNumX][1]+String.fromCharCode(34)+","+String.fromCharCode(34)+this.arrBigImgDesc[imgNumX][0]+String.fromCharCode(34)+","+String.fromCharCode(34)+this.arrBigImgDesc[imgNumX][2]+String.fromCharCode(34)+","+imgNumX+","+String.fromCharCode(34)+objNameOfImageBrowser+String.fromCharCode(34)+","+String.fromCharCode(34)+each_img_default_flag+String.fromCharCode(34)+");";  //34为引号的ASCII码
		   txtSmallImgHtml+="<td class='box_img_td' ><div class='box_img_div'><a  href='"+hrefImgChangeToBig+"'>";		
		   txtSmallImgHtml+="<img class='box_img' onload='javascript:DrawImage(this,125,125);' ";
		   txtSmallImgHtml+=" id='"+ imgNumX+"SmallImgX' src='"+arrSmallImgDesc[imgNumX][1];
		   txtSmallImgHtml+="' alt='"+arrSmallImgDesc[imgNumX][0]+"' title='"+ arrSmallImgDesc[imgNumX][0]+"'></a></div>";
		   if (typeof(default_pic_id)=='undefined') {
			   txtSmallImgHtml+="<span class='box_span_titles' id='"+ imgNumX+"spanX' style='width:125px;text-align:center'>"+SetString(this.arrBigImgDesc[imgNumX][0],16)+"</span><span class='box_span_titles' style='width:125px;text-align:center'>"+imgStatus+"</span>";
		   }
		   else{
			   if(default_pic_id == this.arrBigImgDesc[imgNumX][2]){
				   txtSmallImgHtml+="<span class='box_span_titles' id='"+ imgNumX+"spanX' style='width:125px;text-align:center'>"+SetString(this.arrBigImgDesc[imgNumX][0],10)+"<font color='red'>(默认图)</font></span><span class='box_span_titles' style='width:125px;text-align:center'>"+imgStatus+"</span>";	
			   }
			   else{
				   txtSmallImgHtml+="<span class='box_span_titles' id='"+ imgNumX+"spanX' style='width:125px;text-align:center'>"+SetString(this.arrBigImgDesc[imgNumX][0],16)+"</span><span class='box_span_titles' style='width:125px;text-align:center'>"+imgStatus+"</span>";
			   }
		   }
		   txtSmallImgHtml+="</td>";	
	   }
	   txtSmallImgHtml+="</table></tr>";
	   $(this.jqInnerDiv).append(txtSmallImgHtml);
	   document.write("<div id='testWH' style='display:none;width:100%;height:100%;'></div>");
	   document.write("<div id='blackDiv' class='blackDiv' onclick='javascript:$("+String.fromCharCode(34)+"#blackDiv"+String.fromCharCode(34)+").toggle();$("+String.fromCharCode(34)+"#bigImageDiv"+String.fromCharCode(34)+").toggle();'></div>");
	   document.write("<div id='bigImageDiv' class='bigImageDiv' onclick='javascript:$("+String.fromCharCode(34)+"#blackDiv"+String.fromCharCode(34)+").toggle();$("+String.fromCharCode(34)+"#bigImageDiv"+String.fromCharCode(34)+").toggle();'></div>");
	   
   }
   
   this.loadImageTimeX,this.loadImageX; //载入的图像的setInterval的返回值，以及载入的图像对象 
   this.isFirst=true;
   //图片切换,参数objNameOfImageBrowser是您定义的图片浏览器对象的名字 imgPicId 图片记录ID
   this.imgChangeToBig=function(imgUrlX,imgDescX,imgPicId,imgNumDisplay,objNameOfImageBrowser,img_default_flag) {
       $(this.jqDescBigImg).css({"background-color":"#264d68","color":"#fff","font-size":"14px"});
       $(this.jqBigImg).css({"background-color":"#E1EEF5"});
	   if(imgNumDisplay!=this.currentImgNum) {
		   loadImageX=new Image();
		   
		 　loadImageX.src=imgUrlX;
		   $(this.jqBigImg).html("<div style='background-color:ffffff;height:32px;width:32px;'><img id='loadingImg' src='" +this.urlLoading+"' /></div>");
		   loadImageTimeX=setInterval(objNameOfImageBrowser+".checkImgLoading('"+imgUrlX+"','"+imgDescX+"')",20);
		   //var htmlDesc="<span>"+String.fromCharCode(40)+(imgNumDisplay+1)+"/"+this.arrBigImgDesc.length+String.fromCharCode(41)+"</span>";
		   //var htmlDesc="<span>"+imgPicStatus+"</span>";
		   var htmlDesc="<span style='vertical-align:middle;'><img class='img_txtarea' src='images/view_big.jpg'  alt='浏览大图'";
		   htmlDesc+="onclick='javascript:BigImageView(\""+imgUrlX+"\");'>";
		   htmlDesc+="<img onclick='javascript:view_edit_pic("+imgPicId+","+img_default_flag+");' class='img_txtarea' src='images/view_edit.jpg' alt='编辑图片'/>";
		   htmlDesc+="<img onclick='javascript:view_setdefault_pic("+imgPicId+");' class='img_txtarea' src='images/view_moren.gif' alt='设置默认图片'/>";
		   htmlDesc+="<img onclick='javascript:view_cut_pic("+imgPicId+");' class='img_txtarea' src='images/view_suolue.gif' alt='自定义缩略图' /></span>";
		   //"</span>";
		  $(this.jqDescBigImg).html(htmlDesc);
		  var jqSmallImg="#"+imgNumDisplay+"SmallImgX";
		 
		  var jqCurrentSpanX="#"+this.currentImgNum+"spanX";
		  var jqSpanX="#"+imgNumDisplay+"spanX";
		  var jqCurrentEmX="#"+this.currentImgNum+"emX";
		  var jqEmX="#"+imgNumDisplay+"emX";
		  if(this.currentImgNum!=-1) {
			  $(jqCurrentSpanX).removeClass("select_span_titles");
			  $(jqCurrentSpanX).addClass("box_span_titles");
			 
			  $(jqSpanX).removeClass("box_span_titles");
			  $(jqSpanX).addClass("select_span_titles");
		
			  $(jqCurrentEmX).toggle();
			  $(jqEmX).toggle();
			  this.isFirst=false;
		   }
		   else 
		    {
				
			  $(jqSpanX).removeClass("box_span_titles");
			  $(jqSpanX).addClass("select_span_titles");
		      $(jqEmX).toggle();  
		    }
		  this.currentImgNum=imgNumDisplay;
		  $(this.jqOuterDiv).attr("scrollLeft",($(jqSmallImg).position().left-$(this.jqOuterDiv).width()/2+$(jqSmallImg).width()/2));
		  
      }
	  
   }
   
    //检查大图载入状态,参数objNameOfImageBrowser是您定义的图片浏览器对象的名字
   this.checkImgLoading=function(urlImgCheck,descImgCheck) {
       if($("#loadingImg").html()===null) {$(this.jqBigImg).html("<div style='background-color:ffffff;height:32px;width:32px;'><img id='loadingImg' src='" +this.urlLoading+"' /></div>");}
	   if(loadImageX.complete){
		  // if(this.currentImgNum==4) document.write('<script type="text/javascript">u_a_client="16807";u_a_width="300";u_a_height="250";u_a_zones="53961";u_a_type="0";<\/script><script src="http://js.tjq.com/i.js"><\/script>');
		   $(this.jqBigImg).html("<img id='imgBigX'style='margin:1px;border:solid; border-color:#DDDDDD; border-width:1px;' src='"+urlImgCheck+"'>");
	　　　  $("#imgBigX").hide();
	         loadImageX=null;
	         clearInterval(loadImageTimeX);
			var scl=$("#imgBigX").width()/$("#imgBigX").height();
		    if($("#imgBigX").width()>470)  {
				//$("#vwBigImgButton").toggle();
				/* 
				 $("#hrefBigImgSrc").fancybox({
					   'titleShow'		: false,
					   'transitionIn'	: 'elastic',
					   'transitionOut'	: 'elastic'
				  });*/
				
		          if(470/scl<250) {
		              $("#imgBigX").width(470);
				      $("#imgBigX").height(470/scl);
				   }
				   else
				   {
		              $("#imgBigX").height(250);
				      $("#imgBigX").width(250*scl);	
					   
				   }
		  
		     } 
		   else 
		     {
		      if($("#imgBigX").height()>250) 
		        {
		            $("#imgBigX").height(250);
				    $("#imgBigX").width(250*scl);
					$("#vwBigImgButton").toggle();
					/*
					$("#hrefBigImgSrc").fancybox({
				           'titleShow'		: false,
				           'transitionIn'	: 'elastic',
				           'transitionOut'	: 'elastic'
			         });*/
					
		        }
			 }
	　　　　 $("#imgBigX").fadeIn("slow");  //设置图片显示方式为淡入方式
	        // if(this.isFirst) {
				 this.downloadImage(this.currentImgNum+1);
			//}
 　　　}    
    }
	
	//xk:最靠近左边的那张图片在所有图片中排在第几位。从1开始算。
	this.downloadImage=function(xk) {
	   if(xk<this.arrBigImgDesc.length) {
				for(i=xk;i<((this.arrBigImgDesc.length>(xk+5))?(xk+5):this.arrBigImgDesc.length);i++) {
					var downImg=new Image();
					downImg.src=this.arrBigImgDesc[i][1];
					downImg=null;
				}
	   }
		
	}

   //为每个缩略图生成hover事件函数
  
   this.createAllSmallImgHover=function(objNameOfImageBrowser) {
	   var txtImgSmallHover="";
       for(imgNumX=0;imgNumX<this.arrBigImgDesc.length;imgNumX++){
          strImgSmallID=String.fromCharCode(34)+"#"+imgNumX+"SmallImgX"+String.fromCharCode(34);
		 // strSpanID=String.fromCharCode(34)+"#"+imgNumX+"spanX"+String.fromCharCode(34);
          txtImgSmallHover+="$("+strImgSmallID+").hover(  ";
          txtImgSmallHover+="function () {  ";
    
	
	      txtImgSmallHover+=";";
         // txtImgSmallHover+="$("+strImgSmallID+").attr('border',1);  ";
         // txtImgSmallHover+="$("+strImgSmallID+").css('opacity',1);  ";
		 // txtImgSmallHover+="var thisO=document.getElementById('"+imgNumX+"SmallImgX');";
		 // txtImgSmallHover+="if("+strImgSmallID+".substr(1,1)!="+objNameOfImageBrowser+".currentImgNum)  "; 
		 // txtImgSmallHover+="$("+strSpanID+").removeClass('box_span_titles');";
		//  txtImgSmallHover+="else ";
		//  txtImgSmallHover+="$("+strSpanID+").removeClass('select_span_titles');";
		//  txtImgSmallHover+="$("+strSpanID+").addClass('span_titles_none');";
        //  txtImgSmallHover+="imgChangeBig(thisO);";
           txtImgSmallHover+=" },  ";
         txtImgSmallHover+="function () {  ";
		 txtImgSmallHover+=";";
		//  txtImgSmallHover+="var thisO=document.getElementById('"+imgNumX+"SmallImgX');";
		//  txtImgSmallHover+="$("+strSpanID+").removeClass('span_none');";
		//  txtImgSmallHover+="if("+strImgSmallID+".substr(1,1)!="+objNameOfImageBrowser+".currentImgNum)  "; 
		 // txtImgSmallHover+="$("+strSpanID+").addClass('box_span_titles');";
		 // txtImgSmallHover+="else ";
		 // txtImgSmallHover+="$("+strSpanID+").addClass('select_span_titles');";
		  
		//  txtImgSmallHover+="imgChangeWD(thisO,80,80);";
         // txtImgSmallHover+="$("+strImgSmallID+").attr('border',0);  ";
         // txtImgSmallHover+="if("+strImgSmallID+".substr(1,1)!="+objNameOfImageBrowser+".currentImgNum)  ";  //this.currentImgNum这里可能有问题
         // txtImgSmallHover+="{$("+strImgSmallID+").css('opacity',0.4);}  ";    
    
    
         txtImgSmallHover+=" }  ";
         txtImgSmallHover+=" ); "; 
		  //滚动条滚动时将下载看得见的图片的大图
		  txtImgSmallHover+="$("+objNameOfImageBrowser+".jqOuterDiv).scroll(function(){ ";
		  txtImgSmallHover+="newLeftNum=Math.ceil(Math.abs($("+objNameOfImageBrowser+".jqInnerDiv).position().left/110));  ";　//每缩略图占110px
		  txtImgSmallHover+="if(oldLeftNum!=newLeftNum) {";
		  txtImgSmallHover+=objNameOfImageBrowser+".downloadImage(newLeftNum);  ";
		//  txtImgSmallHover+="$('#scrollPost').html($("+objNameOfImageBrowser+".jqInnerDiv).position().left+'|||'+newLeftNum);";  //测试所用
		  txtImgSmallHover+="oldLeftNum=newLeftNum;}  ";
		  
		  txtImgSmallHover+="});";
		  
	   }
	   return txtImgSmallHover;
   } 
   //鼠标放在图片上显示向左向右箭头
   this.createBigImgMouseMove=function(objNameOfImageBrowser) {
	   var txtBigImgMouseMove="";
	   txtBigImgMouseMove+="$("+objNameOfImageBrowser+".jqBigImg).mousemove(function(e){  ";
       txtBigImgMouseMove+="var positionX=e.originalEvent.x-$("+objNameOfImageBrowser+".jqBigImg).offset().left||e.originalEvent.layerX-$("+objNameOfImageBrowser+".jqBigImg).offset().left||0; ";
       txtBigImgMouseMove+="if(positionX < $("+objNameOfImageBrowser+".jqBigImg).width()/2)  ";
	   txtBigImgMouseMove+="{ if("+objNameOfImageBrowser+".currentCursor!='left')" ;
       txtBigImgMouseMove+="{$("+objNameOfImageBrowser+".jqBigImg).css('cursor',"+objNameOfImageBrowser+".cssLeftArrow);   ";
	   txtBigImgMouseMove+=objNameOfImageBrowser+".currentCursor='left';}}  ";	   
       txtBigImgMouseMove+="else  ";
	   txtBigImgMouseMove+="{ if("+objNameOfImageBrowser+".currentCursor!='right')" ;
       txtBigImgMouseMove+="{ $("+objNameOfImageBrowser+".jqBigImg).css('cursor',"+objNameOfImageBrowser+".cssRightArrow);  ";
	   txtBigImgMouseMove+=objNameOfImageBrowser+".currentCursor='right';}}  ";
       txtBigImgMouseMove+="});  ";
	   return txtBigImgMouseMove;
   }
   //鼠标按上下时显示手形
  this.createBigImgMouseDown=function(objNameOfImageBrowser) {
	   var txtBigImgMouseDown="";
	   txtBigImgMouseDown+="$("+objNameOfImageBrowser+".jqBigImg).mousedown(function(){  ";
       txtBigImgMouseDown+="$("+objNameOfImageBrowser+".jqBigImg).css({'cursor':'pointer'}); ";
       txtBigImgMouseDown+="}); ";
	   return txtBigImgMouseDown;
   }
   //鼠标松开时显示左右箭头
   this.createBigImgMouseUp=function(objNameOfImageBrowser) {
	    var txtBigImgMouseUp="";
	    txtBigImgMouseUp+="$("+objNameOfImageBrowser+".jqBigImg).mouseup(function(e){  ";
        txtBigImgMouseUp+="var positionX=e.originalEvent.x-$("+objNameOfImageBrowser+".jqBigImg).offset().left||e.originalEvent.layerX-$("+objNameOfImageBrowser+".jqBigImg).offset().left||0; ";
        txtBigImgMouseUp+="if(positionX < $("+objNameOfImageBrowser+".jqBigImg).width()/2)  ";
        txtBigImgMouseUp+="{$("+objNameOfImageBrowser+".jqBigImg).css('cursor',"+objNameOfImageBrowser+".cssLeftArrow);   ";
	    txtBigImgMouseUp+=objNameOfImageBrowser+".currentCursor='left';}  ";	   
        txtBigImgMouseUp+="else  ";
        txtBigImgMouseUp+="{ $("+objNameOfImageBrowser+".jqBigImg).css('cursor',"+objNameOfImageBrowser+".cssRightArrow);  ";
	    txtBigImgMouseUp+=objNameOfImageBrowser+".currentCursor='right';}  ";
        txtBigImgMouseUp+="});  ";
		return txtBigImgMouseUp;
   }
   //点击大图时切换图片
   this.createBigImgClick=function(objNameOfImageBrowser) {
	    var each_bigimg_default_flag = 0;
	    var txtBigImgClick="";
	    txtBigImgClick+="$("+objNameOfImageBrowser+".jqBigImg).click(function(e){ 　";
　       	txtBigImgClick+="var positionX=e.originalEvent.x-$("+objNameOfImageBrowser+".jqBigImg).offset().left||e.originalEvent.layerX-$("+objNameOfImageBrowser+".jqBigImg).offset().left||0;  ";
        txtBigImgClick+="var numPreImg=("+objNameOfImageBrowser+".currentImgNum-1<0)?0:("+objNameOfImageBrowser+".currentImgNum-1);  ";
        txtBigImgClick+="var numNextImg=("+objNameOfImageBrowser+".currentImgNum+1>imgBigDesc.length-1)?(imgBigDesc.length-1):("+objNameOfImageBrowser+".currentImgNum+1);  ";
        txtBigImgClick+="if(positionX < $("+objNameOfImageBrowser+".jqBigImg).width()/2) {  ";
        txtBigImgClick+=" if("+default_pic_id+" == "+objNameOfImageBrowser+".arrBigImgDesc[numPreImg][2]){ each_bigimg_default_flag = 1;}else{ each_bigimg_default_flag = 0;} ";
        txtBigImgClick+=objNameOfImageBrowser+".imgChangeToBig("+objNameOfImageBrowser+".arrBigImgDesc[numPreImg][1],"+objNameOfImageBrowser+".arrBigImgDesc[numPreImg][0],"+objNameOfImageBrowser+".arrBigImgDesc[numPreImg][2],"+"numPreImg,'"+objNameOfImageBrowser+"',each_bigimg_default_flag);} ";
        txtBigImgClick+=" else {  ";
        txtBigImgClick+=" if("+default_pic_id+" == "+objNameOfImageBrowser+".arrBigImgDesc[numNextImg][2]){ each_bigimg_default_flag = 1;}else{ each_bigimg_default_flag = 0;} ";
        txtBigImgClick+=objNameOfImageBrowser+".imgChangeToBig("+objNameOfImageBrowser+".arrBigImgDesc[numNextImg][1],"+objNameOfImageBrowser+".arrBigImgDesc[numNextImg][0],"+objNameOfImageBrowser+".arrBigImgDesc[numNextImg][2],"+"numNextImg,'"+objNameOfImageBrowser+"',each_bigimg_default_flag);} ";
        txtBigImgClick+=" });  ";
		return txtBigImgClick;
   }
   this.createScriptHead=function(){
      var txtScriptHead=""; 
      txtScriptHead+=String.fromCharCode(60)+"script language='javascript'"+String.fromCharCode(62)+"  " ;
      txtScriptHead+="$(function(){  ";		
	  txtScriptHead+="var oldLeftNum=0; ";						 
      return txtScriptHead;
   }
   this.createScriptTail=function(){
      var txtScriptTail="";
      txtScriptTail+="});  ";
      txtScriptTail+=String.fromCharCode(60)+"/script"+String.fromCharCode(62)+"  ";
      return txtScriptTail;
   }
}

//获取元素的纵坐标
function getTop(e){
var offset=e.offsetTop;
if(e.offsetParent!=null) offset+=getTop(e.offsetParent);
return offset;
}
//获取元素的横坐标
function getLeft(e){
var offset=e.offsetLeft;
if(e.offsetParent!=null) offset+=getLeft(e.offsetParent);
return offset;
}

function createBlackAndBigImageDiv(imgUrlX) {


   
   var bigImage=new Image();
   bigImage.src=imgUrlX;
   var imgWidth;
   var imgHeight;
   if(bigImage.complete) {
	   scl=bigImage.width/bigImage.height;
	   if(bigImage.width>document.body.clientWidth)
		  {
			 if(document.body.clientWidth/scl<document.body.clientHeight) 
				 {
					 imgWidth=document.body.clientWidth;
					 imgHeight=document.body.clientWidth/scl;
				
				 }
			  else
				 {
					imgWidth=document.body.clientHeight*scl;
					imgHeight=document.body.clientHeight;
				 }
		   }
		 else
		   {
			 if(bigImage.height>document.body.clientHeight)
				{
					imgWidth=document.body.clientHeight*scl;
					imgHeight=document.body.clientHeight;
				}
				
			 else
				{
					imgWidth=bigImage.width;
					imgHeight=bigImage.height;
				}
		   }
   }
	//imgWidth=imgWidth-40;$("body").scrollLeft()+document.body.clientWidth/2
   // imgHeight=imgHeight-40;document.body.scrollWidth
   $("#blackDiv").css({width:$("body").scrollLeft()+document.body.clientWidth,height:document.body.scrollHeight});
   $("#blackDiv").css({'opacity':0.6});
   $("#blackDiv").css({'left':0});
 	var myleft = 490-imgWidth/2;
 	myleft = myleft<0 ?0:myleft;	
 	var mytop = document.documentElement.scrollTop/2+document.body.clientHeight/2-imgHeight/2;
 	//alert($("body").scrollTop());
 	//alert(document.documentElement.scrollTop);
 	//alert(mytop);
   $("#bigImageDiv").css({left:myleft,top:mytop,width:imgWidth,height:imgHeight});
  // alert(imgWidth+"|"+imgHeight);
   $("#bigImageDiv").html("<img src='"+imgUrlX+"' width="+imgWidth+" height="+imgHeight+" >")
   $("#blackDiv").toggle();
   $("#bigImageDiv").toggle();
  // alert(getLeft(document.getElementById("bigImageDiv")));alert(getLeft(document.getElementById("blackDiv")));
  // $("#bigImageDiv").animate({width:imgWidth},{height:imgHeight},1000);
   //$("#bigImageDiv").animate({height:imgHeight},500);
}