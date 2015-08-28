(function($){
$.extend({
		validate : {
				Require : /.+/,
				Email : /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
				Phone : /^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/,
				Mobile : /^((\(\d{2,3}\))|(\d{3}\-))?1[35]\d{9}$/,
				Url : /^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/,
				Currency : /^\d+(\.\d+)?$/,
				Number : /^\d+$/,
				Zip : /^\d{6}$/,
				QQ : /^[1-9]\d{4,9}$/,
				Int : /^[-\+]?\d+$/,
				Double : /^[-\+]?\d+(\.\d+)?$/,
				AbsDouble: /^\d+(\.\d+)?$/,
				AbsFen: function(n){
						return /^\d+(\.\d+)?$/.test(n) && n != '0';
					},
				English : /^[A-Za-z]+$/,
				Chinese :  /^[\u4E00-\u9FA5]+$/,
				Username : /^[a-z]\w{3,}$/i,
				UnSafe : /^(([A-Z]*|[a-z]*|\d*|[-_\~!@#\$%\^&\*\.\(\)\[\]\{\}<>\?\\\/\'\"]*)|.{0,5})$|\s/,
				IdCard : function(number){
										var date, Ai;
										var verify = "10x98765432";
										var Wi = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
										var area = ['','','','','','','','','','','',"1","1","1","1","1",'','','','','',"1","1","1",'','','','','','','',"1","1","1","1","1","1","1",'','','',"1","1","1","1","1","1",'','','',"1","1","1","1","1",'','','','','','',"1","1","1","1","1",'','','','','',"1",'','','','','','','','','',"1","1",'','','','','','','','',"1"];
										var re = number.match(/^(\d{2})\d{4}(((\d{2})(\d{2})(\d{2})(\d{3}))|((\d{4})(\d{2})(\d{2})(\d{3}[x\d])))$/i);
										if(re == null) return false;
										if(re[1] >= area.length || area[re[1]] == "") return false;
										if(re[2].length == 12){
											Ai = number.substr(0, 17);
											date = [re[9], re[10], re[11]].join("-");
										}
										else{
											Ai = number.substr(0, 6) + "19" + number.substr(6);
											date = ["19" + re[4], re[5], re[6]].join("-");
										}
										if(!this.Date(date, "ymd")) return false;
										var sum = 0;
										for(var i = 0;i<=16;i++){
											sum += Ai.charAt(i) * Wi[i];
										}
										Ai +=  verify.charAt(sum%11);
										return (number.length ==15 || number.length == 18 && number == Ai);
									},
				IsSafe : function(str){return !this.UnSafe.test(str);},
				SafeString : this.IsSafe,
				Filter : function(input, filter){
										return new RegExp("^.+\.(?=EXT)(EXT)$".replace(/EXT/g, filter.split(/\s*,\s*/).join("|")), "gi").test(input);
									},
				Limit : function(v,params){
									min = typeof params.min !== "undefined" && parseFloat(params.min) || 0;
									max = typeof params.max !== "undefined" && parseFloat(params.max) || Number.MAX_VALUE;
									v=parseFloat(v);
									return (min <= v && v <= max);
								},
				LimitInt : function(v,params){
									min = typeof params.min !== "undefined" && parseInt(params.min) || 0;
									max = typeof params.max !== "undefined" && parseInt(params.max) || Number.MAX_VALUE;
									v=parseFloat(v);
									return (min <= v && v <= max);
								},
				Range : function( v , params ) {
									min = typeof params.min !== "undefined" && parseFloat(params.min) || 0;
									max = typeof params.max !== "undefined" && parseFloat(params.max) || Number.MAX_VALUE;
									return (min < v && v < max);
								},
				RangeInt : function( v , params ) {
									min = typeof params.min !== "undefined" && parseInt(params.min) || 0;
									max = typeof params.max !== "undefined" && parseInt(params.max) || Number.MAX_VALUE;
									return (min < v && v < max);
								},
				LimitLen : function(v,params) {
									min = typeof params.min !== "undefined" && parseInt(params.min) || 0;
									max = typeof params.max !== "undefined" && parseInt(params.max) || Number.MAX_VALUE;
									len = v.replace(/[^\x00-\xff]/g,"aa").length;
									return (min <= len && len <= max);
								},
				Date : function(op, formatString){
								formatString = formatString || "ymd";
								var m, year, month, day;
								switch(formatString){
									case "ymd" :
										m = op.match(new RegExp("^((\\d{4})|(\\d{2}))([-./])(\\d{1,2})\\4(\\d{1,2})$"));
										if(m == null ) return false;
										day = m[6];
										month = m[5]*1;
										year =  (m[2].length == 4) ? m[2] : GetFullYear(parseInt(m[3], 10));
										break;
									case "dmy" :
										m = op.match(new RegExp("^(\\d{1,2})([-./])(\\d{1,2})\\2((\\d{4})|(\\d{2}))$"));
										if( m == null ) return false;
										day = m[1];
										month = m[3]*1;
										year = (m[5].length == 4) ? m[5] : GetFullYear(parseInt(m[6], 10));
										break;
									default :
										break;
								}
								if(!parseInt(month)) return false;
								month = month==0 ?12:month;
								var date = new Date(year, month-1, day);
						        return (typeof(date) == "object" && year == date.getFullYear() && month == (date.getMonth()+1) && day == date.getDate());
								function GetFullYear(y){return ((y<30 ? "20" : "19") + y)|0;}
							},
				Repeat : function( v , to ) {
									return ( v == $("[name=" + to + "]").get(0).value);
								},
				Compare : function(v,params){
										operator = typeof params.op !== "undefined" && params.op || "NotEq";
										vv = typeof params.to !== "undefined" && params.to || "";
										switch (operator) {
											case "NotEq":
												return (v != vv);
											case "Big":
												return (v  > vv);
											case "BigEq":
												return (v >= vv);
											case "Less":
												return (v  < vv);
											case "LessEq":
												return (v <= vv);
											default:
												return (v == vv);            
										}
									},
				Custom : function(v, reg){
									return new RegExp(reg,"g").test(v);
								},
				Group : function(name, params){
								var len = $("input[name='" + name + "']:checked").length;
								min = typeof params.min !== "undefined" && parseInt(params.min) || 1;
								max = typeof params.max !== "undefined" && parseInt(params.max) || len;
								return min <= len && len <= max;
							},
				ErrorItem : [document.forms[0]],
				ErrorMessage : ["\u4EE5\u4E0B\u539F\u56E0\u5BFC\u81F4\u63D0\u4EA4\u5931\u8D25\uFF1A\t\t\t\t"],
				OldMessage : [""],
				check : function(jqo, mode){
					var obj = jqo;
					var count = obj.length;
					this.ErrorMessage.length = 1;
					this.ErrorItem.length = 1;
					this.OldMessage.length = 1;
					this.ErrorItem[0] = obj;
					for(var i=0;i<count;i++){
						this.checkone( obj.get(i) , i );
					}
					if(this.ErrorMessage.length > 1){
						mode = mode || 1;
						var errCount = this.ErrorMessage.length;
						switch(mode){
						case 2 :
							for(var i=1;i<errCount;i++)
								this.ErrorItem[i].style.color = "red";
						case 1 :
							alert(this.ErrorMessage.join("\n"));
							this.ErrorItem[1].focus();
							break;
						case 3 :
							for(var i=1;i<errCount;i++){
								this.tips( this.ErrorItem[i].name , this.ErrorMessage[i].replace(/^\d+:/,"") );
							}
							try{
								this.ErrorItem[1].focus();
							}catch(e){}
							break;
						default :
							alert(this.ErrorMessage.join("\n"));
							break;
						}
						return false;
					}
					return true;
				},
				checkone: function(o,i){
					var _dataType = $(o).attr('datatype');
					if(typeof(_dataType) === "object" || typeof(_dataType) === "undefined")  return;
					this.ClearState( i );
					value = $(o).val();
					if(value == "<p>&nbsp;</p>") {value='';$(o).val(value);}
					if( $(o).attr('require') == "false" && value == "") return;
					var param,v=value;
					switch(_dataType){
						case "Date" :
							param = $(o).attr("format");
							break;
						case "Repeat" :
							param = $(o).attr("to");
							break;
						case "Custom" :
							param = $(o).attr("regexp");
							break;
						case "Range" :
						case "Limit" :
						case "LimitLen" :
							param = {min:$(o).attr("min"),max:$(o).attr("max")};
							break;
						case "Compare" : 
							param = {op:$(o).attr("op"),to:$(o).attr("to")};
							break;
						case "Group" : 
							v = name;
							param = {min:$(o).attr("min"),max:$(o).attr("max")};
							break;
						default :
							break;
					}
					var _pass = $(o).attr('pass');
					if( (!_pass || (_pass && v)) && !this.test(v,_dataType,param) ){
						this.AddError( i , $(o).attr("msg") );
					}else {
						this.tips( this.ErrorItem[0].get(i).name );
					}
				},
				test : function(v , vType , param) {
					switch(vType) {
						case "IdCard" :
						case "SafeString" :
						case "Filter" :
						case "Date" :
						case "Repeat" :
						case "Range" :
						case "Compare" :
						case "Custom" :
						case "Limit" :
						case "Group" :
						case "LimitLen" :
							return (this[vType])(v,param);
						default :
							if(typeof this[vType] == "undefined"){
								return true;
							}
							else if(typeof this[vType] == "function"){
								return (this[vType])(v);
							}
							else {
								return this[vType].test(v);
							}
					}
				},
				ClearState : function( i ){
					if ( i && typeof this.ErrorItem[i] !== "undefined"){
						$("span[info='" + this.ErrorItem[ i ].name + "']")
							.removeClass("invalidate")
							.removeClass("validate")
							.html(this.OldMessage[i]);
					}
				},
				tips : function( name , msg ) {
					var span = $("span[info='" + name + "']");
					if( typeof span !== "undefined") {
						if( msg ) {
							span
							.html( msg )
							.addClass("invalidate")
							.removeClass("validate");
						}else {
							span
							.html('&nbsp;')
							.addClass("validate")
							.removeClass("invalidate");
						}
					}
				},
				AddError : function(index , str){
					this.ErrorItem[this.ErrorItem.length] = this.ErrorItem[0].get(index);
					this.ErrorMessage[this.ErrorMessage.length] = this.ErrorMessage.length + ":" + str;
					this.OldMessage[this.OldMessage.length] = $("span[info='" + this.ErrorItem[0].get(index).name + "']").html();
				}
			},
		checkForm: function(form){
			if(!$.validate.check( $(":input",form) ,3 )){
				return false;
			}
			return true;
		}
	});
})(jQuery);
