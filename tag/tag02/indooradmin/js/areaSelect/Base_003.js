(function() {
	/*
	 *Author Liyao
	 *Date 2009-4-8
	 *Function JobLayer class extends ExtZzLayer class
	 */
	
	//check the class name , it will be replaced when existed
	if ( window.JobLayer ) {
		alert( 'variable JobLayer has been used,it will be replaced with _JobLayer!' );
		window._JobLayer = window.JobLayer;
	}

	//constructor
	window.JobLayer = function( param ) {

		param = typeof param == 'object' ? param : {};

		this.cfg = param.cfg || {};
		if ( typeof this.cfg.langs != 'object' ) {
			this.cfg.langs = {};
		}
		if ( typeof this.cfg.url != 'object' ) {
			this.cfg.url = {};
		}

		this.cityChoose = param.cityChoose || '';//城市选择特殊处理
		
		if ( this.cityChoose ) {//城市选择
			Base.apply( this , [param] );
			this.cityChoose.cityChooseDiv = this._( this.cityChoose.cityChooseDiv );
			param.isMulty = false;
		}
		else {
			//extends ExtZzLayer
			param.divProps = param.divProps || {};
			if ( !param.divProps.className ) {
				param.divProps.className = 'jobLayer';
				if ( 'e' == this.cfg.lang && ( this.constructor == window.FuntypeLayer || this.constructor == window.IndtypeLayer ) ) {
					param.divProps.className += ' jobLayer_e';
				}
			}
			
			param.openType = param.openType || 2;
			param.filterParam = {};
			param.createIfr = false;
			if ( param.subBeforeOpen == undefined ) {
				param.subBeforeOpen = function() {
					if ( this.isMulty && this._valueNodes && this._valueNodes[0] ) {
						this.initSelected( this._valueNodes[0].value );
					}
				}
			}
			ExtZzLayer.apply( this , [param] );
		}
		
		//private property
		if ( param.data ) {
			this.data = param.data;
		}
		this.isMulty = param.isMulty != undefined ? param.isMulty : true;
		this.genNamePrefix( param.namePrefix );
		this.colNum = typeof param.colNum == 'number' ? param.colNum : 4;
		this.maxSelectNum = typeof param.maxSelectNum == 'number' ? param.maxSelectNum : 5;//仅当this.isMulty为true时有效
		if ( typeof param.initTblFunc == 'function' ) {
			this.initTblFunc = param.initTblFunc;
		}

		if ( typeof param.getTextFunc == 'function' ) {
			this.getTextFunc = param.getTextFunc;
		}

		if ( typeof param.getSubValues == 'function' ) {
			this.getSubValues = param.getSubValues;
		}

		if ( typeof param.getPareValue == 'function' ) {
			this.getPareValue = param.getPareValue;
		}
		
		this.isHasNolimit = param.isHasNolimit != undefined ? param.isHasNolimit : true;

		this.containPare = param.containPare != undefined ? param.containPare : true;

		this.getSubColNum = typeof param.getSubColNum == 'function' ? param.getSubColNum : function() { return 1; };

		if ( typeof param.confirmFunc == 'function' ) {
			this.confirmFunc = param.confirmFunc;
		}
		
		this.setOpenNodes( param.openNodes );
		this.setValueNodes( param._valueNodes );
		//alert(param._textNodes);
		this.setTextNodes( param._textNodes );

		this.tdProps = {
			onmouseover : function() { this.className = 'zz_51High';  } ,
			onmouseout : function() { this.className = 'zz_51Lower' + ( 'bgGray' == this.parentNode.className ? ' ' + this.parentNode.className : '' ); } ,
			style : { paddingRight: '15px' } 
		}

		this.subTdProps = {
			onmouseover : function() { this.className = 'zz_51High'; } ,
			onmouseout : function() { this.className = 'zz_51LowerYellow'; }
		}

		if ( typeof param.tdProps == 'object' ) {
			for ( var prop in param.tdProps ) {
				this.tdProps[prop] = param.tdProps[prop];
			}
		}
		
		this.subTblProps = param.subTblProps || { className: ( this.cityChoose ? this.cityChoose.cityChooseDiv.className : param.divProps.className ) + ' subLayer' };//城市选择

		if ( typeof param.subTdProps == 'object' ) {
			for ( var prop in param.subTdProps ) {
				this.subTdProps[prop] = param.subTdProps[prop];
			}
		}

		if ( this.cityChoose ) {//城市选择
			this.tdProps.onmouseover = this.tdProps.onmouseout = this.subTdProps.onmouseover = this.subTdProps.onmouseout = function() {};
		}

		this.emptyWords = param.emptyWords || this.cfg.langs.xj_xg || '选择/修改';

		this.headTitle = param.headTitle || '';
		this.selectedTitle = param.selectedTitle || '';
		this.headTitleWords = param.headTitleWords ||  ( this.cfg.langs.qingxj || '请选择' ) + this.headTitle + ( this.isMulty ? '（' + this.cfg.langs.nzdnxj  + this.maxSelectNum + this.cfg.langs.xiang + '）' : '' );
		if ( typeof param.titleTdProps == 'object' ) {
			this.titleTdProps = param.titleTdProps;
		}
		if ( typeof param.titleTrProps == 'object' ) {
			this.titleTrProps = param.titleTrProps;
		}
		
		this.initTblFunc();
		
		if ( this.cityChoose ) {//城市选择
			this.cityChoose.cityChooseDiv.appendChild( this.tbl );
		}
		else {
			this.setContent( this.tbl );
			this.shouldInit = undefined == param.shouldInit ? true : param.shouldInit;
			if ( this.shouldInit && param.selValues ) {
				if ( this.isMulty ) {
					this.initSelected( param.selValues );
					this.onFunc();
				}
				else {
					this.onFunc( param.selValues );
				}
			}

			this.setDragNode( this.tbl.firstChild.firstChild );
		}

	}.$extends( ExtZzLayer );

	//share property & method
	var pt = JobLayer.prototype;

	pt.namePrefixs = {};

	pt.genNamePrefix = function( namePrefix ) {
		namePrefix = namePrefix || 'JL' + parseInt( Math.random() * 10000 );
		while ( this.namePrefixs[namePrefix] ) {
			namePrefix = 'JL' + parseInt( Math.random() * 10000 );
		}
		this.namePrefix = namePrefix;
		this.namePrefixs[namePrefix] = true;
	}

	pt.initTblFunc = function() {
		if ( this.data ) {
			//table
			this.createTbl();

			//title tr
			this.createTitleTr( { tdProps : this.titleTdProps , trProps : this.titleTrProps } );
			
			//selected tr
			if ( this.isMulty ) {
				this.createSelectedTr();
				//seprator line
				var tr = this.tbl.insertRow( -1  );
				var td = document.createElement( 'td' );
				td.colSpan =  this.colNum;
				td.className = 'jlSeprator';
				tr.appendChild( td );
			}

			//select trs
			var _values = [];
			var count = 0;
			var isCheck = false;
			var isDouble = false;
			for ( var _value in this.data ) {
				if ( 'string' == typeof this.data[_value] ) {
					if ( !isCheck ) {
						isDouble = this.getSubValues( _value ).length ? true : false;
						isCheck = true;
					}
					if ( !isDouble || isDouble && '00' == _value.substr( 2 ) ) {
						_values.push( _value );
					}
					if ( _values.length == this.colNum ) {
						this.createSelectTr( _values );
						_values = [];
					}
				}
			}
			if ( _values.length ) {
				this.createSelectTr( _values );
			}

			//bottom line
			this.createBottomLine();
			
		}
	}

	pt.getTextFunc = function( _value ) {
		var _text = '';
		if ( this.data && this.data[_value] ) {
			_text = this.data[_value];
		}
		return _text;
	}

	pt.getSubValues = function( _value ) {
		return this.getSubCodes( this.data , _value );
	}

	pt.getOprNodes = function( nodes ) {
		if ( !( nodes instanceof Array ) ) {
			nodes = ( nodes = this._( nodes ) ) && nodes.nodeName ? [nodes] : [];
		}
		return nodes;
	}

	pt.setValueNodes = function( nodes ) {
		this._valueNodes = this.getOprNodes( nodes );
	}

	pt.setTextNodes = function( nodes ) {
		var tmp = this.getOprNodes( nodes );
		this._textNodes = this.getOprNodes( nodes );
	}

	pt.setOpenNodes = function( nodes ) {
		if ( this.openNodes ) {
			for ( var i = 0 ; i < this.openNodes.length ; i++ ) {
				if ( this.openNodes[i].jobLayerObj ) {
					this.removeEvent( this.openNodes[i] , this.openNodes[i].jobLayerObj.openNodeClk );
					this.openNodes[i].jobLayerObj = null;
				}
			}
		}
		this.openNodes = [];
		this.pushOpenNodes( nodes );
	}

	pt.pushOpenNodes = function( openNodes ) {
		openNodes = this.getOprNodes( openNodes );
		for ( var i = 0 ; i < openNodes.length ; i++ ) {
			if ( openNodes[i].jobLayerObj ) {
				this.removeEvent( openNodes[i] , openNodes[i].jobLayerObj.openNodeClk );
				openNodes[i].jobLayerObj = null;
			}
			this.bindEvent( openNodes[i] , 'click' , this.openNodeClk );
			openNodes[i].jobLayerObj = this;
			openNodes[i].style.cursor = 'pointer';
			this.openNodes.push( openNodes[i] );
		}
	}

	pt.openNodeClk = function( event ) {
		event = event || window.event;
		var openNode = event.srcElement || event.target;
		openNode.jobLayerObj.open( event );
	}

	pt.createTbl = function() {
		var tbl = document.createElement( 'table' );
		tbl.style.fontSize = '12px';
		tbl.border = 0;
		tbl.cellPadding = 0;
		tbl.cellSpacing = 0;
		this.tbl = tbl;
	}

	pt.createTitleTr = function( param ) {
		param = typeof param == 'object' ? param : {};
		var tr = this.tbl.insertRow( -1  );
		tr.className = 'title';
		var ccWords , ccType , buxianSpn = '';
		if ( this.isMulty ) {
			ccWords = this.cfg.langs.queren || '确认';
			ccType = 'confirm';
		}
		else {
			ccWords = this.cfg.langs.guanbi || '关闭';
			ccType = 'close';
			if ( this.isHasNolimit ) {
				this.emptyWords = this.cfg.langs.buxian || '不限';
				buxianSpn = '<span ccType="buxian">[' + ( this.cfg.langs.buxian || '不限' ) + ']</span>&nbsp;&nbsp;';
			}
			else {
				buxianSpn = '';
			}
		}
		var tdProps = { 
			innerHTML: '<img src="' + this.cfg.url.image_search + '/title_layer_yd.gif" /><span>&nbsp;' + ( this.headTitleWords || '' ) + '</span><span  class="ccType">' + buxianSpn + '<span ccType="' + ccType + '">[' + ccWords + ']</span><span>'
		};
		var td = document.createElement( 'td' );
		td.colSpan = this.colNum;
		this.setProps( td , tdProps );
		this.setProps( td , param.tdProps );
		this.setProps( td , param.trProps );
		tr.appendChild( td );
		var spans = td.getElementsByTagName( 'span' );
		var thisObj = this;
		for ( var i = 0 ; i < spans.length ; i++ ) {
			var ccType = spans[i].getAttribute( 'ccType' );
			if ( ccType ) {
				spans[i].style.cursor = 'pointer';
				if ( 'confirm' == ccType || 'close' == ccType ) {
					if ( 'confirm' == ccType ) {
						this.attEvt( spans[i] , 'click' , function() { thisObj.onFunc(); } );
					}
					else {
						this.attEvt( spans[i] , 'click' , function() { thisObj.close(); } );
					}			
				}
				else if ( 'buxian' == ccType ) {
					this.attEvt( spans[i] , 'click' , function() { thisObj.onFunc( [] ); } );
				}
			}
		}
	}

	pt.createSelectedTr = function( param ) {
		param = typeof param == 'object' ? param : {};
		var tr = this.tbl.insertRow( -1  );
		tr.isSelected = true;
		var td = document.createElement( 'td' );
		td.innerHTML = ( this.cfg.langs.yixuan || '已选' ) + ( this.selectedTitle || '条件' ) + '：';
		tr.appendChild( td );
		td.className = 'bigOrange';
		this.setProps( td , param.tdProps );
		this.setProps( tr , param.trProps );
		for ( var i = 1 ; i < this.colNum ; i++ ) {
			tr.appendChild( document.createElement( 'td' ) );
		}
	}

	pt.createSelectTr = function( data ) {
		if ( data ) {
			var tr = this.tbl.insertRow( -1  );
			if ( data['trClassName'] ) {
				tr.className = data['trClassName'];
			}
			if ( data['subIds'] != undefined ) {
				var td = document.createElement( 'td' );
				td.rowSpan = Math.ceil( data['subIds'].length / ( this.colNum - 1 ) );
				td.innerHTML = data['pareName'];
				if ( data['pareClassName'] ) {
					td.className = data['pareClassName'];
				}
				tr.appendChild( td );
				var _values = data['subIds'];
				var hasLeftPare = true;
			}
			else {
				var _values = data;
				var hasLeftPare = false;
			} 
			
			for ( var k = 0 ; k < _values.length ; k++ ) {
				if ( ( !hasLeftPare && 0 == k % this.colNum ) || ( hasLeftPare && k != 0 && 0 == k % ( this.colNum - 1 ) ) ) {
					tr = this.tbl.insertRow( -1  );
					if ( data['trClassName'] ) {
						tr.className = data['trClassName'];
					}
				}
				tr.appendChild( this.createTd( _values[k] ) );
			}
			var colNum = hasLeftPare ? this.colNum - 1 : this.colNum;
			var modNum = k % colNum;
			if ( modNum > 0 ) {
				var td = document.createElement( 'td' );
				td.colSpan = colNum - modNum;
				tr.appendChild( td );
			}
		}
	}

	pt.createBottomLine = function( trProps ) {
		var tr = this.tbl.insertRow( -1  );
		var td = document.createElement( 'td' );
		td.colSpan =  this.colNum;
		tr.appendChild( td );
		trProps = trProps || {};
		trProps.className = trProps.className || 'bottomLine';
		this.setProps( tr , trProps );
	}

	pt.initSelected = function( _values ) {
		this.clearSelected();
		if ( typeof _values == 'string' ) {
			_values = _values.split( ',' );
		}
		if ( _values instanceof Array ) {
			for ( var i = 0 ; i < _values.length ; i++ ) {
				if ( this.getTextFunc( _values[i] ) ) {
					this.doChecked( _values[i] );
				}
			}
		}
	}

	pt.clearSelected = function( _values ) {
		_values = _values != undefined ? _values : this.getSelectedValues();
		for ( var i = 0 ; i < _values.length ; i++ ) {
			this.cancelChecked( _values[i] );
		}
	}

	pt.getSelectedValues = function() {
		var _values = [];
		var trs = this.getSelectedTrs();
		for ( var i = 0 ; i < trs.length ; i++ ) {
			var chxs = trs[i].getElementsByTagName( 'input' );
			for ( var j = 0 ; j < chxs.length ; j++ ) {
				_values.push( chxs[j].parentNode._value );
			}
		}
		return _values;
	}

	pt.getSelectedTrs = function() {
		var trs = [];
		var objs = this.div.firstChild.getElementsByTagName( 'tr' );
		for ( var i = 0 ; i < objs.length ; i++ ) {
			if ( objs[i].isSelected ) {
				trs.push( objs[i] );
			}
		}
		return trs;
	}

	pt.doChecked = function( _value , td ) {
		this.clearSelected( this.getSubValues( _value , true ) );//第二个参数为处理广东省深圳市特殊情况而加的 2009-4-29
		this.cancelChecked( this.getPareValue( _value ) );
		 
		if ( this.getSelectedValues().length >= this.maxSelectNum ) {
			if(typeof(td)!="undefined"){
				alert( this.cfg.langs.nzdnxj + this.maxSelectNum + this.cfg.langs.xiang );
				td.firstChild.checked = false;
				return false;
			}
		}

		var chxs = document.getElementsByName( this.namePrefix + _value );
		for ( var i = 0 ; i < chxs.length ; i++ ) {
			chxs[i].checked = true;
		}

		var trs = this.getSelectedTrs();
		var lastTr = trs[trs.length - 1];
		var chxsNum = lastTr.getElementsByTagName( 'input' ).length;
		var td = this.createTd( _value , true );
		if ( this.colNum - 1 == chxsNum ) {//行满
			trs[0].firstChild.rowSpan += 1;
			var newTr = lastTr.parentNode.insertRow( lastTr.rowIndex + 1 );
			newTr.isSelected = true;
			newTr.appendChild( td );
			for ( var i = 2 ; i < this.colNum ; i++ ) {
				newTr.appendChild( document.createElement( 'td' ) );
			}
		}
		else {
			lastTr.replaceChild( td  , lastTr.childNodes[1 == trs.length ? chxsNum + 1 : chxsNum] );
		}
		td.firstChild.checked = true;
	}

	pt.cancelChecked = function( _value ) {
		if ( _value ) {
			var trs = this.getSelectedTrs();
			var lastTr = trs[trs.length - 1];
			var chxs = document.getElementsByName( this.namePrefix + _value );
			for ( var i = chxs.length - 1 ; i >= 0 ; i-- ) {//!!!必须逆向
				var td = chxs[i].parentNode;
				var tr = td.parentNode;
				if ( tr.isSelected ) {
					tr.removeChild( td );
					while ( tr != lastTr ) {
						var nextTr = this.tbl.rows[tr.rowIndex + 1];
						tr.appendChild( nextTr.firstChild );
						tr.lastChild.firstChild.checked = true;
						tr = nextTr;
					}
					if ( tr != trs[0] && 0 == tr.getElementsByTagName( 'input' ).length ) {
						tr.parentNode.removeChild( tr );
						trs[0].firstChild.rowSpan -= 1;
					}
					else {
						tr.appendChild( document.createElement( 'td' ) );
					}
				}
				else {
					chxs[i].checked = false;
				}
			}
		}
	}

	pt.createTd = function( _value , isLast , checked ) {
		var td = document.createElement( 'td' );
		td.style.cursor = 'pointer';
		td.thisObj = this;
		td._value = _value;
		td.isLast = isLast != undefined ? isLast : false;

        var bAddCheckBox = false;
		if ( this.isMulty && ( td.isLast || !this.getSubValues( _value ).length ) ) {
            var bAddCheckBox = true;
			td.innerHTML = '<input type="checkbox" name="' + this.namePrefix + _value + '" onclick="this.checked=!this.checked;" ' + ( checked ? 'checked="checked"' : '' ) + ' />';
		}
		td.onclick = this.tdClk;
		if ( this.cityChoose ) {
			var a = document.createElement( 'a' );
			a.className = 'blue';
            var sTextValue = this.getTextFunc( _value );

			a.innerHTML = sTextValue;

			if ( this.getSubValues( _value ).length && !td.isLast ) {
				a.href = 'javascript: void(0);';
				a.onclick = 'return false;';
			}
			else {
				this.cityChoose.jobarea = _value;
				a.href = this.getSearchResultHref( this.cityChoose );
			}

			td.appendChild( a );



            if (('c' == this.cfg.lang) && ('undefined' != typeof this.tdProps.style.width)) {
                var iTextValueLength = sTextValue.getLen();
                if (iTextValueLength > 8 && iTextValueLength < 12) {
                    if (bAddCheckBox) {
                        if (parseInt(this.subTdProps.style.width) < 85) {
                            this.subTdProps.style.defaultWidth = this.subTdProps.style.width;
                            this.subTdProps.style.width        = '85px';
                        }
                    }
                }
                else if (iTextValueLength > 12) {
                    if (bAddCheckBox) {
                        if (parseInt(this.subTdProps.style.width) < 120) {
                            this.subTdProps.style.defaultWidth = this.subTdProps.style.width;
                            this.subTdProps.style.width        = '120px';
                        }
                    }
                    else {
                        if (parseInt(this.tdProps.style.width) < 105) {
                            this.subTdProps.style.defaultWidth = this.subTdProps.style.width;
                            this.subTdProps.style.width        = '105px';
                        }
                    }
                }
                else {
                    if ('undefined' != typeof this.subTdProps.style.defaultWidth) {
                        this.subTdProps.style.width = this.subTdProps.style.defaultWidth;
                        delete this.subTdProps.style.defaultWidth;
                    }
                }
            }
		}
		else {
            var sTextValue = this.getTextFunc( _value );

            if (('c' == this.cfg.lang) && ('undefined' != typeof this.tdProps.style.width)) {
                var iTextValueLength = sTextValue.getLen();
                if (iTextValueLength > 8 && iTextValueLength < 12) {
                    if (bAddCheckBox) {
                        if (parseInt(this.tdProps.style.width) < 85) {
                            this.tdProps.style.defaultWidth = this.tdProps.style.width;
                            this.tdProps.style.width        = '85px';
                        }
                    }
                }
                else if (iTextValueLength > 12) {
                    if (bAddCheckBox) {
                        if (parseInt(this.tdProps.style.width) < 120) {
                            this.tdProps.style.defaultWidth = this.tdProps.style.width;
                            this.tdProps.style.width        = '120px';
                        }
                    }
                    else {
                        if (parseInt(this.tdProps.style.width) < 105) {
                            this.tdProps.style.defaultWidth = this.tdProps.style.width;
                            this.tdProps.style.width        = '105px';
                        }
                    }
                }
                else {
                    if ('undefined' != typeof this.tdProps.style.defaultWidth) {
                        this.tdProps.style.width = this.tdProps.style.defaultWidth;
                        delete this.tdProps.style.defaultWidth;
                    }
                }
            }

            td.appendChild(document.createTextNode(sTextValue));
		}
		this.setProps( td , this.tdProps );
		return td;
	}

	pt.openSubLayer = function( td , event ) {
		if ( !td.subLayer ) {
			td.thisObj.createSubLayer( td );
		}
		if ( td.subLayer ) {
			td.subLayer.open( event );
		}
	}

	pt.getChecked = function( _value ) {
		var checked = false;
		var chxs = document.getElementsByName( this.namePrefix +  _value );
		if ( chxs[0] && chxs[0].checked ) {
			checked = true;
		}
		return checked;
	}

	pt.createSubLayer = function( td ) {
		var subValues;
		if ( td._value && ( subValues = this.getSubValues( td._value ) ).length ) {
			var param = {
				openType : 1 ,
				createIfr : false ,
				appendNode1 : td ,
				MOutCloseNode : td
			};
			var subLayer = new ExtZzLayer( param );

			var tbl = document.createElement( 'table' );
			tbl.border = 0;
			tbl.cellPadding = 0;
			tbl.cellSpacing = 0;
			
			var subColNum = this.getSubColNum( subValues.length );

			if ( this.containPare ) {
				var tr = tbl.insertRow( -1  );
				var newTd = this.createTd( td._value , true , this.getChecked( td._value ) );
				newTd.colSpan = subColNum;
				tr.appendChild( newTd );
				newTd.style.fontWeight = 'bold';
				newTd.subLayer = subLayer;
				this.setProps( newTd , this.subTdProps );
			}
			
			var tr;
			for ( var i = 0 ; i < subValues.length ; i++ ) {
				if ( 0 == i % subColNum ) {
					tr = tbl.insertRow( -1 );
				}
				newTd = this.createTd( subValues[i] , true , this.getChecked( subValues[i] ) );
				tr.appendChild( newTd );
				newTd.subLayer = subLayer;
				this.setProps( newTd , this.subTdProps );

			}
			var modNum = i % subColNum;
			if ( modNum > 0 ) {
				var newTd = document.createElement( 'td' );
				newTd.colSpan = subColNum - modNum;
				tr.appendChild( newTd );
			}
			this.setProps( tbl , this.subTblProps );

			subLayer.setContent( tbl );
			td.subLayer = subLayer;
		}
	}

	pt.tdClk = function( event ) {
		if ( !this.isLast && this.thisObj.getSubValues( this._value ).length ) {
			this.thisObj.openSubLayer( this , event );
		}
		else {
			if ( !this.thisObj.cityChoose ) {
				if ( this.thisObj.isMulty ) {
					var chx = this.firstChild;
					chx.checked = !chx.checked;
					if ( chx.checked ) {
						this.thisObj.doChecked( this._value , this );
					}
					else {
						this.thisObj.cancelChecked( this._value );
					}
				}
				else {
					if ( this.subLayer ) {
						this.subLayer.close();
					}
					if ( this.thisObj ) {
						//this.thisObj.close( {} , event );
						this.thisObj.onFunc( [this._value] );
					}
				}
			}
		}
	}

	pt.getPareValue = function( _value ) {
		var pareValue = '';
		var suffixValue = _value.substr( 2 );
		if ( suffixValue && '00' != suffixValue ) {
			pareValue = _value.substr( 0 , 2 ) + '00';
		}
		return pareValue;
	}

	pt.getTextsByValues = function( _values ) {
		var _texts = [];
		for ( var i = 0 ; i < _values.length ; i++ ) {
			_texts.push( this.getTextFunc( _values[i] ) );
		}
		return _texts;
	}

	pt.confirmFunc = function( _values ) {
		if ( this._valueNodes ) {
			var _valuesStr = _values.join( ',' );
			for ( var i = 0 ; i < this._valueNodes.length ; i++ ) {
				this._valueNodes[i].value = _valuesStr;
			}
		}
		if ( this._textNodes ) {
			//alert('ssssss');
			
			var _textsStr = this.getTextsByValues( _values ).join( '+' ) || this.emptyWords || '选择/修改';
			//var _textsStr = this.getTextsByValues( ["0100"] ).join( '+' ) || this.emptyWords || '选择/修改';
			var pixelWidth = this.cfg.pixelWidth || ( 'e' == this.cfg.lang ? 15/130 : 12/94 );
			var pixelWidth = 13/95;
			for ( var i = 0 ; i < this._textNodes.length ; i++ ) {
				//alert(_values);
				//alert(this._textNodes.length);
				this.showAutoAdaptText( this._textNodes[i] , _textsStr , pixelWidth );
				this.saveDataFunc(_values);
			}
		}
		this.close();
	}
	

	pt.onFunc = function( _values ) {
		if ( this.isMulty ) {
			_values = this.getSelectedValues();
		}
		this.confirmFunc( _values );
	}
	
	pt.saveDataFunc = function(values){
		var tmpStr = values + '';
		var provStr = tmpStr.substr(0,2)+ '00';
		var cityValue = this.getTextsByValues( values ).join( '+' );
		if(provStr != tmpStr){
			var provValue = [];
			provValue.push(provStr);
			var textValue = this.getTextsByValues( provValue ).join( '+' );
			$('#PROV').val(textValue);
			$('#PROV_SHOW').html(textValue);
			$('#CITY').val(cityValue);
			$('#CITY_SHOW').html(cityValue);
		}else{
			$('#PROV').val('');
			$('#PROV_SHOW').html('');
			if(this.checkGroupMembers(provStr)){
				$('#CITY').val('');
				$('#CITY_SHOW').html('');
			}else{
				$('#CITY').val(cityValue);
				$('#CITY_SHOW').html(cityValue);
				if(cityValue == "深圳"){
					$('#PROV').val('广东省');
					$('#PROV_SHOW').html('广东省');
				}
			}
		}
	}
	
	pt.checkGroupMembers = function(valStr){
		valStr = parseInt(valStr)+2;
		valStr +='';
		var cityValue = [];
		cityValue.push(valStr);
		var textValue = this.getTextsByValues( cityValue ).join( '+' );
		if(textValue){
			return true;
		}else{
			return false;
		}
	}

})();