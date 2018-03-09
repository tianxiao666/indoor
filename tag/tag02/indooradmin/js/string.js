;(function($) {
	$.extend({
		trim : function(str){
			return str.replace(/(^\s*)(\s*$)/g,'');
		},
		ltrim : function(str){
			return str.replace(/(^\s*)/g,'');
		},
		rtrim : function(str){
			return str.replace(/(\s*$)/g,'');
		}
	});
});