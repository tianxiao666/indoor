(function() {
	/*
	 *Author Liyao
	 *Date 2009-4-8
	 *Function JobareaLayer class extends JobLayer class
	 */
	
	//check the class name , it will be replaced when existed
	if ( window.JobareaLayer ) {
		alert( 'variable JobareaLayer has been used,it will be replaced with _JobareaLayer!' );
		window._JobareaLayer = window.JobareaLayer;
	}

	//constructor
	window.JobareaLayer = function( param ) {
		param = param instanceof Object ? param : {};
		
		param.colNum = param.colNum || 10;
		if ( param.extData ) {//处理居住地的情况
			param.data = ja.clone();
			for ( var p in param.extData ) {
				if ( /^[\d]{4}$/.test( p ) ) {
					param.data[p] = param.extData[p];
				}
			}
			//this.mainCity[2].subIds[0] = '9900';
			param.headTitle = param.cfg.langs.juzd;
		}
		else {
			//this.mainCity[2].subIds[0] = '0302';
			param.data = ja;
			param.headTitle = param.cfg.langs.gzdd;
		}
		
		param.selectedTitle = param.cfg.langs.didian;
		param.initTblFunc = this.initJobareaLayer;
		param.getSubValues = this.getJobareaIds;
		param.getSubColNum = function( len ) { return Math.ceil( Math.sqrt( len ) ); };		
		var tdWidth = 'e' == param.cfg.lang ? 'auto' : '75px';
		param.tdProps = { style : { width: tdWidth , paddingLeft: '3px'  } };
		if ( param.cityChoose ) {
			param.tdProps.style.width = '84px';
			param.subTdProps = { style : {} };
			param.subTdProps.style.width = '75px';
			if ( 'e' == param.cfg.lang ) {
				param.colNum = 6;
			}
		}

		param.emptyWords = param.cfg.langs.xzdq;

		JobLayer.apply( this , [param] );

	}.$extends( JobLayer );

	//share property & method
	var pt = JobareaLayer.prototype;

	//主要城市数据字典
	pt.mainCity = [
		{Chinese:'B',English:'B',subIds:['0100']},
		{Chinese:'C',English:'C',subIds:['2402','1902','0902','0600','0705']},
		{Chinese:'D',English:'D',subIds:['2303','0308']},
		{Chinese:'F',English:'F',subIds:['1102','0306']},
		{Chinese:'G',English:'G',subIds:['0302','2602']},
		{Chinese:'H',English:'H',subIds:['2202','0802','1502']},
		{Chinese:'J',English:'J',subIds:['1202','0807']},
		{Chinese:'K',English:'K',subIds:['2502']},
		{Chinese:'L',English:'L',subIds:['2702']},
		{Chinese:'N',English:'N',subIds:['0702','0803','1302','0709','1402']},
		{Chinese:'Q',English:'Q',subIds:['1203','1104']},
		{Chinese:'S',English:'S',subIds:['0200','0400','2302','1602','0703']},
		{Chinese:'T',English:'T',subIds:['0500','2102']},
		{Chinese:'W',English:'W',subIds:['1802','0704','0804']},
		{Chinese:'X',English:'X',subIds:['2002','1103']},
		{Chinese:'Y',English:'Y',subIds:['1204']},
		{Chinese:'Z',English:'Z',subIds:['1105','1702','0307','0305']}
	];
	pt.maxCityNumber=5;

	//所有省份数据字典	
	pt.allProvince=[
		{Chinese:'A-G',English:'A-G',subIds:['1500','3400','1100','2700','0300','1400','2600','3600']},
		{Chinese:'H-J',English:'H-J',subIds:['1000','1600','1700','2200','1800','1900','2400','0700','1300']},
		{Chinese:'L-S',English:'L-S',subIds:['2300','2800','2900','3200','1200','2100','2000','0900']},
		{Chinese:'T-Z',English:'T-Z',subIds:['3500','3000','3300','3100','2500','0800']}
	];

	pt.getJobareaIds = function( _value , isClearShenZhen ){//处理广东省深圳市特殊情况
		var _values = this.constructor.prototype.getSubValues.apply( this , [_value] );
		if ( '0300' == _value ) {
			if ( _values[1] != '0400' ) {
				_values.splice( 1 , 0 , '0400' );
			}
			if ( isClearShenZhen ) {
				_values.splice( 1 , 1 );
			}
		}
		return _values;
	}

	pt.initJobareaLayer = function() {
	
		//table
		this.createTbl();
	
		if ( !this.cityChoose ) {
			//title tr
			this.createTitleTr();

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

			//main city title
			var tr = this.tbl.insertRow( -1  );
			var td = document.createElement( 'td' );
			td.colSpan =  this.colNum;
			td.innerHTML = this.cfg.langs.zycs + '：';
			td.className = 'bigOrange gray';
			tr.appendChild( td );
		}
		
		var pareClsAdd = this.cityChoose && 'e' == this.cfg.lang ? ' quName' : '';
		//main city trs		
		var cityTr=this.tbl.insertRow(-1);
		var cityTd=document.createElement("td");	
		cityTd.colSpan=this.colNum;
		cityTr.appendChild(cityTd);	
		// 整个主要城市  都包含在 cityTable中
		var cityTable=document.createElement("table");			
		cityTable.style.width=pt.maxCityNumber*50*3+"px";		
		cityTd.appendChild(cityTable);
		for(var i=0;i<this.mainCity.length;i++){	
			
			// 一行排3列
			if((i % 3) ==0){			
			  var tmptr =cityTable.insertRow(-1);								
			}				
			this.mainCity[i]['pareName'] = this.mainCity[i][this.cfg.fullLang];
			this.mainCity[i]['pareClassName'] = 'cityOrange' + pareClsAdd;			
			this.createCitySelectTd(this.mainCity[i],tmptr,pt.maxCityNumber);			
		}
		//all province title
		var tr = this.tbl.insertRow( -1  );
		var td = document.createElement( 'td' );
		
		td.colSpan =  this.colNum;
		td.innerHTML = this.cfg.langs.sysf + '：';
		td.className = 'bigOrange gray';
		tr.appendChild( td );		
		//all province trs		
		for ( var i = 0 ; i < this.allProvince.length ; i++ ) {
			this.allProvince[i]['pareName'] = this.allProvince[i][this.cfg.fullLang] + '';
			this.allProvince[i]['pareClassName'] = 'cityOrange' + pareClsAdd;
			this.createSelectAreaTr( this.allProvince[i] );
		}
			
		if ( !this.cityChoose ) {
			//bottom line
			this.createBottomLine();
		}	
	}
	pt.createAreaTd = function( _value , isLast , checked ) {
		//新添加的城市特别显示 
		//var newCity=['2602','0807','2702','0709','1402','1104','2102','0804','1103','1204','1105'];
		var newCity=['0302'];
		var hotCity=['0100','0200','0302','0400'];
		var isNewCity=false;
		var isHotCity=false;
		for(var i=0;i<newCity.length;i++){
			if(_value==newCity[i]){
				isNewCity=true;
				break;
			}
		}
		for(var i=0;i<hotCity.length;i++){
			if(_value==hotCity[i]){
				isHotCity=true;
				break;
			}
		}
		var td = document.createElement( 'td' );
		td.style.cursor = 'pointer';	
		if(isNewCity){
			td.style.color='#0075E8';
		}
		if(isHotCity){
			td.style.fontWeight='bold';
		}
		td.thisObj = this;
		td._value = _value;
		td.isLast = isLast != undefined ? isLast : false;		
        var bAddCheckBox = false;		
		if ( this.isMulty && ( td.isLast || !this.getSubValues( _value ).length ) ) {			
            var bAddCheckBox = true;			         		
			td.innerHTML = '<input style="display:none" type="checkbox" name="' + this.namePrefix + _value + '" onclick="this.checked=!this.checked;" ' + ( checked ? 'checked="checked"' : '' ) + ' />';
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
	pt.createCitySelectTd=function(data,tr,maxCityNumber){
		if(data){
			if ( data['subIds'] != undefined ) {			
			 var td = document.createElement( 'td' );			 
			 td.innerHTML=data['pareName'];
			 if ( data['pareClassName'] ) {
					td.className = data['pareClassName'];
			 }
			 tr.appendChild(td);
			 var _values = data['subIds'];
			}			
		   for ( var k = 0 ; k <maxCityNumber ; k++ ) {	
				if(k<_values.length){
					tr.appendChild( this.createAreaTd( _values[k] ) );
				}else{
					var tmptd=document.createElement("td");
					tmptd.innerHTML="&nbsp;";
					tr.appendChild(tmptd);
				}
			}		
						
		}	
	}
	pt.createSelectAreaTr = function( data ) {
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
				tr.appendChild( this.createAreaTd( _values[k] ) );
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

})();
