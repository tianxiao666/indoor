	function selCheckBox(){
      var flag = document.getElementById("checkall").checked;
	  var ckboxes = document.getElementsByName("track");
	  for(i=0; i < ckboxes.length; i++) {
	       ckboxes[i].checked = flag;
	  }
   }
	function del(){			
		var sjb_right = $("#route > li > label > input"); 
    	for(var i=0;i<sjb_right.length;i++){
			var cb = sjb_right[i];
			if(cb.checked){	
				var spotid = $(cb).attr("id");
				$(cb).parent().parent().remove();
				
			}
		}
	}
	
	function asc(){
		order_by("asc");				
	}	
	function desc(){		
		order_by("desc");
	}
	
	function order_by(v){
	var sjb_right = document.getElementById("route");
	var lis = sjb_right.children;
	var my_asc=new Array();	
	if(v=="asc"){
		for(var i=0;i<lis.length;i++){
			if (i > 0) {
				var last = lis[i-1];
				var li = lis[i];
				if (li.firstChild.firstChild.checked) {
					var dom = li.parentNode.removeChild(li);									
					sjb_right.insertBefore(dom, last);
					dom.firstChild.firstChild.checked = 'checked';
				}
			}
		}
	}else{
		for(var i=lis.length-1;i>=0;i--){
			if (i < lis.length -1) {
				var next = lis[i+1];
				var li = lis[i];
				if (li.firstChild.firstChild.checked) {
					var checked = next.firstChild.checked;
					var dom = next.parentNode.removeChild(next);														
					sjb_right.insertBefore(dom, li);
					dom.firstChild.firstChild.checked = checked;
				}
			}
		}
	}
	for(var i=0;i<my_asc.length;i++){
		sjb_right.appendChild(my_asc[i]);
	}
}