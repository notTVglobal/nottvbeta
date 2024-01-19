/*! For license information please see 8725.js.LICENSE.txt */
"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[8725],{24831:(e,t,r)=>{r.d(t,{U:()=>c});var n=r(70821),o=r(90771),a=r(58358),l=r(40191),i=r(9680);function c(e){var t=(0,o.useUserStore)(),r=(0,a.useAppSettingStore)(),c=(0,l.useVideoPlayerStore)();r.currentPage=e,r.showFlashMessage=!0,r.pageIsHidden=!1,t.isMobile&&(r.ott=0),c.makeVideoTopRight();(0,n.onBeforeMount)((function(){r.pageReload&&(r.pageReload=!1,window.location.reload(!0))})),(0,n.onMounted)((function(){if(!(""!==window.location.search)){var e=document.getElementById("topDiv");e&&e.scrollIntoView()}r.setPrevUrl(),r.noLayout=!1,i.Inertia.reload()}))}},59369:(e,t,r)=>{r.d(t,{Z:()=>y});var n=r(70821),o=r(9680),a=r(58358);function l(e){return l="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},l(e)}function i(){i=function(){return t};var e,t={},r=Object.prototype,n=r.hasOwnProperty,o=Object.defineProperty||function(e,t,r){e[t]=r.value},a="function"==typeof Symbol?Symbol:{},c=a.iterator||"@@iterator",s=a.asyncIterator||"@@asyncIterator",u=a.toStringTag||"@@toStringTag";function d(e,t,r){return Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{d({},"")}catch(e){d=function(e,t,r){return e[t]=r}}function m(e,t,r,n){var a=t&&t.prototype instanceof x?t:x,l=Object.create(a.prototype),i=new D(n||[]);return o(l,"_invoke",{value:C(e,r,i)}),l}function f(e,t,r){try{return{type:"normal",arg:e.call(t,r)}}catch(e){return{type:"throw",arg:e}}}t.wrap=m;var h="suspendedStart",p="suspendedYield",g="executing",v="completed",y={};function x(){}function w(){}function b(){}var E={};d(E,c,(function(){return this}));var N=Object.getPrototypeOf,V=N&&N(N(M([])));V&&V!==r&&n.call(V,c)&&(E=V);var k=b.prototype=x.prototype=Object.create(E);function S(e){["next","throw","return"].forEach((function(t){d(e,t,(function(e){return this._invoke(t,e)}))}))}function B(e,t){function r(o,a,i,c){var s=f(e[o],e,a);if("throw"!==s.type){var u=s.arg,d=u.value;return d&&"object"==l(d)&&n.call(d,"__await")?t.resolve(d.__await).then((function(e){r("next",e,i,c)}),(function(e){r("throw",e,i,c)})):t.resolve(d).then((function(e){u.value=e,i(u)}),(function(e){return r("throw",e,i,c)}))}c(s.arg)}var a;o(this,"_invoke",{value:function(e,n){function o(){return new t((function(t,o){r(e,n,t,o)}))}return a=a?a.then(o,o):o()}})}function C(t,r,n){var o=h;return function(a,l){if(o===g)throw new Error("Generator is already running");if(o===v){if("throw"===a)throw l;return{value:e,done:!0}}for(n.method=a,n.arg=l;;){var i=n.delegate;if(i){var c=L(i,n);if(c){if(c===y)continue;return c}}if("next"===n.method)n.sent=n._sent=n.arg;else if("throw"===n.method){if(o===h)throw o=v,n.arg;n.dispatchException(n.arg)}else"return"===n.method&&n.abrupt("return",n.arg);o=g;var s=f(t,r,n);if("normal"===s.type){if(o=n.done?v:p,s.arg===y)continue;return{value:s.arg,done:n.done}}"throw"===s.type&&(o=v,n.method="throw",n.arg=s.arg)}}}function L(t,r){var n=r.method,o=t.iterator[n];if(o===e)return r.delegate=null,"throw"===n&&t.iterator.return&&(r.method="return",r.arg=e,L(t,r),"throw"===r.method)||"return"!==n&&(r.method="throw",r.arg=new TypeError("The iterator does not provide a '"+n+"' method")),y;var a=f(o,t.iterator,r.arg);if("throw"===a.type)return r.method="throw",r.arg=a.arg,r.delegate=null,y;var l=a.arg;return l?l.done?(r[t.resultName]=l.value,r.next=t.nextLoc,"return"!==r.method&&(r.method="next",r.arg=e),r.delegate=null,y):l:(r.method="throw",r.arg=new TypeError("iterator result is not an object"),r.delegate=null,y)}function j(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function _(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function D(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(j,this),this.reset(!0)}function M(t){if(t||""===t){var r=t[c];if(r)return r.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var o=-1,a=function r(){for(;++o<t.length;)if(n.call(t,o))return r.value=t[o],r.done=!1,r;return r.value=e,r.done=!0,r};return a.next=a}}throw new TypeError(l(t)+" is not iterable")}return w.prototype=b,o(k,"constructor",{value:b,configurable:!0}),o(b,"constructor",{value:w,configurable:!0}),w.displayName=d(b,u,"GeneratorFunction"),t.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===w||"GeneratorFunction"===(t.displayName||t.name))},t.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,b):(e.__proto__=b,d(e,u,"GeneratorFunction")),e.prototype=Object.create(k),e},t.awrap=function(e){return{__await:e}},S(B.prototype),d(B.prototype,s,(function(){return this})),t.AsyncIterator=B,t.async=function(e,r,n,o,a){void 0===a&&(a=Promise);var l=new B(m(e,r,n,o),a);return t.isGeneratorFunction(r)?l:l.next().then((function(e){return e.done?e.value:l.next()}))},S(k),d(k,u,"Generator"),d(k,c,(function(){return this})),d(k,"toString",(function(){return"[object Generator]"})),t.keys=function(e){var t=Object(e),r=[];for(var n in t)r.push(n);return r.reverse(),function e(){for(;r.length;){var n=r.pop();if(n in t)return e.value=n,e.done=!1,e}return e.done=!0,e}},t.values=M,D.prototype={constructor:D,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=e,this.done=!1,this.delegate=null,this.method="next",this.arg=e,this.tryEntries.forEach(_),!t)for(var r in this)"t"===r.charAt(0)&&n.call(this,r)&&!isNaN(+r.slice(1))&&(this[r]=e)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var r=this;function o(n,o){return i.type="throw",i.arg=t,r.next=n,o&&(r.method="next",r.arg=e),!!o}for(var a=this.tryEntries.length-1;a>=0;--a){var l=this.tryEntries[a],i=l.completion;if("root"===l.tryLoc)return o("end");if(l.tryLoc<=this.prev){var c=n.call(l,"catchLoc"),s=n.call(l,"finallyLoc");if(c&&s){if(this.prev<l.catchLoc)return o(l.catchLoc,!0);if(this.prev<l.finallyLoc)return o(l.finallyLoc)}else if(c){if(this.prev<l.catchLoc)return o(l.catchLoc,!0)}else{if(!s)throw new Error("try statement without catch or finally");if(this.prev<l.finallyLoc)return o(l.finallyLoc)}}}},abrupt:function(e,t){for(var r=this.tryEntries.length-1;r>=0;--r){var o=this.tryEntries[r];if(o.tryLoc<=this.prev&&n.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var a=o;break}}a&&("break"===e||"continue"===e)&&a.tryLoc<=t&&t<=a.finallyLoc&&(a=null);var l=a?a.completion:{};return l.type=e,l.arg=t,a?(this.method="next",this.next=a.finallyLoc,y):this.complete(l)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),y},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.finallyLoc===e)return this.complete(r.completion,r.afterLoc),_(r),y}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.tryLoc===e){var n=r.completion;if("throw"===n.type){var o=n.arg;_(r)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(t,r,n){return this.delegate={iterator:M(t),resultName:r,nextLoc:n},"next"===this.method&&(this.arg=e),y}},t}function c(e,t,r,n,o,a,l){try{var i=e[a](l),c=i.value}catch(e){return void r(e)}i.done?t(c):Promise.resolve(c).then(n,o)}var s={class:"mx-4 my-4"},u={key:0,class:"alert alert-info mt-4"},d=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24",class:"stroke-current shrink-0 w-6 h-6"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"})],-1),m={key:1,class:"alert alert-success mt-4 mx-3"},f=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"})],-1),h={key:2,class:"alert alert-warning mt-4 mx-3"},p=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"})],-1),g={key:3,class:"alert alert-error mt-4 mx-3"},v=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"})],-1);const y={__name:"Messages",props:{flash:Object},setup:function(e){(0,a.useAppSettingStore)().showFlashMessage=!0;var t=e,r=((0,n.computed)((function(){return{"text-green-700 bg-green-100 dark:bg-green-200 dark:text-green-800":t.flash.success,"text-blue-700 bg-blue-100 dark:bg-blue-200 dark:text-blue-800":t.flash.message,"text-orange-700 bg-orange-100 dark:bg-orange-200 dark:text-orange-800":t.flash.warning,"text-red-700 bg-red-100 dark:bg-red-200 dark:text-red-800":t.flash.error}})),function(){var e,t=(e=i().mark((function e(){return i().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,o.Inertia.post(route("flash.clear"));case 2:o.Inertia.reload();case 3:case"end":return e.stop()}}),e)})),function(){var t=this,r=arguments;return new Promise((function(n,o){var a=e.apply(t,r);function l(e){c(a,n,o,l,i,"next",e)}function i(e){c(a,n,o,l,i,"throw",e)}l(void 0)}))});return function(){return t.apply(this,arguments)}}());return function(e,o){return(0,n.openBlock)(),(0,n.createElementBlock)("div",s,[(0,n.unref)(t).flash.message?((0,n.openBlock)(),(0,n.createElementBlock)("div",u,[d,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.message),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0),(0,n.unref)(t).flash.success?((0,n.openBlock)(),(0,n.createElementBlock)("div",m,[f,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.success),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0),(0,n.unref)(t).flash.warning?((0,n.openBlock)(),(0,n.createElementBlock)("div",h,[p,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.warning),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0),(0,n.unref)(t).flash.error?((0,n.openBlock)(),(0,n.createElementBlock)("div",g,[v,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.error),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0)])}}}},58725:(e,t,r)=>{r.r(t),r.d(t,{default:()=>te});var n=r(70821),o=r(9680),a=r(23493),l=r.n(a),i=r(24831),c=r(58358),s=r(93213),u=r(59369),d=r(25543),m={id:"topDiv",class:"place-self-center flex flex-col gap-y-3"},f={class:"bg-gray-900 text-white px-5"},h={class:"flex justify-between mb-3 border-b border-gray-800"},p={class:"container mx-auto flex flex-col lg:flex-row items-center justify-between px-4 py-6"},g={class:"flex flex-col lg:flex-row items-center"},v=(0,n.createElementVNode)("h1",{class:"text-3xl font-semibold"},"Movies",-1),y={class:"flex ml-0 lg:ml-16 mt-6 lg:mt-0 space-x-8"},x=(0,n.createElementVNode)("li",null,[(0,n.createElementVNode)("button",{href:"",class:"text-gray-700 cursor-not-allowed"},"Categories")],-1),w={class:"flex items-center mt-6 lg:mt-0"},b={class:"relative"},E=(0,n.createElementVNode)("div",{class:"absolute top-0 flex items-center h-full ml-2"},[(0,n.createElementVNode)("svg",{class:"fill-current text-gray-400 w-4",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 512 512"},[(0,n.createElementVNode)("path",{d:"M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"})])],-1),N={class:"py-8"},V={class:"container mx-auto px-4 border-b border-gray-800 pb-16"},k=(0,n.createElementVNode)("h2",{class:"text-yellow-500 uppercase tracking-wide font-semibold text-2xl"},"Popular Movies",-1),S={class:"popular-movies text-sm grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-12 pb-12 justify-items-center"},B={class:"relative inline-block"},C=(0,n.createElementVNode)("div",{class:"absolute bottom-0 right-0 w-12 h-12 bg-gray-800 rounded-full",style:{right:"-20px",bottom:"-20px"}},[(0,n.createElementVNode)("div",{class:"font-semi-bold text-xs flex justify-center items-center h-full"},"80%")],-1),L={class:"text-gray-400 mt-1"},j={key:0},_={class:"text-gray-400 mt-1 hidden"},D={class:"flex flex-col lg:flex-row my-10"},M={class:"recently-reviewed w-full lg:w-3/4 mr-0 md:mr-16 lg:mr-32"},O=(0,n.createElementVNode)("h2",{id:"review",class:"text-yellow-500 uppercase tracking-wide font-semibold text-2xl"},"Recently Reviewed",-1),T={class:"recently-reviewed-container space-y-12 mt-8"},I={class:"relative flex-none"},P=(0,n.createElementVNode)("div",{class:"absolute bottom-0 right-0 w-12 h-12 bg-gray-900 rounded-full",style:{right:"-20px",bottom:"-20px"}},[(0,n.createElementVNode)("div",{class:"font-semi-bold text-xs flex justify-center items-center h-full"},"80%")],-1),F={class:"ml-12"},A={class:"text-gray-400 mt-1"},R={class:"hidden"},Z={class:"mt-6 pr-4 text-gray-300 hidden lg:block"},G={class:"flex flex-col xl:flex-row my-10 pl-4"},z={class:"most-anticipated w-full xl:w-3/4 mr-0 md:mr-16 xl:mr-32"},U=(0,n.createElementVNode)("h2",{class:"text-yellow-500 uppercase tracking-wide font-semibold text-2xl"},"Most Anticipated",-1),H={class:"most-anticipated-container space-y-10 mt-8"},Y={class:"ml-4"},$={class:"text-gray-400 text-sm mt-1"},q={class:"hidden"},J=(0,n.createElementVNode)("h2",{id:"coming-soon",class:"text-yellow-500 uppercase tracking-wide font-semibold mt-16 text-2xl"},"Coming Soon",-1),K={class:"most-anticipated-container space-y-10 mt-8"},Q={class:"ml-4"},W={class:"text-gray-400 text-sm mt-1"},X={class:"hidden"},ee=(0,n.createElementVNode)("footer",{class:"border-t border-gray-800"},[(0,n.createElementVNode)("div",{class:"container text-right text-gray-800 mx-auto px-4 py-6"}," Powered by not.tv ")],-1);const te={__name:"Index",props:{movies:Object,recentlyReviewed:Object,mostAnticipated:Object,comingSoon:Object,filters:Object,can:Object},setup:function(e){(0,i.U)("movies");var t=(0,c.useAppSettingStore)(),r=e,a=(0,n.ref)(r.filters.search);function te(){document.getElementById("review").scrollIntoView({behavior:"smooth"})}function re(){document.getElementById("coming-soon").scrollIntoView({behavior:"smooth"})}return(0,n.watch)(a,l()((function(e){o.Inertia.get("/movies",{search:e},{preserveState:!0,replace:!0})}),300)),function(r,o){var l=(0,n.resolveComponent)("Head"),i=(0,n.resolveComponent)("Link");return(0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,null,[(0,n.createVNode)(l,{title:"Movies"}),(0,n.createElementVNode)("div",m,[(0,n.createElementVNode)("div",f,[(0,n.unref)(t).showFlashMessage?((0,n.openBlock)(),(0,n.createBlock)((0,n.unref)(u.Z),{key:0,flash:r.$page.props.flash},null,8,["flash"])):(0,n.createCommentVNode)("",!0),(0,n.createElementVNode)("header",h,[(0,n.createElementVNode)("div",p,[(0,n.createElementVNode)("div",g,[v,(0,n.createElementVNode)("ul",y,[x,(0,n.createElementVNode)("li",null,[(0,n.createElementVNode)("button",{href:"",onClick:(0,n.withModifiers)(te,["prevent"]),class:"hover:text-blue-800"},"Reviews")]),(0,n.createElementVNode)("li",null,[(0,n.createElementVNode)("button",{href:"",onClick:(0,n.withModifiers)(re,["prevent"]),class:"hover:text-blue-800"},"Coming Soon")])])]),(0,n.createElementVNode)("div",w,[(0,n.createElementVNode)("div",b,[(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":o[0]||(o[0]=function(e){return(0,n.isRef)(a)?a.value=e:a=e}),type:"search",class:"bg-gray-800 text-sm rounded-full focus:outline-none focus:shadow w-64 pl-8 px-3 py-1",placeholder:"Search..."},null,512),[[n.vModelText,(0,n.unref)(a)]]),E])])])]),(0,n.createElementVNode)("main",N,[(0,n.createElementVNode)("div",V,[k,(0,n.createElementVNode)("div",S,[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)(e.movies.data,(function(e){return(0,n.openBlock)(),(0,n.createElementBlock)("div",{key:e.id,class:"movie mt-8"},[(0,n.createElementVNode)("div",B,[(0,n.createVNode)(i,{href:"/movies/".concat(e.slug)},{default:(0,n.withCtx)((function(){return[(0,n.createVNode)((0,n.unref)(d.Z),{image:e.image,alt:"movie cover",class:(0,n.normalizeClass)("h-48 min-w-[8rem] w-28 object-cover hover:opacity-75 transition ease-in-out duration-150")},null,8,["image"])]})),_:2},1032,["href"]),C]),(0,n.createVNode)(i,{href:"/movies/".concat(e.slug),class:"block text-base font-semibold leading-tight max-w-[8rem] hover:text-gray-400 mt-4 mb-2"},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)((0,n.toDisplayString)(e.name),1)]})),_:2},1032,["href"]),(0,n.createElementVNode)("div",L,[(0,n.createTextVNode)((0,n.toDisplayString)(e.category)+" ",1),e.release_year?((0,n.openBlock)(),(0,n.createElementBlock)("span",j,"("+(0,n.toDisplayString)(e.release_year)+")",1)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",_,(0,n.toDisplayString)(e.subCategory),1)])})),128))]),(0,n.createVNode)((0,n.unref)(s.Z),{data:e.movies,class:""},null,8,["data"])]),(0,n.createElementVNode)("div",D,[(0,n.createElementVNode)("div",M,[O,(0,n.createElementVNode)("div",T,[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)(e.recentlyReviewed.data,(function(e){return(0,n.openBlock)(),(0,n.createElementBlock)("div",{key:e.id,class:"movie bg-gray-800 rounded-lg shadow-md flex px-6 py-6"},[(0,n.createElementVNode)("div",I,[(0,n.createVNode)(i,{href:"/movies/".concat(e.slug),class:"hover:text-blue-400 hover:opacity-75 transition ease-in-out duration-150"},{default:(0,n.withCtx)((function(){return[(0,n.createVNode)((0,n.unref)(d.Z),{image:e.image,alt:"movie cover",class:"h-32 md:h-64 md:min-w-[8rem] w-24 md:w-48 object-cover hover:opacity-75 transition ease-in-out duration-150"},null,8,["image"])]})),_:2},1032,["href"]),P]),(0,n.createElementVNode)("div",F,[(0,n.createVNode)(i,{href:"/movies/".concat(e.slug),class:"block text-lg font-semibold leading-tight max-w-[8rem] hover:text-gray-400 mt-4"},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)((0,n.toDisplayString)(e.name),1)]})),_:2},1032,["href"]),(0,n.createElementVNode)("div",A,[(0,n.createTextVNode)((0,n.toDisplayString)(e.category),1),(0,n.createElementVNode)("span",R,", "+(0,n.toDisplayString)(e.subCategory),1)]),(0,n.createElementVNode)("p",Z,(0,n.toDisplayString)(e.logline),1)])])})),128))])]),(0,n.createElementVNode)("div",G,[(0,n.createElementVNode)("div",z,[U,(0,n.createElementVNode)("div",H,[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)(e.mostAnticipated.data,(function(e){return(0,n.openBlock)(),(0,n.createElementBlock)("div",{key:e.id,class:"movie flex"},[(0,n.createVNode)(i,{href:"/movies/".concat(e.slug)},{default:(0,n.withCtx)((function(){return[(0,n.createVNode)((0,n.unref)(d.Z),{image:e.image,alt:"movie cover",class:"h-24 min-w-[4rem] w-16 object-cover hover:opacity-75 transition ease-in-out duration-150"},null,8,["image"])]})),_:2},1032,["href"]),(0,n.createElementVNode)("div",Y,[(0,n.createVNode)(i,{href:"/movies/".concat(e.slug),class:"hover:text-gray-300"},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)((0,n.toDisplayString)(e.name),1)]})),_:2},1032,["href"]),(0,n.createElementVNode)("div",$,[(0,n.createTextVNode)((0,n.toDisplayString)(e.category),1),(0,n.createElementVNode)("span",q,", "+(0,n.toDisplayString)(e.subCategory),1)])])])})),128))]),J,(0,n.createElementVNode)("div",K,[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)(e.comingSoon.data,(function(e){return(0,n.openBlock)(),(0,n.createElementBlock)("div",{key:e.id,class:"movie flex"},[(0,n.createVNode)(i,{href:"/movies/".concat(e.slug)},{default:(0,n.withCtx)((function(){return[(0,n.createVNode)((0,n.unref)(d.Z),{image:e.image,alt:"movie cover",class:"h-24 min-w-[4rem] w-16 object-cover hover:opacity-75 transition ease-in-out duration-150"},null,8,["image"])]})),_:2},1032,["href"]),(0,n.createElementVNode)("div",Q,[(0,n.createVNode)(i,{href:"/movies/".concat(e.slug),class:"hover:text-gray-300"},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)((0,n.toDisplayString)(e.name),1)]})),_:2},1032,["href"]),(0,n.createElementVNode)("div",W,[(0,n.createTextVNode)((0,n.toDisplayString)(e.category),1),(0,n.createElementVNode)("span",X,", "+(0,n.toDisplayString)(e.subCategory),1)])])])})),128))])])])])]),ee])])],64)}}}}}]);
//# sourceMappingURL=8725.js.map