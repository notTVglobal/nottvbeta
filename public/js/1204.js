/*! For license information please see 1204.js.LICENSE.txt */
"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[1204],{32242:(e,t,r)=>{r.d(t,{Z:()=>i});var n=r(70821),o={class:"min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100"},a={class:"w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"};const c={},i=(0,r(83744).Z)(c,[["render",function(e,t){return(0,n.openBlock)(),(0,n.createElementBlock)("div",o,[(0,n.createElementVNode)("div",null,[(0,n.renderSlot)(e.$slots,"logo")]),(0,n.createElementVNode)("div",a,[(0,n.renderSlot)(e.$slots,"default")])])}]])},5556:(e,t,r)=>{r.d(t,{Z:()=>u});var n=r(70821),o=r(58358),a=r(39038),c=r(9680),i=["src"];const u={__name:"AuthenticationCardLogo",setup:function(e){var t=(0,o.useAppSettingStore)();t.pageReload=!1;var r=function(){t.noLayout?(t.pageReload=!0,c.Inertia.visit("/")):c.Inertia.visit("/")};return function(e,t){return(0,n.openBlock)(),(0,n.createBlock)((0,n.unref)(a.rU),{onClick:r},{default:(0,n.withCtx)((function(){return[(0,n.createElementVNode)("img",{src:"/storage/images/logo_black_311.png",alt:"image",class:"justify-center"},null,8,i)]})),_:1})}}}},6710:(e,t,r)=>{r.d(t,{Z:()=>a});var n=r(70821),o=["value"];const a={__name:"Input",props:{modelValue:String},emits:["update:modelValue"],setup:function(e,t){var r=t.expose,a=(0,n.ref)(null);return(0,n.onMounted)((function(){a.value.hasAttribute("autofocus")&&a.value.focus()})),r({focus:function(){return a.value.focus()}}),function(t,r){return(0,n.openBlock)(),(0,n.createElementBlock)("input",{ref_key:"input",ref:a,class:"border-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm",value:e.modelValue,onInput:r[0]||(r[0]=function(e){return t.$emit("update:modelValue",e.target.value)})},null,40,o)}}}},15957:(e,t,r)=>{r.d(t,{Z:()=>i});var n=r(70821),o={class:"block font-medium text-sm text-gray-700"},a={key:0},c={key:1};const i={__name:"Label",props:{value:String},setup:function(e){return function(t,r){return(0,n.openBlock)(),(0,n.createElementBlock)("label",o,[e.value?((0,n.openBlock)(),(0,n.createElementBlock)("span",a,(0,n.toDisplayString)(e.value),1)):((0,n.openBlock)(),(0,n.createElementBlock)("span",c,[(0,n.renderSlot)(t.$slots,"default")]))])}}}},30131:(e,t,r)=>{r.d(t,{Z:()=>u});var n=r(70821),o=r(39038),a={key:0},c=(0,n.createElementVNode)("div",{class:"font-medium text-red-600"}," Whoops! Something went wrong. ",-1),i={class:"mt-3 list-disc list-inside text-sm text-red-600"};const u={__name:"ValidationErrors",setup:function(e){var t=(0,n.computed)((function(){return(0,o.qt)().props.value.errors})),r=(0,n.computed)((function(){return Object.keys(t.value).length>0}));return function(e,o){return r.value?((0,n.openBlock)(),(0,n.createElementBlock)("div",a,[c,(0,n.createElementVNode)("ul",i,[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)(t.value,(function(e,t){return(0,n.openBlock)(),(0,n.createElementBlock)("li",{key:t},(0,n.toDisplayString)(e),1)})),128))])])):(0,n.createCommentVNode)("",!0)}}}},96898:(e,t,r)=>{r.d(t,{Z:()=>i});var n=r(70821),o=(0,n.createElementVNode)("div",null,null,-1),a={class:"overflow-scroll"};const c={},i=(0,r(83744).Z)(c,[["render",function(e,t){return(0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,null,[o,(0,n.createElementVNode)("div",a,[(0,n.renderSlot)(e.$slots,"default")])],64)}]])},81204:(e,t,r)=>{r.r(t),r.d(t,{default:()=>k});var n=r(70821),o=r(39038),a=r(32242),c=r(5556),i=r(28277),u=r(6710),l=r(15957),s=r(30131),f=r(96898);function d(e){return d="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},d(e)}function p(){p=function(){return t};var e,t={},r=Object.prototype,n=r.hasOwnProperty,o=Object.defineProperty||function(e,t,r){e[t]=r.value},a="function"==typeof Symbol?Symbol:{},c=a.iterator||"@@iterator",i=a.asyncIterator||"@@asyncIterator",u=a.toStringTag||"@@toStringTag";function l(e,t,r){return Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{l({},"")}catch(e){l=function(e,t,r){return e[t]=r}}function s(e,t,r,n){var a=t&&t.prototype instanceof w?t:w,c=Object.create(a.prototype),i=new Z(n||[]);return o(c,"_invoke",{value:L(e,r,i)}),c}function f(e,t,r){try{return{type:"normal",arg:e.call(t,r)}}catch(e){return{type:"throw",arg:e}}}t.wrap=s;var h="suspendedStart",m="suspendedYield",y="executing",v="completed",g={};function w(){}function k(){}function b(){}var x={};l(x,c,(function(){return this}));var E=Object.getPrototypeOf,_=E&&E(E(C([])));_&&_!==r&&n.call(_,c)&&(x=_);var V=b.prototype=w.prototype=Object.create(x);function B(e){["next","throw","return"].forEach((function(t){l(e,t,(function(e){return this._invoke(t,e)}))}))}function N(e,t){function r(o,a,c,i){var u=f(e[o],e,a);if("throw"!==u.type){var l=u.arg,s=l.value;return s&&"object"==d(s)&&n.call(s,"__await")?t.resolve(s.__await).then((function(e){r("next",e,c,i)}),(function(e){r("throw",e,c,i)})):t.resolve(s).then((function(e){l.value=e,c(l)}),(function(e){return r("throw",e,c,i)}))}i(u.arg)}var a;o(this,"_invoke",{value:function(e,n){function o(){return new t((function(t,o){r(e,n,t,o)}))}return a=a?a.then(o,o):o()}})}function L(t,r,n){var o=h;return function(a,c){if(o===y)throw new Error("Generator is already running");if(o===v){if("throw"===a)throw c;return{value:e,done:!0}}for(n.method=a,n.arg=c;;){var i=n.delegate;if(i){var u=S(i,n);if(u){if(u===g)continue;return u}}if("next"===n.method)n.sent=n._sent=n.arg;else if("throw"===n.method){if(o===h)throw o=v,n.arg;n.dispatchException(n.arg)}else"return"===n.method&&n.abrupt("return",n.arg);o=y;var l=f(t,r,n);if("normal"===l.type){if(o=n.done?v:m,l.arg===g)continue;return{value:l.arg,done:n.done}}"throw"===l.type&&(o=v,n.method="throw",n.arg=l.arg)}}}function S(t,r){var n=r.method,o=t.iterator[n];if(o===e)return r.delegate=null,"throw"===n&&t.iterator.return&&(r.method="return",r.arg=e,S(t,r),"throw"===r.method)||"return"!==n&&(r.method="throw",r.arg=new TypeError("The iterator does not provide a '"+n+"' method")),g;var a=f(o,t.iterator,r.arg);if("throw"===a.type)return r.method="throw",r.arg=a.arg,r.delegate=null,g;var c=a.arg;return c?c.done?(r[t.resultName]=c.value,r.next=t.nextLoc,"return"!==r.method&&(r.method="next",r.arg=e),r.delegate=null,g):c:(r.method="throw",r.arg=new TypeError("iterator result is not an object"),r.delegate=null,g)}function j(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function O(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function Z(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(j,this),this.reset(!0)}function C(t){if(t||""===t){var r=t[c];if(r)return r.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var o=-1,a=function r(){for(;++o<t.length;)if(n.call(t,o))return r.value=t[o],r.done=!1,r;return r.value=e,r.done=!0,r};return a.next=a}}throw new TypeError(d(t)+" is not iterable")}return k.prototype=b,o(V,"constructor",{value:b,configurable:!0}),o(b,"constructor",{value:k,configurable:!0}),k.displayName=l(b,u,"GeneratorFunction"),t.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===k||"GeneratorFunction"===(t.displayName||t.name))},t.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,b):(e.__proto__=b,l(e,u,"GeneratorFunction")),e.prototype=Object.create(V),e},t.awrap=function(e){return{__await:e}},B(N.prototype),l(N.prototype,i,(function(){return this})),t.AsyncIterator=N,t.async=function(e,r,n,o,a){void 0===a&&(a=Promise);var c=new N(s(e,r,n,o),a);return t.isGeneratorFunction(r)?c:c.next().then((function(e){return e.done?e.value:c.next()}))},B(V),l(V,u,"Generator"),l(V,c,(function(){return this})),l(V,"toString",(function(){return"[object Generator]"})),t.keys=function(e){var t=Object(e),r=[];for(var n in t)r.push(n);return r.reverse(),function e(){for(;r.length;){var n=r.pop();if(n in t)return e.value=n,e.done=!1,e}return e.done=!0,e}},t.values=C,Z.prototype={constructor:Z,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=e,this.done=!1,this.delegate=null,this.method="next",this.arg=e,this.tryEntries.forEach(O),!t)for(var r in this)"t"===r.charAt(0)&&n.call(this,r)&&!isNaN(+r.slice(1))&&(this[r]=e)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var r=this;function o(n,o){return i.type="throw",i.arg=t,r.next=n,o&&(r.method="next",r.arg=e),!!o}for(var a=this.tryEntries.length-1;a>=0;--a){var c=this.tryEntries[a],i=c.completion;if("root"===c.tryLoc)return o("end");if(c.tryLoc<=this.prev){var u=n.call(c,"catchLoc"),l=n.call(c,"finallyLoc");if(u&&l){if(this.prev<c.catchLoc)return o(c.catchLoc,!0);if(this.prev<c.finallyLoc)return o(c.finallyLoc)}else if(u){if(this.prev<c.catchLoc)return o(c.catchLoc,!0)}else{if(!l)throw new Error("try statement without catch or finally");if(this.prev<c.finallyLoc)return o(c.finallyLoc)}}}},abrupt:function(e,t){for(var r=this.tryEntries.length-1;r>=0;--r){var o=this.tryEntries[r];if(o.tryLoc<=this.prev&&n.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var a=o;break}}a&&("break"===e||"continue"===e)&&a.tryLoc<=t&&t<=a.finallyLoc&&(a=null);var c=a?a.completion:{};return c.type=e,c.arg=t,a?(this.method="next",this.next=a.finallyLoc,g):this.complete(c)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),g},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.finallyLoc===e)return this.complete(r.completion,r.afterLoc),O(r),g}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.tryLoc===e){var n=r.completion;if("throw"===n.type){var o=n.arg;O(r)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(t,r,n){return this.delegate={iterator:C(t),resultName:r,nextLoc:n},"next"===this.method&&(this.arg=e),g}},t}function h(e,t,r,n,o,a,c){try{var i=e[a](c),u=i.value}catch(e){return void r(e)}i.done?t(u):Promise.resolve(u).then(n,o)}var m={class:"mb-4 text-sm text-gray-600"},y={key:0},v={key:1},g={class:"flex items-center justify-end mt-4"},w={layout:f.Z};const k=Object.assign(w,{__name:"TwoFactorChallengeOld",setup:function(e){var t=(0,n.ref)(!1),r=(0,o.cI)({code:"",recovery_code:""}),f=(0,n.ref)(null),d=(0,n.ref)(null),w=function(){var e,o=(e=p().mark((function e(){return p().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.value^=!0,e.next=3,(0,n.nextTick)();case 3:t.value?(f.value.focus(),r.code=""):(d.value.focus(),r.recovery_code="");case 4:case"end":return e.stop()}}),e)})),function(){var t=this,r=arguments;return new Promise((function(n,o){var a=e.apply(t,r);function c(e){h(a,n,o,c,i,"next",e)}function i(e){h(a,n,o,c,i,"throw",e)}c(void 0)}))});return function(){return o.apply(this,arguments)}}(),k=function(){r.post(route("two-factor.login"))};return function(e,p){return(0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,null,[(0,n.createVNode)((0,n.unref)(o.Fb),{title:"Two-factor Confirmation"}),(0,n.createVNode)(a.Z,null,{logo:(0,n.withCtx)((function(){return[(0,n.createVNode)(c.Z)]})),default:(0,n.withCtx)((function(){return[(0,n.createElementVNode)("div",m,[t.value?((0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,{key:1},[(0,n.createTextVNode)(" Please confirm access to your account by entering one of your emergency recovery codes. ")],64)):((0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,{key:0},[(0,n.createTextVNode)(" Please confirm access to your account by entering the authentication code provided by your authenticator application. ")],64))]),(0,n.createVNode)(s.Z,{class:"mb-4"}),(0,n.createElementVNode)("form",{onSubmit:(0,n.withModifiers)(k,["prevent"])},[t.value?((0,n.openBlock)(),(0,n.createElementBlock)("div",v,[(0,n.createVNode)(l.Z,{for:"recovery_code",value:"Recovery Code"}),(0,n.createVNode)(u.Z,{id:"recovery_code",ref_key:"recoveryCodeInput",ref:f,modelValue:(0,n.unref)(r).recovery_code,"onUpdate:modelValue":p[1]||(p[1]=function(e){return(0,n.unref)(r).recovery_code=e}),type:"text",class:"mt-1 block w-full",autocomplete:"one-time-code"},null,8,["modelValue"])])):((0,n.openBlock)(),(0,n.createElementBlock)("div",y,[(0,n.createVNode)(l.Z,{for:"code",value:"Code"}),(0,n.createVNode)(u.Z,{id:"code",ref_key:"codeInput",ref:d,modelValue:(0,n.unref)(r).code,"onUpdate:modelValue":p[0]||(p[0]=function(e){return(0,n.unref)(r).code=e}),type:"text",inputmode:"numeric",class:"mt-1 block w-full",autofocus:"",autocomplete:"one-time-code"},null,8,["modelValue"])])),(0,n.createElementVNode)("div",g,[(0,n.createElementVNode)("button",{type:"button",class:"text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer",onClick:(0,n.withModifiers)(w,["prevent"])},[t.value?((0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,{key:1},[(0,n.createTextVNode)(" Use an authentication code ")],64)):((0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,{key:0},[(0,n.createTextVNode)(" Use a recovery code ")],64))]),(0,n.createVNode)(i.Z,{class:(0,n.normalizeClass)(["ml-4",{"opacity-25":(0,n.unref)(r).processing}]),disabled:(0,n.unref)(r).processing},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Log in ")]})),_:1},8,["class","disabled"])])],32)]})),_:1})],64)}}})}}]);
//# sourceMappingURL=1204.js.map