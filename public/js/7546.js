/*! For license information please see 7546.js.LICENSE.txt */
"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[7546],{24831:(e,t,r)=>{r.d(t,{U:()=>i});r(70821);var n=r(90771),o=r(58358),a=r(40191),l=r(9680);function i(e){var t=(0,n.useUserStore)(),r=(0,o.useAppSettingStore)(),i=(0,a.useVideoPlayerStore)();r.currentPage=e,r.showFlashMessage=!0,r.pageIsHidden=!1,t.isMobile&&(r.ott=0),i.makeVideoTopRight(),r.pageReload&&(r.pageReload=!1,window.location.reload(!0)),l.Inertia.on("navigate",(function(e){""!==window.location.search||requestAnimationFrame((function(){var e=document.getElementById("topDiv");e?e.scrollIntoView({behavior:"auto"}):window.scrollTo(0,0)}))})),r.setPrevUrl(),r.noLayout=!1,r.showOttButtons=!0}},59369:(e,t,r)=>{r.d(t,{Z:()=>y});var n=r(70821),o=r(9680),a=r(58358);function l(e){return l="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},l(e)}function i(){i=function(){return t};var e,t={},r=Object.prototype,n=r.hasOwnProperty,o=Object.defineProperty||function(e,t,r){e[t]=r.value},a="function"==typeof Symbol?Symbol:{},s=a.iterator||"@@iterator",c=a.asyncIterator||"@@asyncIterator",u=a.toStringTag||"@@toStringTag";function d(e,t,r){return Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{d({},"")}catch(e){d=function(e,t,r){return e[t]=r}}function f(e,t,r,n){var a=t&&t.prototype instanceof w?t:w,l=Object.create(a.prototype),i=new R(n||[]);return o(l,"_invoke",{value:L(e,r,i)}),l}function m(e,t,r){try{return{type:"normal",arg:e.call(t,r)}}catch(e){return{type:"throw",arg:e}}}t.wrap=f;var h="suspendedStart",p="suspendedYield",g="executing",v="completed",y={};function w(){}function b(){}function x(){}var k={};d(k,s,(function(){return this}));var E=Object.getPrototypeOf,N=E&&E(E(O([])));N&&N!==r&&n.call(N,s)&&(k=N);var V=x.prototype=w.prototype=Object.create(k);function S(e){["next","throw","return"].forEach((function(t){d(e,t,(function(e){return this._invoke(t,e)}))}))}function C(e,t){function r(o,a,i,s){var c=m(e[o],e,a);if("throw"!==c.type){var u=c.arg,d=u.value;return d&&"object"==l(d)&&n.call(d,"__await")?t.resolve(d.__await).then((function(e){r("next",e,i,s)}),(function(e){r("throw",e,i,s)})):t.resolve(d).then((function(e){u.value=e,i(u)}),(function(e){return r("throw",e,i,s)}))}s(c.arg)}var a;o(this,"_invoke",{value:function(e,n){function o(){return new t((function(t,o){r(e,n,t,o)}))}return a=a?a.then(o,o):o()}})}function L(t,r,n){var o=h;return function(a,l){if(o===g)throw new Error("Generator is already running");if(o===v){if("throw"===a)throw l;return{value:e,done:!0}}for(n.method=a,n.arg=l;;){var i=n.delegate;if(i){var s=B(i,n);if(s){if(s===y)continue;return s}}if("next"===n.method)n.sent=n._sent=n.arg;else if("throw"===n.method){if(o===h)throw o=v,n.arg;n.dispatchException(n.arg)}else"return"===n.method&&n.abrupt("return",n.arg);o=g;var c=m(t,r,n);if("normal"===c.type){if(o=n.done?v:p,c.arg===y)continue;return{value:c.arg,done:n.done}}"throw"===c.type&&(o=v,n.method="throw",n.arg=c.arg)}}}function B(t,r){var n=r.method,o=t.iterator[n];if(o===e)return r.delegate=null,"throw"===n&&t.iterator.return&&(r.method="return",r.arg=e,B(t,r),"throw"===r.method)||"return"!==n&&(r.method="throw",r.arg=new TypeError("The iterator does not provide a '"+n+"' method")),y;var a=m(o,t.iterator,r.arg);if("throw"===a.type)return r.method="throw",r.arg=a.arg,r.delegate=null,y;var l=a.arg;return l?l.done?(r[t.resultName]=l.value,r.next=t.nextLoc,"return"!==r.method&&(r.method="next",r.arg=e),r.delegate=null,y):l:(r.method="throw",r.arg=new TypeError("iterator result is not an object"),r.delegate=null,y)}function _(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function j(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function R(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(_,this),this.reset(!0)}function O(t){if(t||""===t){var r=t[s];if(r)return r.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var o=-1,a=function r(){for(;++o<t.length;)if(n.call(t,o))return r.value=t[o],r.done=!1,r;return r.value=e,r.done=!0,r};return a.next=a}}throw new TypeError(l(t)+" is not iterable")}return b.prototype=x,o(V,"constructor",{value:x,configurable:!0}),o(x,"constructor",{value:b,configurable:!0}),b.displayName=d(x,u,"GeneratorFunction"),t.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===b||"GeneratorFunction"===(t.displayName||t.name))},t.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,x):(e.__proto__=x,d(e,u,"GeneratorFunction")),e.prototype=Object.create(V),e},t.awrap=function(e){return{__await:e}},S(C.prototype),d(C.prototype,c,(function(){return this})),t.AsyncIterator=C,t.async=function(e,r,n,o,a){void 0===a&&(a=Promise);var l=new C(f(e,r,n,o),a);return t.isGeneratorFunction(r)?l:l.next().then((function(e){return e.done?e.value:l.next()}))},S(V),d(V,u,"Generator"),d(V,s,(function(){return this})),d(V,"toString",(function(){return"[object Generator]"})),t.keys=function(e){var t=Object(e),r=[];for(var n in t)r.push(n);return r.reverse(),function e(){for(;r.length;){var n=r.pop();if(n in t)return e.value=n,e.done=!1,e}return e.done=!0,e}},t.values=O,R.prototype={constructor:R,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=e,this.done=!1,this.delegate=null,this.method="next",this.arg=e,this.tryEntries.forEach(j),!t)for(var r in this)"t"===r.charAt(0)&&n.call(this,r)&&!isNaN(+r.slice(1))&&(this[r]=e)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var r=this;function o(n,o){return i.type="throw",i.arg=t,r.next=n,o&&(r.method="next",r.arg=e),!!o}for(var a=this.tryEntries.length-1;a>=0;--a){var l=this.tryEntries[a],i=l.completion;if("root"===l.tryLoc)return o("end");if(l.tryLoc<=this.prev){var s=n.call(l,"catchLoc"),c=n.call(l,"finallyLoc");if(s&&c){if(this.prev<l.catchLoc)return o(l.catchLoc,!0);if(this.prev<l.finallyLoc)return o(l.finallyLoc)}else if(s){if(this.prev<l.catchLoc)return o(l.catchLoc,!0)}else{if(!c)throw new Error("try statement without catch or finally");if(this.prev<l.finallyLoc)return o(l.finallyLoc)}}}},abrupt:function(e,t){for(var r=this.tryEntries.length-1;r>=0;--r){var o=this.tryEntries[r];if(o.tryLoc<=this.prev&&n.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var a=o;break}}a&&("break"===e||"continue"===e)&&a.tryLoc<=t&&t<=a.finallyLoc&&(a=null);var l=a?a.completion:{};return l.type=e,l.arg=t,a?(this.method="next",this.next=a.finallyLoc,y):this.complete(l)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),y},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.finallyLoc===e)return this.complete(r.completion,r.afterLoc),j(r),y}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.tryLoc===e){var n=r.completion;if("throw"===n.type){var o=n.arg;j(r)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(t,r,n){return this.delegate={iterator:O(t),resultName:r,nextLoc:n},"next"===this.method&&(this.arg=e),y}},t}function s(e,t,r,n,o,a,l){try{var i=e[a](l),s=i.value}catch(e){return void r(e)}i.done?t(s):Promise.resolve(s).then(n,o)}var c={class:"mx-4 my-4"},u={key:0,class:"alert alert-info mt-4"},d=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24",class:"stroke-current shrink-0 w-6 h-6"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"})],-1),f={key:1,class:"alert alert-success mt-4 mx-3"},m=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"})],-1),h={key:2,class:"alert alert-warning mt-4 mx-3"},p=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"})],-1),g={key:3,class:"alert alert-error mt-4 mx-3"},v=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"})],-1);const y={__name:"Messages",props:{flash:Object},setup:function(e){(0,a.useAppSettingStore)().showFlashMessage=!0;var t=e,r=((0,n.computed)((function(){return{"text-green-700 bg-green-100 dark:bg-green-200 dark:text-green-800":t.flash.success,"text-blue-700 bg-blue-100 dark:bg-blue-200 dark:text-blue-800":t.flash.message,"text-orange-700 bg-orange-100 dark:bg-orange-200 dark:text-orange-800":t.flash.warning,"text-red-700 bg-red-100 dark:bg-red-200 dark:text-red-800":t.flash.error}})),function(){var e,t=(e=i().mark((function e(){return i().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,o.Inertia.post(route("flash.clear"));case 2:o.Inertia.reload();case 3:case"end":return e.stop()}}),e)})),function(){var t=this,r=arguments;return new Promise((function(n,o){var a=e.apply(t,r);function l(e){s(a,n,o,l,i,"next",e)}function i(e){s(a,n,o,l,i,"throw",e)}l(void 0)}))});return function(){return t.apply(this,arguments)}}());return function(e,o){return(0,n.openBlock)(),(0,n.createElementBlock)("div",c,[(0,n.unref)(t).flash.message?((0,n.openBlock)(),(0,n.createElementBlock)("div",u,[d,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.message),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0),(0,n.unref)(t).flash.success?((0,n.openBlock)(),(0,n.createElementBlock)("div",f,[m,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.success),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0),(0,n.unref)(t).flash.warning?((0,n.openBlock)(),(0,n.createElementBlock)("div",h,[p,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.warning),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0),(0,n.unref)(t).flash.error?((0,n.openBlock)(),(0,n.createElementBlock)("div",g,[v,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.error),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0)])}}}},32379:(e,t,r)=>{r.d(t,{Z:()=>l});var n=r(70821),o={key:0,class:"flex flex-wrap justify-center my-4 space-x-4 space-y-2"},a=(0,n.createElementVNode)("div",null,null,-1);const l={__name:"Pagination",props:{data:Object},setup:function(e){var t=0,r=function(){t=window.scrollY};return(0,n.onMounted)((function(){window.scrollTo(0,t)})),function(t,l){return e.data.last_page>1?((0,n.openBlock)(),(0,n.createElementBlock)("div",o,[a,((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)(e.data.links,(function(e,t){return(0,n.openBlock)(),(0,n.createBlock)((0,n.resolveDynamicComponent)(e.url?"Link":"span"),{key:t,href:e.url,onClick:(0,n.withModifiers)(r,["prevent"]),innerHTML:e.label,class:(0,n.normalizeClass)(["px-4 py-3 text-sm leading-4 h-fit",{"text-white bg-orange-400 hover:bg-orange-400 dark:bg-orange-400 dark:hover:bg-orange-400":e.active,"rounded mt-2 mr-0.5 text-gray-300 dark:text-gray-700":!e.url,"rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-800 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-50 focus:text-indigo-500 hover:shadow":e.url}])},null,8,["href","innerHTML","class"])})),128))])):(0,n.createCommentVNode)("",!0)}}}},79725:(e,t,r)=>{r.d(t,{Z:()=>d});var n=r(70821),o=r(58358),a=r(90016),l={class:"w-full mx-auto mb-3 pb-3 border-b border-gray-500 pt-6"},i={class:"w-full mx-auto flex flex-wrap justify-between mb-3 pb-3 gap-2"},s={class:"text-4xl font-semibold text-center lg:text-left"},c={class:"flex justify-between align-items-end mt-4"},u={class:"flex flex-wrap ml-0 lg:ml-16 mt-6 mr-6 lg:mt-0 space-x-8"};const d={__name:"NewsHeader",props:{search:String,can:Object},setup:function(e){var t=(0,o.useAppSettingStore)(),r=function(e){var r="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition";return t.currentPage===e?"".concat(r," ").concat("border-indigo-400 text-gray-800 focus:outline-none focus:border-indigo-700"):"".concat(r," ").concat("border-transparent text-black hover:text-blue-500 hover:border-indigo-400 focus:outline-none focus:text-gray-700 focus:border-gray-300 disabled:text-gray-300 disabled:cursor-not-allowed disabled:border-none")};return function(o,d){return(0,n.openBlock)(),(0,n.createElementBlock)("div",l,[(0,n.createElementVNode)("div",i,[(0,n.createElementVNode)("div",s,[(0,n.renderSlot)(o.$slots,"default")]),(0,n.createElementVNode)("div",null,[(0,n.createElementVNode)("button",{onClick:d[0]||(d[0]=function(e){return(0,n.unref)(t).btnRedirect("/dashboard")}),class:"bg-black hover:bg-gray-800 text-white font-semibold ml-2 mt-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"},"Dashboard ")])]),(0,n.createElementVNode)("div",c,[(0,n.createElementVNode)("div",null,[(0,n.createElementVNode)("ul",u,[(0,n.createElementVNode)("li",null,[(0,n.createElementVNode)("button",{onClick:d[1]||(d[1]=function(e){return(0,n.unref)(t).btnRedirect("/newsroom")}),class:(0,n.normalizeClass)(["",r("newsroom")])}," Stories ",2)]),(0,n.createElementVNode)("li",null,[(0,n.createElementVNode)("button",{disabled:"",class:(0,n.normalizeClass)(["",r("categories")])}," Categories ",2)]),(0,n.createElementVNode)("li",null,[(0,n.createElementVNode)("button",{disabled:"",class:(0,n.normalizeClass)(["",r("cities")])}," Cities ",2)]),(0,n.createElementVNode)("li",null,[(0,n.createElementVNode)("button",{disabled:"",class:(0,n.normalizeClass)(["",r("districts")])}," Districts ",2)]),(0,n.createElementVNode)("li",null,[(0,n.createElementVNode)("button",{disabled:"",class:(0,n.normalizeClass)(["",r("pressReleases")])}," Press Releases ",2)]),(0,n.createElementVNode)("li",null,[(0,n.createElementVNode)("button",{disabled:"",class:(0,n.normalizeClass)(["",r("calendar")])}," Calendar ",2)]),(0,n.createElementVNode)("li",null,[(0,n.createElementVNode)("button",{onClick:d[2]||(d[2]=function(e){return(0,n.unref)(t).btnRedirect("/newsRssFeeds")}),class:(0,n.normalizeClass)(["",r("newsRssFeeds.index")])}," Feeds ",2)]),(0,n.createElementVNode)("li",null,[(0,n.createElementVNode)("button",{onClick:d[3]||(d[3]=function(e){return(0,n.unref)(t).btnRedirect("/newsRssArchive")}),class:(0,n.normalizeClass)(["",r("newsRssArchive.index")])}," Archive ",2)])])]),(0,n.createElementVNode)("div",null,[(0,n.createVNode)((0,n.unref)(a.Z),{can:e.can},null,8,["can"])])])])}}}},90016:(e,t,r)=>{r.d(t,{Z:()=>s});var n=r(70821),o=r(58358),a={class:"w-full flex flex-end flex-wrap-reverse justify-end gap-2 mr-4 mb-4"},l=(0,n.createElementVNode)("br",null,null,-1),i=(0,n.createElementVNode)("span",{class:"text-xs"},"Move this to the Press Releases page",-1);const s={__name:"NewsHeaderButtons",props:{can:Object},setup:function(e){var t=(0,o.useAppSettingStore)();return function(e,r){return(0,n.openBlock)(),(0,n.createElementBlock)("div",a,[(0,n.createElementVNode)("button",{onClick:r[0]||(r[0]=function(e){return(0,n.unref)(t).btnRedirect("")}),class:"bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400",disabled:""},[(0,n.createTextVNode)("Upload Press Release"),l,i])])}}}},7546:(e,t,r)=>{r.r(t),r.d(t,{default:()=>A});var n=r(70821),o=r(9680),a=r(39038),l=(r(57810),r(23493)),i=r.n(l),s=r(24831),c=r(58358),u=r(90771),d=(r(90016),r(79725)),f=r(32379),m=r(59369),h={class:"place-self-center flex flex-col gap-y-3"},p={id:"topDiv",class:"bg-white dark:bg-gray-800 text-black dark:text-gray-50 p-5 mb-10"},g={class:"overflow-hidden bg-white shadow-sm sm:rounded-lg"},v={class:"p-6 bg-white dark:bg-gray-900 border-b border-gray-200"},y={class:"flex justify-center"},w={class:"flex items-center"},b={class:"relative w-full"},x=(0,n.createElementVNode)("div",{class:"absolute top-0 left-0 flex items-center h-full ml-4"},[(0,n.createElementVNode)("svg",{class:"fill-current text-gray-400 w-4 h-4",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 512 512"},[(0,n.createElementVNode)("path",{d:"M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"})])],-1),k={class:"flex flex-col items-center w-full"},E={class:"py-4 px-3 space-y-3 w-full max-w-4xl"},N={class:"bg-gray-600 text-white p-5 rounded-xl"},V={class:"flex flex-row mb-2"},S={class:"w-full text-xl font-semibold"},C=["href"],L={class:"text-xs mb-2"},B=["innerHTML"],_={class:"flex justify-center"},j=["href"],R=["src"],O={key:0,class:"mt-2 text-xs tracking-wider hover:text-blue-300"},M={class:"font-semibold"},P={class:"py-8"};const A={__name:"Index",props:{filters:Object,archive:Object},setup:function(e){(0,s.U)("newsRssArchive.index");var t=(0,c.useAppSettingStore)(),r=((0,u.useUserStore)(),e),l=((0,a.cI)({}),(0,n.ref)(r.filters.search));return(0,n.watch)(l,i()((function(e){o.Inertia.get("/newsRssArchive",{search:e},{preserveState:!0,replace:!0})}),300)),function(r,o){var a=(0,n.resolveComponent)("Head"),i=(0,n.resolveComponent)("Link");return(0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,null,[(0,n.createVNode)(a,{title:"News RSS Archive"}),(0,n.createElementVNode)("div",h,[(0,n.createElementVNode)("div",p,[(0,n.unref)(t).showFlashMessage?((0,n.openBlock)(),(0,n.createBlock)((0,n.unref)(m.Z),{key:0,flash:r.$page.props.flash},null,8,["flash"])):(0,n.createCommentVNode)("",!0),(0,n.createVNode)((0,n.unref)(d.Z),null,{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)("News RSS Archive")]})),_:1}),(0,n.createElementVNode)("div",g,[(0,n.createElementVNode)("div",v,[(0,n.createElementVNode)("div",y,[(0,n.createElementVNode)("div",w,[(0,n.createElementVNode)("div",b,[(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":o[0]||(o[0]=function(e){return(0,n.isRef)(l)?l.value=e:l=e}),type:"search",class:"bg-gray-50 text-black text-sm rounded-full focus:outline-none focus:shadow w-full pl-10 py-2",placeholder:"Search..."},null,512),[[n.vModelText,(0,n.unref)(l)]]),x])])]),(0,n.createVNode)((0,n.unref)(f.Z),{data:e.archive},null,8,["data"]),(0,n.createElementVNode)("div",k,[(0,n.createElementVNode)("div",E,[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)(e.archive.data,(function(e){return(0,n.openBlock)(),(0,n.createElementBlock)("div",N,[(0,n.createElementVNode)("div",V,[(0,n.createElementVNode)("div",S,[(0,n.createElementVNode)("a",{href:e.url,target:"_blank"},(0,n.toDisplayString)(e.title),9,C)])]),(0,n.createElementVNode)("div",L,(0,n.toDisplayString)(r.formatDate(e.pubDate)),1),(0,n.createElementVNode)("div",{innerHTML:e.description,class:"mb-2"},null,8,B),(0,n.createElementVNode)("div",_,[(0,n.createElementVNode)("a",{href:e.url,target:"_blank"},[(0,n.createElementVNode)("img",{src:e.image_url,class:"max-w-full h-auto"},null,8,R)],8,j)]),e.feedName?((0,n.openBlock)(),(0,n.createElementBlock)("p",O,[(0,n.createVNode)(i,{href:"/newsRssFeeds/".concat(e.feedSlug)},{default:(0,n.withCtx)((function(){return[(0,n.createElementVNode)("span",M,(0,n.toDisplayString)(e.feedName),1)]})),_:2},1032,["href"])])):(0,n.createCommentVNode)("",!0)])})),256)),(0,n.createElementVNode)("div",P,[(0,n.createVNode)((0,n.unref)(f.Z),{data:e.archive},null,8,["data"])])])])])])])])],64)}}}}}]);
//# sourceMappingURL=7546.js.map