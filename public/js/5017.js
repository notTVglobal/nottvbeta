/*! For license information please see 5017.js.LICENSE.txt */
"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[5017],{24831:(e,t,r)=>{r.d(t,{U:()=>l});var n=r(70821),o=r(90771),a=r(58358),i=r(40191),c=r(9680);function l(e){var t=(0,o.useUserStore)(),r=(0,a.useAppSettingStore)(),l=(0,i.useVideoPlayerStore)();r.currentPage=e,r.showFlashMessage=!0,r.pageIsHidden=!1,t.isMobile&&(r.ott=0),l.makeVideoTopRight();(0,n.onBeforeMount)((function(){r.pageReload&&(r.pageReload=!1,window.location.reload(!0))})),(0,n.onMounted)((function(){if(!(""!==window.location.search)){var e=document.getElementById("topDiv");e&&e.scrollIntoView()}r.setPrevUrl(),r.noLayout=!1,c.Inertia.reload()}))}},59369:(e,t,r)=>{r.d(t,{Z:()=>y});var n=r(70821),o=r(9680),a=r(58358);function i(e){return i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},i(e)}function c(){c=function(){return t};var e,t={},r=Object.prototype,n=r.hasOwnProperty,o=Object.defineProperty||function(e,t,r){e[t]=r.value},a="function"==typeof Symbol?Symbol:{},l=a.iterator||"@@iterator",s=a.asyncIterator||"@@asyncIterator",u=a.toStringTag||"@@toStringTag";function f(e,t,r){return Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{f({},"")}catch(e){f=function(e,t,r){return e[t]=r}}function h(e,t,r,n){var a=t&&t.prototype instanceof w?t:w,i=Object.create(a.prototype),c=new O(n||[]);return o(i,"_invoke",{value:B(e,r,c)}),i}function p(e,t,r){try{return{type:"normal",arg:e.call(t,r)}}catch(e){return{type:"throw",arg:e}}}t.wrap=h;var d="suspendedStart",m="suspendedYield",v="executing",g="completed",y={};function w(){}function k(){}function x(){}var b={};f(b,l,(function(){return this}));var E=Object.getPrototypeOf,L=E&&E(E(M([])));L&&L!==r&&n.call(L,l)&&(b=L);var N=x.prototype=w.prototype=Object.create(b);function V(e){["next","throw","return"].forEach((function(t){f(e,t,(function(e){return this._invoke(t,e)}))}))}function S(e,t){function r(o,a,c,l){var s=p(e[o],e,a);if("throw"!==s.type){var u=s.arg,f=u.value;return f&&"object"==i(f)&&n.call(f,"__await")?t.resolve(f.__await).then((function(e){r("next",e,c,l)}),(function(e){r("throw",e,c,l)})):t.resolve(f).then((function(e){u.value=e,c(u)}),(function(e){return r("throw",e,c,l)}))}l(s.arg)}var a;o(this,"_invoke",{value:function(e,n){function o(){return new t((function(t,o){r(e,n,t,o)}))}return a=a?a.then(o,o):o()}})}function B(t,r,n){var o=d;return function(a,i){if(o===v)throw new Error("Generator is already running");if(o===g){if("throw"===a)throw i;return{value:e,done:!0}}for(n.method=a,n.arg=i;;){var c=n.delegate;if(c){var l=_(c,n);if(l){if(l===y)continue;return l}}if("next"===n.method)n.sent=n._sent=n.arg;else if("throw"===n.method){if(o===d)throw o=g,n.arg;n.dispatchException(n.arg)}else"return"===n.method&&n.abrupt("return",n.arg);o=v;var s=p(t,r,n);if("normal"===s.type){if(o=n.done?g:m,s.arg===y)continue;return{value:s.arg,done:n.done}}"throw"===s.type&&(o=g,n.method="throw",n.arg=s.arg)}}}function _(t,r){var n=r.method,o=t.iterator[n];if(o===e)return r.delegate=null,"throw"===n&&t.iterator.return&&(r.method="return",r.arg=e,_(t,r),"throw"===r.method)||"return"!==n&&(r.method="throw",r.arg=new TypeError("The iterator does not provide a '"+n+"' method")),y;var a=p(o,t.iterator,r.arg);if("throw"===a.type)return r.method="throw",r.arg=a.arg,r.delegate=null,y;var i=a.arg;return i?i.done?(r[t.resultName]=i.value,r.next=t.nextLoc,"return"!==r.method&&(r.method="next",r.arg=e),r.delegate=null,y):i:(r.method="throw",r.arg=new TypeError("iterator result is not an object"),r.delegate=null,y)}function j(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function C(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function O(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(j,this),this.reset(!0)}function M(t){if(t||""===t){var r=t[l];if(r)return r.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var o=-1,a=function r(){for(;++o<t.length;)if(n.call(t,o))return r.value=t[o],r.done=!1,r;return r.value=e,r.done=!0,r};return a.next=a}}throw new TypeError(i(t)+" is not iterable")}return k.prototype=x,o(N,"constructor",{value:x,configurable:!0}),o(x,"constructor",{value:k,configurable:!0}),k.displayName=f(x,u,"GeneratorFunction"),t.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===k||"GeneratorFunction"===(t.displayName||t.name))},t.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,x):(e.__proto__=x,f(e,u,"GeneratorFunction")),e.prototype=Object.create(N),e},t.awrap=function(e){return{__await:e}},V(S.prototype),f(S.prototype,s,(function(){return this})),t.AsyncIterator=S,t.async=function(e,r,n,o,a){void 0===a&&(a=Promise);var i=new S(h(e,r,n,o),a);return t.isGeneratorFunction(r)?i:i.next().then((function(e){return e.done?e.value:i.next()}))},V(N),f(N,u,"Generator"),f(N,l,(function(){return this})),f(N,"toString",(function(){return"[object Generator]"})),t.keys=function(e){var t=Object(e),r=[];for(var n in t)r.push(n);return r.reverse(),function e(){for(;r.length;){var n=r.pop();if(n in t)return e.value=n,e.done=!1,e}return e.done=!0,e}},t.values=M,O.prototype={constructor:O,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=e,this.done=!1,this.delegate=null,this.method="next",this.arg=e,this.tryEntries.forEach(C),!t)for(var r in this)"t"===r.charAt(0)&&n.call(this,r)&&!isNaN(+r.slice(1))&&(this[r]=e)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var r=this;function o(n,o){return c.type="throw",c.arg=t,r.next=n,o&&(r.method="next",r.arg=e),!!o}for(var a=this.tryEntries.length-1;a>=0;--a){var i=this.tryEntries[a],c=i.completion;if("root"===i.tryLoc)return o("end");if(i.tryLoc<=this.prev){var l=n.call(i,"catchLoc"),s=n.call(i,"finallyLoc");if(l&&s){if(this.prev<i.catchLoc)return o(i.catchLoc,!0);if(this.prev<i.finallyLoc)return o(i.finallyLoc)}else if(l){if(this.prev<i.catchLoc)return o(i.catchLoc,!0)}else{if(!s)throw new Error("try statement without catch or finally");if(this.prev<i.finallyLoc)return o(i.finallyLoc)}}}},abrupt:function(e,t){for(var r=this.tryEntries.length-1;r>=0;--r){var o=this.tryEntries[r];if(o.tryLoc<=this.prev&&n.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var a=o;break}}a&&("break"===e||"continue"===e)&&a.tryLoc<=t&&t<=a.finallyLoc&&(a=null);var i=a?a.completion:{};return i.type=e,i.arg=t,a?(this.method="next",this.next=a.finallyLoc,y):this.complete(i)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),y},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.finallyLoc===e)return this.complete(r.completion,r.afterLoc),C(r),y}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.tryLoc===e){var n=r.completion;if("throw"===n.type){var o=n.arg;C(r)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(t,r,n){return this.delegate={iterator:M(t),resultName:r,nextLoc:n},"next"===this.method&&(this.arg=e),y}},t}function l(e,t,r,n,o,a,i){try{var c=e[a](i),l=c.value}catch(e){return void r(e)}c.done?t(l):Promise.resolve(l).then(n,o)}var s={class:"mx-4 my-4"},u={key:0,class:"alert alert-info mt-4"},f=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24",class:"stroke-current shrink-0 w-6 h-6"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"})],-1),h={key:1,class:"alert alert-success mt-4 mx-3"},p=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"})],-1),d={key:2,class:"alert alert-warning mt-4 mx-3"},m=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"})],-1),v={key:3,class:"alert alert-error mt-4 mx-3"},g=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"})],-1);const y={__name:"Messages",props:{flash:Object},setup:function(e){(0,a.useAppSettingStore)().showFlashMessage=!0;var t=e,r=((0,n.computed)((function(){return{"text-green-700 bg-green-100 dark:bg-green-200 dark:text-green-800":t.flash.success,"text-blue-700 bg-blue-100 dark:bg-blue-200 dark:text-blue-800":t.flash.message,"text-orange-700 bg-orange-100 dark:bg-orange-200 dark:text-orange-800":t.flash.warning,"text-red-700 bg-red-100 dark:bg-red-200 dark:text-red-800":t.flash.error}})),function(){var e,t=(e=c().mark((function e(){return c().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,o.Inertia.post(route("flash.clear"));case 2:o.Inertia.reload();case 3:case"end":return e.stop()}}),e)})),function(){var t=this,r=arguments;return new Promise((function(n,o){var a=e.apply(t,r);function i(e){l(a,n,o,i,c,"next",e)}function c(e){l(a,n,o,i,c,"throw",e)}i(void 0)}))});return function(){return t.apply(this,arguments)}}());return function(e,o){return(0,n.openBlock)(),(0,n.createElementBlock)("div",s,[(0,n.unref)(t).flash.message?((0,n.openBlock)(),(0,n.createElementBlock)("div",u,[f,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.message),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0),(0,n.unref)(t).flash.success?((0,n.openBlock)(),(0,n.createElementBlock)("div",h,[p,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.success),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0),(0,n.unref)(t).flash.warning?((0,n.openBlock)(),(0,n.createElementBlock)("div",d,[m,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.warning),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0),(0,n.unref)(t).flash.error?((0,n.openBlock)(),(0,n.createElementBlock)("div",v,[g,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.error),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0)])}}}},95017:(e,t,r)=>{r.r(t),r.d(t,{default:()=>l});var n=r(70821),o=r(24831),a=r(58358),i=r(59369),c={id:"topDiv"};const l={__name:"2",setup:function(e){(0,o.U)("2");var t=(0,a.useAppSettingStore)();return function(e,r){return(0,n.openBlock)(),(0,n.createElementBlock)("div",null,[(0,n.createElementVNode)("div",c,[(0,n.unref)(t).showFlashMessage?((0,n.openBlock)(),(0,n.createBlock)((0,n.unref)(i.Z),{key:0,flash:e.$page.props.flash},null,8,["flash"])):(0,n.createCommentVNode)("",!0)])])}}}}}]);
//# sourceMappingURL=5017.js.map