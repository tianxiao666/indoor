/**
 * 
 * @param obj参数
 * id   补全控件ID
 * url  请求数据URL , url为完整url（带参数），默认关键字参数名为q，若为默认则可不带
 * @returns
 */
$.complete = function(obj){
	KISSY.ready(function(S) {
		dataUrl = obj.url;
	    var sug = new S.Suggest("#"+obj.id, obj.selid , dataUrl, {
	        charset: 'gbk',
	        callbackFn: 'Callback',
	    });
	    sug.on('dataReturn', function() {
	        var data = this.returnedData || [];
	        var result = [];
	        for (var i = 0, len = data.length; i < len; ++i) {
	            result.push([data[i]['val'], data[i]['key']]);
	        }
	        this.returnedData = result;
	    });
	    //设置选中项
	    if(obj.selid){
		    sug.on('itemSelect', function() {
		    	document.getElementById(obj.selid).value= this.textInput.wid;
	        });
	    }
	    
	});
}