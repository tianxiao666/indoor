/*
 * ext-shapes.js
 *
 * Licensed under the MIT License
 *
 * Copyright(c) 2010 Christian Tzurcanu
 * Copyright(c) 2010 Alexis Deveria
 *
 */

var env = env || {};
if(!!!env.svgeditdir){
	env.svgeditdir = "";
}
svgEditor.addExtension("shapes", function() {
	

	var current_d, cur_shape_id;
	var canv = svgEditor.canvas;
	var cur_shape;
	var start_x, start_y;
	var svgroot = canv.getRootElem();
	var lastBBox = {};
	var categories = {
		svg:'室内地图图形'
	};
	
	var library = {
		'svg': {
			data: {
				"AP":"m7.74049,154.30731c0,-81.53617 66.01012,-147.60641 147.50276,-147.60641c81.49272,0 147.50067,66.07024 147.50067,147.60641c0,81.55721 -66.00795,147.62857 -147.50067,147.62857c-81.49265,0 -147.50276,-66.07137 -147.50276,-147.62857zm42.77752,103.33151l210.92135,-208.12111m-210.92135,0l210.18154,208.12111z",
				"电梯":"m3.99425,71.19325l294.99992,0l0,196.35039l-294.99992,0l0,-196.35039m1.89713,0.94836l291.20607,193.505m0,-191.60806l-291.20607,190.65915z",
				"卫生间": "m84.9474,60.80411c0,-11.62441 9.41645,-21.04059 21.04043,-21.04059c11.62517,0 21.04082,9.41618 21.04082,21.04059c0,11.62392 -9.41565,21.03993 -21.04082,21.03993c-11.62398,0 -21.04043,-9.41601 -21.04043,-21.03993zm70.74133,59.55699l0,-10.62535c0,-11.18082 -9.058,-20.24509 -20.24481,-20.24509l-58.97723,0c-11.17975,0 -20.2445,9.06426 -20.2445,20.24509l0,10.62535c-0.01946,0.24191 -0.03258,0.48894 -0.03258,0.73993l0,61.20378c0,4.75555 3.85319,8.60802 8.6082,8.60802c4.74956,0 8.61107,-3.85246 8.61107,-8.60802l0,-60.29781l5.77377,0l0,68.91226l0.04169,0l0,97.37148c0,6.32895 5.14025,11.47287 11.47643,11.47287c6.34019,0 11.47691,-5.13727 11.47691,-11.47287l0,-97.37148l7.57166,0l0,97.37148c0,6.32895 5.14391,11.47287 11.47673,11.47287c6.34007,0 11.47714,-5.13727 11.47714,-11.47287l0,-97.37148l0.03571,0l0,-68.91226l5.77272,0l0,60.29478c0,4.75493 3.86162,8.61104 8.61116,8.61104c4.75578,0 8.60838,-3.85611 8.60838,-8.61104l0,-61.20419c-0.00296,-0.25426 -0.02321,-0.49458 -0.04245,-0.7365l0,0zm70,-38c11.30028,0 20.46906,-9.44522 20.46906,-21.0955c0,-11.65828 -9.16878,-21.10944 -20.46906,-21.10944c-11.30389,0 -20.47639,9.45116 -20.47639,21.10944c0,11.65028 9.16899,21.0955 20.47639,21.0955zm57.40092,90.98302l-18.36676,-70.21626c-0.17056,-0.6383 -0.41412,-1.24538 -0.70395,-1.80753c-2.08493,-6.57793 -8.07782,-11.34151 -15.15419,-11.34151l-46.48441,0c-7.37724,0 -13.5706,5.17218 -15.39273,12.1907c-0.12369,0.30804 -0.22406,0.63103 -0.32072,0.96516l-18.03468,70.21241c-1.20367,4.60303 1.44995,9.33368 5.91808,10.57269c4.46855,1.23239 9.05663,-1.50006 10.25957,-6.10443l14.16318,-55.1545l5.90312,0l-25.71001,99.79314l24.22821,0l0,68.89568c0,5.2998 4.15927,9.5918 9.30833,9.5918c5.13326,0 9.30289,-4.28885 9.30289,-9.5918l0,-68.89568l7.40501,0l0,68.89568c0,5.2998 4.16306,9.5918 9.31187,9.5918c5.13681,0 9.30701,-4.28885 9.30701,-9.5918l0,-68.89568l24.2142,0l-25.80751,-99.79314l6.05385,0l14.43002,55.1545c1.2027,4.60437 5.79117,7.33682 10.25546,6.10443c4.45685,-1.23901 7.1102,-5.9727 5.91415,-10.57565l0,0z",
				"楼梯": "m2.18934,245.58681c17.86729,-0.11601 35.73457,-0.23204 53.60186,-0.34804c0.11764,-18.34987 0.23526,-36.69974 0.35289,-55.04959c17.78193,0 35.56387,0 53.34581,0c0,-18.46245 0,-36.9249 0,-55.38734c18.2373,0 36.47458,0 54.71188,0c0,-18.01214 0,-36.02428 0,-54.03642c18.01213,0 36.02426,0 54.03641,0c0,-18.01213 0,-36.02428 0,-54.03642c26.79306,0 53.58612,0 80.37918,0c0,9.00607 0,18.01214 0,27.01821c-17.78702,0 -35.57397,0 -53.36099,0c0,18.23729 0,36.47458 0,54.71188c-18.01212,0 -36.02425,0 -54.03641,0c0,18.2373 0,36.47459 0,54.71188c-18.01215,0 -36.02426,0 -54.03641,0c0,18.23729 0,36.47459 0,54.71185c-18.2373,0 -36.47459,0 -54.71187,0c0,18.46246 0,36.9249 0,55.38733c-27.01822,0 -54.03642,0 -81.05463,0c1.20448,-8.94977 -2.05336,-19.04163 0.77228,-27.68333z",
				"svg_3":"M9.230468134546157,172.49999876718647 C9.230468134546157,93.06931851856768 73.56931913592737,28.73046751718647 152.99999938454616,28.73046751718647 C232.43067963316494,28.73046751718647 296.7695306345462,93.06931851856768 296.7695306345462,172.49999876718647 C296.7695306345462,251.93067901580525 232.43067963316494,316.26953001718647 152.99999938454616,316.26953001718647 C73.56931913592737,316.26953001718647 9.230468134546157,251.93067901580525 9.230468134546157,172.49999876718647 M152,30L152,315M152,29L100,150M151,29L204,150Z",
				"双门":"M1,301 C1,301 1,302 1,101 C1,-100 150,301 150,301M299,301 C299,301 299,300 298,101 C297,-98 144,302 151,302",
				"单门":"m68.66519,249.96291c0,0 0,0.83012 0,-166.15679c0,-166.98776 188.23318,166.15679 188.23318,166.15679"
			},
			buttons: []
		}
	};
	
	var cur_lib = library.svg;
	
	var mode_id = 'shapelib';
	
	function loadIcons() {
		$('#shape_buttons').empty();
		
		// Show lib ones
		$('#shape_buttons').append(cur_lib.buttons);
	}
	
	function loadLibrary(cat_id) {
	
		var lib = library[cat_id];
		
		if(!lib) {
			$('#shape_buttons').html('Loading...');
			$.getJSON(env.svgeditdir + 'extensions/shapelib/' + cat_id + '.json', function(result, textStatus) {
				cur_lib = library[cat_id] = {
					data: result.data,
					size: result.size,
					fill: result.fill
				}
				makeButtons(cat_id, result);
				loadIcons();
			});
			return;
		}
		
		cur_lib = lib;
		if(!lib.buttons.length)	makeButtons(cat_id, lib);
		loadIcons();
	}
	
	function makeButtons(cat, shapes) {
		var size = cur_lib.size || 300;
		var fill = cur_lib.fill || false;
		var off = size * .05;
		var vb = [-off, -off, size + off*2, size + off*2].join(' ');
		var stroke = fill ? 0: (size/30);
		
		var shape_icon = new DOMParser().parseFromString(
			'<svg xmlns="http://www.w3.org/2000/svg"><svg viewBox="' + vb + '"><path fill="'+(fill?'#333':'none')+'" stroke="#000000" stroke-width="' + stroke + '" /><\/svg><\/svg>',
			'text/xml');

		var width = 24;
		var height = 24;
		shape_icon.documentElement.setAttribute('width', width);
		shape_icon.documentElement.setAttribute('height', height);
		var svg_elem = $(document.importNode(shape_icon.documentElement,true));
	
		var data = shapes.data;
		
		cur_lib.buttons = [];
	
		for(var id in data) {
			var path_d = data[id];
			var icon = svg_elem.clone();
			icon.find('path').attr('d', path_d);
			
			var icon_btn = icon.wrap('<div class="tool_button">').parent().attr({
				id: mode_id + '_' + id,
				title: id
			});
			
			
			// Store for later use
			cur_lib.buttons.push(icon_btn[0]);
		}
		
	}

	
	return {
		svgicons: env.svgeditdir + "extensions/ext-shapes.xml",
		buttons: [{
			id: "tool_shapelib",
			type: "mode_flyout", // _flyout
			position: 6,
			title: "室内地图图形库",
			events: {
				"click": function() {
					canv.setMode(mode_id);
				}
			}
		}],
		callback: function() {
		
			$('<style>').text('\
			#shape_buttons {\
				overflow: auto;\
				width: 180px;\
				max-height: 300px;\
				display: table-cell;\
				vertical-align: middle;\
			}\
			\
			#shape_cats {\
				min-width: 150px;\
				display: table-cell;\
				vertical-align: middle;\
				height: 300px;\
			}\
			#shape_cats > div {\
				line-height: 1em;\
				padding: .5em;\
				border:1px solid #B0B0B0;\
				background: #E8E8E8;\
				display: none;\
				margin-bottom: -1px;\
			}\
			#shape_cats div:hover {\
				background: #FFFFCC;\
			}\
			#shape_cats div.current {\
				font-weight: bold;\
			}').appendTo('head');

		
			var btn_div = $('<div id="shape_buttons">');
			$('#tools_shapelib > *').wrapAll(btn_div);
			
			var shower = $('#tools_shapelib_show');

			
			loadLibrary('svg');
			
			// Do mouseup on parent element rather than each button
			$('#shape_buttons').mouseup(function(evt) {
				var btn = $(evt.target).closest('div.tool_button');
				
				if(!btn.length) return;
				
				var copy = btn.children().clone();
				shower.children(':not(.flyout_arrow_horiz)').remove();
				shower
					.append(copy)
					.attr('data-curopt', '#' + btn[0].id) // This sets the current mode
					.mouseup();
				canv.setMode(mode_id);
				
				cur_shape_id = btn[0].id.substr((mode_id+'_').length);
				current_d = cur_lib.data[cur_shape_id];
				
				$('.tools_flyout').fadeOut();

			});
			var shape_cats = $('<div id="shape_cats">');
			
			var cat_str = '';
			
			$.each(categories, function(id, label) {
				cat_str += '<div data-cat=' + id + '>' + label + '</div>';
			});
			
			shape_cats.html(cat_str).children().bind('mouseup', function() {
				var catlink = $(this);
				catlink.siblings().removeClass('current');
				catlink.addClass('current');
				
				loadLibrary(catlink.attr('data-cat'));
				// Get stuff
				
				return false;
			});
			
			shape_cats.children().eq(0).addClass('current');
			
//			$('#tools_shapelib').append(shape_cats);

			shower.mouseup(function() {
				canv.setMode(current_d ? mode_id : 'select');
			});

			
			$('#tool_shapelib').remove();
			
			var h = $('#tools_shapelib').height();
			$('#tools_shapelib').css({
				'margin-top': -(h/2 - 15),
				'margin-left': 3
			});

	
		},
		mouseDown: function(opts) {
			var mode = canv.getMode();
			if(mode !== mode_id) return;
			
			var e = opts.event;
			var x = start_x = opts.start_x;
			var y = start_y = opts.start_y;
			var cur_style = canv.getStyle();

			cur_shape = canv.addSvgElementFromJson({
				"element": "path",
				"curStyles": true,
				"attr": {
					"d": current_d,
					"id": canv.getNextId(),
					"opacity": cur_style.opacity / 2,
					"style": "pointer-events:none"
				}
			});
			
			// Make sure shape uses absolute values
			if(/[a-z]/.test(current_d)) {
				current_d = cur_lib.data[cur_shape_id] = canv.pathActions.convertPath(cur_shape);
				cur_shape.setAttribute('d', current_d);
				canv.pathActions.fixEnd(cur_shape);
			}
	
			cur_shape.setAttribute('transform', "translate(" + x + "," + y + ") scale(0.005) translate(" + -x + "," + -y + ")");
			
// 			console.time('b');
			canv.recalculateDimensions(cur_shape);
			
			var tlist = canv.getTransformList(cur_shape);
			
			lastBBox = cur_shape.getBBox();
			
			return {
				started: true
			}
			// current_d
		},
		mouseMove: function(opts) {
			var mode = canv.getMode();
			if(mode !== mode_id) return;
			
			var zoom = canv.getZoom();
			var evt = opts.event
			
			var x = opts.mouse_x/zoom;
			var y = opts.mouse_y/zoom;
			
			var tlist = canv.getTransformList(cur_shape),
				box = cur_shape.getBBox(), 
				left = box.x, top = box.y, width = box.width,
				height = box.height;
			var dx = (x-start_x), dy = (y-start_y);

			var newbox = {
				'x': Math.min(start_x,x),
				'y': Math.min(start_y,y),
				'width': Math.abs(x-start_x),
				'height': Math.abs(y-start_y)
			};

			var ts = null,
				tx = 0, ty = 0,
				sy = height ? (height+dy)/height : 1, 
				sx = width ? (width+dx)/width : 1;

			var sx = newbox.width / lastBBox.width;
			var sy = newbox.height / lastBBox.height;
			
			sx = sx || 1;
			sy = sy || 1;
			
			// Not perfect, but mostly works...
			if(x < start_x) {
				tx = lastBBox.width;
			}
			if(y < start_y) ty = lastBBox.height;
			
			// update the transform list with translate,scale,translate
			var translateOrigin = svgroot.createSVGTransform(),
				scale = svgroot.createSVGTransform(),
				translateBack = svgroot.createSVGTransform();
				
			translateOrigin.setTranslate(-(left+tx), -(top+ty));
			if(!evt.shiftKey) {
				var max = Math.min(Math.abs(sx), Math.abs(sy));

				sx = max * (sx < 0 ? -1 : 1);
				sy = max * (sy < 0 ? -1 : 1);
			}
			scale.setScale(sx,sy);
			
			translateBack.setTranslate(left+tx, top+ty);
			var N = tlist.numberOfItems;
			tlist.appendItem(translateBack);
			tlist.appendItem(scale);
			tlist.appendItem(translateOrigin);

			canv.recalculateDimensions(cur_shape);
			
			lastBBox = cur_shape.getBBox();
		},
		mouseUp: function(opts) {
			var mode = canv.getMode();
			if(mode !== mode_id) return;
			
			if(opts.mouse_x == start_x && opts.mouse_y == start_y) {
				return {
					keep: false,
					element: cur_shape,
					started: false
				}
			}
			
			return {
				keep: true,
				element: cur_shape,
				started: false
			}
		}		
	}
});

