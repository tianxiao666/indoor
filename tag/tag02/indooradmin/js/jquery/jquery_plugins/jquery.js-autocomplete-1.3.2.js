/* Ryoma's Auto-Complete (ver 1.3.2.topfans) TopFans.com revision
--------------------------------------------------------------------
  
  This file is Top Fans' hacked version of: 
    * http://code.google.com/p/js-autocomplete/ ver 1.3.2
    * An Auto-Complete jQuery Plugin by Ryoma Nakashima 
        - nakashima@2next.co.jp
        - http://www.2next.co.jp/
  
  This version adds the following improvements
    by Wil Everts (wil@topfans.com) for http://topfans.com: 
    * added JSON Support
    * removed avoidable css definitions from the plugin conf so it 
      can be kept in the css where it belongs
    * added conf['result-type'] to distinguish plain or json 
      result
    * added conf['min-search'] to improve speed by waiting 
      until X chars have been typed
    * fixed a bug to allows the form post with the current 
      text when no result is selected (good for search boxes)
    * enabled the conf['strict'] = 'true/false'; variable 
      which switches between drop-down style boxes that don't 
      allow variation (true), and "search" or form input type 
      boxes that allow an unknown be submitted (false)
    * *FIXME* 02 add an optional results object. - if its an 
      object rather than a string...
  
  Dual licensed under the MIT and GPL licenses:
    - http://www.opensource.org/licenses/mit-license.php
    - http://www.gnu.org/licenses/gpl.html
      
  Original js-autocomplete Project's site: 
    - http://code.google.com/p/js-autocomplete/

  This requires jQuery and jQuery Hotkeys Plugin.
    - http://jquery.com/
    - http://code.google.com/p/js-hotkeys/
    
    
  SET UP
  ----------------------------------------------------------------- 
	  For this code to work you'll need:
	 
	  1. A form with a text input with class="auto_complete"
	  2. An empty ul beneath #1 with class="auto_complete_dropdown". 
	     - Give this your desired id/css, we'll position it...
	  3. A max-height on your result <ul /> css
	     - At which point we'll add a scroll bar...
	  4. A line-height on your <li /> css (maybe?)
	  5. Edit the url and conf values below, we need them.

*/

