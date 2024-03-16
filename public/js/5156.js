/*! For license information please see 5156.js.LICENSE.txt */
"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[5156],{24831:(e,t,r)=>{r.d(t,{U:()=>s});r(70821);var n=r(90771),o=r(58358),a=r(40191),l=r(9680);function s(e){var t=(0,n.useUserStore)(),r=(0,o.useAppSettingStore)(),s=(0,a.useVideoPlayerStore)();r.currentPage=e,r.showFlashMessage=!0,r.pageIsHidden=!1,t.isMobile&&(r.ott=0),s.makeVideoTopRight(),r.pageReload&&(r.pageReload=!1,window.location.reload(!0)),l.Inertia.on("navigate",(function(e){""!==window.location.search||requestAnimationFrame((function(){var e=document.getElementById("topDiv");e?e.scrollIntoView({behavior:"auto"}):window.scrollTo(0,0)}))})),r.setPrevUrl(),r.noLayout=!1,r.showOttButtons=!0}},59369:(e,t,r)=>{r.d(t,{Z:()=>y});var n=r(70821),o=r(9680),a=r(58358);function l(e){return l="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},l(e)}function s(){s=function(){return t};var e,t={},r=Object.prototype,n=r.hasOwnProperty,o=Object.defineProperty||function(e,t,r){e[t]=r.value},a="function"==typeof Symbol?Symbol:{},c=a.iterator||"@@iterator",i=a.asyncIterator||"@@asyncIterator",u=a.toStringTag||"@@toStringTag";function d(e,t,r){return Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{d({},"")}catch(e){d=function(e,t,r){return e[t]=r}}function f(e,t,r,n){var a=t&&t.prototype instanceof w?t:w,l=Object.create(a.prototype),s=new _(n||[]);return o(l,"_invoke",{value:S(e,r,s)}),l}function m(e,t,r){try{return{type:"normal",arg:e.call(t,r)}}catch(e){return{type:"throw",arg:e}}}t.wrap=f;var p="suspendedStart",h="suspendedYield",g="executing",v="completed",y={};function w(){}function b(){}function x(){}var k={};d(k,c,(function(){return this}));var E=Object.getPrototypeOf,N=E&&E(E(F([])));N&&N!==r&&n.call(N,c)&&(k=N);var V=x.prototype=w.prototype=Object.create(k);function C(e){["next","throw","return"].forEach((function(t){d(e,t,(function(e){return this._invoke(t,e)}))}))}function B(e,t){function r(o,a,s,c){var i=m(e[o],e,a);if("throw"!==i.type){var u=i.arg,d=u.value;return d&&"object"==l(d)&&n.call(d,"__await")?t.resolve(d.__await).then((function(e){r("next",e,s,c)}),(function(e){r("throw",e,s,c)})):t.resolve(d).then((function(e){u.value=e,s(u)}),(function(e){return r("throw",e,s,c)}))}c(i.arg)}var a;o(this,"_invoke",{value:function(e,n){function o(){return new t((function(t,o){r(e,n,t,o)}))}return a=a?a.then(o,o):o()}})}function S(t,r,n){var o=p;return function(a,l){if(o===g)throw new Error("Generator is already running");if(o===v){if("throw"===a)throw l;return{value:e,done:!0}}for(n.method=a,n.arg=l;;){var s=n.delegate;if(s){var c=L(s,n);if(c){if(c===y)continue;return c}}if("next"===n.method)n.sent=n._sent=n.arg;else if("throw"===n.method){if(o===p)throw o=v,n.arg;n.dispatchException(n.arg)}else"return"===n.method&&n.abrupt("return",n.arg);o=g;var i=m(t,r,n);if("normal"===i.type){if(o=n.done?v:h,i.arg===y)continue;return{value:i.arg,done:n.done}}"throw"===i.type&&(o=v,n.method="throw",n.arg=i.arg)}}}function L(t,r){var n=r.method,o=t.iterator[n];if(o===e)return r.delegate=null,"throw"===n&&t.iterator.return&&(r.method="return",r.arg=e,L(t,r),"throw"===r.method)||"return"!==n&&(r.method="throw",r.arg=new TypeError("The iterator does not provide a '"+n+"' method")),y;var a=m(o,t.iterator,r.arg);if("throw"===a.type)return r.method="throw",r.arg=a.arg,r.delegate=null,y;var l=a.arg;return l?l.done?(r[t.resultName]=l.value,r.next=t.nextLoc,"return"!==r.method&&(r.method="next",r.arg=e),r.delegate=null,y):l:(r.method="throw",r.arg=new TypeError("iterator result is not an object"),r.delegate=null,y)}function j(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function R(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function _(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(j,this),this.reset(!0)}function F(t){if(t||""===t){var r=t[c];if(r)return r.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var o=-1,a=function r(){for(;++o<t.length;)if(n.call(t,o))return r.value=t[o],r.done=!1,r;return r.value=e,r.done=!0,r};return a.next=a}}throw new TypeError(l(t)+" is not iterable")}return b.prototype=x,o(V,"constructor",{value:x,configurable:!0}),o(x,"constructor",{value:b,configurable:!0}),b.displayName=d(x,u,"GeneratorFunction"),t.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===b||"GeneratorFunction"===(t.displayName||t.name))},t.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,x):(e.__proto__=x,d(e,u,"GeneratorFunction")),e.prototype=Object.create(V),e},t.awrap=function(e){return{__await:e}},C(B.prototype),d(B.prototype,i,(function(){return this})),t.AsyncIterator=B,t.async=function(e,r,n,o,a){void 0===a&&(a=Promise);var l=new B(f(e,r,n,o),a);return t.isGeneratorFunction(r)?l:l.next().then((function(e){return e.done?e.value:l.next()}))},C(V),d(V,u,"Generator"),d(V,c,(function(){return this})),d(V,"toString",(function(){return"[object Generator]"})),t.keys=function(e){var t=Object(e),r=[];for(var n in t)r.push(n);return r.reverse(),function e(){for(;r.length;){var n=r.pop();if(n in t)return e.value=n,e.done=!1,e}return e.done=!0,e}},t.values=F,_.prototype={constructor:_,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=e,this.done=!1,this.delegate=null,this.method="next",this.arg=e,this.tryEntries.forEach(R),!t)for(var r in this)"t"===r.charAt(0)&&n.call(this,r)&&!isNaN(+r.slice(1))&&(this[r]=e)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var r=this;function o(n,o){return s.type="throw",s.arg=t,r.next=n,o&&(r.method="next",r.arg=e),!!o}for(var a=this.tryEntries.length-1;a>=0;--a){var l=this.tryEntries[a],s=l.completion;if("root"===l.tryLoc)return o("end");if(l.tryLoc<=this.prev){var c=n.call(l,"catchLoc"),i=n.call(l,"finallyLoc");if(c&&i){if(this.prev<l.catchLoc)return o(l.catchLoc,!0);if(this.prev<l.finallyLoc)return o(l.finallyLoc)}else if(c){if(this.prev<l.catchLoc)return o(l.catchLoc,!0)}else{if(!i)throw new Error("try statement without catch or finally");if(this.prev<l.finallyLoc)return o(l.finallyLoc)}}}},abrupt:function(e,t){for(var r=this.tryEntries.length-1;r>=0;--r){var o=this.tryEntries[r];if(o.tryLoc<=this.prev&&n.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var a=o;break}}a&&("break"===e||"continue"===e)&&a.tryLoc<=t&&t<=a.finallyLoc&&(a=null);var l=a?a.completion:{};return l.type=e,l.arg=t,a?(this.method="next",this.next=a.finallyLoc,y):this.complete(l)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),y},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.finallyLoc===e)return this.complete(r.completion,r.afterLoc),R(r),y}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.tryLoc===e){var n=r.completion;if("throw"===n.type){var o=n.arg;R(r)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(t,r,n){return this.delegate={iterator:F(t),resultName:r,nextLoc:n},"next"===this.method&&(this.arg=e),y}},t}function c(e,t,r,n,o,a,l){try{var s=e[a](l),c=s.value}catch(e){return void r(e)}s.done?t(c):Promise.resolve(c).then(n,o)}var i={class:"mx-4 my-4"},u={key:0,class:"alert alert-info mt-4"},d=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24",class:"stroke-current shrink-0 w-6 h-6"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"})],-1),f={key:1,class:"alert alert-success mt-4 mx-3"},m=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"})],-1),p={key:2,class:"alert alert-warning mt-4 mx-3"},h=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"})],-1),g={key:3,class:"alert alert-error mt-4 mx-3"},v=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"})],-1);const y={__name:"Messages",props:{flash:Object},setup:function(e){(0,a.useAppSettingStore)().showFlashMessage=!0;var t=e,r=((0,n.computed)((function(){return{"text-green-700 bg-green-100 dark:bg-green-200 dark:text-green-800":t.flash.success,"text-blue-700 bg-blue-100 dark:bg-blue-200 dark:text-blue-800":t.flash.message,"text-orange-700 bg-orange-100 dark:bg-orange-200 dark:text-orange-800":t.flash.warning,"text-red-700 bg-red-100 dark:bg-red-200 dark:text-red-800":t.flash.error}})),function(){var e,t=(e=s().mark((function e(){return s().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,o.Inertia.post(route("flash.clear"));case 2:o.Inertia.reload();case 3:case"end":return e.stop()}}),e)})),function(){var t=this,r=arguments;return new Promise((function(n,o){var a=e.apply(t,r);function l(e){c(a,n,o,l,s,"next",e)}function s(e){c(a,n,o,l,s,"throw",e)}l(void 0)}))});return function(){return t.apply(this,arguments)}}());return function(e,o){return(0,n.openBlock)(),(0,n.createElementBlock)("div",i,[(0,n.unref)(t).flash.message?((0,n.openBlock)(),(0,n.createElementBlock)("div",u,[d,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.message),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0),(0,n.unref)(t).flash.success?((0,n.openBlock)(),(0,n.createElementBlock)("div",f,[m,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.success),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0),(0,n.unref)(t).flash.warning?((0,n.openBlock)(),(0,n.createElementBlock)("div",p,[h,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.warning),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0),(0,n.unref)(t).flash.error?((0,n.openBlock)(),(0,n.createElementBlock)("div",g,[v,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.error),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0)])}}}},32379:(e,t,r)=>{r.d(t,{Z:()=>l});var n=r(70821),o={key:0,class:"flex flex-wrap justify-center my-4 space-x-4 space-y-2"},a=(0,n.createElementVNode)("div",null,null,-1);const l={__name:"Pagination",props:{data:Object},setup:function(e){var t=0,r=function(){t=window.scrollY};return(0,n.onMounted)((function(){window.scrollTo(0,t)})),function(t,l){return e.data.last_page>1?((0,n.openBlock)(),(0,n.createElementBlock)("div",o,[a,((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)(e.data.links,(function(e,t){return(0,n.openBlock)(),(0,n.createBlock)((0,n.resolveDynamicComponent)(e.url?"Link":"span"),{key:t,href:e.url,onClick:(0,n.withModifiers)(r,["prevent"]),innerHTML:e.label,class:(0,n.normalizeClass)(["px-4 py-3 text-sm leading-4 h-fit",{"text-white bg-orange-400 hover:bg-orange-400 dark:bg-orange-400 dark:hover:bg-orange-400":e.active,"rounded mt-2 mr-0.5 text-gray-300 dark:text-gray-700":!e.url,"rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-800 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-50 focus:text-indigo-500 hover:shadow":e.url}])},null,8,["href","innerHTML","class"])})),128))])):(0,n.createCommentVNode)("",!0)}}}},79725:(e,t,r)=>{r.d(t,{Z:()=>d});var n=r(70821),o=r(58358),a=r(90016),l={class:"w-full mx-auto mb-3 pb-3 border-b border-gray-500 pt-6"},s={class:"w-full mx-auto flex flex-wrap justify-between mb-3 pb-3 gap-2"},c={class:"text-4xl font-semibold text-center lg:text-left"},i={class:"flex justify-between align-items-end mt-4"},u={class:"flex flex-wrap ml-0 lg:ml-16 mt-6 mr-6 lg:mt-0 space-x-8"};const d={__name:"NewsHeader",props:{search:String,can:Object},setup:function(e){var t=(0,o.useAppSettingStore)(),r=function(e){var r="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition";return t.currentPage===e?"".concat(r," ").concat("border-indigo-400 text-gray-800 focus:outline-none focus:border-indigo-700"):"".concat(r," ").concat("border-transparent text-black hover:text-blue-500 hover:border-indigo-400 focus:outline-none focus:text-gray-700 focus:border-gray-300 disabled:text-gray-300 disabled:cursor-not-allowed disabled:border-none")};return function(o,d){return(0,n.openBlock)(),(0,n.createElementBlock)("div",l,[(0,n.createElementVNode)("div",s,[(0,n.createElementVNode)("div",c,[(0,n.renderSlot)(o.$slots,"default")]),(0,n.createElementVNode)("div",null,[(0,n.createElementVNode)("button",{onClick:d[0]||(d[0]=function(e){return(0,n.unref)(t).btnRedirect("/dashboard")}),class:"bg-black hover:bg-gray-800 text-white font-semibold ml-2 mt-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"},"Dashboard ")])]),(0,n.createElementVNode)("div",i,[(0,n.createElementVNode)("div",null,[(0,n.createElementVNode)("ul",u,[(0,n.createElementVNode)("li",null,[(0,n.createElementVNode)("button",{onClick:d[1]||(d[1]=function(e){return(0,n.unref)(t).btnRedirect("/newsroom")}),class:(0,n.normalizeClass)(["",r("newsroom")])}," Stories ",2)]),(0,n.createElementVNode)("li",null,[(0,n.createElementVNode)("button",{disabled:"",class:(0,n.normalizeClass)(["",r("categories")])}," Categories ",2)]),(0,n.createElementVNode)("li",null,[(0,n.createElementVNode)("button",{disabled:"",class:(0,n.normalizeClass)(["",r("cities")])}," Cities ",2)]),(0,n.createElementVNode)("li",null,[(0,n.createElementVNode)("button",{disabled:"",class:(0,n.normalizeClass)(["",r("districts")])}," Districts ",2)]),(0,n.createElementVNode)("li",null,[(0,n.createElementVNode)("button",{disabled:"",class:(0,n.normalizeClass)(["",r("pressReleases")])}," Press Releases ",2)]),(0,n.createElementVNode)("li",null,[(0,n.createElementVNode)("button",{disabled:"",class:(0,n.normalizeClass)(["",r("calendar")])}," Calendar ",2)]),(0,n.createElementVNode)("li",null,[(0,n.createElementVNode)("button",{onClick:d[2]||(d[2]=function(e){return(0,n.unref)(t).btnRedirect("/newsRssFeeds")}),class:(0,n.normalizeClass)(["",r("newsRssFeeds.index")])}," Feeds ",2)]),(0,n.createElementVNode)("li",null,[(0,n.createElementVNode)("button",{onClick:d[3]||(d[3]=function(e){return(0,n.unref)(t).btnRedirect("/newsRssArchive")}),class:(0,n.normalizeClass)(["",r("newsRssArchive.index")])}," Archive ",2)])])]),(0,n.createElementVNode)("div",null,[(0,n.createVNode)((0,n.unref)(a.Z),{can:e.can},null,8,["can"])])])])}}}},90016:(e,t,r)=>{r.d(t,{Z:()=>c});var n=r(70821),o=r(58358),a={class:"w-full flex flex-end flex-wrap-reverse justify-end gap-2 mr-4 mb-4"},l=(0,n.createElementVNode)("br",null,null,-1),s=(0,n.createElementVNode)("span",{class:"text-xs"},"Move this to the Press Releases page",-1);const c={__name:"NewsHeaderButtons",props:{can:Object},setup:function(e){var t=(0,o.useAppSettingStore)();return function(e,r){return(0,n.openBlock)(),(0,n.createElementBlock)("div",a,[(0,n.createElementVNode)("button",{onClick:r[0]||(r[0]=function(e){return(0,n.unref)(t).btnRedirect("")}),class:"bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400",disabled:""},[(0,n.createTextVNode)("Upload Press Release"),l,s])])}}}},95156:(e,t,r)=>{r.r(t),r.d(t,{default:()=>D});var n=r(70821),o=r(9680),a=r(39038),l=r(57810),s=r(23493),c=r.n(s),i=r(24831),u=r(58358),d=r(90771),f=(r(90016),r(79725)),m=r(32379),p=r(59369),h={class:"place-self-center flex flex-col gap-y-3"},g={id:"topDiv",class:"bg-white dark:bg-gray-800 text-black dark:text-gray-50 p-5 mb-10"},v={class:"w-full overflow-hidden bg-white shadow-sm sm:rounded-lg"},y={class:"w-full p-6 bg-white dark:bg-gray-900 border-b border-gray-200"},w={class:"w-full relative overflow-x-auto shadow-md sm:rounded-lg"},b={class:"table-auto w-full text-sm text-left text-gray-500 dark:text-gray-400"},x={class:"flex flex-justify text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"},k={class:"w-full"},E={scope:"col",class:"w-full flex flex-row justify-between px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"},N=(0,n.createElementVNode)("span",{class:"text-lg"},"News RSS Feed",-1),V={class:"flex items-center mt-6 lg:mt-0"},C={class:"relative"},B=(0,n.createElementVNode)("div",{class:"absolute top-0 flex items-center h-full ml-2"},[(0,n.createElementVNode)("svg",{class:"fill-current text-gray-400 w-4",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 512 512"},[(0,n.createElementVNode)("path",{d:"M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"})])],-1),S=(0,n.createElementVNode)("th",{scope:"col",class:""},null,-1),L={scope:"row",class:"px-6 py-4 text-gray-900 dark:text-white whitespace-nowrap"},j={class:"flex flex-row justify-between space-x-2"},R={class:"flex flex-row justify-between w-full"},_=["onClick"],F={class:"mr-2"},O={key:0},M={key:1},P={class:"space-x-1"},A=["onClick"],T=["onClick"];const D={__name:"Index",props:{filters:Object,can:Object,feeds:Object},setup:function(e){(0,i.U)("newsRssFeeds.index");var t=(0,u.useAppSettingStore)(),r=(0,d.useUserStore)(),s=e,D=(0,a.cI)({}),z=(0,n.ref)(s.filters.search);return(0,n.watch)(z,c()((function(e){o.Inertia.get("/newsRssFeeds",{search:e},{preserveState:!0,replace:!0})}),300)),function(o,a){var s=(0,n.resolveComponent)("Head");return(0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,null,[(0,n.createVNode)(s,{title:"News RSS Feeds"}),(0,n.createElementVNode)("div",h,[(0,n.createElementVNode)("div",g,[(0,n.unref)(t).showFlashMessage?((0,n.openBlock)(),(0,n.createBlock)((0,n.unref)(p.Z),{key:0,flash:o.$page.props.flash},null,8,["flash"])):(0,n.createCommentVNode)("",!0),(0,n.createVNode)((0,n.unref)(f.Z),{can:e.can},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)("News RSS Feeds")]})),_:1},8,["can"]),(0,n.createElementVNode)("div",v,[(0,n.createElementVNode)("div",y,[(0,n.createElementVNode)("div",w,[(0,n.createElementVNode)("table",b,[(0,n.createElementVNode)("thead",x,[(0,n.createElementVNode)("tr",k,[(0,n.createElementVNode)("th",E,[(0,n.createElementVNode)("div",null,[N,e.can.viewNewsroom?((0,n.openBlock)(),(0,n.createElementBlock)("button",{key:0,onClick:a[0]||(a[0]=function(e){return(0,n.unref)(t).btnRedirect("newsRssFeeds/create")}),class:"bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"},"Add Feed ")):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",V,[(0,n.createElementVNode)("div",C,[(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":a[1]||(a[1]=function(e){return(0,n.isRef)(z)?z.value=e:z=e}),type:"search",class:"bg-gray-50 text-black text-sm rounded-full focus:outline-none focus:shadow w-64 pl-8 px-3 py-1",placeholder:"Search..."},null,512),[[n.vModelText,(0,n.unref)(z)]]),B])])]),S])]),(0,n.createElementVNode)("tbody",null,[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)(e.feeds.data,(function(e){return(0,n.openBlock)(),(0,n.createElementBlock)("tr",{key:e.id,class:"bg-white border-b dark:bg-gray-300 dark:border-gray-700"},[(0,n.createElementVNode)("td",L,[(0,n.createElementVNode)("div",j,[(0,n.createElementVNode)("div",R,[(0,n.createElementVNode)("div",null,[(0,n.createElementVNode)("button",{onClick:function(r){return(0,n.unref)(t).btnRedirect("/newsRssFeeds/".concat(e.slug))},class:"text-blue-800 uppercase font-semibold text-md hover:text-blue-600 hover:opacity-75 transition ease-in-out duration-150"},(0,n.toDisplayString)(e.name),9,_)]),(0,n.createElementVNode)("div",F,[e.lastSuccessfulUpdate?((0,n.openBlock)(),(0,n.createElementBlock)("span",O,"Last update on "+(0,n.toDisplayString)(o.formatDateTime(e.lastSuccessfulUpdate)),1)):((0,n.openBlock)(),(0,n.createElementBlock)("span",M,"Never updated"))])]),(0,n.createElementVNode)("div",P,[(0,n.unref)(r).isNewsPerson?((0,n.openBlock)(),(0,n.createElementBlock)("button",{key:0,onClick:function(r){return(0,n.unref)(t).btnRedirect("/newsRssFeeds/".concat(e.slug,"/edit"))},class:"px-2 py-1 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"},"Edit ",8,A)):(0,n.createCommentVNode)("",!0),(0,n.unref)(r).isAdmin?((0,n.openBlock)(),(0,n.createElementBlock)("button",{key:1,onClick:function(t){return r=e.slug,void(confirm("Are you sure you want to Delete")&&D.delete(route("newsRssFeeds.destroy",r)));var r},class:"px-2 py-1 text-white bg-red-600 hover:bg-red-500 rounded-lg"},[(0,n.createVNode)((0,n.unref)(l.GN),{icon:"fa-trash-can"})],8,T)):(0,n.createCommentVNode)("",!0)])])])])})),128))])]),(0,n.createVNode)((0,n.unref)(m.Z),{data:e.feeds,class:"mt-6"},null,8,["data"])])])])])])],64)}}}}}]);
//# sourceMappingURL=5156.js.map