
(function(window, $, undefined){
//"use strict";
/*
 * 缓存正确的变量引用
 */
var document = window.document;

/*
 * 修复IE6背景缓存bug
 */
try{
	document.execCommand("BackgroundImageCache", false, true);
}catch(e){}



function Class() {}
(function(window) {
    //防止被匿名函数包裹
    if (window.Class !== Class) {
        window.Class = Class;
    }

    function noop() {}

    //增加调试函数
    Class.prototype.log = Class.prototype.warn = noop;
    if (window.console) {
        Class.prototype.log = function() {
            console.log && console.log.apply(console, arguments);
        };
        Class.prototype.warn = function() {
            console.warn && console.warn.apply(console, arguments);
        };
    }
    var warn = Class.prototype.warn;

    //增加callSuper占位，防止在非覆盖函数中调用callSuper，并给出警告
    Class.prototype.callSuper = function() {
        warn("父类没有同名方法，不能调用callSuper！");
    };

    //类继承（单线继承）
    Class.extend = function extend(nameSpace, props) {
        var prototype, checkResult, superPrototype = this.prototype;
        //可选参数
        if (!props) {
            props = nameSpace;
            nameSpace = "";
        }
        if (typeof props !== "object" || !props.hasOwnProperty) {
            warn("继承类的原型数据错误！");
            return;
        }
        //创建入库函数
        var regClass2Lib = checkNameSpace(nameSpace);
        if (!regClass2Lib)
            return;

        //创建子类原型
        prototype = new this();
        for (var name in props) {
            if (props.hasOwnProperty(name)) {
                if (typeof props[name] == "function" && typeof superPrototype[name] == "function") {
                    //如果父类函数没有被包装，则存在潜在的 callSuper 死循环
                    //特临时增加一个包装以解决可能的死循环问题，并给出警告
                    var superFn = superPrototype[name];
                    if (!superFn.__isAgent) {
                        superFn = getAgentFn(getWarnFn(name + "方法被子类覆盖，但是父类没有同名函数，不能调用callSuper!"), superPrototype[name]);
                    }
                    prototype[name] = getAgentFn(superFn, props[name]);
                } else {
                    prototype[name] = props[name];
                }
            }
        }

        function subClass() {}
        subClass.prototype = prototype;
        subClass.prototype.constructor = subClass;
        //保留父类的引用，供查询继承关系使用
        //subClass.__super = this;

        subClass.extend = extend;
        subClass.create = create;

        //引用到Class命名空间上
        regClass2Lib(subClass);

        //同时返回子类
        return subClass;
    };

    function create() {
        var instance = new this();
        if (instance.init) {
            instance.init.apply(instance, arguments);
        }
        return instance;
    }

    function checkNameSpace(nameSpace) {
        //没有命名空间，则不入库
        if (!nameSpace) {
            return noop;
        }
        //命名空间错误，则不能入库
        if (!/^(?:Base|Tools|Widgets|Game|Page)\./.test(nameSpace)) {
            return warn("Class命名空间错误，一级命名空间只能是:Base、Tools、Widgets、Game、Page");
        }
        var nameSpaceArr = nameSpace.split("."),
            n = nameSpaceArr.length,
            i = 0,
            path = Class,
            name;
        for (; i < n - 1; i++) {
            name = nameSpaceArr[i];
            path = path[name] = path[name] || {};
        }
        name = nameSpaceArr[n - 1];
        if (path[name]) {
            return warn("已经有同名Class存在，请更换名称或路径！");
        }
        return function(subClass) {
            path[name] = subClass;
        };
    }

    function getAgentFn(superFn, fn) {
        var agentFn = function() {
            var hasOwnCallSuper = this.hasOwnProperty("callSuper"),
                tmp = this.callSuper,
                ret;
            this.callSuper = superFn;
            //如果在 fn中调用了 callSuper 则相当于调用到了 superFn
            //如果 superFn 并没有被覆盖（未被包装），而且里面也调用了 callSuper（多数是误用）
            //那么就陷入了死循环
            ret = fn.apply(this, arguments);
            //复原callSuper
            if (!hasOwnCallSuper) {
                delete this.callSuper;
            } else {
                this.callSuper = tmp;
            }
            return ret;
        };
        //2014-07-05 马超 增加包装标志，用于解决callSuper死循环问题
        agentFn.__isAgent = true;
        return agentFn;
    }

    function getWarnFn(str) {
        return function() {
            warn(str);
        };
    }
})(window);

/**
 * 框架基础类
 */
(function(Class) {
    var slice = Array.prototype.slice,
        toString = Object.prototype.toString,
        noop = function() {},
        MUID = 1,
        isFunction = function(fn) {
            return toString.call(fn) == "[object Function]";
        };

    /**
     * 消息和事件的基础类，不入库，仅仅内部使用
     */
    var EventCore = Class.extend({
        init: function() {
            //创建私有事件缓存以及标志
            this.eventCache = this.eventCache || {};
        },
        //动态添加自定义事件缓存
        //events 仅仅支持字符串类型数据，空格分割的函数名称，建议事件名都添加 on/before/after 等明显前缀
        createEvent: function(events, extendBaseObj) {
            if (typeof events !== "string") {
                return;
            }
            var com = this,
                cache = com.eventCache;
            $.each(events.split(" "), function(i, eventName) {
                cache[eventName] = cache[eventName] || [];
                extendBaseObj && (com[eventName] = function(fn) {
                    if (isFunction(fn)) {
                        com.bind(eventName, fn);
                        return this;
                    } else {
                        return com.trigger.apply(com, [eventName].concat(slice.call(arguments, 0)));
                    }
                });
            });
        },
        //触发事件
        //cacheTime [可选]时间缓冲设置，默认0，不缓冲，缓冲类型为后到优先类型，用于防止高速事件触发动作带来的性能问题
        trigger: function(cacheTime, eventName) {
            var cache,
                falseNum = 0,
                com = this,
                para = slice.call(arguments, 1);

            //如果设置了cacheTime，则是缓冲模式，事件将无返回值
            if (!isNaN(cacheTime) && cacheTime && +cacheTime > 0) {
                if (typeof eventName !== "string") return 1;
                cache = this.eventCache[eventName || ""];
                if (!cache) return 2;
                //没有注册事件则不处理
                if (!cache.length) return 0;
                //保存参数
                cache.paras = para;
                if (!cache.t) { //如果等待的事件，则开始定时处理
                    cache.t = window.setTimeout(function() {
                        //删除定时器标志
                        delete cache.t;
                        //真正触发事件
                        com.trigger.apply(com, cache.paras);
                    }, parseInt(cacheTime, 10) || 200);
                }
                return 0;
            }
            //处理参数错误
            if (typeof cacheTime === "number" && (isNaN(cacheTime) || cacheTime < 0)) {
                if (typeof(eventName) !== "string") return 1;
                cache = this.eventCache[eventName || ""];
                if (cache) {
                    this.warn("事件" + eventName + "设置的缓冲保护时间不是合法数字");
                }
            } else {
                //检测参数
                if (typeof(cacheTime || eventName) !== "string") return 1;
                //如果没有设置cacheTime则立即激发事件，并处理回调
                cache = this.eventCache[cacheTime || eventName || ""];
            }
            //没有注册事件则不处理
            if (!cache) return 2;

            //获取事件缓存的副本，以允许事件修改缓存（卸载）
            $.each(cache.slice(0), function(i, evt) {
                try { //防止handler出现错误导致其余handler无法执行
                    if (evt.apply(com, para) === false) {
                        falseNum++;
                    }
                } catch (e) {
                    com.log(e);
                    return;
                }
            });
            return falseNum ? false : 0;
        },
        bind: function(eventName, handler) {
            if (typeof eventName !== "string") return 1;
            var cache = this.eventCache[eventName];
            if (!cache) return 2;
            if (!isFunction(handler)) return 3;
            //添加标志
            handler.muid = handler.muid || MUID++;
            //插入缓存
            cache.push(handler);
            return 0;
        },
        unbind: function(eventName, handler) {
            //全卸载
            if (arguments.length === 0) {
                this.eventCache = {};
                return 0;
            }
            //部分卸载
            if (typeof eventName !== "string") return 1;
            var cache = this.eventCache[eventName || ""];
            if (!cache) return 2;
            if (handler === undefined) {
                cache.length = 0;
                return this;
            }
            if (!isFunction(handler)) return 3;
            for (var i = 0; i < cache.length; i++) {
                if (cache[i] === handler || (handler.muid && cache[i].muid === handler.muid)) {
                    cache.splice(i, 1);
                    i--;
                }
            }
            return 0;
        },
        bindOnce: function(eventName, handler) {
            if (typeof eventName !== "string") return 1;
            var cache = this.eventCache[eventName],
                com = this;
            if (!cache) return 2;
            if (!isFunction(handler)) return 3;
            var fn = function() {
                var ret = handler.apply(this, arguments);
                com.unbind(eventName, fn);
                return ret;
            };
            //添加同源标志
            fn.muid = handler.muid = (handler.muid || MUID++);
            return com.bind(eventName, fn);
        }
    });

    /**
     * 消息通知组件
     */
    Class.extend("Base.Message", {
        init: function() {
            this.__agent = this.__agent || EventCore.create();
        },
        bindMsg: function(messageType, handler, _owner, _one) {
            if (!messageType || !isFunction(handler)) {
                return this;
            }
            //动态创建事件缓存，不设置快捷入口
            this.__agent.createEvent(messageType);
            //创建owner中介函数
            var Fn = _owner ? function() {
                return handler.apply(_owner, arguments);
            } : function() {
                return handler.apply(window, arguments);
            };
            //设置muid方便卸载用
            Fn.muid = handler.muid;
            //按照不同的绑定方式处理
            this.__agent[_one ? "bindOnce" : "bind"](messageType, Fn);
            //同步muid
            handler.muid = Fn.muid;
            return this;
        },
        bindMsgOnce: function(messageType, handler, _owner) {
            return this.bindMsg(messageType, handler, _owner, 1);
        },
        unbindMsg: function(eventName) {
            //消息不允许一次性全部卸载
            if (!eventName) {
                return this;
            }
            this.__agent.unbind.apply(this.__agent, arguments);
            return this;
        },
        sendMsg: function(cacheTime) {
            //不处理handler的返回值
            this.__agent.trigger.apply(
                this.__agent,
                //如果需要禁用缓冲保护，则打开下面的注释
                //Array.prototype.slice.call(arguments, !isNaN(cacheTime) && cacheTime ? 1 : 0)
                arguments
            );
            return this;
        }
    });
    //全局消息接口
    (function($, $$) {
        var globalMsg = Class.Base.Message.create();
        $.each(["bindMsg", "bindMsgOnce", "unbindMsg", "sendMsg"], function(i, method) {
            $$[method] = $[method] = function() {
                globalMsg[method].apply(globalMsg, arguments);
                return this;
            };
        });
    })(window.jQuery || window.Zepto, window.Zepto || window);

    /**
     * 基础事件组件
     */
    EventCore.extend("Base.Event", {
        init: function(events) {
            this.callSuper();


            this.createEvent(events, true);
            this.createEvent = noop;
        },
        trigger: function(eventName) {
            var ret = this.callSuper.apply(this, arguments);
            if (ret && !isNaN(ret)) {
                this.warn(["trigger事件名称必须是字符串", "未注册的事件(" + eventName + ")不能trigger"][ret - 1]);
            }
            if (ret === false) {
                return false;
            }
        },
        bind: function(eventName) {
            var err = this.callSuper.apply(this, arguments);
            if (err) {
                this.warn(["bind事件名称必须是字符串", "未注册的事件(" + eventName + ")不能bind", "bind(" + eventName + ")注册事件必须是函数"][err - 1]);
            }
            return this;
        },
        unbind: function(eventName) {
            //暂时不允许一次性全部卸载
            if (!eventName) {
                this.warn("暂不支持全部事件一次性卸载");
                return this;
            }
            this.callSuper.apply(this, arguments);
            return this;
        },
        bindOnce: function(eventName) {
            var err = this.callSuper.apply(this, arguments);
            if (err) {
                this.warn(["bindOnce事件名称必须是字符串", "未注册的事件(" + eventName + ")不能bindOnce", "bindOnce(" + eventName + ")注册事件必须是函数"][err - 1]);
            }
            return this;
        }
    });
})(window.Class);


/*
 * Cookie操作组件
 * 来源于jQueryUI/extend
 */
$.cookie = function(key, value, options) {
    // key and value given, set cookie...
    if (arguments.length > 1 && (value === null || typeof value !== "object")) {
        options = $.extend({}, options);
        if (value === null) {
            options.expires = -1;
        }
        if (typeof options.expires === 'number') {
            var days = options.expires,
                t = options.expires = new Date();
            t.setDate(t.getDate() + days);
        }
        return (document.cookie = [
            encodeURIComponent(key), '=',
            options.raw ? String(value) : encodeURIComponent(String(value)),
            options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
            options.path ? '; path=' + options.path : '',
            options.domain ? '; domain=' + options.domain : '',
            options.secure ? '; secure' : ''
        ].join(''));
    }
    // key and possibly options given, get cookie...
    options = value || {};
    var result, decode = options.raw ? function(s) {
        return s;
    } : decodeURIComponent;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};
/*
 * 格式化模版字符串
 * string 待格式化的字符串，占位符为 {key} 而不是其他模版文件中的 ${key} ---- MaChao: 是我想不到为什么要添加一个$前缀的原因和理由
 * source 填充数据，支持多参、数组、对象类型（对象名仅支持数字英文和下划线）
 */
(function() {
    var formatReg = RegExp.regCompile ? /./.compile("\\{([\\w\\.]+)\\}", "g") : /\{([\w\.]+)\}/g,
		isNewFormat = false,
    	format = function(string, source) {
			var isArray = true,
				N, numReg,
				//检测数据源
				data = source === undefined ? null : $.isPlainObject(source) ? (isArray = false, source) : $.isArray(source) ? source : Array.prototype.slice.call(arguments, 1);
			if (data === null)
				return string;
			//数组长度
			N = isArray ? data.length : 0;
			//预编译数字检测正则表达式
			numReg = RegExp.regCompile ? /./.compile("^\\d+$") : /^\d+$/;
			//执行替换
			return String(string).replace(formatReg, function(match, index) {
				var isNumber = numReg.test(index),
					n, fnPath, val;
				if (isNumber && isArray) {
					n = parseInt(index, 10);
					return n < N ? data[n] : match;
				} else { //数据源为对象，则遍历逐级查找数据
					fnPath = index.split(".");
					val = data;
					for (var i = 0; i < fnPath.length; i++)
						val = val[fnPath[i]];
					return val == undefined ? match : val;
				}
			});
		};
	$.format2 = format;
	$.format = function(){
		return format.apply(this,arguments).replace(formatReg,"");	
	}
})();

/*
 * 自加载模块控制组件
 * 2013-07-01 马超 增加
 * 2015-01-16 马超 完善并发加载逻辑
 *
 * target 	[可选]待绑定的对象，如果省略，则绑定当前调用者this
 * ops		[必选]待绑定的配置，格式 {"method1 method2": {css:"",js:"",check:function(){}}} 其中css和js配置也可以是数组
 * cdnURL	[可选]加载资源的cdn路径，如果提供，则自动补足相关url地址
 */
$.bindModule = function( target, ops, cdnURL ){
	//如果不提供target，则默认使用this
	if( typeof ops != "object" ){
		cdnURL = ops;
		ops = target;
		target = 0;
	}
	var lib = target || this;
	//遍历配置进行扩展
	$.each(ops||{}, function(interfaces, source){
		//必须配置js，否则不处理
		source && source.js && $.each(interfaces.split(" "), function(i, method){
			//当已有函数的时候，则不进行占位处理
			if( lib[method] )return;
			var contextList = [],
				paras = [];
			var fn = lib[method] = function(){
				var arg = arguments;
				//将上下文保留, 防止上下文在闭包的情况下出现问题
				contextList.push(this);
				//将调用参数保留
				paras.push(arg);
				//已经在加载中了，则不再处理
				if( fn.autoLoaded == 1 ) return;
				fn.autoLoaded = 1;
				//最长1秒后解锁
				var timer = window.setTimeout(function(){ fn.autoLoaded = 0; }, 1000);
				//开始加载资源
				source.css && $.loadCss(source.css, cdnURL);
				//防止重复死循环加载，加标志位进行处理
				$.loadJS(source.js, function(){
					timer && window.clearTimeout(timer);
					if( lib[method] === fn ){ //接口木有被覆盖，则放弃处理
						window.console && window.console.log("方法"+ method +"在"+ source.js +"中未被定义！自动加载模块处理失败！");
						lib[method] = $.noop;
						return;
					}
					for(var n=paras.length, i=0; i<n; i++)
						lib[method].apply(contextList[i], paras[i]);
					paras.length = 0;
				}, cdnURL);
			};
		});
	});
	return this;
};
/*	
* $.timeCount(tm, dtm, callback);
* tm为倒计时毫秒数，  callback为每个倒计时需要处理的函数
* dtm： 倒计时间隔，也即是多久运行一次setInterval
* callback: 接收两个参数(格式化后的时间数组[year,month, date, hour, mini, second, millseconds], [未格式化的年月日])
* 返回值： 返回倒计时的句柄
*/	
(function(window,$,undefined){function formatTime(tm){if(!tm||tm<0){return}var tm1=Math.floor(tm/1000);var oneday=24*3600;var day=Math.floor(tm1/oneday);var tepm=tm;var h=Math.floor((tm1-oneday*day)/3600),m=Math.floor((tm1-oneday*day-h*3600)/60),s=tm1%60,ms=tm%1000;return[day,h,m,s,ms]}function format(a){return("00"+a).slice(-2)}$.timeCount=function(tm,dtm,callback){if(!tm||tm<0){return}var ctm,lasttime,act=function(){var tmAll=formatTime(tm);if(tm<=0){callback(0);window.clearInterval(ctm)}else{callback([format(tmAll[0]),format(tmAll[1]),format(tmAll[2]),format(tmAll[3]),Math.round(tmAll[4]/100)],tmAll)}lasttime=new Date().getTime()};ctm=window.setInterval(function(){if(dtm!=1000){tm-=new Date().getTime()-lasttime}else{tm-=1000}act()},dtm||1000);act();return ctm};$.timeCount.clearTime=function(handler){window.clearInterval(handler)}})(window,jQuery);
/*
 * easyBase核心模块：load
 * 扩展两个jquery工具入口
 * $.loadJS / $.loadCss
 */
(function(){
var resCache = {},
load = function(type, url, chk, fn, charset){
	var key = url.toLowerCase().replace(/#.*$/,"").replace("/\?.*$/", ""), tag, head, isFunc = $.isFunction, cache = resCache[key]||[], userChk = !!(chk || $.noop)(url), GC = window.CollectGarbage || $.noop;
	if( userChk ){ //如果检查函数认为已经加载，则立即返回
		isFunc(fn) && fn();
		return;
	}
	resCache[key] = cache;
	if( !cache || !cache.loaded || (chk && !userChk) ){ //尚未加载
		isFunc(fn) && cache.push(fn);
		cache.loaded = 1;
		tag = document.createElement(type), head = document.getElementsByTagName("head")[0] || document.documentElement;
		//添加缓存控制参数
		url = url + (url.indexOf("?") >=0 ? "&" : "?") + (window.Core ? Core.version : +new Date());
		if( type == "link" ){ // load css
			tag.rel = "stylesheet";
			tag.type = "text/css";
			tag.media = "screen";
			tag.charset = charset || "UTF-8";
			tag.href = url;
		}else{ //load js
			tag.type = "text/javascript";
			tag.charset = charset || "UTF-8";
			var done = false;
			tag.onload = tag.onreadystatechange = function(){
				if ( !done && (!this.readyState || {loaded:1,complete:1}[this.readyState]) ) {
					//重置状态
					done = true;
					tag.onload = tag.onreadystatechange = null;
					this.parentNode.removeChild(this);
					//处理缓存
					var cache = resCache[key], n = cache.length, i=0;
					//打标记
					cache.loaded = 2;
					//调用回调
					for(; i<n; i++)
					  	isFunc(cache[i]) && cache[i]();
					//立即重置缓存
					cache.length = 0;
					//释放引用，内存回收
					cache = head = tag = null;
					GC();
				}
			};
			tag.src = url;
		}
		head.appendChild( tag, head.lastChild );
	}else if( cache.loaded == 2 ){ //已经加载过
		isFunc(fn) && fn();
		cache = null;
		GC();
	}else{ //加载中
		isFunc(fn) && cache.push(fn);
		cache = null;
		GC();
	}
},
fixURL = function(url, cdn){
	if( !cdn )return url;
	return /^http/i.test(url) ? url : (cdn.replace(/\/*$/, "") + (url.indexOf("/") == 0 ? "" : "/") + url);
};
//扩展jquery
$.extend({
	/*
	 * 加载javascript
	 */
	loadJS : function(url, chkFn, callback, charset, cdnURL){
		//如果仅仅提供一个函数，则当作回调处理
		if( !$.isFunction(callback) ){
			cdnURL = charset;
			charset = callback;
			callback = chkFn;
			chkFn = null;
		}
		//如果一个函数都没有提供
		if( !$.isFunction(callback) ){
			cdnURL = charset;
			charset = callback;
			callback = null;
		}
		//如果charset是url，则当作cdnURL
		if( /^http/i.test(charset) ){
			cdnURL = charset;
			charset = "";
		}
		if( $.isArray(url) ){
			//如果是数组，则并发加载
			//2013-05-03 马超 修改为串行加载
			var N = url.length,
				loadNo = function( index ){
					if( index < N ){ // 尚未加载完毕，继续加载
						load("script", fixURL(url[index], cdnURL), chkFn, function(){ loadNo(index+1) }, charset);
					}else{ //加载完毕
						$.isFunction(callback) && callback();
					}
				};
			//开始加载
			loadNo( 0 );
		}else //单文件加载
			load("script", fixURL(url, cdnURL), chkFn, callback, charset);
		//返回
		return this;
	},
	/*
	 * 加载样式表
	 */
	loadCss : function(url, cdnURL){
		if( $.isArray(url) ){
			var N = url.length, i=0;
			for(; i<N; i++)
				load("link", fixURL(url[i], cdnURL));
		}else
			load("link", fixURL(url, cdnURL));
		return this;
	}
});
})();

/*
 * 扩展Number对象
 */
$.extend(Number.prototype,{
	// dot 保留几位小数
	// step 逢几进位，默认5（四舍五入）
	Round : function(dot, step){var a = Math.pow(10, dot || 0); return step == 0 ? Math.ceil( this*a )/a : Math.round( this*a + (5 - (step || 5))/10 )/a; },
	// 同上
	Cint : function(step){ return this.Round(0, step); }
});

/*
 * 测试浏览器是否支持正则表达式预编译
 */
var testReg = /./, regCompile = testReg.compile && testReg.compile(testReg.source,"g");
//保存是否支持正则表达式预编译
RegExp.regCompile = regCompile;

/*
 * 预编译常用的正则表达式
 */
var compileReg = [
	/[\u4e00-\u9fa5\u3400-\u4db5\ue000-\uf8ff]/g,	//检测中文字符，共三区汉字：CJK-A、CJK-B、EUDC
	/^(?:\s|\xa0|\u3000)+|(?:\s|\xa0|\u3000)+$/g, //检测前后空格　\u00a0 == \xa0　是html中 &nbsp; 中文全角空格是 \u3000
	/([^\x00-\xff])/g	//检测双字节字符，并保留匹配结果
];
regCompile && $.each(compileReg, function(i, reg){
	compileReg[i] = reg.compile(reg.source, "g");
});

/*
 * 扩展String对象
 */
$.extend(String.prototype,{
	//删除前后空格
	trim : function(){return this.replace(compileReg[1],"");},
	//计算字节占位长度
	byteLen : function(){return this.replace(compileReg[2],"ma").length;},
	//按字节截取字符串
	// len		为要截取的字节数
	// holder	截取后的字符串后缀，比如"..."
	cutString : function( len, holder ){
		if( holder ){
			var hd = String(holder), hdLen = hd.length, str = this.replace(compileReg[2],"$1 ");
			len = len >= hdLen ? len-hdLen : 0;
			holder = str.length > len ? hd : "";
			return str.substr(0,len).replace(/([^\x00-\xff]) /g,'$1')+holder;
		}
		//算法来源于百度开源前端库
		//https://github.com/BaiduFE/Tangram-more/blob/master/src/SubstrByByte/substrByByte.js
		return this.substr(0,len).replace(compileReg[2],'$1 ').substr(0,len).replace(/([^\x00-\xff]) /g,'$1');
	},
	//截取文件名
	getFileName : function(){
		var m =/[^\\]+\.?[^\\\.]+$/g.exec(this.replace(/\//g, "\\"));
		return m ? m[0] : "";
	}
});

/*
 * 利用制表符检测IE678
 * 同样的代码 $.isIE678 = !+'\v1'; \v被低版本IE解析为v，v1就不能被转化为数字
 * 类似的代码 $.isIE678 = !-[1,];  利用IE对数组转换的特性来完成检测，但这可能会报语法错误
 */
$.isIE678 = "\v" == "v";

/*
 * 对渲染引擎和脚本引擎进行综合探测来判断IE版本
 */
if( $.isIE678 ){
	//IE8下字符串可以被当作数组取指定字符
	$.isIE8 = !!'1'[0];
	//documentMode是高版本浏览器向下兼容时提供的一种特有属性，在IE6和IETester中该属性未定义
	$.isIE6 = !$.isIE8 && (!document.documentMode || document.compatMode == "BackCompat"); 
	$.isIE7 = !$.isIE8 && !$.isIE6;
}

/*
 * 自动修复低版本IE的click BUG
 * 仅仅IE6、7、8有click问题
 */
if( $.isIE678 ){
	$.fn.extend({
		_bind_ : $.fn.bind,
		bind : function(type, data, fn){
			/^click$/gi.test(type) && fixIEClick( this );
			return this._bind_(type, data, fn);
		}
	});
	//IE click 修复函数
	var fixIEClick = function( obj ){
		var n=obj.length, i=0, dom;
		for(; i<n; i++){
			dom = obj[i];
			if( !dom.fixClick ){
				dom.fixClick = true;
				$(dom).bind("dblclick",function(e){
					//路径修复检查，避免多级修复导致的重复click问题
					var cur = e.target, n = 0;
					while ( cur && cur.nodeType !== 9 && ( cur.nodeType !== 1 || cur !== this ) ) {
						if ( cur.nodeType === 1 ) {
							if( cur.fixClick )
								return;
						}
						cur = cur.parentNode;
					}
					//模拟点击
					e.type = "click";
					e.source = "dblclick";
					//替换事件源并激发click事件
					$(e.target).trigger(e);
				});
			}
		}
	};
}

/*
 * 使得低版本IE识别HTML5标签
 */
if( $.isIE678 ){
	var html5 = "abbr,article,aside,audio,canvas,datalist,details,dialog,eventsource,figure,footer,header,hgroup,mark,menu,meter,nav,output,progress,section,time,video".split(','),
		i = html5.length;
	while(i--) document.createElement(html5[i]);
}

/*
 * jQuery简单扩展[小工具集]
 */
$.extend({
	 //从URL中捕获参数
    getUrlPara: function(paraName) {
        return $.getParaFromString(window.location.search.replace(/^\?/g, ""), paraName);
    },
    //从HASH中捕获参数
    getHashPara: function(paraName) {
        //FireFox中location.hash会默认进行解码,所以只能通过location.href来获取hash
        var match = window.location.href.match(/#(.*)$/);
        return $.getParaFromString(match ? match[1] : '', paraName);
    },
    //综合读取
    getPara: function(paraName) {
        return $.getUrlPara(paraName) || $.getHashPara(paraName)
    },
    //从字符串中捕获参数
    getParaFromString: function(str, paraName) {
        var data = {};
        $.each(("" + str).match(/([^=&#\?]+)=[^&#]+/g) || [], function(i, para) {
            var d = para.split("="),
                val = decodeURIComponent(d[1]);
            if (data[d[0]] !== undefined) {
                data[d[0]] += "," + val;
            } else {
                data[d[0]] = val;
            }
        });
        return paraName ? data[paraName] || "" : data;
    },
    //替换安全的html字符串
    safeHTML: function(str) {
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#39;");
    },
    //替换安全的正则表达式字符串
    safeRegStr: function(str) {
        return String(str).replace(/([\\\(\)\{\}\[\]\^\$\+\-\*\?\|])/g, "\\$1");
    },
    //false fn
    falseFn: function() {
        return false
    },
    //阻止冒泡函数
    stopProp: function(e) {
        e.stopPropagation()
    },
    //阻止默认行为
    preventDft: function(e) {
        e.preventDefault()
    },
    //判断是否是左键点击，e为jquery事件对象
    isLeftClick: function(e) {
        //IE 6 7 8      左键 1 右键 2 中键 4  若是组合键，则位或，如按下左键后不放点击右键，button为3
        //IE9以及其他       左键 0 中键 1 右键 2  组合键没有特殊值
        return e.button == (eval('"\\v"=="v"') ? 1 : 0);
    },
    /**
     * @name jQuery.addUrlPara
     * @param {string} url 要处理的url
     * @param {string} para 要增加的url，比如 "id=1&name=machao"
     * @param {boolean} [removeSamePara=false] 添加之前是否先移除同名的参数
     * @return {string} 处理好的url字符串
     */
    addUrlPara: function(href, para, removeSamePara) {
        var url = (href + "").split('#'),
            sp;
        //先移除同名参数
        if (removeSamePara) {
            url[0] = $.removeUrlPara(url[0], $.map(para.match(/([^=&#\?]+)=[^&#]+/g), function(str) {
                return str.replace(/=.+$/, "")
            }));
        }
        sp = url[0].indexOf("?") + 1 ? "&" : "?";
        return (url[0] + sp + para + (url.length > 1 ? "#" + url[1] : "")).replace(/\?\&/, "?");
    },
    /**
     * @name jQuery.removeUrlPara
     * @param {string} url 要处理的url
     * @param {string|array<string>} [paraName] 要移除的参数或数组，比如 "id" 或 ["name","id"]，如果不传此参数，则全部移除
     * @return {string} 处理好的url字符串
     */
    removeUrlPara: function(url, paraName) {
        var arr1 = url.split("#"),
            arr2 = arr1[0].split("?"),
            base = arr2[0],
            para = arr2.length > 1 ? arr2[1] : "",
            hash = arr1.length > 1 ? "#" + arr1[1] : "",
            paraReg = typeof paraName === "string" && paraName ? [paraName] : paraName.join ? paraName : [];
        if (!paraReg.length || !para) {
            return base.replace(/\?.+$/, "") + hash;
        }
        $.map(paraReg, function(str) {
            return str.replace(/([\\\(\)\{\}\[\]\^\$\+\-\*\?\|])/g, "\\$1");
        });
        return (
            base + "?" + para.replace(new RegExp("(\?:^\|&)(?:" + paraReg.join("|") + ")=[^&$]+", "g"), "").replace(/^&/, "")
        ).replace(/\?$/, "") + hash;
    },
    //将指定url转化为绝对路径
    //2014-12-06 马超 从easyNav中迁移过来
    fillUrl: function(url) {
        if (typeof url !== "string" || url == "") return url;
        if (!/^http/i.test(url)) {
            var port = window.location.port || "80",
                fromRoot = /^\//.test(url);
            if (!fromRoot)
                url = document.URL.replace(/\/[^\/]*$/g, "\/") + url;
            else
                url = window.location.protocol + "//" + window.location.host + (port == "80" ? "" : (":" + port)) + url;
        }
        return url;
    },
    /*
     * 添加到收藏夹
     */
    addFav: (window.sidebar && window.sidebar.addPanel) ? function(url, txt) {
        window.sidebar.addPanel(txt, url, "");
    } : function(url, txt) {
        try {
            window.external.addFavorite(url, txt);
        } catch (e) {
            window.alert("请尝试点击 Ctrl + D 来添加！");
        };
    },
    /*
     * 格式化日期
     */
    formatTime: function(timeNum, tmpl) {
        //转化为数字
        var num = /^\d+$/i.test(timeNum + "") ? +timeNum : Date.parse(timeNum);
        //如果数据不能转化为日期，则直接返回不处理
        if (isNaN(num))
            return timeNum;
        //转化日期
        var D = new Date(num),
            zz = function(a) {
                return ("0" + a).slice(-2);
            },
            yyyy = D.getFullYear(),
            M = D.getMonth() + 1,
            MM = zz(M),
            d = D.getDate(),
            dd = zz(d),
            h = D.getHours(),
            hh = zz(h),
            m = D.getMinutes(),
            mm = zz(m),
            s = D.getSeconds(),
            ss = zz(s);
        return (tmpl || "yyyy-MM-dd hh:mm:ss")
            .replace(/yyyy/g, yyyy)
            .replace(/MM/g, MM).replace(/M/g, M)
            .replace(/dd/g, dd).replace(/d/g, d)
            .replace(/hh/g, hh).replace(/h/g, h)
            .replace(/mm/g, mm).replace(/m/g, m)
            .replace(/ss/g, ss).replace(/s/g, s);
    }
});
/*
 * 极简模版引擎，基于大牛的引擎修改
 * http://ejohn.org/blog/javascript-micro-templating/
 * 详见：https://github.com/machao/tmpl/
 */
(function($) {
    var tmplCache={}, fnCache={}, guid=0, toString = Object.prototype.toString, compile = function( tmpl, sp ){
        //默认分隔符
        var f = sp || "%",
            //动态创建函数，并增加数据源引用（data/my）
            fn = new Function("var p=[],my=this,data=my,print=function(){p.push.apply(p,arguments);};p.push('" +
                // Convert the template into pure JavaScript
                tmpl
                .replace(/[\r\t\n]/g, " ")
                .split("<" + f).join("\t")
                .replace(new RegExp("((^|" + f + ">)[^\\t]*)'", "g"), "$1\r")
                .replace(new RegExp("\\t=(.*?)" + f + ">", "g"), "',$1,'")
                .split("\t").join("');")
                .split(f + ">").join("p.push('")
                .split("\r").join("\\'") + "');return p.join('');");
        return fn;
    };
    //对外接口
    $.template = function(tmpl, data, sp) {
        sp = sp||"%";
        var fn = toString.call(tmpl) === "[object Function]" ? tmpl
                : !/\W/.test(tmpl) ? fnCache[tmpl+sp] = fnCache[tmpl+sp] || compile(document.getElementById(tmpl).innerHTML, sp)
                : (function(){
                    for(var id in tmplCache)if( tmplCache[id] === tmpl ) return fnCache[id];
                    return (tmplCache[++guid] = tmpl, fnCache[guid] = compile(tmpl, sp));
                })();
        return data ? fn.call(data) : fn;
    };
})(window.jQuery || window);

//js模板引擎函数
$.jsTemplateEngine = function(html, options) {
		var re = /<\$([^\$>]+)?\$>/g,
			reExp = /(^( )?(if|for|else|switch|case|break|{|}))(.*)?/g, 
			code = 'var r=[];\n', cursor = 0;
		var add = function(line, js) {
			js? (code += line.match(reExp) ? line + '\n' : 'r.push(' + line + ');\n') :
			(code += line != '' ? 'r.push("' + line.replace(/"/g, '\\"') + '");\n' : '');
			return add;
		}
		while(match = re.exec(html)) {
			add(html.slice(cursor, match.index))(match[1], true);
			cursor = match.index + match[0].length;
		}
		add(html.substr(cursor, html.length - cursor));
		code += 'return r.join("");';
		return new Function(code.replace(/[\r\t\n]/g, '')).apply(options);
};

/*
 * jQuery原型扩展
 */
$.fn.extend({
	// disabled / enabled
	// 和setControlEffect有样式联动，目的为了解决Ie6不支持多class联合定义的bug
	disabled: function( css ) {
		return this.each(function(){
			var fix = this.bindDownCssFix || "", dis = !css ? "disabled"+fix : css;
			$(this).attr("disabled", "disabled").addClass( dis )[0].disabled = true;
		});
	},
	enabled: function( css ) {
		return this.each(function(){
			var fix = this.bindDownCssFix || "",
				dis = !css ? "disabled"+fix : css;
			$(this).removeClass( dis ).removeAttr("disabled")[0].disabled = false;
		});
	},
	// 设置选择限制 / 取消选择限制
	disableSelection: function() {return this.attr('unselectable', 'on').css('MozUserSelect', 'none').bind('selectstart', $.falseFn);},
	enableSelection: function() {return this.removeAttr('unselectable').css('MozUserSelect', '').unbind('selectstart').bind('selectstart', $.stopProp);},
	// 禁止浏览器默认的拖曳事件
	disableDarg : function(){ return this.bind('dragstart', $.falseFn); },
	enableDarg : function(){ return this.unbind('dragstart', $.falseFn); },
	// 禁止右键 / 开启右键
	disableRightClick: function(){ return this.bind("contextmenu", $.falseFn); },
	enableRightClick : function(){ return this.unbind("contextmenu", $.falseFn).bind("contextmenu", $.stopProp); },
	//禁止/启用输入法
	disableIME : function(){ return this.css("ime-mode", "disabled"); },
	enableIME : function(){ return this.css("ime-mode", ""); }
});

/*
 * 设置元素多态样式，normal状态是默认状态，不予以特殊标记；link的hover动作通过CSS设定，JS不参与
 * downCSS		鼠标按下样式，默认 down
 * keepDownCss	鼠标点击后保持的样式，如果没有按下，则不用此参数
 */
$.fn.setControlEffect = function( downCss, keepDownCss ){
	return this.each(function(){
		//添加一次性标志位
		if( this.bindControlEffect )return;
		this.bindControlEffect = 1;
		//检测并绑定
		var down = downCss || "down", fix;
		//2011-05-30 增加对down样式的记忆功能，以便和disabled成对绑定
		if( /^down(.+)$/.test(down) )
			fix = RegExp.$1;
		fix !== undefined && (
			this.bindDownCssFix = fix,
			$(this).hasClass("disabled") && $(this).removeClass("disabled").addClass("disabled"+fix)
		);
		//按下状态
		//IE(<9)下连续高速点击，将导致一部分mousedown事件丢失
		//IE 6 7 8  左键 1 右键2 中键 4  若是组合键，则位或，如按下左键后不放点击右键，button为3
		//IE9以及其他  左键0 中键1 右键2  组合键没有特殊值
		$(this).enableDarg().disableDarg().bind({
			mousedown : function(e){
				if(!$.isLeftClick(e) || this.disabled || /disabled/gi.test(this.className) )
					return false;
				$(this).addClass(down);
			},
			mouseup : function(e){
				if(!$.isLeftClick(e))
					return false;
				$(this).removeClass(down);
			},
			mouseout : function(){$(this).removeClass(down);}
		});
		//保持状态
		//IE下连续高速点击，将导致一部分click事件丢失 ---- 此bug已经通过脚本修复
		keepDownCss && $(this).click(function(){
			$(this).toggleClass(keepDownCss);
		});
	});
};

/*
 * 闪动一个Dom元素
 * 不支持动画队列，仅仅支持单个元素调用
 */
$.fn.flash = function(num,speed,fn){if($.isFunction(num)){fn=num;num=0}if($.isFunction(speed)){fn=speed;speed=0}var N=2*(num||3),i=0,isShow=this.is(":visible"),timer=this.flashTimer,obj=this;timer&&window.clearInterval(timer);timer=window.setInterval(function(){obj.css("visibility",i%2?"visible":"hidden");i++;if(i>=N){window.clearInterval(timer);obj.flashTimer=0;$.isFunction(fn)&&fn.call(obj)}},speed||200);this.flashTimer=timer;return this};

/*
 * 输入框默认值组件
 * dftValue 输入框默认值，为空则不处理
 * dftCss	默认值情况下的样式名，不传则轮换设置以下颜色值 #9a9a9a / #000
 */
$.fn.defaultValue = function(dftValue, dftCss){
	return this.each(function(){
		var me = $(this), evt, dft, evt;
		if( !me.is("input:text") && !me.is("textarea") )
			return;
		dft = dftValue || me.attr("defaultVal");
		if( dft ){
			me.attr("defaultVal", $.safeHTML(dft));
			evt = defaultValueEvents;
			dftCss && me.attr("defaultCss", $.safeHTML(dftCss));
			evt = {focus:defaultValueEvents,blur:defaultValueEvents};
			me.unbind(evt).bind(evt);
			//初始化
			evt.focus.call(this,{type:"focus"});
			evt.blur.call(this,{type:"blur"});
		}
	});
};
var defaultValueEvents = function( e ){
	var me = $(this), val = $.trim(me.val()), dft = me.attr("defaultVal") || "", css = me.attr("defaultCss");
	if( e.type == "focus" && dft == val ){
		me.val("");
		css ? me.removeClass(css) : me.css("color", "#333");
	}else if( e.type == "blur" && !val ){
		me.val(dft);
		css ? me.addClass(css) : me.css("color", "#9a9a9a");
	}
	if( e && e.type ){
	  e.value = val;
	  e.type = "i."+ e.type;
	  $(e.target).trigger(e);
	}
};
/*
 * 固定位置组件〔for IE6〕
 * 2012-08-02 马超 编写
 * 2013-01-23 马超、何泉 完善对resize的处理
 */
$.fn.fixPosition = function(){
	var me = this, t, b, l, r, css = function(o,dir){var c=(o[0].currentStyle[dir]);return c.indexOf("%")+1?false:(o.css(dir).replace(/\D/g,"")||null);}, win = $(window), top, left, fn;
	if( me.css("position") == "absolute" ){
		t = css(me,"top");
		b = css(me,"bottom");
		l = css(me,"left");
		r = css(me,"right");
		//记录当前状态
		top = +win.scrollTop();
		left = +win.scrollLeft();
		fn = function(e){
			var chk = e.type=="resize", isHide;
			if(chk){
				isHide = me.is(":hidden");
				if(!isHide)me.hide();
			}
			var _top = +win.scrollTop(), _left = +win.scrollLeft();
			b && me.css("bottom", +b+1).css("bottom", b+"px");
			t && me.css("top", (+t + _top - top )+"px");
			r && me.css("right", +r+1).css("right", r+"px");
			l && me.css("left", (+l + _left - left )+"px");
			if(chk&&!isHide)me.show();
		};
		win.scroll(fn).resize(fn);
	}
	return me;
};
/*
* 返回顶部显示隐藏和定位问题, 超过第一屏显示，第一屏内隐藏
*/
$.fn.backTopPos = function(){
	var doc = document.documentElement, docBody = document.body, obj = $(this);
	obj.fixPosition(); //针对ie6的固定位置
	function backShow(){
		var clientH = doc.clientHeight;
		if( ($(doc).scrollTop() || $(docBody).scrollTop()) > 0){ //chrome浏览器必须读取body的scrollTop才有值
			obj.show();
		}else{
			obj.hide();
		}	
	}
	$(window).scroll(backShow).resize(backShow);
	backShow();
};
//初始话客服定位, 客服开始固定定位在一处，当滚动到定不是则固定在顶部
// tops为客服距离顶部的距离，传入会比较好
$.fn.initKeFuPos = function(tops){
	return this.each(function(){
		var me = $(this), top;
		top = tops || +me.css("top"),
			setPos = function(){
				var doc = document.documentElement;
				var scrollTop = doc.scrollTop || document.body.scrollTop, h = me.height();
				if(scrollTop > top){
					me.css({"position":"fixed", "top": 0});
					if(me.css("position") == "absolute"){
						//tm && window.clearTimeout(tm);
						//tm = window.setTimeout(function(){
							me.css("top", +scrollTop);	
						//}, 5);
					}
				}else{
					me.css({"position":"absolute", "top": top});
				}
			};
		
		setPos();
		$(window).scroll(setPos).resize(setPos); //客服定位	
	});
};
/*
 * Tab切换组件
 * callback		[可选]切换回调，当tab显式的时候调用，this指向当前tab，参数是 内容dom
 * method		[可选]切换方法，支持所有合理的方法监听
 * itemTag		[可选]tab元素，用于代理监听，默认 li
 * activeCss	[可选]选中态样式，默认 active
 * hookAttr		[可选]与内容卡关联的节点名称，默认 rel，节点内容为 selector，通常是 #contentID
 */
$.fn.bindTab = function( callback, method, itemTag, activeCss, hookAttr ){
	if( !$.isFunction(callback) ){
		hookAttr = activeCss;
		activeCss = itemTag;
		itemTag = method;
		method = callback;
		callback = $.noop;
	}
	return this.each(function(){
		var tab = $(this), timer, css = activeCss || "active", tag = itemTag || "li", hook = hookAttr || "rel",
		//检查method，所有鼠标滑动触发都转化为mouseenter模式
		//fireMethod = !method || /mouse/i.test(method) ? "mouseenter" : method.toLowerCase(),
		fireMethod = method || "mouseenter",
		delay = fireMethod == "mouseenter",
		//切换tab
		toggTab = function( me ){
			$("#"+tab.find("."+css).removeClass(css).attr(hook)).hide();
			var pnl = $("#"+me.addClass(css).attr(hook)).show()[0];
			callback.call(me[0], pnl);
		};
		//绑定监听
		tab.delegate(tag, fireMethod, function(){
			var me = $(this);
			if( me.hasClass(css) || this.disabled )
				return;
			if( delay ){
				timer && window.clearTimeout(timer);
				timer = window.setTimeout(function(){toggTab(me)},200);
			}else
				toggTab(me);
		});
		delay && tab.delegate(tag, "mouseleave", function(){
			timer && window.clearTimeout(timer);
			timer = 0;
		});
		tag == "a" && tab.delegate(tag, "click", function(e){ e.preventDefault() });
	});
};

/*
 * localStorage组件
 * 支持IE6+浏览器，提供与原生localStorage类似的接口以及一个二次包装接口
 * 未压缩代码以及更多说明请查看localStorage组件目录
 */
(function(g){var h,noop=function(){},document=g.document,notSupport={set:noop,get:noop,remove:noop,clear:noop,each:noop,obj:noop};(function(){if("localStorage"in g){try{h=g.localStorage;return}catch(e){}}var o=document.getElementsByTagName("head")[0],hostKey=g.location.hostname||"localStorage",d=new Date(),doc,agent;if(!o.addBehavior){try{h=g.localStorage}catch(e){h=null}return}try{agent=new ActiveXObject('htmlfile');agent.open();agent.write('<s'+'cript>document.w=window;</s'+'cript><iframe src="/favicon.ico"></frame>');agent.close();doc=agent.w.frames[0].document;o=doc.createElement('head');doc.appendChild(o)}catch(e){o=document.getElementsByTagName("head")[0]}try{d.setDate(d.getDate()+36500);o.addBehavior("#default#userData");o.expires=d.toUTCString();o.load(hostKey);o.save(hostKey)}catch(e){return}var c,attrs;try{c=o.XMLDocument.documentElement;attrs=c.attributes}catch(e){return}var f="p__hack_",spfix="m-_-c",reg1=new RegExp("^"+f),reg2=new RegExp(spfix,"g"),encode=function(a){return encodeURIComponent(f+a).replace(/%/g,spfix)},decode=function(a){return decodeURIComponent(a.replace(reg2,"%")).replace(reg1,"")};h={length:attrs.length,isVirtualObject:true,getItem:function(a){return(attrs.getNamedItem(encode(a))||{nodeValue:null}).nodeValue||c.getAttribute(encode(a))},setItem:function(a,b){try{c.setAttribute(encode(a),b);o.save(hostKey);this.length=attrs.length}catch(e){}},removeItem:function(a){try{c.removeAttribute(encode(a));o.save(hostKey);this.length=attrs.length}catch(e){}},clear:function(){while(attrs.length){this.removeItem(attrs[0].nodeName)}this.length=0},key:function(i){return attrs[i]?decode(attrs[i].nodeName):undefined}};if(!("localStorage"in g))g.localStorage=h})();g.LS=!h?notSupport:{set:function(a,b){if(this.get(a)!==undefined)this.remove(a);h.setItem(a,b)},get:function(a){var v=h.getItem(a);return v===null?undefined:v},remove:function(a){h.removeItem(a)},clear:function(){h.clear()},each:function(a){var b=this.obj(),fn=a||function(){},key;for(key in b)if(fn.call(this,key,this.get(key))===false)break},obj:function(){var a={},i=0,n,key;if(h.isVirtualObject){a=h.key(-1)}else{n=h.length;for(;i<n;i++){key=h.key(i);a[key]=this.get(key)}}return a}};if(g.jQuery)g.jQuery.LS=g.LS})(window);
/*
 * 简易拖拉组件 $.fn.bindDrag
 * 不使用浏览器自带的拖拉事件，而是使用mousedown / mousemove / mouseup 事件
 * 一个组合参数，事件函数的调用者都指向Dom元素本身
 * {
 *	beforeDrag : fn,//鼠标按下时触发，接收一个参数，event 对象，若fn返回flase则不拖动
 *	dragStart : fn,	//准备拖动前触发，接收一个参数，event 对象，若fn返回flase则不拖动
 *	onDrag : fn,	//拖动中不断触发，接收一个参数，event 对象
 *	dragEnd : fn,	//拖动结束时触发，接收一个参数，event 对象
 *	pix : 3			//启用拖动像素差，不得小于1，不得大于10
 * }
 */
$.fn.bindDrag = function( options ){
	var op = $.extend({
			  beforeDrag:$.noop,
			  dragStart:$.noop,
			  onDrag:$.noop,
			  dragEnd:$.noop,
			  pix : 3
			}, options||{} ),
		dragCache,
		dragEvents = {
			mousedown : function(e){
				if( op.beforeDrag.call(this, e) === false) //用户停止
					return; //由原来的return false 修改 return; 2012-03-02 马超
				//缓存鼠标位置并标记
				dragCache = {
					mouse : [e.pageX, e.pageY],
					flag : 1
				};
				this.setCapture
					? this.setCapture()
					: window.captureEvents && window.captureEvents(window.Event.MOUSEMOVE|window.Event.MOUSEUP);
				$(this).one("losecapture", function(){$(document).mouseup()});
				//绑定document进行监听
				$(document).mousemove($.proxy(dragEvents.mousemove, this))
					.mouseup($.proxy(dragEvents.mouseup, this));
				//仅阻止默认行为，不阻止冒泡
				e.preventDefault();
			},
			mousemove : function(e){
				var cache = dragCache;
				if( cache.flag < 1 )
					return;
				if( cache.flag > 1 ){
					op.onDrag.call(this, e);
				}else if( Math.abs(e.pageX-cache.mouse[0])>=op.pix || Math.abs(e.pageY-cache.mouse[1])>=op.pix ){
					cache.flag = 2;
					if( op.dragStart.call(this, e) === false){ //用户停止
						cache.flag = 1;
						dragEvents.mouseup.call(this, e);
					}
				}
			},
			mouseup : function(e){
				var cache = dragCache;
				if(cache.flag > 1)
					op.dragEnd.call(this, e);
				//重置标签
				cache.flag = 0;
				this.releaseCapture
					? this.releaseCapture()
					: window.releaseEvents && window.releaseEvents(window.Event.MOUSEMOVE|window.Event.MOUSEUP);
				$(this).unbind("losecapture");
				//取消事件监听
				$(document).unbind("mousemove", dragEvents.mousemove)
					.unbind("mouseup", dragEvents.mouseup);
				return false;
			}
		};
	//像素差范围 [1,9]
	op.pix = op.pix < 1 ? 1 : op.pix > 9 ? 9 : op.pix;
	//绑定mousedown监听触发
	return this.mousedown(dragEvents.mousedown);
};

})(window, jQuery);
/*********************** easyBase End ***********************/


/*-------login组件 start-------*/
(function(window, $, undefined) {
    /**
     * 强依赖检测，AMD应用后可以去掉
     * 第二版对话框组件由于修改了组件接口，所以也需要立即加载
     */
    if (!window.Class || !$) {
        return;
    }
    var dependErr = !$.loadJS ? "baseLoad" : !$.cookie ? "cookie" : !$.format ? "format" : "";
    if (dependErr) {
        window.Class.prototype.warn("登录组件依赖的必要模块" + dependErr + "未被加载！");
        return;
    }

    /*
     * 写死的一些路径配置，AMD应用后可以去掉
     */
    var jsPath = {
        //对话框js路径
        dialog: "http://pimg1.126.net/baoxian/js2/dialog.js?V2",
        //自动完成组件js路径
        autoComplete: "http://pimg1.126.net/caipiao/js2/autoComplete.js",
        //placeholder组件js路径
        placeholder: "http://pimg1.126.net/baoxian/js/placeholder.js"
    };

    //全局配置，各个项目配置不同
    var GConf = {
            //项目标志
            appId: window.appId || "baoxian",
            //项目主页
            home: "http://baoxian.163.com",
            //是否使用iframe形式来调用第三方登录
            useIframeForUnion: true,
            //是否独占登录
            holdOnly: false
        },

        /**
         * 内置的登录表单
         */
        FORMS = {
            //自有网易邮箱帐号登录表单
            "163": {
                title: "使用网易账号登录",
                css: "",
				caption: "", //针对保险手机登陆增加  主要用于显示手机登录和网易邮箱帐号登录tab
				footadd: "",  //针对保险手机登陆增加  主要用于显示手机登录和网易邮箱帐号登录tab
                submitUrl: "https://reg.163.com/logins.jsp",
                forgetUrl: "http://reg.163.com/RecoverPasswd1.shtml",
                forgetTxt: "找回密码",
                regUrl: "http://reg.163.com/reg/mobileAliasReg.do?product={appId}",
                regTxt: "免费注册",
                loginAgent: "/agent/loginAgentV2.htm",
                domainAgent: "/agent/domainAgent.htm",
                userKey: "username",
                passKey: "password",
                succKey: "url",
                failKey: "url2",
                //自动登录表单key
                autoKey: "savelogin",
                savelogin: 1,
                hiddenData: {
                    product: GConf.appId,
                    type: 1,
                    //强制urs返回指定页面（遇到异常也返回）
                    noRedirect: 1
                },
                userHolder: "网易邮箱帐号",
                passHolder: "密码",
                errCode: {
                    "412": "您尝试的次数已经太多，请过一段时间再试。",
                    "414": "您的IP登录失败次数过多，请稍后再试。",
                    "415": "您今天登录错误次数已经太多，请明天再试。",
                    "416": "您的IP今天登录过于频繁，请稍后再试。",
                    "417": "您的IP今天登录次数过多，请明天再试。",
                    "418": "您今天登录次数过多，请明天再试。",
                    "419": "您的登录操作过于频繁，请稍候再试。",
                    "422": "账号被锁定，请您解锁后再登录！",
                    "424": "该靓号服务已到期，请您续费！",
                    "500": "系统繁忙，请您稍后再试！",
                    "503": "系统维护，请您稍后再试！",
                    "425": "账号不存在！", //外域账号并且在激活有效期以内，但尚未激活
                    "426": "账号不存在！", //外域账号并且已经过了激活有效期限！
                    "420": "账号不存在！", //用户名不存在
                    "460": "登录密码错误！", //密码不正确
                    "x": "系统繁忙，请您稍后再试！" //未定义的错误
                },
                //如果登录用户名不是邮箱，则不需要这个配置
                //如果不配置这个扩展，其不启动自动完成组件
                mailExts: "@163.com @126.com @yeah.net @vip.163.com @vip.126.com @popo.163.com @188.com @qq.com @sina.com".split(" "),
				html: [
					'{caption}',
					'<div class="loginFormBox"><form id="loginForm" method="post" action="{submitUrl}"><div class="loginErrTip" id="loginErrTip"></div><ul id="loginBox"  class="loginPopBox">',
						'<li><span class="mcInputBox"><span class="mcInputBox_inner"><label class="loginPlaceHolder"></label><input id="loginUserName" autocomplete="off" class="loginInput" name="{userKey}" myholder="{userHolder}"/></span></span><i></i></li>',
						'<li><span class="mcInputBox"><span class="mcInputBox_inner"><label class="loginPlaceHolder">&nbsp;</label><input type="password" id="loginPassword" autocomplete="off" class="loginInput" name="{passKey}" myholder="{passHolder}"></span></span></li>',
						'<li class="hide"><span><label id="saveloginLabel" for="login_savelogin"><input type="checkbox" value="{savelogin}" id="loginSavelogin" name="{autoKey}">两周内自动登录</label><b id="savelogin_tip"><i><b></b></i>为了您的信息安全，请不要在网吧或公共电脑上使用此功能！</b></span></li>',
						'<li><div class="loginPBar"><button style="position:absolute;border:0;visiblity:hidden;width:0;height:0;overflow:hidden" type="submit">submit</button><a class="iDialogBtn focusBtn"><span>登录</span></a></div> <div class="login_optBar"><span class="login_links">没有网易账号？<a id="login_reg" href="{regUrl}">{regTxt}</a></span><a href="{forgetUrl}" id="login_pswUrl" class="login_links">{forgetTxt}</a></div></li>',
					'</ul><input name="{succKey}" id="loginSuccessUrl" type="hidden" value=""/>',
                    '<input name="{failKey}" id="loginFailUrl" type="hidden" value=""/>',
                    '{hiddenInput}',
					'</form><div class="thirdPartyLoginWrap {unionCss}">其他方式登录：{unionLoginLinks}</div>{footadd}</div>'
				].join(""),
                //对话框html
              /*  html: ['<form id="loginForm" method="post" action="{submitUrl}">',
                    '<div class="loginErrTip"></div>',
                    '<ul id="loginBox">',
                    '<li><span class="mcInputBox"><span class="mcInputBoxUser">',
                    '<input name="{userKey}" class="loginInput" autocomplete="off" myholder="{userHolder}" id="loginUserName" value=""/>',
                    '</span></span><a href="{regUrl}" id="login_reg" tabindex="-1">{regTxt}</a></li>',
                    '<li id="loginUserErrTip">&nbsp;</li>',
                    '<li><span class="mcInputBox"><span class="mcInputBoxPass">',
                    '<input name="{passKey}" class="loginInput" autocomplete="off" myholder="{passHolder}" id="loginPassword" type="password" value=""/>',
                    '</span></span><a id="login_pswUrl" href="{forgetUrl}" tabindex="-1">{forgetTxt}</a></li>',
                    '<li class="errAndSaveLi"><span id="loginPassErrTip"></span>',
                    '<label for="loginSavelogin"><input type="checkbox" name="{autoKey}" value="{savelogin}" id="loginSavelogin"/>自动登录</label></li>',
                    '<li class="leftLi"><button type="submit" style="position:absolute;border:0;left:-9999px;top:-999px;visiblity:hidden;width:0;height:0;overflow:hidden">submit</button>',
                    '<a class="iDialogBtn focusBtn"><span>登 录</span></a></li>',
                    '</ul><input name="{succKey}" id="loginSuccessUrl" type="hidden" value=""/>',
                    '<input name="{failKey}" id="loginFailUrl" type="hidden" value=""/>',
                    '{hiddenInput}',
                    '</form><div class="thirdPartyLoginWrap {unionCss}">其他方式登录：{unionLoginLinks}</div>'
                ].join(""),*/
                html2: ["<div class='iDialogFrameWrap'>",
                    "<iframe class='iDialogFrame' frameborder='0' src='{iframe}' allowTransparency='true' scrolling='no'></iframe>",
                    "</div>",
                    "<div class='thirdPartyLoginWrap {unionCss}'>其他方式登录：{unionLoginLinks}</div>"
                ].join("")
            },
            //第三方无刷新登录统一使用iframe方式
            //腾讯=1, 人人=2, 微博=3 腾讯微博=4, 奇虎360=5, 微信=6, 豆瓣=7, 易信开放平台=8, 易信公众平台=9, 58同城=10，
            //2014-12-02 马超测试结果：
            // qq登录         可用
            // 人人登录       urs返回结果“维护中”（503）
            // 微博           可用
            // 腾讯微博       登录后返回地址有误
            // 奇虎360        登录后返回地址有误
            // 微信           不能用浏览器访问（提示微信客户端内访问）
            // 豆瓣           可用
            // 易信开放平台   可用
            // 易信公众平台   不能浏览器访问（提示客户端内访问）
            "qq": {
                sort: 9,
                ursAccountReg: /@tencent\.163\.com$/,
                target: 1,
                winSize: [750, 450]
            },
            "weixin":{
                name : "微信",
                sort : 8,
                ursAccountReg: /@wx\.163\.com$/,
                target: 13,
                winSize: [450, 550]
            },
            // "weibo": {
            //     sort: 8,
            //     name : "微博",
            //     ursAccountReg: /@sina\.163\.com$/,
            //     target: 3,
            //     winSize: [755, 550]
            // },
            // "douban": {
            //     sort: 8,
            //     name: "豆瓣",
            //     ursAccountReg: /@douban\.163\.com$/,
            //     target: 7,
            //     winSize: [450, 600]
            // },
            // "yixin": {
            //     sort: 8,
            //     name: "易信",
            //     ursAccountReg: /@yixin\.163\.com$/,
            //     target: 8,
            //     winSize: [500, 450]
            // },
            "58": {
                sort: 7,
                ursAccountReg: /@58\.163\.com$/,
                target: 10,
                getNickName: function(ursId) {
                    return ursId.replace(/@.+$/, "");
                },
                winSize: [981, 593]
            }
        },

        /**
         * 内部私有对象
         */
        _login = {
            //设置默认表单类型
            defSite: "163",
            //当前表单类型
            site: "163",
            //设置默认的登录表单
            setDefault: function(siteName, holdOnly, _forceSet) {
                var lib = _login,
                    conf = FORMS[siteName];
                if (!conf) {
                    return;
                }
                if (typeof holdOnly === "boolean") {
                    GConf.holdOnly = holdOnly;
                }
                lib.defSite = siteName;
            },

            //修改登录配置
            setConf: function(siteName, options) {
                //提供一个参数，则表示修改全局配置
                if (!options) {
                    $.extend(GConf, siteName || {});
                    return;
                }
                //否则修改站点配置
                var conf = FORMS[siteName];
                if (!conf) {
                    return;
                }
                var act = function() {
                    $.extend(conf, options || {});
                };
                if (login.lock) {
                    $.bindMsgOnce("login.complete", act);
                    return;
                }
                act();
            },

            //检查参数
            checkOptions: function(comboPara, options) {
                var ops = {};
                //如果是字符串
                if (typeof comboPara === "string") {
                    $.extend(ops, options || {}, {
                        successUrl: comboPara
                    });
                }
                //如果是回调
                if ($.isFunction(comboPara)) {
                    $.extend(ops, options || {}, {
                        callback: comboPara
                    });
                }
                //如果是配置
                if (typeof comboPara === "object" && !$.isArray(comboPara)) {
                    $.extend(ops, comboPara || {});
                }
                //没有指定回调，也没有指定url，则默认url是当前页面
                if (!$.isFunction(ops.callback) && !ops.successUrl) {
                    ops.successUrl = document.URL;
                }
                //初始化用户名
                if (!ops.initUserName) {
                    ops.initUserName = (_login.cookie.getPInfo("163") || {}).base;
                    if (!ops.initUserName && window.LS && window.LS.get) {
                        ops.initUserName = LS.get("lastURSAccount");
                    }
                }
                //设置loginType
                ops.loginType = $.isFunction(ops.callback) ? 1 : 0;
                //包装的登录回调
                //回调参数发生变化
                ops.__callback = ops.loginType ? ops.callback : function() {
                    $.sendMsg("login.jump", _login.site);
                    var URL = document.URL;
                    window.location.href = ops.successUrl;
                    //如果当前页面地址和要跳转的地址相同，则使用reload方法
                    if( URL.replace(/#.+$/g, "") === ops.successUrl.replace(/#.+$/g, "") ){
                        window.location.reload(true);
                    }
                };
                //动画类型
                ops.animate = ops.animate === 0 ? 0 : (ops.animate || 5);
                //自动登录选项
                ops.savelogin = FORMS["163"].autoKey ? ops.savelogin : null;
                //填充
                _login.fillUrlFor(ops, ["successUrl", "failUrl"]);
                //返回配置
                return ops;
            },

            //填充url为完整地址
            //独立函数，可以移植到框架上去
            fillUrl: function(url) {
                if (typeof url !== "string" || !url || /^(?:http|javascript)/i.test(url)) return url;
                if (/^#/.test(url)) {
                    return document.URL.replace(/#.*$/, "") + url;
                }
                if (/^\//.test(url)) {
                    var port = ":" + (window.location.port || "80");
                    return window.location.protocol + "//" + window.location.host + (port == ":80" ? "" : port) + url;
                }
                return document.URL.replace(/\/[^\/]*$/g, "\/") + url;
            },

            //fillUrl包装
            fillUrlFor: function(ops, keyArr) {
                $.each(keyArr, function(i, key) {
                    if (ops[key]) {
                        ops[key] = _login.fillUrl(ops[key]);
                    }
                });
            },

            //解绑事件
            destroy: function(ops) {
                if (!ops) {
                    return;
                }
                $.unbindMsg("login.success", ops.__callback);
                var me = this;
                $.each(["orgOptions", "dialog"], function(i, k) {
                    if (me[k]) {
                        delete me[k];
                    }
                });
            },

            //拼接第三方登录url
            getUnionLinks: function(curSite, backUrl) {
                var arr = [],
                    netese;
                $.each(FORMS, function(site, conf) {
                    if (site.toLowerCase() !== curSite.toLowerCase()) {
                        conf.site = site;
                        if (site === "163") {
                            netese = conf;
                        } else {
                            arr.push(conf);
                        }
                    }
                });
                //排序
                arr.sort(function(a, b) {
                    return (b.sort || 0) - (a.sort || 0);
                });
                //构造html
                return $.map(netese ? [netese].concat(arr) : arr, function(conf) {
                    if (conf.site === "163") {
                        return '<a rel="163" class="neteaseLogin" href="https://reg.163.com"><span>网易邮箱帐号登录</span></a>';
                    } else {
                        var url = encodeURIComponent(backUrl || document.URL),
                            SITE = conf.site.toUpperCase();
                        return ['<a rel="', conf.site, '" class="thirdPartyLoginBtn thirdPartyLogin', SITE, '" ',
                            'href="http://reg.163.com/outerLogin/oauth2/connect.do?target=', conf.target,
                            '&url=', conf.unionTrans ? encodeURIComponent($.format2(conf.unionTrans, url)) : url,
                            '&url2=', encodeURIComponent(GConf.home),
                            '&domains=163.com&product=', GConf.appId,
                            '"><span>用', conf.name || SITE, '账号登录</span></a>'
                        ].join("");
                    }
                }).join("");
            },

            //获得指定站点的联合登录链接（iframe使用）
            getIframeUnionLink: function(site, backUrl) {
                //逻辑上不会以第三方登录模式打开网易自有登录
                if (site === "163") {
                    return 'https://reg.163.com';
                } else {
                    var url = encodeURIComponent(backUrl || document.URL),
                        conf = FORMS[site];
                    if( conf.unionTrans ){
                        url = encodeURIComponent($.format2(conf.unionTrans, url));
                    }
                    return 'http://reg.163.com/outerLogin/oauth2/connect.do?target=' + conf.target + '&url=' + url + '&url2=' + url + '&domains=163.com&product=' + GConf.appId;
                }
            },

            //获得一个动态的callbackName
            getACallbackName: function(prefix) {
                return (prefix || "cb") + String(+new Date()) + parseInt(Math.random() * 100 + 1);
            },

            //修改其他登录入口
            checkUnionEntry: function(ops) {
                //参数中的holdOnly优先
                if (typeof ops.holdOnly === "boolean" ? ops.holdOnly : GConf.holdOnly) {
                    ops.unionCss = "hide";
                } else {
                    ops.unionCss = "";
                    ops.unionLoginLinks = _login.getUnionLinks(_login.site, ops.successUrl);
                }
            },

            //监听其他登录入口
            bindUnionEntry: function(dialog) {
                //监听其他登录入口
                dialog && dialog.wrap.delegate(".thirdPartyLoginWrap a", "click", function() {
                    var mySite = $(this).attr("rel") || "163";
                    _login.site = mySite;
                    //如果不使用iframe模式，则需要立即打开模态窗，防止异步操作有权限限制
                    if (!GConf.useIframeForUnion && mySite !== "163") {
                        dialog.close("alt");
                        unionLogin(_login.orgOptions, 1);
                        //使用了iframe模式，则需要关闭当前对话框后，打开新的登录框
                    } else {
                        dialog.onClose(function() {
                            mySite == "163" ? neteaseLogin(_login.orgOptions, 1) : unionLogin(_login.orgOptions, 1);
                        }).close("alt");
                    }
                    return false;
                });
            },

            //登录cookie的使用
            cookie: {
                getPInfo: function(site, _returnName) {
                    var name = site === "163" || $.cookie("S_INFO") || !$.cookie("S_OINFO") ? "P_INFO" : "P_OINFO";
                    if (_returnName) {
                        return name;
                    }
                    var info = ($.cookie(name) || "").replace(/\"|\'/g, ""),
                        arr = info.split("|");
                    if (arr.length > 1 && /^.+@.+$/.test(arr[0])) {
                        var alias = arr[arr.length - 1];
                        return {
                            base: arr[0],
                            alias: /^1\d{10}@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(alias) ? alias : arr[0]
                        };
                    }
                },
                getSInfo: function(site) {
                    var name = this.getPInfo(site, true) === "P_INFO" ? "S_INFO" : "S_OINFO",
                        info = $.cookie(name) || "";
                    return info.split("|").length > 1 ? info : "";
                },
                //根据cookie预检查当前的登录状态和用户名
                checkCurrentStatus: function() {
                    var soinfo = $.cookie("S_OINFO"),
                        sinfo = $.cookie("S_INFO"),
                        pinfo = this.getPInfo();
                    //网易自有登录
                    if (sinfo) {
                        return {
                            site: "163",
                            ursId: (pinfo || {}).base
                        };
                    }
                    //如果是第三方登录状态
                    //根据用户名检查是哪个站点的用户
                    if (soinfo) {
                        if (pinfo && pinfo.base) {
                            $.each(FORMS, function(site, conf) {
                                if (conf.ursAccountReg && conf.ursAccountReg.test(pinfo.base)) {
                                    pinfo.site = site;
                                    pinfo.ursId = pinfo.base;
                                    return false;
                                }
                            });
                            if (pinfo.site) {
                                return pinfo;
                            }
                        }
                    }
                    //任何错误，都默认是网易的自有登录，且未登录
                    return {
                        site: "163",
                        ursId: ""
                    };
                }
            }
        },

        /**
         * 主登录接口
         */
        login = function(backUrlOrCallback, options) {
            //并发锁，UI界面仅仅允许打开一个
            if (login.lock) {
                return;
            }
            login.lock = 1;
            $.bindMsgOnce("login.complete", function() {
                login.lock = 0;
            });
            //处理参数
            var ops = _login.checkOptions(backUrlOrCallback, options);
            //保存用户配置副本，方便更换登录方案时使用
            _login.orgOptions = $.extend({}, ops);

            //继续
            $.loadJS(jsPath.dialog, function() {
                var dialogLoaded = Class.Widgets && Class.Widgets.Dialog,
                    //如果对话框组件没有加载，并且需要用弹窗打开第三方登录，则提前打开一个窗口备用
                    site = ops.site || _login.defSite,
                    isUnion = site !== "163" && !GConf.useIframeForUnion;
                if (!dialogLoaded && isUnion && FORMS[site]) {
                    UnionEvents.prepearWin("about:blank", FORMS[site].winSize);
                }
                return dialogLoaded;
            }, function() {
                //挂接消息通知
                $.bindMsgOnce("login.success", ops.__callback);
                //设置当前的登录表单并分情况打开登录框
                (_login.site = ops.site || _login.defSite) === "163" ? neteaseLogin(ops): unionLogin(ops);
            });
        },

        /**
         * 网易自有登录
         * 2014-12-13 马超 增加直接使用 用户名、密码 进行登录接口
         */
        neteaseLogin = function(options, _shortCut) {
            //构造登录表单
            var ops = $.extend({}, FORMS["163"], options || {}),
                site = "163";

            //检查其他登录入口
            _login.checkUnionEntry(ops);

            //转化为全路径
            _login.fillUrlFor(ops, ["domainAgent", "loginAgent"]);

            //补充url
            $.each(ops, function(key, value) {
                if (key.indexOf("Url") > 0 && typeof value === "string") {
                    ops[key] = $.format2(value, GConf);
                }
            });

            //底层实现全部是模拟无刷新方式
            //检查登录代理文件
            if (!ops.loginAgent || !ops.domainAgent) {
                Class.prototype.warn("缺少登录代理配置(loginAgent或domainAgent)!");
                return;
            }

            //隐藏字段
            ops.hiddenInput = (function() {
                //需要时再打开此功能
                //var hiddenData = $.isFunction(ops.hiddenData) ? ops.hiddenData(ops) : ops.hiddenData,
                var hiddenData = ops.hiddenData,
                    hiddeHTML = [];
                if (!hiddenData) {
                    return "";
                }
                for (var name in hiddenData) {
                    if (hiddenData[name] !== null && hiddenData[name] !== undefined) {
                        hiddeHTML.push($.format2('<input name="{0}" type="hidden" value="{1}"/>', name, hiddenData[name]));
                    }
                }
                return hiddeHTML.join("");
            })();

            //如果是无界面直接提交，则
            if (ops.username && ops.password) {
                $("#directSubmitWrap").remove();
                $(document.body).append("<div id='directSubmitWrap' style='display:none;'></div>");
                NtesEvents.initAndSubmit($("#directSubmitWrap").html($.format2(ops.html, ops)), ops);
                return;
            }

            //创建对话框对象并返回
            $.dialog({
                css: "loginDialog" + (ops.css ? " " + ops.css : ""),
                width: 0,
                method: "prepend",
                position: ops.position || "c",
                title: ops.title,
                content: $.format2(ops.html, ops),
                animate: ops.animate,
                button: []
            }).onCreate(function() {
                NtesEvents.init(this, ops);
                _login.bindUnionEntry(this);
                _login.dialog = this;
            }).onShow(function() {
                //新版本的jq选择器已经不能按照 value属性 来选择dom了
                //只能按照 value节点 来选择dom
                this.wrap.find("input:visible").each(function() {
                    if (!$.trim(this.value)) {
                        this.focus();
                        return false;
                    }
                });
                $.sendMsg("login.show", site, !!_shortCut);
            }).onBeforeClose(function(ret) {
                // 关闭登录弹出框的时候，也要把自动完成浮出层隐藏
                $.hideAutoComplete && $.hideAutoComplete();
            }).onClose(function(ret) {
                if (ret === null) { //放弃登录
                    $.sendMsg("login.giveup", site);
                }
                if (ret !== "alt") { //不是更换登录，则通知完成
                    $.sendMsg("login.complete", site, ret ? 1 : 0);
                    _login.destroy(ops);
                }
            });
        },

        //网易自有登录表单事件
        NtesEvents = {
            //初始化dom缓存，方便查找和使用
            makeCache: function(dialog, ops) {
                var form = dialog.wrap.find("form");
                this.options = ops;
                this.dialog = dialog;
                this.cache = {
                    form: form,
                    user: $("#loginUserName"),
                    psw: $("#loginPassword"),
                    userTip: $("#loginErrTip"),
                    pswTip: $("#loginErrTip"),
                    topTip: $("#loginErrTip")
                };
                //创建iframe代理
                var loginIframeAgent = $("#loginIframeAgent");
                if (!loginIframeAgent[0]) {
                    //为了提高IE下的页面效率，使用prepend将iframe插入到页面最顶部
                    $(document.body).prepend("<iframe id='loginIframeAgent' name='loginIframeAgent' frameborder='0' width='0' height='0' src='" + ops.domainAgent + "'/>");
                    loginIframeAgent = $("#loginIframeAgent");
                }
                this.cache.agent = loginIframeAgent;
            },

            //表单初始化
            init: function(dialog, ops) {
                //创建dom缓存
                NtesEvents.makeCache(dialog, ops);

                var cache = this.cache,
                    form = cache.form,
                    user = cache.user;

                //form提交
                form.submit(function() {
                    return NtesEvents.onSubmit(form);
                });
                dialog.onBtnClick(function() {
                    form.trigger('submit');
                    return false;
                });

                //自动完成
                if (ops.mailExts && ops.mailExts.length) {
                    $.loadJS(jsPath.autoComplete, function() {
                        return !!$.fn.autoFill;
                    }, function() {
                        user.autoFill(ops.mailExts, {
                            onHide: NtesEvents.onHide,
                            canShowNormal: /^\d*$/,
                            listCss: 'autoFill loginAutoFill'
                        });
                    });
                }

                //初始化用户名
                user.val(ops.initUserName || "");

                //placeholder
                $.loadJS(jsPath.placeholder, function() {
                    return !!$.fn.placeholder;
                }, function() {
                    user.placeholder();
                    cache.psw.placeholder();
                    //触发一次输入框校验和同步
                    user.keyup();
                });

                //创建回调
                var callbackName = _login.getACallbackName("login");
                window[callbackName] = NtesEvents.loginCallback;

                //保存回调名，方便卸载和清理
                this.callbackName = callbackName;

                //修改表单提交位置
                form[0].target = "loginIframeAgent";

                //写入返回地址，成功和错误均返回代理页面，并将回调函数名传给代理文件
                $("#loginSuccessUrl,#loginFailUrl").val($.addUrlPara(ops.loginAgent, "callback=" + callbackName));

                //修改自动登录选中样式
                if (ops.savelogin === null) {
                    $("#loginSavelogin").parent().hide();
                } else {
                    $("#loginSavelogin")[0].checked = !!ops.savelogin;
                }
            },

            //直接带用户名、密码的表单提交
            //非请勿用
            initAndSubmit: function(wrap, ops) {
                //创建dom缓存
                NtesEvents.makeCache({
                    wrap: wrap
                }, ops);
                var cache = this.cache,
                    form = cache.form;
                //创建回调
                var callbackName = _login.getACallbackName("login");
                window[callbackName] = NtesEvents.loginCallback;
                //保存回调名，方便卸载和清理
                this.callbackName = callbackName;
                //修改表单提交位置
                form[0].target = "loginIframeAgent";
                //写入返回地址，成功和错误均返回代理页面，并将回调函数名传给代理文件
                $("#loginSuccessUrl,#loginFailUrl").val($.addUrlPara(ops.loginAgent, "callback=" + callbackName));
                //写入用户名、密码并提交表单
                cache.user.val(ops.username);
                cache.psw.val(ops.password);
                //为了能触发complete则需要对事件转化
                var succ = function(site, userID) {
                        $.sendMsg("login.complete", "163", 1);
                        $.unbindMsg("login.fail", fail);
                        window.setTimeout(function() {
                            _login.destroy(ops);
                        }, 1);
                    },
                    fail = function() {
                        $.sendMsg("login.complete", "163", 0);
                        $.unbindMsg("login.success", succ);
                        window.setTimeout(function() {
                            _login.destroy(ops);
                        }, 1);
                    };
                $.bindMsgOnce("login.success", succ);
                $.bindMsgOnce("login.fail", fail);
                //提交表单
                form.trigger('submit');
            },

            //登录表单提交时
            onSubmit: function(form) {
                if (this.inSubmit || !form || !form[0]) {
                    return false;
                }
                //重置tip
                NtesEvents.inputErrTip().topErrTip();

                //检查表单
                var user = $("#loginUserName")[0],
                    psw = $("#loginPassword")[0],
                    userName = $.trim(user.value);
                if (!userName) {
                    NtesEvents.inputErrTip("user", "", 700);
                    return false;
                }

                //检查账号是否正确，宽松校验，并支持输入手机号
                if (!/^\w+[-+.\w]*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(userName) && !/^1[0-9]{10}$/.test(userName)) {
                    NtesEvents.inputErrTip("user", "请输入正确的账号！", 2000);
                    return false;
                }

                //检查密码是否输入
                if (!$.trim(psw.value).length) {
                    NtesEvents.inputErrTip("psw", "", 700);
                    return false;
                }

                //登录按钮提示文案修改
                $(".iDialogBtn span", form).html("登录中");

                //设置20s超时，超时后提示用户是否放弃登录，否则继续等待
                //多增加1秒留给浏览器做响应：）
                NtesEvents.loginTimeout = window.setTimeout(function() {
                    NtesEvents.topErrTip("登录时间过长，是否<a href='javascript:;' id='reLogin'>重新登录</a>？", true);
                    $("#reLogin").unbind().click(function() {
                        NtesEvents.reset(true);
                        return false;
                    });
                    $.sendMsg("login.timeout", _login.site);
                }, 21000);

                //提交表单
                this.inSubmit = true;

                //允许表单提交
                return true;
            },

            //顶部错误信息提示
            topErrTip: function(txt, keep) {
                var tip = this.cache.topTip;
                if (!tip[0]) {
                    return this;
                }
                window.clearTimeout(tip[0].timer || 0);
                if (txt) {
                    tip.html(txt);
                    if (!keep) {
                        tip[0].timer = window.setTimeout(function() {
                            tip.html("");
                        }, 5000);
                    }
                } else {
                    tip.html("");
                }
                return this;
            },

            //输入框错误提示
            inputErrTip: function(type, error, delay) {
                var cache = this.cache,
                    form = cache.form;
                if (!form[0]) {
                    return this;
                }
                //不传递内容，则清空错误提示
                if (!type) {
                    return this.inputErrTip("user").inputErrTip("psw");
                }
                //查找dom元素
                var doms = type == "user" ? {
                        input: cache.user,
                        tip: cache.userTip
                    } : type == "psw" ? {
                        input: cache.psw,
                        tip: cache.pswTip
                    } : 0,
                    input = doms ? doms.input : 0,
                    box = doms ? input.closest('.mcInputBox') : 0,
                    //清空错误信息
                    resetTip = doms ? function() {
                        box.removeClass("loginInputErr");
                        doms.tip.empty();
                        if (input[0].errTimer) {
                            window.clearTimeout(input[0].errTimer);
                            input[0].errTimer = 0;
                        }
                    } : $.noop;
                //清空已有的定时器
                if (input[0]) {
                    window.clearTimeout(input[0].errTimer);
                }
                //如果不设置错误信息，则重置
                if (!error && error !== "") {
                    resetTip();
                    return this;
                }
                //提示错误信息
                if (box) {
                    box.addClass("loginInputErr");
                    doms.tip.html(error);
                    input[0].errTimer = window.setTimeout(resetTip, delay || 5000);
                    input[0].select();
                }
                return this;
            },

            //重置表单
            reset: function(relogin) {
                var form = this.cache.form;
                if (form[0]) { //防止没有提交完毕，用户就关闭对话框
                    $(".iDialogBtn span", form).html("登录");
                }
                NtesEvents.inSubmit = false;
                NtesEvents.loginTimeout && window.clearTimeout(NtesEvents.loginTimeout);
                NtesEvents.inputErrTip().topErrTip();
                //重新登录
                if (relogin === true && form && form[0]) {
                    form.trigger('submit');
                }
            },

            //自动完成列表隐藏时
            onHide: function(reason) {
                if (reason == "inputConfirm")
                    $("#loginPassword")[0].select();
                else if (reason == "loseFocus") {
                    var v = $(".autoListItemHover", "#autoCompleteList"),
                        username = $("#loginUserName")[0].value;
                    if (v[0]) {
                        $("#loginUserName")[0].value = v[0].title;
                    }
                }
            },

            //登录回调
            loginCallback: function(errCode, userId, jumpUrl) {
                var errCodeMap = FORMS["163"].errCode,
                    error = errCodeMap[errCode];
                //复原表单
                NtesEvents.reset();
                //不需要跳转到urs进行处理的，则进行默认的错误提示
                if (errCode > 0 && !jumpUrl) {
                    //页面提示
                    if ($.inArray(errCode, [425, 426, 420]) >= 0) {
                        NtesEvents.inputErrTip("user", error);
                    } else if (errCode == 460) {
                        NtesEvents.inputErrTip("psw", error);
                    } else {
                        NtesEvents.topErrTip(error);
                    }
                    //发送登录错误消息
                    $.sendMsg("login.fail", _login.site, errCode, error);
                    return;
                }
                //需要异常处理的，则询问回调，返回false则不处理，否则需要返回一个成功后返回的url地址，默认当前页
                if (jumpUrl) {
                    //获取登录后的页面地址
                    var backUrl = NtesEvents.options.successUrl || document.URL;
                    //发送登录错误消息
                    $.sendMsg("login.fail", _login.site, errCode, error);
                    //页面跳转前通知
                    $.sendMsg("login.jump");
                    //如果需要跳转到urs，则附加上url参数到jumpUrl上，然后去urs进行后续处理
                    window.top.location.href = $.addUrlPara(jumpUrl, "username=" + encodeURIComponent(userId) + "&url=" + encodeURIComponent(backUrl));
                    return;
                }
                if (userId) {
                    //发送消息通知
                    $.sendMsg("login.success", _login.site, userId);
                    //清理
                    NtesEvents.destroy();
                }
            },

            //清理缓存和dom
            destroy: function() {
                //删除回调引用
                var cache = this.cache,
                    form = cache.form,
                    cb = this.callbackName;
                if (cb && window[cb]) {
                    window[cb] = undefined;
                }
                //关闭对话框
                if (this.dialog && this.dialog.close) {
                    this.dialog.close(true);
                } else {
                    $("#directSubmitWrap").remove();
                }
                //删除缓存
                delete this.options;
                delete this.cache;
                if (this.dialog) {
                    delete this.dialog;
                }
            }
        },

        /**
         * 第三方登录[iframe]
         */
        unionLogin = function(options, _shortCut) {
            var site = _login.site,
                ops = $.extend({
                    //登录代理文件默认走网易自有登录的
                    loginAgent: FORMS["163"].loginAgent,
                    html: FORMS["163"].html2
                }, FORMS[site] || {}, options || {});
            //自有登录
            if (site == "163") {
                return neteaseLogin(options);
            }
            //转化为全路径
            _login.fillUrlFor(ops, ["loginAgent"]);

            //检查登录代理文件
            if (!ops.loginAgent) {
                Class.prototype.warn("缺少登录代理配置(loginAgent)!");
                return;
            }
            //创建回调
            var callbackName = ops.callbackName = _login.getACallbackName("ulogin"),
                agentUrl = $.addUrlPara(ops.loginAgent, "union=" + site + "&type=" + (GConf.useIframeForUnion ? 0 : 1) + "&callback=" + callbackName);
            ops.iframe = _login.getIframeUnionLink(site, agentUrl);
            //如果是登录框快捷入口打开，则按照配置使用
            //如果是默认直接打开的时候，则不能直接mdlWin模式，防止权限问题无法打开弹窗
            return UnionEvents[GConf.useIframeForUnion ? "iframe" : "mdlWin"](site, ops, _shortCut);
        },

        /**
         * 第三方登录相关函数
         */
        UnionEvents = {
            iframe: function(site, ops, _shortCut) {
                var SITE = site.toUpperCase();

                //检查其他登录入口
                _login.checkUnionEntry(ops);

                //绑定回调
                window[ops.callbackName] = function(errCode, union, userId) {
                    var errCodeMap = FORMS["163"].errCode,
                        errMsg = errCodeMap[errCode] || errCodeMap.x;
                    if (union == site) {
                        //发送消息通知
                        if (errCode) {
                            $.sendMsg("login.fail", site, errCode, errMsg);
                            $.dialog.toast(errMsg);
                        } else {
                            $.sendMsg("login.success", site, userId);
                        }
                        dialog && dialog.close(true);
                    } else {
                        //要处理吗？
                    }
                };

                //创建对话框对象并返回
                var dialog = $.dialog({
                    css: "unionLoginDialog loginDialogFor" + SITE + " loginDialog" + (ops.css ? " " + ops.css : ""),
                    width: 0,
                    title: /*ops.title ||*/ ("用" + (ops.name || SITE) + "账号登录"),
                    method: "prepend",
                    position: ops.position || "c",
                    content: $.format2(ops.html, ops),
                    animate: ops.animate,
                    button: []
                }).onCreate(function() {
                    _login.bindUnionEntry(this);
                    _login.dialog = this;
                }).onShow(function() {
                    $.sendMsg("login.show", site, !!_shortCut);
                }).onBeforeClose(function(ret) {
                    // 关闭登录弹出框的时候，也要把自动完成浮出层隐藏
                    $.hideAutoComplete && $.hideAutoComplete();
                }).onClose(function(ret) {
                    if (ret === null) { //放弃登录
                        $.sendMsg("login.giveup", site);
                    }
                    if (ret !== "alt") { //不是更换登录，则通知完成
                        $.sendMsg("login.complete", site, ret ? 1 : 0);
                        _login.destroy(ops);
                    }
                });
            },
            /**
             * 用浏览器的新窗口打开联合登录页面
             */
            mdlWin: function(site, ops, _shortCut) {
                //创建代理回调
                window[ops.callbackName] = function(errCode, union, userId) {
                    var errCodeMap = FORMS["163"].errCode,
                        errMsg = errCodeMap[errCode] || errCodeMap.x;
                    if (union == site) {
                        //关闭模态窗口
                        UnionEvents.closeWin(true);
                        if (errCode) {
                            $.sendMsg("login.fail", site, errCode, errMsg);
                            $.dialog.toast(errMsg);
                        } else {
                            //发送消息通知
                            $.sendMsg("login.success", site, userId);
                        }
                    } else {
                        //要处理吗？
                    }
                };

                // 要发送以下消息
                UnionEvents.openWin(ops.iframe, ops.winSize, function(ret) {
                    //放弃登录通知
                    if (ret === null) {
                        $.sendMsg("login.giveup", site);
                    }
                    if (ret !== "alt") { //不是更换登录，则通知完成
                        window.setTimeout(function() {
                            $.sendMsg("login.complete", site, ret ? 1 : 0);
                            _login.destroy(ops);
                        }, 1);
                    }
                });
                $.sendMsg("login.show", site, !!_shortCut);
            },

            /**
             * 弹出登录弹窗管理模块
             */
            _popCache: {},
            prepearWin: function(url, winSize) {
                //先关闭已有的登录弹窗
                this.closeWin("alt");
                var size = winSize || [750, 450],
                    doc = $(window),
                    docSize = [doc.width(), doc.height()],
                    //如果用户调用登录是在异步过程中，那么打开新窗口可能会存在问题，需要给出提示和引导
                    win = window.open(url, "loginPop", 'width=' + size[0] + ',height=' + size[1] + ',top=' + Math.max((docSize[1] - size[1]) / 2, 0) + ',left=' + Math.max((docSize[0] - size[0]) / 2, 0) + ',toolbar=no,menubar=no,scrollbars=no,resizable=yes,location=no,status=no,alwaysRaised=yes,depended=yes');
                return win;
            },
            openWin: function(url, winSize, callback) {
                var win = this.prepearWin(url, $.isArray(winSize) && winSize.length === 2 ? winSize : null);
                cb = function(ret) {
                    $.dialog(".loginNoticeDialog");
                    if ($.isFunction(callback)) {
                        callback(ret);
                    }
                };
                //没有返回弹窗句柄，则提示用户
                if (!win) {
                    var baidu = "http://www.baidu.com/s?wd=%E5%85%81%E8%AE%B8%E6%B5%8F%E8%A7%88%E5%99%A8%E5%BC%B9%E5%87%BA%E7%AA%97%E5%8F%A3&rsv_spt=1&issp=1&f=8&rsv_bp=0&rsv_idx=2&ie=utf-8&tn=baiduhome_pg";
                    $.dialog({
                        css: "iDialogInfo",
                        content: ["啊哦，登录弹窗被您的浏览器拦截了！<br><br>",
                            "请<a target='_blank' href='" + baidu + "'>允许 " + location.host + " 弹出窗口</a>，<br>或点击下面的按钮重试登录。"
                        ].join(""),
                        button: ["*重试登录", "取消"]
                    }).onBtnClick(function(btnId) {
                        if (btnId) { //重试登录
                            $.dialog(this.id, "retry");
                            UnionEvents.openWin(url, winSize, callback);
                        }
                    }).onClose(function(ret) {
                        if (!ret) { //放弃登录
                            cb(null);
                        }
                    });
                    return;
                } else {
                    //如果打开了弹窗，则页面给一个提示弹层
                    $.dialog({
                        title: null,
                        animate: 0,
                        css: "loginNoticeDialog",
                        content: "请在弹窗中完成登录。",
                        button: ["*显示弹窗", "取消"]
                    }).onBtnClick(function(btnId) {
                        if (btnId && win && !win.closed) {
                            win.focus();
                            return false;
                        }
                    }).onClose(function(ret) {
                        if (!ret) {
                            UnionEvents.closeWin();
                        }
                    });
                }
                var t = window.setInterval(function() {
                    if (win && win.closed) {
                        cb(null);
                        window.clearInterval(t);
                    } else if (!win) {
                        window.clearInterval(t);
                    }
                }, 200);
                //保存缓存
                this._popCache = {
                    win: win,
                    cb: cb,
                    t: t
                };
            },
            closeWin: function(ret) {
                var cache = this._popCache;
                if (cache.t) {
                    window.clearInterval(cache.t);
                }
                if (cache.win && !cache.win.closed) {
                    try {
                        cache.win.close();
                    } catch (e) {}
                    cache.cb(ret || null);
                    cache = {};
                }
            }
        };

    /**
     * 记录用户的登录状态
     */
    var loginStatus = (function() {
        var data = _login.cookie.checkCurrentStatus();
        _login.site = data.site;
        return {
            site: data.site,
            ursId: data.ursId || ""
        };
    })();
    $.bindMsg("login.success", function(site, ursId) {
        loginStatus.site = site;
        loginStatus.ursId = ursId;
        //保存用户名
        if (site === "163" && window.LS && window.LS.set) {
            LS.set("lastURSAccount", ursId);
        }
    });

    /**
     * 功能扩展
     */
    $.login = login;
    $.extend(login, {
        //修改登录表单配置
        setConf: function(siteName, config) {
            _login.setConf(siteName, config);
            return this;
        },
        //修改默认的登录表单
        setDefault: function(siteName, holdOnly) {
            _login.setDefault(siteName, holdOnly);
            return this;
        },
        //设置当前登录用户名
        //当通过ajax获得后端登录的状态的时候，可以同步设置
        setURSId: function(siteName, ursId) {
            var oldSite = loginStatus.site,
                oldStatus = loginStatus.ursId;
            //如果仅仅设置一个ursId，则自动查找所属的siteName
            if (/.+@.+/.test(siteName)) {
                ursId = siteName;
                siteName = "163";
                $.each(FORMS, function(site, conf) {
                    if (conf.ursAccountReg && conf.ursAccountReg.test(ursId)) {
                        siteName = site;
                        return false;
                    }
                });
            }
            //仅仅传递一个空参数，则强制注销登录
            if( !ursId && !siteName ){
                siteName = "163";
                ursId = "";
            }
            if (FORMS[siteName]) {
                _login.site = siteName;
                //检查一下是否跟现有的相同，如果相同，则不再消息通知
                if( oldSite == siteName && oldStatus == ursId ){
                    return;
                }
                loginStatus.site = siteName;
                loginStatus.ursId = ursId || "";
                if (ursId) {
                    $.sendMsg("login.success", siteName, ursId);
                }
                //如果是注销用户（即ursId为空），则act为2
                $.sendMsg("login.complete", siteName, ursId ? 1 : 2);
            }
            return this;
        },
        //获取当前的cookie中存储的用户名，如果是第三方登录，则获取的是第三方昵称或账号
        //包含部分项目业务逻辑
        getAccount: function() {
            var pinfo = _login.cookie.getPInfo(),
                account = pinfo ? pinfo.base : "",
                conf = FORMS[_login.site];
            //查找昵称
            if (conf.getNickName && conf.ursAccountReg) {
                var name = conf.ursAccountReg.test(account) ? conf.getNickName(account, pinfo) : null;
                if (name !== null) {
                    account = name;
                }
            }
            return account;
        },
        //获取当前登录的表单名
        getSite: function() {
            return _login.site;
        },
        //获取当前登录的用户名
        getURSId: function() {
            var pinfo = _login.cookie.getPInfo() || {};
            return loginStatus.ursId || pinfo.base;
        },
        //检查cookie是否登录
        isLogin: function(site) {
            site = site || _login.site;
            //当前登录站点，以缓存为准
            if( loginStatus.site == site ){
                return !!loginStatus.ursId;
            }
            //否则查询cookie
            var sinfo = _login.cookie.getSInfo(site),
                pinfo = _login.cookie.getPInfo(site),
                conf = FORMS[site];
            if (!conf) {
                return;
            }
            if (site === "163") {
                return !!(sinfo && pinfo && pinfo.base);
            }
            //有联合登录cookie，且账号是指定站点的账号
            return !!(sinfo && pinfo && pinfo.base && conf.ursAccountReg.test(pinfo.base));
        },
        close: function() {
            _login.dialog && _login.dialog.close();
            UnionEvents.closeWin();
        },
        //直接提交登录表单，非请勿用
        submit: function(username, password, callback) {
            //启动无刷新登录并提交表单
            if (username && password) {
                login({
                    site: "163",
                    callback: $.isFunction(callback) ? callback : undefined,
                    username: username,
                    password: password
                });
            }
            return this;
        },
        //获取其他登录入口，非请勿用
        insertUnionLinks: function(options, callback) {
            var ops = options || {};
            if (!ops.wrap) {
                return this;
            }
            //插入html代码
            var wrap = $(ops.wrap).html(_login.getUnionLinks("163", ops.backUrl));
            if (!wrap[0].initUnionLoginClick) {
                wrap[0].initUnionLoginClick = true;
                wrap.delegate(".thirdPartyLoginWrap a", "click", function() {
                    ops.dialog && ops.dialog.close();
                    login({
                        site: $(this).attr("rel") || "163",
                        callback: $.isFunction(callback) ? callback : undefined
                    });
                    return false;
                });
            }
            return this;
        },
        //获取回调，非请勿用
        _getCallback : function(){
            return (_login.orgOptions || {}).__callback;
        }
    });

    //ADM模块支持
    // if (typeof define === "function" && define.amd) {
    //     define("login", ["jquery", "baseClass", "baseLoad", "cookie", "format", "dialog"], function() {
    //         return $.login;
    //     });
    // }

})(window, window.jQuery);
/*-------login组件 end-------*/

/*------------easyNav组件--------------*/
(function(factory) {
    var $ = window.jQuery;
    factory(window, $, ($ || {}).login);
})(function(window, $, login, undefined) {
    //映射全局变量
    var document = window.document,
        uri = encodeURIComponent,
        currentUrl = document.URL,
        log = window.console ? function(t) {
            console.log(t);
        } : function() {};

    if (!$ || !login || !$.format || !$.safeHTML) {
        log("easyNav依赖的模块尚未加载!");
        return;
    }
    //默认配置
    var defaultConf = {
            //产品名称、标识、首页地址，会共享给登录组件
            appName: "",
            appId: "",
            home: window.location.protocol + "//" + window.location.host,

            //注册地址，会共享给登录组件
            regUrl: "http://reg.163.com/reg/mobileAliasReg.do?product={appId}&url=" + uri(currentUrl) + "&loginurl=" + uri(currentUrl),

            //登录、退出登录地址
            loginUrl: "javascript:Core.login('',{isTopLogin:true});",
            logoutUrl: "http://reg.163.com/Logout.jsp?username={username}&url=" + uri(currentUrl),

            //各种文案提示，支持占位符
            welcomeUser: "{time}好，{nameHolder}<span id='mailInfoHolder'></span>，欢迎来到{appName}！{logoutLink}",
            welcomeGuest: "欢迎来到{appName}！{loginLink} {regLink}",
            logoutTxt: "安全退出",
            loginTxt: "请登录",
            regTxt: "免费注册",

            //产品入口配置
            //如果设置为 null或false，则不显示产品入口，即nameHolder的位置
            //如果设置为 true 则强制显示产品入口
            //如果设置为空，则自动根据当前登录的站点类型显示（获取登录组件的nickName，并仅仅在网易邮箱帐号登录的时候显示产品入口）
            //支持函数配置，接受 siteName 参数
            funcEntry: true,

            //邮箱信息提示配置
            mailInfoConf: {
                infoTmpl: "( {0})",
                holderId: "mailInfoHolder"
            }
        },
        //网易产品入口配置
        Netease = {
            mails: {
                "163.com": "http://entry.mail.163.com/coremail/fcg/ntesdoor2?verifycookie=1&lightweight=1",
                "126.com": "http://entry.mail.126.com/cgi/ntesdoor?verifycookie=1&lightweight=1&style=-1",
                "yeah.net": "http://entry.yeah.net/cgi/ntesdoor?verifycookie=1&lightweight=1&style=-1",
                "188.com": "http://reg.mail.188.com/servlet/enter",
                "vip.163.com": "http://reg.vip.163.com/enterMail.m?enterVip=true",
                "vip.126.com": "http://reg.vip.126.com/enterMail.m"
            },
            entrys: [{
                    text: "进入我的通行证",
                    url: "http://reg.163.com/Main.jsp?username={ursId}"
                },
                function(data) {
                    if (!data || !data.username || data.username.indexOf("@") < 0) {
                        return;
                    }
                    var mailType = data.username.split("@")[1],
                        url = Netease.mails[mailType];
                    if (!mailType) {
                        return;
                    }
                    return {
                        text: "进入我的邮箱",
                        url: url
                    };
                }, {
                    text: "进入我的网易宝",
                    url: "http://epay.163.com/index.jsp#from=jsdh"
                }, {
                    text: "进入我的贵金属",
                    url: "http://fa.163.com/#from=jsdh"
                }, {
                    text: "进入我的彩票",
                    url: "http://caipiao.163.com/#from=jsdh"
                }, {
                    text: "进入我的车险",
                    url: "http://baoxian.163.com/car/#from=jsdh"
                }, {
                    text: "进入我的博客",
                    url: "http://blog.163.com/passportIn.do?entry={from}"
                }, {
                    text: "进入我的微博",
                    url: "http://t.163.com/"
                }
            ]
        },

        //对外的接口
        easyNav = {
            //初始化easyNav工具条
            init: function(config, wrap) {
                //必须初始化提供appName/appId两个个基础配置
                if (!config || !config.appName || !config.appId) {
                    log("easyNav配置错误：初始化缺少appName和appId.");
                    return;
                }
                //先设置登录组件
                login.setConf({
                    appName: config.appName,
                    appId: config.appId,
                    home: config.home || defaultConf.home
                });
                //再设置nav组件
                _Nav.setWrap(wrap);
                this.setConf(config);
                //同步登录模块用的几个url
                //仅仅对163登录生效
                var ops = _Nav.options;
                login.setConf("163", {
                    regUrl: ops.regUrl,
                    regTxt: ops.regTxt
                });
            },

            //修改配置，调用后会立即更新dom节点
            setConf: function(config) {
                _Nav.checkOptions(config);
                this.repaint();
            },

            //重绘nav条
            repaint: function() {
                if (!_Nav.wrap) {
                    return;
                }
                _Nav.wrap.empty().html(_Nav.getHTML());
                //绑定事件
                var entryList = $("#user163Box")[0];
                if( entryList ){
                    easyNav.bindDropMenu(entryList, $("#user163List")[0], "mouseover", "user163BoxActive", $.noop, 200);
                }
                //加载邮件信息
                var mailInfoBox = $("#"+ _Nav.options.mailInfoConf.holderId);
                if( mailInfoBox[0] ){
                    window.mailInfoConf = _Nav.options.mailInfoConf;
                    $(document.body).append("<"+"script id='__loadMailJS' src='http://pimg1.126.net/baoxian/js/mail.js?2014'></"+"script>");
                    window.setTimeout(function(){
                        $("#__loadMailJS").remove();
                    },100);
                }
            },

            //登录接口代理
            login: login,
            login2: function(callback, options) {
                return login(callback || $.noop, options);
            },

            //判断是否登录，前端判断不太靠谱，请在非关键流程中使用
            isLogin: function() {
                return login.isLogin();
            },

            //无刷新登录事件绑定，如果不传递参数，则重新检查登录
            //业务逻辑保持跟旧版类似
            onLogin: function(fn) {
                if ($.isFunction(fn)) {
                    $.bindMsg("login.success", fn);
                }
                if (login.isLogin()) { //如果已登录，则触发 fn
                    fn(login.getSite(), login.getURSId());
                }
                //更新状态栏
                easyNav.repaint();
            },

            //工具方法：绑定下拉菜单
            bindDropMenu: function(holder, list, method, activeCss, clickFn, delay, activeCallback, disableCallback) {
                var fn = clickFn || $.noop,
                    timer,
                    holderEvt = {
                        mouseout: function(e) {
                            var rel = e.relatedTarget || e.toElement;
                            if (rel !== this && !$.contains(this, rel) && rel !== list && !$.contains(list, rel)) {
                                if (!listIsSub) list.style.display = "none";
                                $(holder).removeClass(activeCss);
                                disableCallback && disableCallback();
                            }
                            timer && window.clearTimeout(timer);
                        }
                    },

                    //如果list在holder内，则通过样式控制其显示和隐藏
                    listIsSub = $.contains(holder, list);
                //添加holder事件监听
                holderEvt[method || "click"] = function(e) {
                    if (delay && (method || "").indexOf("mouse") >= 0) {
                        var me = this;
                        timer && window.clearTimeout(timer);
                        timer = window.setTimeout(function() {
                            timer = 0;
                            if (!listIsSub) list.style.display = "block";
                            $(me).addClass(activeCss);
                            activeCallback && activeCallback(e);
                        }, delay);
                    } else {
                        if (!listIsSub) list.style.display = "block";
                        $(this).addClass(activeCss);
                        activeCallback && activeCallback(e);
                    }
                    //如果是list中的click，则不阻止默认行为
                    $.contains(list, e.target) || e.preventDefault();
                };
                //bind event
                $(holder).bind(holderEvt);
                $(list).bind({
                    mouseout: function(e) {
                        var rel = e.relatedTarget;
                        if (rel !== this && !$.contains(this, rel) && rel !== holder && !$.contains(holder, rel)) {
                            if (!listIsSub) this.style.display = "none";
                            $(holder).removeClass(activeCss);
                            disableCallback && disableCallback(e);
                        }
                        timer && window.clearTimeout(timer);
                    },
                    click: function(e) {
                        var a = e.target.tagName.toLowerCase() == "a" ? e.target : e.target.parentNode.tagName.toLowerCase() == "a" ? e.target.parentNode : null;
                        //是否关闭菜单
                        if (!a || fn.call(a, e) !== false) {
                            if (!listIsSub) list.style.display = "none";
                            $(holder).removeClass(activeCss);
                            disableCallback && disableCallback(e);
                        }
                    }
                });
            }
        },
        //工具条私有数据
        _Nav = {
            //获取输出容器
            setWrap: function(wrap) {
                this.wrap = this.wrap || $(wrap || "#topNavLeft");
            },

            //检查配置
            checkOptions: function(options) {
                var ops = this.options || $.extend({}, defaultConf),
                    setOps = options || {};
                //复制options
                $.each(defaultConf, function(key, conf) {
                    ops[key] = setOps[key] !== undefined ? setOps[key] : ops[key];
                });
                //检查参数
                ops.loginUrl = ops.loginUrl || "javascript:easyNav.login()";
                //填充参数中的占位信息
                $.each(ops, function(key, value) {
                    if (typeof value === "string") {
                        ops[key] = $.format2(value, ops);
                    }
                });
                //保存原始配置
                this.options = ops;
            },

            //获取动态的显示数据
            checkData: function() {
                var ops = this.options;
                //计算其他页面输出的占位符
                this.data = {
                    //登录链接
                    loginLink: _Nav.getUrl(ops.loginUrl, ops.loginTxt),

                    //退出链接
                    logoutLink: _Nav.getUrl(ops.logoutUrl, ops.logoutTxt),

                    //注册链接
                    regLink: _Nav.getUrl(ops.regUrl, ops.regTxt),

                    //问候语
                    time: _Nav.getTimeDesc(),

                    //昵称
                    nickName: $.safeHTML(login.getAccount()),

                    //产品入口，网易邮箱帐号显示产品入口，其他默认不显示
                    //从登录组件要account进行显示
                    nameHolder: (function() {
                        var site = login.getSite(),
                            info = $.isFunction(ops.funcEntry) ? ops.funcEntry(site) : ops.funcEntry;
                        //如果强制不显示产品入口
                        if (info === null || info === false) {
                            return login.getAccount();
                        }
                        //如果强制显示产品入口
                        if (info === true) {
                            return _Nav.get163Entry(login.getAccount());
                        }
                        //如果设置了文案，则按照设置的文案显示
                        if (info) {
                            return $.safeHTML(info + "");
                        }
                        //否则，自动设置显示的文案
                        if (login.getSite() === "163") {
                            return _Nav.get163Entry();
                        }
                        //第三方登录，默认查询登录用户名
                        return $.safeHTML(login.getAccount());
                    })()
                };
            },

            //配置检查：注册链接/登录链接/退出链接
            getUrl: function(url, txt) {
                var u = /^javascript:/i.test(url) ? 'javascript:void(0);" onclick="' + url.substring(11) : url;
                //填充username
                u = $.format2(u, {
                    username: uri(login.getURSId())
                });
                return '<a href="' + u + '">' + txt + '</a>';
            },

            //生成html代码
            getHTML: function() {
                this.checkData();
                return $.format2(this.options[login.isLogin() ? "welcomeUser" : "welcomeGuest"], this.data);
            },

            //获得问候语
            getTimeDesc: function() {
                var hours = new Date().getHours();
                return hours > 5 && hours <= 11 ? "上午" : hours > 11 && hours <= 13 ? "中午" : hours > 13 && hours <= 17 ? "下午" : hours > 17 || hours <= 2 ? "晚上" : "凌晨";
            },

            //获得网易产品入口
            get163Entry: function(username) {
                var listHtml = ['<span id="user163Box">',
                        '<a href="{link}" target="_blank" hideFocus="true" id="user163Name" title="{username}"><em>{username}</em></a>',
                        '<i id="userBoxArrow"></i><div id="user163List">{list}</div></span>'
                    ].join(""),
                    listTmpl = '<a target="_blank" href="{url}">{text}</a>',
                    list = [];
                //遍历产品入口
                var data = {
                        ursId : $.safeHTML(login.getURSId()),
                        username: $.safeHTML(username || login.getURSId()),
                        from: _Nav.options.appId
                    },
                    currentDom = _Nav.tools.getUrlDomain();
                $.each(Netease.entrys, function(i, conf) {
                    var info = $.extend({}, $.isFunction(conf) ? conf(data) : conf);
                    if (info && info.text && info.url) {
                        //检查域名
                        var domain = info.domain || _Nav.tools.getUrlDomain(info.url);
                        if (!_Nav.tools.checkUrlDomain(currentDom, domain)) {
                            info.url = $.format2(info.url, data);
                            list.push($.format2(listTmpl, info));
                            //默认是显示第一个子链接的地址
                            if (list.length === 1) {
                                data.link = info.url;
                            }
                        }
                    }
                });
                data.link = data.link || "#";
                data.list = list.join("");
                return $.format2(listHtml, data);
            },

            //小工具函数，仅限内部使用
            tools: {
                getUrlDomain: function(strUrl) {
                    var url = (strUrl || document.URL).replace(/\?.*$/g, "").replace(/#.*$/g, "");
                    if (/^[^:]+:\/\/([^\/\?\#]+).*$/gi.test(url))
                        return RegExp.$1;
                    return url;
                },
                checkUrlDomain: function(urlDomain, domain) {
                    return (new RegExp(domain + "$", "i")).test(urlDomain);
                }
            }
        };

    //挂接window
    window.easyNav = easyNav;
    //注册登录组件事件 action: 1登录成功  2登录注销  0登录放弃
    $.bindMsg("login.complete", function(siteName, action) {
        action && easyNav.repaint();
    });
});
/*---------easyNav end----------*/

/*
 * 核心对象Core
 */
var Core = (function(window, $, undefined){
	 var a = function() {
        if ($.cookie("S_INFO"))
            return;
        if ("58tc" == $.cookie("unionLogin"))
            return {site: "58",regUrl: "http://open.hezuo.58.com/user/reg?referer=" + encodeURIComponent(document.URL)}
    }();
	 ! function() {
		 var getNickName = function(e) {
			 var n = $.cookie("BXThirdPartyInfo") || "{}",
				 name;
			 try {
				 name = JSON.parse(n).nickName || null;
			 } catch (o) {
				 name = null;
			 }
			 return name;
		 };
		 $.login.setConf("qq", {
			 //unionTrans: "http://caipiao.163.com/third/qq_login.html?gotoUrl={0}",
			 getNickName: getNickName
		 }).setConf("weixin", {
			 //unionTrans: "http://caipiao.163.com/third/weixin_login.html?gotoUrl={0}",
			 getNickName: getNickName
		 }).setConf("58", {
			 getNickName: function(e) {
				 return "58同城用户" + e.replace(/@.+$/, "")
			 }
		 }).setConf({
			 useIframeForUnion: false
		 });
		 if (a && a.site)
			 $.login.setDefault(a.site, true)
	 }();

var Core = {
	/*
	 * UI版本
	 */
	version : "1.1",
	domainUrl: "http://baoxian.163.com",
	
	/*
	 * 内存强制回收函数引用
	 */
	GC : window.CollectGarbage || $.noop,
	
	/*
	 * 日志输出接口
	 */
	log : $.getPara("debugger") && window.console ? window.console.log || $.noop : $.noop,
	
	/*
	 * 业务配置信息对象/缓存对象，由页面自定设置
	 */
//	config : {},
//	helper : {},
//	cache : {},

	/*
	 * 时间戳
	 */
	now : function(){return (new Date).getTime();},
	
	/*
	 * 业务配置信息对象/缓存对象，由页面自定设置
	 */
	navConfig : {
		appName : "网易保险",
		appId : "baoxian",
		logoutUrl: "/logout.html",
		funcEntry: true
	//	loginConfig : {
	//		iframeAgent : "/loginAgent.htm",
	//		submitUrl : "https://reg.163.com/logins.jsp?type=1&product=baoxian",
	//		keyNames : {
	//			user : "ursLoginUserName",
	//			pass : "ursLoginUserPass"
	//			//url : "goingToUrl"
	//		},
	//		hiddenData : {}
	//	}
	},
	//justInit为私有参数
	navInit : function( cdn, userId, versionId, justInit ){
		//引用easyNav
		this.easyNav = window.easyNav;
		//保存变量
		this.cdnUrl = cdn;
		this.version = versionId || this.version;
		//初始化
		this.easyNav.init(this.navConfig);
		//当获取到后端传递的登录用户名后，调用这个接口更新组件登录信息
		$.login.setURSId(userId || "");
		//删除初始化方法
		delete this.navInit;
	},
	/*
	* 保险手机登录头部初始化函数，跟navInit类似，此方法只会在页面手机登录的情况下调用，其它情况仍然用navInit方法初始化
	* 为了跟网易邮箱帐号的不要混的太严密，故而增加此方法
	*/
	mobileNavInit: function(cdn, mobile, versionId){
		//引用easyNav
		this.easyNav = window.easyNav;
		//保存变量
		this.cdnUrl = cdn;
		this.navConfig.loginUrl = this.navConfig.loginUrl || ("@"+ cdn +"/js2");
		this.version = versionId || this.version;

		//初始化
		//this.easyNav.init(this.navConfig);
		this.loginMobile = mobile; //保存登录手机号
		this.onMobileLogin("", true);

		//删除初始化方法
		delete this.mobileNavInit;
		//删除初始化方法
		delete this.navInit;
	},
	//手机登录后修改头部欢迎语 isInit:表示是否是页面初始化， true表示页面初始化，此时应该用document.write来显示
	onMobileLogin: function( callback, isInit ){
		var welcomeStr = '{time}好，<b>{mobile}</b>，欢迎来到{appName}！<a href="{logoutUrl}">安全退出</a>', html = "",
			//分析问候语
			hours = new Date().getHours(),
			sayHello = hours > 5 && hours <= 11 ? "上午"
					:hours > 11 && hours <= 13 ? "中午"
					:hours > 13 && hours <= 17 ? "下午"
					:hours > 17 ||  hours <= 2 ? "晚上"
					:"凌晨";
		html = $.format(welcomeStr, $.extend({},this.navConfig, {mobile:this.loginMobile, time:sayHello}) );	
		if(isInit){
			document.write( html );	
		}else{
			$('#topNavLeft').html( html );
		}
		if($.isFunction(callback)){
			callback(this.loginMobile);	
		}
	},
	/*
	 * 快速初始化入口，在页面底部即被执行
	 * 2012-09-25 马超 删除quickInit修改为由fastInit方法调用
	 */
	fastInit : function( quickInit ){
		
		this.initNav()
			.initBindGuide() //绑定全局绑定邮箱引导
			.initMenu()   //初始化导航菜单
			.initFromCookie()  //这个函数是用来读取页面中#from的参数并复制给cookie from，后台用来统计来源的
			.initSearch(); //搜索初始化	
		//调用其他快速初始化
		quickInit && this.quickInit && this.quickInit();
		//由于非车这边很多页面底部有quickInit方法调用，所以fastInit方法的参数没有传，为了能够快速调用页面某一方法，增加qkInit方法
		this.qkInit && this.qkInit();
		//删除
		delete this.fastInit;
	},
	//在手机号登录情况下，dom节点如果有自定义属性needGuid="1"属性，则阻止默认行为，主要还是一些链接的跳转行为。弹出绑定弹出
	initBindGuide: function(){
		$(document.body).delegate("[needGuid=1]", "click", function(e){
			var loginObj = Core.isLogin();
			if(loginObj.status == 0){
				Core.showBindGuid(loginObj.account);
				e.preventDefault();
				return false;	
			}
		});
		return this;
	},
	//这个函数是用来读取页面中#from的参数并复制给cookie from，后台用来统计来源的用
	initFromCookie: function(){  
		var from = $.getHashPara("from");
		if(from && !$.cookie("FROM")){
			$.cookie("FROM", from, {path:"/", expires:30});	
		}
		return this;
	},
	/*
	 * 无刷新重绘nav
	 */
	repaint : function(){
		Core.loadCdnJS("js/mail.js", $.falseFn, $.noop);
	},
	/*
	 * 初始化入口
	 */
	init : function(){
		//初始化工具条，防止navInit未被调用
		//2012-07-17 马超 修改默认处理的默认版本号为当前时间戳以获取最新前端资源
		this.navInit && this.navInit("http://pimg1.126.net/baoxian", "", +new Date(), true);
		//动态事件绑定
		$(document).delegate("a[href*=#Core]", "click", this.autoHashClick);
		this.initNewDialogEv(); //初始化新弹出框关闭按钮事件
		//检查是否需要弹广告，add 2014-03-19
		this.checkAd();
		//各个页面独立的初始化任务
		this.myInit();
		
		//通用点击三态效果
		//无刷新登录顶部NAV事件响应
		//this.easyNav.onLogin( this.repaint );
		
		//页面卸载以及尺寸变化
		this.unload != $.noop && $(window).unload($.proxy(this.unload, this));
		this.resize != $.noop && $(window).resize($.proxy(this.resize, this));
		this.beforeUnload != $.noop && $(window).bind("beforeunload", $.proxy(this.beforeUnload, this));
		//主动激发一次resize事件
		window.setTimeout(function(){$(window).resize()},0);
		
		//删除过期成员
		delete this.init;
		this.quickInit && delete this.quickInit;
		//内存垃圾回收
		this.GC();
	},
	/*显示更多内容*/
	showMoreInfo: function(obj, id){
		if($(obj).text() == "[显示更多内容]"){
			$(obj).text("[收起更多内容]");
			$("#"+id).show();
		}
		else{
			$(obj).text("[显示更多内容]");
			$("#"+id).hide();
		}	
	},
	//初始化二级菜单
	initMenu: function(){
		var nav_ul = $("#nav_ul"), timer, tm;
		if(nav_ul[0]){
			if( Core.pageLoc != "index" ){
				nav_ul.find("li:eq(0)").mouseenter(function(){
					var self = $(this);
					tm && window.clearTimeout(tm);
					tm = window.setTimeout(function(){self.addClass("category_open");},200);
				}).mouseleave(function(){
					tm && window.clearTimeout(tm);
					$(this).removeClass("category_open");
				});
			}
			nav_ul.find(".bxList li").mouseenter(function(){
				var obj = $(this);
				if(obj.find(".sec_menu").length){
					timer && window.clearTimeout(timer);
					timer = window.setTimeout(function(){obj.addClass("hover");},200);
				}
			}).mouseleave(function(){
				var obj = $(this);
				timer && window.clearTimeout(timer);
				if(obj.hasClass("hover")){
					window.setTimeout(function(){obj.removeClass("hover");},200);
				}	
			});
		}
		return this;
	},
	/*
	 * 顶部工具条初始化
	 */
	initNav : function(){
		//下拉菜单
		$(".mcDropMenuBox").each(function(){
			window.easyNav.bindDropMenu(this, $(".mcDropMenu", this)[0], "mouseover", "dropMenuBoxActive", Core.navMenuClick);
			$(".topNavHolder",this).click(Core.navMenuClick);
		});
				
		//检查是否需要打开登陆窗
		if( $.getUrlPara("isShowLogin") && !this.easyNav.isLogin() )
			this.easyNav.login();
		//客服电话弹层
		$("#kfLink").bind("mouseover",function(){
			$("#sv_popo").show();
		}).bind("mouseout",function(){
			$("#sv_popo").hide();
		});
		
		return this;
	},
	navMenuClick : function(e){
		if( !!$(this).attr("user") && !Core.easyNav.isLogin() ){ //需要登录验证
			Core.easyNav.login( this.href );
			e.preventDefault();
			return;
		}
	},
	//各个页面独立的初始化任务，需要在页面中覆盖
	myInit : $.noop,
	//页面卸载任务
	unload : $.noop,
	//页面尺寸变化
	resize : $.noop,
	//窗口关闭前处理
	beforeUnload : $.noop,
	
	/*
	 * 自动绑定的click事件处理
	 * html结构：<a href="#Core.openMenu:1">...</a>
	 * this --> link
	 */
	autoHashClick : function(e){
		if( this.disabled || e.shiftKey || e.altKey || e.ctrlKey )return false;
		if( !/^#Core\.([^\:]+):?(.*)$/.test(this.hash) )
			return;
		//定位
		var method = RegExp.$1.split("."),
			para = RegExp.$2 ? RegExp.$2.split(",") : [],
			i, n, fn = Core, $this = fn;
		//将事件对象作为参数追加到参数列表中
		para.push(e);
		//查找方法
		n = method.length;
		for(i=0; i<n; i++){
			$this = fn;
			fn = fn[method[i]];
		}
		//执行
		return fn.apply($this, para);
	},
	
	/*
	 * 函数执行保护
	 * 2013-04-19 马超 增加
	 * key 设置为 @ 开头的字符串时，将采用后到优先的模式，否则就是先到优先模式
	 * [警告]所有原函数的返回值将不能被处理！
	 */
	getProtectedFn : function(fn, key, time){
		return $.getProtectedFn(fn, key, time, this);
	},
	/*
	 * 限制函数递归
	 * 2013-05-07 曹建雄 增加
	 * fn [必选]一个函数， 调用该方法后将返回一个新的函数，这个函数会做 fn做的事情，但是这个方法无法被递归，一旦递归则会返回owner或者 windo
	 * owner 函数所有者
	 * [警告]，fn中如果有异步处理，该方法则会失效
	 */
	getStopRecursionFn: function(fn, owner) {
		return $.getStopRecursionFn(fn, owner);
	},
	/*
	 * 转化日期数字为指定格式
	 */
	formatTime : function( timeNum, tmpl ){return $.formatTime(timeNum, tmpl);},
	
	/*
	 * 字符串转化为json对象，适用小数据量转化
	 * 此处不对字符串进行安全检查，也不处理前后空格
	 * 将\/Date(...)\/格式的外层斜线去掉以供js使用
	 * $.parseJSON 也可进行json格式化，但是对输入检验比较严格，可以根据实际情况选择使用
	 */
	parseJSON : function(data){
		data = data.replace(/("|')\\?\/Date\((-?[0-9+]+)\)\\?\/\1/g, "new Date($2)");
		return (new Function("return " + data))();
	},
	
	/*
	 * 添加到收藏夹
	 */
	addFav : window.sidebar && window.sidebar.addPanel ? function(url, txt){ window.sidebar.addPanel(txt, url, "");} : function(url, txt){
		try{window.external.addFavorite(url,txt);}catch(e){window.alert("您的浏览器不支持自动添加到收藏夹，请按 Ctrl+D 键手动添加！");};
	},
	
	/*
	 * 发送GET类型的http请求
	 * 可根据类型参数来控制并发冲突，如果key是一个以 @开头的字符串，则表示去掉上一个同类型的ajax，否则就取消本次ajax除非上一个ajax完成
	 */
	get : function( url, data, callback, key ){ return ajax("GET", url, data, callback, key); },
	
	/*
	 * 发送POST类型的http请求
	 */
	post : function( url, data, callback, key ){ return ajax("POST", url, data, callback, key); },
	
	/*
	 * 获得指定域代理页面的jQuery对象
	 */
	agent$ : function( domain, callback ){
		createAgent(domain, callback);
		return this;
	},
	
	/*
	 * 加载cdn资源
	 */
	loadCdnJS : function(url, chkFn, callback){
		//如果是数组，则逐个补足url
		var arr = [], cdnUrl = this.cdnUrl +"/";
		if( $.isArray(url) ){
			$.each(url, function(i, u){
				arr[i] = cdnUrl + u;
			});
		}else{
			url = cdnUrl + url;
		}
		return this.loadJS(arr.length ? arr : url, chkFn, callback);
	},
	loadCdnCss : function(url){ return this.loadCss(this.cdnUrl+"/"+url);},
	
	/*
	 * 加载javascript
	 */
	loadJS : function(url, chkFn, callback, charset, cdnURL){
		$.loadJS(url, chkFn, callback, charset, cdnURL);
		//返回Core
		return this;
	},
	/*
	 * 加载样式表
	 */
	loadCss : function(url, cdnURL){
		$.loadCss(url, cdnURL);
		return this;
	},
	/*跳转 代替custom.js中的jumpto方法*/
	jumpTo: function( url ){
		if(!easyNav.isLogin()){
			easyNav.login( function(){
				window.location = url;	
			});
		}else{
			window.location = url;		
		}	
	},
	filterToNum: function(val){
		return +val.trim().replace(/\D/g, "");
	},
	/*通用信息提示框*/
	message: function( msg ){
		$.dialog({
			title : "温馨提示",
			width : 444,
			content : "<p>"+msg+"</p>",
			css: "iDialogInfo",
			button : ["*确定"]
		})	
	},
	//仅支持content是字符串型，插入的情形请各自包含头
	initNewDialogEv: function(){
		//弹框关闭按钮
		$("body").delegate(".new_dialog_box .close","click",function(e){
			$.dialog();
			e.preventDefault();
		});	
	},
	//此方法后续不再用到，如果想用到最新的弹框样式，直接配置css: "newiDialog"即可
	newDialog: function(op){
		if(op && op.content && typeof op.content == "string"){
			return $.dialog($.extend({}, op,{
				type: "shell",
				content: '<div class="new_dialog_box" style="width:' + (op.width || 460) + 'px">' + (op.title ? '<h2>' + op.title + '</h2>' : '') + '<a href="javascript:;" class="close"></a>' + op.content + '</div>',
				width: 0	
			}));
			//$("#" + p).find(".new_dialog_box .close", function(){ $.dialog();});
		}
	},
	/*
	* 背投广告 param为传入的参数，不能为空  可为对象 或 字符串
	* param：如果为字符串则认为是网址，如果为对象则可包含{url:url,width: width, height:height, top:top, left:left}
	*/
	backAd: function( param ){
		var top=window.screenTop, left=window.screenLeft, data = {}, sFeatures;
		if(!top){
			top = window.screenY; 
			left=window.screenX;
		}
		if(typeof param == "string"){ //如果是字符串则认为是地址
			data.url = param;
		}else{
			data = param;	
		}
		sFeatures = "width=" + (data.width || 400) + ", height=" + (data.height || 400) + ",toolbar=yes,scrollbars=yes, menubar=yes, resizable=yes,location=yes, status=yes, top=" + (data.top || top) + ", left="+ (data.left || left);
		
		this.popTao = window.open(data.url, "_blank", sFeatures); 

		if(this.popTao){ 
			this.popTao.blur();
			if(this.popTao.opener){ //opera下背投被拦截时存在this.popTao 但是不存在this.popTao.opener。所以要加判断
				this.popTao.opener.focus();
				window.setTimeout(function(){
					Core.popTao.opener.focus();	
				},100);
			}
		}
	},
	//显示续保按钮提示弹层
	showXbTip: function( tbButton ){
		tbButton = tbButton || $("#tbButton") || $(".tbButton");
		if(tbButton && tbButton[0]){
			tbButton.mouseover(function(){
				if(tbButton.hasClass("disabled")) return;
				var tbOffset = tbButton.offset(), top = tbOffset.top + tbButton.outerHeight() + 9, left = tbOffset.left + tbButton.width()/2;
				var obj = $('<span style="width:180px;text-align:left;" class="popo_corp_b" id="xubaoTip"><em></em>续保上年保险公司，只需车牌，两步完成，价格更优惠</span>').appendTo(document.body);		
				left = left - obj.outerWidth()/2;
				obj.css({top: top, left: left,"z-index":10})	
			}).mouseout(function(){
				$("#xubaoTip").remove();	
			});	
		}	
	},
	//全站搜索提示
	initSearch: function(){
		var mvTopSearch = $("#bx_topSearch");
		if(mvTopSearch.length){
			var top_sform = $("#top_sform");
			this.focusBlurTip(mvTopSearch,"搜“精心优选”试试，精算师推荐的保险");
			top_sform.submit( this.topToSearch );
			mvTopSearch.keyup(function(e){
				if(e.keyCode == 13){
					top_sform.submit();	
				}
			}).autoSearch("http://baoxian.163.com/search_tip.html?keywords={key}&callback={callback}", function(data){
			//mvTopSearch.autoSearch("http://suggestion.baidu.com/su?wd={key}&p=3&cb={callback}&t={cache}", function(data){
				//var data = {keywords:"aa", list:[{name:"dfsdsafs", count:2},{name:"车险", count:12,url:"http://www.baidu.com"}]}
				var key = data.keywords, list = data.list, arr = [], v = this.value.trim(), n = list.length, i=0, reg, url;
				if( key === v ){
					//reg = new RegExp("^(.*)"+ $.safeRegStr(key) +"(.+)$");
					reg = new RegExp("^(.*)("+ $.safeRegStr(key) +")(.*)$","i");
					for(; i<n; i++){ 
						arr[i] = {
							text : list[i].name,
							value : list[i].url ? list[i].url : "javascript:;",
							textShow : '<span class="ss_count">约' + list[i].count + '款产品</span><span class="ss_pname">' + list[i].name.replace(reg,"$1<b>$2</b>$3") + '</span>'
						};
					}
				}
				return arr;
			},{
				sameWidth:true,
				listCss:"searchTopCss",
				itemTmpl : '<a class="{itemCss}" href="{value}" title="{text}">{textShow}</a>',
				onHide : function( reason, text, value ){
					if( reason == "inputConfirm" ){
						if(value != "javascript:;"){
							window.open(value);
						}else{
							top_sform.submit();	
						}
					}
					$(this).parent().removeClass("sousuo_active");
				},
				//defaultShow: '<a title="综合意外" href="javascript:;" class="autoListItem"><span class="ss_count">约14款产品</span><span class="ss_pname">综合意外</span></a><a title="意外" href="javascript:;" class="autoListItem"><span class="ss_count">约19款产品</span><span class="ss_pname">意外</span></a><a title="交通意外" href="javascript:;" class="autoListItem"><span class="ss_count">约6款产品</span><span class="ss_pname">交通意外</span></a><a title="旅行" href="javascript:;" class="autoListItem"><span class="ss_count">约3款产品</span><span class="ss_pname">旅行</span></a><a title="自驾" href="javascript:;" class="autoListItem"><span class="ss_count">约4款产品</span><span class="ss_pname">自驾</span></a>',
				onShow: function(f){
					$(this).parent().addClass("sousuo_active");
				}
			});
		}
		return this;
	},
	topToSearch: function(){
		var val = $("#bx_topSearch").val().trim();
		if(val){ 
			if(val.indexOf("精心优选") !=-1){
				window.location =  "http://baoxian.163.com/product/6006.html"; //去往每周特价页面
			}else{
				window.location =  "http://baoxian.163.com/search.html?keywords=" + encodeURIComponent(val);
			}
		}
		return false;
	},
	/*
	 * 输入框提示语函数，txt默认显示在输入框里面的值，focus时文字消失，如果没有改变任何内容失去焦点的时候文字显示
	 * className 显示提示文字时候的样式
	 */
	focusBlurTip: function(obj, txt, className){
		if(!obj[0]) return;
		var domObj = obj[0], input = $(domObj);
		className = className || "gray2";
		input.blur(function(){
			if(this.value.trim() == ""){
				domObj.value = txt;
				input.addClass(className);
			}
		}).focus(function(){
			if(this.value.trim() == txt ){
				domObj.value = "";	
			}
			input.removeClass(className);
		});
		//初始化检查
		var v = domObj.value.trim() || txt;
		input[v == txt?"addClass":"removeClass"](className);
		domObj.value = v;
	},
	//获取图片验证码地址
	changeVerifyCode: function( imgId ){
		if(!imgId) return;
		//用<img>实现，修改<img src=url>的url
		$("#"+imgId)[0].src="http://baoxian.163.com/imageCode/getImageCode.html?t="+new Date().getTime();
	},
	//获取图片校验码校验地址
	getImgVerifyUrl: function(){
		return "/imageCode/verifyImageCode.html";
	},
	//获取二维码图片
	getQrCode: function(url, size, type){
		return "/imageCode/getQrCode.html?type="+(typeof type == "string" ? type : "weixin")+"&url="+encodeURIComponent(url)+"&size="+size;
	},
	/*插入flash*/
	insertFlash: function(o, n, p) {
		var q = $.isFunction(p) ? p($.easyFlash.support, $.easyFlash.version) : null;
		if (q !== false) {
			$.easyFlash.insert(o, n)
		}
	},
	/*
	 * 绑定复制文本功能
	 * txt 可以为静态文本，也可以是函数（需要return一个字符串）
	 */
	zclip : function( button, txt, callback ){
		var btn = $(button), swfPath = this.domainUrl + "/swf/ZeroClipboard.swf";
		//优先调用window的接口，以防止杂牌Ie内核浏览器的flash复制无效
		/*if( window.clipboardData ){
			btn.click(function(){
				var ok = true, t = $.isFunction(txt) ? txt() : String(txt);
				try{
					window.clipboardData.clearData();
					window.clipboardData.setData("Text", t);
				}catch(e){
					ok = false;
				}
				callback && callback(ok);
			});
			return true;
		}*/
		//否则调用flash完成复制功能
		this.loadCdnJS("js/zClip.js", function(){
			return !!$.fn.zclip;
		},function(){
			btn.zclip({
				path : swfPath,
				copy : txt,
				afterCopy : callback
			});
		});
	},
	/*
	 * 发送一个请求到后台，不处理返回结果
	 */
	emptySendHttp : function(url) {
		var n = "imgLoad_"+ (+new Date()) + parseInt(Math.random()*100), _img, sp;
		_img = window[n] = new Image();
		_img.onload = function(){window[n] = null;};
		_img.onerror=function(){window[n] = null;};
		url = url.replace(/#\S*$/, '');
		sp = (url+"").indexOf("?")+1 ? "&" : "?";
		_img.src = url + sp + 'd=' + (+ new Date());
		_img = null;
	},
	/*
	* 检测首页和车险首页大屏广告是否弹出  
	* 该广告不能跟新手弹出层同时弹出
	*/
	checkAd : function(){
		var lib = this,
		fn = function(){
			var conf = window.adConfig, hasAd, pageId = lib.pageId4Ad, cfg;
			if( conf && pageId ){
				$.each(conf, function(i, info){
					//检查必要参数
					if( info.id && info.png && info.url ){
						info.page = +(info.page || 3);
						//位与，如果等于1则表示当前页要显示
						if( info.page & pageId ){
							lib.showPngAd( info );
						}
					}
				});
			}
		};
		if(this.pageId4Ad){
			if(this.pageId4Ad == 2 && $.cookie("hasVisited") != 1){ //车险首页有新手引导，引导成和广告只弹出一个，先弹出广告
				//引导层调用  这个函数从页面调整到easycore上来
				Core.guidManage.init();
			}else{
				$.cookie("hasVisited") == 1 && $.cookie("hasVisited","1",{expires:365, path:"/"}); //将新手引导层的cookie设置时间改长点
				//this.loadJS(["http://127.0.0.1/html/globalConfig.html", this.cdnUrl +"/js/car/hello.js"], function(){
				this.loadJS([this.domainUrl + "/car/getAdLayerInfo.html", this.cdnUrl +"/js/car/hello.js"], function(){
					fn();	
				});		
			}
		}
		return this;
	},
	//向后台发送请求获取获取登陆状态 callback:如果callback为函数的话则是ajax请求获取登录状态，不是则是前端自行判断cookie
  isLogin : function(callback ){
		if($.isFunction(callback)){
			/*status：
			-1： 未登录
			0： 手机登录 mobile手机登录时返回手机号
			1：网易邮箱帐号登录， account表示登录的网易邮箱帐号
			2：二者都登录了，这种情况就按照1的情况处理吧？ 
			*/
			this.get("/mlogin/getLoginStatus.html", function(hasErr, rs){
				//如果通讯失败，则检查cookie
				rs = hasErr ? {status:-1} : rs;
				if($.isFunction(callback)){
					callback.call(this, rs.status,  rs.account || rs.mobile || "");
				}
			});
		}else{
			var rs = {status:-1, account:""};
			if(easyNav.isLogin(true)){ //网易邮箱帐号登录
				rs = {status:1, account: $.login.getAccount()}
			}else if($.cookie("NTES_BX_MOBILE_OSESS")){ //手机号登录NTES_BX_MOBILE_OSESS m+手机号+@mobile.com##加密串
				rs = {status:0, account:$.cookie("NTES_BX_MOBILE_OSESS").substr(1,11)};
			}
			return rs;	
		}
	},
	/*-------------以下几个函数式针对登录的，网易邮箱帐号绑定和注册会单独加载一个js，必要时再加载---------------------*/

	/* 
	 * 保险头部登录函数， 头部导航登录改为tab选择，虽然是tab，但是实际上是两个弹框，有手机登录和网易邮箱帐号登录两种方式，如果按照老方式登录，仍然可以用easyNav.login登录
	 * op对于与login.js里面的相关配置参数，这里增加一个isTopLogin：标记是否是头部登录，如果是头部登录则手机登录完后需要调用引导层
	 */
	login: function(backurl, op){
		var core = this;
		//$.dialog();
		//2015年11月18日应产品任亚梅的要求，手机登录淡化入口
		
		//如果login的第一个参数不是字符串则自定义的caption就不会生效
		easyNav.login(backurl || "",{
			title: 	"登录",
			animate:0,
			footadd: '<div id="MBTAB"><a href="javascript:;">手机号登录（仅限老用户）</a></div>'
		});	
		$("#MBTAB a").click(function(){
			var callback;
			if(op && op.isTopLogin){
				callback = function(mobile){
					core.showBindGuid(mobile, 0);
				}	
			}else{
				callback = backurl;
			}
			Core.mobileLogin(op, callback);	
		});
	},
	/*
	* op: 对象 主要是dialog弹框需要的一些配置参数, 如果op是函数，则mobilePopLogin中会将其赋值为回调函数
	*/
	mobileLogin: function( op, callback ){
		$.dialog();
		if(this.mobilePopLogin){
			this.mobilePopLogin(op, callback );
		}else{
			this.loadCdnJS("js/login/mobileLogin.js", function(){
				Core.mobilePopLogin( op,callback );
			});
		}
	},
	/*
	* 显示绑定弹出 
	* type: 0或1; 0表示头部导航手机登录后进入绑定，1则为页面操作需要绑定，现在主要是投保礼对话页面
	* 		type不传值则默认为1，也就是页面操作类型的手机绑定
	*/
	showBindGuid: function(mobile, type, callback){
		type = typeof type != "undefined" ? type : 1;
		if(!Core.BindManage){
			Core.loadCdnJS(['js/placeholder.js','js/login/bindGuid.js'],function(){
				Core.BindManage.show({mobile:mobile, source:type, callback: callback});
			});
		}else{
			Core.BindManage.show({mobile:mobile, source:type, callback: callback});	
		}
	},
	//站内通用系统繁忙弹出层
	showBusyPop: function(){
		$.dialog({
			title: "&nbsp;",
			css: "zqNewDialog",
			width:480,
			button: "",
			content: '<div class="p-busyMain"> <h1 class="p-busyTit">系统繁忙</h1> <p class="p-busyDesc">对不起，系统繁忙，请您稍后再试</p> <a class="p-popBtn" onclick="jQuery.dialog();">我知道了</a> </div>'
		});
	}
},
/*
 * 创建跨域代理iframe
 * 需要在相应的域名根目录下放置 agent.htm（如 http://caipiao.163.com/agent.htm）
 */
altDomain = function( domain ){
	var d = (domain+"").toLowerCase(), i = d.indexOf("http");
	return i<0 ? /\.163\.com$/.test(d) ? d : ""
			   : i ? ""
			       : d.replace(/^https?:\/\//,"").replace(/\/.+$/,"");
},
agentCache = {},
createAgent = function( domain, callback ){
	var key = altDomain(domain), host = window.location.host+"", agent = agentCache[key], url = domain.replace(/https?:\/\/[^\/]+?/,"\1")+"/agent/ajaxAgent.htm";
	if( !key || key == host ){ //如果没有指定特殊的域名或是当前域名，则直接返回
		callback( $ );
		return;
	}
	if( agent ){ //如果已经创建了代理，则返回代理页面的 jQuery
		callback( agent );
		return;
	}
	//创建代理页面
	if( !document.body ){
		window.setTimeout(function(){ createAgent(domain, callback) }, 1);
		return;
	}
	var frame = $("<iframe scrolling='no' frameborder='0' width='0' height='0'/>")
	.insertAfter(document.body)
	.bind("load", function(){
		var agent = agentCache[key] = frame[0].contentWindow.jQuery;
		agent ? callback( agent ) : alert("跨域代理文件错误！<br/>"+ escape(url));
	}).attr("src", url);
},

/*
 * Core.get / Core.post 支持函数，私有
 * 当key为 @ 开头的字符串时，并发处理策略是：取消前一个未完成的ajax请求，然后发送新的ajax请求，否则取消当前函数；
 * 2012-06-12 马超 增加跨域ajax请求的处理
 * 2012-09-20 马超 完善ajax包装
 */
httpCache={},
ajax = function(type, url, data, callback, key){
	var host = window.location.host+"", domain = altDomain(url) || host, reg = /\.163\.com$/i, protocol = "http:", port = "80", fn;
	//分析url的访问协议
	if( /^(https?:)/i.test( url ) ){//如果指明了访问协议，则检查协议和端口号
		protocol = RegExp.$1.toLowerCase();
		if( /:(\d+)/i.test( url ) )
			port = RegExp.$1 || "80";
	}else{ //否则url是相对路径，则协议和端口都OK
		protocol = window.location.protocol;
		port = window.location.port || "80";
	}
	//如果访问协议和端口号不一致，则直接忽略此次ajax请求
	if( window.location.protocol != protocol || (window.location.port||"80") != port ){
		fn = $.isFunction(callback) ? callback : $.isFunction(data) ? data : $.noop;
		fn.call(Core, 2, "", "protocols or ports not match");
		return;
	}
	//同在163.com主域下才可以跨域处理，否则一律转化为相对路径访问
	//只有在http协议下才启用跨域代理
	if( reg.test( domain ) && reg.test( host ) && document.domain == "163.com" && protocol == "http:" ){
		createAgent(domain, function( jq ){
			_ajax(jq, type, url, data, callback, key);
		});
	}else{ //转化为相对路径
		_ajax(jQuery, type, url.replace(/https?:\/\/[^\/]+/, ""), data, callback, key);
	}
},
_ajax = function(jq, type, url, data, callback, key){
	var fn = $.isFunction(callback) ? callback : $.noop, URL = url, xhr, state, lib = Core, noCache = false, cachePara = (URL.indexOf("?")>=0 ? "&" : "?") +"cache="+ (+new Date());
	if( $.isFunction(data) ){
		fn = data;
		data = null;
		key = callback;
	}
	if( key && key.indexOf("*") == 0 ){ //无缓存
		noCache = true;
		key = key.substr(1);
	}
	if( key ){
		xhr = httpCache[key];
		if( xhr ){
			//普通并发处理，直接取消当前处理
			if( key.indexOf("@") !== 0 )
				return;
			//否则，取消上一个未完成的ajax请求
			state = xhr.readyState;
			if( state > 0 && state < 5 ){
				//IE9' abort bug, see more:
				//http://www.enkeladress.com/article.php/internetexplorer9jscripterror
				try{
					xhr.aborted = true;
				}catch(e){} //防止IE6报错
				xhr.abort();
			}
		}
	}
	//发送
	xhr = jq.ajax({
		url: URL + (noCache ? "" : cachePara),
		type: type,
		data: data,
		success : function( txt, status, res ){
			//主动删除缓存
			delete httpCache[key];
			//如果请求被取消，则不进行任何处理
			if( res.aborted )
				return;
			//无法连接服务器（返回空数据）被认为是错误，但chorme却认为是正确返回
			if( txt == undefined || txt == null || txt == "" || typeof txt === "string" && txt.indexOf("<!DOCTYPE")>=0 ){
				fn.call(lib, 2, txt, status);
				return;
			}
			try{
				if( typeof txt === "string" )
					txt = lib.parseJSON(txt);
			}catch(e){
				//通知回调
				fn.call(lib, 1, txt, status);
				return;
			}
			//通知回调
			fn.call(lib, 0, txt, status);
		},
		error : function( res, status ){
			//主动删除缓存
			delete httpCache[key];
			//没有文件等错误，会返回两次error事件，一次状态是error，一次状态是null
			if( !status || status == "error" ){
				//通知回调
				fn.call(lib, 2, "", status);
				return;
			}
			if( res.aborted )
				return;
			//通知回调
			fn.call(lib, 2, res.responseText, status);
		}
	});
	//存储
	key && (httpCache[key] = xhr);
}

//引用到window
return Core;
})(window, jQuery);

/*
 * 卸载事件
 */
jQuery(window).unload(function(){
	document.oncontextmenu = null;
	window.Core = null;
	window.onload = null;
	window.onresize = null;
	window.onunload = null;
	window.onerror = null;
	window.CollectGarbage && window.CollectGarbage();
});

//绑定页面完成监听
jQuery(document).ready(function(){ Core.init(); });


/*兼容保险以前js，扩展接口*/
//登录成功回调
function popupLogin(onLoginSucc, onLoginFail){
	Core.easyNav.login(onLoginSucc);
}

//为了兼容以前js故尔加上这个对象
var ev={
          evtListeners: [],
          //添加事件监听
          addEvent:function(obj,evt,fun){
			  	var _ev = ev, relFun = function(){ fun.call(obj); }, lis = this.evtListeners;
			 
				  if(obj.addEventListener){//for dom
						obj.addEventListener(evt,fun,false);
				  }
				  else if(obj.attachEvent){//for ie
						obj.attachEvent("on"+evt,relFun);//解决IE attachEvent this指向window的问题
						lis[lis.length] = [obj, evt, fun, relFun];
				  }
				  else{obj["on"+evt] = fun}//for other
			  },
		  
          //删除事件监听
          removeEvent:function(obj,evt,fun){
			  var lis = this.evtListeners, len = lis.length, item;
			  
			  if(obj.removeEventListener){//for dom
				obj.removeEventListener(evt, fun,false);
			  }
			  else if(obj.detachEvent){//for ie
			  	 while(len--) {
					 item = lis[len];
				 	 if(item[1] === evt  && item[0] === obj && item[2] === fun){
						  lis.splice(len, 1);
						  obj.detachEvent("on"+evt,item[3]);	
					}
				}
			  }
			  else{
				  obj["on"+evt] = null;
			  } //for other
           },
	
          //捕获事件		
           getEvent:function(){
                    if(window.event){return window.event}
                    else{return ev.getEvent.caller.arguments[0];}	
           },
		   
		   formatEvent:function(evt){
                    evt.eTarget = evt.target ? evt.target:evt.srcElement;//事件目标对象
                    evt.eX = evt.pagex ? evt.pagex:evt.clientX + document.body.scrollLeft;//页面鼠标X坐标
                    evt.eY = evt.pagey ? evt.pagex:evt.clientY + document.body.scrollTop;//页面鼠标Y坐标
                    evt.eStopDefault = function(){this.preventDefault ? this.preventDefault():this.returnValue = false;};//取消默认动作
                    evt.eStopBubble = function(){this.stopPropagation ? this.stopPropagation():this.cancelBubble = true;};//取消冒泡
           }
};