function autoComplete (callBack){

	/* AJAX URL
	-------------------------------------------------------------- */
	var url = new Array();
//	url['searchterm'] = '/pages/autocomplete.json?q=';
//	url['header_searchterm'] = '/pages/autocomplete.json?q=';
	url['ajax'] = 'ajax.cgi?q=';
	
	/* Configurations
  ---------------------------------------------------------------*/
  var conf = new Array();
  
	conf['list-color'] = '#999'; // original state
	conf['list-background'] = '#FFF'; // original state
	conf['list-color-active'] = '#999'; // selected li
	conf['list-background-active'] = '#f3f8fe'; // selected li
		
	conf['strict'] = 'true'; /* Strict Mode
	-----------------------------------------------------------------
	  - 'true' for a dropdown-like box
	  - 'false' for a search or input box
  -------------------------------------------------------------- */
  
	conf['min-search'] = 1; /* Minmum Search Length
	-----------------------------------------------------------------
	  don't send an ajax query unless it is at least this many 
	  chars long
  -------------------------------------------------------------- */
  
	conf['result-type'] = ''; /* return json, plain text, or []
	-----------------------------------------------------------------
    this can be any of the following:
      1. conf['result-type'] = 'json'; 
        - A json object formed like so:
        {"results":[
          {"name":"Albert Camus"},
          {"name":"Albert Einstein"},
          {"name":"Albert Pujols"} 
        ]}
    
      2. conf['result-type'] = 'plain';
        - Plain text seperated by new lines (/n) 
      3. conf['result-type'] = 'array'; //*FIXME* 02  
	-------------------------------------------------------------- */


	/* Auto Complete!
	-------------------------------------------------------------- */
	$('.auto_complete').each(function(){

		var active = -1;
		var keybind = 0;
		var inputBefore = '';
		var position = '';
		
		var ua = $.browser;	
		
		var textBox = $(this);
		textBox.attr('autocomplete','off');
		
		var width = textBox.outerWidth();
		var height = textBox.outerHeight();

		var textBorderWidth = 0;
		if(textBox.css('border-left-width').match(/^\d+px$/)){
			textBorderWidth += Number(textBox.css('border-left-width').replace(/px$/,''));
		}
		if(textBox.css('border-right-width').match(/^\d+px$/)){
			textBorderWidth += Number(textBox.css('border-right-width').replace(/px$/,''));
		}

		if($(this).next().attr('tagName') != 'UL'){
			$(this).after('<ul class="auto_complete_dropdown"></ul>');
		}

		var box = $(this).next();		
		box.css({
			'display':'none',
			'z-index':'2147483647',
			'overflow-x':'hidden',
			'height':'auto'
		});
		var borderWidth = 0;
		if(box.css('border-left-width').match(/^\d+px$/)){
			borderWidth += Number(box.css('border-left-width').replace(/px$/,''));
		}
		if(box.css('border-right-width').match(/^\d+px$/)){
			borderWidth += Number(box.css('border-right-width').replace(/px$/,''));
		}
		
		box.width(width-borderWidth-textBorderWidth);
		if(box.css('max-height') == 'none'){
			box.css('max-height','350px');
		}

		var maxHeight = 0;
		if(box.css('max-height')){
			maxHeight = box.css('max-height').replace(/px$/,'');
		}else{
			maxHeight = 350;
		}
		var list = box.find('li');
		createList();
	
		textBox.click(function(){
			actionBox(list);
		});

		textBox.keyup(function(event){
			if(event.keyCode != 37 && event.keyCode != 38 && event.keyCode != 39 && event.keyCode != 40){

				if(textBox.attr('id') && url[textBox.attr('id')] && inputBefore != input && textBox.val().length >= conf['min-search']){

					var input = textBox.val();
					inputBefore = input;
					
					if ( conf['result-type'] == 'json' ) {
						$.getJSON(url[textBox.attr('id')] + input, function(data){
							box.empty();

							$.each(data.results, function(i,result){
								//console.log(this.name); //for debugging only
								var li = $('<li>'+this.name+'</li>');
								box.append(li);
							});
							list = box.find('li');
							createList();

							var ajax = 1;

							actionBox(list,ajax);
						});
					} else {
						jQuery.ajax({
	  						url: url[textBox.attr('id')] + input,
	  						cache: true,
	  						success: function(data){
	  							box.empty();
	  							
	  							jQuery.each(data.split("\n"), function(i, item){
									if(String(this).length > 0){
	  									var li = $('<li>'+item+'</li>');
	  									box.append(li);
									}
	  							});
	  
	  							list = box.find('li');
	  							createList();
	  
	  							var ajax = 1;
	  
	  							actionBox(list,ajax);
	  						}
  						});
					}
				} else {
					
					actionBox(list);
				}
			}
		});
	
		function createList () {
			list.css({
				'overflow-x':'hidden',
				'white-space':'nowrap',
				'height':'24px',
				'line-height':'24px'
			});
			list.width(width-8);

			list.each(function(){
				$(this).data('text',$(this).text().replace(/\s+$/,''));
				$(this).data('textLC',$(this).data('text').toLowerCase());
			});
		}

		function actionBox (list,ajax) {
			if((!textBox.val() || ajax) && list.size() > 0){
				active = -1;
				box.scrollTop(0);
				list.css({
					'background':conf['list-background'],
					'color':conf['list-color']
				});

				list.each(function(){
					$(this).css('display','block');
					$(this).addClass('select-list');
				});
				selectBoxOn();
			}else{
				var boxon = 0;
				var input = textBox.val();
				var inputLC = textBox.val().toLowerCase();
					active = -1;
					box.scrollTop(0);
					list.css({
						'background':conf['list-background'],
						'color':conf['list-color']
					});
					
					list.each(function(){
						var re = new RegExp('^'+inputLC, 'i');
						if(!$(this).data('textLC').match(re) || $(this).data('text') == input){
							$(this).css('display','none');
							$(this).removeClass('select-list');
						}else{
							$(this).css('display','block');
							$(this).addClass('select-list');
							boxon = 1;
						}
					});
					if(boxon){
						selectBoxOn();
					}else{
						selectBoxOff();
					}
			}
		}
		function selectBoxOn () {			
			if(box.css('display') != 'block'){
				active = -1;
			}

			var width = textBox.width();
			if(box.height() > maxHeight){
				box.css({
					width:width+'px',
					height:maxHeight+'px',
					overflowY: 'scroll'
				});
			}else{
				box.css({
					width:width+'px',
					overflowY: 'auto'
				});
			}

			fixBox ();

			$(textBox).bind('blur',selectBoxOff);
			
			box.mouseover(function(){
				$(textBox).unbind('blur',selectBoxOff);
			});
			box.mouseout(function(){
				$(textBox).bind('blur',selectBoxOff);
			});
			
			

			list.bind('mouseover',listMouseOver);
			list.bind('click',listClick);

			$(document).bind('keydown', 'down', listDown);
			$(document).bind('keydown', 'up', listUp);
			$(document).bind('keydown', 'return', listEnter);
			$(window).bind('resize',fixBox);
			keybind = 1;
		}
		function fixBox () {
			var offset = textBox.position();
//			var left = offset.left + 5;
			var left = offset.left;
			var top = offset.top;
			
			box.css({
				'position':'absolute',
				'left':left + 'px'
			});


			var bHeight = getBrowserHeight();
			var scrollTop = getScrollTop();
			
			box.css('display','block');
			if(bHeight < top+height+box.height()-scrollTop && top+height-scrollTop > box.height()){
				box.css('top',(top-box.height()-1) + 'px');
				
				position = 'up';

				scrollTopNew = (box.find('li.select-list').size() -1) * list.outerHeight() - (maxHeight - list.outerHeight());
				if(box.scrollTop() - scrollTopNew < list.height()){
					box.scrollTop(scrollTopNew);
				}
								
			}else{
			
					box.css('top',(top + textBox.outerHeight()-1) + 'px');

				position = 'down';
			}
		}
		function selectBoxOff () {		
			list.unbind('mouseover',listMouseOver);
			list.unbind('click',listClick);
			if(keybind){
				$(document).unbind('keydown', 'down', listDown);
				$(document).unbind('keydown', 'up', listUp);
				$(document).unbind('keydown', 'return', listEnter);
				$(window).unbind('resize',fixBox);
				keybind = 0;
			}

			box.scrollTop(0);
			list.css({
				'background':conf['list-background'],
				'color':conf['list-color']
			});
			box.css('display','none');
		}
		function listMouseOver () {
			list.css({
				'background':conf['list-background'],
				'color':conf['list-color']
			});
			$(this).css({
				'background':conf['list-background-active'],
				'color':conf['list-color-active']
			});
			active = box.find('li.select-list').index(this);
		}
		function listClick () {
			var text = $(this).text().replace(/\s+$/,'');
			textBox.val(text);
			selectBoxOff();
//			textBox.focus();
			
			if ('function' == typeof(callBack))
			{
				callBack(this.title, text);
			}
		}
		function listDown () {
			var next = 0;
			if(position == 'up' && active == -1){
				return true;
			}else{
				next = active + 1;
			}

			if(box.find('li.select-list').eq(active + 1).size()){
				list.css({
					'background':conf['list-background'],
					'color':conf['list-color']
				});
				active += 1;
				box.find('li.select-list').eq(active).css({
					'background':conf['list-background-active'],
					'color':conf['list-color-active']
				});
				
				scrollTopNew = active * list.outerHeight() - (maxHeight - list.outerHeight());
				
				if(box.scrollTop() - scrollTopNew < list.height()){
					box.scrollTop(scrollTopNew);
				}				

				return true;
			}
		}
		function listUp () {
			var next = 0;
			if(active - 1 < -1){
				if(position == 'up'){
					next = box.find('li.select-list').size() -1;
				}else{
					return true;
				}
			}else{
				next = active - 1;
			}

			if(box.find('li.select-list').eq(next).size()){
				list.css({
					'background':conf['list-background'],
					'color':conf['list-color']
				});
				active = next;
				box.find('li.select-list').eq(active).css({
					'background':conf['list-background-active'],
					'color':conf['list-color-active']
				});

				scrollTopNew = active * list.outerHeight();
				if(scrollTopNew - box.scrollTop() < list.height()){
					box.scrollTop(scrollTopNew);
				}				

				return true;
			}
		}
		function listEnter (event) {
  			var text = box.find('li.select-list').eq(active).text().replace(/\s+$/,'');
			if (conf['strict'] == 'false') {
				if (text) { // to allow the regular results if nothing has been selected...
					textBox.val(text);
	    				selectBoxOff();
	    				textBox.focus();
				}
			} else if (conf['strict'] == 'true') {
				textBox.val(text);
				selectBoxOff();
	    			textBox.focus();
	    			enableEnter(event);
			}
			
			if ('function' == typeof(callBack))
			{
				callBack(box.find('li.select-list').eq(active).attr('title'), text);
				textBox.blur();
			}
		}
		
		function enableEnter(e){
	  			if(e.srcElement){
	  				o = e.srcElement;
	  			}else{
	  				o = e.target;
	  			}
				if (o.tagName != 'TEXTAREA' && e.keyCode == 13) {
	  				if(e.preventDefault){
	  					e.preventDefault();
	  					e.stopPropagation();
	  				}
	  				e.returnValue=false;
	  				e.cancelBubble=true;
	  			}
		}
		function getBrowserHeight() {
			if (window.innerHeight) {
				return window.innerHeight;
			}
			else if(document.documentElement && document.documentElement.clientHeight != 0){
				return document.documentElement.clientHeight;
			}
			else if ( document.body ) {
				return document.body.clientHeight;
			}
			return 0;
		}
		function getScrollTop() {
			var scrollTop  = document.body.scrollTop  || document.documentElement.scrollTop;
			return scrollTop;
		}
	});

}
