/*! jQuery UI - v1.11.4 - 2015-03-11
* http://jqueryui.com
* Includes: core.js, widget.js, mouse.js, position.js, accordion.js, autocomplete.js, button.js, datepicker.js, dialog.js, draggable.js, droppable.js, effect.js, effect-blind.js, effect-bounce.js, effect-clip.js, effect-drop.js, effect-explode.js, effect-fade.js, effect-fold.js, effect-highlight.js, effect-puff.js, effect-pulsate.js, effect-scale.js, effect-shake.js, effect-size.js, effect-slide.js, effect-transfer.js, menu.js, progressbar.js, resizable.js, selectable.js, selectmenu.js, slider.js, sortable.js, spinner.js, tabs.js, tooltip.js
* Copyright 2015 jQuery Foundation and other contributors; Licensed MIT */

(function(e){"function"==typeof define&&define.amd?define(["jquery"],e):e(jQuery)})(function(e){function t(t,s){var n,a,o,r=t.nodeName.toLowerCase();return"area"===r?(n=t.parentNode,a=n.name,t.href&&a&&"map"===n.nodeName.toLowerCase()?(o=e("img[usemap='#"+a+"']")[0],!!o&&i(o)):!1):(/^(input|select|textarea|button|object)$/.test(r)?!t.disabled:"a"===r?t.href||s:s)&&i(t)}function i(t){return e.expr.filters.visible(t)&&!e(t).parents().addBack().filter(function(){return"hidden"===e.css(this,"visibility")}).length}function s(e){for(var t,i;e.length&&e[0]!==document;){if(t=e.css("position"),("absolute"===t||"relative"===t||"fixed"===t)&&(i=parseInt(e.css("zIndex"),10),!isNaN(i)&&0!==i))return i;e=e.parent()}return 0}function n(){this._curInst=null,this._keyEvent=!1,this._disabledInputs=[],this._datepickerShowing=!1,this._inDialog=!1,this._mainDivId="ui-datepicker-div",this._inlineClass="ui-datepicker-inline",this._appendClass="ui-datepicker-append",this._triggerClass="ui-datepicker-trigger",this._dialogClass="ui-datepicker-dialog",this._disableClass="ui-datepicker-disabled",this._unselectableClass="ui-datepicker-unselectable",this._currentClass="ui-datepicker-current-day",this._dayOverClass="ui-datepicker-days-cell-over",this.regional=[],this.regional[""]={closeText:"Done",prevText:"Prev",nextText:"Next",currentText:"Today",monthNames:["January","February","March","April","May","June","July","August","September","October","November","December"],monthNamesShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],dayNames:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],dayNamesShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],dayNamesMin:["Su","Mo","Tu","We","Th","Fr","Sa"],weekHeader:"Wk",dateFormat:"mm/dd/yy",firstDay:0,isRTL:!1,showMonthAfterYear:!1,yearSuffix:""},this._defaults={showOn:"focus",showAnim:"fadeIn",showOptions:{},defaultDate:null,appendText:"",buttonText:"...",buttonImage:"",buttonImageOnly:!1,hideIfNoPrevNext:!1,navigationAsDateFormat:!1,gotoCurrent:!1,changeMonth:!1,changeYear:!1,yearRange:"c-10:c+10",showOtherMonths:!1,selectOtherMonths:!1,showWeek:!1,calculateWeek:this.iso8601Week,shortYearCutoff:"+10",minDate:null,maxDate:null,duration:"fast",beforeShowDay:null,beforeShow:null,onSelect:null,onChangeMonthYear:null,onClose:null,numberOfMonths:1,showCurrentAtPos:0,stepMonths:1,stepBigMonths:12,altField:"",altFormat:"",constrainInput:!0,showButtonPanel:!1,autoSize:!1,disabled:!1},e.extend(this._defaults,this.regional[""]),this.regional.en=e.extend(!0,{},this.regional[""]),this.regional["en-US"]=e.extend(!0,{},this.regional.en),this.dpDiv=a(e("<div id='"+this._mainDivId+"' class='ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>"))}function a(t){var i="button, .ui-datepicker-prev, .ui-datepicker-next, .ui-datepicker-calendar td a";return t.delegate(i,"mouseout",function(){e(this).removeClass("ui-state-hover"),-1!==this.className.indexOf("ui-datepicker-prev")&&e(this).removeClass("ui-datepicker-prev-hover"),-1!==this.className.indexOf("ui-datepicker-next")&&e(this).removeClass("ui-datepicker-next-hover")}).delegate(i,"mouseover",o)}function o(){e.datepicker._isDisabledDatepicker(v.inline?v.dpDiv.parent()[0]:v.input[0])||(e(this).parents(".ui-datepicker-calendar").find("a").removeClass("ui-state-hover"),e(this).addClass("ui-state-hover"),-1!==this.className.indexOf("ui-datepicker-prev")&&e(this).addClass("ui-datepicker-prev-hover"),-1!==this.className.indexOf("ui-datepicker-next")&&e(this).addClass("ui-datepicker-next-hover"))}function r(t,i){e.extend(t,i);for(var s in i)null==i[s]&&(t[s]=i[s]);return t}function h(e){return function(){var t=this.element.val();e.apply(this,arguments),this._refresh(),t!==this.element.val()&&this._trigger("change")}}e.ui=e.ui||{},e.extend(e.ui,{version:"1.11.4",keyCode:{BACKSPACE:8,COMMA:188,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,LEFT:37,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SPACE:32,TAB:9,UP:38}}),e.fn.extend({scrollParent:function(t){var i=this.css("position"),s="absolute"===i,n=t?/(auto|scroll|hidden)/:/(auto|scroll)/,a=this.parents().filter(function(){var t=e(this);return s&&"static"===t.css("position")?!1:n.test(t.css("overflow")+t.css("overflow-y")+t.css("overflow-x"))}).eq(0);return"fixed"!==i&&a.length?a:e(this[0].ownerDocument||document)},uniqueId:function(){var e=0;return function(){return this.each(function(){this.id||(this.id="ui-id-"+ ++e)})}}(),removeUniqueId:function(){return this.each(function(){/^ui-id-\d+$/.test(this.id)&&e(this).removeAttr("id")})}}),e.extend(e.expr[":"],{data:e.expr.createPseudo?e.expr.createPseudo(function(t){return function(i){return!!e.data(i,t)}}):function(t,i,s){return!!e.data(t,s[3])},focusable:function(i){return t(i,!isNaN(e.attr(i,"tabindex")))},tabbable:function(i){var s=e.attr(i,"tabindex"),n=isNaN(s);return(n||s>=0)&&t(i,!n)}}),e("<a>").outerWidth(1).jquery||e.each(["Width","Height"],function(t,i){function s(t,i,s,a){return e.each(n,function(){i-=parseFloat(e.css(t,"padding"+this))||0,s&&(i-=parseFloat(e.css(t,"border"+this+"Width"))||0),a&&(i-=parseFloat(e.css(t,"margin"+this))||0)}),i}var n="Width"===i?["Left","Right"]:["Top","Bottom"],a=i.toLowerCase(),o={innerWidth:e.fn.innerWidth,innerHeight:e.fn.innerHeight,outerWidth:e.fn.outerWidth,outerHeight:e.fn.outerHeight};e.fn["inner"+i]=function(t){return void 0===t?o["inner"+i].call(this):this.each(function(){e(this).css(a,s(this,t)+"px")})},e.fn["outer"+i]=function(t,n){return"number"!=typeof t?o["outer"+i].call(this,t):this.each(function(){e(this).css(a,s(this,t,!0,n)+"px")})}}),e.fn.addBack||(e.fn.addBack=function(e){return this.add(null==e?this.prevObject:this.prevObject.filter(e))}),e("<a>").data("a-b","a").removeData("a-b").data("a-b")&&(e.fn.removeData=function(t){return function(i){return arguments.length?t.call(this,e.camelCase(i)):t.call(this)}}(e.fn.removeData)),e.ui.ie=!!/msie [\w.]+/.exec(navigator.userAgent.toLowerCase()),e.fn.extend({focus:function(t){return function(i,s){return"number"==typeof i?this.each(function(){var t=this;setTimeout(function(){e(t).focus(),s&&s.call(t)},i)}):t.apply(this,arguments)}}(e.fn.focus),disableSelection:function(){var e="onselectstart"in document.createElement("div")?"selectstart":"mousedown";return function(){return this.bind(e+".ui-disableSelection",function(e){e.preventDefault()})}}(),enableSelection:function(){return this.unbind(".ui-disableSelection")},zIndex:function(t){if(void 0!==t)return this.css("zIndex",t);if(this.length)for(var i,s,n=e(this[0]);n.length&&n[0]!==document;){if(i=n.css("position"),("absolute"===i||"relative"===i||"fixed"===i)&&(s=parseInt(n.css("zIndex"),10),!isNaN(s)&&0!==s))return s;n=n.parent()}return 0}}),e.ui.plugin={add:function(t,i,s){var n,a=e.ui[t].prototype;for(n in s)a.plugins[n]=a.plugins[n]||[],a.plugins[n].push([i,s[n]])},call:function(e,t,i,s){var n,a=e.plugins[t];if(a&&(s||e.element[0].parentNode&&11!==e.element[0].parentNode.nodeType))for(n=0;a.length>n;n++)e.options[a[n][0]]&&a[n][1].apply(e.element,i)}};var l=0,u=Array.prototype.slice;e.cleanData=function(t){return function(i){var s,n,a;for(a=0;null!=(n=i[a]);a++)try{s=e._data(n,"events"),s&&s.remove&&e(n).triggerHandler("remove")}catch(o){}t(i)}}(e.cleanData),e.widget=function(t,i,s){var n,a,o,r,h={},l=t.split(".")[0];return t=t.split(".")[1],n=l+"-"+t,s||(s=i,i=e.Widget),e.expr[":"][n.toLowerCase()]=function(t){return!!e.data(t,n)},e[l]=e[l]||{},a=e[l][t],o=e[l][t]=function(e,t){return this._createWidget?(arguments.length&&this._createWidget(e,t),void 0):new o(e,t)},e.extend(o,a,{version:s.version,_proto:e.extend({},s),_childConstructors:[]}),r=new i,r.options=e.widget.extend({},r.options),e.each(s,function(t,s){return e.isFunction(s)?(h[t]=function(){var e=function(){return i.prototype[t].apply(this,arguments)},n=function(e){return i.prototype[t].apply(this,e)};return function(){var t,i=this._super,a=this._superApply;return this._super=e,this._superApply=n,t=s.apply(this,arguments),this._super=i,this._superApply=a,t}}(),void 0):(h[t]=s,void 0)}),o.prototype=e.widget.extend(r,{widgetEventPrefix:a?r.widgetEventPrefix||t:t},h,{constructor:o,namespace:l,widgetName:t,widgetFullName:n}),a?(e.each(a._childConstructors,function(t,i){var s=i.prototype;e.widget(s.namespace+"."+s.widgetName,o,i._proto)}),delete a._childConstructors):i._childConstructors.push(o),e.widget.bridge(t,o),o},e.widget.extend=function(t){for(var i,s,n=u.call(arguments,1),a=0,o=n.length;o>a;a++)for(i in n[a])s=n[a][i],n[a].hasOwnProperty(i)&&void 0!==s&&(t[i]=e.isPlainObject(s)?e.isPlainObject(t[i])?e.widget.extend({},t[i],s):e.widget.extend({},s):s);return t},e.widget.bridge=function(t,i){var s=i.prototype.widgetFullName||t;e.fn[t]=function(n){var a="string"==typeof n,o=u.call(arguments,1),r=this;return a?this.each(function(){var i,a=e.data(this,s);return"instance"===n?(r=a,!1):a?e.isFunction(a[n])&&"_"!==n.charAt(0)?(i=a[n].apply(a,o),i!==a&&void 0!==i?(r=i&&i.jquery?r.pushStack(i.get()):i,!1):void 0):e.error("no such method '"+n+"' for "+t+" widget instance"):e.error("cannot call methods on "+t+" prior to initialization; "+"attempted to call method '"+n+"'")}):(o.length&&(n=e.widget.extend.apply(null,[n].concat(o))),this.each(function(){var t=e.data(this,s);t?(t.option(n||{}),t._init&&t._init()):e.data(this,s,new i(n,this))})),r}},e.Widget=function(){},e.Widget._childConstructors=[],e.Widget.prototype={widgetName:"widget",widgetEventPrefix:"",defaultElement:"<div>",options:{disabled:!1,create:null},_createWidget:function(t,i){i=e(i||this.defaultElement||this)[0],this.element=e(i),this.uuid=l++,this.eventNamespace="."+this.widgetName+this.uuid,this.bindings=e(),this.hoverable=e(),this.focusable=e(),i!==this&&(e.data(i,this.widgetFullName,this),this._on(!0,this.element,{remove:function(e){e.target===i&&this.destroy()}}),this.document=e(i.style?i.ownerDocument:i.document||i),this.window=e(this.document[0].defaultView||this.document[0].parentWindow)),this.options=e.widget.extend({},this.options,this._getCreateOptions(),t),this._create(),this._trigger("create",null,this._getCreateEventData()),this._init()},_getCreateOptions:e.noop,_getCreateEventData:e.noop,_create:e.noop,_init:e.noop,destroy:function(){this._destroy(),this.element.unbind(this.eventNamespace).removeData(this.widgetFullName).removeData(e.camelCase(this.widgetFullName)),this.widget().unbind(this.eventNamespace).removeAttr("aria-disabled").removeClass(this.widgetFullName+"-disabled "+"ui-state-disabled"),this.bindings.unbind(this.eventNamespace),this.hoverable.removeClass("ui-state-hover"),this.focusable.removeClass("ui-state-focus")},_destroy:e.noop,widget:function(){return this.element},option:function(t,i){var s,n,a,o=t;if(0===arguments.length)return e.widget.extend({},this.options);if("string"==typeof t)if(o={},s=t.split("."),t=s.shift(),s.length){for(n=o[t]=e.widget.extend({},this.options[t]),a=0;s.length-1>a;a++)n[s[a]]=n[s[a]]||{},n=n[s[a]];if(t=s.pop(),1===arguments.length)return void 0===n[t]?null:n[t];n[t]=i}else{if(1===arguments.length)return void 0===this.options[t]?null:this.options[t];o[t]=i}return this._setOptions(o),this},_setOptions:function(e){var t;for(t in e)this._setOption(t,e[t]);return this},_setOption:function(e,t){return this.options[e]=t,"disabled"===e&&(this.widget().toggleClass(this.widgetFullName+"-disabled",!!t),t&&(this.hoverable.removeClass("ui-state-hover"),this.focusable.removeClass("ui-state-focus"))),this},enable:function(){return this._setOptions({disabled:!1})},disable:function(){return this._setOptions({disabled:!0})},_on:function(t,i,s){var n,a=this;"boolean"!=typeof t&&(s=i,i=t,t=!1),s?(i=n=e(i),this.bindings=this.bindings.add(i)):(s=i,i=this.element,n=this.widget()),e.each(s,function(s,o){function r(){return t||a.options.disabled!==!0&&!e(this).hasClass("ui-state-disabled")?("string"==typeof o?a[o]:o).apply(a,arguments):void 0}"string"!=typeof o&&(r.guid=o.guid=o.guid||r.guid||e.guid++);var h=s.match(/^([\w:-]*)\s*(.*)$/),l=h[1]+a.eventNamespace,u=h[2];u?n.delegate(u,l,r):i.bind(l,r)})},_off:function(t,i){i=(i||"").split(" ").join(this.eventNamespace+" ")+this.eventNamespace,t.unbind(i).undelegate(i),this.bindings=e(this.bindings.not(t).get()),this.focusable=e(this.focusable.not(t).get()),this.hoverable=e(this.hoverable.not(t).get())},_delay:function(e,t){function i(){return("string"==typeof e?s[e]:e).apply(s,arguments)}var s=this;return setTimeout(i,t||0)},_hoverable:function(t){this.hoverable=this.hoverable.add(t),this._on(t,{mouseenter:function(t){e(t.currentTarget).addClass("ui-state-hover")},mouseleave:function(t){e(t.currentTarget).removeClass("ui-state-hover")}})},_focusable:function(t){this.focusable=this.focusable.add(t),this._on(t,{focusin:function(t){e(t.currentTarget).addClass("ui-state-focus")},focusout:function(t){e(t.currentTarget).removeClass("ui-state-focus")}})},_trigger:function(t,i,s){var n,a,o=this.options[t];if(s=s||{},i=e.Event(i),i.type=(t===this.widgetEventPrefix?t:this.widgetEventPrefix+t).toLowerCase(),i.target=this.element[0],a=i.originalEvent)for(n in a)n in i||(i[n]=a[n]);return this.element.trigger(i,s),!(e.isFunction(o)&&o.apply(this.element[0],[i].concat(s))===!1||i.isDefaultPrevented())}},e.each({show:"fadeIn",hide:"fadeOut"},function(t,i){e.Widget.prototype["_"+t]=function(s,n,a){"string"==typeof n&&(n={effect:n});var o,r=n?n===!0||"number"==typeof n?i:n.effect||i:t;n=n||{},"number"==typeof n&&(n={duration:n}),o=!e.isEmptyObject(n),n.complete=a,n.delay&&s.delay(n.delay),o&&e.effects&&e.effects.effect[r]?s[t](n):r!==t&&s[r]?s[r](n.duration,n.easing,a):s.queue(function(i){e(this)[t](),a&&a.call(s[0]),i()})}}),e.widget;var d=!1;e(document).mouseup(function(){d=!1}),e.widget("ui.mouse",{version:"1.11.4",options:{cancel:"input,textarea,button,select,option",distance:1,delay:0},_mouseInit:function(){var t=this;this.element.bind("mousedown."+this.widgetName,function(e){return t._mouseDown(e)}).bind("click."+this.widgetName,function(i){return!0===e.data(i.target,t.widgetName+".preventClickEvent")?(e.removeData(i.target,t.widgetName+".preventClickEvent"),i.stopImmediatePropagation(),!1):void 0}),this.started=!1},_mouseDestroy:function(){this.element.unbind("."+this.widgetName),this._mouseMoveDelegate&&this.document.unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate)},_mouseDown:function(t){if(!d){this._mouseMoved=!1,this._mouseStarted&&this._mouseUp(t),this._mouseDownEvent=t;var i=this,s=1===t.which,n="string"==typeof this.options.cancel&&t.target.nodeName?e(t.target).closest(this.options.cancel).length:!1;return s&&!n&&this._mouseCapture(t)?(this.mouseDelayMet=!this.options.delay,this.mouseDelayMet||(this._mouseDelayTimer=setTimeout(function(){i.mouseDelayMet=!0},this.options.delay)),this._mouseDistanceMet(t)&&this._mouseDelayMet(t)&&(this._mouseStarted=this._mouseStart(t)!==!1,!this._mouseStarted)?(t.preventDefault(),!0):(!0===e.data(t.target,this.widgetName+".preventClickEvent")&&e.removeData(t.target,this.widgetName+".preventClickEvent"),this._mouseMoveDelegate=function(e){return i._mouseMove(e)},this._mouseUpDelegate=function(e){return i._mouseUp(e)},this.document.bind("mousemove."+this.widgetName,this._mouseMoveDelegate).bind("mouseup."+this.widgetName,this._mouseUpDelegate),t.preventDefault(),d=!0,!0)):!0}},_mouseMove:function(t){if(this._mouseMoved){if(e.ui.ie&&(!document.documentMode||9>document.documentMode)&&!t.button)return this._mouseUp(t);if(!t.which)return this._mouseUp(t)}return(t.which||t.button)&&(this._mouseMoved=!0),this._mouseStarted?(this._mouseDrag(t),t.preventDefault()):(this._mouseDistanceMet(t)&&this._mouseDelayMet(t)&&(this._mouseStarted=this._mouseStart(this._mouseDownEvent,t)!==!1,this._mouseStarted?this._mouseDrag(t):this._mouseUp(t)),!this._mouseStarted)},_mouseUp:function(t){return this.document.unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate),this._mouseStarted&&(this._mouseStarted=!1,t.target===this._mouseDownEvent.target&&e.data(t.target,this.widgetName+".preventClickEvent",!0),this._mouseStop(t)),d=!1,!1},_mouseDistanceMet:function(e){return Math.max(Math.abs(this._mouseDownEvent.pageX-e.pageX),Math.abs(this._mouseDownEvent.pageY-e.pageY))>=this.options.distance},_mouseDelayMet:function(){return this.mouseDelayMet},_mouseStart:function(){},_mouseDrag:function(){},_mouseStop:function(){},_mouseCapture:function(){return!0}}),function(){function t(e,t,i){return[parseFloat(e[0])*(p.test(e[0])?t/100:1),parseFloat(e[1])*(p.test(e[1])?i/100:1)]}function i(t,i){return parseInt(e.css(t,i),10)||0}function s(t){var i=t[0];return 9===i.nodeType?{width:t.width(),height:t.height(),offset:{top:0,left:0}}:e.isWindow(i)?{width:t.width(),height:t.height(),offset:{top:t.scrollTop(),left:t.scrollLeft()}}:i.preventDefault?{width:0,height:0,offset:{top:i.pageY,left:i.pageX}}:{width:t.outerWidth(),height:t.outerHeight(),offset:t.offset()}}e.ui=e.ui||{};var n,a,o=Math.max,r=Math.abs,h=Math.round,l=/left|center|right/,u=/top|center|bottom/,d=/[\+\-]\d+(\.[\d]+)?%?/,c=/^\w+/,p=/%$/,f=e.fn.position;e.position={scrollbarWidth:function(){if(void 0!==n)return n;var t,i,s=e("<div style='display:block;position:absolute;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>"),a=s.children()[0];return e("body").append(s),t=a.offsetWidth,s.css("overflow","scroll"),i=a.offsetWidth,t===i&&(i=s[0].clientWidth),s.remove(),n=t-i},getScrollInfo:function(t){var i=t.isWindow||t.isDocument?"":t.element.css("overflow-x"),s=t.isWindow||t.isDocument?"":t.element.css("overflow-y"),n="scroll"===i||"auto"===i&&t.width<t.element[0].scrollWidth,a="scroll"===s||"auto"===s&&t.height<t.element[0].scrollHeight;return{width:a?e.position.scrollbarWidth():0,height:n?e.position.scrollbarWidth():0}},getWithinInfo:function(t){var i=e(t||window),s=e.isWindow(i[0]),n=!!i[0]&&9===i[0].nodeType;return{element:i,isWindow:s,isDocument:n,offset:i.offset()||{left:0,top:0},scrollLeft:i.scrollLeft(),scrollTop:i.scrollTop(),width:s||n?i.width():i.outerWidth(),height:s||n?i.height():i.outerHeight()}}},e.fn.position=function(n){if(!n||!n.of)return f.apply(this,arguments);n=e.extend({},n);var p,m,g,v,y,b,_=e(n.of),x=e.position.getWithinInfo(n.within),w=e.position.getScrollInfo(x),k=(n.collision||"flip").split(" "),T={};return b=s(_),_[0].preventDefault&&(n.at="left top"),m=b.width,g=b.height,v=b.offset,y=e.extend({},v),e.each(["my","at"],function(){var e,t,i=(n[this]||"").split(" ");1===i.length&&(i=l.test(i[0])?i.concat(["center"]):u.test(i[0])?["center"].concat(i):["center","center"]),i[0]=l.test(i[0])?i[0]:"center",i[1]=u.test(i[1])?i[1]:"center",e=d.exec(i[0]),t=d.exec(i[1]),T[this]=[e?e[0]:0,t?t[0]:0],n[this]=[c.exec(i[0])[0],c.exec(i[1])[0]]}),1===k.length&&(k[1]=k[0]),"right"===n.at[0]?y.left+=m:"center"===n.at[0]&&(y.left+=m/2),"bottom"===n.at[1]?y.top+=g:"center"===n.at[1]&&(y.top+=g/2),p=t(T.at,m,g),y.left+=p[0],y.top+=p[1],this.each(function(){var s,l,u=e(this),d=u.outerWidth(),c=u.outerHeight(),f=i(this,"marginLeft"),b=i(this,"marginTop"),D=d+f+i(this,"marginRight")+w.width,S=c+b+i(this,"marginBottom")+w.height,M=e.extend({},y),C=t(T.my,u.outerWidth(),u.outerHeight());"right"===n.my[0]?M.left-=d:"center"===n.my[0]&&(M.left-=d/2),"bottom"===n.my[1]?M.top-=c:"center"===n.my[1]&&(M.top-=c/2),M.left+=C[0],M.top+=C[1],a||(M.left=h(M.left),M.top=h(M.top)),s={marginLeft:f,marginTop:b},e.each(["left","top"],function(t,i){e.ui.position[k[t]]&&e.ui.position[k[t]][i](M,{targetWidth:m,targetHeight:g,elemWidth:d,elemHeight:c,collisionPosition:s,collisionWidth:D,collisionHeight:S,offset:[p[0]+C[0],p[1]+C[1]],my:n.my,at:n.at,within:x,elem:u})}),n.using&&(l=function(e){var t=v.left-M.left,i=t+m-d,s=v.top-M.top,a=s+g-c,h={target:{element:_,left:v.left,top:v.top,width:m,height:g},element:{element:u,left:M.left,top:M.top,width:d,height:c},horizontal:0>i?"left":t>0?"right":"center",vertical:0>a?"top":s>0?"bottom":"middle"};d>m&&m>r(t+i)&&(h.horizontal="center"),c>g&&g>r(s+a)&&(h.vertical="middle"),h.important=o(r(t),r(i))>o(r(s),r(a))?"horizontal":"vertical",n.using.call(this,e,h)}),u.offset(e.extend(M,{using:l}))})},e.ui.position={fit:{left:function(e,t){var i,s=t.within,n=s.isWindow?s.scrollLeft:s.offset.left,a=s.width,r=e.left-t.collisionPosition.marginLeft,h=n-r,l=r+t.collisionWidth-a-n;t.collisionWidth>a?h>0&&0>=l?(i=e.left+h+t.collisionWidth-a-n,e.left+=h-i):e.left=l>0&&0>=h?n:h>l?n+a-t.collisionWidth:n:h>0?e.left+=h:l>0?e.left-=l:e.left=o(e.left-r,e.left)},top:function(e,t){var i,s=t.within,n=s.isWindow?s.scrollTop:s.offset.top,a=t.within.height,r=e.top-t.collisionPosition.marginTop,h=n-r,l=r+t.collisionHeight-a-n;t.collisionHeight>a?h>0&&0>=l?(i=e.top+h+t.collisionHeight-a-n,e.top+=h-i):e.top=l>0&&0>=h?n:h>l?n+a-t.collisionHeight:n:h>0?e.top+=h:l>0?e.top-=l:e.top=o(e.top-r,e.top)}},flip:{left:function(e,t){var i,s,n=t.within,a=n.offset.left+n.scrollLeft,o=n.width,h=n.isWindow?n.scrollLeft:n.offset.left,l=e.left-t.collisionPosition.marginLeft,u=l-h,d=l+t.collisionWidth-o-h,c="left"===t.my[0]?-t.elemWidth:"right"===t.my[0]?t.elemWidth:0,p="left"===t.at[0]?t.targetWidth:"right"===t.at[0]?-t.targetWidth:0,f=-2*t.offset[0];0>u?(i=e.left+c+p+f+t.collisionWidth-o-a,(0>i||r(u)>i)&&(e.left+=c+p+f)):d>0&&(s=e.left-t.collisionPosition.marginLeft+c+p+f-h,(s>0||d>r(s))&&(e.left+=c+p+f))},top:function(e,t){var i,s,n=t.within,a=n.offset.top+n.scrollTop,o=n.height,h=n.isWindow?n.scrollTop:n.offset.top,l=e.top-t.collisionPosition.marginTop,u=l-h,d=l+t.collisionHeight-o-h,c="top"===t.my[1],p=c?-t.elemHeight:"bottom"===t.my[1]?t.elemHeight:0,f="top"===t.at[1]?t.targetHeight:"bottom"===t.at[1]?-t.targetHeight:0,m=-2*t.offset[1];0>u?(s=e.top+p+f+m+t.collisionHeight-o-a,(0>s||r(u)>s)&&(e.top+=p+f+m)):d>0&&(i=e.top-t.collisionPosition.marginTop+p+f+m-h,(i>0||d>r(i))&&(e.top+=p+f+m))}},flipfit:{left:function(){e.ui.position.flip.left.apply(this,arguments),e.ui.position.fit.left.apply(this,arguments)},top:function(){e.ui.position.flip.top.apply(this,arguments),e.ui.position.fit.top.apply(this,arguments)}}},function(){var t,i,s,n,o,r=document.getElementsByTagName("body")[0],h=document.createElement("div");t=document.createElement(r?"div":"body"),s={visibility:"hidden",width:0,height:0,border:0,margin:0,background:"none"},r&&e.extend(s,{position:"absolute",left:"-1000px",top:"-1000px"});for(o in s)t.style[o]=s[o];t.appendChild(h),i=r||document.documentElement,i.insertBefore(t,i.firstChild),h.style.cssText="position: absolute; left: 10.7432222px;",n=e(h).offset().left,a=n>10&&11>n,t.innerHTML="",i.removeChild(t)}()}(),e.ui.position,e.widget("ui.accordion",{version:"1.11.4",options:{active:0,animate:{},collapsible:!1,event:"click",header:"> li > :first-child,> :not(li):even",heightStyle:"auto",icons:{activeHeader:"ui-icon-triangle-1-s",header:"ui-icon-triangle-1-e"},activate:null,beforeActivate:null},hideProps:{borderTopWidth:"hide",borderBottomWidth:"hide",paddingTop:"hide",paddingBottom:"hide",height:"hide"},showProps:{borderTopWidth:"show",borderBottomWidth:"show",paddingTop:"show",paddingBottom:"show",height:"show"},_create:function(){var t=this.options;this.prevShow=this.prevHide=e(),this.element.addClass("ui-accordion ui-widget ui-helper-reset").attr("role","tablist"),t.collapsible||t.active!==!1&&null!=t.active||(t.active=0),this._processPanels(),0>t.active&&(t.active+=this.headers.length),this._refresh()},_getCreateEventData:function(){return{header:this.active,panel:this.active.length?this.active.next():e()}},_createIcons:function(){var t=this.options.icons;t&&(e("<span>").addClass("ui-accordion-header-icon ui-icon "+t.header).prependTo(this.headers),this.active.children(".ui-accordion-header-icon").removeClass(t.header).addClass(t.activeHeader),this.headers.addClass("ui-accordion-icons"))},_destroyIcons:function(){this.headers.removeClass("ui-accordion-icons").children(".ui-accordion-header-icon").remove()},_destroy:function(){var e;this.element.removeClass("ui-accordion ui-widget ui-helper-reset").removeAttr("role"),this.headers.removeClass("ui-accordion-header ui-accordion-header-active ui-state-default ui-corner-all ui-state-active ui-state-disabled ui-corner-top").removeAttr("role").removeAttr("aria-expanded").removeAttr("aria-selected").removeAttr("aria-controls").removeAttr("tabIndex").removeUniqueId(),this._destroyIcons(),e=this.headers.next().removeClass("ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content ui-accordion-content-active ui-state-disabled").css("display","").removeAttr("role").removeAttr("aria-hidden").removeAttr("aria-labelledby").removeUniqueId(),"content"!==this.options.heightStyle&&e.css("height","")},_setOption:function(e,t){return"active"===e?(this._activate(t),void 0):("event"===e&&(this.options.event&&this._off(this.headers,this.options.event),this._setupEvents(t)),this._super(e,t),"collapsible"!==e||t||this.options.active!==!1||this._activate(0),"icons"===e&&(this._destroyIcons(),t&&this._createIcons()),"disabled"===e&&(this.element.toggleClass("ui-state-disabled",!!t).attr("aria-disabled",t),this.headers.add(this.headers.next()).toggleClass("ui-state-disabled",!!t)),void 0)},_keydown:function(t){if(!t.altKey&&!t.ctrlKey){var i=e.ui.keyCode,s=this.headers.length,n=this.headers.index(t.target),a=!1;switch(t.keyCode){case i.RIGHT:case i.DOWN:a=this.headers[(n+1)%s];break;case i.LEFT:case i.UP:a=this.headers[(n-1+s)%s];break;case i.SPACE:case i.ENTER:this._eventHandler(t);break;case i.HOME:a=this.headers[0];break;case i.END:a=this.headers[s-1]}a&&(e(t.target).attr("tabIndex",-1),e(a).attr("tabIndex",0),a.focus(),t.preventDefault())}},_panelKeyDown:function(t){t.keyCode===e.ui.keyCode.UP&&t.ctrlKey&&e(t.currentTarget).prev().focus()},refresh:function(){var t=this.options;this._processPanels(),t.active===!1&&t.collapsible===!0||!this.headers.length?(t.active=!1,this.active=e()):t.active===!1?this._activate(0):this.active.length&&!e.contains(this.element[0],this.active[0])?this.headers.length===this.headers.find(".ui-state-disabled").length?(t.active=!1,this.active=e()):this._activate(Math.max(0,t.active-1)):t.active=this.headers.index(this.active),this._destroyIcons(),this._refresh()},_processPanels:function(){var e=this.headers,t=this.panels;this.headers=this.element.find(this.options.header).addClass("ui-accordion-header ui-state-default ui-corner-all"),this.panels=this.headers.next().addClass("ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom").filter(":not(.ui-accordion-content-active)").hide(),t&&(this._off(e.not(this.headers)),this._off(t.not(this.panels)))},_refresh:function(){var t,i=this.options,s=i.heightStyle,n=this.element.parent();this.active=this._findActive(i.active).addClass("ui-accordion-header-active ui-state-active ui-corner-top").removeClass("ui-corner-all"),this.active.next().addClass("ui-accordion-content-active").show(),this.headers.attr("role","tab").each(function(){var t=e(this),i=t.uniqueId().attr("id"),s=t.next(),n=s.uniqueId().attr("id");t.attr("aria-controls",n),s.attr("aria-labelledby",i)}).next().attr("role","tabpanel"),this.headers.not(this.active).attr({"aria-selected":"false","aria-expanded":"false",tabIndex:-1}).next().attr({"aria-hidden":"true"}).hide(),this.active.length?this.active.attr({"aria-selected":"true","aria-expanded":"true",tabIndex:0}).next().attr({"aria-hidden":"false"}):this.headers.eq(0).attr("tabIndex",0),this._createIcons(),this._setupEvents(i.event),"fill"===s?(t=n.height(),this.element.siblings(":visible").each(function(){var i=e(this),s=i.css("position");"absolute"!==s&&"fixed"!==s&&(t-=i.outerHeight(!0))}),this.headers.each(function(){t-=e(this).outerHeight(!0)}),this.headers.next().each(function(){e(this).height(Math.max(0,t-e(this).innerHeight()+e(this).height()))}).css("overflow","auto")):"auto"===s&&(t=0,this.headers.next().each(function(){t=Math.max(t,e(this).css("height","").height())}).height(t))},_activate:function(t){var i=this._findActive(t)[0];i!==this.active[0]&&(i=i||this.active[0],this._eventHandler({target:i,currentTarget:i,preventDefault:e.noop}))},_findActive:function(t){return"number"==typeof t?this.headers.eq(t):e()},_setupEvents:function(t){var i={keydown:"_keydown"};t&&e.each(t.split(" "),function(e,t){i[t]="_eventHandler"}),this._off(this.headers.add(this.headers.next())),this._on(this.headers,i),this._on(this.headers.next(),{keydown:"_panelKeyDown"}),this._hoverable(this.headers),this._focusable(this.headers)},_eventHandler:function(t){var i=this.options,s=this.active,n=e(t.currentTarget),a=n[0]===s[0],o=a&&i.collapsible,r=o?e():n.next(),h=s.next(),l={oldHeader:s,oldPanel:h,newHeader:o?e():n,newPanel:r};t.preventDefault(),a&&!i.collapsible||this._trigger("beforeActivate",t,l)===!1||(i.active=o?!1:this.headers.index(n),this.active=a?e():n,this._toggle(l),s.removeClass("ui-accordion-header-active ui-state-active"),i.icons&&s.children(".ui-accordion-header-icon").removeClass(i.icons.activeHeader).addClass(i.icons.header),a||(n.removeClass("ui-corner-all").addClass("ui-accordion-header-active ui-state-active ui-corner-top"),i.icons&&n.children(".ui-accordion-header-icon").removeClass(i.icons.header).addClass(i.icons.activeHeader),n.next().addClass("ui-accordion-content-active")))},_toggle:function(t){var i=t.newPanel,s=this.prevShow.length?this.prevShow:t.oldPanel;this.prevShow.add(this.prevHide).stop(!0,!0),this.prevShow=i,this.prevHide=s,this.options.animate?this._animate(i,s,t):(s.hide(),i.show(),this._toggleComplete(t)),s.attr({"aria-hidden":"true"}),s.prev().attr({"aria-selected":"false","aria-expanded":"false"}),i.length&&s.length?s.prev().attr({tabIndex:-1,"aria-expanded":"false"}):i.length&&this.headers.filter(function(){return 0===parseInt(e(this).attr("tabIndex"),10)}).attr("tabIndex",-1),i.attr("aria-hidden","false").prev().attr({"aria-selected":"true","aria-expanded":"true",tabIndex:0})},_animate:function(e,t,i){var s,n,a,o=this,r=0,h=e.css("box-sizing"),l=e.length&&(!t.length||e.index()<t.index()),u=this.options.animate||{},d=l&&u.down||u,c=function(){o._toggleComplete(i)};return"number"==typeof d&&(a=d),"string"==typeof d&&(n=d),n=n||d.easing||u.easing,a=a||d.duration||u.duration,t.length?e.length?(s=e.show().outerHeight(),t.animate(this.hideProps,{duration:a,easing:n,step:function(e,t){t.now=Math.round(e)}}),e.hide().animate(this.showProps,{duration:a,easing:n,complete:c,step:function(e,i){i.now=Math.round(e),"height"!==i.prop?"content-box"===h&&(r+=i.now):"content"!==o.options.heightStyle&&(i.now=Math.round(s-t.outerHeight()-r),r=0)}}),void 0):t.animate(this.hideProps,a,n,c):e.animate(this.showProps,a,n,c)},_toggleComplete:function(e){var t=e.oldPanel;t.removeClass("ui-accordion-content-active").prev().removeClass("ui-corner-top").addClass("ui-corner-all"),t.length&&(t.parent()[0].className=t.parent()[0].className),this._trigger("activate",null,e)}}),e.widget("ui.menu",{version:"1.11.4",defaultElement:"<ul>",delay:300,options:{icons:{submenu:"ui-icon-carat-1-e"},items:"> *",menus:"ul",position:{my:"left-1 top",at:"right top"},role:"menu",blur:null,focus:null,select:null},_create:function(){this.activeMenu=this.element,this.mouseHandled=!1,this.element.uniqueId().addClass("ui-menu ui-widget ui-widget-content").toggleClass("ui-menu-icons",!!this.element.find(".ui-icon").length).attr({role:this.options.role,tabIndex:0}),this.options.disabled&&this.element.addClass("ui-state-disabled").attr("aria-disabled","true"),this._on({"mousedown .ui-menu-item":function(e){e.preventDefault()},"click .ui-menu-item":function(t){var i=e(t.target);!this.mouseHandled&&i.not(".ui-state-disabled").length&&(this.select(t),t.isPropagationStopped()||(this.mouseHandled=!0),i.has(".ui-menu").length?this.expand(t):!this.element.is(":focus")&&e(this.document[0].activeElement).closest(".ui-menu").length&&(this.element.trigger("focus",[!0]),this.active&&1===this.active.parents(".ui-menu").length&&clearTimeout(this.timer)))},"mouseenter .ui-menu-item":function(t){if(!this.previousFilter){var i=e(t.currentTarget);
i.siblings(".ui-state-active").removeClass("ui-state-active"),this.focus(t,i)}},mouseleave:"collapseAll","mouseleave .ui-menu":"collapseAll",focus:function(e,t){var i=this.active||this.element.find(this.options.items).eq(0);t||this.focus(e,i)},blur:function(t){this._delay(function(){e.contains(this.element[0],this.document[0].activeElement)||this.collapseAll(t)})},keydown:"_keydown"}),this.refresh(),this._on(this.document,{click:function(e){this._closeOnDocumentClick(e)&&this.collapseAll(e),this.mouseHandled=!1}})},_destroy:function(){this.element.removeAttr("aria-activedescendant").find(".ui-menu").addBack().removeClass("ui-menu ui-widget ui-widget-content ui-menu-icons ui-front").removeAttr("role").removeAttr("tabIndex").removeAttr("aria-labelledby").removeAttr("aria-expanded").removeAttr("aria-hidden").removeAttr("aria-disabled").removeUniqueId().show(),this.element.find(".ui-menu-item").removeClass("ui-menu-item").removeAttr("role").removeAttr("aria-disabled").removeUniqueId().removeClass("ui-state-hover").removeAttr("tabIndex").removeAttr("role").removeAttr("aria-haspopup").children().each(function(){var t=e(this);t.data("ui-menu-submenu-carat")&&t.remove()}),this.element.find(".ui-menu-divider").removeClass("ui-menu-divider ui-widget-content")},_keydown:function(t){var i,s,n,a,o=!0;switch(t.keyCode){case e.ui.keyCode.PAGE_UP:this.previousPage(t);break;case e.ui.keyCode.PAGE_DOWN:this.nextPage(t);break;case e.ui.keyCode.HOME:this._move("first","first",t);break;case e.ui.keyCode.END:this._move("last","last",t);break;case e.ui.keyCode.UP:this.previous(t);break;case e.ui.keyCode.DOWN:this.next(t);break;case e.ui.keyCode.LEFT:this.collapse(t);break;case e.ui.keyCode.RIGHT:this.active&&!this.active.is(".ui-state-disabled")&&this.expand(t);break;case e.ui.keyCode.ENTER:case e.ui.keyCode.SPACE:this._activate(t);break;case e.ui.keyCode.ESCAPE:this.collapse(t);break;default:o=!1,s=this.previousFilter||"",n=String.fromCharCode(t.keyCode),a=!1,clearTimeout(this.filterTimer),n===s?a=!0:n=s+n,i=this._filterMenuItems(n),i=a&&-1!==i.index(this.active.next())?this.active.nextAll(".ui-menu-item"):i,i.length||(n=String.fromCharCode(t.keyCode),i=this._filterMenuItems(n)),i.length?(this.focus(t,i),this.previousFilter=n,this.filterTimer=this._delay(function(){delete this.previousFilter},1e3)):delete this.previousFilter}o&&t.preventDefault()},_activate:function(e){this.active.is(".ui-state-disabled")||(this.active.is("[aria-haspopup='true']")?this.expand(e):this.select(e))},refresh:function(){var t,i,s=this,n=this.options.icons.submenu,a=this.element.find(this.options.menus);this.element.toggleClass("ui-menu-icons",!!this.element.find(".ui-icon").length),a.filter(":not(.ui-menu)").addClass("ui-menu ui-widget ui-widget-content ui-front").hide().attr({role:this.options.role,"aria-hidden":"true","aria-expanded":"false"}).each(function(){var t=e(this),i=t.parent(),s=e("<span>").addClass("ui-menu-icon ui-icon "+n).data("ui-menu-submenu-carat",!0);i.attr("aria-haspopup","true").prepend(s),t.attr("aria-labelledby",i.attr("id"))}),t=a.add(this.element),i=t.find(this.options.items),i.not(".ui-menu-item").each(function(){var t=e(this);s._isDivider(t)&&t.addClass("ui-widget-content ui-menu-divider")}),i.not(".ui-menu-item, .ui-menu-divider").addClass("ui-menu-item").uniqueId().attr({tabIndex:-1,role:this._itemRole()}),i.filter(".ui-state-disabled").attr("aria-disabled","true"),this.active&&!e.contains(this.element[0],this.active[0])&&this.blur()},_itemRole:function(){return{menu:"menuitem",listbox:"option"}[this.options.role]},_setOption:function(e,t){"icons"===e&&this.element.find(".ui-menu-icon").removeClass(this.options.icons.submenu).addClass(t.submenu),"disabled"===e&&this.element.toggleClass("ui-state-disabled",!!t).attr("aria-disabled",t),this._super(e,t)},focus:function(e,t){var i,s;this.blur(e,e&&"focus"===e.type),this._scrollIntoView(t),this.active=t.first(),s=this.active.addClass("ui-state-focus").removeClass("ui-state-active"),this.options.role&&this.element.attr("aria-activedescendant",s.attr("id")),this.active.parent().closest(".ui-menu-item").addClass("ui-state-active"),e&&"keydown"===e.type?this._close():this.timer=this._delay(function(){this._close()},this.delay),i=t.children(".ui-menu"),i.length&&e&&/^mouse/.test(e.type)&&this._startOpening(i),this.activeMenu=t.parent(),this._trigger("focus",e,{item:t})},_scrollIntoView:function(t){var i,s,n,a,o,r;this._hasScroll()&&(i=parseFloat(e.css(this.activeMenu[0],"borderTopWidth"))||0,s=parseFloat(e.css(this.activeMenu[0],"paddingTop"))||0,n=t.offset().top-this.activeMenu.offset().top-i-s,a=this.activeMenu.scrollTop(),o=this.activeMenu.height(),r=t.outerHeight(),0>n?this.activeMenu.scrollTop(a+n):n+r>o&&this.activeMenu.scrollTop(a+n-o+r))},blur:function(e,t){t||clearTimeout(this.timer),this.active&&(this.active.removeClass("ui-state-focus"),this.active=null,this._trigger("blur",e,{item:this.active}))},_startOpening:function(e){clearTimeout(this.timer),"true"===e.attr("aria-hidden")&&(this.timer=this._delay(function(){this._close(),this._open(e)},this.delay))},_open:function(t){var i=e.extend({of:this.active},this.options.position);clearTimeout(this.timer),this.element.find(".ui-menu").not(t.parents(".ui-menu")).hide().attr("aria-hidden","true"),t.show().removeAttr("aria-hidden").attr("aria-expanded","true").position(i)},collapseAll:function(t,i){clearTimeout(this.timer),this.timer=this._delay(function(){var s=i?this.element:e(t&&t.target).closest(this.element.find(".ui-menu"));s.length||(s=this.element),this._close(s),this.blur(t),this.activeMenu=s},this.delay)},_close:function(e){e||(e=this.active?this.active.parent():this.element),e.find(".ui-menu").hide().attr("aria-hidden","true").attr("aria-expanded","false").end().find(".ui-state-active").not(".ui-state-focus").removeClass("ui-state-active")},_closeOnDocumentClick:function(t){return!e(t.target).closest(".ui-menu").length},_isDivider:function(e){return!/[^\-\u2014\u2013\s]/.test(e.text())},collapse:function(e){var t=this.active&&this.active.parent().closest(".ui-menu-item",this.element);t&&t.length&&(this._close(),this.focus(e,t))},expand:function(e){var t=this.active&&this.active.children(".ui-menu ").find(this.options.items).first();t&&t.length&&(this._open(t.parent()),this._delay(function(){this.focus(e,t)}))},next:function(e){this._move("next","first",e)},previous:function(e){this._move("prev","last",e)},isFirstItem:function(){return this.active&&!this.active.prevAll(".ui-menu-item").length},isLastItem:function(){return this.active&&!this.active.nextAll(".ui-menu-item").length},_move:function(e,t,i){var s;this.active&&(s="first"===e||"last"===e?this.active["first"===e?"prevAll":"nextAll"](".ui-menu-item").eq(-1):this.active[e+"All"](".ui-menu-item").eq(0)),s&&s.length&&this.active||(s=this.activeMenu.find(this.options.items)[t]()),this.focus(i,s)},nextPage:function(t){var i,s,n;return this.active?(this.isLastItem()||(this._hasScroll()?(s=this.active.offset().top,n=this.element.height(),this.active.nextAll(".ui-menu-item").each(function(){return i=e(this),0>i.offset().top-s-n}),this.focus(t,i)):this.focus(t,this.activeMenu.find(this.options.items)[this.active?"last":"first"]())),void 0):(this.next(t),void 0)},previousPage:function(t){var i,s,n;return this.active?(this.isFirstItem()||(this._hasScroll()?(s=this.active.offset().top,n=this.element.height(),this.active.prevAll(".ui-menu-item").each(function(){return i=e(this),i.offset().top-s+n>0}),this.focus(t,i)):this.focus(t,this.activeMenu.find(this.options.items).first())),void 0):(this.next(t),void 0)},_hasScroll:function(){return this.element.outerHeight()<this.element.prop("scrollHeight")},select:function(t){this.active=this.active||e(t.target).closest(".ui-menu-item");var i={item:this.active};this.active.has(".ui-menu").length||this.collapseAll(t,!0),this._trigger("select",t,i)},_filterMenuItems:function(t){var i=t.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g,"\\$&"),s=RegExp("^"+i,"i");return this.activeMenu.find(this.options.items).filter(".ui-menu-item").filter(function(){return s.test(e.trim(e(this).text()))})}}),e.widget("ui.autocomplete",{version:"1.11.4",defaultElement:"<input>",options:{appendTo:null,autoFocus:!1,delay:300,minLength:1,position:{my:"left top",at:"left bottom",collision:"none"},source:null,change:null,close:null,focus:null,open:null,response:null,search:null,select:null},requestIndex:0,pending:0,_create:function(){var t,i,s,n=this.element[0].nodeName.toLowerCase(),a="textarea"===n,o="input"===n;this.isMultiLine=a?!0:o?!1:this.element.prop("isContentEditable"),this.valueMethod=this.element[a||o?"val":"text"],this.isNewMenu=!0,this.element.addClass("ui-autocomplete-input").attr("autocomplete","off"),this._on(this.element,{keydown:function(n){if(this.element.prop("readOnly"))return t=!0,s=!0,i=!0,void 0;t=!1,s=!1,i=!1;var a=e.ui.keyCode;switch(n.keyCode){case a.PAGE_UP:t=!0,this._move("previousPage",n);break;case a.PAGE_DOWN:t=!0,this._move("nextPage",n);break;case a.UP:t=!0,this._keyEvent("previous",n);break;case a.DOWN:t=!0,this._keyEvent("next",n);break;case a.ENTER:this.menu.active&&(t=!0,n.preventDefault(),this.menu.select(n));break;case a.TAB:this.menu.active&&this.menu.select(n);break;case a.ESCAPE:this.menu.element.is(":visible")&&(this.isMultiLine||this._value(this.term),this.close(n),n.preventDefault());break;default:i=!0,this._searchTimeout(n)}},keypress:function(s){if(t)return t=!1,(!this.isMultiLine||this.menu.element.is(":visible"))&&s.preventDefault(),void 0;if(!i){var n=e.ui.keyCode;switch(s.keyCode){case n.PAGE_UP:this._move("previousPage",s);break;case n.PAGE_DOWN:this._move("nextPage",s);break;case n.UP:this._keyEvent("previous",s);break;case n.DOWN:this._keyEvent("next",s)}}},input:function(e){return s?(s=!1,e.preventDefault(),void 0):(this._searchTimeout(e),void 0)},focus:function(){this.selectedItem=null,this.previous=this._value()},blur:function(e){return this.cancelBlur?(delete this.cancelBlur,void 0):(clearTimeout(this.searching),this.close(e),this._change(e),void 0)}}),this._initSource(),this.menu=e("<ul>").addClass("ui-autocomplete ui-front").appendTo(this._appendTo()).menu({role:null}).hide().menu("instance"),this._on(this.menu.element,{mousedown:function(t){t.preventDefault(),this.cancelBlur=!0,this._delay(function(){delete this.cancelBlur});var i=this.menu.element[0];e(t.target).closest(".ui-menu-item").length||this._delay(function(){var t=this;this.document.one("mousedown",function(s){s.target===t.element[0]||s.target===i||e.contains(i,s.target)||t.close()})})},menufocus:function(t,i){var s,n;return this.isNewMenu&&(this.isNewMenu=!1,t.originalEvent&&/^mouse/.test(t.originalEvent.type))?(this.menu.blur(),this.document.one("mousemove",function(){e(t.target).trigger(t.originalEvent)}),void 0):(n=i.item.data("ui-autocomplete-item"),!1!==this._trigger("focus",t,{item:n})&&t.originalEvent&&/^key/.test(t.originalEvent.type)&&this._value(n.value),s=i.item.attr("aria-label")||n.value,s&&e.trim(s).length&&(this.liveRegion.children().hide(),e("<div>").text(s).appendTo(this.liveRegion)),void 0)},menuselect:function(e,t){var i=t.item.data("ui-autocomplete-item"),s=this.previous;this.element[0]!==this.document[0].activeElement&&(this.element.focus(),this.previous=s,this._delay(function(){this.previous=s,this.selectedItem=i})),!1!==this._trigger("select",e,{item:i})&&this._value(i.value),this.term=this._value(),this.close(e),this.selectedItem=i}}),this.liveRegion=e("<span>",{role:"status","aria-live":"assertive","aria-relevant":"additions"}).addClass("ui-helper-hidden-accessible").appendTo(this.document[0].body),this._on(this.window,{beforeunload:function(){this.element.removeAttr("autocomplete")}})},_destroy:function(){clearTimeout(this.searching),this.element.removeClass("ui-autocomplete-input").removeAttr("autocomplete"),this.menu.element.remove(),this.liveRegion.remove()},_setOption:function(e,t){this._super(e,t),"source"===e&&this._initSource(),"appendTo"===e&&this.menu.element.appendTo(this._appendTo()),"disabled"===e&&t&&this.xhr&&this.xhr.abort()},_appendTo:function(){var t=this.options.appendTo;return t&&(t=t.jquery||t.nodeType?e(t):this.document.find(t).eq(0)),t&&t[0]||(t=this.element.closest(".ui-front")),t.length||(t=this.document[0].body),t},_initSource:function(){var t,i,s=this;e.isArray(this.options.source)?(t=this.options.source,this.source=function(i,s){s(e.ui.autocomplete.filter(t,i.term))}):"string"==typeof this.options.source?(i=this.options.source,this.source=function(t,n){s.xhr&&s.xhr.abort(),s.xhr=e.ajax({url:i,data:t,dataType:"json",success:function(e){n(e)},error:function(){n([])}})}):this.source=this.options.source},_searchTimeout:function(e){clearTimeout(this.searching),this.searching=this._delay(function(){var t=this.term===this._value(),i=this.menu.element.is(":visible"),s=e.altKey||e.ctrlKey||e.metaKey||e.shiftKey;(!t||t&&!i&&!s)&&(this.selectedItem=null,this.search(null,e))},this.options.delay)},search:function(e,t){return e=null!=e?e:this._value(),this.term=this._value(),e.length<this.options.minLength?this.close(t):this._trigger("search",t)!==!1?this._search(e):void 0},_search:function(e){this.pending++,this.element.addClass("ui-autocomplete-loading"),this.cancelSearch=!1,this.source({term:e},this._response())},_response:function(){var t=++this.requestIndex;return e.proxy(function(e){t===this.requestIndex&&this.__response(e),this.pending--,this.pending||this.element.removeClass("ui-autocomplete-loading")},this)},__response:function(e){e&&(e=this._normalize(e)),this._trigger("response",null,{content:e}),!this.options.disabled&&e&&e.length&&!this.cancelSearch?(this._suggest(e),this._trigger("open")):this._close()},close:function(e){this.cancelSearch=!0,this._close(e)},_close:function(e){this.menu.element.is(":visible")&&(this.menu.element.hide(),this.menu.blur(),this.isNewMenu=!0,this._trigger("close",e))},_change:function(e){this.previous!==this._value()&&this._trigger("change",e,{item:this.selectedItem})},_normalize:function(t){return t.length&&t[0].label&&t[0].value?t:e.map(t,function(t){return"string"==typeof t?{label:t,value:t}:e.extend({},t,{label:t.label||t.value,value:t.value||t.label})})},_suggest:function(t){var i=this.menu.element.empty();this._renderMenu(i,t),this.isNewMenu=!0,this.menu.refresh(),i.show(),this._resizeMenu(),i.position(e.extend({of:this.element},this.options.position)),this.options.autoFocus&&this.menu.next()},_resizeMenu:function(){var e=this.menu.element;e.outerWidth(Math.max(e.width("").outerWidth()+1,this.element.outerWidth()))},_renderMenu:function(t,i){var s=this;e.each(i,function(e,i){s._renderItemData(t,i)})},_renderItemData:function(e,t){return this._renderItem(e,t).data("ui-autocomplete-item",t)},_renderItem:function(t,i){return e("<li>").text(i.label).appendTo(t)},_move:function(e,t){return this.menu.element.is(":visible")?this.menu.isFirstItem()&&/^previous/.test(e)||this.menu.isLastItem()&&/^next/.test(e)?(this.isMultiLine||this._value(this.term),this.menu.blur(),void 0):(this.menu[e](t),void 0):(this.search(null,t),void 0)},widget:function(){return this.menu.element},_value:function(){return this.valueMethod.apply(this.element,arguments)},_keyEvent:function(e,t){(!this.isMultiLine||this.menu.element.is(":visible"))&&(this._move(e,t),t.preventDefault())}}),e.extend(e.ui.autocomplete,{escapeRegex:function(e){return e.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g,"\\$&")},filter:function(t,i){var s=RegExp(e.ui.autocomplete.escapeRegex(i),"i");return e.grep(t,function(e){return s.test(e.label||e.value||e)})}}),e.widget("ui.autocomplete",e.ui.autocomplete,{options:{messages:{noResults:"No search results.",results:function(e){return e+(e>1?" results are":" result is")+" available, use up and down arrow keys to navigate."}}},__response:function(t){var i;this._superApply(arguments),this.options.disabled||this.cancelSearch||(i=t&&t.length?this.options.messages.results(t.length):this.options.messages.noResults,this.liveRegion.children().hide(),e("<div>").text(i).appendTo(this.liveRegion))}}),e.ui.autocomplete;var c,p="ui-button ui-widget ui-state-default ui-corner-all",f="ui-button-icons-only ui-button-icon-only ui-button-text-icons ui-button-text-icon-primary ui-button-text-icon-secondary ui-button-text-only",m=function(){var t=e(this);setTimeout(function(){t.find(":ui-button").button("refresh")},1)},g=function(t){var i=t.name,s=t.form,n=e([]);return i&&(i=i.replace(/'/g,"\\'"),n=s?e(s).find("[name='"+i+"'][type=radio]"):e("[name='"+i+"'][type=radio]",t.ownerDocument).filter(function(){return!this.form})),n};e.widget("ui.button",{version:"1.11.4",defaultElement:"<button>",options:{disabled:null,text:!0,label:null,icons:{primary:null,secondary:null}},_create:function(){this.element.closest("form").unbind("reset"+this.eventNamespace).bind("reset"+this.eventNamespace,m),"boolean"!=typeof this.options.disabled?this.options.disabled=!!this.element.prop("disabled"):this.element.prop("disabled",this.options.disabled),this._determineButtonType(),this.hasTitle=!!this.buttonElement.attr("title");var t=this,i=this.options,s="checkbox"===this.type||"radio"===this.type,n=s?"":"ui-state-active";null===i.label&&(i.label="input"===this.type?this.buttonElement.val():this.buttonElement.html()),this._hoverable(this.buttonElement),this.buttonElement.addClass(p).attr("role","button").bind("mouseenter"+this.eventNamespace,function(){i.disabled||this===c&&e(this).addClass("ui-state-active")}).bind("mouseleave"+this.eventNamespace,function(){i.disabled||e(this).removeClass(n)}).bind("click"+this.eventNamespace,function(e){i.disabled&&(e.preventDefault(),e.stopImmediatePropagation())}),this._on({focus:function(){this.buttonElement.addClass("ui-state-focus")},blur:function(){this.buttonElement.removeClass("ui-state-focus")}}),s&&this.element.bind("change"+this.eventNamespace,function(){t.refresh()}),"checkbox"===this.type?this.buttonElement.bind("click"+this.eventNamespace,function(){return i.disabled?!1:void 0}):"radio"===this.type?this.buttonElement.bind("click"+this.eventNamespace,function(){if(i.disabled)return!1;e(this).addClass("ui-state-active"),t.buttonElement.attr("aria-pressed","true");var s=t.element[0];g(s).not(s).map(function(){return e(this).button("widget")[0]}).removeClass("ui-state-active").attr("aria-pressed","false")}):(this.buttonElement.bind("mousedown"+this.eventNamespace,function(){return i.disabled?!1:(e(this).addClass("ui-state-active"),c=this,t.document.one("mouseup",function(){c=null}),void 0)}).bind("mouseup"+this.eventNamespace,function(){return i.disabled?!1:(e(this).removeClass("ui-state-active"),void 0)}).bind("keydown"+this.eventNamespace,function(t){return i.disabled?!1:((t.keyCode===e.ui.keyCode.SPACE||t.keyCode===e.ui.keyCode.ENTER)&&e(this).addClass("ui-state-active"),void 0)}).bind("keyup"+this.eventNamespace+" blur"+this.eventNamespace,function(){e(this).removeClass("ui-state-active")}),this.buttonElement.is("a")&&this.buttonElement.keyup(function(t){t.keyCode===e.ui.keyCode.SPACE&&e(this).click()})),this._setOption("disabled",i.disabled),this._resetButton()},_determineButtonType:function(){var e,t,i;this.type=this.element.is("[type=checkbox]")?"checkbox":this.element.is("[type=radio]")?"radio":this.element.is("input")?"input":"button","checkbox"===this.type||"radio"===this.type?(e=this.element.parents().last(),t="label[for='"+this.element.attr("id")+"']",this.buttonElement=e.find(t),this.buttonElement.length||(e=e.length?e.siblings():this.element.siblings(),this.buttonElement=e.filter(t),this.buttonElement.length||(this.buttonElement=e.find(t))),this.element.addClass("ui-helper-hidden-accessible"),i=this.element.is(":checked"),i&&this.buttonElement.addClass("ui-state-active"),this.buttonElement.prop("aria-pressed",i)):this.buttonElement=this.element},widget:function(){return this.buttonElement},_destroy:function(){this.element.removeClass("ui-helper-hidden-accessible"),this.buttonElement.removeClass(p+" ui-state-active "+f).removeAttr("role").removeAttr("aria-pressed").html(this.buttonElement.find(".ui-button-text").html()),this.hasTitle||this.buttonElement.removeAttr("title")},_setOption:function(e,t){return this._super(e,t),"disabled"===e?(this.widget().toggleClass("ui-state-disabled",!!t),this.element.prop("disabled",!!t),t&&("checkbox"===this.type||"radio"===this.type?this.buttonElement.removeClass("ui-state-focus"):this.buttonElement.removeClass("ui-state-focus ui-state-active")),void 0):(this._resetButton(),void 0)},refresh:function(){var t=this.element.is("input, button")?this.element.is(":disabled"):this.element.hasClass("ui-button-disabled");t!==this.options.disabled&&this._setOption("disabled",t),"radio"===this.type?g(this.element[0]).each(function(){e(this).is(":checked")?e(this).button("widget").addClass("ui-state-active").attr("aria-pressed","true"):e(this).button("widget").removeClass("ui-state-active").attr("aria-pressed","false")}):"checkbox"===this.type&&(this.element.is(":checked")?this.buttonElement.addClass("ui-state-active").attr("aria-pressed","true"):this.buttonElement.removeClass("ui-state-active").attr("aria-pressed","false"))},_resetButton:function(){if("input"===this.type)return this.options.label&&this.element.val(this.options.label),void 0;var t=this.buttonElement.removeClass(f),i=e("<span></span>",this.document[0]).addClass("ui-button-text").html(this.options.label).appendTo(t.empty()).text(),s=this.options.icons,n=s.primary&&s.secondary,a=[];s.primary||s.secondary?(this.options.text&&a.push("ui-button-text-icon"+(n?"s":s.primary?"-primary":"-secondary")),s.primary&&t.prepend("<span class='ui-button-icon-primary ui-icon "+s.primary+"'></span>"),s.secondary&&t.append("<span class='ui-button-icon-secondary ui-icon "+s.secondary+"'></span>"),this.options.text||(a.push(n?"ui-button-icons-only":"ui-button-icon-only"),this.hasTitle||t.attr("title",e.trim(i)))):a.push("ui-button-text-only"),t.addClass(a.join(" "))}}),e.widget("ui.buttonset",{version:"1.11.4",options:{items:"button, input[type=button], input[type=submit], input[type=reset], input[type=checkbox], input[type=radio], a, :data(ui-button)"},_create:function(){this.element.addClass("ui-buttonset")},_init:function(){this.refresh()},_setOption:function(e,t){"disabled"===e&&this.buttons.button("option",e,t),this._super(e,t)},refresh:function(){var t="rtl"===this.element.css("direction"),i=this.element.find(this.options.items),s=i.filter(":ui-button");i.not(":ui-button").button(),s.button("refresh"),this.buttons=i.map(function(){return e(this).button("widget")[0]}).removeClass("ui-corner-all ui-corner-left ui-corner-right").filter(":first").addClass(t?"ui-corner-right":"ui-corner-left").end().filter(":last").addClass(t?"ui-corner-left":"ui-corner-right").end().end()},_destroy:function(){this.element.removeClass("ui-buttonset"),this.buttons.map(function(){return e(this).button("widget")[0]}).removeClass("ui-corner-left ui-corner-right").end().button("destroy")}}),e.ui.button,e.extend(e.ui,{datepicker:{version:"1.11.4"}});var v;e.extend(n.prototype,{markerClassName:"hasDatepicker",maxRows:4,_widgetDatepicker:function(){return this.dpDiv},setDefaults:function(e){return r(this._defaults,e||{}),this},_attachDatepicker:function(t,i){var s,n,a;s=t.nodeName.toLowerCase(),n="div"===s||"span"===s,t.id||(this.uuid+=1,t.id="dp"+this.uuid),a=this._newInst(e(t),n),a.settings=e.extend({},i||{}),"input"===s?this._connectDatepicker(t,a):n&&this._inlineDatepicker(t,a)},_newInst:function(t,i){var s=t[0].id.replace(/([^A-Za-z0-9_\-])/g,"\\\\$1");return{id:s,input:t,selectedDay:0,selectedMonth:0,selectedYear:0,drawMonth:0,drawYear:0,inline:i,dpDiv:i?a(e("<div class='"+this._inlineClass+" ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>")):this.dpDiv}},_connectDatepicker:function(t,i){var s=e(t);i.append=e([]),i.trigger=e([]),s.hasClass(this.markerClassName)||(this._attachments(s,i),s.addClass(this.markerClassName).keydown(this._doKeyDown).keypress(this._doKeyPress).keyup(this._doKeyUp),this._autoSize(i),e.data(t,"datepicker",i),i.settings.disabled&&this._disableDatepicker(t))},_attachments:function(t,i){var s,n,a,o=this._get(i,"appendText"),r=this._get(i,"isRTL");i.append&&i.append.remove(),o&&(i.append=e("<span class='"+this._appendClass+"'>"+o+"</span>"),t[r?"before":"after"](i.append)),t.unbind("focus",this._showDatepicker),i.trigger&&i.trigger.remove(),s=this._get(i,"showOn"),("focus"===s||"both"===s)&&t.focus(this._showDatepicker),("button"===s||"both"===s)&&(n=this._get(i,"buttonText"),a=this._get(i,"buttonImage"),i.trigger=e(this._get(i,"buttonImageOnly")?e("<img/>").addClass(this._triggerClass).attr({src:a,alt:n,title:n}):e("<button type='button'></button>").addClass(this._triggerClass).html(a?e("<img/>").attr({src:a,alt:n,title:n}):n)),t[r?"before":"after"](i.trigger),i.trigger.click(function(){return e.datepicker._datepickerShowing&&e.datepicker._lastInput===t[0]?e.datepicker._hideDatepicker():e.datepicker._datepickerShowing&&e.datepicker._lastInput!==t[0]?(e.datepicker._hideDatepicker(),e.datepicker._showDatepicker(t[0])):e.datepicker._showDatepicker(t[0]),!1}))},_autoSize:function(e){if(this._get(e,"autoSize")&&!e.inline){var t,i,s,n,a=new Date(2009,11,20),o=this._get(e,"dateFormat");o.match(/[DM]/)&&(t=function(e){for(i=0,s=0,n=0;e.length>n;n++)e[n].length>i&&(i=e[n].length,s=n);return s},a.setMonth(t(this._get(e,o.match(/MM/)?"monthNames":"monthNamesShort"))),a.setDate(t(this._get(e,o.match(/DD/)?"dayNames":"dayNamesShort"))+20-a.getDay())),e.input.attr("size",this._formatDate(e,a).length)}},_inlineDatepicker:function(t,i){var s=e(t);s.hasClass(this.markerClassName)||(s.addClass(this.markerClassName).append(i.dpDiv),e.data(t,"datepicker",i),this._setDate(i,this._getDefaultDate(i),!0),this._updateDatepicker(i),this._updateAlternate(i),i.settings.disabled&&this._disableDatepicker(t),i.dpDiv.css("display","block"))},_dialogDatepicker:function(t,i,s,n,a){var o,h,l,u,d,c=this._dialogInst;return c||(this.uuid+=1,o="dp"+this.uuid,this._dialogInput=e("<input type='text' id='"+o+"' style='position: absolute; top: -100px; width: 0px;'/>"),this._dialogInput.keydown(this._doKeyDown),e("body").append(this._dialogInput),c=this._dialogInst=this._newInst(this._dialogInput,!1),c.settings={},e.data(this._dialogInput[0],"datepicker",c)),r(c.settings,n||{}),i=i&&i.constructor===Date?this._formatDate(c,i):i,this._dialogInput.val(i),this._pos=a?a.length?a:[a.pageX,a.pageY]:null,this._pos||(h=document.documentElement.clientWidth,l=document.documentElement.clientHeight,u=document.documentElement.scrollLeft||document.body.scrollLeft,d=document.documentElement.scrollTop||document.body.scrollTop,this._pos=[h/2-100+u,l/2-150+d]),this._dialogInput.css("left",this._pos[0]+20+"px").css("top",this._pos[1]+"px"),c.settings.onSelect=s,this._inDialog=!0,this.dpDiv.addClass(this._dialogClass),this._showDatepicker(this._dialogInput[0]),e.blockUI&&e.blockUI(this.dpDiv),e.data(this._dialogInput[0],"datepicker",c),this},_destroyDatepicker:function(t){var i,s=e(t),n=e.data(t,"datepicker");s.hasClass(this.markerClassName)&&(i=t.nodeName.toLowerCase(),e.removeData(t,"datepicker"),"input"===i?(n.append.remove(),n.trigger.remove(),s.removeClass(this.markerClassName).unbind("focus",this._showDatepicker).unbind("keydown",this._doKeyDown).unbind("keypress",this._doKeyPress).unbind("keyup",this._doKeyUp)):("div"===i||"span"===i)&&s.removeClass(this.markerClassName).empty(),v===n&&(v=null))},_enableDatepicker:function(t){var i,s,n=e(t),a=e.data(t,"datepicker");n.hasClass(this.markerClassName)&&(i=t.nodeName.toLowerCase(),"input"===i?(t.disabled=!1,a.trigger.filter("button").each(function(){this.disabled=!1}).end().filter("img").css({opacity:"1.0",cursor:""})):("div"===i||"span"===i)&&(s=n.children("."+this._inlineClass),s.children().removeClass("ui-state-disabled"),s.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled",!1)),this._disabledInputs=e.map(this._disabledInputs,function(e){return e===t?null:e}))},_disableDatepicker:function(t){var i,s,n=e(t),a=e.data(t,"datepicker");n.hasClass(this.markerClassName)&&(i=t.nodeName.toLowerCase(),"input"===i?(t.disabled=!0,a.trigger.filter("button").each(function(){this.disabled=!0}).end().filter("img").css({opacity:"0.5",cursor:"default"})):("div"===i||"span"===i)&&(s=n.children("."+this._inlineClass),s.children().addClass("ui-state-disabled"),s.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled",!0)),this._disabledInputs=e.map(this._disabledInputs,function(e){return e===t?null:e}),this._disabledInputs[this._disabledInputs.length]=t)},_isDisabledDatepicker:function(e){if(!e)return!1;for(var t=0;this._disabledInputs.length>t;t++)if(this._disabledInputs[t]===e)return!0;return!1},_getInst:function(t){try{return e.data(t,"datepicker")}catch(i){throw"Missing instance data for this datepicker"}},_optionDatepicker:function(t,i,s){var n,a,o,h,l=this._getInst(t);return 2===arguments.length&&"string"==typeof i?"defaults"===i?e.extend({},e.datepicker._defaults):l?"all"===i?e.extend({},l.settings):this._get(l,i):null:(n=i||{},"string"==typeof i&&(n={},n[i]=s),l&&(this._curInst===l&&this._hideDatepicker(),a=this._getDateDatepicker(t,!0),o=this._getMinMaxDate(l,"min"),h=this._getMinMaxDate(l,"max"),r(l.settings,n),null!==o&&void 0!==n.dateFormat&&void 0===n.minDate&&(l.settings.minDate=this._formatDate(l,o)),null!==h&&void 0!==n.dateFormat&&void 0===n.maxDate&&(l.settings.maxDate=this._formatDate(l,h)),"disabled"in n&&(n.disabled?this._disableDatepicker(t):this._enableDatepicker(t)),this._attachments(e(t),l),this._autoSize(l),this._setDate(l,a),this._updateAlternate(l),this._updateDatepicker(l)),void 0)},_changeDatepicker:function(e,t,i){this._optionDatepicker(e,t,i)},_refreshDatepicker:function(e){var t=this._getInst(e);t&&this._updateDatepicker(t)},_setDateDatepicker:function(e,t){var i=this._getInst(e);i&&(this._setDate(i,t),this._updateDatepicker(i),this._updateAlternate(i))},_getDateDatepicker:function(e,t){var i=this._getInst(e);return i&&!i.inline&&this._setDateFromField(i,t),i?this._getDate(i):null},_doKeyDown:function(t){var i,s,n,a=e.datepicker._getInst(t.target),o=!0,r=a.dpDiv.is(".ui-datepicker-rtl");if(a._keyEvent=!0,e.datepicker._datepickerShowing)switch(t.keyCode){case 9:e.datepicker._hideDatepicker(),o=!1;break;case 13:return n=e("td."+e.datepicker._dayOverClass+":not(."+e.datepicker._currentClass+")",a.dpDiv),n[0]&&e.datepicker._selectDay(t.target,a.selectedMonth,a.selectedYear,n[0]),i=e.datepicker._get(a,"onSelect"),i?(s=e.datepicker._formatDate(a),i.apply(a.input?a.input[0]:null,[s,a])):e.datepicker._hideDatepicker(),!1;case 27:e.datepicker._hideDatepicker();break;case 33:e.datepicker._adjustDate(t.target,t.ctrlKey?-e.datepicker._get(a,"stepBigMonths"):-e.datepicker._get(a,"stepMonths"),"M");break;case 34:e.datepicker._adjustDate(t.target,t.ctrlKey?+e.datepicker._get(a,"stepBigMonths"):+e.datepicker._get(a,"stepMonths"),"M");break;case 35:(t.ctrlKey||t.metaKey)&&e.datepicker._clearDate(t.target),o=t.ctrlKey||t.metaKey;break;case 36:(t.ctrlKey||t.metaKey)&&e.datepicker._gotoToday(t.target),o=t.ctrlKey||t.metaKey;break;case 37:(t.ctrlKey||t.metaKey)&&e.datepicker._adjustDate(t.target,r?1:-1,"D"),o=t.ctrlKey||t.metaKey,t.originalEvent.altKey&&e.datepicker._adjustDate(t.target,t.ctrlKey?-e.datepicker._get(a,"stepBigMonths"):-e.datepicker._get(a,"stepMonths"),"M");break;case 38:(t.ctrlKey||t.metaKey)&&e.datepicker._adjustDate(t.target,-7,"D"),o=t.ctrlKey||t.metaKey;break;case 39:(t.ctrlKey||t.metaKey)&&e.datepicker._adjustDate(t.target,r?-1:1,"D"),o=t.ctrlKey||t.metaKey,t.originalEvent.altKey&&e.datepicker._adjustDate(t.target,t.ctrlKey?+e.datepicker._get(a,"stepBigMonths"):+e.datepicker._get(a,"stepMonths"),"M");break;case 40:(t.ctrlKey||t.metaKey)&&e.datepicker._adjustDate(t.target,7,"D"),o=t.ctrlKey||t.metaKey;break;default:o=!1}else 36===t.keyCode&&t.ctrlKey?e.datepicker._showDatepicker(this):o=!1;o&&(t.preventDefault(),t.stopPropagation())},_doKeyPress:function(t){var i,s,n=e.datepicker._getInst(t.target);
return e.datepicker._get(n,"constrainInput")?(i=e.datepicker._possibleChars(e.datepicker._get(n,"dateFormat")),s=String.fromCharCode(null==t.charCode?t.keyCode:t.charCode),t.ctrlKey||t.metaKey||" ">s||!i||i.indexOf(s)>-1):void 0},_doKeyUp:function(t){var i,s=e.datepicker._getInst(t.target);if(s.input.val()!==s.lastVal)try{i=e.datepicker.parseDate(e.datepicker._get(s,"dateFormat"),s.input?s.input.val():null,e.datepicker._getFormatConfig(s)),i&&(e.datepicker._setDateFromField(s),e.datepicker._updateAlternate(s),e.datepicker._updateDatepicker(s))}catch(n){}return!0},_showDatepicker:function(t){if(t=t.target||t,"input"!==t.nodeName.toLowerCase()&&(t=e("input",t.parentNode)[0]),!e.datepicker._isDisabledDatepicker(t)&&e.datepicker._lastInput!==t){var i,n,a,o,h,l,u;i=e.datepicker._getInst(t),e.datepicker._curInst&&e.datepicker._curInst!==i&&(e.datepicker._curInst.dpDiv.stop(!0,!0),i&&e.datepicker._datepickerShowing&&e.datepicker._hideDatepicker(e.datepicker._curInst.input[0])),n=e.datepicker._get(i,"beforeShow"),a=n?n.apply(t,[t,i]):{},a!==!1&&(r(i.settings,a),i.lastVal=null,e.datepicker._lastInput=t,e.datepicker._setDateFromField(i),e.datepicker._inDialog&&(t.value=""),e.datepicker._pos||(e.datepicker._pos=e.datepicker._findPos(t),e.datepicker._pos[1]+=t.offsetHeight),o=!1,e(t).parents().each(function(){return o|="fixed"===e(this).css("position"),!o}),h={left:e.datepicker._pos[0],top:e.datepicker._pos[1]},e.datepicker._pos=null,i.dpDiv.empty(),i.dpDiv.css({position:"absolute",display:"block",top:"-1000px"}),e.datepicker._updateDatepicker(i),h=e.datepicker._checkOffset(i,h,o),i.dpDiv.css({position:e.datepicker._inDialog&&e.blockUI?"static":o?"fixed":"absolute",display:"none",left:h.left+"px",top:h.top+"px"}),i.inline||(l=e.datepicker._get(i,"showAnim"),u=e.datepicker._get(i,"duration"),i.dpDiv.css("z-index",s(e(t))+1),e.datepicker._datepickerShowing=!0,e.effects&&e.effects.effect[l]?i.dpDiv.show(l,e.datepicker._get(i,"showOptions"),u):i.dpDiv[l||"show"](l?u:null),e.datepicker._shouldFocusInput(i)&&i.input.focus(),e.datepicker._curInst=i))}},_updateDatepicker:function(t){this.maxRows=4,v=t,t.dpDiv.empty().append(this._generateHTML(t)),this._attachHandlers(t);var i,s=this._getNumberOfMonths(t),n=s[1],a=17,r=t.dpDiv.find("."+this._dayOverClass+" a");r.length>0&&o.apply(r.get(0)),t.dpDiv.removeClass("ui-datepicker-multi-2 ui-datepicker-multi-3 ui-datepicker-multi-4").width(""),n>1&&t.dpDiv.addClass("ui-datepicker-multi-"+n).css("width",a*n+"em"),t.dpDiv[(1!==s[0]||1!==s[1]?"add":"remove")+"Class"]("ui-datepicker-multi"),t.dpDiv[(this._get(t,"isRTL")?"add":"remove")+"Class"]("ui-datepicker-rtl"),t===e.datepicker._curInst&&e.datepicker._datepickerShowing&&e.datepicker._shouldFocusInput(t)&&t.input.focus(),t.yearshtml&&(i=t.yearshtml,setTimeout(function(){i===t.yearshtml&&t.yearshtml&&t.dpDiv.find("select.ui-datepicker-year:first").replaceWith(t.yearshtml),i=t.yearshtml=null},0))},_shouldFocusInput:function(e){return e.input&&e.input.is(":visible")&&!e.input.is(":disabled")&&!e.input.is(":focus")},_checkOffset:function(t,i,s){var n=t.dpDiv.outerWidth(),a=t.dpDiv.outerHeight(),o=t.input?t.input.outerWidth():0,r=t.input?t.input.outerHeight():0,h=document.documentElement.clientWidth+(s?0:e(document).scrollLeft()),l=document.documentElement.clientHeight+(s?0:e(document).scrollTop());return i.left-=this._get(t,"isRTL")?n-o:0,i.left-=s&&i.left===t.input.offset().left?e(document).scrollLeft():0,i.top-=s&&i.top===t.input.offset().top+r?e(document).scrollTop():0,i.left-=Math.min(i.left,i.left+n>h&&h>n?Math.abs(i.left+n-h):0),i.top-=Math.min(i.top,i.top+a>l&&l>a?Math.abs(a+r):0),i},_findPos:function(t){for(var i,s=this._getInst(t),n=this._get(s,"isRTL");t&&("hidden"===t.type||1!==t.nodeType||e.expr.filters.hidden(t));)t=t[n?"previousSibling":"nextSibling"];return i=e(t).offset(),[i.left,i.top]},_hideDatepicker:function(t){var i,s,n,a,o=this._curInst;!o||t&&o!==e.data(t,"datepicker")||this._datepickerShowing&&(i=this._get(o,"showAnim"),s=this._get(o,"duration"),n=function(){e.datepicker._tidyDialog(o)},e.effects&&(e.effects.effect[i]||e.effects[i])?o.dpDiv.hide(i,e.datepicker._get(o,"showOptions"),s,n):o.dpDiv["slideDown"===i?"slideUp":"fadeIn"===i?"fadeOut":"hide"](i?s:null,n),i||n(),this._datepickerShowing=!1,a=this._get(o,"onClose"),a&&a.apply(o.input?o.input[0]:null,[o.input?o.input.val():"",o]),this._lastInput=null,this._inDialog&&(this._dialogInput.css({position:"absolute",left:"0",top:"-100px"}),e.blockUI&&(e.unblockUI(),e("body").append(this.dpDiv))),this._inDialog=!1)},_tidyDialog:function(e){e.dpDiv.removeClass(this._dialogClass).unbind(".ui-datepicker-calendar")},_checkExternalClick:function(t){if(e.datepicker._curInst){var i=e(t.target),s=e.datepicker._getInst(i[0]);(i[0].id!==e.datepicker._mainDivId&&0===i.parents("#"+e.datepicker._mainDivId).length&&!i.hasClass(e.datepicker.markerClassName)&&!i.closest("."+e.datepicker._triggerClass).length&&e.datepicker._datepickerShowing&&(!e.datepicker._inDialog||!e.blockUI)||i.hasClass(e.datepicker.markerClassName)&&e.datepicker._curInst!==s)&&e.datepicker._hideDatepicker()}},_adjustDate:function(t,i,s){var n=e(t),a=this._getInst(n[0]);this._isDisabledDatepicker(n[0])||(this._adjustInstDate(a,i+("M"===s?this._get(a,"showCurrentAtPos"):0),s),this._updateDatepicker(a))},_gotoToday:function(t){var i,s=e(t),n=this._getInst(s[0]);this._get(n,"gotoCurrent")&&n.currentDay?(n.selectedDay=n.currentDay,n.drawMonth=n.selectedMonth=n.currentMonth,n.drawYear=n.selectedYear=n.currentYear):(i=new Date,n.selectedDay=i.getDate(),n.drawMonth=n.selectedMonth=i.getMonth(),n.drawYear=n.selectedYear=i.getFullYear()),this._notifyChange(n),this._adjustDate(s)},_selectMonthYear:function(t,i,s){var n=e(t),a=this._getInst(n[0]);a["selected"+("M"===s?"Month":"Year")]=a["draw"+("M"===s?"Month":"Year")]=parseInt(i.options[i.selectedIndex].value,10),this._notifyChange(a),this._adjustDate(n)},_selectDay:function(t,i,s,n){var a,o=e(t);e(n).hasClass(this._unselectableClass)||this._isDisabledDatepicker(o[0])||(a=this._getInst(o[0]),a.selectedDay=a.currentDay=e("a",n).html(),a.selectedMonth=a.currentMonth=i,a.selectedYear=a.currentYear=s,this._selectDate(t,this._formatDate(a,a.currentDay,a.currentMonth,a.currentYear)))},_clearDate:function(t){var i=e(t);this._selectDate(i,"")},_selectDate:function(t,i){var s,n=e(t),a=this._getInst(n[0]);i=null!=i?i:this._formatDate(a),a.input&&a.input.val(i),this._updateAlternate(a),s=this._get(a,"onSelect"),s?s.apply(a.input?a.input[0]:null,[i,a]):a.input&&a.input.trigger("change"),a.inline?this._updateDatepicker(a):(this._hideDatepicker(),this._lastInput=a.input[0],"object"!=typeof a.input[0]&&a.input.focus(),this._lastInput=null)},_updateAlternate:function(t){var i,s,n,a=this._get(t,"altField");a&&(i=this._get(t,"altFormat")||this._get(t,"dateFormat"),s=this._getDate(t),n=this.formatDate(i,s,this._getFormatConfig(t)),e(a).each(function(){e(this).val(n)}))},noWeekends:function(e){var t=e.getDay();return[t>0&&6>t,""]},iso8601Week:function(e){var t,i=new Date(e.getTime());return i.setDate(i.getDate()+4-(i.getDay()||7)),t=i.getTime(),i.setMonth(0),i.setDate(1),Math.floor(Math.round((t-i)/864e5)/7)+1},parseDate:function(t,i,s){if(null==t||null==i)throw"Invalid arguments";if(i="object"==typeof i?""+i:i+"",""===i)return null;var n,a,o,r,h=0,l=(s?s.shortYearCutoff:null)||this._defaults.shortYearCutoff,u="string"!=typeof l?l:(new Date).getFullYear()%100+parseInt(l,10),d=(s?s.dayNamesShort:null)||this._defaults.dayNamesShort,c=(s?s.dayNames:null)||this._defaults.dayNames,p=(s?s.monthNamesShort:null)||this._defaults.monthNamesShort,f=(s?s.monthNames:null)||this._defaults.monthNames,m=-1,g=-1,v=-1,y=-1,b=!1,_=function(e){var i=t.length>n+1&&t.charAt(n+1)===e;return i&&n++,i},x=function(e){var t=_(e),s="@"===e?14:"!"===e?20:"y"===e&&t?4:"o"===e?3:2,n="y"===e?s:1,a=RegExp("^\\d{"+n+","+s+"}"),o=i.substring(h).match(a);if(!o)throw"Missing number at position "+h;return h+=o[0].length,parseInt(o[0],10)},w=function(t,s,n){var a=-1,o=e.map(_(t)?n:s,function(e,t){return[[t,e]]}).sort(function(e,t){return-(e[1].length-t[1].length)});if(e.each(o,function(e,t){var s=t[1];return i.substr(h,s.length).toLowerCase()===s.toLowerCase()?(a=t[0],h+=s.length,!1):void 0}),-1!==a)return a+1;throw"Unknown name at position "+h},k=function(){if(i.charAt(h)!==t.charAt(n))throw"Unexpected literal at position "+h;h++};for(n=0;t.length>n;n++)if(b)"'"!==t.charAt(n)||_("'")?k():b=!1;else switch(t.charAt(n)){case"d":v=x("d");break;case"D":w("D",d,c);break;case"o":y=x("o");break;case"m":g=x("m");break;case"M":g=w("M",p,f);break;case"y":m=x("y");break;case"@":r=new Date(x("@")),m=r.getFullYear(),g=r.getMonth()+1,v=r.getDate();break;case"!":r=new Date((x("!")-this._ticksTo1970)/1e4),m=r.getFullYear(),g=r.getMonth()+1,v=r.getDate();break;case"'":_("'")?k():b=!0;break;default:k()}if(i.length>h&&(o=i.substr(h),!/^\s+/.test(o)))throw"Extra/unparsed characters found in date: "+o;if(-1===m?m=(new Date).getFullYear():100>m&&(m+=(new Date).getFullYear()-(new Date).getFullYear()%100+(u>=m?0:-100)),y>-1)for(g=1,v=y;;){if(a=this._getDaysInMonth(m,g-1),a>=v)break;g++,v-=a}if(r=this._daylightSavingAdjust(new Date(m,g-1,v)),r.getFullYear()!==m||r.getMonth()+1!==g||r.getDate()!==v)throw"Invalid date";return r},ATOM:"yy-mm-dd",COOKIE:"D, dd M yy",ISO_8601:"yy-mm-dd",RFC_822:"D, d M y",RFC_850:"DD, dd-M-y",RFC_1036:"D, d M y",RFC_1123:"D, d M yy",RFC_2822:"D, d M yy",RSS:"D, d M y",TICKS:"!",TIMESTAMP:"@",W3C:"yy-mm-dd",_ticksTo1970:1e7*60*60*24*(718685+Math.floor(492.5)-Math.floor(19.7)+Math.floor(4.925)),formatDate:function(e,t,i){if(!t)return"";var s,n=(i?i.dayNamesShort:null)||this._defaults.dayNamesShort,a=(i?i.dayNames:null)||this._defaults.dayNames,o=(i?i.monthNamesShort:null)||this._defaults.monthNamesShort,r=(i?i.monthNames:null)||this._defaults.monthNames,h=function(t){var i=e.length>s+1&&e.charAt(s+1)===t;return i&&s++,i},l=function(e,t,i){var s=""+t;if(h(e))for(;i>s.length;)s="0"+s;return s},u=function(e,t,i,s){return h(e)?s[t]:i[t]},d="",c=!1;if(t)for(s=0;e.length>s;s++)if(c)"'"!==e.charAt(s)||h("'")?d+=e.charAt(s):c=!1;else switch(e.charAt(s)){case"d":d+=l("d",t.getDate(),2);break;case"D":d+=u("D",t.getDay(),n,a);break;case"o":d+=l("o",Math.round((new Date(t.getFullYear(),t.getMonth(),t.getDate()).getTime()-new Date(t.getFullYear(),0,0).getTime())/864e5),3);break;case"m":d+=l("m",t.getMonth()+1,2);break;case"M":d+=u("M",t.getMonth(),o,r);break;case"y":d+=h("y")?t.getFullYear():(10>t.getYear()%100?"0":"")+t.getYear()%100;break;case"@":d+=t.getTime();break;case"!":d+=1e4*t.getTime()+this._ticksTo1970;break;case"'":h("'")?d+="'":c=!0;break;default:d+=e.charAt(s)}return d},_possibleChars:function(e){var t,i="",s=!1,n=function(i){var s=e.length>t+1&&e.charAt(t+1)===i;return s&&t++,s};for(t=0;e.length>t;t++)if(s)"'"!==e.charAt(t)||n("'")?i+=e.charAt(t):s=!1;else switch(e.charAt(t)){case"d":case"m":case"y":case"@":i+="0123456789";break;case"D":case"M":return null;case"'":n("'")?i+="'":s=!0;break;default:i+=e.charAt(t)}return i},_get:function(e,t){return void 0!==e.settings[t]?e.settings[t]:this._defaults[t]},_setDateFromField:function(e,t){if(e.input.val()!==e.lastVal){var i=this._get(e,"dateFormat"),s=e.lastVal=e.input?e.input.val():null,n=this._getDefaultDate(e),a=n,o=this._getFormatConfig(e);try{a=this.parseDate(i,s,o)||n}catch(r){s=t?"":s}e.selectedDay=a.getDate(),e.drawMonth=e.selectedMonth=a.getMonth(),e.drawYear=e.selectedYear=a.getFullYear(),e.currentDay=s?a.getDate():0,e.currentMonth=s?a.getMonth():0,e.currentYear=s?a.getFullYear():0,this._adjustInstDate(e)}},_getDefaultDate:function(e){return this._restrictMinMax(e,this._determineDate(e,this._get(e,"defaultDate"),new Date))},_determineDate:function(t,i,s){var n=function(e){var t=new Date;return t.setDate(t.getDate()+e),t},a=function(i){try{return e.datepicker.parseDate(e.datepicker._get(t,"dateFormat"),i,e.datepicker._getFormatConfig(t))}catch(s){}for(var n=(i.toLowerCase().match(/^c/)?e.datepicker._getDate(t):null)||new Date,a=n.getFullYear(),o=n.getMonth(),r=n.getDate(),h=/([+\-]?[0-9]+)\s*(d|D|w|W|m|M|y|Y)?/g,l=h.exec(i);l;){switch(l[2]||"d"){case"d":case"D":r+=parseInt(l[1],10);break;case"w":case"W":r+=7*parseInt(l[1],10);break;case"m":case"M":o+=parseInt(l[1],10),r=Math.min(r,e.datepicker._getDaysInMonth(a,o));break;case"y":case"Y":a+=parseInt(l[1],10),r=Math.min(r,e.datepicker._getDaysInMonth(a,o))}l=h.exec(i)}return new Date(a,o,r)},o=null==i||""===i?s:"string"==typeof i?a(i):"number"==typeof i?isNaN(i)?s:n(i):new Date(i.getTime());return o=o&&"Invalid Date"==""+o?s:o,o&&(o.setHours(0),o.setMinutes(0),o.setSeconds(0),o.setMilliseconds(0)),this._daylightSavingAdjust(o)},_daylightSavingAdjust:function(e){return e?(e.setHours(e.getHours()>12?e.getHours()+2:0),e):null},_setDate:function(e,t,i){var s=!t,n=e.selectedMonth,a=e.selectedYear,o=this._restrictMinMax(e,this._determineDate(e,t,new Date));e.selectedDay=e.currentDay=o.getDate(),e.drawMonth=e.selectedMonth=e.currentMonth=o.getMonth(),e.drawYear=e.selectedYear=e.currentYear=o.getFullYear(),n===e.selectedMonth&&a===e.selectedYear||i||this._notifyChange(e),this._adjustInstDate(e),e.input&&e.input.val(s?"":this._formatDate(e))},_getDate:function(e){var t=!e.currentYear||e.input&&""===e.input.val()?null:this._daylightSavingAdjust(new Date(e.currentYear,e.currentMonth,e.currentDay));return t},_attachHandlers:function(t){var i=this._get(t,"stepMonths"),s="#"+t.id.replace(/\\\\/g,"\\");t.dpDiv.find("[data-handler]").map(function(){var t={prev:function(){e.datepicker._adjustDate(s,-i,"M")},next:function(){e.datepicker._adjustDate(s,+i,"M")},hide:function(){e.datepicker._hideDatepicker()},today:function(){e.datepicker._gotoToday(s)},selectDay:function(){return e.datepicker._selectDay(s,+this.getAttribute("data-month"),+this.getAttribute("data-year"),this),!1},selectMonth:function(){return e.datepicker._selectMonthYear(s,this,"M"),!1},selectYear:function(){return e.datepicker._selectMonthYear(s,this,"Y"),!1}};e(this).bind(this.getAttribute("data-event"),t[this.getAttribute("data-handler")])})},_generateHTML:function(e){var t,i,s,n,a,o,r,h,l,u,d,c,p,f,m,g,v,y,b,_,x,w,k,T,D,S,M,C,N,A,P,I,H,z,F,E,O,j,W,L=new Date,R=this._daylightSavingAdjust(new Date(L.getFullYear(),L.getMonth(),L.getDate())),Y=this._get(e,"isRTL"),B=this._get(e,"showButtonPanel"),J=this._get(e,"hideIfNoPrevNext"),q=this._get(e,"navigationAsDateFormat"),K=this._getNumberOfMonths(e),V=this._get(e,"showCurrentAtPos"),U=this._get(e,"stepMonths"),Q=1!==K[0]||1!==K[1],G=this._daylightSavingAdjust(e.currentDay?new Date(e.currentYear,e.currentMonth,e.currentDay):new Date(9999,9,9)),X=this._getMinMaxDate(e,"min"),$=this._getMinMaxDate(e,"max"),Z=e.drawMonth-V,et=e.drawYear;if(0>Z&&(Z+=12,et--),$)for(t=this._daylightSavingAdjust(new Date($.getFullYear(),$.getMonth()-K[0]*K[1]+1,$.getDate())),t=X&&X>t?X:t;this._daylightSavingAdjust(new Date(et,Z,1))>t;)Z--,0>Z&&(Z=11,et--);for(e.drawMonth=Z,e.drawYear=et,i=this._get(e,"prevText"),i=q?this.formatDate(i,this._daylightSavingAdjust(new Date(et,Z-U,1)),this._getFormatConfig(e)):i,s=this._canAdjustMonth(e,-1,et,Z)?"<a class='ui-datepicker-prev ui-corner-all' data-handler='prev' data-event='click' title='"+i+"'><span class='ui-icon ui-icon-circle-triangle-"+(Y?"e":"w")+"'>"+i+"</span></a>":J?"":"<a class='ui-datepicker-prev ui-corner-all ui-state-disabled' title='"+i+"'><span class='ui-icon ui-icon-circle-triangle-"+(Y?"e":"w")+"'>"+i+"</span></a>",n=this._get(e,"nextText"),n=q?this.formatDate(n,this._daylightSavingAdjust(new Date(et,Z+U,1)),this._getFormatConfig(e)):n,a=this._canAdjustMonth(e,1,et,Z)?"<a class='ui-datepicker-next ui-corner-all' data-handler='next' data-event='click' title='"+n+"'><span class='ui-icon ui-icon-circle-triangle-"+(Y?"w":"e")+"'>"+n+"</span></a>":J?"":"<a class='ui-datepicker-next ui-corner-all ui-state-disabled' title='"+n+"'><span class='ui-icon ui-icon-circle-triangle-"+(Y?"w":"e")+"'>"+n+"</span></a>",o=this._get(e,"currentText"),r=this._get(e,"gotoCurrent")&&e.currentDay?G:R,o=q?this.formatDate(o,r,this._getFormatConfig(e)):o,h=e.inline?"":"<button type='button' class='ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all' data-handler='hide' data-event='click'>"+this._get(e,"closeText")+"</button>",l=B?"<div class='ui-datepicker-buttonpane ui-widget-content'>"+(Y?h:"")+(this._isInRange(e,r)?"<button type='button' class='ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all' data-handler='today' data-event='click'>"+o+"</button>":"")+(Y?"":h)+"</div>":"",u=parseInt(this._get(e,"firstDay"),10),u=isNaN(u)?0:u,d=this._get(e,"showWeek"),c=this._get(e,"dayNames"),p=this._get(e,"dayNamesMin"),f=this._get(e,"monthNames"),m=this._get(e,"monthNamesShort"),g=this._get(e,"beforeShowDay"),v=this._get(e,"showOtherMonths"),y=this._get(e,"selectOtherMonths"),b=this._getDefaultDate(e),_="",w=0;K[0]>w;w++){for(k="",this.maxRows=4,T=0;K[1]>T;T++){if(D=this._daylightSavingAdjust(new Date(et,Z,e.selectedDay)),S=" ui-corner-all",M="",Q){if(M+="<div class='ui-datepicker-group",K[1]>1)switch(T){case 0:M+=" ui-datepicker-group-first",S=" ui-corner-"+(Y?"right":"left");break;case K[1]-1:M+=" ui-datepicker-group-last",S=" ui-corner-"+(Y?"left":"right");break;default:M+=" ui-datepicker-group-middle",S=""}M+="'>"}for(M+="<div class='ui-datepicker-header ui-widget-header ui-helper-clearfix"+S+"'>"+(/all|left/.test(S)&&0===w?Y?a:s:"")+(/all|right/.test(S)&&0===w?Y?s:a:"")+this._generateMonthYearHeader(e,Z,et,X,$,w>0||T>0,f,m)+"</div><table class='ui-datepicker-calendar'><thead>"+"<tr>",C=d?"<th class='ui-datepicker-week-col'>"+this._get(e,"weekHeader")+"</th>":"",x=0;7>x;x++)N=(x+u)%7,C+="<th scope='col'"+((x+u+6)%7>=5?" class='ui-datepicker-week-end'":"")+">"+"<span title='"+c[N]+"'>"+p[N]+"</span></th>";for(M+=C+"</tr></thead><tbody>",A=this._getDaysInMonth(et,Z),et===e.selectedYear&&Z===e.selectedMonth&&(e.selectedDay=Math.min(e.selectedDay,A)),P=(this._getFirstDayOfMonth(et,Z)-u+7)%7,I=Math.ceil((P+A)/7),H=Q?this.maxRows>I?this.maxRows:I:I,this.maxRows=H,z=this._daylightSavingAdjust(new Date(et,Z,1-P)),F=0;H>F;F++){for(M+="<tr>",E=d?"<td class='ui-datepicker-week-col'>"+this._get(e,"calculateWeek")(z)+"</td>":"",x=0;7>x;x++)O=g?g.apply(e.input?e.input[0]:null,[z]):[!0,""],j=z.getMonth()!==Z,W=j&&!y||!O[0]||X&&X>z||$&&z>$,E+="<td class='"+((x+u+6)%7>=5?" ui-datepicker-week-end":"")+(j?" ui-datepicker-other-month":"")+(z.getTime()===D.getTime()&&Z===e.selectedMonth&&e._keyEvent||b.getTime()===z.getTime()&&b.getTime()===D.getTime()?" "+this._dayOverClass:"")+(W?" "+this._unselectableClass+" ui-state-disabled":"")+(j&&!v?"":" "+O[1]+(z.getTime()===G.getTime()?" "+this._currentClass:"")+(z.getTime()===R.getTime()?" ui-datepicker-today":""))+"'"+(j&&!v||!O[2]?"":" title='"+O[2].replace(/'/g,"&#39;")+"'")+(W?"":" data-handler='selectDay' data-event='click' data-month='"+z.getMonth()+"' data-year='"+z.getFullYear()+"'")+">"+(j&&!v?"&#xa0;":W?"<span class='ui-state-default'>"+z.getDate()+"</span>":"<a class='ui-state-default"+(z.getTime()===R.getTime()?" ui-state-highlight":"")+(z.getTime()===G.getTime()?" ui-state-active":"")+(j?" ui-priority-secondary":"")+"' href='#'>"+z.getDate()+"</a>")+"</td>",z.setDate(z.getDate()+1),z=this._daylightSavingAdjust(z);M+=E+"</tr>"}Z++,Z>11&&(Z=0,et++),M+="</tbody></table>"+(Q?"</div>"+(K[0]>0&&T===K[1]-1?"<div class='ui-datepicker-row-break'></div>":""):""),k+=M}_+=k}return _+=l,e._keyEvent=!1,_},_generateMonthYearHeader:function(e,t,i,s,n,a,o,r){var h,l,u,d,c,p,f,m,g=this._get(e,"changeMonth"),v=this._get(e,"changeYear"),y=this._get(e,"showMonthAfterYear"),b="<div class='ui-datepicker-title'>",_="";if(a||!g)_+="<span class='ui-datepicker-month'>"+o[t]+"</span>";else{for(h=s&&s.getFullYear()===i,l=n&&n.getFullYear()===i,_+="<select class='ui-datepicker-month' data-handler='selectMonth' data-event='change'>",u=0;12>u;u++)(!h||u>=s.getMonth())&&(!l||n.getMonth()>=u)&&(_+="<option value='"+u+"'"+(u===t?" selected='selected'":"")+">"+r[u]+"</option>");_+="</select>"}if(y||(b+=_+(!a&&g&&v?"":"&#xa0;")),!e.yearshtml)if(e.yearshtml="",a||!v)b+="<span class='ui-datepicker-year'>"+i+"</span>";else{for(d=this._get(e,"yearRange").split(":"),c=(new Date).getFullYear(),p=function(e){var t=e.match(/c[+\-].*/)?i+parseInt(e.substring(1),10):e.match(/[+\-].*/)?c+parseInt(e,10):parseInt(e,10);return isNaN(t)?c:t},f=p(d[0]),m=Math.max(f,p(d[1]||"")),f=s?Math.max(f,s.getFullYear()):f,m=n?Math.min(m,n.getFullYear()):m,e.yearshtml+="<select class='ui-datepicker-year' data-handler='selectYear' data-event='change'>";m>=f;f++)e.yearshtml+="<option value='"+f+"'"+(f===i?" selected='selected'":"")+">"+f+"</option>";e.yearshtml+="</select>",b+=e.yearshtml,e.yearshtml=null}return b+=this._get(e,"yearSuffix"),y&&(b+=(!a&&g&&v?"":"&#xa0;")+_),b+="</div>"},_adjustInstDate:function(e,t,i){var s=e.drawYear+("Y"===i?t:0),n=e.drawMonth+("M"===i?t:0),a=Math.min(e.selectedDay,this._getDaysInMonth(s,n))+("D"===i?t:0),o=this._restrictMinMax(e,this._daylightSavingAdjust(new Date(s,n,a)));e.selectedDay=o.getDate(),e.drawMonth=e.selectedMonth=o.getMonth(),e.drawYear=e.selectedYear=o.getFullYear(),("M"===i||"Y"===i)&&this._notifyChange(e)},_restrictMinMax:function(e,t){var i=this._getMinMaxDate(e,"min"),s=this._getMinMaxDate(e,"max"),n=i&&i>t?i:t;return s&&n>s?s:n},_notifyChange:function(e){var t=this._get(e,"onChangeMonthYear");t&&t.apply(e.input?e.input[0]:null,[e.selectedYear,e.selectedMonth+1,e])},_getNumberOfMonths:function(e){var t=this._get(e,"numberOfMonths");return null==t?[1,1]:"number"==typeof t?[1,t]:t},_getMinMaxDate:function(e,t){return this._determineDate(e,this._get(e,t+"Date"),null)},_getDaysInMonth:function(e,t){return 32-this._daylightSavingAdjust(new Date(e,t,32)).getDate()},_getFirstDayOfMonth:function(e,t){return new Date(e,t,1).getDay()},_canAdjustMonth:function(e,t,i,s){var n=this._getNumberOfMonths(e),a=this._daylightSavingAdjust(new Date(i,s+(0>t?t:n[0]*n[1]),1));return 0>t&&a.setDate(this._getDaysInMonth(a.getFullYear(),a.getMonth())),this._isInRange(e,a)},_isInRange:function(e,t){var i,s,n=this._getMinMaxDate(e,"min"),a=this._getMinMaxDate(e,"max"),o=null,r=null,h=this._get(e,"yearRange");return h&&(i=h.split(":"),s=(new Date).getFullYear(),o=parseInt(i[0],10),r=parseInt(i[1],10),i[0].match(/[+\-].*/)&&(o+=s),i[1].match(/[+\-].*/)&&(r+=s)),(!n||t.getTime()>=n.getTime())&&(!a||t.getTime()<=a.getTime())&&(!o||t.getFullYear()>=o)&&(!r||r>=t.getFullYear())},_getFormatConfig:function(e){var t=this._get(e,"shortYearCutoff");return t="string"!=typeof t?t:(new Date).getFullYear()%100+parseInt(t,10),{shortYearCutoff:t,dayNamesShort:this._get(e,"dayNamesShort"),dayNames:this._get(e,"dayNames"),monthNamesShort:this._get(e,"monthNamesShort"),monthNames:this._get(e,"monthNames")}},_formatDate:function(e,t,i,s){t||(e.currentDay=e.selectedDay,e.currentMonth=e.selectedMonth,e.currentYear=e.selectedYear);var n=t?"object"==typeof t?t:this._daylightSavingAdjust(new Date(s,i,t)):this._daylightSavingAdjust(new Date(e.currentYear,e.currentMonth,e.currentDay));return this.formatDate(this._get(e,"dateFormat"),n,this._getFormatConfig(e))}}),e.fn.datepicker=function(t){if(!this.length)return this;e.datepicker.initialized||(e(document).mousedown(e.datepicker._checkExternalClick),e.datepicker.initialized=!0),0===e("#"+e.datepicker._mainDivId).length&&e("body").append(e.datepicker.dpDiv);var i=Array.prototype.slice.call(arguments,1);return"string"!=typeof t||"isDisabled"!==t&&"getDate"!==t&&"widget"!==t?"option"===t&&2===arguments.length&&"string"==typeof arguments[1]?e.datepicker["_"+t+"Datepicker"].apply(e.datepicker,[this[0]].concat(i)):this.each(function(){"string"==typeof t?e.datepicker["_"+t+"Datepicker"].apply(e.datepicker,[this].concat(i)):e.datepicker._attachDatepicker(this,t)}):e.datepicker["_"+t+"Datepicker"].apply(e.datepicker,[this[0]].concat(i))},e.datepicker=new n,e.datepicker.initialized=!1,e.datepicker.uuid=(new Date).getTime(),e.datepicker.version="1.11.4",e.datepicker,e.widget("ui.draggable",e.ui.mouse,{version:"1.11.4",widgetEventPrefix:"drag",options:{addClasses:!0,appendTo:"parent",axis:!1,connectToSortable:!1,containment:!1,cursor:"auto",cursorAt:!1,grid:!1,handle:!1,helper:"original",iframeFix:!1,opacity:!1,refreshPositions:!1,revert:!1,revertDuration:500,scope:"default",scroll:!0,scrollSensitivity:20,scrollSpeed:20,snap:!1,snapMode:"both",snapTolerance:20,stack:!1,zIndex:!1,drag:null,start:null,stop:null},_create:function(){"original"===this.options.helper&&this._setPositionRelative(),this.options.addClasses&&this.element.addClass("ui-draggable"),this.options.disabled&&this.element.addClass("ui-draggable-disabled"),this._setHandleClassName(),this._mouseInit()},_setOption:function(e,t){this._super(e,t),"handle"===e&&(this._removeHandleClassName(),this._setHandleClassName())},_destroy:function(){return(this.helper||this.element).is(".ui-draggable-dragging")?(this.destroyOnClear=!0,void 0):(this.element.removeClass("ui-draggable ui-draggable-dragging ui-draggable-disabled"),this._removeHandleClassName(),this._mouseDestroy(),void 0)},_mouseCapture:function(t){var i=this.options;return this._blurActiveElement(t),this.helper||i.disabled||e(t.target).closest(".ui-resizable-handle").length>0?!1:(this.handle=this._getHandle(t),this.handle?(this._blockFrames(i.iframeFix===!0?"iframe":i.iframeFix),!0):!1)},_blockFrames:function(t){this.iframeBlocks=this.document.find(t).map(function(){var t=e(this);return e("<div>").css("position","absolute").appendTo(t.parent()).outerWidth(t.outerWidth()).outerHeight(t.outerHeight()).offset(t.offset())[0]})},_unblockFrames:function(){this.iframeBlocks&&(this.iframeBlocks.remove(),delete this.iframeBlocks)},_blurActiveElement:function(t){var i=this.document[0];if(this.handleElement.is(t.target))try{i.activeElement&&"body"!==i.activeElement.nodeName.toLowerCase()&&e(i.activeElement).blur()}catch(s){}},_mouseStart:function(t){var i=this.options;return this.helper=this._createHelper(t),this.helper.addClass("ui-draggable-dragging"),this._cacheHelperProportions(),e.ui.ddmanager&&(e.ui.ddmanager.current=this),this._cacheMargins(),this.cssPosition=this.helper.css("position"),this.scrollParent=this.helper.scrollParent(!0),this.offsetParent=this.helper.offsetParent(),this.hasFixedAncestor=this.helper.parents().filter(function(){return"fixed"===e(this).css("position")}).length>0,this.positionAbs=this.element.offset(),this._refreshOffsets(t),this.originalPosition=this.position=this._generatePosition(t,!1),this.originalPageX=t.pageX,this.originalPageY=t.pageY,i.cursorAt&&this._adjustOffsetFromHelper(i.cursorAt),this._setContainment(),this._trigger("start",t)===!1?(this._clear(),!1):(this._cacheHelperProportions(),e.ui.ddmanager&&!i.dropBehaviour&&e.ui.ddmanager.prepareOffsets(this,t),this._normalizeRightBottom(),this._mouseDrag(t,!0),e.ui.ddmanager&&e.ui.ddmanager.dragStart(this,t),!0)},_refreshOffsets:function(e){this.offset={top:this.positionAbs.top-this.margins.top,left:this.positionAbs.left-this.margins.left,scroll:!1,parent:this._getParentOffset(),relative:this._getRelativeOffset()},this.offset.click={left:e.pageX-this.offset.left,top:e.pageY-this.offset.top}},_mouseDrag:function(t,i){if(this.hasFixedAncestor&&(this.offset.parent=this._getParentOffset()),this.position=this._generatePosition(t,!0),this.positionAbs=this._convertPositionTo("absolute"),!i){var s=this._uiHash();if(this._trigger("drag",t,s)===!1)return this._mouseUp({}),!1;this.position=s.position}return this.helper[0].style.left=this.position.left+"px",this.helper[0].style.top=this.position.top+"px",e.ui.ddmanager&&e.ui.ddmanager.drag(this,t),!1},_mouseStop:function(t){var i=this,s=!1;return e.ui.ddmanager&&!this.options.dropBehaviour&&(s=e.ui.ddmanager.drop(this,t)),this.dropped&&(s=this.dropped,this.dropped=!1),"invalid"===this.options.revert&&!s||"valid"===this.options.revert&&s||this.options.revert===!0||e.isFunction(this.options.revert)&&this.options.revert.call(this.element,s)?e(this.helper).animate(this.originalPosition,parseInt(this.options.revertDuration,10),function(){i._trigger("stop",t)!==!1&&i._clear()}):this._trigger("stop",t)!==!1&&this._clear(),!1},_mouseUp:function(t){return this._unblockFrames(),e.ui.ddmanager&&e.ui.ddmanager.dragStop(this,t),this.handleElement.is(t.target)&&this.element.focus(),e.ui.mouse.prototype._mouseUp.call(this,t)},cancel:function(){return this.helper.is(".ui-draggable-dragging")?this._mouseUp({}):this._clear(),this},_getHandle:function(t){return this.options.handle?!!e(t.target).closest(this.element.find(this.options.handle)).length:!0},_setHandleClassName:function(){this.handleElement=this.options.handle?this.element.find(this.options.handle):this.element,this.handleElement.addClass("ui-draggable-handle")},_removeHandleClassName:function(){this.handleElement.removeClass("ui-draggable-handle")},_createHelper:function(t){var i=this.options,s=e.isFunction(i.helper),n=s?e(i.helper.apply(this.element[0],[t])):"clone"===i.helper?this.element.clone().removeAttr("id"):this.element;return n.parents("body").length||n.appendTo("parent"===i.appendTo?this.element[0].parentNode:i.appendTo),s&&n[0]===this.element[0]&&this._setPositionRelative(),n[0]===this.element[0]||/(fixed|absolute)/.test(n.css("position"))||n.css("position","absolute"),n},_setPositionRelative:function(){/^(?:r|a|f)/.test(this.element.css("position"))||(this.element[0].style.position="relative")},_adjustOffsetFromHelper:function(t){"string"==typeof t&&(t=t.split(" ")),e.isArray(t)&&(t={left:+t[0],top:+t[1]||0}),"left"in t&&(this.offset.click.left=t.left+this.margins.left),"right"in t&&(this.offset.click.left=this.helperProportions.width-t.right+this.margins.left),"top"in t&&(this.offset.click.top=t.top+this.margins.top),"bottom"in t&&(this.offset.click.top=this.helperProportions.height-t.bottom+this.margins.top)},_isRootNode:function(e){return/(html|body)/i.test(e.tagName)||e===this.document[0]},_getParentOffset:function(){var t=this.offsetParent.offset(),i=this.document[0];return"absolute"===this.cssPosition&&this.scrollParent[0]!==i&&e.contains(this.scrollParent[0],this.offsetParent[0])&&(t.left+=this.scrollParent.scrollLeft(),t.top+=this.scrollParent.scrollTop()),this._isRootNode(this.offsetParent[0])&&(t={top:0,left:0}),{top:t.top+(parseInt(this.offsetParent.css("borderTopWidth"),10)||0),left:t.left+(parseInt(this.offsetParent.css("borderLeftWidth"),10)||0)}},_getRelativeOffset:function(){if("relative"!==this.cssPosition)return{top:0,left:0};var e=this.element.position(),t=this._isRootNode(this.scrollParent[0]);return{top:e.top-(parseInt(this.helper.css("top"),10)||0)+(t?0:this.scrollParent.scrollTop()),left:e.left-(parseInt(this.helper.css("left"),10)||0)+(t?0:this.scrollParent.scrollLeft())}},_cacheMargins:function(){this.margins={left:parseInt(this.element.css("marginLeft"),10)||0,top:parseInt(this.element.css("marginTop"),10)||0,right:parseInt(this.element.css("marginRight"),10)||0,bottom:parseInt(this.element.css("marginBottom"),10)||0}},_cacheHelperProportions:function(){this.helperProportions={width:this.helper.outerWidth(),height:this.helper.outerHeight()}},_setContainment:function(){var t,i,s,n=this.options,a=this.document[0];return this.relativeContainer=null,n.containment?"window"===n.containment?(this.containment=[e(window).scrollLeft()-this.offset.relative.left-this.offset.parent.left,e(window).scrollTop()-this.offset.relative.top-this.offset.parent.top,e(window).scrollLeft()+e(window).width()-this.helperProportions.width-this.margins.left,e(window).scrollTop()+(e(window).height()||a.body.parentNode.scrollHeight)-this.helperProportions.height-this.margins.top],void 0):"document"===n.containment?(this.containment=[0,0,e(a).width()-this.helperProportions.width-this.margins.left,(e(a).height()||a.body.parentNode.scrollHeight)-this.helperProportions.height-this.margins.top],void 0):n.containment.constructor===Array?(this.containment=n.containment,void 0):("parent"===n.containment&&(n.containment=this.helper[0].parentNode),i=e(n.containment),s=i[0],s&&(t=/(scroll|auto)/.test(i.css("overflow")),this.containment=[(parseInt(i.css("borderLeftWidth"),10)||0)+(parseInt(i.css("paddingLeft"),10)||0),(parseInt(i.css("borderTopWidth"),10)||0)+(parseInt(i.css("paddingTop"),10)||0),(t?Math.max(s.scrollWidth,s.offsetWidth):s.offsetWidth)-(parseInt(i.css("borderRightWidth"),10)||0)-(parseInt(i.css("paddingRight"),10)||0)-this.helperProportions.width-this.margins.left-this.margins.right,(t?Math.max(s.scrollHeight,s.offsetHeight):s.offsetHeight)-(parseInt(i.css("borderBottomWidth"),10)||0)-(parseInt(i.css("paddingBottom"),10)||0)-this.helperProportions.height-this.margins.top-this.margins.bottom],this.relativeContainer=i),void 0):(this.containment=null,void 0)
},_convertPositionTo:function(e,t){t||(t=this.position);var i="absolute"===e?1:-1,s=this._isRootNode(this.scrollParent[0]);return{top:t.top+this.offset.relative.top*i+this.offset.parent.top*i-("fixed"===this.cssPosition?-this.offset.scroll.top:s?0:this.offset.scroll.top)*i,left:t.left+this.offset.relative.left*i+this.offset.parent.left*i-("fixed"===this.cssPosition?-this.offset.scroll.left:s?0:this.offset.scroll.left)*i}},_generatePosition:function(e,t){var i,s,n,a,o=this.options,r=this._isRootNode(this.scrollParent[0]),h=e.pageX,l=e.pageY;return r&&this.offset.scroll||(this.offset.scroll={top:this.scrollParent.scrollTop(),left:this.scrollParent.scrollLeft()}),t&&(this.containment&&(this.relativeContainer?(s=this.relativeContainer.offset(),i=[this.containment[0]+s.left,this.containment[1]+s.top,this.containment[2]+s.left,this.containment[3]+s.top]):i=this.containment,e.pageX-this.offset.click.left<i[0]&&(h=i[0]+this.offset.click.left),e.pageY-this.offset.click.top<i[1]&&(l=i[1]+this.offset.click.top),e.pageX-this.offset.click.left>i[2]&&(h=i[2]+this.offset.click.left),e.pageY-this.offset.click.top>i[3]&&(l=i[3]+this.offset.click.top)),o.grid&&(n=o.grid[1]?this.originalPageY+Math.round((l-this.originalPageY)/o.grid[1])*o.grid[1]:this.originalPageY,l=i?n-this.offset.click.top>=i[1]||n-this.offset.click.top>i[3]?n:n-this.offset.click.top>=i[1]?n-o.grid[1]:n+o.grid[1]:n,a=o.grid[0]?this.originalPageX+Math.round((h-this.originalPageX)/o.grid[0])*o.grid[0]:this.originalPageX,h=i?a-this.offset.click.left>=i[0]||a-this.offset.click.left>i[2]?a:a-this.offset.click.left>=i[0]?a-o.grid[0]:a+o.grid[0]:a),"y"===o.axis&&(h=this.originalPageX),"x"===o.axis&&(l=this.originalPageY)),{top:l-this.offset.click.top-this.offset.relative.top-this.offset.parent.top+("fixed"===this.cssPosition?-this.offset.scroll.top:r?0:this.offset.scroll.top),left:h-this.offset.click.left-this.offset.relative.left-this.offset.parent.left+("fixed"===this.cssPosition?-this.offset.scroll.left:r?0:this.offset.scroll.left)}},_clear:function(){this.helper.removeClass("ui-draggable-dragging"),this.helper[0]===this.element[0]||this.cancelHelperRemoval||this.helper.remove(),this.helper=null,this.cancelHelperRemoval=!1,this.destroyOnClear&&this.destroy()},_normalizeRightBottom:function(){"y"!==this.options.axis&&"auto"!==this.helper.css("right")&&(this.helper.width(this.helper.width()),this.helper.css("right","auto")),"x"!==this.options.axis&&"auto"!==this.helper.css("bottom")&&(this.helper.height(this.helper.height()),this.helper.css("bottom","auto"))},_trigger:function(t,i,s){return s=s||this._uiHash(),e.ui.plugin.call(this,t,[i,s,this],!0),/^(drag|start|stop)/.test(t)&&(this.positionAbs=this._convertPositionTo("absolute"),s.offset=this.positionAbs),e.Widget.prototype._trigger.call(this,t,i,s)},plugins:{},_uiHash:function(){return{helper:this.helper,position:this.position,originalPosition:this.originalPosition,offset:this.positionAbs}}}),e.ui.plugin.add("draggable","connectToSortable",{start:function(t,i,s){var n=e.extend({},i,{item:s.element});s.sortables=[],e(s.options.connectToSortable).each(function(){var i=e(this).sortable("instance");i&&!i.options.disabled&&(s.sortables.push(i),i.refreshPositions(),i._trigger("activate",t,n))})},stop:function(t,i,s){var n=e.extend({},i,{item:s.element});s.cancelHelperRemoval=!1,e.each(s.sortables,function(){var e=this;e.isOver?(e.isOver=0,s.cancelHelperRemoval=!0,e.cancelHelperRemoval=!1,e._storedCSS={position:e.placeholder.css("position"),top:e.placeholder.css("top"),left:e.placeholder.css("left")},e._mouseStop(t),e.options.helper=e.options._helper):(e.cancelHelperRemoval=!0,e._trigger("deactivate",t,n))})},drag:function(t,i,s){e.each(s.sortables,function(){var n=!1,a=this;a.positionAbs=s.positionAbs,a.helperProportions=s.helperProportions,a.offset.click=s.offset.click,a._intersectsWith(a.containerCache)&&(n=!0,e.each(s.sortables,function(){return this.positionAbs=s.positionAbs,this.helperProportions=s.helperProportions,this.offset.click=s.offset.click,this!==a&&this._intersectsWith(this.containerCache)&&e.contains(a.element[0],this.element[0])&&(n=!1),n})),n?(a.isOver||(a.isOver=1,s._parent=i.helper.parent(),a.currentItem=i.helper.appendTo(a.element).data("ui-sortable-item",!0),a.options._helper=a.options.helper,a.options.helper=function(){return i.helper[0]},t.target=a.currentItem[0],a._mouseCapture(t,!0),a._mouseStart(t,!0,!0),a.offset.click.top=s.offset.click.top,a.offset.click.left=s.offset.click.left,a.offset.parent.left-=s.offset.parent.left-a.offset.parent.left,a.offset.parent.top-=s.offset.parent.top-a.offset.parent.top,s._trigger("toSortable",t),s.dropped=a.element,e.each(s.sortables,function(){this.refreshPositions()}),s.currentItem=s.element,a.fromOutside=s),a.currentItem&&(a._mouseDrag(t),i.position=a.position)):a.isOver&&(a.isOver=0,a.cancelHelperRemoval=!0,a.options._revert=a.options.revert,a.options.revert=!1,a._trigger("out",t,a._uiHash(a)),a._mouseStop(t,!0),a.options.revert=a.options._revert,a.options.helper=a.options._helper,a.placeholder&&a.placeholder.remove(),i.helper.appendTo(s._parent),s._refreshOffsets(t),i.position=s._generatePosition(t,!0),s._trigger("fromSortable",t),s.dropped=!1,e.each(s.sortables,function(){this.refreshPositions()}))})}}),e.ui.plugin.add("draggable","cursor",{start:function(t,i,s){var n=e("body"),a=s.options;n.css("cursor")&&(a._cursor=n.css("cursor")),n.css("cursor",a.cursor)},stop:function(t,i,s){var n=s.options;n._cursor&&e("body").css("cursor",n._cursor)}}),e.ui.plugin.add("draggable","opacity",{start:function(t,i,s){var n=e(i.helper),a=s.options;n.css("opacity")&&(a._opacity=n.css("opacity")),n.css("opacity",a.opacity)},stop:function(t,i,s){var n=s.options;n._opacity&&e(i.helper).css("opacity",n._opacity)}}),e.ui.plugin.add("draggable","scroll",{start:function(e,t,i){i.scrollParentNotHidden||(i.scrollParentNotHidden=i.helper.scrollParent(!1)),i.scrollParentNotHidden[0]!==i.document[0]&&"HTML"!==i.scrollParentNotHidden[0].tagName&&(i.overflowOffset=i.scrollParentNotHidden.offset())},drag:function(t,i,s){var n=s.options,a=!1,o=s.scrollParentNotHidden[0],r=s.document[0];o!==r&&"HTML"!==o.tagName?(n.axis&&"x"===n.axis||(s.overflowOffset.top+o.offsetHeight-t.pageY<n.scrollSensitivity?o.scrollTop=a=o.scrollTop+n.scrollSpeed:t.pageY-s.overflowOffset.top<n.scrollSensitivity&&(o.scrollTop=a=o.scrollTop-n.scrollSpeed)),n.axis&&"y"===n.axis||(s.overflowOffset.left+o.offsetWidth-t.pageX<n.scrollSensitivity?o.scrollLeft=a=o.scrollLeft+n.scrollSpeed:t.pageX-s.overflowOffset.left<n.scrollSensitivity&&(o.scrollLeft=a=o.scrollLeft-n.scrollSpeed))):(n.axis&&"x"===n.axis||(t.pageY-e(r).scrollTop()<n.scrollSensitivity?a=e(r).scrollTop(e(r).scrollTop()-n.scrollSpeed):e(window).height()-(t.pageY-e(r).scrollTop())<n.scrollSensitivity&&(a=e(r).scrollTop(e(r).scrollTop()+n.scrollSpeed))),n.axis&&"y"===n.axis||(t.pageX-e(r).scrollLeft()<n.scrollSensitivity?a=e(r).scrollLeft(e(r).scrollLeft()-n.scrollSpeed):e(window).width()-(t.pageX-e(r).scrollLeft())<n.scrollSensitivity&&(a=e(r).scrollLeft(e(r).scrollLeft()+n.scrollSpeed)))),a!==!1&&e.ui.ddmanager&&!n.dropBehaviour&&e.ui.ddmanager.prepareOffsets(s,t)}}),e.ui.plugin.add("draggable","snap",{start:function(t,i,s){var n=s.options;s.snapElements=[],e(n.snap.constructor!==String?n.snap.items||":data(ui-draggable)":n.snap).each(function(){var t=e(this),i=t.offset();this!==s.element[0]&&s.snapElements.push({item:this,width:t.outerWidth(),height:t.outerHeight(),top:i.top,left:i.left})})},drag:function(t,i,s){var n,a,o,r,h,l,u,d,c,p,f=s.options,m=f.snapTolerance,g=i.offset.left,v=g+s.helperProportions.width,y=i.offset.top,b=y+s.helperProportions.height;for(c=s.snapElements.length-1;c>=0;c--)h=s.snapElements[c].left-s.margins.left,l=h+s.snapElements[c].width,u=s.snapElements[c].top-s.margins.top,d=u+s.snapElements[c].height,h-m>v||g>l+m||u-m>b||y>d+m||!e.contains(s.snapElements[c].item.ownerDocument,s.snapElements[c].item)?(s.snapElements[c].snapping&&s.options.snap.release&&s.options.snap.release.call(s.element,t,e.extend(s._uiHash(),{snapItem:s.snapElements[c].item})),s.snapElements[c].snapping=!1):("inner"!==f.snapMode&&(n=m>=Math.abs(u-b),a=m>=Math.abs(d-y),o=m>=Math.abs(h-v),r=m>=Math.abs(l-g),n&&(i.position.top=s._convertPositionTo("relative",{top:u-s.helperProportions.height,left:0}).top),a&&(i.position.top=s._convertPositionTo("relative",{top:d,left:0}).top),o&&(i.position.left=s._convertPositionTo("relative",{top:0,left:h-s.helperProportions.width}).left),r&&(i.position.left=s._convertPositionTo("relative",{top:0,left:l}).left)),p=n||a||o||r,"outer"!==f.snapMode&&(n=m>=Math.abs(u-y),a=m>=Math.abs(d-b),o=m>=Math.abs(h-g),r=m>=Math.abs(l-v),n&&(i.position.top=s._convertPositionTo("relative",{top:u,left:0}).top),a&&(i.position.top=s._convertPositionTo("relative",{top:d-s.helperProportions.height,left:0}).top),o&&(i.position.left=s._convertPositionTo("relative",{top:0,left:h}).left),r&&(i.position.left=s._convertPositionTo("relative",{top:0,left:l-s.helperProportions.width}).left)),!s.snapElements[c].snapping&&(n||a||o||r||p)&&s.options.snap.snap&&s.options.snap.snap.call(s.element,t,e.extend(s._uiHash(),{snapItem:s.snapElements[c].item})),s.snapElements[c].snapping=n||a||o||r||p)}}),e.ui.plugin.add("draggable","stack",{start:function(t,i,s){var n,a=s.options,o=e.makeArray(e(a.stack)).sort(function(t,i){return(parseInt(e(t).css("zIndex"),10)||0)-(parseInt(e(i).css("zIndex"),10)||0)});o.length&&(n=parseInt(e(o[0]).css("zIndex"),10)||0,e(o).each(function(t){e(this).css("zIndex",n+t)}),this.css("zIndex",n+o.length))}}),e.ui.plugin.add("draggable","zIndex",{start:function(t,i,s){var n=e(i.helper),a=s.options;n.css("zIndex")&&(a._zIndex=n.css("zIndex")),n.css("zIndex",a.zIndex)},stop:function(t,i,s){var n=s.options;n._zIndex&&e(i.helper).css("zIndex",n._zIndex)}}),e.ui.draggable,e.widget("ui.resizable",e.ui.mouse,{version:"1.11.4",widgetEventPrefix:"resize",options:{alsoResize:!1,animate:!1,animateDuration:"slow",animateEasing:"swing",aspectRatio:!1,autoHide:!1,containment:!1,ghost:!1,grid:!1,handles:"e,s,se",helper:!1,maxHeight:null,maxWidth:null,minHeight:10,minWidth:10,zIndex:90,resize:null,start:null,stop:null},_num:function(e){return parseInt(e,10)||0},_isNumber:function(e){return!isNaN(parseInt(e,10))},_hasScroll:function(t,i){if("hidden"===e(t).css("overflow"))return!1;var s=i&&"left"===i?"scrollLeft":"scrollTop",n=!1;return t[s]>0?!0:(t[s]=1,n=t[s]>0,t[s]=0,n)},_create:function(){var t,i,s,n,a,o=this,r=this.options;if(this.element.addClass("ui-resizable"),e.extend(this,{_aspectRatio:!!r.aspectRatio,aspectRatio:r.aspectRatio,originalElement:this.element,_proportionallyResizeElements:[],_helper:r.helper||r.ghost||r.animate?r.helper||"ui-resizable-helper":null}),this.element[0].nodeName.match(/^(canvas|textarea|input|select|button|img)$/i)&&(this.element.wrap(e("<div class='ui-wrapper' style='overflow: hidden;'></div>").css({position:this.element.css("position"),width:this.element.outerWidth(),height:this.element.outerHeight(),top:this.element.css("top"),left:this.element.css("left")})),this.element=this.element.parent().data("ui-resizable",this.element.resizable("instance")),this.elementIsWrapper=!0,this.element.css({marginLeft:this.originalElement.css("marginLeft"),marginTop:this.originalElement.css("marginTop"),marginRight:this.originalElement.css("marginRight"),marginBottom:this.originalElement.css("marginBottom")}),this.originalElement.css({marginLeft:0,marginTop:0,marginRight:0,marginBottom:0}),this.originalResizeStyle=this.originalElement.css("resize"),this.originalElement.css("resize","none"),this._proportionallyResizeElements.push(this.originalElement.css({position:"static",zoom:1,display:"block"})),this.originalElement.css({margin:this.originalElement.css("margin")}),this._proportionallyResize()),this.handles=r.handles||(e(".ui-resizable-handle",this.element).length?{n:".ui-resizable-n",e:".ui-resizable-e",s:".ui-resizable-s",w:".ui-resizable-w",se:".ui-resizable-se",sw:".ui-resizable-sw",ne:".ui-resizable-ne",nw:".ui-resizable-nw"}:"e,s,se"),this._handles=e(),this.handles.constructor===String)for("all"===this.handles&&(this.handles="n,e,s,w,se,sw,ne,nw"),t=this.handles.split(","),this.handles={},i=0;t.length>i;i++)s=e.trim(t[i]),a="ui-resizable-"+s,n=e("<div class='ui-resizable-handle "+a+"'></div>"),n.css({zIndex:r.zIndex}),"se"===s&&n.addClass("ui-icon ui-icon-gripsmall-diagonal-se"),this.handles[s]=".ui-resizable-"+s,this.element.append(n);this._renderAxis=function(t){var i,s,n,a;t=t||this.element;for(i in this.handles)this.handles[i].constructor===String?this.handles[i]=this.element.children(this.handles[i]).first().show():(this.handles[i].jquery||this.handles[i].nodeType)&&(this.handles[i]=e(this.handles[i]),this._on(this.handles[i],{mousedown:o._mouseDown})),this.elementIsWrapper&&this.originalElement[0].nodeName.match(/^(textarea|input|select|button)$/i)&&(s=e(this.handles[i],this.element),a=/sw|ne|nw|se|n|s/.test(i)?s.outerHeight():s.outerWidth(),n=["padding",/ne|nw|n/.test(i)?"Top":/se|sw|s/.test(i)?"Bottom":/^e$/.test(i)?"Right":"Left"].join(""),t.css(n,a),this._proportionallyResize()),this._handles=this._handles.add(this.handles[i])},this._renderAxis(this.element),this._handles=this._handles.add(this.element.find(".ui-resizable-handle")),this._handles.disableSelection(),this._handles.mouseover(function(){o.resizing||(this.className&&(n=this.className.match(/ui-resizable-(se|sw|ne|nw|n|e|s|w)/i)),o.axis=n&&n[1]?n[1]:"se")}),r.autoHide&&(this._handles.hide(),e(this.element).addClass("ui-resizable-autohide").mouseenter(function(){r.disabled||(e(this).removeClass("ui-resizable-autohide"),o._handles.show())}).mouseleave(function(){r.disabled||o.resizing||(e(this).addClass("ui-resizable-autohide"),o._handles.hide())})),this._mouseInit()},_destroy:function(){this._mouseDestroy();var t,i=function(t){e(t).removeClass("ui-resizable ui-resizable-disabled ui-resizable-resizing").removeData("resizable").removeData("ui-resizable").unbind(".resizable").find(".ui-resizable-handle").remove()};return this.elementIsWrapper&&(i(this.element),t=this.element,this.originalElement.css({position:t.css("position"),width:t.outerWidth(),height:t.outerHeight(),top:t.css("top"),left:t.css("left")}).insertAfter(t),t.remove()),this.originalElement.css("resize",this.originalResizeStyle),i(this.originalElement),this},_mouseCapture:function(t){var i,s,n=!1;for(i in this.handles)s=e(this.handles[i])[0],(s===t.target||e.contains(s,t.target))&&(n=!0);return!this.options.disabled&&n},_mouseStart:function(t){var i,s,n,a=this.options,o=this.element;return this.resizing=!0,this._renderProxy(),i=this._num(this.helper.css("left")),s=this._num(this.helper.css("top")),a.containment&&(i+=e(a.containment).scrollLeft()||0,s+=e(a.containment).scrollTop()||0),this.offset=this.helper.offset(),this.position={left:i,top:s},this.size=this._helper?{width:this.helper.width(),height:this.helper.height()}:{width:o.width(),height:o.height()},this.originalSize=this._helper?{width:o.outerWidth(),height:o.outerHeight()}:{width:o.width(),height:o.height()},this.sizeDiff={width:o.outerWidth()-o.width(),height:o.outerHeight()-o.height()},this.originalPosition={left:i,top:s},this.originalMousePosition={left:t.pageX,top:t.pageY},this.aspectRatio="number"==typeof a.aspectRatio?a.aspectRatio:this.originalSize.width/this.originalSize.height||1,n=e(".ui-resizable-"+this.axis).css("cursor"),e("body").css("cursor","auto"===n?this.axis+"-resize":n),o.addClass("ui-resizable-resizing"),this._propagate("start",t),!0},_mouseDrag:function(t){var i,s,n=this.originalMousePosition,a=this.axis,o=t.pageX-n.left||0,r=t.pageY-n.top||0,h=this._change[a];return this._updatePrevProperties(),h?(i=h.apply(this,[t,o,r]),this._updateVirtualBoundaries(t.shiftKey),(this._aspectRatio||t.shiftKey)&&(i=this._updateRatio(i,t)),i=this._respectSize(i,t),this._updateCache(i),this._propagate("resize",t),s=this._applyChanges(),!this._helper&&this._proportionallyResizeElements.length&&this._proportionallyResize(),e.isEmptyObject(s)||(this._updatePrevProperties(),this._trigger("resize",t,this.ui()),this._applyChanges()),!1):!1},_mouseStop:function(t){this.resizing=!1;var i,s,n,a,o,r,h,l=this.options,u=this;return this._helper&&(i=this._proportionallyResizeElements,s=i.length&&/textarea/i.test(i[0].nodeName),n=s&&this._hasScroll(i[0],"left")?0:u.sizeDiff.height,a=s?0:u.sizeDiff.width,o={width:u.helper.width()-a,height:u.helper.height()-n},r=parseInt(u.element.css("left"),10)+(u.position.left-u.originalPosition.left)||null,h=parseInt(u.element.css("top"),10)+(u.position.top-u.originalPosition.top)||null,l.animate||this.element.css(e.extend(o,{top:h,left:r})),u.helper.height(u.size.height),u.helper.width(u.size.width),this._helper&&!l.animate&&this._proportionallyResize()),e("body").css("cursor","auto"),this.element.removeClass("ui-resizable-resizing"),this._propagate("stop",t),this._helper&&this.helper.remove(),!1},_updatePrevProperties:function(){this.prevPosition={top:this.position.top,left:this.position.left},this.prevSize={width:this.size.width,height:this.size.height}},_applyChanges:function(){var e={};return this.position.top!==this.prevPosition.top&&(e.top=this.position.top+"px"),this.position.left!==this.prevPosition.left&&(e.left=this.position.left+"px"),this.size.width!==this.prevSize.width&&(e.width=this.size.width+"px"),this.size.height!==this.prevSize.height&&(e.height=this.size.height+"px"),this.helper.css(e),e},_updateVirtualBoundaries:function(e){var t,i,s,n,a,o=this.options;a={minWidth:this._isNumber(o.minWidth)?o.minWidth:0,maxWidth:this._isNumber(o.maxWidth)?o.maxWidth:1/0,minHeight:this._isNumber(o.minHeight)?o.minHeight:0,maxHeight:this._isNumber(o.maxHeight)?o.maxHeight:1/0},(this._aspectRatio||e)&&(t=a.minHeight*this.aspectRatio,s=a.minWidth/this.aspectRatio,i=a.maxHeight*this.aspectRatio,n=a.maxWidth/this.aspectRatio,t>a.minWidth&&(a.minWidth=t),s>a.minHeight&&(a.minHeight=s),a.maxWidth>i&&(a.maxWidth=i),a.maxHeight>n&&(a.maxHeight=n)),this._vBoundaries=a},_updateCache:function(e){this.offset=this.helper.offset(),this._isNumber(e.left)&&(this.position.left=e.left),this._isNumber(e.top)&&(this.position.top=e.top),this._isNumber(e.height)&&(this.size.height=e.height),this._isNumber(e.width)&&(this.size.width=e.width)},_updateRatio:function(e){var t=this.position,i=this.size,s=this.axis;return this._isNumber(e.height)?e.width=e.height*this.aspectRatio:this._isNumber(e.width)&&(e.height=e.width/this.aspectRatio),"sw"===s&&(e.left=t.left+(i.width-e.width),e.top=null),"nw"===s&&(e.top=t.top+(i.height-e.height),e.left=t.left+(i.width-e.width)),e},_respectSize:function(e){var t=this._vBoundaries,i=this.axis,s=this._isNumber(e.width)&&t.maxWidth&&t.maxWidth<e.width,n=this._isNumber(e.height)&&t.maxHeight&&t.maxHeight<e.height,a=this._isNumber(e.width)&&t.minWidth&&t.minWidth>e.width,o=this._isNumber(e.height)&&t.minHeight&&t.minHeight>e.height,r=this.originalPosition.left+this.originalSize.width,h=this.position.top+this.size.height,l=/sw|nw|w/.test(i),u=/nw|ne|n/.test(i);return a&&(e.width=t.minWidth),o&&(e.height=t.minHeight),s&&(e.width=t.maxWidth),n&&(e.height=t.maxHeight),a&&l&&(e.left=r-t.minWidth),s&&l&&(e.left=r-t.maxWidth),o&&u&&(e.top=h-t.minHeight),n&&u&&(e.top=h-t.maxHeight),e.width||e.height||e.left||!e.top?e.width||e.height||e.top||!e.left||(e.left=null):e.top=null,e},_getPaddingPlusBorderDimensions:function(e){for(var t=0,i=[],s=[e.css("borderTopWidth"),e.css("borderRightWidth"),e.css("borderBottomWidth"),e.css("borderLeftWidth")],n=[e.css("paddingTop"),e.css("paddingRight"),e.css("paddingBottom"),e.css("paddingLeft")];4>t;t++)i[t]=parseInt(s[t],10)||0,i[t]+=parseInt(n[t],10)||0;return{height:i[0]+i[2],width:i[1]+i[3]}},_proportionallyResize:function(){if(this._proportionallyResizeElements.length)for(var e,t=0,i=this.helper||this.element;this._proportionallyResizeElements.length>t;t++)e=this._proportionallyResizeElements[t],this.outerDimensions||(this.outerDimensions=this._getPaddingPlusBorderDimensions(e)),e.css({height:i.height()-this.outerDimensions.height||0,width:i.width()-this.outerDimensions.width||0})},_renderProxy:function(){var t=this.element,i=this.options;this.elementOffset=t.offset(),this._helper?(this.helper=this.helper||e("<div style='overflow:hidden;'></div>"),this.helper.addClass(this._helper).css({width:this.element.outerWidth()-1,height:this.element.outerHeight()-1,position:"absolute",left:this.elementOffset.left+"px",top:this.elementOffset.top+"px",zIndex:++i.zIndex}),this.helper.appendTo("body").disableSelection()):this.helper=this.element},_change:{e:function(e,t){return{width:this.originalSize.width+t}},w:function(e,t){var i=this.originalSize,s=this.originalPosition;return{left:s.left+t,width:i.width-t}},n:function(e,t,i){var s=this.originalSize,n=this.originalPosition;return{top:n.top+i,height:s.height-i}},s:function(e,t,i){return{height:this.originalSize.height+i}},se:function(t,i,s){return e.extend(this._change.s.apply(this,arguments),this._change.e.apply(this,[t,i,s]))},sw:function(t,i,s){return e.extend(this._change.s.apply(this,arguments),this._change.w.apply(this,[t,i,s]))},ne:function(t,i,s){return e.extend(this._change.n.apply(this,arguments),this._change.e.apply(this,[t,i,s]))},nw:function(t,i,s){return e.extend(this._change.n.apply(this,arguments),this._change.w.apply(this,[t,i,s]))}},_propagate:function(t,i){e.ui.plugin.call(this,t,[i,this.ui()]),"resize"!==t&&this._trigger(t,i,this.ui())},plugins:{},ui:function(){return{originalElement:this.originalElement,element:this.element,helper:this.helper,position:this.position,size:this.size,originalSize:this.originalSize,originalPosition:this.originalPosition}}}),e.ui.plugin.add("resizable","animate",{stop:function(t){var i=e(this).resizable("instance"),s=i.options,n=i._proportionallyResizeElements,a=n.length&&/textarea/i.test(n[0].nodeName),o=a&&i._hasScroll(n[0],"left")?0:i.sizeDiff.height,r=a?0:i.sizeDiff.width,h={width:i.size.width-r,height:i.size.height-o},l=parseInt(i.element.css("left"),10)+(i.position.left-i.originalPosition.left)||null,u=parseInt(i.element.css("top"),10)+(i.position.top-i.originalPosition.top)||null;i.element.animate(e.extend(h,u&&l?{top:u,left:l}:{}),{duration:s.animateDuration,easing:s.animateEasing,step:function(){var s={width:parseInt(i.element.css("width"),10),height:parseInt(i.element.css("height"),10),top:parseInt(i.element.css("top"),10),left:parseInt(i.element.css("left"),10)};n&&n.length&&e(n[0]).css({width:s.width,height:s.height}),i._updateCache(s),i._propagate("resize",t)}})}}),e.ui.plugin.add("resizable","containment",{start:function(){var t,i,s,n,a,o,r,h=e(this).resizable("instance"),l=h.options,u=h.element,d=l.containment,c=d instanceof e?d.get(0):/parent/.test(d)?u.parent().get(0):d;c&&(h.containerElement=e(c),/document/.test(d)||d===document?(h.containerOffset={left:0,top:0},h.containerPosition={left:0,top:0},h.parentData={element:e(document),left:0,top:0,width:e(document).width(),height:e(document).height()||document.body.parentNode.scrollHeight}):(t=e(c),i=[],e(["Top","Right","Left","Bottom"]).each(function(e,s){i[e]=h._num(t.css("padding"+s))}),h.containerOffset=t.offset(),h.containerPosition=t.position(),h.containerSize={height:t.innerHeight()-i[3],width:t.innerWidth()-i[1]},s=h.containerOffset,n=h.containerSize.height,a=h.containerSize.width,o=h._hasScroll(c,"left")?c.scrollWidth:a,r=h._hasScroll(c)?c.scrollHeight:n,h.parentData={element:c,left:s.left,top:s.top,width:o,height:r}))},resize:function(t){var i,s,n,a,o=e(this).resizable("instance"),r=o.options,h=o.containerOffset,l=o.position,u=o._aspectRatio||t.shiftKey,d={top:0,left:0},c=o.containerElement,p=!0;c[0]!==document&&/static/.test(c.css("position"))&&(d=h),l.left<(o._helper?h.left:0)&&(o.size.width=o.size.width+(o._helper?o.position.left-h.left:o.position.left-d.left),u&&(o.size.height=o.size.width/o.aspectRatio,p=!1),o.position.left=r.helper?h.left:0),l.top<(o._helper?h.top:0)&&(o.size.height=o.size.height+(o._helper?o.position.top-h.top:o.position.top),u&&(o.size.width=o.size.height*o.aspectRatio,p=!1),o.position.top=o._helper?h.top:0),n=o.containerElement.get(0)===o.element.parent().get(0),a=/relative|absolute/.test(o.containerElement.css("position")),n&&a?(o.offset.left=o.parentData.left+o.position.left,o.offset.top=o.parentData.top+o.position.top):(o.offset.left=o.element.offset().left,o.offset.top=o.element.offset().top),i=Math.abs(o.sizeDiff.width+(o._helper?o.offset.left-d.left:o.offset.left-h.left)),s=Math.abs(o.sizeDiff.height+(o._helper?o.offset.top-d.top:o.offset.top-h.top)),i+o.size.width>=o.parentData.width&&(o.size.width=o.parentData.width-i,u&&(o.size.height=o.size.width/o.aspectRatio,p=!1)),s+o.size.height>=o.parentData.height&&(o.size.height=o.parentData.height-s,u&&(o.size.width=o.size.height*o.aspectRatio,p=!1)),p||(o.position.left=o.prevPosition.left,o.position.top=o.prevPosition.top,o.size.width=o.prevSize.width,o.size.height=o.prevSize.height)},stop:function(){var t=e(this).resizable("instance"),i=t.options,s=t.containerOffset,n=t.containerPosition,a=t.containerElement,o=e(t.helper),r=o.offset(),h=o.outerWidth()-t.sizeDiff.width,l=o.outerHeight()-t.sizeDiff.height;t._helper&&!i.animate&&/relative/.test(a.css("position"))&&e(this).css({left:r.left-n.left-s.left,width:h,height:l}),t._helper&&!i.animate&&/static/.test(a.css("position"))&&e(this).css({left:r.left-n.left-s.left,width:h,height:l})}}),e.ui.plugin.add("resizable","alsoResize",{start:function(){var t=e(this).resizable("instance"),i=t.options;e(i.alsoResize).each(function(){var t=e(this);t.data("ui-resizable-alsoresize",{width:parseInt(t.width(),10),height:parseInt(t.height(),10),left:parseInt(t.css("left"),10),top:parseInt(t.css("top"),10)})})},resize:function(t,i){var s=e(this).resizable("instance"),n=s.options,a=s.originalSize,o=s.originalPosition,r={height:s.size.height-a.height||0,width:s.size.width-a.width||0,top:s.position.top-o.top||0,left:s.position.left-o.left||0};e(n.alsoResize).each(function(){var t=e(this),s=e(this).data("ui-resizable-alsoresize"),n={},a=t.parents(i.originalElement[0]).length?["width","height"]:["width","height","top","left"];e.each(a,function(e,t){var i=(s[t]||0)+(r[t]||0);i&&i>=0&&(n[t]=i||null)}),t.css(n)})},stop:function(){e(this).removeData("resizable-alsoresize")}}),e.ui.plugin.add("resizable","ghost",{start:function(){var t=e(this).resizable("instance"),i=t.options,s=t.size;t.ghost=t.originalElement.clone(),t.ghost.css({opacity:.25,display:"block",position:"relative",height:s.height,width:s.width,margin:0,left:0,top:0}).addClass("ui-resizable-ghost").addClass("string"==typeof i.ghost?i.ghost:""),t.ghost.appendTo(t.helper)},resize:function(){var t=e(this).resizable("instance");t.ghost&&t.ghost.css({position:"relative",height:t.size.height,width:t.size.width})},stop:function(){var t=e(this).resizable("instance");t.ghost&&t.helper&&t.helper.get(0).removeChild(t.ghost.get(0))}}),e.ui.plugin.add("resizable","grid",{resize:function(){var t,i=e(this).resizable("instance"),s=i.options,n=i.size,a=i.originalSize,o=i.originalPosition,r=i.axis,h="number"==typeof s.grid?[s.grid,s.grid]:s.grid,l=h[0]||1,u=h[1]||1,d=Math.round((n.width-a.width)/l)*l,c=Math.round((n.height-a.height)/u)*u,p=a.width+d,f=a.height+c,m=s.maxWidth&&p>s.maxWidth,g=s.maxHeight&&f>s.maxHeight,v=s.minWidth&&s.minWidth>p,y=s.minHeight&&s.minHeight>f;s.grid=h,v&&(p+=l),y&&(f+=u),m&&(p-=l),g&&(f-=u),/^(se|s|e)$/.test(r)?(i.size.width=p,i.size.height=f):/^(ne)$/.test(r)?(i.size.width=p,i.size.height=f,i.position.top=o.top-c):/^(sw)$/.test(r)?(i.size.width=p,i.size.height=f,i.position.left=o.left-d):((0>=f-u||0>=p-l)&&(t=i._getPaddingPlusBorderDimensions(this)),f-u>0?(i.size.height=f,i.position.top=o.top-c):(f=u-t.height,i.size.height=f,i.position.top=o.top+a.height-f),p-l>0?(i.size.width=p,i.position.left=o.left-d):(p=l-t.width,i.size.width=p,i.position.left=o.left+a.width-p))}}),e.ui.resizable,e.widget("ui.dialog",{version:"1.11.4",options:{appendTo:"body",autoOpen:!0,buttons:[],closeOnEscape:!0,closeText:"Close",dialogClass:"",draggable:!0,hide:null,height:"auto",maxHeight:null,maxWidth:null,minHeight:150,minWidth:150,modal:!1,position:{my:"center",at:"center",of:window,collision:"fit",using:function(t){var i=e(this).css(t).offset().top;0>i&&e(this).css("top",t.top-i)}},resizable:!0,show:null,title:null,width:300,beforeClose:null,close:null,drag:null,dragStart:null,dragStop:null,focus:null,open:null,resize:null,resizeStart:null,resizeStop:null},sizeRelatedOptions:{buttons:!0,height:!0,maxHeight:!0,maxWidth:!0,minHeight:!0,minWidth:!0,width:!0},resizableRelatedOptions:{maxHeight:!0,maxWidth:!0,minHeight:!0,minWidth:!0},_create:function(){this.originalCss={display:this.element[0].style.display,width:this.element[0].style.width,minHeight:this.element[0].style.minHeight,maxHeight:this.element[0].style.maxHeight,height:this.element[0].style.height},this.originalPosition={parent:this.element.parent(),index:this.element.parent().children().index(this.element)},this.originalTitle=this.element.attr("title"),this.options.title=this.options.title||this.originalTitle,this._createWrapper(),this.element.show().removeAttr("title").addClass("ui-dialog-content ui-widget-content").appendTo(this.uiDialog),this._createTitlebar(),this._createButtonPane(),this.options.draggable&&e.fn.draggable&&this._makeDraggable(),this.options.resizable&&e.fn.resizable&&this._makeResizable(),this._isOpen=!1,this._trackFocus()},_init:function(){this.options.autoOpen&&this.open()},_appendTo:function(){var t=this.options.appendTo;return t&&(t.jquery||t.nodeType)?e(t):this.document.find(t||"body").eq(0)},_destroy:function(){var e,t=this.originalPosition;this._untrackInstance(),this._destroyOverlay(),this.element.removeUniqueId().removeClass("ui-dialog-content ui-widget-content").css(this.originalCss).detach(),this.uiDialog.stop(!0,!0).remove(),this.originalTitle&&this.element.attr("title",this.originalTitle),e=t.parent.children().eq(t.index),e.length&&e[0]!==this.element[0]?e.before(this.element):t.parent.append(this.element)},widget:function(){return this.uiDialog},disable:e.noop,enable:e.noop,close:function(t){var i,s=this;if(this._isOpen&&this._trigger("beforeClose",t)!==!1){if(this._isOpen=!1,this._focusedElement=null,this._destroyOverlay(),this._untrackInstance(),!this.opener.filter(":focusable").focus().length)try{i=this.document[0].activeElement,i&&"body"!==i.nodeName.toLowerCase()&&e(i).blur()}catch(n){}this._hide(this.uiDialog,this.options.hide,function(){s._trigger("close",t)})}},isOpen:function(){return this._isOpen},moveToTop:function(){this._moveToTop()},_moveToTop:function(t,i){var s=!1,n=this.uiDialog.siblings(".ui-front:visible").map(function(){return+e(this).css("z-index")}).get(),a=Math.max.apply(null,n);return a>=+this.uiDialog.css("z-index")&&(this.uiDialog.css("z-index",a+1),s=!0),s&&!i&&this._trigger("focus",t),s},open:function(){var t=this;return this._isOpen?(this._moveToTop()&&this._focusTabbable(),void 0):(this._isOpen=!0,this.opener=e(this.document[0].activeElement),this._size(),this._position(),this._createOverlay(),this._moveToTop(null,!0),this.overlay&&this.overlay.css("z-index",this.uiDialog.css("z-index")-1),this._show(this.uiDialog,this.options.show,function(){t._focusTabbable(),t._trigger("focus")}),this._makeFocusTarget(),this._trigger("open"),void 0)},_focusTabbable:function(){var e=this._focusedElement;e||(e=this.element.find("[autofocus]")),e.length||(e=this.element.find(":tabbable")),e.length||(e=this.uiDialogButtonPane.find(":tabbable")),e.length||(e=this.uiDialogTitlebarClose.filter(":tabbable")),e.length||(e=this.uiDialog),e.eq(0).focus()},_keepFocus:function(t){function i(){var t=this.document[0].activeElement,i=this.uiDialog[0]===t||e.contains(this.uiDialog[0],t);i||this._focusTabbable()}t.preventDefault(),i.call(this),this._delay(i)},_createWrapper:function(){this.uiDialog=e("<div>").addClass("ui-dialog ui-widget ui-widget-content ui-corner-all ui-front "+this.options.dialogClass).hide().attr({tabIndex:-1,role:"dialog"}).appendTo(this._appendTo()),this._on(this.uiDialog,{keydown:function(t){if(this.options.closeOnEscape&&!t.isDefaultPrevented()&&t.keyCode&&t.keyCode===e.ui.keyCode.ESCAPE)return t.preventDefault(),this.close(t),void 0;
if(t.keyCode===e.ui.keyCode.TAB&&!t.isDefaultPrevented()){var i=this.uiDialog.find(":tabbable"),s=i.filter(":first"),n=i.filter(":last");t.target!==n[0]&&t.target!==this.uiDialog[0]||t.shiftKey?t.target!==s[0]&&t.target!==this.uiDialog[0]||!t.shiftKey||(this._delay(function(){n.focus()}),t.preventDefault()):(this._delay(function(){s.focus()}),t.preventDefault())}},mousedown:function(e){this._moveToTop(e)&&this._focusTabbable()}}),this.element.find("[aria-describedby]").length||this.uiDialog.attr({"aria-describedby":this.element.uniqueId().attr("id")})},_createTitlebar:function(){var t;this.uiDialogTitlebar=e("<div>").addClass("ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix").prependTo(this.uiDialog),this._on(this.uiDialogTitlebar,{mousedown:function(t){e(t.target).closest(".ui-dialog-titlebar-close")||this.uiDialog.focus()}}),this.uiDialogTitlebarClose=e("<button type='button'></button>").button({label:this.options.closeText,icons:{primary:"ui-icon-closethick"},text:!1}).addClass("ui-dialog-titlebar-close").appendTo(this.uiDialogTitlebar),this._on(this.uiDialogTitlebarClose,{click:function(e){e.preventDefault(),this.close(e)}}),t=e("<span>").uniqueId().addClass("ui-dialog-title").prependTo(this.uiDialogTitlebar),this._title(t),this.uiDialog.attr({"aria-labelledby":t.attr("id")})},_title:function(e){this.options.title||e.html("&#160;"),e.text(this.options.title)},_createButtonPane:function(){this.uiDialogButtonPane=e("<div>").addClass("ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"),this.uiButtonSet=e("<div>").addClass("ui-dialog-buttonset").appendTo(this.uiDialogButtonPane),this._createButtons()},_createButtons:function(){var t=this,i=this.options.buttons;return this.uiDialogButtonPane.remove(),this.uiButtonSet.empty(),e.isEmptyObject(i)||e.isArray(i)&&!i.length?(this.uiDialog.removeClass("ui-dialog-buttons"),void 0):(e.each(i,function(i,s){var n,a;s=e.isFunction(s)?{click:s,text:i}:s,s=e.extend({type:"button"},s),n=s.click,s.click=function(){n.apply(t.element[0],arguments)},a={icons:s.icons,text:s.showText},delete s.icons,delete s.showText,e("<button></button>",s).button(a).appendTo(t.uiButtonSet)}),this.uiDialog.addClass("ui-dialog-buttons"),this.uiDialogButtonPane.appendTo(this.uiDialog),void 0)},_makeDraggable:function(){function t(e){return{position:e.position,offset:e.offset}}var i=this,s=this.options;this.uiDialog.draggable({cancel:".ui-dialog-content, .ui-dialog-titlebar-close",handle:".ui-dialog-titlebar",containment:"document",start:function(s,n){e(this).addClass("ui-dialog-dragging"),i._blockFrames(),i._trigger("dragStart",s,t(n))},drag:function(e,s){i._trigger("drag",e,t(s))},stop:function(n,a){var o=a.offset.left-i.document.scrollLeft(),r=a.offset.top-i.document.scrollTop();s.position={my:"left top",at:"left"+(o>=0?"+":"")+o+" "+"top"+(r>=0?"+":"")+r,of:i.window},e(this).removeClass("ui-dialog-dragging"),i._unblockFrames(),i._trigger("dragStop",n,t(a))}})},_makeResizable:function(){function t(e){return{originalPosition:e.originalPosition,originalSize:e.originalSize,position:e.position,size:e.size}}var i=this,s=this.options,n=s.resizable,a=this.uiDialog.css("position"),o="string"==typeof n?n:"n,e,s,w,se,sw,ne,nw";this.uiDialog.resizable({cancel:".ui-dialog-content",containment:"document",alsoResize:this.element,maxWidth:s.maxWidth,maxHeight:s.maxHeight,minWidth:s.minWidth,minHeight:this._minHeight(),handles:o,start:function(s,n){e(this).addClass("ui-dialog-resizing"),i._blockFrames(),i._trigger("resizeStart",s,t(n))},resize:function(e,s){i._trigger("resize",e,t(s))},stop:function(n,a){var o=i.uiDialog.offset(),r=o.left-i.document.scrollLeft(),h=o.top-i.document.scrollTop();s.height=i.uiDialog.height(),s.width=i.uiDialog.width(),s.position={my:"left top",at:"left"+(r>=0?"+":"")+r+" "+"top"+(h>=0?"+":"")+h,of:i.window},e(this).removeClass("ui-dialog-resizing"),i._unblockFrames(),i._trigger("resizeStop",n,t(a))}}).css("position",a)},_trackFocus:function(){this._on(this.widget(),{focusin:function(t){this._makeFocusTarget(),this._focusedElement=e(t.target)}})},_makeFocusTarget:function(){this._untrackInstance(),this._trackingInstances().unshift(this)},_untrackInstance:function(){var t=this._trackingInstances(),i=e.inArray(this,t);-1!==i&&t.splice(i,1)},_trackingInstances:function(){var e=this.document.data("ui-dialog-instances");return e||(e=[],this.document.data("ui-dialog-instances",e)),e},_minHeight:function(){var e=this.options;return"auto"===e.height?e.minHeight:Math.min(e.minHeight,e.height)},_position:function(){var e=this.uiDialog.is(":visible");e||this.uiDialog.show(),this.uiDialog.position(this.options.position),e||this.uiDialog.hide()},_setOptions:function(t){var i=this,s=!1,n={};e.each(t,function(e,t){i._setOption(e,t),e in i.sizeRelatedOptions&&(s=!0),e in i.resizableRelatedOptions&&(n[e]=t)}),s&&(this._size(),this._position()),this.uiDialog.is(":data(ui-resizable)")&&this.uiDialog.resizable("option",n)},_setOption:function(e,t){var i,s,n=this.uiDialog;"dialogClass"===e&&n.removeClass(this.options.dialogClass).addClass(t),"disabled"!==e&&(this._super(e,t),"appendTo"===e&&this.uiDialog.appendTo(this._appendTo()),"buttons"===e&&this._createButtons(),"closeText"===e&&this.uiDialogTitlebarClose.button({label:""+t}),"draggable"===e&&(i=n.is(":data(ui-draggable)"),i&&!t&&n.draggable("destroy"),!i&&t&&this._makeDraggable()),"position"===e&&this._position(),"resizable"===e&&(s=n.is(":data(ui-resizable)"),s&&!t&&n.resizable("destroy"),s&&"string"==typeof t&&n.resizable("option","handles",t),s||t===!1||this._makeResizable()),"title"===e&&this._title(this.uiDialogTitlebar.find(".ui-dialog-title")))},_size:function(){var e,t,i,s=this.options;this.element.show().css({width:"auto",minHeight:0,maxHeight:"none",height:0}),s.minWidth>s.width&&(s.width=s.minWidth),e=this.uiDialog.css({height:"auto",width:s.width}).outerHeight(),t=Math.max(0,s.minHeight-e),i="number"==typeof s.maxHeight?Math.max(0,s.maxHeight-e):"none","auto"===s.height?this.element.css({minHeight:t,maxHeight:i,height:"auto"}):this.element.height(Math.max(0,s.height-e)),this.uiDialog.is(":data(ui-resizable)")&&this.uiDialog.resizable("option","minHeight",this._minHeight())},_blockFrames:function(){this.iframeBlocks=this.document.find("iframe").map(function(){var t=e(this);return e("<div>").css({position:"absolute",width:t.outerWidth(),height:t.outerHeight()}).appendTo(t.parent()).offset(t.offset())[0]})},_unblockFrames:function(){this.iframeBlocks&&(this.iframeBlocks.remove(),delete this.iframeBlocks)},_allowInteraction:function(t){return e(t.target).closest(".ui-dialog").length?!0:!!e(t.target).closest(".ui-datepicker").length},_createOverlay:function(){if(this.options.modal){var t=!0;this._delay(function(){t=!1}),this.document.data("ui-dialog-overlays")||this._on(this.document,{focusin:function(e){t||this._allowInteraction(e)||(e.preventDefault(),this._trackingInstances()[0]._focusTabbable())}}),this.overlay=e("<div>").addClass("ui-widget-overlay ui-front").appendTo(this._appendTo()),this._on(this.overlay,{mousedown:"_keepFocus"}),this.document.data("ui-dialog-overlays",(this.document.data("ui-dialog-overlays")||0)+1)}},_destroyOverlay:function(){if(this.options.modal&&this.overlay){var e=this.document.data("ui-dialog-overlays")-1;e?this.document.data("ui-dialog-overlays",e):this.document.unbind("focusin").removeData("ui-dialog-overlays"),this.overlay.remove(),this.overlay=null}}}),e.widget("ui.droppable",{version:"1.11.4",widgetEventPrefix:"drop",options:{accept:"*",activeClass:!1,addClasses:!0,greedy:!1,hoverClass:!1,scope:"default",tolerance:"intersect",activate:null,deactivate:null,drop:null,out:null,over:null},_create:function(){var t,i=this.options,s=i.accept;this.isover=!1,this.isout=!0,this.accept=e.isFunction(s)?s:function(e){return e.is(s)},this.proportions=function(){return arguments.length?(t=arguments[0],void 0):t?t:t={width:this.element[0].offsetWidth,height:this.element[0].offsetHeight}},this._addToManager(i.scope),i.addClasses&&this.element.addClass("ui-droppable")},_addToManager:function(t){e.ui.ddmanager.droppables[t]=e.ui.ddmanager.droppables[t]||[],e.ui.ddmanager.droppables[t].push(this)},_splice:function(e){for(var t=0;e.length>t;t++)e[t]===this&&e.splice(t,1)},_destroy:function(){var t=e.ui.ddmanager.droppables[this.options.scope];this._splice(t),this.element.removeClass("ui-droppable ui-droppable-disabled")},_setOption:function(t,i){if("accept"===t)this.accept=e.isFunction(i)?i:function(e){return e.is(i)};else if("scope"===t){var s=e.ui.ddmanager.droppables[this.options.scope];this._splice(s),this._addToManager(i)}this._super(t,i)},_activate:function(t){var i=e.ui.ddmanager.current;this.options.activeClass&&this.element.addClass(this.options.activeClass),i&&this._trigger("activate",t,this.ui(i))},_deactivate:function(t){var i=e.ui.ddmanager.current;this.options.activeClass&&this.element.removeClass(this.options.activeClass),i&&this._trigger("deactivate",t,this.ui(i))},_over:function(t){var i=e.ui.ddmanager.current;i&&(i.currentItem||i.element)[0]!==this.element[0]&&this.accept.call(this.element[0],i.currentItem||i.element)&&(this.options.hoverClass&&this.element.addClass(this.options.hoverClass),this._trigger("over",t,this.ui(i)))},_out:function(t){var i=e.ui.ddmanager.current;i&&(i.currentItem||i.element)[0]!==this.element[0]&&this.accept.call(this.element[0],i.currentItem||i.element)&&(this.options.hoverClass&&this.element.removeClass(this.options.hoverClass),this._trigger("out",t,this.ui(i)))},_drop:function(t,i){var s=i||e.ui.ddmanager.current,n=!1;return s&&(s.currentItem||s.element)[0]!==this.element[0]?(this.element.find(":data(ui-droppable)").not(".ui-draggable-dragging").each(function(){var i=e(this).droppable("instance");return i.options.greedy&&!i.options.disabled&&i.options.scope===s.options.scope&&i.accept.call(i.element[0],s.currentItem||s.element)&&e.ui.intersect(s,e.extend(i,{offset:i.element.offset()}),i.options.tolerance,t)?(n=!0,!1):void 0}),n?!1:this.accept.call(this.element[0],s.currentItem||s.element)?(this.options.activeClass&&this.element.removeClass(this.options.activeClass),this.options.hoverClass&&this.element.removeClass(this.options.hoverClass),this._trigger("drop",t,this.ui(s)),this.element):!1):!1},ui:function(e){return{draggable:e.currentItem||e.element,helper:e.helper,position:e.position,offset:e.positionAbs}}}),e.ui.intersect=function(){function e(e,t,i){return e>=t&&t+i>e}return function(t,i,s,n){if(!i.offset)return!1;var a=(t.positionAbs||t.position.absolute).left+t.margins.left,o=(t.positionAbs||t.position.absolute).top+t.margins.top,r=a+t.helperProportions.width,h=o+t.helperProportions.height,l=i.offset.left,u=i.offset.top,d=l+i.proportions().width,c=u+i.proportions().height;switch(s){case"fit":return a>=l&&d>=r&&o>=u&&c>=h;case"intersect":return a+t.helperProportions.width/2>l&&d>r-t.helperProportions.width/2&&o+t.helperProportions.height/2>u&&c>h-t.helperProportions.height/2;case"pointer":return e(n.pageY,u,i.proportions().height)&&e(n.pageX,l,i.proportions().width);case"touch":return(o>=u&&c>=o||h>=u&&c>=h||u>o&&h>c)&&(a>=l&&d>=a||r>=l&&d>=r||l>a&&r>d);default:return!1}}}(),e.ui.ddmanager={current:null,droppables:{"default":[]},prepareOffsets:function(t,i){var s,n,a=e.ui.ddmanager.droppables[t.options.scope]||[],o=i?i.type:null,r=(t.currentItem||t.element).find(":data(ui-droppable)").addBack();e:for(s=0;a.length>s;s++)if(!(a[s].options.disabled||t&&!a[s].accept.call(a[s].element[0],t.currentItem||t.element))){for(n=0;r.length>n;n++)if(r[n]===a[s].element[0]){a[s].proportions().height=0;continue e}a[s].visible="none"!==a[s].element.css("display"),a[s].visible&&("mousedown"===o&&a[s]._activate.call(a[s],i),a[s].offset=a[s].element.offset(),a[s].proportions({width:a[s].element[0].offsetWidth,height:a[s].element[0].offsetHeight}))}},drop:function(t,i){var s=!1;return e.each((e.ui.ddmanager.droppables[t.options.scope]||[]).slice(),function(){this.options&&(!this.options.disabled&&this.visible&&e.ui.intersect(t,this,this.options.tolerance,i)&&(s=this._drop.call(this,i)||s),!this.options.disabled&&this.visible&&this.accept.call(this.element[0],t.currentItem||t.element)&&(this.isout=!0,this.isover=!1,this._deactivate.call(this,i)))}),s},dragStart:function(t,i){t.element.parentsUntil("body").bind("scroll.droppable",function(){t.options.refreshPositions||e.ui.ddmanager.prepareOffsets(t,i)})},drag:function(t,i){t.options.refreshPositions&&e.ui.ddmanager.prepareOffsets(t,i),e.each(e.ui.ddmanager.droppables[t.options.scope]||[],function(){if(!this.options.disabled&&!this.greedyChild&&this.visible){var s,n,a,o=e.ui.intersect(t,this,this.options.tolerance,i),r=!o&&this.isover?"isout":o&&!this.isover?"isover":null;r&&(this.options.greedy&&(n=this.options.scope,a=this.element.parents(":data(ui-droppable)").filter(function(){return e(this).droppable("instance").options.scope===n}),a.length&&(s=e(a[0]).droppable("instance"),s.greedyChild="isover"===r)),s&&"isover"===r&&(s.isover=!1,s.isout=!0,s._out.call(s,i)),this[r]=!0,this["isout"===r?"isover":"isout"]=!1,this["isover"===r?"_over":"_out"].call(this,i),s&&"isout"===r&&(s.isout=!1,s.isover=!0,s._over.call(s,i)))}})},dragStop:function(t,i){t.element.parentsUntil("body").unbind("scroll.droppable"),t.options.refreshPositions||e.ui.ddmanager.prepareOffsets(t,i)}},e.ui.droppable;var y="ui-effects-",b=e;e.effects={effect:{}},function(e,t){function i(e,t,i){var s=d[t.type]||{};return null==e?i||!t.def?null:t.def:(e=s.floor?~~e:parseFloat(e),isNaN(e)?t.def:s.mod?(e+s.mod)%s.mod:0>e?0:e>s.max?s.max:e)}function s(i){var s=l(),n=s._rgba=[];return i=i.toLowerCase(),f(h,function(e,a){var o,r=a.re.exec(i),h=r&&a.parse(r),l=a.space||"rgba";return h?(o=s[l](h),s[u[l].cache]=o[u[l].cache],n=s._rgba=o._rgba,!1):t}),n.length?("0,0,0,0"===n.join()&&e.extend(n,a.transparent),s):a[i]}function n(e,t,i){return i=(i+1)%1,1>6*i?e+6*(t-e)*i:1>2*i?t:2>3*i?e+6*(t-e)*(2/3-i):e}var a,o="backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor",r=/^([\-+])=\s*(\d+\.?\d*)/,h=[{re:/rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,parse:function(e){return[e[1],e[2],e[3],e[4]]}},{re:/rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,parse:function(e){return[2.55*e[1],2.55*e[2],2.55*e[3],e[4]]}},{re:/#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/,parse:function(e){return[parseInt(e[1],16),parseInt(e[2],16),parseInt(e[3],16)]}},{re:/#([a-f0-9])([a-f0-9])([a-f0-9])/,parse:function(e){return[parseInt(e[1]+e[1],16),parseInt(e[2]+e[2],16),parseInt(e[3]+e[3],16)]}},{re:/hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,space:"hsla",parse:function(e){return[e[1],e[2]/100,e[3]/100,e[4]]}}],l=e.Color=function(t,i,s,n){return new e.Color.fn.parse(t,i,s,n)},u={rgba:{props:{red:{idx:0,type:"byte"},green:{idx:1,type:"byte"},blue:{idx:2,type:"byte"}}},hsla:{props:{hue:{idx:0,type:"degrees"},saturation:{idx:1,type:"percent"},lightness:{idx:2,type:"percent"}}}},d={"byte":{floor:!0,max:255},percent:{max:1},degrees:{mod:360,floor:!0}},c=l.support={},p=e("<p>")[0],f=e.each;p.style.cssText="background-color:rgba(1,1,1,.5)",c.rgba=p.style.backgroundColor.indexOf("rgba")>-1,f(u,function(e,t){t.cache="_"+e,t.props.alpha={idx:3,type:"percent",def:1}}),l.fn=e.extend(l.prototype,{parse:function(n,o,r,h){if(n===t)return this._rgba=[null,null,null,null],this;(n.jquery||n.nodeType)&&(n=e(n).css(o),o=t);var d=this,c=e.type(n),p=this._rgba=[];return o!==t&&(n=[n,o,r,h],c="array"),"string"===c?this.parse(s(n)||a._default):"array"===c?(f(u.rgba.props,function(e,t){p[t.idx]=i(n[t.idx],t)}),this):"object"===c?(n instanceof l?f(u,function(e,t){n[t.cache]&&(d[t.cache]=n[t.cache].slice())}):f(u,function(t,s){var a=s.cache;f(s.props,function(e,t){if(!d[a]&&s.to){if("alpha"===e||null==n[e])return;d[a]=s.to(d._rgba)}d[a][t.idx]=i(n[e],t,!0)}),d[a]&&0>e.inArray(null,d[a].slice(0,3))&&(d[a][3]=1,s.from&&(d._rgba=s.from(d[a])))}),this):t},is:function(e){var i=l(e),s=!0,n=this;return f(u,function(e,a){var o,r=i[a.cache];return r&&(o=n[a.cache]||a.to&&a.to(n._rgba)||[],f(a.props,function(e,i){return null!=r[i.idx]?s=r[i.idx]===o[i.idx]:t})),s}),s},_space:function(){var e=[],t=this;return f(u,function(i,s){t[s.cache]&&e.push(i)}),e.pop()},transition:function(e,t){var s=l(e),n=s._space(),a=u[n],o=0===this.alpha()?l("transparent"):this,r=o[a.cache]||a.to(o._rgba),h=r.slice();return s=s[a.cache],f(a.props,function(e,n){var a=n.idx,o=r[a],l=s[a],u=d[n.type]||{};null!==l&&(null===o?h[a]=l:(u.mod&&(l-o>u.mod/2?o+=u.mod:o-l>u.mod/2&&(o-=u.mod)),h[a]=i((l-o)*t+o,n)))}),this[n](h)},blend:function(t){if(1===this._rgba[3])return this;var i=this._rgba.slice(),s=i.pop(),n=l(t)._rgba;return l(e.map(i,function(e,t){return(1-s)*n[t]+s*e}))},toRgbaString:function(){var t="rgba(",i=e.map(this._rgba,function(e,t){return null==e?t>2?1:0:e});return 1===i[3]&&(i.pop(),t="rgb("),t+i.join()+")"},toHslaString:function(){var t="hsla(",i=e.map(this.hsla(),function(e,t){return null==e&&(e=t>2?1:0),t&&3>t&&(e=Math.round(100*e)+"%"),e});return 1===i[3]&&(i.pop(),t="hsl("),t+i.join()+")"},toHexString:function(t){var i=this._rgba.slice(),s=i.pop();return t&&i.push(~~(255*s)),"#"+e.map(i,function(e){return e=(e||0).toString(16),1===e.length?"0"+e:e}).join("")},toString:function(){return 0===this._rgba[3]?"transparent":this.toRgbaString()}}),l.fn.parse.prototype=l.fn,u.hsla.to=function(e){if(null==e[0]||null==e[1]||null==e[2])return[null,null,null,e[3]];var t,i,s=e[0]/255,n=e[1]/255,a=e[2]/255,o=e[3],r=Math.max(s,n,a),h=Math.min(s,n,a),l=r-h,u=r+h,d=.5*u;return t=h===r?0:s===r?60*(n-a)/l+360:n===r?60*(a-s)/l+120:60*(s-n)/l+240,i=0===l?0:.5>=d?l/u:l/(2-u),[Math.round(t)%360,i,d,null==o?1:o]},u.hsla.from=function(e){if(null==e[0]||null==e[1]||null==e[2])return[null,null,null,e[3]];var t=e[0]/360,i=e[1],s=e[2],a=e[3],o=.5>=s?s*(1+i):s+i-s*i,r=2*s-o;return[Math.round(255*n(r,o,t+1/3)),Math.round(255*n(r,o,t)),Math.round(255*n(r,o,t-1/3)),a]},f(u,function(s,n){var a=n.props,o=n.cache,h=n.to,u=n.from;l.fn[s]=function(s){if(h&&!this[o]&&(this[o]=h(this._rgba)),s===t)return this[o].slice();var n,r=e.type(s),d="array"===r||"object"===r?s:arguments,c=this[o].slice();return f(a,function(e,t){var s=d["object"===r?e:t.idx];null==s&&(s=c[t.idx]),c[t.idx]=i(s,t)}),u?(n=l(u(c)),n[o]=c,n):l(c)},f(a,function(t,i){l.fn[t]||(l.fn[t]=function(n){var a,o=e.type(n),h="alpha"===t?this._hsla?"hsla":"rgba":s,l=this[h](),u=l[i.idx];return"undefined"===o?u:("function"===o&&(n=n.call(this,u),o=e.type(n)),null==n&&i.empty?this:("string"===o&&(a=r.exec(n),a&&(n=u+parseFloat(a[2])*("+"===a[1]?1:-1))),l[i.idx]=n,this[h](l)))})})}),l.hook=function(t){var i=t.split(" ");f(i,function(t,i){e.cssHooks[i]={set:function(t,n){var a,o,r="";if("transparent"!==n&&("string"!==e.type(n)||(a=s(n)))){if(n=l(a||n),!c.rgba&&1!==n._rgba[3]){for(o="backgroundColor"===i?t.parentNode:t;(""===r||"transparent"===r)&&o&&o.style;)try{r=e.css(o,"backgroundColor"),o=o.parentNode}catch(h){}n=n.blend(r&&"transparent"!==r?r:"_default")}n=n.toRgbaString()}try{t.style[i]=n}catch(h){}}},e.fx.step[i]=function(t){t.colorInit||(t.start=l(t.elem,i),t.end=l(t.end),t.colorInit=!0),e.cssHooks[i].set(t.elem,t.start.transition(t.end,t.pos))}})},l.hook(o),e.cssHooks.borderColor={expand:function(e){var t={};return f(["Top","Right","Bottom","Left"],function(i,s){t["border"+s+"Color"]=e}),t}},a=e.Color.names={aqua:"#00ffff",black:"#000000",blue:"#0000ff",fuchsia:"#ff00ff",gray:"#808080",green:"#008000",lime:"#00ff00",maroon:"#800000",navy:"#000080",olive:"#808000",purple:"#800080",red:"#ff0000",silver:"#c0c0c0",teal:"#008080",white:"#ffffff",yellow:"#ffff00",transparent:[null,null,null,0],_default:"#ffffff"}}(b),function(){function t(t){var i,s,n=t.ownerDocument.defaultView?t.ownerDocument.defaultView.getComputedStyle(t,null):t.currentStyle,a={};if(n&&n.length&&n[0]&&n[n[0]])for(s=n.length;s--;)i=n[s],"string"==typeof n[i]&&(a[e.camelCase(i)]=n[i]);else for(i in n)"string"==typeof n[i]&&(a[i]=n[i]);return a}function i(t,i){var s,a,o={};for(s in i)a=i[s],t[s]!==a&&(n[s]||(e.fx.step[s]||!isNaN(parseFloat(a)))&&(o[s]=a));return o}var s=["add","remove","toggle"],n={border:1,borderBottom:1,borderColor:1,borderLeft:1,borderRight:1,borderTop:1,borderWidth:1,margin:1,padding:1};e.each(["borderLeftStyle","borderRightStyle","borderBottomStyle","borderTopStyle"],function(t,i){e.fx.step[i]=function(e){("none"!==e.end&&!e.setAttr||1===e.pos&&!e.setAttr)&&(b.style(e.elem,i,e.end),e.setAttr=!0)}}),e.fn.addBack||(e.fn.addBack=function(e){return this.add(null==e?this.prevObject:this.prevObject.filter(e))}),e.effects.animateClass=function(n,a,o,r){var h=e.speed(a,o,r);return this.queue(function(){var a,o=e(this),r=o.attr("class")||"",l=h.children?o.find("*").addBack():o;l=l.map(function(){var i=e(this);return{el:i,start:t(this)}}),a=function(){e.each(s,function(e,t){n[t]&&o[t+"Class"](n[t])})},a(),l=l.map(function(){return this.end=t(this.el[0]),this.diff=i(this.start,this.end),this}),o.attr("class",r),l=l.map(function(){var t=this,i=e.Deferred(),s=e.extend({},h,{queue:!1,complete:function(){i.resolve(t)}});return this.el.animate(this.diff,s),i.promise()}),e.when.apply(e,l.get()).done(function(){a(),e.each(arguments,function(){var t=this.el;e.each(this.diff,function(e){t.css(e,"")})}),h.complete.call(o[0])})})},e.fn.extend({addClass:function(t){return function(i,s,n,a){return s?e.effects.animateClass.call(this,{add:i},s,n,a):t.apply(this,arguments)}}(e.fn.addClass),removeClass:function(t){return function(i,s,n,a){return arguments.length>1?e.effects.animateClass.call(this,{remove:i},s,n,a):t.apply(this,arguments)}}(e.fn.removeClass),toggleClass:function(t){return function(i,s,n,a,o){return"boolean"==typeof s||void 0===s?n?e.effects.animateClass.call(this,s?{add:i}:{remove:i},n,a,o):t.apply(this,arguments):e.effects.animateClass.call(this,{toggle:i},s,n,a)}}(e.fn.toggleClass),switchClass:function(t,i,s,n,a){return e.effects.animateClass.call(this,{add:i,remove:t},s,n,a)}})}(),function(){function t(t,i,s,n){return e.isPlainObject(t)&&(i=t,t=t.effect),t={effect:t},null==i&&(i={}),e.isFunction(i)&&(n=i,s=null,i={}),("number"==typeof i||e.fx.speeds[i])&&(n=s,s=i,i={}),e.isFunction(s)&&(n=s,s=null),i&&e.extend(t,i),s=s||i.duration,t.duration=e.fx.off?0:"number"==typeof s?s:s in e.fx.speeds?e.fx.speeds[s]:e.fx.speeds._default,t.complete=n||i.complete,t}function i(t){return!t||"number"==typeof t||e.fx.speeds[t]?!0:"string"!=typeof t||e.effects.effect[t]?e.isFunction(t)?!0:"object"!=typeof t||t.effect?!1:!0:!0}e.extend(e.effects,{version:"1.11.4",save:function(e,t){for(var i=0;t.length>i;i++)null!==t[i]&&e.data(y+t[i],e[0].style[t[i]])},restore:function(e,t){var i,s;for(s=0;t.length>s;s++)null!==t[s]&&(i=e.data(y+t[s]),void 0===i&&(i=""),e.css(t[s],i))},setMode:function(e,t){return"toggle"===t&&(t=e.is(":hidden")?"show":"hide"),t},getBaseline:function(e,t){var i,s;switch(e[0]){case"top":i=0;break;case"middle":i=.5;break;case"bottom":i=1;break;default:i=e[0]/t.height}switch(e[1]){case"left":s=0;break;case"center":s=.5;break;case"right":s=1;break;default:s=e[1]/t.width}return{x:s,y:i}},createWrapper:function(t){if(t.parent().is(".ui-effects-wrapper"))return t.parent();var i={width:t.outerWidth(!0),height:t.outerHeight(!0),"float":t.css("float")},s=e("<div></div>").addClass("ui-effects-wrapper").css({fontSize:"100%",background:"transparent",border:"none",margin:0,padding:0}),n={width:t.width(),height:t.height()},a=document.activeElement;try{a.id}catch(o){a=document.body}return t.wrap(s),(t[0]===a||e.contains(t[0],a))&&e(a).focus(),s=t.parent(),"static"===t.css("position")?(s.css({position:"relative"}),t.css({position:"relative"})):(e.extend(i,{position:t.css("position"),zIndex:t.css("z-index")}),e.each(["top","left","bottom","right"],function(e,s){i[s]=t.css(s),isNaN(parseInt(i[s],10))&&(i[s]="auto")}),t.css({position:"relative",top:0,left:0,right:"auto",bottom:"auto"})),t.css(n),s.css(i).show()},removeWrapper:function(t){var i=document.activeElement;return t.parent().is(".ui-effects-wrapper")&&(t.parent().replaceWith(t),(t[0]===i||e.contains(t[0],i))&&e(i).focus()),t},setTransition:function(t,i,s,n){return n=n||{},e.each(i,function(e,i){var a=t.cssUnit(i);a[0]>0&&(n[i]=a[0]*s+a[1])}),n}}),e.fn.extend({effect:function(){function i(t){function i(){e.isFunction(a)&&a.call(n[0]),e.isFunction(t)&&t()}var n=e(this),a=s.complete,r=s.mode;(n.is(":hidden")?"hide"===r:"show"===r)?(n[r](),i()):o.call(n[0],s,i)}var s=t.apply(this,arguments),n=s.mode,a=s.queue,o=e.effects.effect[s.effect];return e.fx.off||!o?n?this[n](s.duration,s.complete):this.each(function(){s.complete&&s.complete.call(this)}):a===!1?this.each(i):this.queue(a||"fx",i)},show:function(e){return function(s){if(i(s))return e.apply(this,arguments);var n=t.apply(this,arguments);return n.mode="show",this.effect.call(this,n)}}(e.fn.show),hide:function(e){return function(s){if(i(s))return e.apply(this,arguments);var n=t.apply(this,arguments);return n.mode="hide",this.effect.call(this,n)}}(e.fn.hide),toggle:function(e){return function(s){if(i(s)||"boolean"==typeof s)return e.apply(this,arguments);var n=t.apply(this,arguments);return n.mode="toggle",this.effect.call(this,n)}}(e.fn.toggle),cssUnit:function(t){var i=this.css(t),s=[];return e.each(["em","px","%","pt"],function(e,t){i.indexOf(t)>0&&(s=[parseFloat(i),t])}),s}})}(),function(){var t={};e.each(["Quad","Cubic","Quart","Quint","Expo"],function(e,i){t[i]=function(t){return Math.pow(t,e+2)}}),e.extend(t,{Sine:function(e){return 1-Math.cos(e*Math.PI/2)},Circ:function(e){return 1-Math.sqrt(1-e*e)},Elastic:function(e){return 0===e||1===e?e:-Math.pow(2,8*(e-1))*Math.sin((80*(e-1)-7.5)*Math.PI/15)},Back:function(e){return e*e*(3*e-2)},Bounce:function(e){for(var t,i=4;((t=Math.pow(2,--i))-1)/11>e;);return 1/Math.pow(4,3-i)-7.5625*Math.pow((3*t-2)/22-e,2)}}),e.each(t,function(t,i){e.easing["easeIn"+t]=i,e.easing["easeOut"+t]=function(e){return 1-i(1-e)},e.easing["easeInOut"+t]=function(e){return.5>e?i(2*e)/2:1-i(-2*e+2)/2}})}(),e.effects,e.effects.effect.blind=function(t,i){var s,n,a,o=e(this),r=/up|down|vertical/,h=/up|left|vertical|horizontal/,l=["position","top","bottom","left","right","height","width"],u=e.effects.setMode(o,t.mode||"hide"),d=t.direction||"up",c=r.test(d),p=c?"height":"width",f=c?"top":"left",m=h.test(d),g={},v="show"===u;o.parent().is(".ui-effects-wrapper")?e.effects.save(o.parent(),l):e.effects.save(o,l),o.show(),s=e.effects.createWrapper(o).css({overflow:"hidden"}),n=s[p](),a=parseFloat(s.css(f))||0,g[p]=v?n:0,m||(o.css(c?"bottom":"right",0).css(c?"top":"left","auto").css({position:"absolute"}),g[f]=v?a:n+a),v&&(s.css(p,0),m||s.css(f,a+n)),s.animate(g,{duration:t.duration,easing:t.easing,queue:!1,complete:function(){"hide"===u&&o.hide(),e.effects.restore(o,l),e.effects.removeWrapper(o),i()}})},e.effects.effect.bounce=function(t,i){var s,n,a,o=e(this),r=["position","top","bottom","left","right","height","width"],h=e.effects.setMode(o,t.mode||"effect"),l="hide"===h,u="show"===h,d=t.direction||"up",c=t.distance,p=t.times||5,f=2*p+(u||l?1:0),m=t.duration/f,g=t.easing,v="up"===d||"down"===d?"top":"left",y="up"===d||"left"===d,b=o.queue(),_=b.length;for((u||l)&&r.push("opacity"),e.effects.save(o,r),o.show(),e.effects.createWrapper(o),c||(c=o["top"===v?"outerHeight":"outerWidth"]()/3),u&&(a={opacity:1},a[v]=0,o.css("opacity",0).css(v,y?2*-c:2*c).animate(a,m,g)),l&&(c/=Math.pow(2,p-1)),a={},a[v]=0,s=0;p>s;s++)n={},n[v]=(y?"-=":"+=")+c,o.animate(n,m,g).animate(a,m,g),c=l?2*c:c/2;l&&(n={opacity:0},n[v]=(y?"-=":"+=")+c,o.animate(n,m,g)),o.queue(function(){l&&o.hide(),e.effects.restore(o,r),e.effects.removeWrapper(o),i()}),_>1&&b.splice.apply(b,[1,0].concat(b.splice(_,f+1))),o.dequeue()},e.effects.effect.clip=function(t,i){var s,n,a,o=e(this),r=["position","top","bottom","left","right","height","width"],h=e.effects.setMode(o,t.mode||"hide"),l="show"===h,u=t.direction||"vertical",d="vertical"===u,c=d?"height":"width",p=d?"top":"left",f={};e.effects.save(o,r),o.show(),s=e.effects.createWrapper(o).css({overflow:"hidden"}),n="IMG"===o[0].tagName?s:o,a=n[c](),l&&(n.css(c,0),n.css(p,a/2)),f[c]=l?a:0,f[p]=l?0:a/2,n.animate(f,{queue:!1,duration:t.duration,easing:t.easing,complete:function(){l||o.hide(),e.effects.restore(o,r),e.effects.removeWrapper(o),i()}})},e.effects.effect.drop=function(t,i){var s,n=e(this),a=["position","top","bottom","left","right","opacity","height","width"],o=e.effects.setMode(n,t.mode||"hide"),r="show"===o,h=t.direction||"left",l="up"===h||"down"===h?"top":"left",u="up"===h||"left"===h?"pos":"neg",d={opacity:r?1:0};e.effects.save(n,a),n.show(),e.effects.createWrapper(n),s=t.distance||n["top"===l?"outerHeight":"outerWidth"](!0)/2,r&&n.css("opacity",0).css(l,"pos"===u?-s:s),d[l]=(r?"pos"===u?"+=":"-=":"pos"===u?"-=":"+=")+s,n.animate(d,{queue:!1,duration:t.duration,easing:t.easing,complete:function(){"hide"===o&&n.hide(),e.effects.restore(n,a),e.effects.removeWrapper(n),i()}})},e.effects.effect.explode=function(t,i){function s(){b.push(this),b.length===d*c&&n()}function n(){p.css({visibility:"visible"}),e(b).remove(),m||p.hide(),i()}var a,o,r,h,l,u,d=t.pieces?Math.round(Math.sqrt(t.pieces)):3,c=d,p=e(this),f=e.effects.setMode(p,t.mode||"hide"),m="show"===f,g=p.show().css("visibility","hidden").offset(),v=Math.ceil(p.outerWidth()/c),y=Math.ceil(p.outerHeight()/d),b=[];for(a=0;d>a;a++)for(h=g.top+a*y,u=a-(d-1)/2,o=0;c>o;o++)r=g.left+o*v,l=o-(c-1)/2,p.clone().appendTo("body").wrap("<div></div>").css({position:"absolute",visibility:"visible",left:-o*v,top:-a*y}).parent().addClass("ui-effects-explode").css({position:"absolute",overflow:"hidden",width:v,height:y,left:r+(m?l*v:0),top:h+(m?u*y:0),opacity:m?0:1}).animate({left:r+(m?0:l*v),top:h+(m?0:u*y),opacity:m?1:0},t.duration||500,t.easing,s)},e.effects.effect.fade=function(t,i){var s=e(this),n=e.effects.setMode(s,t.mode||"toggle");s.animate({opacity:n},{queue:!1,duration:t.duration,easing:t.easing,complete:i})},e.effects.effect.fold=function(t,i){var s,n,a=e(this),o=["position","top","bottom","left","right","height","width"],r=e.effects.setMode(a,t.mode||"hide"),h="show"===r,l="hide"===r,u=t.size||15,d=/([0-9]+)%/.exec(u),c=!!t.horizFirst,p=h!==c,f=p?["width","height"]:["height","width"],m=t.duration/2,g={},v={};e.effects.save(a,o),a.show(),s=e.effects.createWrapper(a).css({overflow:"hidden"}),n=p?[s.width(),s.height()]:[s.height(),s.width()],d&&(u=parseInt(d[1],10)/100*n[l?0:1]),h&&s.css(c?{height:0,width:u}:{height:u,width:0}),g[f[0]]=h?n[0]:u,v[f[1]]=h?n[1]:0,s.animate(g,m,t.easing).animate(v,m,t.easing,function(){l&&a.hide(),e.effects.restore(a,o),e.effects.removeWrapper(a),i()})},e.effects.effect.highlight=function(t,i){var s=e(this),n=["backgroundImage","backgroundColor","opacity"],a=e.effects.setMode(s,t.mode||"show"),o={backgroundColor:s.css("backgroundColor")};"hide"===a&&(o.opacity=0),e.effects.save(s,n),s.show().css({backgroundImage:"none",backgroundColor:t.color||"#ffff99"}).animate(o,{queue:!1,duration:t.duration,easing:t.easing,complete:function(){"hide"===a&&s.hide(),e.effects.restore(s,n),i()}})},e.effects.effect.size=function(t,i){var s,n,a,o=e(this),r=["position","top","bottom","left","right","width","height","overflow","opacity"],h=["position","top","bottom","left","right","overflow","opacity"],l=["width","height","overflow"],u=["fontSize"],d=["borderTopWidth","borderBottomWidth","paddingTop","paddingBottom"],c=["borderLeftWidth","borderRightWidth","paddingLeft","paddingRight"],p=e.effects.setMode(o,t.mode||"effect"),f=t.restore||"effect"!==p,m=t.scale||"both",g=t.origin||["middle","center"],v=o.css("position"),y=f?r:h,b={height:0,width:0,outerHeight:0,outerWidth:0};"show"===p&&o.show(),s={height:o.height(),width:o.width(),outerHeight:o.outerHeight(),outerWidth:o.outerWidth()},"toggle"===t.mode&&"show"===p?(o.from=t.to||b,o.to=t.from||s):(o.from=t.from||("show"===p?b:s),o.to=t.to||("hide"===p?b:s)),a={from:{y:o.from.height/s.height,x:o.from.width/s.width},to:{y:o.to.height/s.height,x:o.to.width/s.width}},("box"===m||"both"===m)&&(a.from.y!==a.to.y&&(y=y.concat(d),o.from=e.effects.setTransition(o,d,a.from.y,o.from),o.to=e.effects.setTransition(o,d,a.to.y,o.to)),a.from.x!==a.to.x&&(y=y.concat(c),o.from=e.effects.setTransition(o,c,a.from.x,o.from),o.to=e.effects.setTransition(o,c,a.to.x,o.to))),("content"===m||"both"===m)&&a.from.y!==a.to.y&&(y=y.concat(u).concat(l),o.from=e.effects.setTransition(o,u,a.from.y,o.from),o.to=e.effects.setTransition(o,u,a.to.y,o.to)),e.effects.save(o,y),o.show(),e.effects.createWrapper(o),o.css("overflow","hidden").css(o.from),g&&(n=e.effects.getBaseline(g,s),o.from.top=(s.outerHeight-o.outerHeight())*n.y,o.from.left=(s.outerWidth-o.outerWidth())*n.x,o.to.top=(s.outerHeight-o.to.outerHeight)*n.y,o.to.left=(s.outerWidth-o.to.outerWidth)*n.x),o.css(o.from),("content"===m||"both"===m)&&(d=d.concat(["marginTop","marginBottom"]).concat(u),c=c.concat(["marginLeft","marginRight"]),l=r.concat(d).concat(c),o.find("*[width]").each(function(){var i=e(this),s={height:i.height(),width:i.width(),outerHeight:i.outerHeight(),outerWidth:i.outerWidth()};
f&&e.effects.save(i,l),i.from={height:s.height*a.from.y,width:s.width*a.from.x,outerHeight:s.outerHeight*a.from.y,outerWidth:s.outerWidth*a.from.x},i.to={height:s.height*a.to.y,width:s.width*a.to.x,outerHeight:s.height*a.to.y,outerWidth:s.width*a.to.x},a.from.y!==a.to.y&&(i.from=e.effects.setTransition(i,d,a.from.y,i.from),i.to=e.effects.setTransition(i,d,a.to.y,i.to)),a.from.x!==a.to.x&&(i.from=e.effects.setTransition(i,c,a.from.x,i.from),i.to=e.effects.setTransition(i,c,a.to.x,i.to)),i.css(i.from),i.animate(i.to,t.duration,t.easing,function(){f&&e.effects.restore(i,l)})})),o.animate(o.to,{queue:!1,duration:t.duration,easing:t.easing,complete:function(){0===o.to.opacity&&o.css("opacity",o.from.opacity),"hide"===p&&o.hide(),e.effects.restore(o,y),f||("static"===v?o.css({position:"relative",top:o.to.top,left:o.to.left}):e.each(["top","left"],function(e,t){o.css(t,function(t,i){var s=parseInt(i,10),n=e?o.to.left:o.to.top;return"auto"===i?n+"px":s+n+"px"})})),e.effects.removeWrapper(o),i()}})},e.effects.effect.scale=function(t,i){var s=e(this),n=e.extend(!0,{},t),a=e.effects.setMode(s,t.mode||"effect"),o=parseInt(t.percent,10)||(0===parseInt(t.percent,10)?0:"hide"===a?0:100),r=t.direction||"both",h=t.origin,l={height:s.height(),width:s.width(),outerHeight:s.outerHeight(),outerWidth:s.outerWidth()},u={y:"horizontal"!==r?o/100:1,x:"vertical"!==r?o/100:1};n.effect="size",n.queue=!1,n.complete=i,"effect"!==a&&(n.origin=h||["middle","center"],n.restore=!0),n.from=t.from||("show"===a?{height:0,width:0,outerHeight:0,outerWidth:0}:l),n.to={height:l.height*u.y,width:l.width*u.x,outerHeight:l.outerHeight*u.y,outerWidth:l.outerWidth*u.x},n.fade&&("show"===a&&(n.from.opacity=0,n.to.opacity=1),"hide"===a&&(n.from.opacity=1,n.to.opacity=0)),s.effect(n)},e.effects.effect.puff=function(t,i){var s=e(this),n=e.effects.setMode(s,t.mode||"hide"),a="hide"===n,o=parseInt(t.percent,10)||150,r=o/100,h={height:s.height(),width:s.width(),outerHeight:s.outerHeight(),outerWidth:s.outerWidth()};e.extend(t,{effect:"scale",queue:!1,fade:!0,mode:n,complete:i,percent:a?o:100,from:a?h:{height:h.height*r,width:h.width*r,outerHeight:h.outerHeight*r,outerWidth:h.outerWidth*r}}),s.effect(t)},e.effects.effect.pulsate=function(t,i){var s,n=e(this),a=e.effects.setMode(n,t.mode||"show"),o="show"===a,r="hide"===a,h=o||"hide"===a,l=2*(t.times||5)+(h?1:0),u=t.duration/l,d=0,c=n.queue(),p=c.length;for((o||!n.is(":visible"))&&(n.css("opacity",0).show(),d=1),s=1;l>s;s++)n.animate({opacity:d},u,t.easing),d=1-d;n.animate({opacity:d},u,t.easing),n.queue(function(){r&&n.hide(),i()}),p>1&&c.splice.apply(c,[1,0].concat(c.splice(p,l+1))),n.dequeue()},e.effects.effect.shake=function(t,i){var s,n=e(this),a=["position","top","bottom","left","right","height","width"],o=e.effects.setMode(n,t.mode||"effect"),r=t.direction||"left",h=t.distance||20,l=t.times||3,u=2*l+1,d=Math.round(t.duration/u),c="up"===r||"down"===r?"top":"left",p="up"===r||"left"===r,f={},m={},g={},v=n.queue(),y=v.length;for(e.effects.save(n,a),n.show(),e.effects.createWrapper(n),f[c]=(p?"-=":"+=")+h,m[c]=(p?"+=":"-=")+2*h,g[c]=(p?"-=":"+=")+2*h,n.animate(f,d,t.easing),s=1;l>s;s++)n.animate(m,d,t.easing).animate(g,d,t.easing);n.animate(m,d,t.easing).animate(f,d/2,t.easing).queue(function(){"hide"===o&&n.hide(),e.effects.restore(n,a),e.effects.removeWrapper(n),i()}),y>1&&v.splice.apply(v,[1,0].concat(v.splice(y,u+1))),n.dequeue()},e.effects.effect.slide=function(t,i){var s,n=e(this),a=["position","top","bottom","left","right","width","height"],o=e.effects.setMode(n,t.mode||"show"),r="show"===o,h=t.direction||"left",l="up"===h||"down"===h?"top":"left",u="up"===h||"left"===h,d={};e.effects.save(n,a),n.show(),s=t.distance||n["top"===l?"outerHeight":"outerWidth"](!0),e.effects.createWrapper(n).css({overflow:"hidden"}),r&&n.css(l,u?isNaN(s)?"-"+s:-s:s),d[l]=(r?u?"+=":"-=":u?"-=":"+=")+s,n.animate(d,{queue:!1,duration:t.duration,easing:t.easing,complete:function(){"hide"===o&&n.hide(),e.effects.restore(n,a),e.effects.removeWrapper(n),i()}})},e.effects.effect.transfer=function(t,i){var s=e(this),n=e(t.to),a="fixed"===n.css("position"),o=e("body"),r=a?o.scrollTop():0,h=a?o.scrollLeft():0,l=n.offset(),u={top:l.top-r,left:l.left-h,height:n.innerHeight(),width:n.innerWidth()},d=s.offset(),c=e("<div class='ui-effects-transfer'></div>").appendTo(document.body).addClass(t.className).css({top:d.top-r,left:d.left-h,height:s.innerHeight(),width:s.innerWidth(),position:a?"fixed":"absolute"}).animate(u,t.duration,t.easing,function(){c.remove(),i()})},e.widget("ui.progressbar",{version:"1.11.4",options:{max:100,value:0,change:null,complete:null},min:0,_create:function(){this.oldValue=this.options.value=this._constrainedValue(),this.element.addClass("ui-progressbar ui-widget ui-widget-content ui-corner-all").attr({role:"progressbar","aria-valuemin":this.min}),this.valueDiv=e("<div class='ui-progressbar-value ui-widget-header ui-corner-left'></div>").appendTo(this.element),this._refreshValue()},_destroy:function(){this.element.removeClass("ui-progressbar ui-widget ui-widget-content ui-corner-all").removeAttr("role").removeAttr("aria-valuemin").removeAttr("aria-valuemax").removeAttr("aria-valuenow"),this.valueDiv.remove()},value:function(e){return void 0===e?this.options.value:(this.options.value=this._constrainedValue(e),this._refreshValue(),void 0)},_constrainedValue:function(e){return void 0===e&&(e=this.options.value),this.indeterminate=e===!1,"number"!=typeof e&&(e=0),this.indeterminate?!1:Math.min(this.options.max,Math.max(this.min,e))},_setOptions:function(e){var t=e.value;delete e.value,this._super(e),this.options.value=this._constrainedValue(t),this._refreshValue()},_setOption:function(e,t){"max"===e&&(t=Math.max(this.min,t)),"disabled"===e&&this.element.toggleClass("ui-state-disabled",!!t).attr("aria-disabled",t),this._super(e,t)},_percentage:function(){return this.indeterminate?100:100*(this.options.value-this.min)/(this.options.max-this.min)},_refreshValue:function(){var t=this.options.value,i=this._percentage();this.valueDiv.toggle(this.indeterminate||t>this.min).toggleClass("ui-corner-right",t===this.options.max).width(i.toFixed(0)+"%"),this.element.toggleClass("ui-progressbar-indeterminate",this.indeterminate),this.indeterminate?(this.element.removeAttr("aria-valuenow"),this.overlayDiv||(this.overlayDiv=e("<div class='ui-progressbar-overlay'></div>").appendTo(this.valueDiv))):(this.element.attr({"aria-valuemax":this.options.max,"aria-valuenow":t}),this.overlayDiv&&(this.overlayDiv.remove(),this.overlayDiv=null)),this.oldValue!==t&&(this.oldValue=t,this._trigger("change")),t===this.options.max&&this._trigger("complete")}}),e.widget("ui.selectable",e.ui.mouse,{version:"1.11.4",options:{appendTo:"body",autoRefresh:!0,distance:0,filter:"*",tolerance:"touch",selected:null,selecting:null,start:null,stop:null,unselected:null,unselecting:null},_create:function(){var t,i=this;this.element.addClass("ui-selectable"),this.dragged=!1,this.refresh=function(){t=e(i.options.filter,i.element[0]),t.addClass("ui-selectee"),t.each(function(){var t=e(this),i=t.offset();e.data(this,"selectable-item",{element:this,$element:t,left:i.left,top:i.top,right:i.left+t.outerWidth(),bottom:i.top+t.outerHeight(),startselected:!1,selected:t.hasClass("ui-selected"),selecting:t.hasClass("ui-selecting"),unselecting:t.hasClass("ui-unselecting")})})},this.refresh(),this.selectees=t.addClass("ui-selectee"),this._mouseInit(),this.helper=e("<div class='ui-selectable-helper'></div>")},_destroy:function(){this.selectees.removeClass("ui-selectee").removeData("selectable-item"),this.element.removeClass("ui-selectable ui-selectable-disabled"),this._mouseDestroy()},_mouseStart:function(t){var i=this,s=this.options;this.opos=[t.pageX,t.pageY],this.options.disabled||(this.selectees=e(s.filter,this.element[0]),this._trigger("start",t),e(s.appendTo).append(this.helper),this.helper.css({left:t.pageX,top:t.pageY,width:0,height:0}),s.autoRefresh&&this.refresh(),this.selectees.filter(".ui-selected").each(function(){var s=e.data(this,"selectable-item");s.startselected=!0,t.metaKey||t.ctrlKey||(s.$element.removeClass("ui-selected"),s.selected=!1,s.$element.addClass("ui-unselecting"),s.unselecting=!0,i._trigger("unselecting",t,{unselecting:s.element}))}),e(t.target).parents().addBack().each(function(){var s,n=e.data(this,"selectable-item");return n?(s=!t.metaKey&&!t.ctrlKey||!n.$element.hasClass("ui-selected"),n.$element.removeClass(s?"ui-unselecting":"ui-selected").addClass(s?"ui-selecting":"ui-unselecting"),n.unselecting=!s,n.selecting=s,n.selected=s,s?i._trigger("selecting",t,{selecting:n.element}):i._trigger("unselecting",t,{unselecting:n.element}),!1):void 0}))},_mouseDrag:function(t){if(this.dragged=!0,!this.options.disabled){var i,s=this,n=this.options,a=this.opos[0],o=this.opos[1],r=t.pageX,h=t.pageY;return a>r&&(i=r,r=a,a=i),o>h&&(i=h,h=o,o=i),this.helper.css({left:a,top:o,width:r-a,height:h-o}),this.selectees.each(function(){var i=e.data(this,"selectable-item"),l=!1;i&&i.element!==s.element[0]&&("touch"===n.tolerance?l=!(i.left>r||a>i.right||i.top>h||o>i.bottom):"fit"===n.tolerance&&(l=i.left>a&&r>i.right&&i.top>o&&h>i.bottom),l?(i.selected&&(i.$element.removeClass("ui-selected"),i.selected=!1),i.unselecting&&(i.$element.removeClass("ui-unselecting"),i.unselecting=!1),i.selecting||(i.$element.addClass("ui-selecting"),i.selecting=!0,s._trigger("selecting",t,{selecting:i.element}))):(i.selecting&&((t.metaKey||t.ctrlKey)&&i.startselected?(i.$element.removeClass("ui-selecting"),i.selecting=!1,i.$element.addClass("ui-selected"),i.selected=!0):(i.$element.removeClass("ui-selecting"),i.selecting=!1,i.startselected&&(i.$element.addClass("ui-unselecting"),i.unselecting=!0),s._trigger("unselecting",t,{unselecting:i.element}))),i.selected&&(t.metaKey||t.ctrlKey||i.startselected||(i.$element.removeClass("ui-selected"),i.selected=!1,i.$element.addClass("ui-unselecting"),i.unselecting=!0,s._trigger("unselecting",t,{unselecting:i.element})))))}),!1}},_mouseStop:function(t){var i=this;return this.dragged=!1,e(".ui-unselecting",this.element[0]).each(function(){var s=e.data(this,"selectable-item");s.$element.removeClass("ui-unselecting"),s.unselecting=!1,s.startselected=!1,i._trigger("unselected",t,{unselected:s.element})}),e(".ui-selecting",this.element[0]).each(function(){var s=e.data(this,"selectable-item");s.$element.removeClass("ui-selecting").addClass("ui-selected"),s.selecting=!1,s.selected=!0,s.startselected=!0,i._trigger("selected",t,{selected:s.element})}),this._trigger("stop",t),this.helper.remove(),!1}}),e.widget("ui.selectmenu",{version:"1.11.4",defaultElement:"<select>",options:{appendTo:null,disabled:null,icons:{button:"ui-icon-triangle-1-s"},position:{my:"left top",at:"left bottom",collision:"none"},width:null,change:null,close:null,focus:null,open:null,select:null},_create:function(){var e=this.element.uniqueId().attr("id");this.ids={element:e,button:e+"-button",menu:e+"-menu"},this._drawButton(),this._drawMenu(),this.options.disabled&&this.disable()},_drawButton:function(){var t=this;this.label=e("label[for='"+this.ids.element+"']").attr("for",this.ids.button),this._on(this.label,{click:function(e){this.button.focus(),e.preventDefault()}}),this.element.hide(),this.button=e("<span>",{"class":"ui-selectmenu-button ui-widget ui-state-default ui-corner-all",tabindex:this.options.disabled?-1:0,id:this.ids.button,role:"combobox","aria-expanded":"false","aria-autocomplete":"list","aria-owns":this.ids.menu,"aria-haspopup":"true"}).insertAfter(this.element),e("<span>",{"class":"ui-icon "+this.options.icons.button}).prependTo(this.button),this.buttonText=e("<span>",{"class":"ui-selectmenu-text"}).appendTo(this.button),this._setText(this.buttonText,this.element.find("option:selected").text()),this._resizeButton(),this._on(this.button,this._buttonEvents),this.button.one("focusin",function(){t.menuItems||t._refreshMenu()}),this._hoverable(this.button),this._focusable(this.button)},_drawMenu:function(){var t=this;this.menu=e("<ul>",{"aria-hidden":"true","aria-labelledby":this.ids.button,id:this.ids.menu}),this.menuWrap=e("<div>",{"class":"ui-selectmenu-menu ui-front"}).append(this.menu).appendTo(this._appendTo()),this.menuInstance=this.menu.menu({role:"listbox",select:function(e,i){e.preventDefault(),t._setSelection(),t._select(i.item.data("ui-selectmenu-item"),e)},focus:function(e,i){var s=i.item.data("ui-selectmenu-item");null!=t.focusIndex&&s.index!==t.focusIndex&&(t._trigger("focus",e,{item:s}),t.isOpen||t._select(s,e)),t.focusIndex=s.index,t.button.attr("aria-activedescendant",t.menuItems.eq(s.index).attr("id"))}}).menu("instance"),this.menu.addClass("ui-corner-bottom").removeClass("ui-corner-all"),this.menuInstance._off(this.menu,"mouseleave"),this.menuInstance._closeOnDocumentClick=function(){return!1},this.menuInstance._isDivider=function(){return!1}},refresh:function(){this._refreshMenu(),this._setText(this.buttonText,this._getSelectedItem().text()),this.options.width||this._resizeButton()},_refreshMenu:function(){this.menu.empty();var e,t=this.element.find("option");t.length&&(this._parseOptions(t),this._renderMenu(this.menu,this.items),this.menuInstance.refresh(),this.menuItems=this.menu.find("li").not(".ui-selectmenu-optgroup"),e=this._getSelectedItem(),this.menuInstance.focus(null,e),this._setAria(e.data("ui-selectmenu-item")),this._setOption("disabled",this.element.prop("disabled")))},open:function(e){this.options.disabled||(this.menuItems?(this.menu.find(".ui-state-focus").removeClass("ui-state-focus"),this.menuInstance.focus(null,this._getSelectedItem())):this._refreshMenu(),this.isOpen=!0,this._toggleAttr(),this._resizeMenu(),this._position(),this._on(this.document,this._documentClick),this._trigger("open",e))},_position:function(){this.menuWrap.position(e.extend({of:this.button},this.options.position))},close:function(e){this.isOpen&&(this.isOpen=!1,this._toggleAttr(),this.range=null,this._off(this.document),this._trigger("close",e))},widget:function(){return this.button},menuWidget:function(){return this.menu},_renderMenu:function(t,i){var s=this,n="";e.each(i,function(i,a){a.optgroup!==n&&(e("<li>",{"class":"ui-selectmenu-optgroup ui-menu-divider"+(a.element.parent("optgroup").prop("disabled")?" ui-state-disabled":""),text:a.optgroup}).appendTo(t),n=a.optgroup),s._renderItemData(t,a)})},_renderItemData:function(e,t){return this._renderItem(e,t).data("ui-selectmenu-item",t)},_renderItem:function(t,i){var s=e("<li>");return i.disabled&&s.addClass("ui-state-disabled"),this._setText(s,i.label),s.appendTo(t)},_setText:function(e,t){t?e.text(t):e.html("&#160;")},_move:function(e,t){var i,s,n=".ui-menu-item";this.isOpen?i=this.menuItems.eq(this.focusIndex):(i=this.menuItems.eq(this.element[0].selectedIndex),n+=":not(.ui-state-disabled)"),s="first"===e||"last"===e?i["first"===e?"prevAll":"nextAll"](n).eq(-1):i[e+"All"](n).eq(0),s.length&&this.menuInstance.focus(t,s)},_getSelectedItem:function(){return this.menuItems.eq(this.element[0].selectedIndex)},_toggle:function(e){this[this.isOpen?"close":"open"](e)},_setSelection:function(){var e;this.range&&(window.getSelection?(e=window.getSelection(),e.removeAllRanges(),e.addRange(this.range)):this.range.select(),this.button.focus())},_documentClick:{mousedown:function(t){this.isOpen&&(e(t.target).closest(".ui-selectmenu-menu, #"+this.ids.button).length||this.close(t))}},_buttonEvents:{mousedown:function(){var e;window.getSelection?(e=window.getSelection(),e.rangeCount&&(this.range=e.getRangeAt(0))):this.range=document.selection.createRange()},click:function(e){this._setSelection(),this._toggle(e)},keydown:function(t){var i=!0;switch(t.keyCode){case e.ui.keyCode.TAB:case e.ui.keyCode.ESCAPE:this.close(t),i=!1;break;case e.ui.keyCode.ENTER:this.isOpen&&this._selectFocusedItem(t);break;case e.ui.keyCode.UP:t.altKey?this._toggle(t):this._move("prev",t);break;case e.ui.keyCode.DOWN:t.altKey?this._toggle(t):this._move("next",t);break;case e.ui.keyCode.SPACE:this.isOpen?this._selectFocusedItem(t):this._toggle(t);break;case e.ui.keyCode.LEFT:this._move("prev",t);break;case e.ui.keyCode.RIGHT:this._move("next",t);break;case e.ui.keyCode.HOME:case e.ui.keyCode.PAGE_UP:this._move("first",t);break;case e.ui.keyCode.END:case e.ui.keyCode.PAGE_DOWN:this._move("last",t);break;default:this.menu.trigger(t),i=!1}i&&t.preventDefault()}},_selectFocusedItem:function(e){var t=this.menuItems.eq(this.focusIndex);t.hasClass("ui-state-disabled")||this._select(t.data("ui-selectmenu-item"),e)},_select:function(e,t){var i=this.element[0].selectedIndex;this.element[0].selectedIndex=e.index,this._setText(this.buttonText,e.label),this._setAria(e),this._trigger("select",t,{item:e}),e.index!==i&&this._trigger("change",t,{item:e}),this.close(t)},_setAria:function(e){var t=this.menuItems.eq(e.index).attr("id");this.button.attr({"aria-labelledby":t,"aria-activedescendant":t}),this.menu.attr("aria-activedescendant",t)},_setOption:function(e,t){"icons"===e&&this.button.find("span.ui-icon").removeClass(this.options.icons.button).addClass(t.button),this._super(e,t),"appendTo"===e&&this.menuWrap.appendTo(this._appendTo()),"disabled"===e&&(this.menuInstance.option("disabled",t),this.button.toggleClass("ui-state-disabled",t).attr("aria-disabled",t),this.element.prop("disabled",t),t?(this.button.attr("tabindex",-1),this.close()):this.button.attr("tabindex",0)),"width"===e&&this._resizeButton()},_appendTo:function(){var t=this.options.appendTo;return t&&(t=t.jquery||t.nodeType?e(t):this.document.find(t).eq(0)),t&&t[0]||(t=this.element.closest(".ui-front")),t.length||(t=this.document[0].body),t},_toggleAttr:function(){this.button.toggleClass("ui-corner-top",this.isOpen).toggleClass("ui-corner-all",!this.isOpen).attr("aria-expanded",this.isOpen),this.menuWrap.toggleClass("ui-selectmenu-open",this.isOpen),this.menu.attr("aria-hidden",!this.isOpen)},_resizeButton:function(){var e=this.options.width;e||(e=this.element.show().outerWidth(),this.element.hide()),this.button.outerWidth(e)},_resizeMenu:function(){this.menu.outerWidth(Math.max(this.button.outerWidth(),this.menu.width("").outerWidth()+1))},_getCreateOptions:function(){return{disabled:this.element.prop("disabled")}},_parseOptions:function(t){var i=[];t.each(function(t,s){var n=e(s),a=n.parent("optgroup");i.push({element:n,index:t,value:n.val(),label:n.text(),optgroup:a.attr("label")||"",disabled:a.prop("disabled")||n.prop("disabled")})}),this.items=i},_destroy:function(){this.menuWrap.remove(),this.button.remove(),this.element.show(),this.element.removeUniqueId(),this.label.attr("for",this.ids.element)}}),e.widget("ui.slider",e.ui.mouse,{version:"1.11.4",widgetEventPrefix:"slide",options:{animate:!1,distance:0,max:100,min:0,orientation:"horizontal",range:!1,step:1,value:0,values:null,change:null,slide:null,start:null,stop:null},numPages:5,_create:function(){this._keySliding=!1,this._mouseSliding=!1,this._animateOff=!0,this._handleIndex=null,this._detectOrientation(),this._mouseInit(),this._calculateNewMax(),this.element.addClass("ui-slider ui-slider-"+this.orientation+" ui-widget"+" ui-widget-content"+" ui-corner-all"),this._refresh(),this._setOption("disabled",this.options.disabled),this._animateOff=!1},_refresh:function(){this._createRange(),this._createHandles(),this._setupEvents(),this._refreshValue()},_createHandles:function(){var t,i,s=this.options,n=this.element.find(".ui-slider-handle").addClass("ui-state-default ui-corner-all"),a="<span class='ui-slider-handle ui-state-default ui-corner-all' tabindex='0'></span>",o=[];for(i=s.values&&s.values.length||1,n.length>i&&(n.slice(i).remove(),n=n.slice(0,i)),t=n.length;i>t;t++)o.push(a);this.handles=n.add(e(o.join("")).appendTo(this.element)),this.handle=this.handles.eq(0),this.handles.each(function(t){e(this).data("ui-slider-handle-index",t)})},_createRange:function(){var t=this.options,i="";t.range?(t.range===!0&&(t.values?t.values.length&&2!==t.values.length?t.values=[t.values[0],t.values[0]]:e.isArray(t.values)&&(t.values=t.values.slice(0)):t.values=[this._valueMin(),this._valueMin()]),this.range&&this.range.length?this.range.removeClass("ui-slider-range-min ui-slider-range-max").css({left:"",bottom:""}):(this.range=e("<div></div>").appendTo(this.element),i="ui-slider-range ui-widget-header ui-corner-all"),this.range.addClass(i+("min"===t.range||"max"===t.range?" ui-slider-range-"+t.range:""))):(this.range&&this.range.remove(),this.range=null)},_setupEvents:function(){this._off(this.handles),this._on(this.handles,this._handleEvents),this._hoverable(this.handles),this._focusable(this.handles)},_destroy:function(){this.handles.remove(),this.range&&this.range.remove(),this.element.removeClass("ui-slider ui-slider-horizontal ui-slider-vertical ui-widget ui-widget-content ui-corner-all"),this._mouseDestroy()},_mouseCapture:function(t){var i,s,n,a,o,r,h,l,u=this,d=this.options;return d.disabled?!1:(this.elementSize={width:this.element.outerWidth(),height:this.element.outerHeight()},this.elementOffset=this.element.offset(),i={x:t.pageX,y:t.pageY},s=this._normValueFromMouse(i),n=this._valueMax()-this._valueMin()+1,this.handles.each(function(t){var i=Math.abs(s-u.values(t));(n>i||n===i&&(t===u._lastChangedValue||u.values(t)===d.min))&&(n=i,a=e(this),o=t)}),r=this._start(t,o),r===!1?!1:(this._mouseSliding=!0,this._handleIndex=o,a.addClass("ui-state-active").focus(),h=a.offset(),l=!e(t.target).parents().addBack().is(".ui-slider-handle"),this._clickOffset=l?{left:0,top:0}:{left:t.pageX-h.left-a.width()/2,top:t.pageY-h.top-a.height()/2-(parseInt(a.css("borderTopWidth"),10)||0)-(parseInt(a.css("borderBottomWidth"),10)||0)+(parseInt(a.css("marginTop"),10)||0)},this.handles.hasClass("ui-state-hover")||this._slide(t,o,s),this._animateOff=!0,!0))},_mouseStart:function(){return!0},_mouseDrag:function(e){var t={x:e.pageX,y:e.pageY},i=this._normValueFromMouse(t);return this._slide(e,this._handleIndex,i),!1},_mouseStop:function(e){return this.handles.removeClass("ui-state-active"),this._mouseSliding=!1,this._stop(e,this._handleIndex),this._change(e,this._handleIndex),this._handleIndex=null,this._clickOffset=null,this._animateOff=!1,!1},_detectOrientation:function(){this.orientation="vertical"===this.options.orientation?"vertical":"horizontal"},_normValueFromMouse:function(e){var t,i,s,n,a;return"horizontal"===this.orientation?(t=this.elementSize.width,i=e.x-this.elementOffset.left-(this._clickOffset?this._clickOffset.left:0)):(t=this.elementSize.height,i=e.y-this.elementOffset.top-(this._clickOffset?this._clickOffset.top:0)),s=i/t,s>1&&(s=1),0>s&&(s=0),"vertical"===this.orientation&&(s=1-s),n=this._valueMax()-this._valueMin(),a=this._valueMin()+s*n,this._trimAlignValue(a)},_start:function(e,t){var i={handle:this.handles[t],value:this.value()};return this.options.values&&this.options.values.length&&(i.value=this.values(t),i.values=this.values()),this._trigger("start",e,i)},_slide:function(e,t,i){var s,n,a;this.options.values&&this.options.values.length?(s=this.values(t?0:1),2===this.options.values.length&&this.options.range===!0&&(0===t&&i>s||1===t&&s>i)&&(i=s),i!==this.values(t)&&(n=this.values(),n[t]=i,a=this._trigger("slide",e,{handle:this.handles[t],value:i,values:n}),s=this.values(t?0:1),a!==!1&&this.values(t,i))):i!==this.value()&&(a=this._trigger("slide",e,{handle:this.handles[t],value:i}),a!==!1&&this.value(i))},_stop:function(e,t){var i={handle:this.handles[t],value:this.value()};this.options.values&&this.options.values.length&&(i.value=this.values(t),i.values=this.values()),this._trigger("stop",e,i)},_change:function(e,t){if(!this._keySliding&&!this._mouseSliding){var i={handle:this.handles[t],value:this.value()};this.options.values&&this.options.values.length&&(i.value=this.values(t),i.values=this.values()),this._lastChangedValue=t,this._trigger("change",e,i)}},value:function(e){return arguments.length?(this.options.value=this._trimAlignValue(e),this._refreshValue(),this._change(null,0),void 0):this._value()},values:function(t,i){var s,n,a;if(arguments.length>1)return this.options.values[t]=this._trimAlignValue(i),this._refreshValue(),this._change(null,t),void 0;if(!arguments.length)return this._values();if(!e.isArray(arguments[0]))return this.options.values&&this.options.values.length?this._values(t):this.value();for(s=this.options.values,n=arguments[0],a=0;s.length>a;a+=1)s[a]=this._trimAlignValue(n[a]),this._change(null,a);this._refreshValue()},_setOption:function(t,i){var s,n=0;switch("range"===t&&this.options.range===!0&&("min"===i?(this.options.value=this._values(0),this.options.values=null):"max"===i&&(this.options.value=this._values(this.options.values.length-1),this.options.values=null)),e.isArray(this.options.values)&&(n=this.options.values.length),"disabled"===t&&this.element.toggleClass("ui-state-disabled",!!i),this._super(t,i),t){case"orientation":this._detectOrientation(),this.element.removeClass("ui-slider-horizontal ui-slider-vertical").addClass("ui-slider-"+this.orientation),this._refreshValue(),this.handles.css("horizontal"===i?"bottom":"left","");break;case"value":this._animateOff=!0,this._refreshValue(),this._change(null,0),this._animateOff=!1;break;case"values":for(this._animateOff=!0,this._refreshValue(),s=0;n>s;s+=1)this._change(null,s);this._animateOff=!1;break;case"step":case"min":case"max":this._animateOff=!0,this._calculateNewMax(),this._refreshValue(),this._animateOff=!1;break;case"range":this._animateOff=!0,this._refresh(),this._animateOff=!1}},_value:function(){var e=this.options.value;return e=this._trimAlignValue(e)},_values:function(e){var t,i,s;if(arguments.length)return t=this.options.values[e],t=this._trimAlignValue(t);if(this.options.values&&this.options.values.length){for(i=this.options.values.slice(),s=0;i.length>s;s+=1)i[s]=this._trimAlignValue(i[s]);return i}return[]},_trimAlignValue:function(e){if(this._valueMin()>=e)return this._valueMin();if(e>=this._valueMax())return this._valueMax();var t=this.options.step>0?this.options.step:1,i=(e-this._valueMin())%t,s=e-i;return 2*Math.abs(i)>=t&&(s+=i>0?t:-t),parseFloat(s.toFixed(5))},_calculateNewMax:function(){var e=this.options.max,t=this._valueMin(),i=this.options.step,s=Math.floor(+(e-t).toFixed(this._precision())/i)*i;e=s+t,this.max=parseFloat(e.toFixed(this._precision()))},_precision:function(){var e=this._precisionOf(this.options.step);return null!==this.options.min&&(e=Math.max(e,this._precisionOf(this.options.min))),e},_precisionOf:function(e){var t=""+e,i=t.indexOf(".");return-1===i?0:t.length-i-1},_valueMin:function(){return this.options.min},_valueMax:function(){return this.max},_refreshValue:function(){var t,i,s,n,a,o=this.options.range,r=this.options,h=this,l=this._animateOff?!1:r.animate,u={};this.options.values&&this.options.values.length?this.handles.each(function(s){i=100*((h.values(s)-h._valueMin())/(h._valueMax()-h._valueMin())),u["horizontal"===h.orientation?"left":"bottom"]=i+"%",e(this).stop(1,1)[l?"animate":"css"](u,r.animate),h.options.range===!0&&("horizontal"===h.orientation?(0===s&&h.range.stop(1,1)[l?"animate":"css"]({left:i+"%"},r.animate),1===s&&h.range[l?"animate":"css"]({width:i-t+"%"},{queue:!1,duration:r.animate})):(0===s&&h.range.stop(1,1)[l?"animate":"css"]({bottom:i+"%"},r.animate),1===s&&h.range[l?"animate":"css"]({height:i-t+"%"},{queue:!1,duration:r.animate}))),t=i}):(s=this.value(),n=this._valueMin(),a=this._valueMax(),i=a!==n?100*((s-n)/(a-n)):0,u["horizontal"===this.orientation?"left":"bottom"]=i+"%",this.handle.stop(1,1)[l?"animate":"css"](u,r.animate),"min"===o&&"horizontal"===this.orientation&&this.range.stop(1,1)[l?"animate":"css"]({width:i+"%"},r.animate),"max"===o&&"horizontal"===this.orientation&&this.range[l?"animate":"css"]({width:100-i+"%"},{queue:!1,duration:r.animate}),"min"===o&&"vertical"===this.orientation&&this.range.stop(1,1)[l?"animate":"css"]({height:i+"%"},r.animate),"max"===o&&"vertical"===this.orientation&&this.range[l?"animate":"css"]({height:100-i+"%"},{queue:!1,duration:r.animate}))},_handleEvents:{keydown:function(t){var i,s,n,a,o=e(t.target).data("ui-slider-handle-index");switch(t.keyCode){case e.ui.keyCode.HOME:case e.ui.keyCode.END:case e.ui.keyCode.PAGE_UP:case e.ui.keyCode.PAGE_DOWN:case e.ui.keyCode.UP:case e.ui.keyCode.RIGHT:case e.ui.keyCode.DOWN:case e.ui.keyCode.LEFT:if(t.preventDefault(),!this._keySliding&&(this._keySliding=!0,e(t.target).addClass("ui-state-active"),i=this._start(t,o),i===!1))return}switch(a=this.options.step,s=n=this.options.values&&this.options.values.length?this.values(o):this.value(),t.keyCode){case e.ui.keyCode.HOME:n=this._valueMin();break;case e.ui.keyCode.END:n=this._valueMax();break;case e.ui.keyCode.PAGE_UP:n=this._trimAlignValue(s+(this._valueMax()-this._valueMin())/this.numPages);break;case e.ui.keyCode.PAGE_DOWN:n=this._trimAlignValue(s-(this._valueMax()-this._valueMin())/this.numPages);break;case e.ui.keyCode.UP:case e.ui.keyCode.RIGHT:if(s===this._valueMax())return;n=this._trimAlignValue(s+a);break;case e.ui.keyCode.DOWN:case e.ui.keyCode.LEFT:if(s===this._valueMin())return;n=this._trimAlignValue(s-a)}this._slide(t,o,n)},keyup:function(t){var i=e(t.target).data("ui-slider-handle-index");this._keySliding&&(this._keySliding=!1,this._stop(t,i),this._change(t,i),e(t.target).removeClass("ui-state-active"))}}}),e.widget("ui.sortable",e.ui.mouse,{version:"1.11.4",widgetEventPrefix:"sort",ready:!1,options:{appendTo:"parent",axis:!1,connectWith:!1,containment:!1,cursor:"auto",cursorAt:!1,dropOnEmpty:!0,forcePlaceholderSize:!1,forceHelperSize:!1,grid:!1,handle:!1,helper:"original",items:"> *",opacity:!1,placeholder:!1,revert:!1,scroll:!0,scrollSensitivity:20,scrollSpeed:20,scope:"default",tolerance:"intersect",zIndex:1e3,activate:null,beforeStop:null,change:null,deactivate:null,out:null,over:null,receive:null,remove:null,sort:null,start:null,stop:null,update:null},_isOverAxis:function(e,t,i){return e>=t&&t+i>e},_isFloating:function(e){return/left|right/.test(e.css("float"))||/inline|table-cell/.test(e.css("display"))},_create:function(){this.containerCache={},this.element.addClass("ui-sortable"),this.refresh(),this.offset=this.element.offset(),this._mouseInit(),this._setHandleClassName(),this.ready=!0},_setOption:function(e,t){this._super(e,t),"handle"===e&&this._setHandleClassName()},_setHandleClassName:function(){this.element.find(".ui-sortable-handle").removeClass("ui-sortable-handle"),e.each(this.items,function(){(this.instance.options.handle?this.item.find(this.instance.options.handle):this.item).addClass("ui-sortable-handle")})},_destroy:function(){this.element.removeClass("ui-sortable ui-sortable-disabled").find(".ui-sortable-handle").removeClass("ui-sortable-handle"),this._mouseDestroy();for(var e=this.items.length-1;e>=0;e--)this.items[e].item.removeData(this.widgetName+"-item");return this},_mouseCapture:function(t,i){var s=null,n=!1,a=this;return this.reverting?!1:this.options.disabled||"static"===this.options.type?!1:(this._refreshItems(t),e(t.target).parents().each(function(){return e.data(this,a.widgetName+"-item")===a?(s=e(this),!1):void 0}),e.data(t.target,a.widgetName+"-item")===a&&(s=e(t.target)),s?!this.options.handle||i||(e(this.options.handle,s).find("*").addBack().each(function(){this===t.target&&(n=!0)}),n)?(this.currentItem=s,this._removeCurrentsFromItems(),!0):!1:!1)},_mouseStart:function(t,i,s){var n,a,o=this.options;if(this.currentContainer=this,this.refreshPositions(),this.helper=this._createHelper(t),this._cacheHelperProportions(),this._cacheMargins(),this.scrollParent=this.helper.scrollParent(),this.offset=this.currentItem.offset(),this.offset={top:this.offset.top-this.margins.top,left:this.offset.left-this.margins.left},e.extend(this.offset,{click:{left:t.pageX-this.offset.left,top:t.pageY-this.offset.top},parent:this._getParentOffset(),relative:this._getRelativeOffset()}),this.helper.css("position","absolute"),this.cssPosition=this.helper.css("position"),this.originalPosition=this._generatePosition(t),this.originalPageX=t.pageX,this.originalPageY=t.pageY,o.cursorAt&&this._adjustOffsetFromHelper(o.cursorAt),this.domPosition={prev:this.currentItem.prev()[0],parent:this.currentItem.parent()[0]},this.helper[0]!==this.currentItem[0]&&this.currentItem.hide(),this._createPlaceholder(),o.containment&&this._setContainment(),o.cursor&&"auto"!==o.cursor&&(a=this.document.find("body"),this.storedCursor=a.css("cursor"),a.css("cursor",o.cursor),this.storedStylesheet=e("<style>*{ cursor: "+o.cursor+" !important; }</style>").appendTo(a)),o.opacity&&(this.helper.css("opacity")&&(this._storedOpacity=this.helper.css("opacity")),this.helper.css("opacity",o.opacity)),o.zIndex&&(this.helper.css("zIndex")&&(this._storedZIndex=this.helper.css("zIndex")),this.helper.css("zIndex",o.zIndex)),this.scrollParent[0]!==this.document[0]&&"HTML"!==this.scrollParent[0].tagName&&(this.overflowOffset=this.scrollParent.offset()),this._trigger("start",t,this._uiHash()),this._preserveHelperProportions||this._cacheHelperProportions(),!s)for(n=this.containers.length-1;n>=0;n--)this.containers[n]._trigger("activate",t,this._uiHash(this));
return e.ui.ddmanager&&(e.ui.ddmanager.current=this),e.ui.ddmanager&&!o.dropBehaviour&&e.ui.ddmanager.prepareOffsets(this,t),this.dragging=!0,this.helper.addClass("ui-sortable-helper"),this._mouseDrag(t),!0},_mouseDrag:function(t){var i,s,n,a,o=this.options,r=!1;for(this.position=this._generatePosition(t),this.positionAbs=this._convertPositionTo("absolute"),this.lastPositionAbs||(this.lastPositionAbs=this.positionAbs),this.options.scroll&&(this.scrollParent[0]!==this.document[0]&&"HTML"!==this.scrollParent[0].tagName?(this.overflowOffset.top+this.scrollParent[0].offsetHeight-t.pageY<o.scrollSensitivity?this.scrollParent[0].scrollTop=r=this.scrollParent[0].scrollTop+o.scrollSpeed:t.pageY-this.overflowOffset.top<o.scrollSensitivity&&(this.scrollParent[0].scrollTop=r=this.scrollParent[0].scrollTop-o.scrollSpeed),this.overflowOffset.left+this.scrollParent[0].offsetWidth-t.pageX<o.scrollSensitivity?this.scrollParent[0].scrollLeft=r=this.scrollParent[0].scrollLeft+o.scrollSpeed:t.pageX-this.overflowOffset.left<o.scrollSensitivity&&(this.scrollParent[0].scrollLeft=r=this.scrollParent[0].scrollLeft-o.scrollSpeed)):(t.pageY-this.document.scrollTop()<o.scrollSensitivity?r=this.document.scrollTop(this.document.scrollTop()-o.scrollSpeed):this.window.height()-(t.pageY-this.document.scrollTop())<o.scrollSensitivity&&(r=this.document.scrollTop(this.document.scrollTop()+o.scrollSpeed)),t.pageX-this.document.scrollLeft()<o.scrollSensitivity?r=this.document.scrollLeft(this.document.scrollLeft()-o.scrollSpeed):this.window.width()-(t.pageX-this.document.scrollLeft())<o.scrollSensitivity&&(r=this.document.scrollLeft(this.document.scrollLeft()+o.scrollSpeed))),r!==!1&&e.ui.ddmanager&&!o.dropBehaviour&&e.ui.ddmanager.prepareOffsets(this,t)),this.positionAbs=this._convertPositionTo("absolute"),this.options.axis&&"y"===this.options.axis||(this.helper[0].style.left=this.position.left+"px"),this.options.axis&&"x"===this.options.axis||(this.helper[0].style.top=this.position.top+"px"),i=this.items.length-1;i>=0;i--)if(s=this.items[i],n=s.item[0],a=this._intersectsWithPointer(s),a&&s.instance===this.currentContainer&&n!==this.currentItem[0]&&this.placeholder[1===a?"next":"prev"]()[0]!==n&&!e.contains(this.placeholder[0],n)&&("semi-dynamic"===this.options.type?!e.contains(this.element[0],n):!0)){if(this.direction=1===a?"down":"up","pointer"!==this.options.tolerance&&!this._intersectsWithSides(s))break;this._rearrange(t,s),this._trigger("change",t,this._uiHash());break}return this._contactContainers(t),e.ui.ddmanager&&e.ui.ddmanager.drag(this,t),this._trigger("sort",t,this._uiHash()),this.lastPositionAbs=this.positionAbs,!1},_mouseStop:function(t,i){if(t){if(e.ui.ddmanager&&!this.options.dropBehaviour&&e.ui.ddmanager.drop(this,t),this.options.revert){var s=this,n=this.placeholder.offset(),a=this.options.axis,o={};a&&"x"!==a||(o.left=n.left-this.offset.parent.left-this.margins.left+(this.offsetParent[0]===this.document[0].body?0:this.offsetParent[0].scrollLeft)),a&&"y"!==a||(o.top=n.top-this.offset.parent.top-this.margins.top+(this.offsetParent[0]===this.document[0].body?0:this.offsetParent[0].scrollTop)),this.reverting=!0,e(this.helper).animate(o,parseInt(this.options.revert,10)||500,function(){s._clear(t)})}else this._clear(t,i);return!1}},cancel:function(){if(this.dragging){this._mouseUp({target:null}),"original"===this.options.helper?this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper"):this.currentItem.show();for(var t=this.containers.length-1;t>=0;t--)this.containers[t]._trigger("deactivate",null,this._uiHash(this)),this.containers[t].containerCache.over&&(this.containers[t]._trigger("out",null,this._uiHash(this)),this.containers[t].containerCache.over=0)}return this.placeholder&&(this.placeholder[0].parentNode&&this.placeholder[0].parentNode.removeChild(this.placeholder[0]),"original"!==this.options.helper&&this.helper&&this.helper[0].parentNode&&this.helper.remove(),e.extend(this,{helper:null,dragging:!1,reverting:!1,_noFinalSort:null}),this.domPosition.prev?e(this.domPosition.prev).after(this.currentItem):e(this.domPosition.parent).prepend(this.currentItem)),this},serialize:function(t){var i=this._getItemsAsjQuery(t&&t.connected),s=[];return t=t||{},e(i).each(function(){var i=(e(t.item||this).attr(t.attribute||"id")||"").match(t.expression||/(.+)[\-=_](.+)/);i&&s.push((t.key||i[1]+"[]")+"="+(t.key&&t.expression?i[1]:i[2]))}),!s.length&&t.key&&s.push(t.key+"="),s.join("&")},toArray:function(t){var i=this._getItemsAsjQuery(t&&t.connected),s=[];return t=t||{},i.each(function(){s.push(e(t.item||this).attr(t.attribute||"id")||"")}),s},_intersectsWith:function(e){var t=this.positionAbs.left,i=t+this.helperProportions.width,s=this.positionAbs.top,n=s+this.helperProportions.height,a=e.left,o=a+e.width,r=e.top,h=r+e.height,l=this.offset.click.top,u=this.offset.click.left,d="x"===this.options.axis||s+l>r&&h>s+l,c="y"===this.options.axis||t+u>a&&o>t+u,p=d&&c;return"pointer"===this.options.tolerance||this.options.forcePointerForContainers||"pointer"!==this.options.tolerance&&this.helperProportions[this.floating?"width":"height"]>e[this.floating?"width":"height"]?p:t+this.helperProportions.width/2>a&&o>i-this.helperProportions.width/2&&s+this.helperProportions.height/2>r&&h>n-this.helperProportions.height/2},_intersectsWithPointer:function(e){var t="x"===this.options.axis||this._isOverAxis(this.positionAbs.top+this.offset.click.top,e.top,e.height),i="y"===this.options.axis||this._isOverAxis(this.positionAbs.left+this.offset.click.left,e.left,e.width),s=t&&i,n=this._getDragVerticalDirection(),a=this._getDragHorizontalDirection();return s?this.floating?a&&"right"===a||"down"===n?2:1:n&&("down"===n?2:1):!1},_intersectsWithSides:function(e){var t=this._isOverAxis(this.positionAbs.top+this.offset.click.top,e.top+e.height/2,e.height),i=this._isOverAxis(this.positionAbs.left+this.offset.click.left,e.left+e.width/2,e.width),s=this._getDragVerticalDirection(),n=this._getDragHorizontalDirection();return this.floating&&n?"right"===n&&i||"left"===n&&!i:s&&("down"===s&&t||"up"===s&&!t)},_getDragVerticalDirection:function(){var e=this.positionAbs.top-this.lastPositionAbs.top;return 0!==e&&(e>0?"down":"up")},_getDragHorizontalDirection:function(){var e=this.positionAbs.left-this.lastPositionAbs.left;return 0!==e&&(e>0?"right":"left")},refresh:function(e){return this._refreshItems(e),this._setHandleClassName(),this.refreshPositions(),this},_connectWith:function(){var e=this.options;return e.connectWith.constructor===String?[e.connectWith]:e.connectWith},_getItemsAsjQuery:function(t){function i(){r.push(this)}var s,n,a,o,r=[],h=[],l=this._connectWith();if(l&&t)for(s=l.length-1;s>=0;s--)for(a=e(l[s],this.document[0]),n=a.length-1;n>=0;n--)o=e.data(a[n],this.widgetFullName),o&&o!==this&&!o.options.disabled&&h.push([e.isFunction(o.options.items)?o.options.items.call(o.element):e(o.options.items,o.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"),o]);for(h.push([e.isFunction(this.options.items)?this.options.items.call(this.element,null,{options:this.options,item:this.currentItem}):e(this.options.items,this.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"),this]),s=h.length-1;s>=0;s--)h[s][0].each(i);return e(r)},_removeCurrentsFromItems:function(){var t=this.currentItem.find(":data("+this.widgetName+"-item)");this.items=e.grep(this.items,function(e){for(var i=0;t.length>i;i++)if(t[i]===e.item[0])return!1;return!0})},_refreshItems:function(t){this.items=[],this.containers=[this];var i,s,n,a,o,r,h,l,u=this.items,d=[[e.isFunction(this.options.items)?this.options.items.call(this.element[0],t,{item:this.currentItem}):e(this.options.items,this.element),this]],c=this._connectWith();if(c&&this.ready)for(i=c.length-1;i>=0;i--)for(n=e(c[i],this.document[0]),s=n.length-1;s>=0;s--)a=e.data(n[s],this.widgetFullName),a&&a!==this&&!a.options.disabled&&(d.push([e.isFunction(a.options.items)?a.options.items.call(a.element[0],t,{item:this.currentItem}):e(a.options.items,a.element),a]),this.containers.push(a));for(i=d.length-1;i>=0;i--)for(o=d[i][1],r=d[i][0],s=0,l=r.length;l>s;s++)h=e(r[s]),h.data(this.widgetName+"-item",o),u.push({item:h,instance:o,width:0,height:0,left:0,top:0})},refreshPositions:function(t){this.floating=this.items.length?"x"===this.options.axis||this._isFloating(this.items[0].item):!1,this.offsetParent&&this.helper&&(this.offset.parent=this._getParentOffset());var i,s,n,a;for(i=this.items.length-1;i>=0;i--)s=this.items[i],s.instance!==this.currentContainer&&this.currentContainer&&s.item[0]!==this.currentItem[0]||(n=this.options.toleranceElement?e(this.options.toleranceElement,s.item):s.item,t||(s.width=n.outerWidth(),s.height=n.outerHeight()),a=n.offset(),s.left=a.left,s.top=a.top);if(this.options.custom&&this.options.custom.refreshContainers)this.options.custom.refreshContainers.call(this);else for(i=this.containers.length-1;i>=0;i--)a=this.containers[i].element.offset(),this.containers[i].containerCache.left=a.left,this.containers[i].containerCache.top=a.top,this.containers[i].containerCache.width=this.containers[i].element.outerWidth(),this.containers[i].containerCache.height=this.containers[i].element.outerHeight();return this},_createPlaceholder:function(t){t=t||this;var i,s=t.options;s.placeholder&&s.placeholder.constructor!==String||(i=s.placeholder,s.placeholder={element:function(){var s=t.currentItem[0].nodeName.toLowerCase(),n=e("<"+s+">",t.document[0]).addClass(i||t.currentItem[0].className+" ui-sortable-placeholder").removeClass("ui-sortable-helper");return"tbody"===s?t._createTrPlaceholder(t.currentItem.find("tr").eq(0),e("<tr>",t.document[0]).appendTo(n)):"tr"===s?t._createTrPlaceholder(t.currentItem,n):"img"===s&&n.attr("src",t.currentItem.attr("src")),i||n.css("visibility","hidden"),n},update:function(e,n){(!i||s.forcePlaceholderSize)&&(n.height()||n.height(t.currentItem.innerHeight()-parseInt(t.currentItem.css("paddingTop")||0,10)-parseInt(t.currentItem.css("paddingBottom")||0,10)),n.width()||n.width(t.currentItem.innerWidth()-parseInt(t.currentItem.css("paddingLeft")||0,10)-parseInt(t.currentItem.css("paddingRight")||0,10)))}}),t.placeholder=e(s.placeholder.element.call(t.element,t.currentItem)),t.currentItem.after(t.placeholder),s.placeholder.update(t,t.placeholder)},_createTrPlaceholder:function(t,i){var s=this;t.children().each(function(){e("<td>&#160;</td>",s.document[0]).attr("colspan",e(this).attr("colspan")||1).appendTo(i)})},_contactContainers:function(t){var i,s,n,a,o,r,h,l,u,d,c=null,p=null;for(i=this.containers.length-1;i>=0;i--)if(!e.contains(this.currentItem[0],this.containers[i].element[0]))if(this._intersectsWith(this.containers[i].containerCache)){if(c&&e.contains(this.containers[i].element[0],c.element[0]))continue;c=this.containers[i],p=i}else this.containers[i].containerCache.over&&(this.containers[i]._trigger("out",t,this._uiHash(this)),this.containers[i].containerCache.over=0);if(c)if(1===this.containers.length)this.containers[p].containerCache.over||(this.containers[p]._trigger("over",t,this._uiHash(this)),this.containers[p].containerCache.over=1);else{for(n=1e4,a=null,u=c.floating||this._isFloating(this.currentItem),o=u?"left":"top",r=u?"width":"height",d=u?"clientX":"clientY",s=this.items.length-1;s>=0;s--)e.contains(this.containers[p].element[0],this.items[s].item[0])&&this.items[s].item[0]!==this.currentItem[0]&&(h=this.items[s].item.offset()[o],l=!1,t[d]-h>this.items[s][r]/2&&(l=!0),n>Math.abs(t[d]-h)&&(n=Math.abs(t[d]-h),a=this.items[s],this.direction=l?"up":"down"));if(!a&&!this.options.dropOnEmpty)return;if(this.currentContainer===this.containers[p])return this.currentContainer.containerCache.over||(this.containers[p]._trigger("over",t,this._uiHash()),this.currentContainer.containerCache.over=1),void 0;a?this._rearrange(t,a,null,!0):this._rearrange(t,null,this.containers[p].element,!0),this._trigger("change",t,this._uiHash()),this.containers[p]._trigger("change",t,this._uiHash(this)),this.currentContainer=this.containers[p],this.options.placeholder.update(this.currentContainer,this.placeholder),this.containers[p]._trigger("over",t,this._uiHash(this)),this.containers[p].containerCache.over=1}},_createHelper:function(t){var i=this.options,s=e.isFunction(i.helper)?e(i.helper.apply(this.element[0],[t,this.currentItem])):"clone"===i.helper?this.currentItem.clone():this.currentItem;return s.parents("body").length||e("parent"!==i.appendTo?i.appendTo:this.currentItem[0].parentNode)[0].appendChild(s[0]),s[0]===this.currentItem[0]&&(this._storedCSS={width:this.currentItem[0].style.width,height:this.currentItem[0].style.height,position:this.currentItem.css("position"),top:this.currentItem.css("top"),left:this.currentItem.css("left")}),(!s[0].style.width||i.forceHelperSize)&&s.width(this.currentItem.width()),(!s[0].style.height||i.forceHelperSize)&&s.height(this.currentItem.height()),s},_adjustOffsetFromHelper:function(t){"string"==typeof t&&(t=t.split(" ")),e.isArray(t)&&(t={left:+t[0],top:+t[1]||0}),"left"in t&&(this.offset.click.left=t.left+this.margins.left),"right"in t&&(this.offset.click.left=this.helperProportions.width-t.right+this.margins.left),"top"in t&&(this.offset.click.top=t.top+this.margins.top),"bottom"in t&&(this.offset.click.top=this.helperProportions.height-t.bottom+this.margins.top)},_getParentOffset:function(){this.offsetParent=this.helper.offsetParent();var t=this.offsetParent.offset();return"absolute"===this.cssPosition&&this.scrollParent[0]!==this.document[0]&&e.contains(this.scrollParent[0],this.offsetParent[0])&&(t.left+=this.scrollParent.scrollLeft(),t.top+=this.scrollParent.scrollTop()),(this.offsetParent[0]===this.document[0].body||this.offsetParent[0].tagName&&"html"===this.offsetParent[0].tagName.toLowerCase()&&e.ui.ie)&&(t={top:0,left:0}),{top:t.top+(parseInt(this.offsetParent.css("borderTopWidth"),10)||0),left:t.left+(parseInt(this.offsetParent.css("borderLeftWidth"),10)||0)}},_getRelativeOffset:function(){if("relative"===this.cssPosition){var e=this.currentItem.position();return{top:e.top-(parseInt(this.helper.css("top"),10)||0)+this.scrollParent.scrollTop(),left:e.left-(parseInt(this.helper.css("left"),10)||0)+this.scrollParent.scrollLeft()}}return{top:0,left:0}},_cacheMargins:function(){this.margins={left:parseInt(this.currentItem.css("marginLeft"),10)||0,top:parseInt(this.currentItem.css("marginTop"),10)||0}},_cacheHelperProportions:function(){this.helperProportions={width:this.helper.outerWidth(),height:this.helper.outerHeight()}},_setContainment:function(){var t,i,s,n=this.options;"parent"===n.containment&&(n.containment=this.helper[0].parentNode),("document"===n.containment||"window"===n.containment)&&(this.containment=[0-this.offset.relative.left-this.offset.parent.left,0-this.offset.relative.top-this.offset.parent.top,"document"===n.containment?this.document.width():this.window.width()-this.helperProportions.width-this.margins.left,("document"===n.containment?this.document.width():this.window.height()||this.document[0].body.parentNode.scrollHeight)-this.helperProportions.height-this.margins.top]),/^(document|window|parent)$/.test(n.containment)||(t=e(n.containment)[0],i=e(n.containment).offset(),s="hidden"!==e(t).css("overflow"),this.containment=[i.left+(parseInt(e(t).css("borderLeftWidth"),10)||0)+(parseInt(e(t).css("paddingLeft"),10)||0)-this.margins.left,i.top+(parseInt(e(t).css("borderTopWidth"),10)||0)+(parseInt(e(t).css("paddingTop"),10)||0)-this.margins.top,i.left+(s?Math.max(t.scrollWidth,t.offsetWidth):t.offsetWidth)-(parseInt(e(t).css("borderLeftWidth"),10)||0)-(parseInt(e(t).css("paddingRight"),10)||0)-this.helperProportions.width-this.margins.left,i.top+(s?Math.max(t.scrollHeight,t.offsetHeight):t.offsetHeight)-(parseInt(e(t).css("borderTopWidth"),10)||0)-(parseInt(e(t).css("paddingBottom"),10)||0)-this.helperProportions.height-this.margins.top])},_convertPositionTo:function(t,i){i||(i=this.position);var s="absolute"===t?1:-1,n="absolute"!==this.cssPosition||this.scrollParent[0]!==this.document[0]&&e.contains(this.scrollParent[0],this.offsetParent[0])?this.scrollParent:this.offsetParent,a=/(html|body)/i.test(n[0].tagName);return{top:i.top+this.offset.relative.top*s+this.offset.parent.top*s-("fixed"===this.cssPosition?-this.scrollParent.scrollTop():a?0:n.scrollTop())*s,left:i.left+this.offset.relative.left*s+this.offset.parent.left*s-("fixed"===this.cssPosition?-this.scrollParent.scrollLeft():a?0:n.scrollLeft())*s}},_generatePosition:function(t){var i,s,n=this.options,a=t.pageX,o=t.pageY,r="absolute"!==this.cssPosition||this.scrollParent[0]!==this.document[0]&&e.contains(this.scrollParent[0],this.offsetParent[0])?this.scrollParent:this.offsetParent,h=/(html|body)/i.test(r[0].tagName);return"relative"!==this.cssPosition||this.scrollParent[0]!==this.document[0]&&this.scrollParent[0]!==this.offsetParent[0]||(this.offset.relative=this._getRelativeOffset()),this.originalPosition&&(this.containment&&(t.pageX-this.offset.click.left<this.containment[0]&&(a=this.containment[0]+this.offset.click.left),t.pageY-this.offset.click.top<this.containment[1]&&(o=this.containment[1]+this.offset.click.top),t.pageX-this.offset.click.left>this.containment[2]&&(a=this.containment[2]+this.offset.click.left),t.pageY-this.offset.click.top>this.containment[3]&&(o=this.containment[3]+this.offset.click.top)),n.grid&&(i=this.originalPageY+Math.round((o-this.originalPageY)/n.grid[1])*n.grid[1],o=this.containment?i-this.offset.click.top>=this.containment[1]&&i-this.offset.click.top<=this.containment[3]?i:i-this.offset.click.top>=this.containment[1]?i-n.grid[1]:i+n.grid[1]:i,s=this.originalPageX+Math.round((a-this.originalPageX)/n.grid[0])*n.grid[0],a=this.containment?s-this.offset.click.left>=this.containment[0]&&s-this.offset.click.left<=this.containment[2]?s:s-this.offset.click.left>=this.containment[0]?s-n.grid[0]:s+n.grid[0]:s)),{top:o-this.offset.click.top-this.offset.relative.top-this.offset.parent.top+("fixed"===this.cssPosition?-this.scrollParent.scrollTop():h?0:r.scrollTop()),left:a-this.offset.click.left-this.offset.relative.left-this.offset.parent.left+("fixed"===this.cssPosition?-this.scrollParent.scrollLeft():h?0:r.scrollLeft())}},_rearrange:function(e,t,i,s){i?i[0].appendChild(this.placeholder[0]):t.item[0].parentNode.insertBefore(this.placeholder[0],"down"===this.direction?t.item[0]:t.item[0].nextSibling),this.counter=this.counter?++this.counter:1;var n=this.counter;this._delay(function(){n===this.counter&&this.refreshPositions(!s)})},_clear:function(e,t){function i(e,t,i){return function(s){i._trigger(e,s,t._uiHash(t))}}this.reverting=!1;var s,n=[];if(!this._noFinalSort&&this.currentItem.parent().length&&this.placeholder.before(this.currentItem),this._noFinalSort=null,this.helper[0]===this.currentItem[0]){for(s in this._storedCSS)("auto"===this._storedCSS[s]||"static"===this._storedCSS[s])&&(this._storedCSS[s]="");this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper")}else this.currentItem.show();for(this.fromOutside&&!t&&n.push(function(e){this._trigger("receive",e,this._uiHash(this.fromOutside))}),!this.fromOutside&&this.domPosition.prev===this.currentItem.prev().not(".ui-sortable-helper")[0]&&this.domPosition.parent===this.currentItem.parent()[0]||t||n.push(function(e){this._trigger("update",e,this._uiHash())}),this!==this.currentContainer&&(t||(n.push(function(e){this._trigger("remove",e,this._uiHash())}),n.push(function(e){return function(t){e._trigger("receive",t,this._uiHash(this))}}.call(this,this.currentContainer)),n.push(function(e){return function(t){e._trigger("update",t,this._uiHash(this))}}.call(this,this.currentContainer)))),s=this.containers.length-1;s>=0;s--)t||n.push(i("deactivate",this,this.containers[s])),this.containers[s].containerCache.over&&(n.push(i("out",this,this.containers[s])),this.containers[s].containerCache.over=0);if(this.storedCursor&&(this.document.find("body").css("cursor",this.storedCursor),this.storedStylesheet.remove()),this._storedOpacity&&this.helper.css("opacity",this._storedOpacity),this._storedZIndex&&this.helper.css("zIndex","auto"===this._storedZIndex?"":this._storedZIndex),this.dragging=!1,t||this._trigger("beforeStop",e,this._uiHash()),this.placeholder[0].parentNode.removeChild(this.placeholder[0]),this.cancelHelperRemoval||(this.helper[0]!==this.currentItem[0]&&this.helper.remove(),this.helper=null),!t){for(s=0;n.length>s;s++)n[s].call(this,e);this._trigger("stop",e,this._uiHash())}return this.fromOutside=!1,!this.cancelHelperRemoval},_trigger:function(){e.Widget.prototype._trigger.apply(this,arguments)===!1&&this.cancel()},_uiHash:function(t){var i=t||this;return{helper:i.helper,placeholder:i.placeholder||e([]),position:i.position,originalPosition:i.originalPosition,offset:i.positionAbs,item:i.currentItem,sender:t?t.element:null}}}),e.widget("ui.spinner",{version:"1.11.4",defaultElement:"<input>",widgetEventPrefix:"spin",options:{culture:null,icons:{down:"ui-icon-triangle-1-s",up:"ui-icon-triangle-1-n"},incremental:!0,max:null,min:null,numberFormat:null,page:10,step:1,change:null,spin:null,start:null,stop:null},_create:function(){this._setOption("max",this.options.max),this._setOption("min",this.options.min),this._setOption("step",this.options.step),""!==this.value()&&this._value(this.element.val(),!0),this._draw(),this._on(this._events),this._refresh(),this._on(this.window,{beforeunload:function(){this.element.removeAttr("autocomplete")}})},_getCreateOptions:function(){var t={},i=this.element;return e.each(["min","max","step"],function(e,s){var n=i.attr(s);void 0!==n&&n.length&&(t[s]=n)}),t},_events:{keydown:function(e){this._start(e)&&this._keydown(e)&&e.preventDefault()},keyup:"_stop",focus:function(){this.previous=this.element.val()},blur:function(e){return this.cancelBlur?(delete this.cancelBlur,void 0):(this._stop(),this._refresh(),this.previous!==this.element.val()&&this._trigger("change",e),void 0)},mousewheel:function(e,t){if(t){if(!this.spinning&&!this._start(e))return!1;this._spin((t>0?1:-1)*this.options.step,e),clearTimeout(this.mousewheelTimer),this.mousewheelTimer=this._delay(function(){this.spinning&&this._stop(e)},100),e.preventDefault()}},"mousedown .ui-spinner-button":function(t){function i(){var e=this.element[0]===this.document[0].activeElement;e||(this.element.focus(),this.previous=s,this._delay(function(){this.previous=s}))}var s;s=this.element[0]===this.document[0].activeElement?this.previous:this.element.val(),t.preventDefault(),i.call(this),this.cancelBlur=!0,this._delay(function(){delete this.cancelBlur,i.call(this)}),this._start(t)!==!1&&this._repeat(null,e(t.currentTarget).hasClass("ui-spinner-up")?1:-1,t)},"mouseup .ui-spinner-button":"_stop","mouseenter .ui-spinner-button":function(t){return e(t.currentTarget).hasClass("ui-state-active")?this._start(t)===!1?!1:(this._repeat(null,e(t.currentTarget).hasClass("ui-spinner-up")?1:-1,t),void 0):void 0},"mouseleave .ui-spinner-button":"_stop"},_draw:function(){var e=this.uiSpinner=this.element.addClass("ui-spinner-input").attr("autocomplete","off").wrap(this._uiSpinnerHtml()).parent().append(this._buttonHtml());this.element.attr("role","spinbutton"),this.buttons=e.find(".ui-spinner-button").attr("tabIndex",-1).button().removeClass("ui-corner-all"),this.buttons.height()>Math.ceil(.5*e.height())&&e.height()>0&&e.height(e.height()),this.options.disabled&&this.disable()},_keydown:function(t){var i=this.options,s=e.ui.keyCode;switch(t.keyCode){case s.UP:return this._repeat(null,1,t),!0;case s.DOWN:return this._repeat(null,-1,t),!0;case s.PAGE_UP:return this._repeat(null,i.page,t),!0;case s.PAGE_DOWN:return this._repeat(null,-i.page,t),!0}return!1},_uiSpinnerHtml:function(){return"<span class='ui-spinner ui-widget ui-widget-content ui-corner-all'></span>"},_buttonHtml:function(){return"<a class='ui-spinner-button ui-spinner-up ui-corner-tr'><span class='ui-icon "+this.options.icons.up+"'>&#9650;</span>"+"</a>"+"<a class='ui-spinner-button ui-spinner-down ui-corner-br'>"+"<span class='ui-icon "+this.options.icons.down+"'>&#9660;</span>"+"</a>"},_start:function(e){return this.spinning||this._trigger("start",e)!==!1?(this.counter||(this.counter=1),this.spinning=!0,!0):!1},_repeat:function(e,t,i){e=e||500,clearTimeout(this.timer),this.timer=this._delay(function(){this._repeat(40,t,i)},e),this._spin(t*this.options.step,i)},_spin:function(e,t){var i=this.value()||0;this.counter||(this.counter=1),i=this._adjustValue(i+e*this._increment(this.counter)),this.spinning&&this._trigger("spin",t,{value:i})===!1||(this._value(i),this.counter++)},_increment:function(t){var i=this.options.incremental;return i?e.isFunction(i)?i(t):Math.floor(t*t*t/5e4-t*t/500+17*t/200+1):1},_precision:function(){var e=this._precisionOf(this.options.step);return null!==this.options.min&&(e=Math.max(e,this._precisionOf(this.options.min))),e},_precisionOf:function(e){var t=""+e,i=t.indexOf(".");return-1===i?0:t.length-i-1},_adjustValue:function(e){var t,i,s=this.options;return t=null!==s.min?s.min:0,i=e-t,i=Math.round(i/s.step)*s.step,e=t+i,e=parseFloat(e.toFixed(this._precision())),null!==s.max&&e>s.max?s.max:null!==s.min&&s.min>e?s.min:e},_stop:function(e){this.spinning&&(clearTimeout(this.timer),clearTimeout(this.mousewheelTimer),this.counter=0,this.spinning=!1,this._trigger("stop",e))},_setOption:function(e,t){if("culture"===e||"numberFormat"===e){var i=this._parse(this.element.val());return this.options[e]=t,this.element.val(this._format(i)),void 0}("max"===e||"min"===e||"step"===e)&&"string"==typeof t&&(t=this._parse(t)),"icons"===e&&(this.buttons.first().find(".ui-icon").removeClass(this.options.icons.up).addClass(t.up),this.buttons.last().find(".ui-icon").removeClass(this.options.icons.down).addClass(t.down)),this._super(e,t),"disabled"===e&&(this.widget().toggleClass("ui-state-disabled",!!t),this.element.prop("disabled",!!t),this.buttons.button(t?"disable":"enable"))},_setOptions:h(function(e){this._super(e)}),_parse:function(e){return"string"==typeof e&&""!==e&&(e=window.Globalize&&this.options.numberFormat?Globalize.parseFloat(e,10,this.options.culture):+e),""===e||isNaN(e)?null:e},_format:function(e){return""===e?"":window.Globalize&&this.options.numberFormat?Globalize.format(e,this.options.numberFormat,this.options.culture):e},_refresh:function(){this.element.attr({"aria-valuemin":this.options.min,"aria-valuemax":this.options.max,"aria-valuenow":this._parse(this.element.val())})},isValid:function(){var e=this.value();return null===e?!1:e===this._adjustValue(e)},_value:function(e,t){var i;""!==e&&(i=this._parse(e),null!==i&&(t||(i=this._adjustValue(i)),e=this._format(i))),this.element.val(e),this._refresh()},_destroy:function(){this.element.removeClass("ui-spinner-input").prop("disabled",!1).removeAttr("autocomplete").removeAttr("role").removeAttr("aria-valuemin").removeAttr("aria-valuemax").removeAttr("aria-valuenow"),this.uiSpinner.replaceWith(this.element)},stepUp:h(function(e){this._stepUp(e)}),_stepUp:function(e){this._start()&&(this._spin((e||1)*this.options.step),this._stop())},stepDown:h(function(e){this._stepDown(e)}),_stepDown:function(e){this._start()&&(this._spin((e||1)*-this.options.step),this._stop())},pageUp:h(function(e){this._stepUp((e||1)*this.options.page)}),pageDown:h(function(e){this._stepDown((e||1)*this.options.page)}),value:function(e){return arguments.length?(h(this._value).call(this,e),void 0):this._parse(this.element.val())},widget:function(){return this.uiSpinner}}),e.widget("ui.tabs",{version:"1.11.4",delay:300,options:{active:null,collapsible:!1,event:"click",heightStyle:"content",hide:null,show:null,activate:null,beforeActivate:null,beforeLoad:null,load:null},_isLocal:function(){var e=/#.*$/;return function(t){var i,s;t=t.cloneNode(!1),i=t.href.replace(e,""),s=location.href.replace(e,"");try{i=decodeURIComponent(i)}catch(n){}try{s=decodeURIComponent(s)}catch(n){}return t.hash.length>1&&i===s}}(),_create:function(){var t=this,i=this.options;this.running=!1,this.element.addClass("ui-tabs ui-widget ui-widget-content ui-corner-all").toggleClass("ui-tabs-collapsible",i.collapsible),this._processTabs(),i.active=this._initialActive(),e.isArray(i.disabled)&&(i.disabled=e.unique(i.disabled.concat(e.map(this.tabs.filter(".ui-state-disabled"),function(e){return t.tabs.index(e)}))).sort()),this.active=this.options.active!==!1&&this.anchors.length?this._findActive(i.active):e(),this._refresh(),this.active.length&&this.load(i.active)},_initialActive:function(){var t=this.options.active,i=this.options.collapsible,s=location.hash.substring(1);return null===t&&(s&&this.tabs.each(function(i,n){return e(n).attr("aria-controls")===s?(t=i,!1):void 0}),null===t&&(t=this.tabs.index(this.tabs.filter(".ui-tabs-active"))),(null===t||-1===t)&&(t=this.tabs.length?0:!1)),t!==!1&&(t=this.tabs.index(this.tabs.eq(t)),-1===t&&(t=i?!1:0)),!i&&t===!1&&this.anchors.length&&(t=0),t},_getCreateEventData:function(){return{tab:this.active,panel:this.active.length?this._getPanelForTab(this.active):e()}},_tabKeydown:function(t){var i=e(this.document[0].activeElement).closest("li"),s=this.tabs.index(i),n=!0;if(!this._handlePageNav(t)){switch(t.keyCode){case e.ui.keyCode.RIGHT:case e.ui.keyCode.DOWN:s++;break;case e.ui.keyCode.UP:case e.ui.keyCode.LEFT:n=!1,s--;break;case e.ui.keyCode.END:s=this.anchors.length-1;break;case e.ui.keyCode.HOME:s=0;break;case e.ui.keyCode.SPACE:return t.preventDefault(),clearTimeout(this.activating),this._activate(s),void 0;case e.ui.keyCode.ENTER:return t.preventDefault(),clearTimeout(this.activating),this._activate(s===this.options.active?!1:s),void 0;default:return}t.preventDefault(),clearTimeout(this.activating),s=this._focusNextTab(s,n),t.ctrlKey||t.metaKey||(i.attr("aria-selected","false"),this.tabs.eq(s).attr("aria-selected","true"),this.activating=this._delay(function(){this.option("active",s)},this.delay))}},_panelKeydown:function(t){this._handlePageNav(t)||t.ctrlKey&&t.keyCode===e.ui.keyCode.UP&&(t.preventDefault(),this.active.focus())},_handlePageNav:function(t){return t.altKey&&t.keyCode===e.ui.keyCode.PAGE_UP?(this._activate(this._focusNextTab(this.options.active-1,!1)),!0):t.altKey&&t.keyCode===e.ui.keyCode.PAGE_DOWN?(this._activate(this._focusNextTab(this.options.active+1,!0)),!0):void 0},_findNextTab:function(t,i){function s(){return t>n&&(t=0),0>t&&(t=n),t}for(var n=this.tabs.length-1;-1!==e.inArray(s(),this.options.disabled);)t=i?t+1:t-1;return t},_focusNextTab:function(e,t){return e=this._findNextTab(e,t),this.tabs.eq(e).focus(),e},_setOption:function(e,t){return"active"===e?(this._activate(t),void 0):"disabled"===e?(this._setupDisabled(t),void 0):(this._super(e,t),"collapsible"===e&&(this.element.toggleClass("ui-tabs-collapsible",t),t||this.options.active!==!1||this._activate(0)),"event"===e&&this._setupEvents(t),"heightStyle"===e&&this._setupHeightStyle(t),void 0)},_sanitizeSelector:function(e){return e?e.replace(/[!"$%&'()*+,.\/:;<=>?@\[\]\^`{|}~]/g,"\\$&"):""},refresh:function(){var t=this.options,i=this.tablist.children(":has(a[href])");t.disabled=e.map(i.filter(".ui-state-disabled"),function(e){return i.index(e)}),this._processTabs(),t.active!==!1&&this.anchors.length?this.active.length&&!e.contains(this.tablist[0],this.active[0])?this.tabs.length===t.disabled.length?(t.active=!1,this.active=e()):this._activate(this._findNextTab(Math.max(0,t.active-1),!1)):t.active=this.tabs.index(this.active):(t.active=!1,this.active=e()),this._refresh()},_refresh:function(){this._setupDisabled(this.options.disabled),this._setupEvents(this.options.event),this._setupHeightStyle(this.options.heightStyle),this.tabs.not(this.active).attr({"aria-selected":"false","aria-expanded":"false",tabIndex:-1}),this.panels.not(this._getPanelForTab(this.active)).hide().attr({"aria-hidden":"true"}),this.active.length?(this.active.addClass("ui-tabs-active ui-state-active").attr({"aria-selected":"true","aria-expanded":"true",tabIndex:0}),this._getPanelForTab(this.active).show().attr({"aria-hidden":"false"})):this.tabs.eq(0).attr("tabIndex",0)},_processTabs:function(){var t=this,i=this.tabs,s=this.anchors,n=this.panels;
this.tablist=this._getList().addClass("ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all").attr("role","tablist").delegate("> li","mousedown"+this.eventNamespace,function(t){e(this).is(".ui-state-disabled")&&t.preventDefault()}).delegate(".ui-tabs-anchor","focus"+this.eventNamespace,function(){e(this).closest("li").is(".ui-state-disabled")&&this.blur()}),this.tabs=this.tablist.find("> li:has(a[href])").addClass("ui-state-default ui-corner-top").attr({role:"tab",tabIndex:-1}),this.anchors=this.tabs.map(function(){return e("a",this)[0]}).addClass("ui-tabs-anchor").attr({role:"presentation",tabIndex:-1}),this.panels=e(),this.anchors.each(function(i,s){var n,a,o,r=e(s).uniqueId().attr("id"),h=e(s).closest("li"),l=h.attr("aria-controls");t._isLocal(s)?(n=s.hash,o=n.substring(1),a=t.element.find(t._sanitizeSelector(n))):(o=h.attr("aria-controls")||e({}).uniqueId()[0].id,n="#"+o,a=t.element.find(n),a.length||(a=t._createPanel(o),a.insertAfter(t.panels[i-1]||t.tablist)),a.attr("aria-live","polite")),a.length&&(t.panels=t.panels.add(a)),l&&h.data("ui-tabs-aria-controls",l),h.attr({"aria-controls":o,"aria-labelledby":r}),a.attr("aria-labelledby",r)}),this.panels.addClass("ui-tabs-panel ui-widget-content ui-corner-bottom").attr("role","tabpanel"),i&&(this._off(i.not(this.tabs)),this._off(s.not(this.anchors)),this._off(n.not(this.panels)))},_getList:function(){return this.tablist||this.element.find("ol,ul").eq(0)},_createPanel:function(t){return e("<div>").attr("id",t).addClass("ui-tabs-panel ui-widget-content ui-corner-bottom").data("ui-tabs-destroy",!0)},_setupDisabled:function(t){e.isArray(t)&&(t.length?t.length===this.anchors.length&&(t=!0):t=!1);for(var i,s=0;i=this.tabs[s];s++)t===!0||-1!==e.inArray(s,t)?e(i).addClass("ui-state-disabled").attr("aria-disabled","true"):e(i).removeClass("ui-state-disabled").removeAttr("aria-disabled");this.options.disabled=t},_setupEvents:function(t){var i={};t&&e.each(t.split(" "),function(e,t){i[t]="_eventHandler"}),this._off(this.anchors.add(this.tabs).add(this.panels)),this._on(!0,this.anchors,{click:function(e){e.preventDefault()}}),this._on(this.anchors,i),this._on(this.tabs,{keydown:"_tabKeydown"}),this._on(this.panels,{keydown:"_panelKeydown"}),this._focusable(this.tabs),this._hoverable(this.tabs)},_setupHeightStyle:function(t){var i,s=this.element.parent();"fill"===t?(i=s.height(),i-=this.element.outerHeight()-this.element.height(),this.element.siblings(":visible").each(function(){var t=e(this),s=t.css("position");"absolute"!==s&&"fixed"!==s&&(i-=t.outerHeight(!0))}),this.element.children().not(this.panels).each(function(){i-=e(this).outerHeight(!0)}),this.panels.each(function(){e(this).height(Math.max(0,i-e(this).innerHeight()+e(this).height()))}).css("overflow","auto")):"auto"===t&&(i=0,this.panels.each(function(){i=Math.max(i,e(this).height("").height())}).height(i))},_eventHandler:function(t){var i=this.options,s=this.active,n=e(t.currentTarget),a=n.closest("li"),o=a[0]===s[0],r=o&&i.collapsible,h=r?e():this._getPanelForTab(a),l=s.length?this._getPanelForTab(s):e(),u={oldTab:s,oldPanel:l,newTab:r?e():a,newPanel:h};t.preventDefault(),a.hasClass("ui-state-disabled")||a.hasClass("ui-tabs-loading")||this.running||o&&!i.collapsible||this._trigger("beforeActivate",t,u)===!1||(i.active=r?!1:this.tabs.index(a),this.active=o?e():a,this.xhr&&this.xhr.abort(),l.length||h.length||e.error("jQuery UI Tabs: Mismatching fragment identifier."),h.length&&this.load(this.tabs.index(a),t),this._toggle(t,u))},_toggle:function(t,i){function s(){a.running=!1,a._trigger("activate",t,i)}function n(){i.newTab.closest("li").addClass("ui-tabs-active ui-state-active"),o.length&&a.options.show?a._show(o,a.options.show,s):(o.show(),s())}var a=this,o=i.newPanel,r=i.oldPanel;this.running=!0,r.length&&this.options.hide?this._hide(r,this.options.hide,function(){i.oldTab.closest("li").removeClass("ui-tabs-active ui-state-active"),n()}):(i.oldTab.closest("li").removeClass("ui-tabs-active ui-state-active"),r.hide(),n()),r.attr("aria-hidden","true"),i.oldTab.attr({"aria-selected":"false","aria-expanded":"false"}),o.length&&r.length?i.oldTab.attr("tabIndex",-1):o.length&&this.tabs.filter(function(){return 0===e(this).attr("tabIndex")}).attr("tabIndex",-1),o.attr("aria-hidden","false"),i.newTab.attr({"aria-selected":"true","aria-expanded":"true",tabIndex:0})},_activate:function(t){var i,s=this._findActive(t);s[0]!==this.active[0]&&(s.length||(s=this.active),i=s.find(".ui-tabs-anchor")[0],this._eventHandler({target:i,currentTarget:i,preventDefault:e.noop}))},_findActive:function(t){return t===!1?e():this.tabs.eq(t)},_getIndex:function(e){return"string"==typeof e&&(e=this.anchors.index(this.anchors.filter("[href$='"+e+"']"))),e},_destroy:function(){this.xhr&&this.xhr.abort(),this.element.removeClass("ui-tabs ui-widget ui-widget-content ui-corner-all ui-tabs-collapsible"),this.tablist.removeClass("ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all").removeAttr("role"),this.anchors.removeClass("ui-tabs-anchor").removeAttr("role").removeAttr("tabIndex").removeUniqueId(),this.tablist.unbind(this.eventNamespace),this.tabs.add(this.panels).each(function(){e.data(this,"ui-tabs-destroy")?e(this).remove():e(this).removeClass("ui-state-default ui-state-active ui-state-disabled ui-corner-top ui-corner-bottom ui-widget-content ui-tabs-active ui-tabs-panel").removeAttr("tabIndex").removeAttr("aria-live").removeAttr("aria-busy").removeAttr("aria-selected").removeAttr("aria-labelledby").removeAttr("aria-hidden").removeAttr("aria-expanded").removeAttr("role")}),this.tabs.each(function(){var t=e(this),i=t.data("ui-tabs-aria-controls");i?t.attr("aria-controls",i).removeData("ui-tabs-aria-controls"):t.removeAttr("aria-controls")}),this.panels.show(),"content"!==this.options.heightStyle&&this.panels.css("height","")},enable:function(t){var i=this.options.disabled;i!==!1&&(void 0===t?i=!1:(t=this._getIndex(t),i=e.isArray(i)?e.map(i,function(e){return e!==t?e:null}):e.map(this.tabs,function(e,i){return i!==t?i:null})),this._setupDisabled(i))},disable:function(t){var i=this.options.disabled;if(i!==!0){if(void 0===t)i=!0;else{if(t=this._getIndex(t),-1!==e.inArray(t,i))return;i=e.isArray(i)?e.merge([t],i).sort():[t]}this._setupDisabled(i)}},load:function(t,i){t=this._getIndex(t);var s=this,n=this.tabs.eq(t),a=n.find(".ui-tabs-anchor"),o=this._getPanelForTab(n),r={tab:n,panel:o},h=function(e,t){"abort"===t&&s.panels.stop(!1,!0),n.removeClass("ui-tabs-loading"),o.removeAttr("aria-busy"),e===s.xhr&&delete s.xhr};this._isLocal(a[0])||(this.xhr=e.ajax(this._ajaxSettings(a,i,r)),this.xhr&&"canceled"!==this.xhr.statusText&&(n.addClass("ui-tabs-loading"),o.attr("aria-busy","true"),this.xhr.done(function(e,t,n){setTimeout(function(){o.html(e),s._trigger("load",i,r),h(n,t)},1)}).fail(function(e,t){setTimeout(function(){h(e,t)},1)})))},_ajaxSettings:function(t,i,s){var n=this;return{url:t.attr("href"),beforeSend:function(t,a){return n._trigger("beforeLoad",i,e.extend({jqXHR:t,ajaxSettings:a},s))}}},_getPanelForTab:function(t){var i=e(t).attr("aria-controls");return this.element.find(this._sanitizeSelector("#"+i))}}),e.widget("ui.tooltip",{version:"1.11.4",options:{content:function(){var t=e(this).attr("title")||"";return e("<a>").text(t).html()},hide:!0,items:"[title]:not([disabled])",position:{my:"left top+15",at:"left bottom",collision:"flipfit flip"},show:!0,tooltipClass:null,track:!1,close:null,open:null},_addDescribedBy:function(t,i){var s=(t.attr("aria-describedby")||"").split(/\s+/);s.push(i),t.data("ui-tooltip-id",i).attr("aria-describedby",e.trim(s.join(" ")))},_removeDescribedBy:function(t){var i=t.data("ui-tooltip-id"),s=(t.attr("aria-describedby")||"").split(/\s+/),n=e.inArray(i,s);-1!==n&&s.splice(n,1),t.removeData("ui-tooltip-id"),s=e.trim(s.join(" ")),s?t.attr("aria-describedby",s):t.removeAttr("aria-describedby")},_create:function(){this._on({mouseover:"open",focusin:"open"}),this.tooltips={},this.parents={},this.options.disabled&&this._disable(),this.liveRegion=e("<div>").attr({role:"log","aria-live":"assertive","aria-relevant":"additions"}).addClass("ui-helper-hidden-accessible").appendTo(this.document[0].body)},_setOption:function(t,i){var s=this;return"disabled"===t?(this[i?"_disable":"_enable"](),this.options[t]=i,void 0):(this._super(t,i),"content"===t&&e.each(this.tooltips,function(e,t){s._updateContent(t.element)}),void 0)},_disable:function(){var t=this;e.each(this.tooltips,function(i,s){var n=e.Event("blur");n.target=n.currentTarget=s.element[0],t.close(n,!0)}),this.element.find(this.options.items).addBack().each(function(){var t=e(this);t.is("[title]")&&t.data("ui-tooltip-title",t.attr("title")).removeAttr("title")})},_enable:function(){this.element.find(this.options.items).addBack().each(function(){var t=e(this);t.data("ui-tooltip-title")&&t.attr("title",t.data("ui-tooltip-title"))})},open:function(t){var i=this,s=e(t?t.target:this.element).closest(this.options.items);s.length&&!s.data("ui-tooltip-id")&&(s.attr("title")&&s.data("ui-tooltip-title",s.attr("title")),s.data("ui-tooltip-open",!0),t&&"mouseover"===t.type&&s.parents().each(function(){var t,s=e(this);s.data("ui-tooltip-open")&&(t=e.Event("blur"),t.target=t.currentTarget=this,i.close(t,!0)),s.attr("title")&&(s.uniqueId(),i.parents[this.id]={element:this,title:s.attr("title")},s.attr("title",""))}),this._registerCloseHandlers(t,s),this._updateContent(s,t))},_updateContent:function(e,t){var i,s=this.options.content,n=this,a=t?t.type:null;return"string"==typeof s?this._open(t,e,s):(i=s.call(e[0],function(i){n._delay(function(){e.data("ui-tooltip-open")&&(t&&(t.type=a),this._open(t,e,i))})}),i&&this._open(t,e,i),void 0)},_open:function(t,i,s){function n(e){l.of=e,o.is(":hidden")||o.position(l)}var a,o,r,h,l=e.extend({},this.options.position);if(s){if(a=this._find(i))return a.tooltip.find(".ui-tooltip-content").html(s),void 0;i.is("[title]")&&(t&&"mouseover"===t.type?i.attr("title",""):i.removeAttr("title")),a=this._tooltip(i),o=a.tooltip,this._addDescribedBy(i,o.attr("id")),o.find(".ui-tooltip-content").html(s),this.liveRegion.children().hide(),s.clone?(h=s.clone(),h.removeAttr("id").find("[id]").removeAttr("id")):h=s,e("<div>").html(h).appendTo(this.liveRegion),this.options.track&&t&&/^mouse/.test(t.type)?(this._on(this.document,{mousemove:n}),n(t)):o.position(e.extend({of:i},this.options.position)),o.hide(),this._show(o,this.options.show),this.options.show&&this.options.show.delay&&(r=this.delayedShow=setInterval(function(){o.is(":visible")&&(n(l.of),clearInterval(r))},e.fx.interval)),this._trigger("open",t,{tooltip:o})}},_registerCloseHandlers:function(t,i){var s={keyup:function(t){if(t.keyCode===e.ui.keyCode.ESCAPE){var s=e.Event(t);s.currentTarget=i[0],this.close(s,!0)}}};i[0]!==this.element[0]&&(s.remove=function(){this._removeTooltip(this._find(i).tooltip)}),t&&"mouseover"!==t.type||(s.mouseleave="close"),t&&"focusin"!==t.type||(s.focusout="close"),this._on(!0,i,s)},close:function(t){var i,s=this,n=e(t?t.currentTarget:this.element),a=this._find(n);return a?(i=a.tooltip,a.closing||(clearInterval(this.delayedShow),n.data("ui-tooltip-title")&&!n.attr("title")&&n.attr("title",n.data("ui-tooltip-title")),this._removeDescribedBy(n),a.hiding=!0,i.stop(!0),this._hide(i,this.options.hide,function(){s._removeTooltip(e(this))}),n.removeData("ui-tooltip-open"),this._off(n,"mouseleave focusout keyup"),n[0]!==this.element[0]&&this._off(n,"remove"),this._off(this.document,"mousemove"),t&&"mouseleave"===t.type&&e.each(this.parents,function(t,i){e(i.element).attr("title",i.title),delete s.parents[t]}),a.closing=!0,this._trigger("close",t,{tooltip:i}),a.hiding||(a.closing=!1)),void 0):(n.removeData("ui-tooltip-open"),void 0)},_tooltip:function(t){var i=e("<div>").attr("role","tooltip").addClass("ui-tooltip ui-widget ui-corner-all ui-widget-content "+(this.options.tooltipClass||"")),s=i.uniqueId().attr("id");return e("<div>").addClass("ui-tooltip-content").appendTo(i),i.appendTo(this.document[0].body),this.tooltips[s]={element:t,tooltip:i}},_find:function(e){var t=e.data("ui-tooltip-id");return t?this.tooltips[t]:null},_removeTooltip:function(e){e.remove(),delete this.tooltips[e.attr("id")]},_destroy:function(){var t=this;e.each(this.tooltips,function(i,s){var n=e.Event("blur"),a=s.element;n.target=n.currentTarget=a[0],t.close(n,!0),e("#"+i).remove(),a.data("ui-tooltip-title")&&(a.attr("title")||a.attr("title",a.data("ui-tooltip-title")),a.removeData("ui-tooltip-title"))}),this.liveRegion.remove()}})});


/*!
 * jQuery Mousewheel 3.1.13
 *
 * Copyright 2015 jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 */
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?module.exports=a:a(jQuery)}(function(a){function b(b){var g=b||window.event,h=i.call(arguments,1),j=0,l=0,m=0,n=0,o=0,p=0;if(b=a.event.fix(g),b.type="mousewheel","detail"in g&&(m=-1*g.detail),"wheelDelta"in g&&(m=g.wheelDelta),"wheelDeltaY"in g&&(m=g.wheelDeltaY),"wheelDeltaX"in g&&(l=-1*g.wheelDeltaX),"axis"in g&&g.axis===g.HORIZONTAL_AXIS&&(l=-1*m,m=0),j=0===m?l:m,"deltaY"in g&&(m=-1*g.deltaY,j=m),"deltaX"in g&&(l=g.deltaX,0===m&&(j=-1*l)),0!==m||0!==l){if(1===g.deltaMode){var q=a.data(this,"mousewheel-line-height");j*=q,m*=q,l*=q}else if(2===g.deltaMode){var r=a.data(this,"mousewheel-page-height");j*=r,m*=r,l*=r}if(n=Math.max(Math.abs(m),Math.abs(l)),(!f||f>n)&&(f=n,d(g,n)&&(f/=40)),d(g,n)&&(j/=40,l/=40,m/=40),j=Math[j>=1?"floor":"ceil"](j/f),l=Math[l>=1?"floor":"ceil"](l/f),m=Math[m>=1?"floor":"ceil"](m/f),k.settings.normalizeOffset&&this.getBoundingClientRect){var s=this.getBoundingClientRect();o=b.clientX-s.left,p=b.clientY-s.top}return b.deltaX=l,b.deltaY=m,b.deltaFactor=f,b.offsetX=o,b.offsetY=p,b.deltaMode=0,h.unshift(b,j,l,m),e&&clearTimeout(e),e=setTimeout(c,200),(a.event.dispatch||a.event.handle).apply(this,h)}}function c(){f=null}function d(a,b){return k.settings.adjustOldDeltas&&"mousewheel"===a.type&&b%120===0}var e,f,g=["wheel","mousewheel","DOMMouseScroll","MozMousePixelScroll"],h="onwheel"in document||document.documentMode>=9?["wheel"]:["mousewheel","DomMouseScroll","MozMousePixelScroll"],i=Array.prototype.slice;if(a.event.fixHooks)for(var j=g.length;j;)a.event.fixHooks[g[--j]]=a.event.mouseHooks;var k=a.event.special.mousewheel={version:"3.1.12",setup:function(){if(this.addEventListener)for(var c=h.length;c;)this.addEventListener(h[--c],b,!1);else this.onmousewheel=b;a.data(this,"mousewheel-line-height",k.getLineHeight(this)),a.data(this,"mousewheel-page-height",k.getPageHeight(this))},teardown:function(){if(this.removeEventListener)for(var c=h.length;c;)this.removeEventListener(h[--c],b,!1);else this.onmousewheel=null;a.removeData(this,"mousewheel-line-height"),a.removeData(this,"mousewheel-page-height")},getLineHeight:function(b){var c=a(b),d=c["offsetParent"in a.fn?"offsetParent":"parent"]();return d.length||(d=a("body")),parseInt(d.css("fontSize"),10)||parseInt(c.css("fontSize"),10)||16},getPageHeight:function(b){return a(b).height()},settings:{adjustOldDeltas:!0,normalizeOffset:!0}};a.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})});


$(document).on("change", ".demopick", function(e){
	var	thisObj, 
		thisSel = $(e.currentTarget),
		thisBox = "#"+thisSel.data("link"),
		thisVal = thisSel.val(),
		thisJSON = makeJSON(thisVal);
		
	if ( thisVal === "true" ) { thisVal = true; }
	if ( thisVal === "false" ) { thisVal = false; }
	if ( thisVal === parseInt(thisVal,10) ) { thisVal = parseInt(thisVal,10); }
	if ( thisJSON !==false ) { thisVal = thisJSON; }
	thisObj = {}; thisObj[thisSel.data("opt")] = thisVal;
	$(thisBox).datebox(thisObj);
	$(thisBox).datebox("refresh");
});

function makeJSON(str) {
	try {
		return jQuery.parseJSON(str);
	} catch (e) {
		return false;
	}
}
var langs = [{"data":[
	"en: English US", "af: Afrikaans", "ar: العربية", "bg: български език", "ca: Català",
	"cs: Čeština", "da: Dansk", "de: Deutsch", "el: ελληνικά", "es-ES: Español", "fi: Suomi",
	"fr: Français", "he: עברית", "hr: Hrvatski Jezik", "hu: Magyar", "id: Bahasa Indonesia",
	"it: Italiano", "ja: 日本語", "ko: 한국어", "lt: Lietuvių Kalba", "nl: Nederlands",
	"nl-BE: Nederlands, Belgium", "no: Norsk", "pl: Język Polski", "pt-BR: Português",
	"pt-PT: Português", "ro: Limba Română", "ru: русский язык", "sl: Slovenski Jezik",
	"sr: српски језик", "sv-SE: Svenska", "th: ไทย", "tr: Türkçe", "uk: українська мова",
	"vi: Tiếng Việt", "zh-CN: 中文 (Simplified)", "zh-TW: 中文 (Traditional)"]}];
		
window.changeLang = function(a) {
	var thisLangRaw = langs[0].data[a.custom[0]],
		colonPos = thisLangRaw.search(/:/),
		thisLang = thisLangRaw.substr(0,colonPos);
		
	$( "[data-role='datebox']" ).each(function() {
		if ( typeof $(this).data("jtsage-datebox") !== "undefined" ) {
			$(this).datebox({"useLang": thisLang});
		} else {
			$(this).attr("datebox-use-lang", thisLang);
		}
	});
};

window.doOptLimit = function() {
	console.log($( "#filterman" ).val());
	var newVal = $( "#filterman" ).val().toLowerCase();
	if ( newVal === "" ) { 
		$( ".panel.hide" ).removeClass( "hide" );
	} else {
		$( ".panel.hide" ).removeClass( "hide" );
		$( ".panel-title" ).each( function () { 
			if ( $( this ).text().toLowerCase().indexOf( newVal ) === -1 ) { 
				$( this ).parent().parent().addClass( "hide" );
			}
		}); 
	}
};
window.doOptLimit2 = function() {
	console.log($( "#filterman2" ).val());
	var newVal = $( "#filterman2" ).val().toLowerCase();
	if ( newVal === "" ) { 
		$( ".panel.hide" ).removeClass( "hide" );
	} else {
		$( ".panel.hide" ).removeClass( "hide" );
		$( ".panel-body" ).each( function () { 
			if ( $( this ).text().toLowerCase().indexOf( newVal ) === -1 ) { 
				$( this ).parent().addClass( "hide" );
			}
		}); 
	}
};

$(document).on("keyup", "#filterman", function() { window.doOptLimit(); });
$(document).on("change", "#filterman", function() { window.doOptLimit(); });
$(document).on("click", "#filtermanbtn", function(e) { 
	e.preventDefault(); window.doOptLimit();
});

$(document).on("keyup", "#filterman2", function() { window.doOptLimit2(); });
$(document).on("change", "#filterman2", function() { window.doOptLimit2(); });
$(document).on("click", "#filterman2btn", function(e) { 
	e.preventDefault(); window.doOptLimit2();
});


/*! JTSage-DateBox-4.2.2 |2017-06-20T22:49:58Z | (c) 2010,  2017 JTSage | https://github.com/jtsage/jquery-mobile-datebox/blob/master/LICENSE.txt */
!function(a){a.widget("jtsage.datebox",{initSelector:"input[data-role='datebox']",options:{version:"4.2.2",jqmVersion:"1.4.5",bootstrapVersion:"3.3.7",bootstrap4Version:"4.0.0a6",jqmuiWidgetVersion:"1.11.4",theme:!1,themeDefault:"a",themeHeader:"a",themeSetButton:"a",themeCloseButton:"default",mode:!1,transition:"fade",useAnimation:!0,hideInput:!1,hideContainer:!1,lockInput:!0,zindex:"1100",clickEvent:"click",clickEventAlt:"click",useKinetic:!0,defaultValue:!1,showInitialValue:!1,linkedField:!1,linkedFieldFormat:"%J",popupPosition:!1,popupButtonPosition:"left",popupForceX:!1,popupForceY:!1,useModal:!0,useModalTheme:"b",useInline:!1,useInlineBlind:!1,useHeader:!0,useImmediate:!1,useButton:!0,buttonIcon:!1,useFocus:!1,useSetButton:!0,useCancelButton:!1,useTodayButton:!1,useTomorrowButton:!1,useClearButton:!1,useCollapsedBut:!1,usePlaceholder:!1,beforeOpenCallback:!1,beforeOpenCallbackArgs:[],openCallback:!1,openCallbackArgs:[],closeCallback:!1,closeCallbackArgs:[],startOffsetYears:!1,startOffsetMonths:!1,startOffsetDays:!1,afterToday:!1,beforeToday:!1,notToday:!1,maxDays:!1,minDays:!1,maxYear:!1,minYear:!1,blackDates:!1,blackDatesRec:!1,blackDays:!1,whiteDates:!0,minHour:!1,maxHour:!1,minTime:!1,maxTime:!1,maxDur:!1,minDur:!1,minuteStep:1,minuteStepRound:0,twoDigitYearCutoff:38,rolloverMode:{m:!0,d:!0,h:!0,i:!0,s:!0},useLang:"default",lang:{"default":{setDateButtonLabel:"Set Date",setTimeButtonLabel:"Set Time",setDurationButtonLabel:"Set Duration",todayButtonLabel:"Jump to Today",tomorrowButtonLabel:"Jump to Tomorrow",titleDateDialogLabel:"Set Date",titleTimeDialogLabel:"Set Time",daysOfWeek:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],daysOfWeekShort:["Su","Mo","Tu","We","Th","Fr","Sa"],monthsOfYear:["January","February","March","April","May","June","July","August","September","October","November","December"],monthsOfYearShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],durationLabel:["Days","Hours","Minutes","Seconds"],durationDays:["Day","Days"],timeFormat:24,headerFormat:"%A, %B %-d, %Y",tooltip:"Open Date Picker",nextMonth:"Next Month",prevMonth:"Previous Month",dateFieldOrder:["m","d","y"],timeFieldOrder:["h","i","a"],slideFieldOrder:["y","m","d"],dateFormat:"%Y-%m-%d",useArabicIndic:!1,isRTL:!1,calStartDay:0,clearButton:"Clear",cancelButton:"Cancel",durationOrder:["d","h","i","s"],meridiem:["AM","PM"],timeOutput:"%k:%M",durationFormat:"%Dd %DA, %Dl:%DM:%DS",calDateListLabel:"Other Dates",calHeaderFormat:"%B %Y"}},themeDateToday:"info",themeDayHigh:"warning",themeDatePick:"success",themeDateHigh:"warning",themeDateHighAlt:"danger",themeDateHighRec:"warning",themeDate:"default",themeButton:"default",themeInput:"default",themeClearButton:"default",themeCancelButton:"default",themeTomorrowButton:"default",themeTodayButton:"default",buttonIconDate:"calendar",buttonIconTime:"time",disabledState:"disabled",bootstrapDropdown:!0,bootstrapDropdownRight:!0,bootstrapModal:!1,bootstrapResponsive:!0,calNextMonthIcon:"plus",calPrevMonthIcon:"minus",useInlineAlign:"left",btnCls:" btn btn-sm btn-",icnCls:" glyphicon glyphicon-",s:{cal:{prevMonth:"<span title='{text}' class='glyphicon glyphicon-{icon}'></span>",nextMonth:"<span title='{text}' class='glyphicon glyphicon-{icon}'></span>",botButton:"<a href='#' class='{cls}' role='button'><span class='{icon}'></span> {text}</a>"}},tranDone:"webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend",calHighToday:!0,calHighPick:!0,calShowDays:!0,calOnlyMonth:!1,calWeekMode:!1,calWeekModeDay:1,calControlGroup:!1,calShowWeek:!1,calUsePickers:!1,calNoHeader:!1,calFormatter:!1,calAlwaysValidateDates:!1,calYearPickMin:-6,calYearPickMax:6,calYearPickRelative:!0,calBeforeAppendFunc:function(a){return a},highDays:!1,highDates:!1,highDatesRec:!1,highDatesAlt:!1,enableDates:!1,calDateList:!1,calShowDateList:!1,validHours:!1,repButton:!0,durationStep:1,durationSteppers:{d:1,h:1,i:1,s:1},flen:{y:25,m:24,d:40,h:24,i:30,s:30},slen:{y:9,m:14,d:16,h:16,i:30}},_getLongOptions:function(a){var b,c,d={},e="datebox",f=7;for(b in a.data())b.substr(0,f)===e&&b.length>f&&(c=b.substr(f),c=c.charAt(0).toLowerCase()+c.slice(1),"options"!==c&&(d[c]=a.data(b)));return d},_setOption:function(){a.Widget.prototype._setOption.apply(this,arguments),this.refresh()},getOption:function(a){var b=this.__(a);return"Err:NotFound"!==b?b:this.options[a]},baseMode:"bootstrap",_stdBtn:{cancel:function(){var b=this,c=this.options;return a("<a href='#' role='button' class='btn btn-sm btn-"+c.themeCancelButton+"'><span class='"+c.icnCls+"remove'></span> "+b.__("cancelButton")+"</a>").on(c.clickEventAlt,function(a){a.preventDefault(),b._t({method:"close",closeCancel:!0})})},clear:function(){var b=this,c=this.options;return a("<a href='#' role='button' class='btn btn-sm btn-"+c.themeClearButton+"'><span class='"+c.icnCls+"erase'></span> "+b.__("clearButton")+"</a>").on(c.clickEventAlt,function(a){a.preventDefault(),b.d.input.val(""),b._t({method:"clear"}),b._t({method:"close",closeCancel:!0})})},close:function(b,c){var d=this,e=this.options;return"undefined"==typeof c&&(c=!1),a("<a href='#' role='button' class='btn btn-sm btn-"+e.themeCloseButton+"'><span class='"+e.icnCls+"ok'></span> "+b+"</a>").addClass(""+(d.dateOK===!0?"":"disabled")).on(e.clickEventAlt,function(a){a.preventDefault(),d.dateOK===!0&&(c===!1?d._t({method:"set",value:d._formatter(d.__fmt(),d.theDate),date:d.theDate}):d._t(c),d._t({method:"close"}))})},today:function(){var b=this,c=this.options;return a("<a href='#' role='button' class='btn btn-sm btn-"+c.themeTodayButton+"'><span class='"+c.icnCls+"send'></span> "+b.__("todayButtonLabel")+"</a>").on(c.clickEventAlt,function(a){a.preventDefault(),b.theDate=b._pa([0,0,0],new b._date),b.calBackDate=!1,b._t({method:"doset"})})},tomorrow:function(){var b=this,c=this.options;return a("<a href='#' role='button' class='btn btn-sm btn-"+c.themeTomorrowButton+"'><span class='"+c.icnCls+"send'></span> "+b.__("tomorrowButtonLabel")+"</a>").on(c.clickEventAlt,function(a){a.preventDefault(),b.theDate=b._pa([0,0,0],new b._date).adj(2,1),b.calBackDate=!1,b._t({method:"doset"})})}},_destroy:function(){var b=this,c=this.options,d=this.d.wrap.find(".input-group-addon");c.useButton===!0&&(d.remove(),b.d.input.unwrap()),c.lockInput&&b.d.input.removeAttr("readonly"),b.d.input.off("datebox").off("focus.datebox").off("blur.datebox").off("change.datebox"),a(document).off(b.drag.eMove).off(b.drag.eEnd).off(b.drag.eEndA)},_create:function(){a(document).trigger("dateboxcreate");var b=this,c=a.extend(this.options,this._getLongOptions(this.element),this.element.data("options")),d=c.theme===!1?"default":c.theme,e={input:this.element,wrap:this.element.parent(),mainWrap:a("<div>",{"class":"ui-datebox-container"}).css("zIndex",c.zindex),intHTML:!1},f=".datebox"+this.uuid,g="undefined"!=typeof window.ontouchstart,h={eStart:"touchstart"+f+" mousedown"+f,eMove:"touchmove"+f+" mousemove"+f,eEnd:"touchend"+f+" mouseup"+f,eEndA:["mouseup","touchend","touchcanel","touchmove"].join(f+" ")+f,move:!1,start:!1,end:!1,pos:!1,target:!1,delta:!1,tmp:!1};a.extend(b,{d:e,drag:h,touch:g}),c.usePlaceholder!==!1&&(c.usePlaceholder===!0&&""!==b._grabLabel()&&b.d.input.attr("placeholder",b._grabLabel()),"string"==typeof c.usePlaceholder&&b.d.input.attr("placeholder",c.usePlaceholder)),c.theme=d,b.cancelClose=!1,b.calBackDate=!1,b.calDateVisible=!0,b.disabled=!1,b.runButton=!1,b._date=window.Date,b._enhanceDate(),b.baseID=b.d.input.attr("id"),b.initDate=new b._date,b.initDate.setMilliseconds(0),b.theDate=c.defaultValue?b._makeDate():""!==b.d.input.val()?b._makeDate(b.d.input.val()):new b._date,""===b.d.input.val()&&b._startOffset(b.theDate),b.initDone=!1,c.showInitialValue&&b.d.input.val(b._formatter(b.__fmt(),b.theDate)),b.d.wrap=b.d.input.wrap("<div class='input-group'>").parent(),c.mode!==!1&&c.buttonIcon===!1&&("time"===c.mode.substr(0,4)||"dur"===c.mode.substr(0,3)?c.buttonIcon=c.buttonIconTime:c.buttonIcon=c.buttonIconDate),c.useButton?a("<div class='input-group-addon'><span class='"+c.icnCls+c.buttonIcon+"'></span></div>").attr("title",b.__("tooltip")).on(c.clickEvent,function(a){a.preventDefault(),c.useFocus?b.d.input.focus():b.disabled||b._t({method:"open"})}).appendTo(b.d.wrap):b.d.wrap.css("width","100%"),c.hideInput&&b.d.wrap.hide(),c.hideContainer&&b.d.wrap.parent().hide(),c.hideContainer&&!c.useInline&&(c.bootstrapModal=!0,c.bootstrapResponsive=!1),b.d.input.on("focus.datebox",function(){b.d.input.addClass("ui-focus"),b.disabled===!1&&c.useFocus&&b._t({method:"open"})}).on("blur.datebox",function(){b.d.input.removeClass("ui-focus")}).on("change.datebox",function(){b.theDate=b._makeDate(b.d.input.val()),b.refresh()}).on("datebox",b._event),c.lockInput&&b.d.input.attr("readonly","readonly"),"undefined"!=typeof a.event.special.mousewheel&&(b.wheelExists=!0),b.d.input.is(":disabled")&&b.disable(),b.applyMinMax(!1,!1),(c.useInline||c.useInlineBlind)&&b.open(),a(document).trigger("dateboxaftercreate")},open:function(){var b=this,c=this.options,d={};if(c.useFocus&&b.fastReopen===!0)return b.d.input.blur(),!1;if(b.theDate=b._makeDate(b.d.input.val()),b.calBackDate=!1,""===b.d.input.val()&&b._startOffset(b.theDate),b.d.input.blur(),"undefined"==typeof b._build[c.mode]?b._build["default"].apply(b,[]):b._build[c.mode].apply(b,[]),"undefined"!=typeof b._drag[c.mode]&&b._drag[c.mode].apply(b,[]),b._t({method:"refresh"}),b.__("useArabicIndic")===!0&&b._doIndic(),(c.useInline||c.useInlineBlind)&&b.initDone===!1){switch(b.d.mainWrap.append(b.d.intHTML),c.hideContainer?(c.useHeader&&b.d.mainWrap.prepend(a(b._spf("<div class='{c1}'><h4 class='{c2}'>{text}</h4></div>",{c1:"modal-header",c2:"modal-title text-center",text:b.d.headerText}))),b.d.wrap.parent().after(b.d.mainWrap)):b.d.wrap.parent().append(b.d.mainWrap),c.useInlineAlign){case"right":b.d.mainWrap.css({marginRight:0,marginLeft:"auto"});break;case"left":b.d.mainWrap.css({marginLeft:0,marginRight:"auto"});break;case"center":case"middle":b.d.mainWrap.css({marginLeft:"auto",marginRight:"auto"})}if(b.d.mainWrap.removeClass("ui-datebox-hidden ui-overlay-shadow"),c.useInline)return b.d.mainWrap.addClass("ui-datebox-inline").css("zIndex","auto"),c.hideInput||c.hideContainer||b.d.mainWrap.addClass("ui-datebox-inline-has-input"),setTimeout(function(a){return function(){a._t({method:"postrefresh"})}}(b),100),!0;b.d.mainWrap.addClass("ui-datebox-inline ui-datebox-inline-has-input").css("zIndex","auto"),b.d.mainWrap.hide(),b.initDone=!1,b._t({method:"postrefresh"})}return c.useInlineBlind?(b.initDone?(b.refresh(),b.d.mainWrap.slideDown(),b._t({method:"postrefresh"})):b.initDone=!0,!0):b.d.intHTML.is(":visible")?!1:(b.d.mainWrap.empty(),c.useHeader&&b.d.mainWrap.append(a(b._spf("<div class='{c1}'><h4 class='{c2}'><span class='{c3}'></span>{text}</h4></div>",{c1:"modal-header",c2:"modal-title text-center",c3:"closer"+c.icnCls+"remove pull-"+c.popupButtonPosition,text:b.d.headerText}))).find(".closer").on(c.clickEventAlt,function(a){a.preventDefault(),b._t({method:"close",closeCancel:!0})}),b.d.mainWrap.append(b.d.intHTML).css("zIndex",c.zindex),b._t({method:"postrefresh"}),c.openCallback!==!1?(a.isFunction(c.openCallback)||"function"==typeof window[c.openCallback]&&(c.openCallback=window[c.openCallback]),d.afteropen=function(){b._t({method:"postrefresh"}),c.openCallback.apply(b,a.merge([{custom:b.customCurrent,initDate:b.initDate,date:b.theDate,duration:b.lastDuration}],c.openCallbackArgs))===!1&&b._t({method:"close"})}):d.afteropen=function(){b._t({method:"postrefresh"})},c.beforeOpenCallback!==!1&&(a.isFunction(c.beforeOpenCallback)||"function"==typeof window[c.beforeOpenCallback]&&(c.beforeOpenCallback=window[c.beforeOpenCallback]),c.beforeOpenCallback.apply(b,a.merge([{custom:b.customCurrent,initDate:b.initDate,date:b.theDate,duration:b.lastDuration}],c.beforeOpenCallbackArgs))===!1)?!1:(c.bootstrapResponsive===!0?a(window).width()>768?(c.bootstrapModal=!1,c.bootstrapDropdown=!0):(c.bootstrapModal=!0,c.bootstrapDropdown=!1):c.bootstrapModal===!0&&(c.bootstrapDropdown=!1),c.bootstrapDropdown===!1&&c.bootstrapModal===!0&&(b.d.mainWrap.css({width:"100%"}),b.d.modalWrap=a('<div id="jtdb-'+this.uuid+'" class="modal fade"><div class="modal-dialog" role="document"><div class="modal-content"></div></div></div>').addClass(c.useAnimation?c.transition:""),b.d.modalWrap.find(".modal-content").append(b.d.mainWrap),b.d.modalWrap.appendTo(a("body")).on("shown.bs.modal",function(){d.afteropen.call()}).modal({backdrop:"static"}),b.d.modalWrap.modal("show")),void(c.bootstrapDropdown===!0&&c.bootstrapModal===!1&&(b.d.mainWrap.removeAttr("style").addClass("dropdown-menu").addClass(c.useAnimation?c.transition:"").addClass(c.bootstrapDropdownRight===!0?"dropdown-menu-right":"").appendTo(b.d.wrap).on(c.tranDone,function(){b.d.mainWrap.is(":visible")?d.afteropen.call():(d.afterclose.call(),b.d.wrap.removeClass("open"))}),b.d.wrap.addClass("open"),b.d.backdrop=a("<div class='jtsage-datebox-backdrop-div'></div>").css({position:"fixed",left:0,top:0,bottom:0,right:0}).appendTo("body").on(c.clickEvent,function(a){a.preventDefault(),b._t({method:"close",closeCancel:!0})}),window.setTimeout(function(){b.d.mainWrap.addClass("in")},0)))))},close:function(){var b=this,c=this.options,d={};return b.calBackDate=!1,c.useInlineBlind?(b.d.mainWrap.slideUp(),!0):c.useInline||b.d.intHTML===!1?!0:(c.closeCallback!==!1?(a.isFunction(c.closeCallback)||"function"==typeof window[c.closeCallback]&&(c.closeCallback=window[c.closeCallback]),d.afterclose=function(){c.closeCallback.apply(b,a.merge([{custom:b.customCurrent,initDate:b.initDate,date:b.theDate,duration:b.lastDuration,cancelClose:b.cancelClose}],c.closeCallbackArgs))}):d.afterclose=function(){return!0},c.bootstrapDropdown===!1&&c.bootstrapModal===!0&&(b.d.modalWrap.on("hidden.bs.modal",function(){d.afterclose.call(),b.d.modalWrap.remove()}),b.d.modalWrap.modal("hide")),c.bootstrapDropdown===!0&&c.bootstrapModal===!1&&(c.useAnimation===!0?(b.d.mainWrap.removeClass("in"),b.d.backdrop.remove(),a(".jtsage-datebox-backdrop-div").remove(),window.setTimeout(function(){b.d.wrap.removeClass("open"),d.afterclose.call()},0)):(b.d.wrap.removeClass("open"),b.d.backdrop.remove(),a(".jtsage-datebox-backdrop-div").remove(),d.afterclose.call())),a(document).off(b.drag.eMove).off(b.drag.eEnd).off(b.drag.eEndA),void(c.useFocus&&(b.fastReopen=!0,setTimeout(function(a){return function(){a.fastReopen=!1}}(b),300))))},disable:function(){var a=this;a.d.input.attr("disabled",!0),a.disabled=!0,a._t({method:"disable"})},enable:function(){var a=this;a.d.input.attr("disabled",!1),a.disabled=!1,a._t({method:"enable"})},_controlGroup:function(a){var b=this.options;return b.useCollapsedBut?(a.find("a").css({width:"auto"}),a.addClass("btn-group btn-group-justified")):a.addClass("btn-group-vertical"),a},_enhanceDate:function(){a.extend(this._date.prototype,{copy:function(b,c){return b=a.extend([0,0,0,0,0,0,0],b),c=a.extend([0,0,0,0,0,0,0],c),new Date(c[0]>0?c[0]:this.get(0)+b[0],c[1]>0?c[1]:this.get(1)+b[1],c[2]>0?c[2]:this.get(2)+b[2],c[3]>0?c[3]:this.get(3)+b[3],c[4]>0?c[4]:this.get(4)+b[4],c[5]>0?c[5]:this.get(5)+b[5],c[6]>0?c[5]:this.get(6)+b[6])},adj:function(a,b){if("number"!=typeof b||"number"!=typeof a)throw new Error("Invalid Arguments");switch(a){case 0:this.setD(0,this.get(0)+b);break;case 1:this.setD(1,this.get(1)+b);break;case 2:this.setD(2,this.get(2)+b);break;case 3:b*=60;case 4:b*=60;case 5:b*=1e3;case 6:this.setTime(this.getTime()+b)}return this},setD:function(a,b){switch(a){case 0:this.setFullYear(b);break;case 1:this.setMonth(b);break;case 2:this.setDate(b);break;case 3:this.setHours(b);break;case 4:this.setMinutes(b);break;case 5:this.setSeconds(b);break;case 6:this.setMilliseconds(b)}return this},get:function(a){switch(a){case 0:return this.getFullYear();case 1:return this.getMonth();case 2:return this.getDate();case 3:return this.getHours();case 4:return this.getMinutes();case 5:return this.getSeconds();case 6:return this.getMilliseconds()}return!1},get12hr:function(){return 0===this.get(3)?12:this.get(3)<13?this.get(3):this.get(3)-12},iso:function(){var a=[0,0,0],b=0;for(b=0;3>b;b++)a[b]=this.get(b),1===b&&a[b]++,a[b]<10&&(a[b]="0"+String(a[b]));return a.join("-")},comp:function(){return parseInt(this.iso().replace(/-/g,""),10)},getEpoch:function(){return Math.floor(this.getTime()/1e3)},getArray:function(){var a=[0,0,0,0,0,0],b=0;for(b=0;6>b;b++)a[b]=this.get(b);return a},setFirstDay:function(a){return this.setD(2,1).adj(2,a-this.getDay()),this.get(2)>10&&this.adj(2,7),this},setDWeek:function(a,b){return 4===a?this.setD(1,0).setD(2,1).setFirstDay(4).adj(2,-3).adj(2,7*(b-1)):this.setD(1,0).setD(2,1).setFirstDay(a).adj(2,7*(b-1))},getDWeek:function(a){var b,c;switch(a){case 0:return b=this.copy([0,-1*this.getMonth()]).setFirstDay(0),Math.floor((this.getTime()-(b.getTime()+6e4*(this.getTimezoneOffset()-b.getTimezoneOffset())))/6048e5)+1;case 1:return b=this.copy([0,-1*this.getMonth()]).setFirstDay(1),Math.floor((this.getTime()-(b.getTime()+6e4*(this.getTimezoneOffset()-b.getTimezoneOffset())))/6048e5)+1;case 4:return 11===this.getMonth()&&this.getDate()>28?1:(b=this.copy([0,-1*this.getMonth()],!0).setFirstDay(4).adj(2,-3),c=Math.floor((this.getTime()-(b.getTime()+6e4*(this.getTimezoneOffset()-b.getTimezoneOffset())))/6048e5)+1,1>c?(b=this.copy([-1,-1*this.getMonth()]).setFirstDay(4).adj(2,-3),Math.floor((this.getTime()-b.getTime())/6048e5)+1):c);default:return 0}}})},_ord:{"default":function(a){var b=a%10;return a>9&&21>a||b>3?"th":["th","st","nd","rd"][b]}},_customformat:{"default":function(){return!1}},_formatter:function(a,b,c){var d,e=this,f=this.options,g=0;return"undefined"==typeof c&&(c=!0),"dura"===f.mode.substr(0,4)&&(g=e._dur(this.theDate.getTime()-this.initDate.getTime()),a.match(/%Dd/)||(g[1]+=24*g[0]),a.match(/%Dl/)||(g[2]+=60*g[1]),a.match(/%DM/)||(g[3]+=60*g[2])),a=a.replace(/%(D|X|0|-)*([1-9a-zA-Z])/g,function(a,c,h){if("X"===c)return"undefined"!=typeof e._customformat[f.mode]?e._customformat[f.mode](h,b,f):a;if("D"===c)switch(h){case"d":return g[0];case"l":return e._zPad(g[1]);case"M":return e._zPad(g[2]);case"S":return e._zPad(g[3]);case"A":return e.__("durationDays")[1===g[0]?0:1];default:return a}switch(h){case"a":return e.__("daysOfWeekShort")[b.getDay()];case"A":return e.__("daysOfWeek")[b.getDay()];case"b":return e.__("monthsOfYearShort")[b.getMonth()];case"B":return e.__("monthsOfYear")[b.getMonth()];case"C":return parseInt(b.getFullYear()/100);case"d":return e._zPad(b.getDate(),c);case"H":case"k":return e._zPad(b.getHours(),c);case"I":case"l":return e._zPad(b.get12hr(),c);case"m":return e._zPad(b.getMonth()+1,c);case"M":return e._zPad(b.getMinutes(),c);case"p":case"P":return d=e.__("meridiem")[b.get(3)<12?0:1].toUpperCase(),"P"===h?d.toLowerCase():d;case"s":return b.getEpoch();case"S":return e._zPad(b.getSeconds(),c);case"u":return e._zPad(b.getDay()+1,c);case"w":return b.getDay();case"y":return e._zPad(b.getFullYear()%100);case"Y":return b.getFullYear();case"E":return b.getFullYear()+543;case"V":return e._zPad(b.getDWeek(4),c);case"U":return e._zPad(b.getDWeek(0),c);case"W":return e._zPad(b.getDWeek(1),c);case"o":return"undefined"!=typeof e._ord[f.useLang]?e._ord[f.useLang](b.getDate()):e._ord["default"](b.getDate());case"j":return d=new Date(b.getFullYear(),0,1),d="000"+String(Math.ceil((b-d)/864e5)+1),d.slice(-3);case"J":return b.toJSON();case"G":return d=b.getFullYear(),1===b.getDWeek(4)&&b.getMonth()>0?d+1:b.getDWeek(4)>51&&b.getMonth()<11?d-1:d;case"g":return d=b.getFullYear%100,1===b.getDWeek(4)&&b.getMonth()>0&&++d,b.getDWeek(4)>51&&b.getMonth()<11&&--d,e._zpad(d);default:return a}}),e.__("useArabicIndic")===!0&&c===!0&&(a=e._dRep(a)),a},_minStepFix:function(){var a=this.theDate.get(4),b=this.options.minuteStep,c=this.options.minStepRound,d=a%b;b>1&&d>0&&(0>c?a-=d:c>0?a+=b-d:b/2>a%b?a-=d:a+=b-d,this.theDate.setMinutes(a))},_check:function(){var b,c,d,e,f,g,h=this,i=this.options,j=this.theDate;if(h.dateOK=!0,"undefined"==typeof i.mode)return!0;if(i.afterToday&&(b=new h._date,b>j&&(j=b)),i.beforeToday&&(b=new h._date,j>b&&(j=b)),i.maxDays!==!1&&(b=new h._date,b.adj(2,i.maxDays),j>b&&(j=b)),i.minDays!==!1&&(b=new h._date,b.adj(2,-1*i.minDays),b>j&&(j=b)),i.minHour!==!1&&j.get(3)<i.minHour&&j.setD(3,i.minHour),i.maxHour!==!1&&j.get(3)>i.maxHour&&j.setD(3,i.maxHour),i.minTime!==!1&&(b=new h._date,g=i.minTime.split(":"),b.setD(3,g[0]).setD(4,g[1]),b>j&&(j=b)),i.maxTime!==!1&&(b=new h._date,g=i.maxTime.split(":"),b.setD(3,g[0]).setD(4,g[1]),j>b&&(j=b)),i.maxYear!==!1&&(b=new h._date(i.maxYear,11,31),j>b&&(j=b)),i.minYear!==!1&&(b=new h._date(i.minYear,0,1),b>j&&(j=b)),"time"===i.mode.substr(0,4)||"dur"===i.mode.substr(0,3))"timeflipbox"===i.mode&&i.validHours!==!1&&a.inArray(j.get(3),i.validHours)<0&&(h.dateOK=!1);else{if(i.blackDatesRec!==!1)for(c=j.get(0),d=j.get(1),e=j.get(2),f=0;f<i.blackDatesRec.length;f++)-1!==i.blackDatesRec[f][0]&&i.blackDatesRec[f][0]!==c||-1!==i.blackDatesRec[f][1]&&i.blackDatesRec[f][1]!==d||-1!==i.blackDatesRec[f][2]&&i.blackDatesRec[f][2]!==e||(h.dateOK=!1);i.blackDates!==!1&&a.inArray(j.iso(),i.blackDates)>-1&&(h.dateOK=!1),i.blackDays!==!1&&a.inArray(j.getDay(),i.blackDays)>-1&&(h.dateOK=!1),i.whiteDates!==!1&&a.inArray(h.theDate.iso(),i.whiteDates)>-1&&(h.dateOK=!0,j=h.theDate)}h.theDate=j},_fixstepper:function(b){var c=this.options.durationSteppers,d=this.options.durationStep;a.inArray("d",b)>-1&&(c.d=d),a.inArray("h",b)>-1&&(c.d=1,c.h=d),a.inArray("i",b)>-1&&(c.h=1,c.i=d),a.inArray("s",b)>-1&&(c.i=1,c.s=d)},_parser:{"default":function(){return!1}},_makeDate:function(b){var c,d,e,f,g=this,h=this.options,i=this.options.defaultValue,j=g.__fmt(),k=null,l=[],m=new g._date,n={year:-1,mont:-1,date:-1,hour:-1,mins:-1,secs:-1,week:!1,wtyp:4,wday:!1,yday:!1,meri:0};if(b=a.trim(g.__("useArabicIndic")===!0&&"undefined"!=typeof b?g._dRep(b,-1):b),"undefined"==typeof h.mode)return m;if("undefined"!=typeof g._parser[h.mode])return g._parser[h.mode].apply(g,[b]);if("durationbox"===h.mode||"durationflipbox"===h.mode){if(j=j.replace(/%D([a-z])/gi,function(a,b){switch(b){case"d":case"l":case"M":case"S":return"("+a+"|[0-9]+)";default:return".+?"}}),j=new RegExp("^"+j+"$"),k=j.exec(b),e=j.exec(g.__fmt()),null===k||k.length!==e.length)return"number"==typeof i&&i>0?new g._date(1e3*(g.initDate.getEpoch()+parseInt(i,10))):new g._date(g.initDate.getTime());for(d=g.initDate.getEpoch(),c=1;c<k.length;c++)f=parseInt(k[c],10),e[c].match(/^%Dd$/i)&&(d+=86400*f),e[c].match(/^%Dl$/i)&&(d+=3600*f),e[c].match(/^%DM$/i)&&(d+=60*f),e[c].match(/^%DS$/i)&&(d+=f);return new g._date(1e3*d)}if("%J"===j)return m=new g._date(b),isNaN(m.getDate())&&(m=new g._date),m;if(j=j.replace(/%(0|-)*([a-z])/gi,function(a,b,c){switch(l.push(c),c){case"p":case"P":case"b":case"B":return"("+a+"|.+?)";case"H":case"k":case"I":case"l":case"m":case"M":case"S":case"V":case"U":case"u":case"W":case"d":return"("+a+"|[0-9]{"+("-"===b?"1,":"")+"2})";case"j":return"("+a+"|[0-9]{3})";case"s":return"("+a+"|[0-9]+)";case"g":case"y":return"("+a+"|[0-9]{2})";case"E":case"G":case"Y":return"("+a+"|[0-9]{1,4})";default:return l.pop(),".+?"}}),j=new RegExp("^"+j+"$"),k=j.exec(b),e=j.exec(g.__fmt()),null===k||k.length!==e.length){if(i!==!1&&""!==i)switch(typeof i){case"object":a.isFunction(i.getDay)?m=i:3===i.length&&(m=g._pa(i,"time"===h.mode.substr(0,4)?m:!1));break;case"number":m=new g._date(1e3*i);break;case"string":"time"===h.mode.substr(0,4)?(d=a.extend([0,0,0],i.split(":")).slice(0,3),m=g._pa(d,m)):(d=a.extend([0,0,0],i.split("-")).slice(0,3),d[1]--,m=g._pa(d,!1))}isNaN(m.getDate())&&(m=new g._date)}else{for(c=1;c<k.length;c++)switch(f=parseInt(k[c],10),l[c-1]){case"s":return new g._date(1e3*parseInt(k[c],10));case"Y":case"G":n.year=f;break;case"E":n.year=f-543;break;case"y":case"g":h.afterToday||f<h.twoDigitYearCutoff?n.year=2e3+f:n.year=1900+f;break;case"m":n.mont=f-1;break;case"d":n.date=f;break;case"H":case"k":case"I":case"l":n.hour=f;break;case"M":n.mins=f;break;case"S":n.secs=f;break;case"u":n.wday=f-1;break;case"w":n.wday=f;break;case"j":n.yday=f;break;case"V":n.week=f,n.wtyp=4;break;case"U":n.week=f,n.wtyp=0;break;case"W":n.week=f,n.wtyp=1;break;case"p":case"P":f=new RegExp("^"+k[c]+"$","i"),n.meri=f.test(g.__("meridiem")[0])?-1:1;break;case"b":d=a.inArray(k[c],g.__("monthsOfYearShort")),d>-1&&(n.mont=d);break;case"B":d=a.inArray(k[c],g.__("monthsOfYear")),d>-1&&(n.mont=d)}if(0!==n.meri&&(-1===n.meri&&12===n.hour&&(n.hour=0),1===n.meri&&12!==n.hour&&(n.hour=n.hour+12)),m=new g._date(g._n(n.year,0),g._n(n.mont,0),g._n(n.date,1),g._n(n.hour,0),g._n(n.mins,0),g._n(n.secs,0),0),n.year<100&&-1!==n.year&&m.setFullYear(n.year),n.mont>-1&&n.date>-1||n.hour>-1&&n.mins>-1&&n.secs>-1)return m;n.week!==!1&&(m.setDWeek(n.wtyp,n.week),n.date>-1&&m.setDate(n.date)),n.yday!==!1&&m.setD(1,0).setD(2,1).adj(2,n.yday-1),n.wday!==!1&&m.adj(2,n.wday-m.getDay())}return m},_event:function(b,c){var d,e=a(this).data("jtsage-datebox"),f=a(this).data("jtsage-datebox").options;if(!b.isPropagationStopped())switch(c.method){case"close":"undefined"==typeof c.closeCancel&&(c.closeCancel=!1),e.cancelClose=c.closeCancel,e.close();break;case"open":e.open();break;case"set":"object"==typeof c.value?(e.theDate=c.value,e._t({method:"doset"})):(a(this).val(c.value),f.linkedField!==!1&&a(f.linkedField).val(e.callFormat(f.linkedFieldFormat,e.theDate,!1)),a(this).trigger("change"));break;case"doset":d="_"+e.options.mode+"DoSet",a.isFunction(e[d])?e[d].apply(e,[]):e._t({method:"set",value:e._formatter(e.__fmt(),e.theDate),date:e.theDate});break;case"dooffset":c.type&&e._offset(c.type,c.amount,!0);break;case"dorefresh":e.refresh();break;case"doclear":a(this).val("").trigger("change");break;case"clear":a(this).trigger("change")}},_build:{"default":function(){this.d.headerText="Error",this.d.intHTML!==!1&&this.d.intHTML.remove().empty(),this.d.intHTML=a("<div class='ui-body-b'><h2 style='text-align:center' class='bg-danger'>Unknown Mode</h2></div>")},calbox:function(){var b,c,d,e,f,g,h,i,j,k,l,m,n,o,p=this,q=this.options,r=q.calDateList,s="ui-datebox-",t=p.calBackDate!==!1&&p.theDate.get(0)===p.calBackDate.get(0)&&p.theDate.get(1)===p.calBackDate.get(1)?new p._date(p.calBackDate.getTime()):p.theDate,u=!1,v={},w=p.initDate.copy(),x=p.initDate.copy(),y=(t.copy([0],[0,0,1]).getDay()-p.__("calStartDay")+7)%7,z=t.get(1),A=t.get(0),B=t.getArray(),C=""===p.d.input.val()?p._startOffset(p._makeDate(p.d.input.val())):p._makeDate(p.d.input.val()),D=-1,E=new p._date,F=E.getArray(),G=t.copy([0],[0,0,1]).adj(2,-1*y+(0===p.__("calStartDay")?1:0)).getDWeek(4),H=0,I=!1,J=!1,K=32-p.theDate.copy([0],[0,0,32,13]).getDate(),L=32-p.theDate.copy([0,-1],[0,0,32,13]).getDate(),M=q.afterToday||q.beforeToday||q.notToday||q.calAlwaysValidateDates||q.maxDays||q.minDays||q.blackDays||q.blackDates?!0:!1;if(p.calBackDate!==!1&&p.theDate.get(0)===p.calBackDate.get(0)&&p.theDate.get(1)===p.calBackDate.get(1)&&(p.theDate=new p._date(p.calBackDate.getTime()),p.calBackDate=!1),"boolean"!=typeof p.d.intHTML&&(p.d.intHTML.remove(),p.d.intHTML=null),p.d.headerText=p._grabLabel()!==!1?p._grabLabel():p.__("titleDateDialogLabel"),p.d.intHTML=a("<span>"),a(p._spf("<div class='{cl1}'><div class='{cl2}'><h4>{content}</h4></div></div>",{cl1:s+"gridheader",cl2:s+"gridlabel",content:p._formatter(p.__("calHeaderFormat"),p.theDate)})).appendTo(p.d.intHTML),p._cal_prev_next(p.d.intHTML.find("."+s+"gridheader")),q.calNoHeader&&(q.calUsePickersIcons?(p.d.intHTML.find("."+s+"gridlabel").hide(),p.d.intHTML.find("."+s+"gridplus").find(".ui-btn-inline").addClass(s+"nomargbtn"),p.d.intHTML.find("."+s+"gridminus").find(".ui-btn-inline").addClass(s+"nomargbtn")):p.d.intHTML.find("."+s+"gridheader").remove()),p.calNext=!0,p.calPrev=!0,Math.floor(E.comp()/100)===Math.floor(t.comp()/100)&&(I=!0),Math.floor(E.comp()/1e4)===Math.floor(t.comp()/1e4)&&(J=!0),C.comp()===t.comp()&&(D=C.get(2)),q.afterToday&&(I||J&&F[1]>=B[1])&&(p.calPrev=!1),q.beforeToday&&(I||J&&F[1]<=B[1])&&(p.calNext=!1),q.minDays!==!1&&(w.adj(2,-1*q.minDays),b=w.getArray(),B[0]===b[0]&&B[1]<=b[1]&&(p.calPrev=!1)),q.maxDays!==!1&&(x.adj(2,q.maxDays),b=x.getArray(),B[0]===b[0]&&B[1]>=b[1]&&(p.calNext=!1)),q.calUsePickers&&p._cal_pickers(z,A,F),c=a("<div class='"+s+"grid'>").appendTo(p.d.intHTML),q.calShowDays)for(p._cal_days=p.__("daysOfWeekShort").concat(p.__("daysOfWeekShort")),e=a("<div>",{"class":s+"gridrow"}).appendTo(c),q.calControlGroup&&e.addClass(s+"gridrow-last"),p.__("isRTL")===!0&&e.css("direction","rtl"),q.calShowWeek&&a("<div>").addClass(s+"griddate "+s+"griddate-label").appendTo(e),k=0;6>=k;k++)a("<div>").text(p._cal_days[(k+p.__("calStartDay"))%7]).addClass(s+"griddate "+s+"griddate-label").appendTo(e);for(v={i:w,x:x,t:E,p:D},d=p._cal_gen(y,L,K,!q.calOnlyMonth,t.get(1)),!a.isFunction(q.calFormatter)&&q.calFormatter!==!1&&a.isFunction(window[q.calFormatter])&&(q.calFormatter=window[q.calFormatter]),!a.isFunction(q.calBeforeAppendFunc)&&q.calBeforeAppendFunc!==!1&&a.isFunction(window[q.calBeforeAppendFunc])&&(q.calBeforeAppendFunc=window[q.calBeforeAppendFunc]),n=new Date(p.theDate.get(0),d[0][0][1],d[0][0][0],0,0,0,0),o=new Date(p.theDate.get(0),d[d.length-1][6][1],d[d.length-1][6][0],0,0,0,0),p.calBackDate===!1?p.calDateVisible=!0:q.calOnlyMonth?p.calDateVisible=!1:p.calBackDate.comp()<n.comp()||p.calBackDate.comp()>o.comp()?p.calDateVisible=!1:p.calDateVisible=!0,f=0,h=d.length;h>f;f++){for(j=a("<div>",{"class":s+"gridrow"}),p.__("isRTL")&&j.css("direction","rtl"),q.calShowWeek&&(a("<div>",{"class":s+"griddate "+s+"griddate-empty"}).text("W"+G).addClass("bootstrap"===p.baseMode?"pull-left":"").css(q.calControlGroup?{"float":"left"}:{}).appendTo(j),G++,G>52&&"undefined"!=typeof d[f+1]&&(G=new p._date(B[0],B[1],0===p.__("calStartDay")?d[f+1][1][0]:d[f+1][0][0],0,0,0,0).getDWeek(4))),g=0,i=d[f].length;i>g;g++)q.calWeekMode&&(H=d[f][q.calWeekModeDay][0]),"boolean"==typeof d[f][g]?a("<div>",{"class":s+"griddate "+s+"griddate-empty"}).appendTo(j):(u=p._cal_check(M,B[0],d[f][g][1],d[f][g][0],v),d[f][g][0]&&(a.isFunction(q.calFormatter)?(m={Year:d[f][g][1]>11?A+1:d[f][g][1]<0?A-1:A,Month:12===d[f][g][1]?0:-1===d[f][g][1]?11:d[f][g][1],Date:d[f][g][0]},m.Arr=[m.Year,p._zPad(m.Month+1),p._zPad(m.Date)],m.ISO=m.Arr.join("-"),m.Comp=m.Arr.join(""),m.curMonth=t.get(1),m.curYear=A,m.dateVisible=p.calDateVisible,b=q.calFormatter(m),l="object"!=typeof b?{text:b,"class":""}:{text:b.text,"class":b["class"]}):l={text:d[f][g][0],"class":""},q.calBeforeAppendFunc(a("<div>").html(l.text).addClass(s+"griddate").addClass(""+(z===d[f][g][1]||u.force?u.ok||"jqm"!==p.baseMode?q.btnCls+u.theme:q.btnCls+" "+u.theme:s+"griddate-empty"+("bootstrap"===p.baseMode?q.btnCls+"default":"")+(q.calOnlyMonth===!0?" "+q.disabledState:""))).addClass(l["class"]).css(z===d[f][g][1]||q.calOnlyMonth?{}:{cursor:"pointer"}).data("date",q.calWeekMode?H:d[f][g][0]).data("enabled",u.ok).data("month",d[f][q.calWeekMode?q.calWeekModeDay:g][1])).appendTo(j)));switch(p.baseMode){case"jqm":q.calControlGroup&&(j.find("."+s+"griddate-empty").addClass("ui-btn"),q.calOnlyMonth&&j.find("."+s+"griddate-empty").addClass("ui-state-disabled"),j.controlgroup({type:"horizontal"}));break;case"bootstrap":j.addClass("btn-group");break;case"jqueryui":j.find("."+s+"griddate").removeClass("ui-corner-all").not("."+s+"griddate-empty").first().addClass("ui-corner-left").end().last().addClass("ui-corner-right")}f===h-1&&j.addClass(s+"gridrow-last"),j.appendTo(c)}q.calShowWeek&&c.find("."+s+"griddate").addClass(s+"griddate-week"),q.calShowDateList&&r!==!1&&p._cal_date_list(c),(q.useTodayButton||q.useTomorrowButton||q.useClearButton||q.useCancelButton)&&(j=a("<div>",{"class":s+"controls"}),q.useTodayButton&&j.append(p._stdBtn.today.apply(p)),q.useTomorrowButton&&j.append(p._stdBtn.tomorrow.apply(p)),q.useClearButton&&j.append(p._stdBtn.clear.apply(p)),q.useCancelButton&&j.append(p._stdBtn.cancel.apply(p)),p._controlGroup(j).appendTo(c)),p.d.intHTML.on(q.clickEventAlt,"div."+s+"griddate",function(b){b.preventDefault(),a(this).data("enabled")&&(p.calBackDate=!1,
p.theDate.setD(2,1).setD(1,a(this).data("month")).setD(2,a(this).data("date")),p._t({method:"set",value:p._formatter(p.__fmt(),p.theDate),date:p.theDate}),p._t({method:"close"}))}),p.d.intHTML.on("swipeleft",function(){p.calNext&&(p.calBackDate===!1&&(p.calBackDate=new Date(p.theDate.getTime())),p._offset("m",1))}).on("swiperight",function(){p.calPrev&&(p.calBackDate===!1&&(p.calBackDate=new Date(p.theDate.getTime())),p._offset("m",-1))}),p.wheelExists&&p.d.intHTML.on("mousewheel",function(a,b){a.preventDefault(),b>0&&p.calNext&&(p.calBackDate===!1&&(p.calBackDate=new Date(p.theDate.getTime())),p.theDate.setD(2,1),p._offset("m",1)),0>b&&p.calPrev&&(p.calBackDate===!1&&(p.calBackDate=new Date(p.theDate.getTime())),p.theDate.setD(2,1),p._offset("m",-1))})},timebox:function(){this._build.datebox.apply(this,[])},durationbox:function(){this._build.datebox.apply(this,[])},datebox:function(){var b,c,d,e,f,g,h=this,i=this.drag,j=this.options,k="durationbox"===j.mode?!0:!1,l=0,m=["d","h","i","s"],n="ui-datebox-";for("boolean"!=typeof h.d.intHTML&&h.d.intHTML.empty().remove(),h.d.headerText=h._grabLabel()!==!1?h._grabLabel():"datebox"===j.mode?h.__("titleDateDialogLabel"):h.__("titleTimeDialogLabel"),h.d.intHTML=a("<span>"),h.fldOrder="datebox"===j.mode?h.__("dateFieldOrder"):k?h.__("durationOrder"):h.__("timeFieldOrder"),k?(h.dateOK=!0,h._fixstepper(h.fldOrder)):(h._check(),h._minStepFix(),h._dbox_vhour("undefined"!=typeof h._dbox_delta?h._dbox_delta:1)),"datebox"===j.mode&&a(h._spf("<div class='{cls}'><h4>{text}</h4></div>",{cls:n+"header",text:h._formatter(h.__("headerFormat"),h.theDate)})).appendTo(h.d.intHTML),e=a("<div>").addClass(n+"datebox-groups"),c=0;c<h.fldOrder.length;c++)f=a("<div>").addClass(n+"datebox-group"),"jqm"===h.baseMode&&f.addClass("ui-block-"+["a","b","c","d","e"][l]),b=k?j.durationSteppers[h.fldOrder[c]]:"i"===h.fldOrder[c]?j.minuteStep:1,("a"!==h.fldOrder[c]||12===h.__("timeFormat"))&&(h._dbox_button(1,h.fldOrder[c],b).appendTo(f),k&&a(h._spf("<div><label>{text}</label></div>",{text:h.__("durationLabel")[a.inArray(h.fldOrder[c],m)]})).addClass(n+"datebox-label ui-body-"+j.themeInput).appendTo(f),a("<div><input class='form-control w-100' type='text'></div>").addClass(function(){switch(h.baseMode){case"jqm":return"ui-input-text ui-body-"+j.themeInput+" ui-mini";case"bootstrap":case"bootstrap4":return j.themeInput;default:return null}}).appendTo(f).find("input").data({field:h.fldOrder[c],amount:b}),h._dbox_button(-1,h.fldOrder[c],b).appendTo(f),f.appendTo(e),l++);switch(h.baseMode){case"jqm":e.addClass("ui-grid-"+[0,0,"a","b","c","d","e"][l]);break;case"bootstrap":e.addClass("row"),e.find("."+n+"datebox-group").each(function(){a(this).addClass("col-xs-"+12/l)});break;case"bootstrap4":e.addClass("row"),e.find("."+n+"datebox-group").each(function(){a(this).addClass("px-0 col-sm-"+12/l)});break;case"jqueryui":e.find("."+n+"datebox-group").each(function(){a(this).css("width",100/l+"%")})}e.appendTo(h.d.intHTML),h.d.divIn=e,h._dbox_run_update(!0),(j.useSetButton||j.useClearButton||j.useCancelButton||j.useTodayButton||j.useTomorrowButton)&&(g=a("<div>",{"class":n+"controls"}),j.useSetButton&&(h.setBut=h._stdBtn.close.apply(h,["datebox"===j.mode?h.__("setDateButtonLabel"):k?h.__("setDurationButtonLabel"):h.__("setTimeButtonLabel")]),h.setBut.appendTo(g)),j.useTodayButton&&g.append(h._stdBtn.today.apply(h)),j.useTomorrowButton&&g.append(h._stdBtn.tomorrow.apply(h)),j.useClearButton&&g.append(h._stdBtn.clear.apply(h)),j.useCancelButton&&g.append(h._stdBtn.cancel.apply(h)),h._controlGroup(g).appendTo(h.d.intHTML)),j.repButton||h.d.intHTML.on(j.clickEvent,"."+n+"datebox-button",function(b){e.find(":focus").blur(),b.preventDefault(),h._dbox_delta=a(this).data("amount")>1?1:-1,h._offset(a(this).data("field"),a(this).data("amount"))}),e.on("change","input",function(){h._dbox_enter(a(this))}),e.on("keypress","input",function(b){13===b.which&&h.dateOK===!0&&(h._dbox_enter(a(this)),h._t({method:"set",value:h._formatter(h.__fmt(),h.theDate),date:h.theDate}),h._t({method:"close"}))}),h.wheelExists&&e.on("mousewheel","input",function(b,c){b.preventDefault(),h._dbox_delta=0>c?-1:1,h._offset(a(this).data("field"),(0>c?-1:1)*a(this).data("amount"))}),j.repButton&&(h.d.intHTML.on(i.eStart,"."+n+"datebox-button",function(b){b.preventDefault(),e.find(":focus").blur(),d=[a(this).data("field"),a(this).data("amount")],i.move=!0,i.cnt=0,h._dbox_delta=a(this).data("amount")>1?1:-1,h._offset(d[0],d[1],!1),h._dbox_run_update(),h.runButton||(i.target=d,h.runButton=setTimeout(function(){h._dbox_run()},500))}),h.d.intHTML.on(i.eEndA,"."+n+"datebox-button",function(a){i.move&&(a.preventDefault(),clearTimeout(h.runButton),h.runButton=!1,i.move=!1)}))},timeflipbox:function(){this._build.flipbox.apply(this)},durationflipbox:function(){this._build.flipbox.apply(this)},flipbox:function(){var b,c,d,e,f,g,h,i,j,k=this,l=this.options,m=this.drag,n={},o=["d","h","i","s"],p="durationflipbox"===l.mode?!0:!1,q="ui-datebox-",r=a("<div class='ui-overlay-shadow'><ul></ul></div>"),s=a("<div>",{"class":q+"flipcontent"}),t=k.theDate.getTime()-k.initDate.getTime(),u=""+("jqm"===k.baseMode?"ui-body-":"")+("bootstrap"===k.baseMode||"bootstrap4"===k.baseMode?"bg-":""),v=k._dur(0>t?0:t);if(0>t?(k.lastDuration=0,p&&k.theDate.setTime(k.initDate.getTime())):p&&(k.lastDuration=t/1e3),"boolean"!=typeof k.d.intHTML?k.d.intHTML.empty().remove():k.d.input.on("datebox",function(a,b){"postrefresh"===b.method&&k._fbox_pos()}),k.d.headerText=k._grabLabel()!==!1?k._grabLabel():"flipbox"===l.mode?k.__("titleDateDialogLabel"):k.__("titleTimeDialogLabel"),k.d.intHTML=a("<span>"),a(document).one("popupafteropen",function(){k._fbox_pos()}),k.fldOrder="flipbox"===l.mode?k.__("dateFieldOrder"):p?k.__("durationOrder"):k.__("timeFieldOrder"),p?(l.minDur!==!1&&k.theDate.getEpoch()-k.initDate.getEpoch()<l.minDur&&(k.theDate=new Date(k.initDate.getTime()+1e3*l.minDur),k.lastDuration=l.minDur,v=k._dur(1e3*l.minDur)),l.maxDur!==!1&&k.theDate.getEpoch()-k.initDate.getEpoch()>l.maxDur&&(k.theDate=new Date(k.initDate.getTime()+1e3*l.maxDur),k.lastDuration=l.maxDur,v=k._dur(1e3*l.maxDur))):(k._check(),k._minStepFix()),"flipbox"===l.mode&&a(k._spf("<div class='{cls}'><h4>{text}</h4></div>",{cls:q+"header",text:k._formatter(k.__("headerFormat"),k.theDate)})).appendTo(k.d.intHTML),p){for(k._fixstepper(k.fldOrder),e=a(k._spf("<div class='{cls}'></div>",{cls:q+"header ui-grid-"+["a","b","c","d","e"][k.fldOrder.length-2]+" row"})),c=0;c<k.fldOrder.length;c++)a(k._spf("<div class='{cls}'>{text}</div>",{text:k.__("durationLabel")[a.inArray(k.fldOrder[c],o)],cls:q+"fliplab ui-block-"+["a","b","c","d","e"][c]+" col-xs-"+12/k.fldOrder.length})).appendTo(e);for(e.appendTo(k.d.intHTML),k.dateOK=!0,n.d=k._fbox_series(v[0],64,"d",!1),n.h=k._fbox_series(v[1],64,"h",v[0]>0),n.i=k._fbox_series(v[2],60,"i",v[0]>0||v[1]>0),n.s=k._fbox_series(v[3],60,"s",v[0]>0||v[1]>0||v[2]>0),s.addClass(q+"flipcontentd"),c=0;c<k.fldOrder.length;c++){g=k.fldOrder[c],i=v[a.inArray(g,o)],d=r.clone().data({field:g,amount:l.durationSteppers[g]}),f=d.find("ul");for(b in n[g])a(k._spf("<li class='{cls}'><span>{text}</span></li>",{text:n[g][b][0],cls:u+(n[g][b][1]!==i?l.themeDate:l.themeDatePick)})).appendTo(f);d.appendTo(s)}}else 4===k.fldOrder.length&&s.addClass(q+"flipcontentd");for(c=0;c<k.fldOrder.length&&!p;c++){if(i=k.fldOrder[c],d=r.clone().data({field:i,amount:"i"===i?l.minuteStep:1}),f=d.find("ul"),"function"==typeof k._fbox_mktxt[i]){for(b=-1*l.flen[i];b<l.flen[i]+1;b++)a(k._spf("<li class='{cls}'><span>{text}</span></li>",{cls:u+(0!==b?l.themeDate:l.themeDatePick),text:k._fbox_mktxt[i].apply(k,["i"===i?b*l.minuteStep:b])})).appendTo(f);d.appendTo(s)}if("a"===i&&12===k.__("timeFormat")){for(j=a("<li class='"+u+l.themeDate+"'><span></span></li>"),e=k.theDate.get(3)>11?[l.themeDate,l.themeDatePick,2,5]:[l.themeDatePick,l.themeDate,2,3],b=-1*e[2];b<e[3];b++)0>b||b>1?j.clone().appendTo(f):a(k._spf("<li class='{cls}'><span>{text}</span></li>",{cls:u+e[b],text:k.__("meridiem")[b]})).appendTo(f);d.appendTo(s)}}k.d.intHTML.append(s),a("<div>",{"class":q+"flipcenter ui-overlay-shadow"}).css("pointerEvents","none").appendTo(k.d.intHTML),(l.useSetButton||l.useClearButton||l.useCancelButton||l.useTodayButton||l.useTomorrowButton)&&(h=a("<div>",{"class":q+"controls"}),l.useSetButton&&h.append(k._stdBtn.close.apply(k,["flipbox"===l.mode?k.__("setDateButtonLabel"):p?k.__("setDurationButtonLabel"):k.__("setTimeButtonLabel")])),l.useTodayButton&&h.append(k._stdBtn.today.apply(k)),l.useTomorrowButton&&h.append(k._stdBtn.tomorrow.apply(k)),l.useClearButton&&h.append(k._stdBtn.clear.apply(k)),l.useCancelButton&&h.append(k._stdBtn.cancel.apply(k)),k._controlGroup(h).appendTo(k.d.intHTML)),k.wheelExists&&k.d.intHTML.on("mousewheel",".ui-overlay-shadow",function(b,c){b.preventDefault(),k._offset(a(this).data("field"),(0>c?1:-1)*a(this).data("amount"))}),k.d.intHTML.on(m.eStart,"ul",function(b,c){m.move||("undefined"!=typeof c&&(b=c),m.move=!0,m.target=a(this).find("li").first(),m.pos=parseInt(m.target.css("marginTop").replace(/px/i,""),10),m.start="touch"===b.type.substr(0,5)?b.originalEvent.changedTouches[0].pageY:b.pageY,m.end=!1,m.direc=p?-1:1,m.velocity=0,m.time=Date.now(),b.stopPropagation(),b.preventDefault())})},slidebox:function(){var b,c,d,e,f,g,h=this,i=this.options,j=this.drag,k="ui-datebox-",l=a("<div class='"+k+"sliderow-int'></div>"),m=a("<div>"),n=a("<div>",{"class":k+"slide"});for("boolean"!=typeof h.d.intHTML?h.d.intHTML.remove().empty():h.d.input.on("datebox",function(a,b){"postrefresh"===b.method&&h._sbox_pos()}),h.d.headerText=h._grabLabel()!==!1?h._grabLabel():h.__("titleDateDialogLabel"),h.d.intHTML=a("<span class='"+k+"nopad'>"),h.fldOrder=h.__("slideFieldOrder"),h._check(),h._minStepFix(),a("<div class='"+k+"header'><h4>"+h._formatter(h.__("headerFormat"),h.theDate)+"</h4></div>").appendTo(h.d.intHTML),h.d.intHTML.append(n),c=0;c<h.fldOrder.length;c++)if(f=h.fldOrder[c],e=m.clone().addClass(k+"sliderow").data("rowtype",f),d=l.clone().data("rowtype",f).appendTo(e),h.__("isRTL")===!0&&d.css("direction","rtl"),"function"==typeof h._sbox_mktxt[f]){for(b=-1*i.slen[f];b<i.slen[f]+1;b++)g=h._sbox_mktxt[f].apply(h,[b]),a("<div>",{"class":k+"slidebox "+k+g[0]+i.btnCls+(0===b?i.themeDatePick:i.themeDate)}).html(g[1]).data("offset",b).appendTo(d);"bootstrap"===h.baseMode&&e.find(".btn-sm").removeClass("btn-sm").addClass("btn-xs"),e.appendTo(n)}(i.useSetButton||i.useClearButton||i.useCancelButton||i.useTodayButton||i.useTomorrowButton)&&(c=a("<div>",{"class":k+"controls "+k+"repad"}),i.useSetButton&&c.append(h._stdBtn.close.apply(h,[h.__("setDateButtonLabel")])),i.useTodayButton&&c.append(h._stdBtn.today.apply(h)),i.useTomorrowButton&&c.append(h._stdBtn.tomorrow.apply(h)),i.useClearButton&&c.append(h._stdBtn.clear.apply(h)),i.useCancelButton&&c.append(h._stdBtn.cancel.apply(h)),h._controlGroup(c).appendTo(h.d.intHTML)),h.wheelExists&&h.d.intHTML.on("mousewheel",".ui-datebox-sliderow-int",function(b,c){b.preventDefault(),h._offset(a(this).data("rowtype"),(0>c?-1:1)*("i"===a(this).data("rowtype")?i.minuteStep:1))}),h.d.intHTML.on(i.clickEvent,".ui-datebox-sliderow-int>div",function(b){b.preventDefault(),h._offset(a(this).parent().data("rowtype"),parseInt(a(this).data("offset"),10))}),h.d.intHTML.on(j.eStart,".ui-datebox-sliderow-int",function(b){j.move||(j.move=!0,j.target=a(this),j.pos=parseInt(j.target.css("marginLeft").replace(/px/i,""),10),j.start="touch"===b.type.substr(0,5)?b.originalEvent.changedTouches[0].pageX:b.pageX,j.end=!1,j.velocity=0,j.time=Date.now(),b.stopPropagation(),b.preventDefault())})}},_drag:{"default":function(){return!1},timeflipbox:function(){this._drag.flipbox.apply(this)},durationflipbox:function(){this._drag.flipbox.apply(this)},flipbox:function(){var b=this,c=this.options,d=this.drag;a(document).on(d.eMove,function(a){return d.move&&"flipbox"===c.mode.slice(-7)?(d.end="touch"===a.type.substr(0,5)?a.originalEvent.changedTouches[0].pageY:a.pageY,d.target.css("marginTop",d.pos+d.end-d.start+"px"),d.elapsed=Date.now()-d.time,d.velocity=.8*(100*(d.end-d.start)/(1+d.elapsed))+.2*d.velocity,a.preventDefault(),a.stopPropagation(),!1):void 0}),a(document).on(d.eEnd,function(a){var e,f,g,h,i,j,k;d.move&&"flipbox"===c.mode.slice(-7)&&(d.velocity<15&&d.velocity>-15||!c.useKinetic?(d.move=!1,d.end!==!1&&(a.preventDefault(),a.stopPropagation(),d.tmp=d.target.parent().parent(),b._offset(d.tmp.data("field"),parseInt((d.start-d.end)/(d.target.outerHeight()-2),10)*d.tmp.data("amount")*d.direc)),d.start=!1,d.end=!1):(d.move=!1,d.start=!1,d.end=!1,d.tmp=d.target.parent().parent(),e=d.target.outerHeight(),f=-(.8*d.velocity)*Math.exp(-d.elapsed/325)*8*-1,g=parseInt(d.target.css("marginTop").replace(/px/i,""),10),h=parseInt(g+f,10),i=d.pos-h,j=Math.round(i/e),k=j*d.tmp.data("amount")*d.direc,d.target.animate({marginTop:h},parseInt(1e4/d.velocity)+1e3,function(){b._offset(d.tmp.data("field"),k)}),a.preventDefault(),a.stopPropagation()))})},slidebox:function(){var b=this,c=this.options,d=this.drag;a(document).on(d.eMove,function(a){return d.move&&"slidebox"===c.mode?(d.end="touch"===a.type.substr(0,5)?a.originalEvent.changedTouches[0].pageX:a.pageX,d.target.css("marginLeft",d.pos+d.end-d.start+"px"),d.elapsed=Date.now()-d.time,d.velocity=.8*(100*(d.end-d.start)/(1+d.elapsed))+.2*d.velocity,a.preventDefault(),a.stopPropagation(),!1):void 0}),a(document).on(d.eEnd,function(a){var e,f,g,h,i,j,k;d.move&&"slidebox"===c.mode&&(d.velocity<15&&d.velocity>-15||!c.useKinetic?(d.move=!1,d.end!==!1&&(a.preventDefault(),a.stopPropagation(),d.tmp=d.target.find("div").first(),b._offset(d.target.data("rowtype"),(b.__("isRTL")?-1:1)*parseInt((d.start-d.end)/d.tmp.innerWidth(),10)*("i"===d.target.data("rowtype")?c.minuteStep:1))),d.start=!1,d.end=!1):(d.move=!1,d.start=!1,d.end=!1,d.tmp=d.target.find("div").first(),e=d.tmp.innerWidth(),f=-(.8*d.velocity)*Math.exp(-d.elapsed/325)*8*-1,g=parseInt(d.target.css("marginLeft").replace(/px/i,""),10),h=parseInt(g+f,10),i=d.pos-h,j=Math.round(i/e),k=j*("i"===d.target.data("rowtype")?c.minuteStep:1),d.target.animate({marginLeft:h},parseInt(1e4/d.velocity)+1e3,function(){b._offset(d.target.data("rowtype"),k)}),a.preventDefault(),a.stopPropagation()))})}},_offset:function(b,c,d){var e,f=this,g=this.options,h=this.theDate,i=!1,j=!1,k=32-f.theDate.copy([0],[0,0,32,13]).getDate(),l=!1;if(b=(b||"").toLowerCase(),l=a.inArray(b,["y","m","d","h","i","s"]),"undefined"==typeof d&&(d=!0),"a"===b||"undefined"!=typeof g.rolloverMode[b]&&g.rolloverMode[b]!==!0)switch(b){case"y":i=0;break;case"m":f._btwn(h.get(1)+c,-1,12)?i=1:(e=h.get(1)+c,j=0>e?[1,12+e]:[1,e%12]);break;case"d":f._btwn(h.get(2)+c,0,k+1)?i=2:(e=h.get(2)+c,j=1>e?[2,k+e]:[2,e%k]);break;case"h":f._btwn(h.get(3)+c,-1,24)?i=3:(e=h.get(3)+c,j=0>e?[3,24+e]:[3,e%24]);break;case"i":f._btwn(h.get(4)+c,-1,60)?i=4:(e=h.get(4)+c,j=0>e?[4,59+e]:[4,e%60]);break;case"s":f._btwn(h.get(5)+c,-1,60)?i=5:(e=h.get(5)+c,j=0>e?[5,59+e]:[5,e%60]);break;case"a":f._offset("h",12*(c>0?1:-1),!1)}else i=l;i!==!1?f.theDate.adj(i,c):f.theDate.setD(j[0],j[1]),d===!0&&f.refresh(),g.useImmediate&&f._t({method:"doset"}),f.calBackDate!==!1&&f._t({method:"displayChange",selectedDate:f.calBackDate,shownDate:f.theDate,thisChange:b,thisChangeAmount:c}),f._t({method:"offset",type:b,amount:c,newDate:f.theDate})},_startOffset:function(a){var b=this.options;return b.startOffsetYears!==!1&&a.adj(0,b.startOffsetYears),b.startOffsetMonths!==!1&&a.adj(1,b.startOffsetMonths),b.startOffsetDays!==!1&&a.adj(2,b.startOffsetDays),a},getTheDate:function(){return this.calBackDate!==!1?this.calBackDate:this.theDate},getLastDur:function(){return this.lastDuration},dateVisible:function(){return this.calDateVisible},setTheDate:function(a){"object"==typeof a?this.theDate=a:this.theDate=this._makeDate(a),this.refresh(),this._t({method:"doset"})},parseDate:function(a,b){var c,d=this;return d.fmtOver=a,c=d._makeDate(b),d.fmtOver=!1,c},callFormat:function(a,b,c){return"undefined"==typeof c&&(c=!1),this._formatter(a,b,c)},refresh:function(){var a=this,b=this.options;"undefined"==typeof a._build[b.mode]?a._build["default"].apply(a,[]):a._build[b.mode].apply(a,[]),a.__("useArabicIndic")===!0&&a._doIndic(),a.d.mainWrap.append(a.d.intHTML),a._t({method:"postrefresh"})},applyMinMax:function(a,b){var c,d,e,f,g=this,h=this.options,i=new this._date,j=864e5;c=g._pa([0,0,0],i),"undefined"==typeof a&&(a=!0),"undefined"==typeof b&&(b=!0),b!==!0&&h.minDays!==!1||"undefined"==typeof g.d.input.attr("min")||(d=g.d.input.attr("min").split("-"),e=new g._date(d[0],d[1]-1,d[2],0,0,0,0),f=(e.getTime()-c.getTime())/j,h.minDays=parseInt(-1*f,10)),b!==!0&&h.maxDays!==!1||"undefined"==typeof g.d.input.attr("max")||(d=g.d.input.attr("max").split("-"),e=new g._date(d[0],d[1]-1,d[2],0,0,0,0),f=(e.getTime()-c.getTime())/j,h.maxDays=parseInt(f,10)),a===!0&&g._t({method:"refresh"})},_dur:function(b){var c=[b/864e5,b/36e5%24,b/6e4%60,b/1e3%60];return a.each(c,function(a,b){0>b?c[a]=0:c[a]=Math.floor(b)}),c},__:function(a){var b=this.options,c=b.lang[b.useLang],d=b[b.mode+"lang"],e="override"+a.charAt(0).toUpperCase()+a.slice(1);return"undefined"!=typeof b[e]?b[e]:"undefined"!=typeof c&&"undefined"!=typeof c[a]?c[a]:"undefined"!=typeof d&&"undefined"!=typeof d[a]?d[a]:"undefined"!=typeof b.lang["default"][a]?b.lang["default"][a]:"Err:NotFound"},__fmt:function(){var a=this,b=this.options;if("undefined"!=typeof a.fmtOver&&a.fmtOver!==!1)return a.fmtOver;switch(b.mode){case"timebox":case"timeflipbox":return a.__("timeOutput");case"durationbox":case"durationflipbox":return a.__("durationFormat");default:return a.__("dateFormat")}},_zPad:function(a,b){return"undefined"!=typeof b&&"-"===b?String(a):(10>a?"0":"")+String(a)},_dRep:function(a,b){var c,d,e=48,f=57,g=1584,h="";for(-1===b&&(e+=g,f+=g,g=-1584),d=0;d<a.length;d++)c=a.charCodeAt(d),h+=c>=e&&f>=c?String.fromCharCode(c+g):String.fromCharCode(c);return h},_doIndic:function(){var b=this;b.d.intHTML.find("*").each(function(){a(this).children().length<1?a(this).text(b._dRep(a(this).text())):a(this).hasClass("ui-datebox-slideday")&&a(this).html(b._dRep(a(this).html()))}),b.d.intHTML.find("input").each(function(){a(this).val(b._dRep(a(this).val()))})},_n:function(a,b){return 0>a?b:a},_pa:function(a,b){return"boolean"==typeof b?new this._date(a[0],a[1],a[2],0,0,0,0):new this._date(b.get(0),b.get(1),b.get(2),a[0],a[1],a[2],0)},_btwn:function(a,b,c){return a>b&&c>a},_grabLabel:function(){var b,c,d=this,e=this.options,f=!1;return"undefined"==typeof e.overrideDialogLabel?(b=d.d.input.attr("placeholder"),c=d.d.input.attr("title"),"undefined"!=typeof b?b:"undefined"!=typeof c?c:(f=a(document).find("label[for='"+d.d.input.attr("id")+"']").text(),""===f?!1:f)):e.overrideDialogLabel},_t:function(a){this.d.input.trigger("datebox",a)},_spf:function(b,c){return a.isArray(c)||a.isPlainObject(c)?b.replace(/{(.+?)}/g,function(a,b){return c[b]}):b},_cal_gen:function(a,b,c,d,e){var f=0,g=0,h=1,i=1,j=[],k=[],l=!1;for(f=0;5>=f;f++)if(l===!1){for(k=[],g=0;6>=g;g++)0===f&&a>g?d===!0?k.push([b+(g-a)+1,e-1]):k.push(" "):f>3&&h>c?(d===!0?(k.push([i,e+1]),i++):k.push(" "),l=!0):(k.push([h,e]),h++,h>c&&(l=!0));j.push(k)}return j},_cal_check:function(b,c,d,e,f){var g,h=this,i=this.options,j=f.x,k=f.i,l=f.t,m=f.p,n=new this._date(c,d,e,12,0,0,0).getDay(),o=i.blackDatesRec,p=i.highDatesRec,q={ok:!0,iso:c+"-"+h._zPad(d+1)+"-"+h._zPad(e),theme:i.themeDate,force:!1,recok:!0,rectheme:!1};if(12===d&&(q.iso=c+1+"-01-"+h._zPad(e)),-1===d&&(q.iso=c-1+"-12-"+h._zPad(e)),q.comp=parseInt(q.iso.replace(/-/g,""),10),o!==!1)for(g=0;g<o.length;g++)-1!==o[g][0]&&o[g][0]!==c||-1!==o[g][1]&&o[g][1]!==d||-1!==o[g][2]&&o[g][2]!==e||(q.ok=!1);if(a.isArray(i.enableDates)&&a.inArray(q.iso,i.enableDates)<0?q.ok=!1:b&&(q.recok!==!0||i.afterToday&&l.comp()>q.comp||i.beforeToday&&l.comp()<q.comp||i.notToday&&l.comp()===q.comp||i.maxDays!==!1&&j.comp()<q.comp||i.minDays!==!1&&k.comp()>q.comp||a.isArray(i.blackDays)&&a.inArray(n,i.blackDays)>-1||a.isArray(i.blackDates)&&a.inArray(q.iso,i.blackDates)>-1)&&(q.ok=!1),a.isArray(i.whiteDates)&&a.inArray(q.iso,i.whiteDates)>-1&&(q.ok=!0),q.ok){if(p!==!1)for(g=0;g<p.length;g++)-1!==p[g][0]&&p[g][0]!==c||-1!==p[g][1]&&p[g][1]!==d||-1!==p[g][2]&&p[g][2]!==e||(q.rectheme=!0);!i.calHighPick||e!==m||""===h.d.input.val()&&i.defaultValue===!1?i.calHighToday&&q.comp===l.comp()?q.theme=i.themeDateToday:i.calHighPick&&h.calDateVisible&&h.calBackDate!==!1&&h.calBackDate.comp()===q.comp?(q.theme=i.themeDatePick,q.force=!0):a.isArray(i.highDatesAlt)&&a.inArray(q.iso,i.highDatesAlt)>-1?q.theme=i.themeDateHighAlt:a.isArray(i.highDates)&&a.inArray(q.iso,i.highDates)>-1?q.theme=i.themeDateHigh:a.isArray(i.highDays)&&a.inArray(n,i.highDays)>-1?q.theme=i.themeDayHigh:a.isArray(i.highDatesRec)&&q.rectheme===!0&&(q.theme=i.themeDateHighRec):q.theme=i.themeDatePick}else q.theme=i.disabledState;return q},_cal_prev_next:function(b){var c=this,d=this.options,e="ui-datebox-";a(c._spf("<div class='{class}'><a href='#'>{name}</a></div>",{"class":e+"gridplus"+(c.__("isRTL")?"-rtl":""),name:c._spf(d.s.cal.nextMonth,{text:c.__("nextMonth"),icon:d.calNextMonthIcon})})).prependTo(b).find("a").addClass(function(){switch(c.baseMode){case"jqm":return d.btnCls+d.themeDate+d.icnCls+d.calNextMonthIcon;case"bootstrap":case"bootstrap4":return d.btnCls+d.themeDate+" pull-"+(c.__("isRTL")?"left":"right");default:return null}}).on(d.clickEventAlt,function(a){return a.preventDefault(),a.stopPropagation(),c.calNext&&(c.calBackDate===!1&&(c.calBackDate=new Date(c.theDate.getTime())),c.theDate.getDate()>28&&c.theDate.setDate(1),c._offset("m",1)),!1}),a(c._spf("<div class='{class}'><a href='#'>{name}</a></div>",{"class":e+"gridminus"+(c.__("isRTL")?"-rtl":""),name:c._spf(d.s.cal.prevMonth,{text:c.__("prevMonth"),icon:d.calPrevMonthIcon})})).prependTo(b).find("a").addClass(function(){switch(c.baseMode){case"jqm":return d.btnCls+d.themeDate+d.icnCls+d.calPrevMonthIcon;case"bootstrap":case"bootstrap4":return d.btnCls+d.themeDate+" pull-"+(c.__("isRTL")?"right":"left");default:return null}}).on(d.clickEventAlt,function(a){return a.preventDefault(),a.stopPropagation(),c.calPrev&&(c.calBackDate===!1&&(c.calBackDate=new Date(c.theDate.getTime())),c.theDate.getDate()>28&&c.theDate.setDate(1),c._offset("m",-1)),!1})},_cal_pickers:function(b,c,d){var e,f,g,h=this,i=this.options,j="ui-datebox-",k=(new Date).get(0),l=a("<div>").addClass("ui-datebox-cal-pickers");for(i.calNoHeader&&i.calUsePickersIcons&&l.addClass("ui-datebox-pickicon"),l.i=a("<fieldset>").appendTo(l),l.a=a("<select>").appendTo(l.i),l.b=a("<select>").appendTo(l.i),g=0;11>=g;g++)l.a.append(a("<option value='"+g+"'"+(b===g?" selected='selected'":"")+">"+h.__("monthsOfYear")[g]+"</option>"));for(e=i.calYearPickMin<1?(i.calYearPickRelative?c:k)+i.calYearPickMin:i.calYearPickMin<1800?(i.calYearPickRelative?c:k)-i.calYearPickMin:"NOW"===i.calYearPickMin?d[0]:i.calYearPickMin,f=i.calYearPickMax<1800?(i.calYearPickRelative?c:k)+i.calYearPickMax:"NOW"===i.calYearPickMax?d[0]:i.calYearPickMax,g=e;f>=g;g++)l.b.append(a("<option value='"+g+"'"+(c===g?" selected='selected'":"")+">"+g+"</option>"));switch(l.a.on("change",function(){h.calBackDate===!1&&(h.calBackDate=new Date(h.theDate.getTime())),h.theDate.setD(1,a(this).val()),h.theDate.get(1)!==parseInt(a(this).val(),10)&&h.theDate.setD(2,0),h.calBackDate!==!1&&h._t({method:"displayChange",selectedDate:h.calBackDate,shownDate:h.theDate,thisChange:"p",thisChangeAmount:null}),h.refresh()}),l.b.on("change",function(){h.calBackDate===!1&&(h.calBackDate=new Date(h.theDate.getTime())),h.theDate.setD(0,a(this).val()),h.theDate.get(1)!==parseInt(l.a.val(),10)&&h.theDate.setD(2,0),h.calBackDate!==!1&&h._t({method:"displayChange",selectedDate:h.calBackDate,shownDate:h.theDate,thisChange:"p",thisChangeAmount:null}),h.refresh()}),h.baseMode){case"bootstrap":case"jqueryui":l.i.find("select").addClass("form-control input-sm").css({marginTop:"3px","float":"left"}).first().css({width:"60%"}).end().last().css({width:"40%"}),i.calNoHeader&&i.calUsePickersIcons?h.d.intHTML.find("."+j+"gridheader").append(l):l.appendTo(h.d.intHTML);break;case"bootstrap4":l.i.find("select").addClass("form-control form-control-sm input-sm").css({marginTop:"3px","float":"left",height:"auto"}).first().css({width:"60%"}).end().last().css({width:"40%"}),i.calNoHeader&&i.calUsePickersIcons?h.d.intHTML.find("."+j+"gridheader").append(l):l.appendTo(h.d.intHTML);break;case"jqm":l.i.controlgroup({mini:!0,type:"horizontal"}),l.i.find("select").selectmenu({nativeMenu:!0}),l.i.find(".ui-controlgroup-controls").css({marginRight:"auto",marginLeft:"auto",width:"100%",display:"table"}),l.i.find(".ui-select").first().css({width:"60%"}).end().last().css({width:"40%"}),i.calNoHeader&&i.calUsePickersIcons&&l.i.css({padding:"0 10px 5px 10px"}),l.appendTo(h.d.intHTML)}},_cal_date_list:function(b){var c,d,e=this,f=this.options,g=a("<div>").addClass("ui-datebox-pickcontrol");for(g.a=a("<select name='pickdate'></select>").appendTo(g),g.a.append("<option value='false' selected='selected'>"+e.__("calDateListLabel")+"</option>"),c=0;c<f.calDateList.length;c++)g.a.append(a(e._spf("<option value='{0}'>{1}</option>",f.calDateList[c])));switch(g.a.on("change",function(){d=a(this).val().split("-"),e.theDate=new e._date(d[0],d[1]-1,d[2],0,0,0,0),e._t({method:"doset"})}),e.baseMode){case"jqm":g.find("select").selectmenu({mini:!0,nativeMenu:!0});break;case"bootstrap":case"bootstrap4":g.find("select").addClass("form-control input-sm")}g.appendTo(b)},_dbox_run:function(){var a=this,b=this.drag,c=parseInt(6.09+142.8*Math.pow(Math.E,-.039*b.cnt),10);b.didRun=!0,b.cnt++,a._offset(b.target[0],b.target[1],!1),a._dbox_run_update(),a.runButton=setTimeout(function(){a._dbox_run()},c)},_dbox_run_update:function(b){var c=this,d=this.options,e=c.theDate.getTime()-c.initDate.getTime(),f="durationbox"===d.mode?!0:!1,g=c._dur(0>e?0:e);0>e&&(c.lastDuration=0,f&&c.theDate.setTime(c.initDate.getTime())),f&&(c.lastDuration=e/1e3,d.minDur!==!1&&c.theDate.getEpoch()-c.initDate.getEpoch()<d.minDur&&(c.theDate=new Date(c.initDate.getTime()+1e3*d.minDur),c.lastDuration=d.minDur,g=c._dur(1e3*d.minDur)),d.maxDur!==!1&&c.theDate.getEpoch()-c.initDate.getEpoch()>d.maxDur&&(c.theDate=new Date(c.initDate.getTime()+1e3*d.maxDur),c.lastDuration=d.maxDur,g=c._dur(1e3*d.maxDur))),b!==!0&&f!==!0&&(c._check(),"datebox"===d.mode&&c.d.intHTML.find(".ui-datebox-header").find("h4").text(c._formatter(c.__("headerFormat"),c.theDate)),d.useSetButton&&(c.dateOK===!1?c.setBut.addClass(d.disabledState):c.setBut.removeClass(d.disabledState))),c.d.divIn.find("input").each(function(){switch(a(this).data("field")){case"y":a(this).val(c.theDate.get(0));break;case"m":a(this).val(c.theDate.get(1)+1);break;case"d":a(this).val(f?g[0]:c.theDate.get(2));break;case"h":f?a(this).val(g[1]):12===c.__("timeFormat")?a(this).val(c.theDate.get12hr()):a(this).val(c.theDate.get(3));break;case"i":f?a(this).val(g[2]):a(this).val(c._zPad(c.theDate.get(4)));break;case"M":a(this).val(c.__("monthsOfYearShort")[c.theDate.get(1)]);break;case"a":a(this).val(c.__("meridiem")[c.theDate.get(3)>11?1:0]);break;case"s":f?a(this).val(g[3]):a(this).val(c._zPad(c.theDate.get(5)))}}),c.__("useArabicIndic")===!0&&c._doIndic()},_dbox_vhour:function(b){var c,d=this,e=this.options,f=[25,0],g=[25,0];return e.validHours===!1?!0:a.inArray(d.theDate.getHours(),e.validHours)>-1?!0:(c=d.theDate.getHours(),a.each(e.validHours,function(){(this>c?1:-1)===b?f[0]>Math.abs(this-c)&&(f=[Math.abs(this-c),parseInt(this,10)]):g[0]>Math.abs(this-c)&&(g=[Math.abs(this-c),parseInt(this,10)])}),void(0!==f[1]?d.theDate.setHours(f[1]):d.theDate.setHours(g[1])))},_dbox_enter:function(b){var c,d=this,e=0;if("M"===b.data("field")&&(c=a.inArray(b.val(),d.__("monthsOfYearShort")),c>-1&&d.theDate.setMonth(c)),""!==b.val()&&0===b.val().toString().search(/^[0-9]+$/))switch(b.data("field")){case"y":d.theDate.setD(0,parseInt(b.val(),10));break;case"m":d.theDate.setD(1,parseInt(b.val(),10)-1);break;case"d":d.theDate.setD(2,parseInt(b.val(),10)),e+=86400*parseInt(b.val(),10);break;case"h":d.theDate.setD(3,parseInt(b.val(),10)),e+=3600*parseInt(b.val(),10);break;case"i":d.theDate.setD(4,parseInt(b.val(),10)),e+=60*parseInt(b.val(),10);break;case"s":d.theDate.setD(5,parseInt(b.val(),10)),e+=parseInt(b.val(),10)}"durationbox"===this.options.mode&&d.theDate.setTime(d.initDate.getTime()+1e3*e),setTimeout(function(){d.refresh()},150)},_dbox_button:function(b,c,d){var e=this,f=this.options;return a("<div>").addClass("ui-datebox-datebox-button").addClass(f.btnCls+f.themeButton).addClass(function(){switch(e.baseMode){case"jqm":case"bootstrap":case"bootstrap4":return f.icnCls+(b>0?f.calNextMonthIcon:f.calPrevMonthIcon);default:return null}}).data({field:c,amount:d*b}).append(function(){switch(e.baseMode){case"jqueryui":return a("<span>").addClass(f.icnCls+(b>0?f.calNextMonthIcon:f.calPrevMonthIcon));default:return null}})},_fbox_pos:function(){var b,c,d,e=0,f=this,g="bootstrap4"===f.baseMode?5:0,h=this.d.intHTML.find(".ui-datebox-flipcontent").innerHeight();f.d.intHTML.find(".ui-datebox-flipcenter").each(function(){c=a(this),e=-1*(h/2-c.innerHeight()/2-3)+g,c.css("top",e)}),f.d.intHTML.find("ul").each(function(){c=a(this),h=c.parent().innerHeight(),d=c.find("li").first(),b=c.find("li").last().offset().top-c.find("li").first().offset().top,d.css("marginTop",-1*((b-h)/2+d.outerHeight()))})},_fbox_series:function(a,b,c,d){for(var e,f,g=this.options,h="h"===c?24:60,i=[[a.toString(),a]],j=1;b>=j;j++)e=a+j*g.durationSteppers[c],f=a-j*g.durationSteppers[c],i.unshift([e.toString(),e]),f>-1?i.push([f.toString(),f]):d?i.push([(h+f).toString(),f]):i.push(["",-1]);return i},_fbox_mktxt:{y:function(a){return this.theDate.get(0)+a},m:function(a){var b=this.theDate.copy([0],[0,0,1]).adj(1,a);return this.__("monthsOfYearShort")[b.get(1)]},d:function(a){var b=this,c=this.options;if(c.rolloverMode===!1||"undefined"!=typeof c.rolloverMode.d&&c.rolloverMode.d===!1){var d=this.theDate.get(2),e=32-b.theDate.copy([0],[0,0,32,13]).getDate(),f=d+a;return 1>f?e+f:f%e>0?f%e:e}return this.theDate.copy([0,0,a]).get(2)},h:function(a){var b=this.theDate.copy([0,0,0,a]);return 12===this.__("timeFormat")?b.get12hr():b.get(3)},i:function(a){return this._zPad(this.theDate.copy([0,0,0,0,a]).get(4))},s:function(a){return this._zPad(this.theDate.copy([0,0,0,0,0,a]).get(5))}},_sbox_pos:function(){var b,c,d,e,f,g=this;g.d.intHTML.find("div.ui-datebox-sliderow-int").each(function(){c=a(this),e=c.parent().outerWidth(),b=c.outerWidth(),d=g.__("isRTL")?c.find("div").last():c.find("div").first(),f=c.find("div").length*d.outerWidth(),b>0&&(f=b),d.css("marginLeft",(f-e)/2*-1)})},_sbox_mktxt:{y:function(a){return["slideyear",this.theDate.get(0)+a]},m:function(a){var b=this.theDate.copy([0],[0,0,1]).adj(1,a);return["slidemonth",this.__("monthsOfYearShort")[b.get(1)]]},d:function(a){var b=this.theDate.copy([0,0,a]);return["slideday",b.get(2)+"<br /><span class='ui-datebox-slidewday'>"+this.__("daysOfWeekShort")[b.getDay()]+"</span>"]},h:function(a){var b=this.theDate.copy([0,0,0,a]);return["slidehour",12===this.__("timeFormat")?this._formatter("%-I<span class='ui-datebox-slidewday'>%p</span>",b):b.get(3)]},i:function(a){return["slidemins",this._zPad(this.theDate.copy([0,0,0,0,a]).get(4))]}}})}(jQuery),function(a){a(document).ready(function(){a("[data-role='datebox']").each(function(){a(this).datebox()})})}(jQuery);


/*
 * JTSage-DateBox-4.2.2
 * For: {"jqm":"1.4.5","bootstrap":"3.3.7"}
 * Date: Tue Jun 20 2017 22:49:58 UTC
 * http://dev.jtsage.com/DateBox/
 * https://github.com/jtsage/jquery-mobile-datebox
 *
 * Copyright 2010, 2017 JTSage. and other contributors
 * Released under the MIT license.
 * https://github.com/jtsage/jquery-mobile-datebox/blob/master/LICENSE.txt
 *
 */
jQuery.extend(jQuery.jtsage.datebox.prototype.options.lang, {
	"af": {
		"setDateButtonLabel": "Selekteer",
		"setTimeButtonLabel": "Selekteer",
		"setDurationButtonLabel": "Selekteer",
		"todayButtonLabel": "Vandag",
		"titleDateDialogLabel": "Kies Datum",
		"titleTimeDialogLabel": "Kies Tyd",
		"daysOfWeek": [
			"Sondag",
			"Maandag",
			"Dinsdag",
			"Woensdag",
			"Donderdag",
			"Vrydag",
			"Saterdag"
		],
		"daysOfWeekShort": [
			"Son",
			"Maa",
			"Din",
			"Woe",
			"Don",
			"Vry",
			"Sat"
		],
		"monthsOfYear": [
			"Januarie",
			"Februarie",
			"Maart",
			"April",
			"Mei",
			"Junie",
			"Julie",
			"Augustus",
			"September",
			"Oktober",
			"November",
			"Desember"
		],
		"monthsOfYearShort": [
			"Jan",
			"Feb",
			"Mrt",
			"Apr",
			"Mei",
			"Jun",
			"Jul",
			"Aug",
			"Sep",
			"Okt",
			"Nov",
			"Des"
		],
		"durationLabel": [
			"Dae",
			"Ure",
			"Minute",
			"Sekondes"
		],
		"durationDays": [
			"Dag",
			"Dae"
		],
		"tooltip": "Maak Datumselekteerder",
		"nextMonth": "Volgende",
		"prevMonth": "Vorige",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d/%m/%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Duidelik",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Ander Datums",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Spring na Môre"
	},
	"ar": {
		"setDateButtonLabel": "تعيين تاريخ",
		"setTimeButtonLabel": "ضبط الوقت",
		"setDurationButtonLabel": "تعيين المدة",
		"todayButtonLabel": "القفز إلى اليوم",
		"titleDateDialogLabel": "اختر التاريخ",
		"titleTimeDialogLabel": "انتخاب زمان",
		"daysOfWeek": [
			"الأحد",
			"دوشنبه",
			"الثلاثاء",
			"الاربعاء",
			"الخميس",
			"الجمعة",
			"السبت"
		],
		"daysOfWeekShort": [
			"الأحد",
			"الاثنين",
			"الثلاثاء",
			"الاربعاء",
			"الخميس",
			"الجمعة",
			"السبت"
		],
		"monthsOfYear": [
			"يناير",
			"فبراير",
			"مارس",
			"إبريل",
			"مايو",
			"يونية",
			"يولية",
			"أغسطس",
			"سبتمبر",
			"أكتوبر",
			"نوفمبر",
			"ديسمبر"
		],
		"monthsOfYearShort": [
			"1",
			"2",
			"3",
			"4",
			"5",
			"6",
			"7",
			"8",
			"9",
			"10",
			"11",
			"12"
		],
		"durationLabel": [
			"أيام",
			"ساعة",
			"دقيقة",
			"ثانية"
		],
		"durationDays": [
			"يوم",
			"أيام"
		],
		"tooltip": "فتح منتقي التاريخ",
		"nextMonth": "التالي",
		"prevMonth": "السابق",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d/%m/%Y",
		"useArabicIndic": true,
		"isRTL": true,
		"calStartDay": 0,
		"clearButton": "واضحة",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "تواريخ أخرى",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "القفز إلى الغد"
	},
	"bg": {
		"setDateButtonLabel": "Запази Датата",
		"setTimeButtonLabel": "Запази Часът",
		"setDurationButtonLabel": "Запази Времето",
		"todayButtonLabel": "Обратно до Днес",
		"titleDateDialogLabel": "Избери Дата",
		"titleTimeDialogLabel": "Избери Час",
		"daysOfWeek": [
			"Неделя",
			"Понеделник",
			"Вторник",
			"Сряда",
			"Четвъртък",
			"Петък",
			"Събота"
		],
		"daysOfWeekShort": [
			"Нд",
			"Пн",
			"Вт",
			"Ср",
			"Чт",
			"Пт",
			"Сб"
		],
		"monthsOfYear": [
			"Януари",
			"Февруари",
			"Март",
			"Април",
			"Май",
			"Юни",
			"Юли",
			"Август",
			"Септември",
			"Октомври",
			"Ноември",
			"Декември"
		],
		"monthsOfYearShort": [
			"Яну",
			"Фев",
			"Мар",
			"Апр",
			"Май",
			"Юни",
			"Юли",
			"Авг",
			"Сеп",
			"Окт",
			"Ное",
			"Дек"
		],
		"durationLabel": [
			"Дни",
			"Часове",
			"Минути",
			"Секунди"
		],
		"durationDays": [
			"Ден",
			"Дни"
		],
		"tooltip": "Заредете Прозореца за Дата",
		"nextMonth": "Следващ Месец",
		"prevMonth": "Предишен Месец",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d.%-m.%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 1,
		"clearButton": "Изчисти",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Други дати",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Направо към утре"
	},
	"ca": {
		"setDateButtonLabel": "Tanca",
		"setTimeButtonLabel": "Tanca",
		"setDurationButtonLabel": "Tanca",
		"todayButtonLabel": "Vés a avui",
		"titleDateDialogLabel": "Tria la data",
		"titleTimeDialogLabel": "Esculli temps",
		"daysOfWeek": [
			"Diumenge",
			"Dilluns",
			"Dimarts",
			"Dimecres",
			"Dijous",
			"Divendres",
			"Dissabte"
		],
		"daysOfWeekShort": [
			"Dg",
			"Dl",
			"Dt",
			"Dc",
			"Dj",
			"Dv",
			"Ds"
		],
		"monthsOfYear": [
			"Gener",
			"Febrer",
			"Març",
			"Abril",
			"Maig",
			"Juny",
			"Juliol",
			"Agost",
			"Setembre",
			"Octubre",
			"Novembre",
			"Desembre"
		],
		"monthsOfYearShort": [
			"Gen",
			"Feb",
			"Març",
			"Abr",
			"Maig",
			"Juny",
			"Jul",
			"Ag",
			"Set",
			"Oct",
			"Nov",
			"Des"
		],
		"durationLabel": [
			"Dies",
			"Hores",
			"Minuts",
			"Segons"
		],
		"durationDays": [
			"Dia",
			"Dies"
		],
		"tooltip": "Selector de la data obert",
		"nextMonth": "Següent",
		"prevMonth": "Anterior",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d/%m/%y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Clar",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Altres Dates",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Saltar per demà"
	},
	"cs": {
		"setDateButtonLabel": "Nastavit datum",
		"setTimeButtonLabel": "Nastavit čas",
		"setDurationButtonLabel": "Nastavit dobu trvání",
		"todayButtonLabel": "Nyní",
		"titleDateDialogLabel": "Zvolte datum",
		"titleTimeDialogLabel": "Zvolte čas",
		"daysOfWeek": [
			"Neděle",
			"Pondělí",
			"Úterý",
			"Středa",
			"Čtvrtek",
			"Pátek",
			"Sobota"
		],
		"daysOfWeekShort": [
			"Ne",
			"Po",
			"Út",
			"St",
			"Čt",
			"Pá",
			"So"
		],
		"monthsOfYear": [
			"Leden",
			"Únor",
			"Březen",
			"Duben",
			"Květen",
			"Červen",
			"Červenec",
			"Srpen",
			"Září",
			"Říjen",
			"Listopad",
			"Prosinec"
		],
		"monthsOfYearShort": [
			"Led",
			"Úno",
			"Bře",
			"Dub",
			"Kvě",
			"Čer",
			"Čvc",
			"Srp",
			"Zář",
			"Říj",
			"Lis",
			"Pro"
		],
		"durationLabel": [
			"Dny",
			"Hodin",
			"Minut",
			"Sekundy"
		],
		"durationDays": [
			"Den",
			"Dny"
		],
		"tooltip": "Otevřít výběr data",
		"nextMonth": "Později",
		"prevMonth": "Dříve",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d.%m.%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 1,
		"clearButton": "Vymazat",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Další termíny",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Přejít na zítřek"
	},
	"da": {
		"setDateButtonLabel": "Angiv dato",
		"setTimeButtonLabel": "Angiv tid",
		"setDurationButtonLabel": "Angiv varighed",
		"todayButtonLabel": "Gå til i dag",
		"titleDateDialogLabel": "Vælg dato",
		"titleTimeDialogLabel": "Vælg tid",
		"daysOfWeek": [
			"Søndag",
			"Mandag",
			"Tirsdag",
			"Onsdag",
			"Torsdag",
			"Fredag",
			"Lørdag"
		],
		"daysOfWeekShort": [
			"Søn",
			"Man",
			"Tir",
			"Ons",
			"Tor",
			"Fre",
			"Lør"
		],
		"monthsOfYear": [
			"Januar",
			"Februar",
			"Marts",
			"April",
			"Maj",
			"Juni",
			"Juli",
			"August",
			"September",
			"Oktober",
			"November",
			"December"
		],
		"monthsOfYearShort": [
			"Jan",
			"Feb",
			"Mar",
			"Apr",
			"Maj",
			"Jun",
			"Jul",
			"Aug",
			"Sep",
			"Okt",
			"Nov",
			"Dec"
		],
		"durationLabel": [
			"Dage",
			"Timer",
			"Minutter",
			"Sekund"
		],
		"durationDays": [
			"Dag",
			"Dage"
		],
		"tooltip": "﻿Åbn datovælger",
		"nextMonth": "Næste måned",
		"prevMonth": "Forrige måned",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d-%m-%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Nulstil",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Andre datoer",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Springe til i morgen"
	},
	"de": {
		"setDateButtonLabel": "Datum einstellen",
		"setTimeButtonLabel": "Zeit einstellen",
		"setDurationButtonLabel": "Dauer einstellen",
		"todayButtonLabel": "heute",
		"titleDateDialogLabel": "Datum wählen",
		"titleTimeDialogLabel": "Zeit wählen",
		"daysOfWeek": [
			"Sonntag",
			"Montag",
			"Dienstag",
			"Mittwoch",
			"Donnerstag",
			"Freitag",
			"Samstag"
		],
		"daysOfWeekShort": [
			"So",
			"Mo",
			"Di",
			"Mi",
			"Do",
			"Fr",
			"Sa"
		],
		"monthsOfYear": [
			"Januar",
			"Februar",
			"März",
			"April",
			"Mai",
			"Juni",
			"Juli",
			"August",
			"September",
			"Oktober",
			"November",
			"Dezember"
		],
		"monthsOfYearShort": [
			"Jan",
			"Feb",
			"Mär",
			"Apr",
			"Mai",
			"Jun",
			"Jul",
			"Aug",
			"Sep",
			"Okt",
			"Nov",
			"Dez"
		],
		"durationLabel": [
			"Tage",
			"Stunden",
			"Minuten",
			"Sekunden"
		],
		"durationDays": [
			"Tag",
			"Tage"
		],
		"tooltip": "Öffne Datumsauswahl",
		"nextMonth": "Nächster Monat",
		"prevMonth": "Vorheriger Monat",
		"timeFormat": 24,
		"headerFormat": "%A %d. %B %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d.%m.%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 1,
		"clearButton": "löschen",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Weitere Termine",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Springen bis morgen"
	},
	"el": {
		"setDateButtonLabel": "Καθορισμός Ημερομηνίας",
		"setTimeButtonLabel": "Καθορισμός Ώρας",
		"setDurationButtonLabel": "Καθορισμός Διάρκειας",
		"todayButtonLabel": "Μετάβαση στη σημερινή",
		"titleDateDialogLabel": "Επιλέξτε ημερομηνία",
		"titleTimeDialogLabel": "Επιλέξτε την ώρα",
		"daysOfWeek": [
			"Κυριακή",
			"Δευτέρα",
			"Τρίτη",
			"Τετάρτη",
			"Πέμπτη",
			"Παρασκευή",
			"Σάββατο"
		],
		"daysOfWeekShort": [
			"Κυ",
			"Δε",
			"Τρ",
			"Τε",
			"Πε",
			"Πα",
			"Σα"
		],
		"monthsOfYear": [
			"Ιανουάριος",
			"Φεβρουάριος",
			"Μάρτιος",
			"Απρίλιος",
			"Μάιος",
			"Ιούνιος",
			"Ιούλιος",
			"Αύγουστος",
			"Σεπτέμβριος",
			"Οκτώβριος",
			"Νοέμβριος",
			"Δεκέμβριος"
		],
		"monthsOfYearShort": [
			"Ιαν",
			"Φεβ",
			"Μαρ",
			"Απρ",
			"Μάι",
			"Ιούν",
			"Ιούλ",
			"Αύγ",
			"Σεπ",
			"Οκτ",
			"Νοέ",
			"Δεκ"
		],
		"durationLabel": [
			"Ημέρες",
			"Ώρες",
			"Λεπτά",
			"Δευτερόλεπτα"
		],
		"durationDays": [
			"Ημέρα",
			"Ημέρες"
		],
		"tooltip": "Άνοιγμα επιλογή ημερομηνίας",
		"nextMonth": "Επόμενος μήνας",
		"prevMonth": "Προηγούμενο μήνα",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d/%m/%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Σαφής",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Άλλες ημερομηνίες",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Άλμα στο αύριο"
	},
	"en": {
		"setDateButtonLabel": "Set Date",
		"setTimeButtonLabel": "Set Time",
		"setDurationButtonLabel": "Set Duration",
		"todayButtonLabel": "Jump to Today",
		"titleDateDialogLabel": "Choose Date",
		"titleTimeDialogLabel": "Choose Time",
		"daysOfWeek": [
			"Sunday",
			"Monday",
			"Tuesday",
			"Wednesday",
			"Thursday",
			"Friday",
			"Saturday"
		],
		"daysOfWeekShort": [
			"Su",
			"Mo",
			"Tu",
			"We",
			"Th",
			"Fr",
			"Sa"
		],
		"monthsOfYear": [
			"January",
			"February",
			"March",
			"April",
			"May",
			"June",
			"July",
			"August",
			"September",
			"October",
			"November",
			"December"
		],
		"monthsOfYearShort": [
			"Jan",
			"Feb",
			"Mar",
			"Apr",
			"May",
			"Jun",
			"Jul",
			"Aug",
			"Sep",
			"Oct",
			"Nov",
			"Dec"
		],
		"durationLabel": [
			"Days",
			"Hours",
			"Minutes",
			"Seconds"
		],
		"durationDays": [
			"Day",
			"Days"
		],
		"tooltip": "Open Date Picker",
		"nextMonth": "Next Month",
		"prevMonth": "Previous Month",
		"timeFormat": 12,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"m",
			"d",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%-m/%-d/%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Clear",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Other Dates",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Jump to Tomorrow"
	},
	"es-ES": {
		"setDateButtonLabel": "Guardar Fecha",
		"setTimeButtonLabel": "Guardar Hora",
		"setDurationButtonLabel": "Guardar Duración",
		"todayButtonLabel": "Hoy",
		"titleDateDialogLabel": "Elija fecha",
		"titleTimeDialogLabel": "Elegir Hora",
		"daysOfWeek": [
			"Domingo",
			"Lunes",
			"Martes",
			"Miércoles",
			"Jueves",
			"Viernes",
			"Sábado"
		],
		"daysOfWeekShort": [
			"Do",
			"Lu",
			"Ma",
			"Mi",
			"Ju",
			"Vi",
			"Sa"
		],
		"monthsOfYear": [
			"Enero",
			"Febrero",
			"Marzo",
			"Abril",
			"Mayo",
			"Junio",
			"Julio",
			"Agosto",
			"Septiembre",
			"Octubre",
			"Noviembre",
			"Diciembre"
		],
		"monthsOfYearShort": [
			"Ene",
			"Feb",
			"Mar",
			"Abr",
			"May",
			"Jun",
			"Jul",
			"Ago",
			"Sep",
			"Oct",
			"Nov",
			"Dic"
		],
		"durationLabel": [
			"Días",
			"Horas",
			"Minutos",
			"Segundos"
		],
		"durationDays": [
			"Día",
			"Días"
		],
		"tooltip": "Abrir El Calendario",
		"nextMonth": "Mes Próximo",
		"prevMonth": "Mes Anterior",
		"timeFormat": 24,
		"headerFormat": "%A, %-d %B, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d/%m/%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Borrar",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Otras fechas",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Saltar al mañana"
	},
	"fi": {
		"setDateButtonLabel": "Valitse päivä",
		"setTimeButtonLabel": "Valitse aika",
		"setDurationButtonLabel": "Valitse kesto",
		"todayButtonLabel": "Tänään",
		"titleDateDialogLabel": "Valitse päivämäärä",
		"titleTimeDialogLabel": "Valitse aika",
		"daysOfWeek": [
			"Sunnuntai",
			"Maanantai",
			"Tiistai",
			"Keskiviikko",
			"Torstai",
			"Perjantai",
			"Lauantai"
		],
		"daysOfWeekShort": [
			"Su",
			"Ma",
			"Ti",
			"Ke",
			"To",
			"Pe",
			"La"
		],
		"monthsOfYear": [
			"Tammikuu",
			"Helmikuu",
			"Maaliskuu",
			"Huhtikuu",
			"Toukokuu",
			"Kesäkuu",
			"Heinäkuu",
			"Elokuu",
			"Syyskuu",
			"Lokakuu",
			"Marraskuu",
			"Joulukuu"
		],
		"monthsOfYearShort": [
			"Tammi",
			"Helmi",
			"Maali",
			"Huhti",
			"Touko",
			"Kesä",
			"Heinä",
			"Elo",
			"Syys",
			"Loka",
			"Marras",
			"Joulu"
		],
		"durationLabel": [
			"Päivää",
			"Tuntia",
			"Minuuttia",
			"Sekuntia"
		],
		"durationDays": [
			"Päivä",
			"Päivää"
		],
		"tooltip": "Avaa päivämäärävalitsin",
		"nextMonth": "Seuraava kuukausi",
		"prevMonth": "Edellinen kuukausi",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d.%m.%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Tyhjennä",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Muut päivämäärät",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Siirry huomiseen"
	},
	"fr": {
		"setDateButtonLabel": "Date Fixée",
		"setTimeButtonLabel": "Régler l'heure",
		"setDurationButtonLabel": "Régler la durée",
		"todayButtonLabel": "Aller à aujourd'hui",
		"titleDateDialogLabel": "Choisir la Date",
		"titleTimeDialogLabel": "Choisir le temps",
		"daysOfWeek": [
			"Dimanche",
			"Lundi",
			"Mardi",
			"Mercredi",
			"Jeudi",
			"Vendredi",
			"Samedi"
		],
		"daysOfWeekShort": [
			"D",
			"L",
			"M",
			"M",
			"J",
			"V",
			"S"
		],
		"monthsOfYear": [
			"Janvier",
			"Février",
			"Mars",
			"Avril",
			"Mai",
			"Juin",
			"Juillet",
			"Août",
			"Septembre",
			"Octobre",
			"Novembre",
			"Décembre"
		],
		"monthsOfYearShort": [
			"Janv",
			"Févr",
			"Mars",
			"Avr",
			"Mai",
			"Juin",
			"Juil",
			"Août",
			"Sept",
			"Oct",
			"Nov",
			"Déc"
		],
		"durationLabel": [
			"Jours",
			"Heures",
			"Minutes",
			"Secondes"
		],
		"durationDays": [
			"Jour",
			"Jours"
		],
		"tooltip": "Ouvrir le sélecteur de date",
		"nextMonth": "Mois Suiv.",
		"prevMonth": "Mois Prec.",
		"timeFormat": 24,
		"headerFormat": "%A, %-d %B, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d/%m/%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 1,
		"clearButton": "Effacer",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Autres Dates",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Aller à demain"
	},
	"he": {
		"setDateButtonLabel": "קבע תאריך",
		"setTimeButtonLabel": "קבע זמן",
		"setDurationButtonLabel": "קבע משך זמן",
		"todayButtonLabel": "﻿קפיצה אל היום",
		"titleDateDialogLabel": "בחר תאריך",
		"titleTimeDialogLabel": "בחר זמן",
		"daysOfWeek": [
			"ראשון",
			"שני",
			"שלישי",
			"רביעי",
			"חמישי",
			"שישי",
			"שבת"
		],
		"daysOfWeekShort": [
			"ראש",
			"שני",
			"שלי",
			"רבי",
			"חמי",
			"ש",
			"שב"
		],
		"monthsOfYear": [
			"ינואר",
			"פברואר",
			"מרץ",
			"אפריל",
			"מאי",
			"יוני",
			"יולי",
			"אוגוסט",
			"ספטמבר",
			"אוקטובר",
			"נובמבר",
			"דצמבר"
		],
		"monthsOfYearShort": [
			"ינו",
			"פבר",
			"מרץ",
			"אפר",
			"מאי",
			"יונ",
			"יול",
			"אוג",
			"ספט",
			"אוק",
			"נוב",
			"דצמ"
		],
		"durationLabel": [
			"ימים",
			"שעות",
			"דקות",
			"שניות"
		],
		"durationDays": [
			"יום",
			"ימים"
		],
		"tooltip": "פתח תאריכון",
		"nextMonth": "החודש הבא",
		"prevMonth": "החודש הקודם",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"m",
			"d",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d-%m-%Y",
		"useArabicIndic": false,
		"isRTL": true,
		"calStartDay": 0,
		"clearButton": "נקה",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "תאריכים אחרים",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "לקפוץ מחר"
	},
	"hr": {
		"setDateButtonLabel": "Postavi Datum",
		"setTimeButtonLabel": "Postavi Vrijeme",
		"setDurationButtonLabel": "Postavi Trajanje",
		"todayButtonLabel": "Današnji Datum",
		"titleDateDialogLabel": "Odaberite datum",
		"titleTimeDialogLabel": "Odaberite vrijeme",
		"daysOfWeek": [
			"Nedjelja",
			"Ponedjeljak",
			"Utorak",
			"Srijeda",
			"Četvrtak",
			"Petak",
			"Subota"
		],
		"daysOfWeekShort": [
			"Ne",
			"Po",
			"Ut",
			"Sr",
			"Če",
			"Pe",
			"Su"
		],
		"monthsOfYear": [
			"Siječanj",
			"Veljača",
			"Ožujak",
			"Travanj",
			"Svibanj",
			"Lipanj",
			"Srpanj",
			"Kolovoz",
			"Rujan",
			"Listopad",
			"Studeni",
			"Prosinac"
		],
		"monthsOfYearShort": [
			"Sij",
			"Vel",
			"Ožu",
			"Tra",
			"Svi",
			"Lip",
			"Srp",
			"Kol",
			"Ruj",
			"Lis",
			"Stu",
			"Pro"
		],
		"durationLabel": [
			"Dani",
			"Sati",
			"Minute",
			"Sekunde"
		],
		"durationDays": [
			"Dan",
			"Dani"
		],
		"tooltip": "Otvorite kontrolu",
		"nextMonth": "Sljedeći",
		"prevMonth": "Prethodna",
		"timeFormat": 12,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d.%m.%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Izbriši",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Ostali datumi",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Skoči sutra"
	},
	"hu": {
		"setDateButtonLabel": "Dátum választása",
		"setTimeButtonLabel": "Idő választása",
		"setDurationButtonLabel": "Időtartam beállítása",
		"todayButtonLabel": "Ugrás a mai napra",
		"titleDateDialogLabel": "Dátum kiválasztása",
		"titleTimeDialogLabel": "Idő kiválasztása",
		"daysOfWeek": [
			"Vasárnap",
			"Hétfő",
			"Kedd",
			"Szerda",
			"Csütörtök",
			"Péntek",
			"Szombat"
		],
		"daysOfWeekShort": [
			"V",
			"H",
			"K",
			"Sze",
			"Cs",
			"P",
			"Szo"
		],
		"monthsOfYear": [
			"Január",
			"Február",
			"Március",
			"Április",
			"Május",
			"Június",
			"Július",
			"Augusztus",
			"Szeptember",
			"Október",
			"November",
			"December"
		],
		"monthsOfYearShort": [
			"Jan.",
			"Febr.",
			"Márc.",
			"Ápr.",
			"Máj.",
			"Jún.",
			"Júl.",
			"Aug.",
			"Szept.",
			"Okt.",
			"Nov.",
			"Dec."
		],
		"durationLabel": [
			"Napok",
			"Óra",
			"Perc",
			"Másodperc"
		],
		"durationDays": [
			"Nap",
			"Napok"
		],
		"tooltip": "Dátumválasztó megnyitása",
		"nextMonth": "Köv. hónap",
		"prevMonth": "Előző hónap",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"y",
			"m",
			"d"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%Y.%m.%d.",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 1,
		"clearButton": "Törlés",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"de.",
			"du."
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Más időpontok",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Ugrás a holnap"
	},
	"id": {
		"setDateButtonLabel": "Atur Tanggal",
		"setTimeButtonLabel": "Atur Waktu",
		"setDurationButtonLabel": "Atur Durasi",
		"todayButtonLabel": "Lompat ke hari ini",
		"titleDateDialogLabel": "Pilih Tanggal",
		"titleTimeDialogLabel": "Pilih Waktu",
		"daysOfWeek": [
			"Minggu",
			"Senin",
			"Selasa",
			"Rabu",
			"Kamis",
			"Jumat",
			"Sabtu"
		],
		"daysOfWeekShort": [
			"Mi",
			"Se",
			"Se",
			"Ra",
			"Ka",
			"Jum",
			"Sab"
		],
		"monthsOfYear": [
			"Januari",
			"Februari",
			"Maret",
			"April",
			"Mei",
			"Juni",
			"Juli",
			"Agustus",
			"September",
			"Oktober",
			"November",
			"Desember"
		],
		"monthsOfYearShort": [
			"Jan",
			"Feb",
			"Mar",
			"Apr",
			"Me",
			"Jun",
			"Jul",
			"Agu",
			"Sep",
			"Okt",
			"Nov",
			"Des"
		],
		"durationLabel": [
			"Hari",
			"Jam",
			"Menit",
			"Detik"
		],
		"durationDays": [
			"Hari",
			"Hari"
		],
		"tooltip": "Buka Date Picker",
		"nextMonth": "Bulan Berikutnya",
		"prevMonth": "Bulan Sebelumnya",
		"timeFormat": 12,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i"
		],
		"slideFieldOrder": [
			"d",
			"m",
			"y"
		],
		"dateFormat": "%d-%m-%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 1,
		"clearButton": "Bersihkan",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Tanggal lainnya",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Melompat ke besok"
	},
	"it": {
		"setDateButtonLabel": "Imposta data",
		"setTimeButtonLabel": "Imposta ora",
		"setDurationButtonLabel": "Setta Durata",
		"todayButtonLabel": "Vai ad oggi",
		"titleDateDialogLabel": "Scegli data",
		"titleTimeDialogLabel": "Scegli ora",
		"daysOfWeek": [
			"Domenica",
			"Lunedì",
			"Martedì",
			"Mercoledì",
			"Giovedì",
			"Venerdì",
			"Sabato"
		],
		"daysOfWeekShort": [
			"Do",
			"Lu",
			"Ma",
			"Me",
			"Gi",
			"Ve",
			"Sa"
		],
		"monthsOfYear": [
			"Gennaio",
			"Febbraio",
			"Marzo",
			"Aprile",
			"Maggio",
			"Giugno",
			"Luglio",
			"Agosto",
			"Settembre",
			"Ottobre",
			"Novembre",
			"Dicembre"
		],
		"monthsOfYearShort": [
			"Gen",
			"Feb",
			"Mar",
			"Apr",
			"Mag",
			"Giu",
			"Lug",
			"Ago",
			"Set",
			"Ott",
			"Nov",
			"Dic"
		],
		"durationLabel": [
			"Giorni",
			"Ore",
			"Minuti",
			"Secondi"
		],
		"durationDays": [
			"Giorno",
			"Giorni"
		],
		"tooltip": "Apri Selettore Data",
		"nextMonth": "Mese successivo",
		"prevMonth": "Mese precedente",
		"timeFormat": 12,
		"headerFormat": "%A %-d %B %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d-%m-%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Pulisci",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Altre date",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Vai a domani"
	},
	"ja": {
		"setDateButtonLabel": "日付を設定",
		"setTimeButtonLabel": "時刻を設定",
		"setDurationButtonLabel": "期間を設定",
		"todayButtonLabel": "今日へジャンプ",
		"titleDateDialogLabel": "日付を選択します。",
		"titleTimeDialogLabel": "時間を選択します。",
		"daysOfWeek": [
			"日曜",
			"月曜",
			"火曜",
			"水曜",
			"木曜",
			"金曜",
			"土曜"
		],
		"daysOfWeekShort": [
			"日",
			"月",
			"火",
			"水",
			"木",
			"金",
			"土"
		],
		"monthsOfYear": [
			"1月",
			"2月",
			"3月",
			"4月",
			"5月",
			"6月",
			"7月",
			"8月",
			"9月",
			"10月",
			"11月",
			"12月"
		],
		"monthsOfYearShort": [
			"1月",
			"2月",
			"3月",
			"4月",
			"5月",
			"6月",
			"7月",
			"8月",
			"9月",
			"10月",
			"11月",
			"12月"
		],
		"durationLabel": [
			"日",
			"時間",
			"分",
			"秒"
		],
		"durationDays": [
			"日",
			"日"
		],
		"tooltip": "日付を選択する",
		"nextMonth": "﻿次の月",
		"prevMonth": "﻿前月",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"m",
			"d",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%Y/%m/%d",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "クリア",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "その他の日付",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "明日へジャンプします。"
	},
	"ko": {
		"setDateButtonLabel": "날짜 지정",
		"setTimeButtonLabel": "시간 설정",
		"setDurationButtonLabel": "기간 설정",
		"todayButtonLabel": "오늘 날짜로 지정",
		"titleDateDialogLabel": "날짜 선택",
		"titleTimeDialogLabel": "시간 선택",
		"daysOfWeek": [
			"일요일",
			"월요일",
			"화요일",
			"수요일",
			"목요일",
			"금요일",
			"토요일"
		],
		"daysOfWeekShort": [
			"일",
			"월",
			"화",
			"수",
			"목",
			"금",
			"토"
		],
		"monthsOfYear": [
			"1월",
			"2월",
			"3월",
			"4월",
			"5월",
			"6월",
			"7월",
			"8월",
			"9월",
			"10월",
			"11월",
			"12월"
		],
		"monthsOfYearShort": [
			"1월",
			"2월",
			"3월",
			"4월",
			"5월",
			"6월",
			"7월",
			"8월",
			"9월",
			"10월",
			"11월",
			"12월"
		],
		"durationLabel": [
			"일",
			"시간",
			"분",
			"초"
		],
		"durationDays": [
			"일",
			"일"
		],
		"tooltip": "달력 열기",
		"nextMonth": "﻿다음 달",
		"prevMonth": "﻿이전 달",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"y",
			"m",
			"d"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%Y-%m-%d",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "지우기",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "다른 날짜",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "내일 이동"
	},
	"lt": {
		"setDateButtonLabel": "Data",
		"setTimeButtonLabel": "Laikas",
		"setDurationButtonLabel": "Trukmė",
		"todayButtonLabel": "Šiandiena",
		"titleDateDialogLabel": "Pasirinkti datą",
		"titleTimeDialogLabel": "Pasirinkite laiko",
		"daysOfWeek": [
			"Sekmadienis",
			"Pirmadienis",
			"Antradienis",
			"Trečiadienis",
			"Ketvirtadienis",
			"Penktadienis",
			"Šeštadienis"
		],
		"daysOfWeekShort": [
			"Sk",
			"Pr",
			"An",
			"Tr",
			"Kt",
			"Pn",
			"Ss"
		],
		"monthsOfYear": [
			"Sausis",
			"Vasaris",
			"Kovas",
			"Balandis",
			"Gegužė",
			"Birželis",
			"Liepa",
			"Rugpjūtis",
			"Rugsėjis",
			"Spalis",
			"Lapkritis",
			"Gruodis"
		],
		"monthsOfYearShort": [
			"Sau",
			"Vas",
			"Kov",
			"Bal",
			"Geg",
			"Bir",
			"Lie",
			"Rug",
			"Rgs",
			"Spa",
			"Lap",
			"Gru"
		],
		"durationLabel": [
			"Dienos",
			"Valandos",
			"Minutės",
			"Sekundeės"
		],
		"durationDays": [
			"Diena",
			"Dienos"
		],
		"tooltip": "Atidaryti datos parinkiklis",
		"nextMonth": "Atgal",
		"prevMonth": "Pirmyn",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"y",
			"m",
			"d"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%Y-%m-%d",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Aiškus",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Kitas datas",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Peršokti į rytoj"
	},
	"nl-BE": {
		"setDateButtonLabel": "Datum instellen",
		"setTimeButtonLabel": "Tijd instellen",
		"setDurationButtonLabel": "Duur instellen",
		"todayButtonLabel": "Spring naar vandaag",
		"titleDateDialogLabel": "Kies datum",
		"titleTimeDialogLabel": "Kies tijd",
		"daysOfWeek": [
			"Zondag",
			"Maandag",
			"Dinsdag",
			"Woensdag",
			"Donderdag",
			"Vrijdag",
			"Zaterdag"
		],
		"daysOfWeekShort": [
			"Zo",
			"Ma",
			"Di",
			"Wo",
			"Do",
			"Vr",
			"Za"
		],
		"monthsOfYear": [
			"Januari",
			"Februari",
			"Maart",
			"April",
			"Mei",
			"Juni",
			"Juli",
			"Augustus",
			"September",
			"Oktober",
			"November",
			"December"
		],
		"monthsOfYearShort": [
			"Jan",
			"Feb",
			"Mrt",
			"Apr",
			"Mei",
			"Jun",
			"Jul",
			"Aug",
			"Sep",
			"Okt",
			"Nov",
			"Dec"
		],
		"durationLabel": [
			"Dagen",
			"Uren",
			"Minuten",
			"Seconden"
		],
		"durationDays": [
			"Dag",
			"Dagen"
		],
		"tooltip": "Open de controle",
		"nextMonth": "Volgende maand",
		"prevMonth": "Vorige maand",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"d",
			"m",
			"y"
		],
		"dateFormat": "%d/%m/%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 1,
		"clearButton": "Wissen",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Andere datums",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Springen naar morgen"
	},
	"nl": {
		"setDateButtonLabel": "Datum instellen",
		"setTimeButtonLabel": "Tijd instellen",
		"setDurationButtonLabel": "Duur instellen",
		"todayButtonLabel": "Spring naar vandaag",
		"titleDateDialogLabel": "Kies datum",
		"titleTimeDialogLabel": "Kies tijd",
		"daysOfWeek": [
			"Zondag",
			"Maandag",
			"Dinsdag",
			"Woensdag",
			"Donderdag",
			"Vrijdag",
			"Zaterdag"
		],
		"daysOfWeekShort": [
			"Zo",
			"Ma",
			"Di",
			"Wo",
			"Do",
			"Vr",
			"Za"
		],
		"monthsOfYear": [
			"Januari",
			"Februari",
			"Maart",
			"April",
			"Mei",
			"Juni",
			"Juli",
			"Augustus",
			"September",
			"Oktober",
			"November",
			"December"
		],
		"monthsOfYearShort": [
			"Jan",
			"Feb",
			"Mrt",
			"Apr",
			"Mei",
			"Jun",
			"Jul",
			"Aug",
			"Sep",
			"Okt",
			"Nov",
			"Dec"
		],
		"durationLabel": [
			"Dagen",
			"Uren",
			"Minuten",
			"Seconden"
		],
		"durationDays": [
			"Dag",
			"Dagen"
		],
		"tooltip": "Open de controle",
		"nextMonth": "Volgende maand",
		"prevMonth": "Vorige maand",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"d",
			"m",
			"y"
		],
		"dateFormat": "%d-%m-%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Wissen",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Andere datums",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Springen naar morgen"
	},
	"no": {
		"setDateButtonLabel": "Velg dato",
		"setTimeButtonLabel": "Velg tidspunkt",
		"setDurationButtonLabel": "Velg varighet",
		"todayButtonLabel": "Gå til dagens dato",
		"titleDateDialogLabel": "Velg dato",
		"titleTimeDialogLabel": "Velg tidspunkt",
		"daysOfWeek": [
			"Søndag",
			"Mandag",
			"Tirsdag",
			"Onsdag",
			"Torsdag",
			"Fredag",
			"Lørdag"
		],
		"daysOfWeekShort": [
			"Søn",
			"Man",
			"Tirs",
			"Ons",
			"Tors",
			"Fre",
			"Lør"
		],
		"monthsOfYear": [
			"Januar",
			"Februar",
			"Mars",
			"April",
			"Mai",
			"Juni",
			"Juli",
			"August",
			"September",
			"Oktober",
			"November",
			"Desember"
		],
		"monthsOfYearShort": [
			"Jan",
			"Feb",
			"Mar",
			"Apr",
			"Mai",
			"Jun",
			"Jul",
			"Aug",
			"Sep",
			"Okt",
			"Nov",
			"Des"
		],
		"durationLabel": [
			"Dager",
			"Timer",
			"Minutter",
			"Sekunder"
		],
		"durationDays": [
			"Dag",
			"Dager"
		],
		"tooltip": "Åpne datovelger",
		"nextMonth": "Neste måned",
		"prevMonth": "Forrige måned",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%m/%d/%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Klart",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Andre datoer",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Hoppe til i morgen"
	},
	"pl": {
		"setDateButtonLabel": "Ustaw datę",
		"setTimeButtonLabel": "Ustaw godzinę",
		"setDurationButtonLabel": "Ustaw okres",
		"todayButtonLabel": "Dzisiaj",
		"titleDateDialogLabel": "Wybierz datę",
		"titleTimeDialogLabel": "Wybierz czas",
		"daysOfWeek": [
			"Niedziela",
			"Poniedziałek",
			"Wtorek",
			"Środa",
			"Czwartek",
			"Piątek",
			"Sobota"
		],
		"daysOfWeekShort": [
			"Nd",
			"Pn",
			"Wt",
			"Śr",
			"Cz",
			"Pt",
			"Sb"
		],
		"monthsOfYear": [
			"Styczeń",
			"Luty",
			"Marzec",
			"Kwiecień",
			"Maj",
			"Czerwiec",
			"Lipiec",
			"Sierpień",
			"Wrzesień",
			"Październik",
			"Listopad",
			"Grudzień"
		],
		"monthsOfYearShort": [
			"Sty",
			"Lut",
			"Mar",
			"Kwi",
			"Maj",
			"Cze",
			"Lip",
			"Sie",
			"Wrz",
			"Paź",
			"Lis",
			"Gru"
		],
		"durationLabel": [
			"Dni",
			"Godziny",
			"Minuty",
			"Sekundy"
		],
		"durationDays": [
			"Dzień",
			"Dni"
		],
		"tooltip": "Otwórz wybór daty",
		"nextMonth": "Następny miesiąc",
		"prevMonth": "Poprzedni miesiąc",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%Y-%m-%d",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Wyczyść",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Inne daty",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Skok do jutra"
	},
	"pt-BR": {
		"setDateButtonLabel": "Informar data",
		"setTimeButtonLabel": "Informar hora",
		"setDurationButtonLabel": "Informar duração",
		"todayButtonLabel": "Ir para hoje",
		"titleDateDialogLabel": "Escolher data",
		"titleTimeDialogLabel": "Escolha a hora",
		"daysOfWeek": [
			"Domingo",
			"Segunda",
			"Terça",
			"Quarta",
			"Quinta",
			"Sexta",
			"Sábado"
		],
		"daysOfWeekShort": [
			"D",
			"S",
			"T",
			"Q",
			"Q",
			"S",
			"S"
		],
		"monthsOfYear": [
			"Janeiro",
			"Fevereiro",
			"Março",
			"Abril",
			"Maio",
			"Junho",
			"Julho",
			"Agosto",
			"Setembro",
			"Outubro",
			"Novembro",
			"Dezembro"
		],
		"monthsOfYearShort": [
			"Jan",
			"Fev",
			"Mar",
			"Abr",
			"Mai",
			"Jun",
			"Jul",
			"Ago",
			"Set",
			"Out",
			"Nov",
			"Dez"
		],
		"durationLabel": [
			"Dias",
			"Horas",
			"Minutos",
			"Segundos"
		],
		"durationDays": [
			"Dia",
			"Dias"
		],
		"tooltip": "Selecionador de data aberta",
		"nextMonth": "Próximo mês",
		"prevMonth": "Mês anterior",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d/%m/%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Limpar",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Outras datas",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Saltar para o amanhã"
	},
	"pt-PT": {
		"setDateButtonLabel": "Fechar",
		"setTimeButtonLabel": "Fechar",
		"setDurationButtonLabel": "Fechar",
		"todayButtonLabel": "Ir para hoje",
		"titleDateDialogLabel": "Escolher data",
		"titleTimeDialogLabel": "Escolha a hora",
		"daysOfWeek": [
			"Domingo",
			"Segunda-feira",
			"Terça-feira",
			"Quarta-feira",
			"Quinta-feira",
			"Sexta-feira",
			"Sábado"
		],
		"daysOfWeekShort": [
			"Dom",
			"Seg",
			"Ter",
			"Qua",
			"Qui",
			"Sex",
			"Sáb"
		],
		"monthsOfYear": [
			"Janeiro",
			"Fevereiro",
			"Março",
			"Abril",
			"Maio",
			"Junho",
			"Julho",
			"Agosto",
			"Setembro",
			"Outubro",
			"Novembro",
			"Dezembro"
		],
		"monthsOfYearShort": [
			"Jan",
			"Fev",
			"Mar",
			"Abr",
			"Mai",
			"Jun",
			"Jul",
			"Ago",
			"Set",
			"Out",
			"Nov",
			"Dez"
		],
		"durationLabel": [
			"Dias",
			"Horas",
			"Minutos",
			"Segundos"
		],
		"durationDays": [
			"Dia",
			"Dias"
		],
		"tooltip": "Abrir selecionador de data",
		"nextMonth": "Mês Seguinte",
		"prevMonth": "Mês anterior",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d/%m/%y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Limpar",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Outras datas",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Saltar para o amanhã"
	},
	"ro": {
		"setDateButtonLabel": "Setaţi data",
		"setTimeButtonLabel": "Setaţi timp",
		"setDurationButtonLabel": "Setaţi durata",
		"todayButtonLabel": "Salt la azi",
		"titleDateDialogLabel": "Alegeţi data",
		"titleTimeDialogLabel": "Alege timp",
		"daysOfWeek": [
			"Duminică",
			"Luni",
			"Marţi",
			"Miercuri",
			"Joi",
			"Vineri",
			"Sâmbătă"
		],
		"daysOfWeekShort": [
			"Dum",
			"Lun",
			"Mar",
			"Mie",
			"Joi",
			"Vin",
			"Sâm"
		],
		"monthsOfYear": [
			"Ianuarie",
			"Februarie",
			"Martie",
			"Aprilie",
			"Mai",
			"Iunie",
			"Iulie",
			"August",
			"Septembrie",
			"Octombrie",
			"Noiembrie",
			"Decembrie"
		],
		"monthsOfYearShort": [
			"Ian",
			"Feb",
			"Mar",
			"Apr",
			"Mai",
			"Iun",
			"Iul",
			"Aug",
			"Sep",
			"Oct",
			"Nov",
			"Dec"
		],
		"durationLabel": [
			"Zi",
			"Ore",
			"Minute",
			"Secunde"
		],
		"durationDays": [
			"Ziua",
			"Zi"
		],
		"tooltip": "Deschide selectorul de dată",
		"nextMonth": "Luna următoare",
		"prevMonth": "Luna precedentă",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d.%m.%y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Clar",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Alte date",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Salt la Maine"
	},
	"ru": {
		"setDateButtonLabel": "Установить Дату",
		"setTimeButtonLabel": "Установить Время",
		"setDurationButtonLabel": "Установить Продолжительность",
		"todayButtonLabel": "Сегодня",
		"titleDateDialogLabel": "Выбрать дату",
		"titleTimeDialogLabel": "Выбрать время",
		"daysOfWeek": [
			"Воскресенье",
			"Понедельник",
			"Вторник",
			"Среда",
			"Четверг",
			"Пятница",
			"Суббота"
		],
		"daysOfWeekShort": [
			"Вс",
			"Пн",
			"Вт",
			"Ср",
			"Чт",
			"Пт",
			"Сб"
		],
		"monthsOfYear": [
			"Январь",
			"Февраль",
			"Март",
			"Апрель",
			"Май",
			"Июнь",
			"Июль",
			"Август",
			"Сентябрь",
			"Октябрь",
			"Ноябрь",
			"Декабрь"
		],
		"monthsOfYearShort": [
			"Янв",
			"Фев",
			"Мар",
			"Апр",
			"Май",
			"Июн",
			"Июл",
			"Авг",
			"Сен",
			"Окт",
			"Ноя",
			"Дек"
		],
		"durationLabel": [
			"Дни",
			"Часы",
			"Минуты",
			"Секунды"
		],
		"durationDays": [
			"День",
			"Дни"
		],
		"tooltip": "Открыть выбор даты",
		"nextMonth": "След",
		"prevMonth": "Пред",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%-d.%-m.%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 1,
		"clearButton": "Очистить",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Другие даты",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Перейти на завтра"
	},
	"sk": {
		"setDateButtonLabel": "Nastaviť dátum",
		"setTimeButtonLabel": "Nastaviť čas",
		"setDurationButtonLabel": "Nastaviť dobu trvania",
		"todayButtonLabel": "Teraz",
		"titleDateDialogLabel": "Zvoľte dátum",
		"titleTimeDialogLabel": "Zvoľte dátum",
		"daysOfWeek": [
			"Nedeľa",
			"Pondelok",
			"Utorok",
			"Streda",
			"Štvrtok",
			"Piatok",
			"Sobota"
		],
		"daysOfWeekShort": [
			"Ne",
			"Po",
			"Ut",
			"St",
			"Št",
			"Pi",
			"So"
		],
		"monthsOfYear": [
			"Január",
			"Februára",
			"Marec",
			"Apríl",
			"Máj",
			"Jún",
			"Júl",
			"August",
			"September",
			"Október",
			"November",
			"December"
		],
		"monthsOfYearShort": [
			"Jan",
			"Feb",
			"Mar",
			"Apr",
			"Máj",
			"Jún",
			"Júl",
			"Aug",
			"Sep",
			"Okt",
			"Nov",
			"Dec"
		],
		"durationLabel": [
			"Dni",
			"Hodín",
			"Minút",
			"Sekund"
		],
		"durationDays": [
			"Deň",
			"Dni"
		],
		"tooltip": "Otvoriť výber dáta",
		"nextMonth": "Neskôr",
		"prevMonth": "Predtým",
		"timeFormat": 24,
		"headerFormat": "%-d. %B %Y (%A)",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d.%m.%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 1,
		"clearButton": "Vymazať",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Ďalšie termíny",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Prejsť na zajtrajšok"
	},
	"sl": {
		"setDateButtonLabel": "Nastavi datum",
		"setTimeButtonLabel": "Nastavi čas",
		"setDurationButtonLabel": "Nastavi trajanje",
		"todayButtonLabel": "Danes",
		"titleDateDialogLabel": "Izberi datum",
		"titleTimeDialogLabel": "Izberi čas",
		"daysOfWeek": [
			"Nedelja",
			"Ponedeljek",
			"Torek",
			"Sreda",
			"Četrtek",
			"Petek",
			"Sobota"
		],
		"daysOfWeekShort": [
			"Ne",
			"Po",
			"To",
			"Sr",
			"Če",
			"Pe",
			"So"
		],
		"monthsOfYear": [
			"Januar",
			"Februar",
			"Marec",
			"April",
			"Maj",
			"Junij",
			"Julij",
			"Avgust",
			"September",
			"Oktober",
			"November",
			"December"
		],
		"monthsOfYearShort": [
			"Jan",
			"Feb",
			"Mar",
			"Apr",
			"Maj",
			"Jun",
			"Jul",
			"Avg",
			"Sep",
			"Okt",
			"Nov",
			"Dec"
		],
		"durationLabel": [
			"Dni",
			"Ur",
			"Minut",
			"Sekund"
		],
		"durationDays": [
			"Dan",
			"Dni"
		],
		"tooltip": "Izberi datum",
		"nextMonth": "Naslednji mesec",
		"prevMonth": "Predhodni mesec",
		"timeFormat": 24,
		"headerFormat": "%A, %-d. %B %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%-d.%-m.%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 1,
		"clearButton": "Počisti",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Drugi datumi",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Jutri"
	},
	"sr": {
		"setDateButtonLabel": "Затвори",
		"setTimeButtonLabel": "Затвори",
		"setDurationButtonLabel": "Затвори",
		"todayButtonLabel": "Данас",
		"titleDateDialogLabel": "Изаберите датум",
		"titleTimeDialogLabel": "Одаберите време",
		"daysOfWeek": [
			"Недеља",
			"Понедељак",
			"Уторак",
			"Среда",
			"Четвртак",
			"Петак",
			"Субота"
		],
		"daysOfWeekShort": [
			"Нед",
			"Пон",
			"Уто",
			"Сре",
			"Чет",
			"Пет",
			"Суб"
		],
		"monthsOfYear": [
			"Јануар",
			"Фебруар",
			"Март",
			"Април",
			"Мај",
			"Јун",
			"Јул",
			"Август",
			"Септембар",
			"Октобар",
			"Новембар",
			"Децембар"
		],
		"monthsOfYearShort": [
			"Јан",
			"Феб",
			"Мар",
			"Апр",
			"Мај",
			"Јун",
			"Јул",
			"Авг",
			"Сеп",
			"Окт",
			"Нов",
			"Дец"
		],
		"durationLabel": [
			"дана",
			"сат",
			"минута",
			"секунди"
		],
		"durationDays": [
			"дан",
			"дана"
		],
		"tooltip": "Отворите Цонтрол",
		"nextMonth": "следећи",
		"prevMonth": "Претходна",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d/%m/%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Обриши",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Остали датуми",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Скочи на Томорров"
	},
	"sv-SE": {
		"setDateButtonLabel": "Välj datum",
		"setTimeButtonLabel": "Välj tid",
		"setDurationButtonLabel": "Välj varaktighet",
		"todayButtonLabel": "Gå till idag",
		"titleDateDialogLabel": "Välj datum",
		"titleTimeDialogLabel": "Välj tid",
		"daysOfWeek": [
			"Söndag",
			"Måndag",
			"Tisdag",
			"Onsdag",
			"Torsdag",
			"Fredag",
			"Lördag"
		],
		"daysOfWeekShort": [
			"Sö",
			"Må",
			"Ti",
			"On",
			"To",
			"Fr",
			"Lö"
		],
		"monthsOfYear": [
			"Januari",
			"Februari",
			"Mars",
			"April",
			"Maj",
			"Juni",
			"July",
			"Augusti",
			"September",
			"Oktober",
			"November",
			"December"
		],
		"monthsOfYearShort": [
			"Jan",
			"Feb",
			"Mar",
			"Apr",
			"Maj",
			"Jun",
			"Jul",
			"Aug",
			"Sep",
			"Okt",
			"Nov",
			"Dec"
		],
		"durationLabel": [
			"Dagar",
			"Timmar",
			"Minuter",
			"Sekunder"
		],
		"durationDays": [
			"Dag",
			"Dagar"
		],
		"tooltip": "Öppna datumväljare",
		"nextMonth": "Nästa månad",
		"prevMonth": "Föregående månad",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"y",
			"m",
			"d"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%Y-%m-%d",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Töm",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Andra datum",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Hoppa till i morgon"
	},
	"th": {
		"setDateButtonLabel": "เลือกวัน",
		"setTimeButtonLabel": "ตั้งเวลา",
		"setDurationButtonLabel": "ตั้งระยะเวลา",
		"todayButtonLabel": "กลับไปวันนี้",
		"titleDateDialogLabel": "เลือกวัน",
		"titleTimeDialogLabel": "เลือกเวลา",
		"daysOfWeek": [
			"อาทิตย์",
			"จันทร์",
			"อังคาร",
			"พุธ",
			"พฤหัส",
			"ศุกร์",
			"เสาร์"
		],
		"daysOfWeekShort": [
			"อา",
			"จ",
			"อ",
			"พ",
			"พฤ",
			"ศ",
			"ส"
		],
		"monthsOfYear": [
			"มกราคม",
			"กุมภาพันธ์",
			"มีนาคม",
			"เมษายน",
			"พฤษภาคม",
			"มิถุนายน",
			"กรกฎาคม",
			"สิงหาคม",
			"กันยายน",
			"ตุลาคม",
			"พฤศจิกายน",
			"ธันวาคม"
		],
		"monthsOfYearShort": [
			"ม.ค.",
			"ก.พ.",
			"มี.ค.",
			"ม.ย.",
			"พ.ค.",
			"มิ.ย.",
			"ก.ค.",
			"ส.ค.",
			"ก.ย.",
			"ต.ค.",
			"พ.ย.",
			"ธ.ค."
		],
		"durationLabel": [
			"วัน",
			"ชั่วโมง",
			"นาที",
			"วินาที"
		],
		"durationDays": [
			"วัน",
			"วัน"
		],
		"tooltip": "เปิดตัวเลือกวัน",
		"nextMonth": "เดือนต่อไป",
		"prevMonth": "เดือนก่อน",
		"timeFormat": 24,
		"headerFormat": "วัน%Aที่ %-d %B %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d/%m/%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "ลบ",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "วันอื่น ๆ",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "ไปวันพรุ่งนี้"
	},
	"tr": {
		"setDateButtonLabel": "Kapat",
		"setTimeButtonLabel": "Saat ayarlama",
		"setDurationButtonLabel": "Kapat",
		"todayButtonLabel": "Bugün",
		"titleDateDialogLabel": "Tarih seçin",
		"titleTimeDialogLabel": "Süreyi belirleyin",
		"daysOfWeek": [
			"Pazar",
			"Pazartesi",
			"Salı",
			"Çarşamba",
			"Perşembe",
			"Cuma",
			"Cumartesi"
		],
		"daysOfWeekShort": [
			"Pz",
			"Pt",
			"Sa",
			"Ça",
			"Pe",
			"Cu",
			"Ct"
		],
		"monthsOfYear": [
			"Ocak",
			"Şubat",
			"Mart",
			"Nisan",
			"Mayıs",
			"Haziran",
			"Temmuz",
			"Ağustos",
			"Eylül",
			"Ekim",
			"Kasım",
			"Aralık"
		],
		"monthsOfYearShort": [
			"Oca",
			"Şub",
			"Mar",
			"Nis",
			"May",
			"Haz",
			"Tem",
			"Ağu",
			"Eyl",
			"Eki",
			"Kas",
			"Ara"
		],
		"durationLabel": [
			"Gün",
			"Saat",
			"Dakika",
			"Saniye"
		],
		"durationDays": [
			"Gün",
			"Gün"
		],
		"tooltip": "Açık tarih seçici",
		"nextMonth": "Ileri",
		"prevMonth": "Geri",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d.%m.%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Açık",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Diğer tarihler",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Yarın için atlama"
	},
	"uk": {
		"setDateButtonLabel": "Встановити Дату",
		"setTimeButtonLabel": "Встановити Час",
		"setDurationButtonLabel": "Встановити Тривалість",
		"todayButtonLabel": "Сьогодні",
		"titleDateDialogLabel": "Вибрати дату",
		"titleTimeDialogLabel": "Вибрати час",
		"daysOfWeek": [
			"Неділя",
			"Понеділок",
			"Вівторок",
			"Середа",
			"Четвер",
			"П\\'ятниця",
			"Субота"
		],
		"daysOfWeekShort": [
			"Нд",
			"Пн",
			"Вт",
			"Ср",
			"Чт",
			"Пт",
			"Сб"
		],
		"monthsOfYear": [
			"Січень",
			"Лютий",
			"Березень",
			"Квітень",
			"Травень",
			"Червень",
			"Липень",
			"Серпень",
			"Вересень",
			"Жовтень",
			"Листопад",
			"Грудень"
		],
		"monthsOfYearShort": [
			"Січ",
			"Лют",
			"Бер",
			"Кві",
			"Тра",
			"Чер",
			"Лип",
			"Сер",
			"Веп",
			"Жов",
			"Лис",
			"Гру"
		],
		"durationLabel": [
			"Дні",
			"Години",
			"Хвилини",
			"Секунди"
		],
		"durationDays": [
			"День",
			"Дні"
		],
		"tooltip": "Відкрити елемент вибору дати",
		"nextMonth": "Наступного місяця",
		"prevMonth": "Минулий місяць",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d.%m.%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Ясно",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Інші дати",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Перейти до завтра"
	},
	"vi": {
		"setDateButtonLabel": "Đóng",
		"setTimeButtonLabel": "Đóng",
		"setDurationButtonLabel": "Đóng",
		"todayButtonLabel": "Hôm nay",
		"titleDateDialogLabel": "Chọn ngày",
		"titleTimeDialogLabel": "Chọn thời gian",
		"daysOfWeek": [
			"Chủ Nhật",
			"Thứ hai",
			"Thứ Ba",
			"Thứ Tư",
			"Thứ Năm",
			"Thứ sáu",
			"Thứ Bảy"
		],
		"daysOfWeekShort": [
			"CN",
			"T2",
			"T3",
			"T4",
			"T5",
			"T6",
			"T7"
		],
		"monthsOfYear": [
			"Tháng một",
			"Tháng hai",
			"Tháng ba",
			"Tháng tư",
			"Tháng năm",
			"Tháng Sáu",
			"Tháng Bảy",
			"Tháng Tám",
			"Tháng Chín",
			"Tháng Mười",
			"Tháng mười một",
			"Tháng mười hai"
		],
		"monthsOfYearShort": [
			"Tháng một",
			"Tháng hai",
			"Tháng ba",
			"Tháng tư",
			"Tháng năm",
			"Tháng Sáu",
			"Tháng Bảy",
			"Tháng Tám",
			"Tháng Chín",
			"Tháng Mười",
			"Tháng mười một",
			"Tháng mười hai"
		],
		"durationLabel": [
			"Ngày",
			"Giờ",
			"Phút",
			"Giây"
		],
		"durationDays": [
			"Ngày",
			"Ngày"
		],
		"tooltip": "Mở bảng chọn ngày",
		"nextMonth": "Tiếp",
		"prevMonth": "Trước",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"d",
			"m",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%d/%m/%Y",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "Rõ ràng",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"AM",
			"PM"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "Ngày khác",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "Nhảy để ngày mai"
	},
	"zh-CN": {
		"setDateButtonLabel": "设置日期",
		"setTimeButtonLabel": "设置时间",
		"setDurationButtonLabel": "设置持续时间",
		"todayButtonLabel": "选择今天日期",
		"titleDateDialogLabel": "选择日期",
		"titleTimeDialogLabel": "选择时间",
		"daysOfWeek": [
			"星期日",
			"星期一",
			"星期二",
			"星期三",
			"星期四",
			"星期五",
			"星期六"
		],
		"daysOfWeekShort": [
			"日",
			"一",
			"二",
			"三",
			"四",
			"五",
			"六"
		],
		"monthsOfYear": [
			"一月",
			"二月",
			"三月",
			"四月",
			"五月",
			"六月",
			"七月",
			"八月",
			"九月",
			"十月",
			"十一月",
			"十二月"
		],
		"monthsOfYearShort": [
			"一",
			"二",
			"三",
			"四",
			"五",
			"六",
			"七",
			"八",
			"九",
			"十",
			"十一",
			"十二"
		],
		"durationLabel": [
			"天",
			"小时",
			"分钟",
			"秒"
		],
		"durationDays": [
			"天",
			"天"
		],
		"tooltip": "开启日期选取器",
		"nextMonth": "下个月",
		"prevMonth": "上个月",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"m",
			"d",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%Y-%m-%d",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "清除",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"上午",
			"下午"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "其他日期",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "跳转到明天"
	},
	"zh-TW": {
		"setDateButtonLabel": "設定日期",
		"setTimeButtonLabel": "設定時間",
		"setDurationButtonLabel": "設定持續日期",
		"todayButtonLabel": "選擇今天日期",
		"titleDateDialogLabel": "選擇日期",
		"titleTimeDialogLabel": "選擇時間",
		"daysOfWeek": [
			"星期日",
			"星期一",
			"星期二",
			"星期三",
			"星期四",
			"星期五",
			"星期六"
		],
		"daysOfWeekShort": [
			"日",
			"一",
			"二",
			"三",
			"四",
			"五",
			"六"
		],
		"monthsOfYear": [
			"一月",
			"二月",
			"三月",
			"四月",
			"五月",
			"六月",
			"七月",
			"八月",
			"九月",
			"十月",
			"十一月",
			"十二月"
		],
		"monthsOfYearShort": [
			"一",
			"二",
			"三",
			"四",
			"五",
			"六",
			"七",
			"八",
			"九",
			"十",
			"十一",
			"十二"
		],
		"durationLabel": [
			"天",
			"小時",
			"分鐘",
			"秒"
		],
		"durationDays": [
			"天",
			"天"
		],
		"tooltip": "開啟日期選擇",
		"nextMonth": "下個月",
		"prevMonth": "上個月",
		"timeFormat": 24,
		"headerFormat": "%A, %B %-d, %Y",
		"dateFieldOrder": [
			"m",
			"d",
			"y"
		],
		"timeFieldOrder": [
			"h",
			"i",
			"a"
		],
		"slideFieldOrder": [
			"y",
			"m",
			"d"
		],
		"dateFormat": "%Y-%m-%d",
		"useArabicIndic": false,
		"isRTL": false,
		"calStartDay": 0,
		"clearButton": "清除",
		"durationOrder": [
			"d",
			"h",
			"i",
			"s"
		],
		"meridiem": [
			"上午",
			"下午"
		],
		"timeOutput": "%l:%M %p",
		"durationFormat": "%Dd %DA, %Dl:%DM:%DS",
		"calDateListLabel": "其他日期",
		"calHeaderFormat": "%B %Y",
		"tomorrowButtonLabel": "跳轉到明天"
	}
});
jQuery.extend(jQuery.jtsage.datebox.prototype.options, {
	useLang: "en"
});



/*!
 * Bootstrap v3.3.7 (http://getbootstrap.com)
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under the MIT license
 */

if (typeof jQuery === 'undefined') {
  throw new Error('Bootstrap\'s JavaScript requires jQuery')
}

+function ($) {
  'use strict';
  var version = $.fn.jquery.split(' ')[0].split('.')
  if ((version[0] < 2 && version[1] < 9) || (version[0] == 1 && version[1] == 9 && version[2] < 1) || (version[0] > 3)) {
    throw new Error('Bootstrap\'s JavaScript requires jQuery version 1.9.1 or higher, but lower than version 4')
  }
}(jQuery);

/* ========================================================================
 * Bootstrap: transition.js v3.3.7
 * http://getbootstrap.com/javascript/#transitions
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // CSS TRANSITION SUPPORT (Shoutout: http://www.modernizr.com/)
  // ============================================================

  function transitionEnd() {
    var el = document.createElement('bootstrap')

    var transEndEventNames = {
      WebkitTransition : 'webkitTransitionEnd',
      MozTransition    : 'transitionend',
      OTransition      : 'oTransitionEnd otransitionend',
      transition       : 'transitionend'
    }

    for (var name in transEndEventNames) {
      if (el.style[name] !== undefined) {
        return { end: transEndEventNames[name] }
      }
    }

    return false // explicit for ie8 (  ._.)
  }

  // http://blog.alexmaccaw.com/css-transitions
  $.fn.emulateTransitionEnd = function (duration) {
    var called = false
    var $el = this
    $(this).one('bsTransitionEnd', function () { called = true })
    var callback = function () { if (!called) $($el).trigger($.support.transition.end) }
    setTimeout(callback, duration)
    return this
  }

  $(function () {
    $.support.transition = transitionEnd()

    if (!$.support.transition) return

    $.event.special.bsTransitionEnd = {
      bindType: $.support.transition.end,
      delegateType: $.support.transition.end,
      handle: function (e) {
        if ($(e.target).is(this)) return e.handleObj.handler.apply(this, arguments)
      }
    }
  })

}(jQuery);

/* ========================================================================
 * Bootstrap: alert.js v3.3.7
 * http://getbootstrap.com/javascript/#alerts
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // ALERT CLASS DEFINITION
  // ======================

  var dismiss = '[data-dismiss="alert"]'
  var Alert   = function (el) {
    $(el).on('click', dismiss, this.close)
  }

  Alert.VERSION = '3.3.7'

  Alert.TRANSITION_DURATION = 150

  Alert.prototype.close = function (e) {
    var $this    = $(this)
    var selector = $this.attr('data-target')

    if (!selector) {
      selector = $this.attr('href')
      selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
    }

    var $parent = $(selector === '#' ? [] : selector)

    if (e) e.preventDefault()

    if (!$parent.length) {
      $parent = $this.closest('.alert')
    }

    $parent.trigger(e = $.Event('close.bs.alert'))

    if (e.isDefaultPrevented()) return

    $parent.removeClass('in')

    function removeElement() {
      // detach from parent, fire event then clean up data
      $parent.detach().trigger('closed.bs.alert').remove()
    }

    $.support.transition && $parent.hasClass('fade') ?
      $parent
        .one('bsTransitionEnd', removeElement)
        .emulateTransitionEnd(Alert.TRANSITION_DURATION) :
      removeElement()
  }


  // ALERT PLUGIN DEFINITION
  // =======================

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var data  = $this.data('bs.alert')

      if (!data) $this.data('bs.alert', (data = new Alert(this)))
      if (typeof option == 'string') data[option].call($this)
    })
  }

  var old = $.fn.alert

  $.fn.alert             = Plugin
  $.fn.alert.Constructor = Alert


  // ALERT NO CONFLICT
  // =================

  $.fn.alert.noConflict = function () {
    $.fn.alert = old
    return this
  }


  // ALERT DATA-API
  // ==============

  $(document).on('click.bs.alert.data-api', dismiss, Alert.prototype.close)

}(jQuery);

/* ========================================================================
 * Bootstrap: button.js v3.3.7
 * http://getbootstrap.com/javascript/#buttons
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // BUTTON PUBLIC CLASS DEFINITION
  // ==============================

  var Button = function (element, options) {
    this.$element  = $(element)
    this.options   = $.extend({}, Button.DEFAULTS, options)
    this.isLoading = false
  }

  Button.VERSION  = '3.3.7'

  Button.DEFAULTS = {
    loadingText: 'loading...'
  }

  Button.prototype.setState = function (state) {
    var d    = 'disabled'
    var $el  = this.$element
    var val  = $el.is('input') ? 'val' : 'html'
    var data = $el.data()

    state += 'Text'

    if (data.resetText == null) $el.data('resetText', $el[val]())

    // push to event loop to allow forms to submit
    setTimeout($.proxy(function () {
      $el[val](data[state] == null ? this.options[state] : data[state])

      if (state == 'loadingText') {
        this.isLoading = true
        $el.addClass(d).attr(d, d).prop(d, true)
      } else if (this.isLoading) {
        this.isLoading = false
        $el.removeClass(d).removeAttr(d).prop(d, false)
      }
    }, this), 0)
  }

  Button.prototype.toggle = function () {
    var changed = true
    var $parent = this.$element.closest('[data-toggle="buttons"]')

    if ($parent.length) {
      var $input = this.$element.find('input')
      if ($input.prop('type') == 'radio') {
        if ($input.prop('checked')) changed = false
        $parent.find('.active').removeClass('active')
        this.$element.addClass('active')
      } else if ($input.prop('type') == 'checkbox') {
        if (($input.prop('checked')) !== this.$element.hasClass('active')) changed = false
        this.$element.toggleClass('active')
      }
      $input.prop('checked', this.$element.hasClass('active'))
      if (changed) $input.trigger('change')
    } else {
      this.$element.attr('aria-pressed', !this.$element.hasClass('active'))
      this.$element.toggleClass('active')
    }
  }


  // BUTTON PLUGIN DEFINITION
  // ========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.button')
      var options = typeof option == 'object' && option

      if (!data) $this.data('bs.button', (data = new Button(this, options)))

      if (option == 'toggle') data.toggle()
      else if (option) data.setState(option)
    })
  }

  var old = $.fn.button

  $.fn.button             = Plugin
  $.fn.button.Constructor = Button


  // BUTTON NO CONFLICT
  // ==================

  $.fn.button.noConflict = function () {
    $.fn.button = old
    return this
  }


  // BUTTON DATA-API
  // ===============

  $(document)
    .on('click.bs.button.data-api', '[data-toggle^="button"]', function (e) {
      var $btn = $(e.target).closest('.btn')
      Plugin.call($btn, 'toggle')
      if (!($(e.target).is('input[type="radio"], input[type="checkbox"]'))) {
        // Prevent double click on radios, and the double selections (so cancellation) on checkboxes
        e.preventDefault()
        // The target component still receive the focus
        if ($btn.is('input,button')) $btn.trigger('focus')
        else $btn.find('input:visible,button:visible').first().trigger('focus')
      }
    })
    .on('focus.bs.button.data-api blur.bs.button.data-api', '[data-toggle^="button"]', function (e) {
      $(e.target).closest('.btn').toggleClass('focus', /^focus(in)?$/.test(e.type))
    })

}(jQuery);

/* ========================================================================
 * Bootstrap: carousel.js v3.3.7
 * http://getbootstrap.com/javascript/#carousel
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // CAROUSEL CLASS DEFINITION
  // =========================

  var Carousel = function (element, options) {
    this.$element    = $(element)
    this.$indicators = this.$element.find('.carousel-indicators')
    this.options     = options
    this.paused      = null
    this.sliding     = null
    this.interval    = null
    this.$active     = null
    this.$items      = null

    this.options.keyboard && this.$element.on('keydown.bs.carousel', $.proxy(this.keydown, this))

    this.options.pause == 'hover' && !('ontouchstart' in document.documentElement) && this.$element
      .on('mouseenter.bs.carousel', $.proxy(this.pause, this))
      .on('mouseleave.bs.carousel', $.proxy(this.cycle, this))
  }

  Carousel.VERSION  = '3.3.7'

  Carousel.TRANSITION_DURATION = 600

  Carousel.DEFAULTS = {
    interval: 5000,
    pause: 'hover',
    wrap: true,
    keyboard: true
  }

  Carousel.prototype.keydown = function (e) {
    if (/input|textarea/i.test(e.target.tagName)) return
    switch (e.which) {
      case 37: this.prev(); break
      case 39: this.next(); break
      default: return
    }

    e.preventDefault()
  }

  Carousel.prototype.cycle = function (e) {
    e || (this.paused = false)

    this.interval && clearInterval(this.interval)

    this.options.interval
      && !this.paused
      && (this.interval = setInterval($.proxy(this.next, this), this.options.interval))

    return this
  }

  Carousel.prototype.getItemIndex = function (item) {
    this.$items = item.parent().children('.item')
    return this.$items.index(item || this.$active)
  }

  Carousel.prototype.getItemForDirection = function (direction, active) {
    var activeIndex = this.getItemIndex(active)
    var willWrap = (direction == 'prev' && activeIndex === 0)
                || (direction == 'next' && activeIndex == (this.$items.length - 1))
    if (willWrap && !this.options.wrap) return active
    var delta = direction == 'prev' ? -1 : 1
    var itemIndex = (activeIndex + delta) % this.$items.length
    return this.$items.eq(itemIndex)
  }

  Carousel.prototype.to = function (pos) {
    var that        = this
    var activeIndex = this.getItemIndex(this.$active = this.$element.find('.item.active'))

    if (pos > (this.$items.length - 1) || pos < 0) return

    if (this.sliding)       return this.$element.one('slid.bs.carousel', function () { that.to(pos) }) // yes, "slid"
    if (activeIndex == pos) return this.pause().cycle()

    return this.slide(pos > activeIndex ? 'next' : 'prev', this.$items.eq(pos))
  }

  Carousel.prototype.pause = function (e) {
    e || (this.paused = true)

    if (this.$element.find('.next, .prev').length && $.support.transition) {
      this.$element.trigger($.support.transition.end)
      this.cycle(true)
    }

    this.interval = clearInterval(this.interval)

    return this
  }

  Carousel.prototype.next = function () {
    if (this.sliding) return
    return this.slide('next')
  }

  Carousel.prototype.prev = function () {
    if (this.sliding) return
    return this.slide('prev')
  }

  Carousel.prototype.slide = function (type, next) {
    var $active   = this.$element.find('.item.active')
    var $next     = next || this.getItemForDirection(type, $active)
    var isCycling = this.interval
    var direction = type == 'next' ? 'left' : 'right'
    var that      = this

    if ($next.hasClass('active')) return (this.sliding = false)

    var relatedTarget = $next[0]
    var slideEvent = $.Event('slide.bs.carousel', {
      relatedTarget: relatedTarget,
      direction: direction
    })
    this.$element.trigger(slideEvent)
    if (slideEvent.isDefaultPrevented()) return

    this.sliding = true

    isCycling && this.pause()

    if (this.$indicators.length) {
      this.$indicators.find('.active').removeClass('active')
      var $nextIndicator = $(this.$indicators.children()[this.getItemIndex($next)])
      $nextIndicator && $nextIndicator.addClass('active')
    }

    var slidEvent = $.Event('slid.bs.carousel', { relatedTarget: relatedTarget, direction: direction }) // yes, "slid"
    if ($.support.transition && this.$element.hasClass('slide')) {
      $next.addClass(type)
      $next[0].offsetWidth // force reflow
      $active.addClass(direction)
      $next.addClass(direction)
      $active
        .one('bsTransitionEnd', function () {
          $next.removeClass([type, direction].join(' ')).addClass('active')
          $active.removeClass(['active', direction].join(' '))
          that.sliding = false
          setTimeout(function () {
            that.$element.trigger(slidEvent)
          }, 0)
        })
        .emulateTransitionEnd(Carousel.TRANSITION_DURATION)
    } else {
      $active.removeClass('active')
      $next.addClass('active')
      this.sliding = false
      this.$element.trigger(slidEvent)
    }

    isCycling && this.cycle()

    return this
  }


  // CAROUSEL PLUGIN DEFINITION
  // ==========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.carousel')
      var options = $.extend({}, Carousel.DEFAULTS, $this.data(), typeof option == 'object' && option)
      var action  = typeof option == 'string' ? option : options.slide

      if (!data) $this.data('bs.carousel', (data = new Carousel(this, options)))
      if (typeof option == 'number') data.to(option)
      else if (action) data[action]()
      else if (options.interval) data.pause().cycle()
    })
  }

  var old = $.fn.carousel

  $.fn.carousel             = Plugin
  $.fn.carousel.Constructor = Carousel


  // CAROUSEL NO CONFLICT
  // ====================

  $.fn.carousel.noConflict = function () {
    $.fn.carousel = old
    return this
  }


  // CAROUSEL DATA-API
  // =================

  var clickHandler = function (e) {
    var href
    var $this   = $(this)
    var $target = $($this.attr('data-target') || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '')) // strip for ie7
    if (!$target.hasClass('carousel')) return
    var options = $.extend({}, $target.data(), $this.data())
    var slideIndex = $this.attr('data-slide-to')
    if (slideIndex) options.interval = false

    Plugin.call($target, options)

    if (slideIndex) {
      $target.data('bs.carousel').to(slideIndex)
    }

    e.preventDefault()
  }

  $(document)
    .on('click.bs.carousel.data-api', '[data-slide]', clickHandler)
    .on('click.bs.carousel.data-api', '[data-slide-to]', clickHandler)

  $(window).on('load', function () {
    $('[data-ride="carousel"]').each(function () {
      var $carousel = $(this)
      Plugin.call($carousel, $carousel.data())
    })
  })

}(jQuery);

/* ========================================================================
 * Bootstrap: collapse.js v3.3.7
 * http://getbootstrap.com/javascript/#collapse
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

/* jshint latedef: false */

+function ($) {
  'use strict';

  // COLLAPSE PUBLIC CLASS DEFINITION
  // ================================

  var Collapse = function (element, options) {
    this.$element      = $(element)
    this.options       = $.extend({}, Collapse.DEFAULTS, options)
    this.$trigger      = $('[data-toggle="collapse"][href="#' + element.id + '"],' +
                           '[data-toggle="collapse"][data-target="#' + element.id + '"]')
    this.transitioning = null

    if (this.options.parent) {
      this.$parent = this.getParent()
    } else {
      this.addAriaAndCollapsedClass(this.$element, this.$trigger)
    }

    if (this.options.toggle) this.toggle()
  }

  Collapse.VERSION  = '3.3.7'

  Collapse.TRANSITION_DURATION = 350

  Collapse.DEFAULTS = {
    toggle: true
  }

  Collapse.prototype.dimension = function () {
    var hasWidth = this.$element.hasClass('width')
    return hasWidth ? 'width' : 'height'
  }

  Collapse.prototype.show = function () {
    if (this.transitioning || this.$element.hasClass('in')) return

    var activesData
    var actives = this.$parent && this.$parent.children('.panel').children('.in, .collapsing')

    if (actives && actives.length) {
      activesData = actives.data('bs.collapse')
      if (activesData && activesData.transitioning) return
    }

    var startEvent = $.Event('show.bs.collapse')
    this.$element.trigger(startEvent)
    if (startEvent.isDefaultPrevented()) return

    if (actives && actives.length) {
      Plugin.call(actives, 'hide')
      activesData || actives.data('bs.collapse', null)
    }

    var dimension = this.dimension()

    this.$element
      .removeClass('collapse')
      .addClass('collapsing')[dimension](0)
      .attr('aria-expanded', true)

    this.$trigger
      .removeClass('collapsed')
      .attr('aria-expanded', true)

    this.transitioning = 1

    var complete = function () {
      this.$element
        .removeClass('collapsing')
        .addClass('collapse in')[dimension]('')
      this.transitioning = 0
      this.$element
        .trigger('shown.bs.collapse')
    }

    if (!$.support.transition) return complete.call(this)

    var scrollSize = $.camelCase(['scroll', dimension].join('-'))

    this.$element
      .one('bsTransitionEnd', $.proxy(complete, this))
      .emulateTransitionEnd(Collapse.TRANSITION_DURATION)[dimension](this.$element[0][scrollSize])
  }

  Collapse.prototype.hide = function () {
    if (this.transitioning || !this.$element.hasClass('in')) return

    var startEvent = $.Event('hide.bs.collapse')
    this.$element.trigger(startEvent)
    if (startEvent.isDefaultPrevented()) return

    var dimension = this.dimension()

    this.$element[dimension](this.$element[dimension]())[0].offsetHeight

    this.$element
      .addClass('collapsing')
      .removeClass('collapse in')
      .attr('aria-expanded', false)

    this.$trigger
      .addClass('collapsed')
      .attr('aria-expanded', false)

    this.transitioning = 1

    var complete = function () {
      this.transitioning = 0
      this.$element
        .removeClass('collapsing')
        .addClass('collapse')
        .trigger('hidden.bs.collapse')
    }

    if (!$.support.transition) return complete.call(this)

    this.$element
      [dimension](0)
      .one('bsTransitionEnd', $.proxy(complete, this))
      .emulateTransitionEnd(Collapse.TRANSITION_DURATION)
  }

  Collapse.prototype.toggle = function () {
    this[this.$element.hasClass('in') ? 'hide' : 'show']()
  }

  Collapse.prototype.getParent = function () {
    return $(this.options.parent)
      .find('[data-toggle="collapse"][data-parent="' + this.options.parent + '"]')
      .each($.proxy(function (i, element) {
        var $element = $(element)
        this.addAriaAndCollapsedClass(getTargetFromTrigger($element), $element)
      }, this))
      .end()
  }

  Collapse.prototype.addAriaAndCollapsedClass = function ($element, $trigger) {
    var isOpen = $element.hasClass('in')

    $element.attr('aria-expanded', isOpen)
    $trigger
      .toggleClass('collapsed', !isOpen)
      .attr('aria-expanded', isOpen)
  }

  function getTargetFromTrigger($trigger) {
    var href
    var target = $trigger.attr('data-target')
      || (href = $trigger.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '') // strip for ie7

    return $(target)
  }


  // COLLAPSE PLUGIN DEFINITION
  // ==========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.collapse')
      var options = $.extend({}, Collapse.DEFAULTS, $this.data(), typeof option == 'object' && option)

      if (!data && options.toggle && /show|hide/.test(option)) options.toggle = false
      if (!data) $this.data('bs.collapse', (data = new Collapse(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  var old = $.fn.collapse

  $.fn.collapse             = Plugin
  $.fn.collapse.Constructor = Collapse


  // COLLAPSE NO CONFLICT
  // ====================

  $.fn.collapse.noConflict = function () {
    $.fn.collapse = old
    return this
  }


  // COLLAPSE DATA-API
  // =================

  $(document).on('click.bs.collapse.data-api', '[data-toggle="collapse"]', function (e) {
    var $this   = $(this)

    if (!$this.attr('data-target')) e.preventDefault()

    var $target = getTargetFromTrigger($this)
    var data    = $target.data('bs.collapse')
    var option  = data ? 'toggle' : $this.data()

    Plugin.call($target, option)
  })

}(jQuery);

/* ========================================================================
 * Bootstrap: dropdown.js v3.3.7
 * http://getbootstrap.com/javascript/#dropdowns
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // DROPDOWN CLASS DEFINITION
  // =========================

  var backdrop = '.dropdown-backdrop'
  var toggle   = '[data-toggle="dropdown"]'
  var Dropdown = function (element) {
    $(element).on('click.bs.dropdown', this.toggle)
  }

  Dropdown.VERSION = '3.3.7'

  function getParent($this) {
    var selector = $this.attr('data-target')

    if (!selector) {
      selector = $this.attr('href')
      selector = selector && /#[A-Za-z]/.test(selector) && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
    }

    var $parent = selector && $(selector)

    return $parent && $parent.length ? $parent : $this.parent()
  }

  function clearMenus(e) {
    if (e && e.which === 3) return
    $(backdrop).remove()
    $(toggle).each(function () {
      var $this         = $(this)
      var $parent       = getParent($this)
      var relatedTarget = { relatedTarget: this }

      if (!$parent.hasClass('open')) return

      if (e && e.type == 'click' && /input|textarea/i.test(e.target.tagName) && $.contains($parent[0], e.target)) return

      $parent.trigger(e = $.Event('hide.bs.dropdown', relatedTarget))

      if (e.isDefaultPrevented()) return

      $this.attr('aria-expanded', 'false')
      $parent.removeClass('open').trigger($.Event('hidden.bs.dropdown', relatedTarget))
    })
  }

  Dropdown.prototype.toggle = function (e) {
    var $this = $(this)

    if ($this.is('.disabled, :disabled')) return

    var $parent  = getParent($this)
    var isActive = $parent.hasClass('open')

    clearMenus()

    if (!isActive) {
      if ('ontouchstart' in document.documentElement && !$parent.closest('.navbar-nav').length) {
        // if mobile we use a backdrop because click events don't delegate
        $(document.createElement('div'))
          .addClass('dropdown-backdrop')
          .insertAfter($(this))
          .on('click', clearMenus)
      }

      var relatedTarget = { relatedTarget: this }
      $parent.trigger(e = $.Event('show.bs.dropdown', relatedTarget))

      if (e.isDefaultPrevented()) return

      $this
        .trigger('focus')
        .attr('aria-expanded', 'true')

      $parent
        .toggleClass('open')
        .trigger($.Event('shown.bs.dropdown', relatedTarget))
    }

    return false
  }

  Dropdown.prototype.keydown = function (e) {
    if (!/(38|40|27|32)/.test(e.which) || /input|textarea/i.test(e.target.tagName)) return

    var $this = $(this)

    e.preventDefault()
    e.stopPropagation()

    if ($this.is('.disabled, :disabled')) return

    var $parent  = getParent($this)
    var isActive = $parent.hasClass('open')

    if (!isActive && e.which != 27 || isActive && e.which == 27) {
      if (e.which == 27) $parent.find(toggle).trigger('focus')
      return $this.trigger('click')
    }

    var desc = ' li:not(.disabled):visible a'
    var $items = $parent.find('.dropdown-menu' + desc)

    if (!$items.length) return

    var index = $items.index(e.target)

    if (e.which == 38 && index > 0)                 index--         // up
    if (e.which == 40 && index < $items.length - 1) index++         // down
    if (!~index)                                    index = 0

    $items.eq(index).trigger('focus')
  }


  // DROPDOWN PLUGIN DEFINITION
  // ==========================

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var data  = $this.data('bs.dropdown')

      if (!data) $this.data('bs.dropdown', (data = new Dropdown(this)))
      if (typeof option == 'string') data[option].call($this)
    })
  }

  var old = $.fn.dropdown

  $.fn.dropdown             = Plugin
  $.fn.dropdown.Constructor = Dropdown


  // DROPDOWN NO CONFLICT
  // ====================

  $.fn.dropdown.noConflict = function () {
    $.fn.dropdown = old
    return this
  }


  // APPLY TO STANDARD DROPDOWN ELEMENTS
  // ===================================

  $(document)
    .on('click.bs.dropdown.data-api', clearMenus)
    .on('click.bs.dropdown.data-api', '.dropdown form', function (e) { e.stopPropagation() })
    .on('click.bs.dropdown.data-api', toggle, Dropdown.prototype.toggle)
    .on('keydown.bs.dropdown.data-api', toggle, Dropdown.prototype.keydown)
    .on('keydown.bs.dropdown.data-api', '.dropdown-menu', Dropdown.prototype.keydown)

}(jQuery);

/* ========================================================================
 * Bootstrap: modal.js v3.3.7
 * http://getbootstrap.com/javascript/#modals
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // MODAL CLASS DEFINITION
  // ======================

  var Modal = function (element, options) {
    this.options             = options
    this.$body               = $(document.body)
    this.$element            = $(element)
    this.$dialog             = this.$element.find('.modal-dialog')
    this.$backdrop           = null
    this.isShown             = null
    this.originalBodyPad     = null
    this.scrollbarWidth      = 0
    this.ignoreBackdropClick = false

    if (this.options.remote) {
      this.$element
        .find('.modal-content')
        .load(this.options.remote, $.proxy(function () {
          this.$element.trigger('loaded.bs.modal')
        }, this))
    }
  }

  Modal.VERSION  = '3.3.7'

  Modal.TRANSITION_DURATION = 300
  Modal.BACKDROP_TRANSITION_DURATION = 150

  Modal.DEFAULTS = {
    backdrop: true,
    keyboard: true,
    show: true
  }

  Modal.prototype.toggle = function (_relatedTarget) {
    return this.isShown ? this.hide() : this.show(_relatedTarget)
  }

  Modal.prototype.show = function (_relatedTarget) {
    var that = this
    var e    = $.Event('show.bs.modal', { relatedTarget: _relatedTarget })

    this.$element.trigger(e)

    if (this.isShown || e.isDefaultPrevented()) return

    this.isShown = true

    this.checkScrollbar()
    this.setScrollbar()
    this.$body.addClass('modal-open')

    this.escape()
    this.resize()

    this.$element.on('click.dismiss.bs.modal', '[data-dismiss="modal"]', $.proxy(this.hide, this))

    this.$dialog.on('mousedown.dismiss.bs.modal', function () {
      that.$element.one('mouseup.dismiss.bs.modal', function (e) {
        if ($(e.target).is(that.$element)) that.ignoreBackdropClick = true
      })
    })

    this.backdrop(function () {
      var transition = $.support.transition && that.$element.hasClass('fade')

      if (!that.$element.parent().length) {
        that.$element.appendTo(that.$body) // don't move modals dom position
      }

      that.$element
        .show()
        .scrollTop(0)

      that.adjustDialog()

      if (transition) {
        that.$element[0].offsetWidth // force reflow
      }

      that.$element.addClass('in')

      that.enforceFocus()

      var e = $.Event('shown.bs.modal', { relatedTarget: _relatedTarget })

      transition ?
        that.$dialog // wait for modal to slide in
          .one('bsTransitionEnd', function () {
            that.$element.trigger('focus').trigger(e)
          })
          .emulateTransitionEnd(Modal.TRANSITION_DURATION) :
        that.$element.trigger('focus').trigger(e)
    })
  }

  Modal.prototype.hide = function (e) {
    if (e) e.preventDefault()

    e = $.Event('hide.bs.modal')

    this.$element.trigger(e)

    if (!this.isShown || e.isDefaultPrevented()) return

    this.isShown = false

    this.escape()
    this.resize()

    $(document).off('focusin.bs.modal')

    this.$element
      .removeClass('in')
      .off('click.dismiss.bs.modal')
      .off('mouseup.dismiss.bs.modal')

    this.$dialog.off('mousedown.dismiss.bs.modal')

    $.support.transition && this.$element.hasClass('fade') ?
      this.$element
        .one('bsTransitionEnd', $.proxy(this.hideModal, this))
        .emulateTransitionEnd(Modal.TRANSITION_DURATION) :
      this.hideModal()
  }

  Modal.prototype.enforceFocus = function () {
    $(document)
      .off('focusin.bs.modal') // guard against infinite focus loop
      .on('focusin.bs.modal', $.proxy(function (e) {
        if (document !== e.target &&
            this.$element[0] !== e.target &&
            !this.$element.has(e.target).length) {
          this.$element.trigger('focus')
        }
      }, this))
  }

  Modal.prototype.escape = function () {
    if (this.isShown && this.options.keyboard) {
      this.$element.on('keydown.dismiss.bs.modal', $.proxy(function (e) {
        e.which == 27 && this.hide()
      }, this))
    } else if (!this.isShown) {
      this.$element.off('keydown.dismiss.bs.modal')
    }
  }

  Modal.prototype.resize = function () {
    if (this.isShown) {
      $(window).on('resize.bs.modal', $.proxy(this.handleUpdate, this))
    } else {
      $(window).off('resize.bs.modal')
    }
  }

  Modal.prototype.hideModal = function () {
    var that = this
    this.$element.hide()
    this.backdrop(function () {
      that.$body.removeClass('modal-open')
      that.resetAdjustments()
      that.resetScrollbar()
      that.$element.trigger('hidden.bs.modal')
    })
  }

  Modal.prototype.removeBackdrop = function () {
    this.$backdrop && this.$backdrop.remove()
    this.$backdrop = null
  }

  Modal.prototype.backdrop = function (callback) {
    var that = this
    var animate = this.$element.hasClass('fade') ? 'fade' : ''

    if (this.isShown && this.options.backdrop) {
      var doAnimate = $.support.transition && animate

      this.$backdrop = $(document.createElement('div'))
        .addClass('modal-backdrop ' + animate)
        .appendTo(this.$body)

      this.$element.on('click.dismiss.bs.modal', $.proxy(function (e) {
        if (this.ignoreBackdropClick) {
          this.ignoreBackdropClick = false
          return
        }
        if (e.target !== e.currentTarget) return
        this.options.backdrop == 'static'
          ? this.$element[0].focus()
          : this.hide()
      }, this))

      if (doAnimate) this.$backdrop[0].offsetWidth // force reflow

      this.$backdrop.addClass('in')

      if (!callback) return

      doAnimate ?
        this.$backdrop
          .one('bsTransitionEnd', callback)
          .emulateTransitionEnd(Modal.BACKDROP_TRANSITION_DURATION) :
        callback()

    } else if (!this.isShown && this.$backdrop) {
      this.$backdrop.removeClass('in')

      var callbackRemove = function () {
        that.removeBackdrop()
        callback && callback()
      }
      $.support.transition && this.$element.hasClass('fade') ?
        this.$backdrop
          .one('bsTransitionEnd', callbackRemove)
          .emulateTransitionEnd(Modal.BACKDROP_TRANSITION_DURATION) :
        callbackRemove()

    } else if (callback) {
      callback()
    }
  }

  // these following methods are used to handle overflowing modals

  Modal.prototype.handleUpdate = function () {
    this.adjustDialog()
  }

  Modal.prototype.adjustDialog = function () {
    var modalIsOverflowing = this.$element[0].scrollHeight > document.documentElement.clientHeight

    this.$element.css({
      paddingLeft:  !this.bodyIsOverflowing && modalIsOverflowing ? this.scrollbarWidth : '',
      paddingRight: this.bodyIsOverflowing && !modalIsOverflowing ? this.scrollbarWidth : ''
    })
  }

  Modal.prototype.resetAdjustments = function () {
    this.$element.css({
      paddingLeft: '',
      paddingRight: ''
    })
  }

  Modal.prototype.checkScrollbar = function () {
    var fullWindowWidth = window.innerWidth
    if (!fullWindowWidth) { // workaround for missing window.innerWidth in IE8
      var documentElementRect = document.documentElement.getBoundingClientRect()
      fullWindowWidth = documentElementRect.right - Math.abs(documentElementRect.left)
    }
    this.bodyIsOverflowing = document.body.clientWidth < fullWindowWidth
    this.scrollbarWidth = this.measureScrollbar()
  }

  Modal.prototype.setScrollbar = function () {
    var bodyPad = parseInt((this.$body.css('padding-right') || 0), 10)
    this.originalBodyPad = document.body.style.paddingRight || ''
    if (this.bodyIsOverflowing) this.$body.css('padding-right', bodyPad + this.scrollbarWidth)
  }

  Modal.prototype.resetScrollbar = function () {
    this.$body.css('padding-right', this.originalBodyPad)
  }

  Modal.prototype.measureScrollbar = function () { // thx walsh
    var scrollDiv = document.createElement('div')
    scrollDiv.className = 'modal-scrollbar-measure'
    this.$body.append(scrollDiv)
    var scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth
    this.$body[0].removeChild(scrollDiv)
    return scrollbarWidth
  }


  // MODAL PLUGIN DEFINITION
  // =======================

  function Plugin(option, _relatedTarget) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.modal')
      var options = $.extend({}, Modal.DEFAULTS, $this.data(), typeof option == 'object' && option)

      if (!data) $this.data('bs.modal', (data = new Modal(this, options)))
      if (typeof option == 'string') data[option](_relatedTarget)
      else if (options.show) data.show(_relatedTarget)
    })
  }

  var old = $.fn.modal

  $.fn.modal             = Plugin
  $.fn.modal.Constructor = Modal


  // MODAL NO CONFLICT
  // =================

  $.fn.modal.noConflict = function () {
    $.fn.modal = old
    return this
  }


  // MODAL DATA-API
  // ==============

  $(document).on('click.bs.modal.data-api', '[data-toggle="modal"]', function (e) {
    var $this   = $(this)
    var href    = $this.attr('href')
    var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))) // strip for ie7
    var option  = $target.data('bs.modal') ? 'toggle' : $.extend({ remote: !/#/.test(href) && href }, $target.data(), $this.data())

    if ($this.is('a')) e.preventDefault()

    $target.one('show.bs.modal', function (showEvent) {
      if (showEvent.isDefaultPrevented()) return // only register focus restorer if modal will actually get shown
      $target.one('hidden.bs.modal', function () {
        $this.is(':visible') && $this.trigger('focus')
      })
    })
    Plugin.call($target, option, this)
  })

}(jQuery);

/* ========================================================================
 * Bootstrap: tooltip.js v3.3.7
 * http://getbootstrap.com/javascript/#tooltip
 * Inspired by the original jQuery.tipsy by Jason Frame
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // TOOLTIP PUBLIC CLASS DEFINITION
  // ===============================

  var Tooltip = function (element, options) {
    this.type       = null
    this.options    = null
    this.enabled    = null
    this.timeout    = null
    this.hoverState = null
    this.$element   = null
    this.inState    = null

    this.init('tooltip', element, options)
  }

  Tooltip.VERSION  = '3.3.7'

  Tooltip.TRANSITION_DURATION = 150

  Tooltip.DEFAULTS = {
    animation: true,
    placement: 'top',
    selector: false,
    template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
    trigger: 'hover focus',
    title: '',
    delay: 0,
    html: false,
    container: false,
    viewport: {
      selector: 'body',
      padding: 0
    }
  }

  Tooltip.prototype.init = function (type, element, options) {
    this.enabled   = true
    this.type      = type
    this.$element  = $(element)
    this.options   = this.getOptions(options)
    this.$viewport = this.options.viewport && $($.isFunction(this.options.viewport) ? this.options.viewport.call(this, this.$element) : (this.options.viewport.selector || this.options.viewport))
    this.inState   = { click: false, hover: false, focus: false }

    if (this.$element[0] instanceof document.constructor && !this.options.selector) {
      throw new Error('`selector` option must be specified when initializing ' + this.type + ' on the window.document object!')
    }

    var triggers = this.options.trigger.split(' ')

    for (var i = triggers.length; i--;) {
      var trigger = triggers[i]

      if (trigger == 'click') {
        this.$element.on('click.' + this.type, this.options.selector, $.proxy(this.toggle, this))
      } else if (trigger != 'manual') {
        var eventIn  = trigger == 'hover' ? 'mouseenter' : 'focusin'
        var eventOut = trigger == 'hover' ? 'mouseleave' : 'focusout'

        this.$element.on(eventIn  + '.' + this.type, this.options.selector, $.proxy(this.enter, this))
        this.$element.on(eventOut + '.' + this.type, this.options.selector, $.proxy(this.leave, this))
      }
    }

    this.options.selector ?
      (this._options = $.extend({}, this.options, { trigger: 'manual', selector: '' })) :
      this.fixTitle()
  }

  Tooltip.prototype.getDefaults = function () {
    return Tooltip.DEFAULTS
  }

  Tooltip.prototype.getOptions = function (options) {
    options = $.extend({}, this.getDefaults(), this.$element.data(), options)

    if (options.delay && typeof options.delay == 'number') {
      options.delay = {
        show: options.delay,
        hide: options.delay
      }
    }

    return options
  }

  Tooltip.prototype.getDelegateOptions = function () {
    var options  = {}
    var defaults = this.getDefaults()

    this._options && $.each(this._options, function (key, value) {
      if (defaults[key] != value) options[key] = value
    })

    return options
  }

  Tooltip.prototype.enter = function (obj) {
    var self = obj instanceof this.constructor ?
      obj : $(obj.currentTarget).data('bs.' + this.type)

    if (!self) {
      self = new this.constructor(obj.currentTarget, this.getDelegateOptions())
      $(obj.currentTarget).data('bs.' + this.type, self)
    }

    if (obj instanceof $.Event) {
      self.inState[obj.type == 'focusin' ? 'focus' : 'hover'] = true
    }

    if (self.tip().hasClass('in') || self.hoverState == 'in') {
      self.hoverState = 'in'
      return
    }

    clearTimeout(self.timeout)

    self.hoverState = 'in'

    if (!self.options.delay || !self.options.delay.show) return self.show()

    self.timeout = setTimeout(function () {
      if (self.hoverState == 'in') self.show()
    }, self.options.delay.show)
  }

  Tooltip.prototype.isInStateTrue = function () {
    for (var key in this.inState) {
      if (this.inState[key]) return true
    }

    return false
  }

  Tooltip.prototype.leave = function (obj) {
    var self = obj instanceof this.constructor ?
      obj : $(obj.currentTarget).data('bs.' + this.type)

    if (!self) {
      self = new this.constructor(obj.currentTarget, this.getDelegateOptions())
      $(obj.currentTarget).data('bs.' + this.type, self)
    }

    if (obj instanceof $.Event) {
      self.inState[obj.type == 'focusout' ? 'focus' : 'hover'] = false
    }

    if (self.isInStateTrue()) return

    clearTimeout(self.timeout)

    self.hoverState = 'out'

    if (!self.options.delay || !self.options.delay.hide) return self.hide()

    self.timeout = setTimeout(function () {
      if (self.hoverState == 'out') self.hide()
    }, self.options.delay.hide)
  }

  Tooltip.prototype.show = function () {
    var e = $.Event('show.bs.' + this.type)

    if (this.hasContent() && this.enabled) {
      this.$element.trigger(e)

      var inDom = $.contains(this.$element[0].ownerDocument.documentElement, this.$element[0])
      if (e.isDefaultPrevented() || !inDom) return
      var that = this

      var $tip = this.tip()

      var tipId = this.getUID(this.type)

      this.setContent()
      $tip.attr('id', tipId)
      this.$element.attr('aria-describedby', tipId)

      if (this.options.animation) $tip.addClass('fade')

      var placement = typeof this.options.placement == 'function' ?
        this.options.placement.call(this, $tip[0], this.$element[0]) :
        this.options.placement

      var autoToken = /\s?auto?\s?/i
      var autoPlace = autoToken.test(placement)
      if (autoPlace) placement = placement.replace(autoToken, '') || 'top'

      $tip
        .detach()
        .css({ top: 0, left: 0, display: 'block' })
        .addClass(placement)
        .data('bs.' + this.type, this)

      this.options.container ? $tip.appendTo(this.options.container) : $tip.insertAfter(this.$element)
      this.$element.trigger('inserted.bs.' + this.type)

      var pos          = this.getPosition()
      var actualWidth  = $tip[0].offsetWidth
      var actualHeight = $tip[0].offsetHeight

      if (autoPlace) {
        var orgPlacement = placement
        var viewportDim = this.getPosition(this.$viewport)

        placement = placement == 'bottom' && pos.bottom + actualHeight > viewportDim.bottom ? 'top'    :
                    placement == 'top'    && pos.top    - actualHeight < viewportDim.top    ? 'bottom' :
                    placement == 'right'  && pos.right  + actualWidth  > viewportDim.width  ? 'left'   :
                    placement == 'left'   && pos.left   - actualWidth  < viewportDim.left   ? 'right'  :
                    placement

        $tip
          .removeClass(orgPlacement)
          .addClass(placement)
      }

      var calculatedOffset = this.getCalculatedOffset(placement, pos, actualWidth, actualHeight)

      this.applyPlacement(calculatedOffset, placement)

      var complete = function () {
        var prevHoverState = that.hoverState
        that.$element.trigger('shown.bs.' + that.type)
        that.hoverState = null

        if (prevHoverState == 'out') that.leave(that)
      }

      $.support.transition && this.$tip.hasClass('fade') ?
        $tip
          .one('bsTransitionEnd', complete)
          .emulateTransitionEnd(Tooltip.TRANSITION_DURATION) :
        complete()
    }
  }

  Tooltip.prototype.applyPlacement = function (offset, placement) {
    var $tip   = this.tip()
    var width  = $tip[0].offsetWidth
    var height = $tip[0].offsetHeight

    // manually read margins because getBoundingClientRect includes difference
    var marginTop = parseInt($tip.css('margin-top'), 10)
    var marginLeft = parseInt($tip.css('margin-left'), 10)

    // we must check for NaN for ie 8/9
    if (isNaN(marginTop))  marginTop  = 0
    if (isNaN(marginLeft)) marginLeft = 0

    offset.top  += marginTop
    offset.left += marginLeft

    // $.fn.offset doesn't round pixel values
    // so we use setOffset directly with our own function B-0
    $.offset.setOffset($tip[0], $.extend({
      using: function (props) {
        $tip.css({
          top: Math.round(props.top),
          left: Math.round(props.left)
        })
      }
    }, offset), 0)

    $tip.addClass('in')

    // check to see if placing tip in new offset caused the tip to resize itself
    var actualWidth  = $tip[0].offsetWidth
    var actualHeight = $tip[0].offsetHeight

    if (placement == 'top' && actualHeight != height) {
      offset.top = offset.top + height - actualHeight
    }

    var delta = this.getViewportAdjustedDelta(placement, offset, actualWidth, actualHeight)

    if (delta.left) offset.left += delta.left
    else offset.top += delta.top

    var isVertical          = /top|bottom/.test(placement)
    var arrowDelta          = isVertical ? delta.left * 2 - width + actualWidth : delta.top * 2 - height + actualHeight
    var arrowOffsetPosition = isVertical ? 'offsetWidth' : 'offsetHeight'

    $tip.offset(offset)
    this.replaceArrow(arrowDelta, $tip[0][arrowOffsetPosition], isVertical)
  }

  Tooltip.prototype.replaceArrow = function (delta, dimension, isVertical) {
    this.arrow()
      .css(isVertical ? 'left' : 'top', 50 * (1 - delta / dimension) + '%')
      .css(isVertical ? 'top' : 'left', '')
  }

  Tooltip.prototype.setContent = function () {
    var $tip  = this.tip()
    var title = this.getTitle()

    $tip.find('.tooltip-inner')[this.options.html ? 'html' : 'text'](title)
    $tip.removeClass('fade in top bottom left right')
  }

  Tooltip.prototype.hide = function (callback) {
    var that = this
    var $tip = $(this.$tip)
    var e    = $.Event('hide.bs.' + this.type)

    function complete() {
      if (that.hoverState != 'in') $tip.detach()
      if (that.$element) { // TODO: Check whether guarding this code with this `if` is really necessary.
        that.$element
          .removeAttr('aria-describedby')
          .trigger('hidden.bs.' + that.type)
      }
      callback && callback()
    }

    this.$element.trigger(e)

    if (e.isDefaultPrevented()) return

    $tip.removeClass('in')

    $.support.transition && $tip.hasClass('fade') ?
      $tip
        .one('bsTransitionEnd', complete)
        .emulateTransitionEnd(Tooltip.TRANSITION_DURATION) :
      complete()

    this.hoverState = null

    return this
  }

  Tooltip.prototype.fixTitle = function () {
    var $e = this.$element
    if ($e.attr('title') || typeof $e.attr('data-original-title') != 'string') {
      $e.attr('data-original-title', $e.attr('title') || '').attr('title', '')
    }
  }

  Tooltip.prototype.hasContent = function () {
    return this.getTitle()
  }

  Tooltip.prototype.getPosition = function ($element) {
    $element   = $element || this.$element

    var el     = $element[0]
    var isBody = el.tagName == 'BODY'

    var elRect    = el.getBoundingClientRect()
    if (elRect.width == null) {
      // width and height are missing in IE8, so compute them manually; see https://github.com/twbs/bootstrap/issues/14093
      elRect = $.extend({}, elRect, { width: elRect.right - elRect.left, height: elRect.bottom - elRect.top })
    }
    var isSvg = window.SVGElement && el instanceof window.SVGElement
    // Avoid using $.offset() on SVGs since it gives incorrect results in jQuery 3.
    // See https://github.com/twbs/bootstrap/issues/20280
    var elOffset  = isBody ? { top: 0, left: 0 } : (isSvg ? null : $element.offset())
    var scroll    = { scroll: isBody ? document.documentElement.scrollTop || document.body.scrollTop : $element.scrollTop() }
    var outerDims = isBody ? { width: $(window).width(), height: $(window).height() } : null

    return $.extend({}, elRect, scroll, outerDims, elOffset)
  }

  Tooltip.prototype.getCalculatedOffset = function (placement, pos, actualWidth, actualHeight) {
    return placement == 'bottom' ? { top: pos.top + pos.height,   left: pos.left + pos.width / 2 - actualWidth / 2 } :
           placement == 'top'    ? { top: pos.top - actualHeight, left: pos.left + pos.width / 2 - actualWidth / 2 } :
           placement == 'left'   ? { top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth } :
        /* placement == 'right' */ { top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width }

  }

  Tooltip.prototype.getViewportAdjustedDelta = function (placement, pos, actualWidth, actualHeight) {
    var delta = { top: 0, left: 0 }
    if (!this.$viewport) return delta

    var viewportPadding = this.options.viewport && this.options.viewport.padding || 0
    var viewportDimensions = this.getPosition(this.$viewport)

    if (/right|left/.test(placement)) {
      var topEdgeOffset    = pos.top - viewportPadding - viewportDimensions.scroll
      var bottomEdgeOffset = pos.top + viewportPadding - viewportDimensions.scroll + actualHeight
      if (topEdgeOffset < viewportDimensions.top) { // top overflow
        delta.top = viewportDimensions.top - topEdgeOffset
      } else if (bottomEdgeOffset > viewportDimensions.top + viewportDimensions.height) { // bottom overflow
        delta.top = viewportDimensions.top + viewportDimensions.height - bottomEdgeOffset
      }
    } else {
      var leftEdgeOffset  = pos.left - viewportPadding
      var rightEdgeOffset = pos.left + viewportPadding + actualWidth
      if (leftEdgeOffset < viewportDimensions.left) { // left overflow
        delta.left = viewportDimensions.left - leftEdgeOffset
      } else if (rightEdgeOffset > viewportDimensions.right) { // right overflow
        delta.left = viewportDimensions.left + viewportDimensions.width - rightEdgeOffset
      }
    }

    return delta
  }

  Tooltip.prototype.getTitle = function () {
    var title
    var $e = this.$element
    var o  = this.options

    title = $e.attr('data-original-title')
      || (typeof o.title == 'function' ? o.title.call($e[0]) :  o.title)

    return title
  }

  Tooltip.prototype.getUID = function (prefix) {
    do prefix += ~~(Math.random() * 1000000)
    while (document.getElementById(prefix))
    return prefix
  }

  Tooltip.prototype.tip = function () {
    if (!this.$tip) {
      this.$tip = $(this.options.template)
      if (this.$tip.length != 1) {
        throw new Error(this.type + ' `template` option must consist of exactly 1 top-level element!')
      }
    }
    return this.$tip
  }

  Tooltip.prototype.arrow = function () {
    return (this.$arrow = this.$arrow || this.tip().find('.tooltip-arrow'))
  }

  Tooltip.prototype.enable = function () {
    this.enabled = true
  }

  Tooltip.prototype.disable = function () {
    this.enabled = false
  }

  Tooltip.prototype.toggleEnabled = function () {
    this.enabled = !this.enabled
  }

  Tooltip.prototype.toggle = function (e) {
    var self = this
    if (e) {
      self = $(e.currentTarget).data('bs.' + this.type)
      if (!self) {
        self = new this.constructor(e.currentTarget, this.getDelegateOptions())
        $(e.currentTarget).data('bs.' + this.type, self)
      }
    }

    if (e) {
      self.inState.click = !self.inState.click
      if (self.isInStateTrue()) self.enter(self)
      else self.leave(self)
    } else {
      self.tip().hasClass('in') ? self.leave(self) : self.enter(self)
    }
  }

  Tooltip.prototype.destroy = function () {
    var that = this
    clearTimeout(this.timeout)
    this.hide(function () {
      that.$element.off('.' + that.type).removeData('bs.' + that.type)
      if (that.$tip) {
        that.$tip.detach()
      }
      that.$tip = null
      that.$arrow = null
      that.$viewport = null
      that.$element = null
    })
  }


  // TOOLTIP PLUGIN DEFINITION
  // =========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.tooltip')
      var options = typeof option == 'object' && option

      if (!data && /destroy|hide/.test(option)) return
      if (!data) $this.data('bs.tooltip', (data = new Tooltip(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  var old = $.fn.tooltip

  $.fn.tooltip             = Plugin
  $.fn.tooltip.Constructor = Tooltip


  // TOOLTIP NO CONFLICT
  // ===================

  $.fn.tooltip.noConflict = function () {
    $.fn.tooltip = old
    return this
  }

}(jQuery);

/* ========================================================================
 * Bootstrap: popover.js v3.3.7
 * http://getbootstrap.com/javascript/#popovers
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // POPOVER PUBLIC CLASS DEFINITION
  // ===============================

  var Popover = function (element, options) {
    this.init('popover', element, options)
  }

  if (!$.fn.tooltip) throw new Error('Popover requires tooltip.js')

  Popover.VERSION  = '3.3.7'

  Popover.DEFAULTS = $.extend({}, $.fn.tooltip.Constructor.DEFAULTS, {
    placement: 'right',
    trigger: 'click',
    content: '',
    template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
  })


  // NOTE: POPOVER EXTENDS tooltip.js
  // ================================

  Popover.prototype = $.extend({}, $.fn.tooltip.Constructor.prototype)

  Popover.prototype.constructor = Popover

  Popover.prototype.getDefaults = function () {
    return Popover.DEFAULTS
  }

  Popover.prototype.setContent = function () {
    var $tip    = this.tip()
    var title   = this.getTitle()
    var content = this.getContent()

    $tip.find('.popover-title')[this.options.html ? 'html' : 'text'](title)
    $tip.find('.popover-content').children().detach().end()[ // we use append for html objects to maintain js events
      this.options.html ? (typeof content == 'string' ? 'html' : 'append') : 'text'
    ](content)

    $tip.removeClass('fade top bottom left right in')

    // IE8 doesn't accept hiding via the `:empty` pseudo selector, we have to do
    // this manually by checking the contents.
    if (!$tip.find('.popover-title').html()) $tip.find('.popover-title').hide()
  }

  Popover.prototype.hasContent = function () {
    return this.getTitle() || this.getContent()
  }

  Popover.prototype.getContent = function () {
    var $e = this.$element
    var o  = this.options

    return $e.attr('data-content')
      || (typeof o.content == 'function' ?
            o.content.call($e[0]) :
            o.content)
  }

  Popover.prototype.arrow = function () {
    return (this.$arrow = this.$arrow || this.tip().find('.arrow'))
  }


  // POPOVER PLUGIN DEFINITION
  // =========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.popover')
      var options = typeof option == 'object' && option

      if (!data && /destroy|hide/.test(option)) return
      if (!data) $this.data('bs.popover', (data = new Popover(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  var old = $.fn.popover

  $.fn.popover             = Plugin
  $.fn.popover.Constructor = Popover


  // POPOVER NO CONFLICT
  // ===================

  $.fn.popover.noConflict = function () {
    $.fn.popover = old
    return this
  }

}(jQuery);

/* ========================================================================
 * Bootstrap: scrollspy.js v3.3.7
 * http://getbootstrap.com/javascript/#scrollspy
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // SCROLLSPY CLASS DEFINITION
  // ==========================

  function ScrollSpy(element, options) {
    this.$body          = $(document.body)
    this.$scrollElement = $(element).is(document.body) ? $(window) : $(element)
    this.options        = $.extend({}, ScrollSpy.DEFAULTS, options)
    this.selector       = (this.options.target || '') + ' .nav li > a'
    this.offsets        = []
    this.targets        = []
    this.activeTarget   = null
    this.scrollHeight   = 0

    this.$scrollElement.on('scroll.bs.scrollspy', $.proxy(this.process, this))
    this.refresh()
    this.process()
  }

  ScrollSpy.VERSION  = '3.3.7'

  ScrollSpy.DEFAULTS = {
    offset: 10
  }

  ScrollSpy.prototype.getScrollHeight = function () {
    return this.$scrollElement[0].scrollHeight || Math.max(this.$body[0].scrollHeight, document.documentElement.scrollHeight)
  }

  ScrollSpy.prototype.refresh = function () {
    var that          = this
    var offsetMethod  = 'offset'
    var offsetBase    = 0

    this.offsets      = []
    this.targets      = []
    this.scrollHeight = this.getScrollHeight()

    if (!$.isWindow(this.$scrollElement[0])) {
      offsetMethod = 'position'
      offsetBase   = this.$scrollElement.scrollTop()
    }

    this.$body
      .find(this.selector)
      .map(function () {
        var $el   = $(this)
        var href  = $el.data('target') || $el.attr('href')
        var $href = /^#./.test(href) && $(href)

        return ($href
          && $href.length
          && $href.is(':visible')
          && [[$href[offsetMethod]().top + offsetBase, href]]) || null
      })
      .sort(function (a, b) { return a[0] - b[0] })
      .each(function () {
        that.offsets.push(this[0])
        that.targets.push(this[1])
      })
  }

  ScrollSpy.prototype.process = function () {
    var scrollTop    = this.$scrollElement.scrollTop() + this.options.offset
    var scrollHeight = this.getScrollHeight()
    var maxScroll    = this.options.offset + scrollHeight - this.$scrollElement.height()
    var offsets      = this.offsets
    var targets      = this.targets
    var activeTarget = this.activeTarget
    var i

    if (this.scrollHeight != scrollHeight) {
      this.refresh()
    }

    if (scrollTop >= maxScroll) {
      return activeTarget != (i = targets[targets.length - 1]) && this.activate(i)
    }

    if (activeTarget && scrollTop < offsets[0]) {
      this.activeTarget = null
      return this.clear()
    }

    for (i = offsets.length; i--;) {
      activeTarget != targets[i]
        && scrollTop >= offsets[i]
        && (offsets[i + 1] === undefined || scrollTop < offsets[i + 1])
        && this.activate(targets[i])
    }
  }

  ScrollSpy.prototype.activate = function (target) {
    this.activeTarget = target

    this.clear()

    var selector = this.selector +
      '[data-target="' + target + '"],' +
      this.selector + '[href="' + target + '"]'

    var active = $(selector)
      .parents('li')
      .addClass('active')

    if (active.parent('.dropdown-menu').length) {
      active = active
        .closest('li.dropdown')
        .addClass('active')
    }

    active.trigger('activate.bs.scrollspy')
  }

  ScrollSpy.prototype.clear = function () {
    $(this.selector)
      .parentsUntil(this.options.target, '.active')
      .removeClass('active')
  }


  // SCROLLSPY PLUGIN DEFINITION
  // ===========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.scrollspy')
      var options = typeof option == 'object' && option

      if (!data) $this.data('bs.scrollspy', (data = new ScrollSpy(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  var old = $.fn.scrollspy

  $.fn.scrollspy             = Plugin
  $.fn.scrollspy.Constructor = ScrollSpy


  // SCROLLSPY NO CONFLICT
  // =====================

  $.fn.scrollspy.noConflict = function () {
    $.fn.scrollspy = old
    return this
  }


  // SCROLLSPY DATA-API
  // ==================

  $(window).on('load.bs.scrollspy.data-api', function () {
    $('[data-spy="scroll"]').each(function () {
      var $spy = $(this)
      Plugin.call($spy, $spy.data())
    })
  })

}(jQuery);

/* ========================================================================
 * Bootstrap: tab.js v3.3.7
 * http://getbootstrap.com/javascript/#tabs
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // TAB CLASS DEFINITION
  // ====================

  var Tab = function (element) {
    // jscs:disable requireDollarBeforejQueryAssignment
    this.element = $(element)
    // jscs:enable requireDollarBeforejQueryAssignment
  }

  Tab.VERSION = '3.3.7'

  Tab.TRANSITION_DURATION = 150

  Tab.prototype.show = function () {
    var $this    = this.element
    var $ul      = $this.closest('ul:not(.dropdown-menu)')
    var selector = $this.data('target')

    if (!selector) {
      selector = $this.attr('href')
      selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
    }

    if ($this.parent('li').hasClass('active')) return

    var $previous = $ul.find('.active:last a')
    var hideEvent = $.Event('hide.bs.tab', {
      relatedTarget: $this[0]
    })
    var showEvent = $.Event('show.bs.tab', {
      relatedTarget: $previous[0]
    })

    $previous.trigger(hideEvent)
    $this.trigger(showEvent)

    if (showEvent.isDefaultPrevented() || hideEvent.isDefaultPrevented()) return

    var $target = $(selector)

    this.activate($this.closest('li'), $ul)
    this.activate($target, $target.parent(), function () {
      $previous.trigger({
        type: 'hidden.bs.tab',
        relatedTarget: $this[0]
      })
      $this.trigger({
        type: 'shown.bs.tab',
        relatedTarget: $previous[0]
      })
    })
  }

  Tab.prototype.activate = function (element, container, callback) {
    var $active    = container.find('> .active')
    var transition = callback
      && $.support.transition
      && ($active.length && $active.hasClass('fade') || !!container.find('> .fade').length)

    function next() {
      $active
        .removeClass('active')
        .find('> .dropdown-menu > .active')
          .removeClass('active')
        .end()
        .find('[data-toggle="tab"]')
          .attr('aria-expanded', false)

      element
        .addClass('active')
        .find('[data-toggle="tab"]')
          .attr('aria-expanded', true)

      if (transition) {
        element[0].offsetWidth // reflow for transition
        element.addClass('in')
      } else {
        element.removeClass('fade')
      }

      if (element.parent('.dropdown-menu').length) {
        element
          .closest('li.dropdown')
            .addClass('active')
          .end()
          .find('[data-toggle="tab"]')
            .attr('aria-expanded', true)
      }

      callback && callback()
    }

    $active.length && transition ?
      $active
        .one('bsTransitionEnd', next)
        .emulateTransitionEnd(Tab.TRANSITION_DURATION) :
      next()

    $active.removeClass('in')
  }


  // TAB PLUGIN DEFINITION
  // =====================

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var data  = $this.data('bs.tab')

      if (!data) $this.data('bs.tab', (data = new Tab(this)))
      if (typeof option == 'string') data[option]()
    })
  }

  var old = $.fn.tab

  $.fn.tab             = Plugin
  $.fn.tab.Constructor = Tab


  // TAB NO CONFLICT
  // ===============

  $.fn.tab.noConflict = function () {
    $.fn.tab = old
    return this
  }


  // TAB DATA-API
  // ============

  var clickHandler = function (e) {
    e.preventDefault()
    Plugin.call($(this), 'show')
  }

  $(document)
    .on('click.bs.tab.data-api', '[data-toggle="tab"]', clickHandler)
    .on('click.bs.tab.data-api', '[data-toggle="pill"]', clickHandler)

}(jQuery);

/* ========================================================================
 * Bootstrap: affix.js v3.3.7
 * http://getbootstrap.com/javascript/#affix
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // AFFIX CLASS DEFINITION
  // ======================

  var Affix = function (element, options) {
    this.options = $.extend({}, Affix.DEFAULTS, options)

    this.$target = $(this.options.target)
      .on('scroll.bs.affix.data-api', $.proxy(this.checkPosition, this))
      .on('click.bs.affix.data-api',  $.proxy(this.checkPositionWithEventLoop, this))

    this.$element     = $(element)
    this.affixed      = null
    this.unpin        = null
    this.pinnedOffset = null

    this.checkPosition()
  }

  Affix.VERSION  = '3.3.7'

  Affix.RESET    = 'affix affix-top affix-bottom'

  Affix.DEFAULTS = {
    offset: 0,
    target: window
  }

  Affix.prototype.getState = function (scrollHeight, height, offsetTop, offsetBottom) {
    var scrollTop    = this.$target.scrollTop()
    var position     = this.$element.offset()
    var targetHeight = this.$target.height()

    if (offsetTop != null && this.affixed == 'top') return scrollTop < offsetTop ? 'top' : false

    if (this.affixed == 'bottom') {
      if (offsetTop != null) return (scrollTop + this.unpin <= position.top) ? false : 'bottom'
      return (scrollTop + targetHeight <= scrollHeight - offsetBottom) ? false : 'bottom'
    }

    var initializing   = this.affixed == null
    var colliderTop    = initializing ? scrollTop : position.top
    var colliderHeight = initializing ? targetHeight : height

    if (offsetTop != null && scrollTop <= offsetTop) return 'top'
    if (offsetBottom != null && (colliderTop + colliderHeight >= scrollHeight - offsetBottom)) return 'bottom'

    return false
  }

  Affix.prototype.getPinnedOffset = function () {
    if (this.pinnedOffset) return this.pinnedOffset
    this.$element.removeClass(Affix.RESET).addClass('affix')
    var scrollTop = this.$target.scrollTop()
    var position  = this.$element.offset()
    return (this.pinnedOffset = position.top - scrollTop)
  }

  Affix.prototype.checkPositionWithEventLoop = function () {
    setTimeout($.proxy(this.checkPosition, this), 1)
  }

  Affix.prototype.checkPosition = function () {
    if (!this.$element.is(':visible')) return

    var height       = this.$element.height()
    var offset       = this.options.offset
    var offsetTop    = offset.top
    var offsetBottom = offset.bottom
    var scrollHeight = Math.max($(document).height(), $(document.body).height())

    if (typeof offset != 'object')         offsetBottom = offsetTop = offset
    if (typeof offsetTop == 'function')    offsetTop    = offset.top(this.$element)
    if (typeof offsetBottom == 'function') offsetBottom = offset.bottom(this.$element)

    var affix = this.getState(scrollHeight, height, offsetTop, offsetBottom)

    if (this.affixed != affix) {
      if (this.unpin != null) this.$element.css('top', '')

      var affixType = 'affix' + (affix ? '-' + affix : '')
      var e         = $.Event(affixType + '.bs.affix')

      this.$element.trigger(e)

      if (e.isDefaultPrevented()) return

      this.affixed = affix
      this.unpin = affix == 'bottom' ? this.getPinnedOffset() : null

      this.$element
        .removeClass(Affix.RESET)
        .addClass(affixType)
        .trigger(affixType.replace('affix', 'affixed') + '.bs.affix')
    }

    if (affix == 'bottom') {
      this.$element.offset({
        top: scrollHeight - height - offsetBottom
      })
    }
  }


  // AFFIX PLUGIN DEFINITION
  // =======================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.affix')
      var options = typeof option == 'object' && option

      if (!data) $this.data('bs.affix', (data = new Affix(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  var old = $.fn.affix

  $.fn.affix             = Plugin
  $.fn.affix.Constructor = Affix


  // AFFIX NO CONFLICT
  // =================

  $.fn.affix.noConflict = function () {
    $.fn.affix = old
    return this
  }


  // AFFIX DATA-API
  // ==============

  $(window).on('load', function () {
    $('[data-spy="affix"]').each(function () {
      var $spy = $(this)
      var data = $spy.data()

      data.offset = data.offset || {}

      if (data.offsetBottom != null) data.offset.bottom = data.offsetBottom
      if (data.offsetTop    != null) data.offset.top    = data.offsetTop

      Plugin.call($spy, data)
    })
  })

}(jQuery);

jQuery.extend(jQuery.jtsage.datebox.prototype.options,
    {
      'useLang': 'en'
    });