/*! For license information please see 9413.js.LICENSE.txt */
"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[9413,9559],{24831:(e,t,n)=>{n.d(t,{U:()=>s});var r=n(70821),o=n(90771),a=n(58358),i=n(40191),c=n(9680);function s(e){var t=(0,o.useUserStore)(),n=(0,a.useAppSettingStore)(),s=(0,i.useVideoPlayerStore)();n.currentPage=e,n.showFlashMessage=!0,n.pageIsHidden=!1,t.isMobile&&(n.ott=0),s.makeVideoTopRight();(0,r.onBeforeMount)((function(){n.pageReload&&(n.pageReload=!1,window.location.reload(!0))})),(0,r.onMounted)((function(){if(!(""!==window.location.search)){var e=document.getElementById("topDiv");e&&e.scrollIntoView()}n.setPrevUrl(),n.noLayout=!1,c.Inertia.reload()}))}},4908:(e,t,n)=>{n.d(t,{Z:()=>c});var r=n(94015),o=n.n(r),a=n(23645),i=n.n(a)()(o());i.push([e.id,"@import url(https://fonts.googleapis.com/css?family=Raleway&display=swap);"]),i.push([e.id,"[data-v-6a668dd6]:root{--light-grey:#f6f9fc;--dark-terminal-color:#0a2540;--accent-color:#635bff;--radius:3px}body[data-v-6a668dd6]{color:var(--dark-terminal-color);display:flex;font-family:Raleway;font-size:1.2em;justify-content:center;padding:20px}main[data-v-6a668dd6]{width:480px}form>*[data-v-6a668dd6]{margin:10px 0}button[data-v-6a668dd6]{background-color:var(--accent-color);background:var(--accent-color);border:0;border-radius:var(--radius);color:#fff;cursor:pointer;display:block;font-weight:600;margin-top:16px;padding:12px 16px;transition:all .2s ease}button[data-v-6a668dd6]:hover{filter:contrast(115%)}button[data-v-6a668dd6]:active{filter:brightness(.9);transform:translateY(0) scale(.98)}button[data-v-6a668dd6]:disabled{cursor:none;opacity:.5}input[data-v-6a668dd6],select[data-v-6a668dd6]{font-size:1.1em;margin-bottom:10px;width:100%}input[data-v-6a668dd6],label[data-v-6a668dd6],select[data-v-6a668dd6]{display:block}a[data-v-6a668dd6]{color:var(--accent-color);font-weight:900}small[data-v-6a668dd6]{font-size:.6em}fieldset[data-v-6a668dd6],input[data-v-6a668dd6],select[data-v-6a668dd6]{border:1px solid #efefef}#payment-form[data-v-6a668dd6]{border:1px solid #f6f9fc;box-shadow:0 30px 50px -20px #32325d40,0 30px 60px -30px #0000004d}#messages[data-v-6a668dd6],#payment-form[data-v-6a668dd6]{border-radius:var(--radius);margin:20px 0;padding:20px}#messages[data-v-6a668dd6]{background-color:#0a253c;color:#00d924;font-family:source-code-pro,Menlo,Monaco,Consolas,Courier New;font-size:.7em}","",{version:3,sources:["webpack://./resources/js/Pages/Subscribe2.vue"],names:[],mappings:"AA8HA,uBACE,oBAAqB,CACrB,6BAA8B,CAC9B,sBAAuB,CACvB,YACF,CAEA,sBAME,gCAAiC,CAHjC,YAAa,CADb,mBAAsB,CAGtB,eAAgB,CADhB,sBAAuB,CAHvB,YAMF,CAEA,sBACE,WACF,CAEA,wBACE,aACF,CAEA,wBACE,oCAAqC,CAIrC,8BAA+B,CAG/B,QAAS,CAFT,2BAA4B,CAC5B,UAAY,CAKZ,cAAe,CAEf,aAAc,CAHd,eAAgB,CADhB,eAAgB,CADhB,iBAAkB,CAIlB,uBAXF,CAeA,8BACE,qBACF,CAEA,+BAEE,qBAAuB,CADvB,kCAEF,CAEA,iCAEE,WAAY,CADZ,UAEF,CAEA,+CAEE,eAAgB,CAEhB,kBAAmB,CADnB,UAEF,CAEA,sEANE,aAQF,CAEA,mBACE,yBAA0B,CAC1B,eACF,CAEA,uBACE,cACF,CAEA,yEACE,wBACF,CAEA,+BACE,wBAAyB,CAIzB,kEACF,CAEA,0DANE,2BAA4B,CAE5B,aAAc,CADd,YAaF,CARA,2BAEE,wBAAyB,CACzB,aAAc,CAFd,6DAAoE,CAMpE,cACF",sourcesContent:["<template>\n\n  <Head title=\"TEST SUBSCRIBE\"/>\n  \x3c!--        <script src=\"https://js.stripe.com/v3/\" defer><\/script>--\x3e\n\n  <div id=\"topDiv\" class=\"h-[calc(100vh-16rem)] text-center bg-gray-800 text-white p-10\">\n\n\n    <Message v-if=\"appSettingStore.showFlashMessage\" :flash=\"$page.props.flash\"/>\n\n    \x3c!--            <h1 class=\"text-3xl font-semibold pb-3\">Become a notTV Premium Subscriber</h1>--\x3e\n\n    <div class=\"flex justify-center w-full\">\n      <div class=\"flex flex-col justify-center w-fit bg-gray-900 rounded-md py-10 mb-24\">\n\n        <main>\n          <h1>Payment</h1>\n\n          <p>\n            Enable more payment method types\n            <a\n                href=\"https://dashboard.stripe.com/settings/payment_methods\"\n                target=\"_blank\"\n            >in your dashboard</a>.\n          </p>\n\n          <form\n              id=\"payment-form\"\n              @submit.prevent=\"handleSubmit\"\n          >\n            <div id=\"link-authentication-element\"/>\n            <div id=\"payment-element\"/>\n            <button\n                id=\"submit\"\n                :disabled=\"isLoading\"\n            >\n              Pay now\n            </button>\n            <sr-messages :messages=\"messages\"/>\n          </form>\n        </main>\n\n      </div>\n    </div>\n\n\n  </div>\n\n</template>\n\n<script setup>\nimport { Inertia } from '@inertiajs/inertia'\nimport { onBeforeMount, onMounted, ref } from 'vue'\nimport { useForm } from '@inertiajs/inertia-vue3'\nimport { loadStripe } from '@stripe/stripe-js'\nimport { usePageSetup } from '@/Utilities/PageSetup'\nimport { useAppSettingStore } from '@/Stores/AppSettingStore'\nimport Message from '@/Components/Global/Modals/Messages'\nimport SrMessages from './SrMessages'\n\nusePageSetup('subscribe')\n\nconst appSettingStore = useAppSettingStore()\n\nconst isLoading = ref(false)\nconst messages = ref([])\n\nlet stripe\nlet elements\n\nonMounted(async () => {\n  const {publishableKey} = await fetch('/api/config').then((res) => res.json())\n  stripe = await loadStripe('pk_test_51KJwK5Kahp38LUVYOjg7h425exCr6UZmMm1M24d31ZaS0HTsgPWIZE9Hd2F0KnREVHuPm2VHesX3J5SQfFFg7fTC00DMNpq1Lj')\n\n  const {clientSecret, error: backendError} = await fetch('/api/create-payment-intent').then((res) => res.json())\n\n  if (backendError) {\n    messages.value.push(backendError.message)\n  }\n  messages.value.push(`Client secret returned.`)\n\n  elements = stripe.elements({clientSecret})\n  const paymentElement = elements.create('payment')\n  paymentElement.mount('#payment-element')\n  const linkAuthenticationElement = elements.create('linkAuthentication')\n  linkAuthenticationElement.mount('#link-authentication-element')\n  isLoading.value = false\n})\n\nconst handleSubmit = async () => {\n  if (isLoading.value) {\n    return\n  }\n\n  isLoading.value = true\n\n  const {error} = await stripe.confirmPayment({\n    elements,\n    confirmParams: {\n      return_url: `${window.location.origin}/return`,\n    },\n  })\n\n  if (error.type === 'card_error' || error.type === 'validation_error') {\n    messages.value.push(error.message)\n  } else {\n    messages.value.push('An unexpected error occured.')\n  }\n\n  isLoading.value = false\n}\n\nlet props = defineProps({\n  intent: Object,\n  card: ref(null),\n})\n\nlet form = useForm({\n  name: '',\n})\n\n<\/script>\n\n<style scoped>\n@import url('https://fonts.googleapis.com/css?family=Raleway&display=swap');\n\n:root {\n  --light-grey: #F6F9FC;\n  --dark-terminal-color: #0A2540;\n  --accent-color: #635BFF;\n  --radius: 3px;\n}\n\nbody {\n  padding: 20px;\n  font-family: 'Raleway';\n  display: flex;\n  justify-content: center;\n  font-size: 1.2em;\n  color: var(--dark-terminal-color);\n}\n\nmain {\n  width: 480px;\n}\n\nform > * {\n  margin: 10px 0;\n}\n\nbutton {\n  background-color: var(--accent-color);\n}\n\nbutton {\n  background: var(--accent-color);\n  border-radius: var(--radius);\n  color: white;\n  border: 0;\n  padding: 12px 16px;\n  margin-top: 16px;\n  font-weight: 600;\n  cursor: pointer;\n  transition: all 0.2s ease;\n  display: block;\n}\n\nbutton:hover {\n  filter: contrast(115%);\n}\n\nbutton:active {\n  transform: translateY(0px) scale(0.98);\n  filter: brightness(0.9);\n}\n\nbutton:disabled {\n  opacity: 0.5;\n  cursor: none;\n}\n\ninput, select {\n  display: block;\n  font-size: 1.1em;\n  width: 100%;\n  margin-bottom: 10px;\n}\n\nlabel {\n  display: block;\n}\n\na {\n  color: var(--accent-color);\n  font-weight: 900;\n}\n\nsmall {\n  font-size: .6em;\n}\n\nfieldset, input, select {\n  border: 1px solid #efefef;\n}\n\n#payment-form {\n  border: #F6F9FC solid 1px;\n  border-radius: var(--radius);\n  padding: 20px;\n  margin: 20px 0;\n  box-shadow: 0 30px 50px -20px rgb(50 50 93 / 25%), 0 30px 60px -30px rgb(0 0 0 / 30%);\n}\n\n#messages {\n  font-family: source-code-pro, Menlo, Monaco, Consolas, 'Courier New';\n  background-color: #0A253C;\n  color: #00D924;\n  padding: 20px;\n  margin: 20px 0;\n  border-radius: var(--radius);\n  font-size: 0.7em;\n}\n\n\n</style>\n\n\n"],sourceRoot:""}]);const c=i},59369:(e,t,n)=>{n.d(t,{Z:()=>g});var r=n(70821),o=n(9680),a=n(58358);function i(e){return i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},i(e)}function c(){c=function(){return t};var e,t={},n=Object.prototype,r=n.hasOwnProperty,o=Object.defineProperty||function(e,t,n){e[t]=n.value},a="function"==typeof Symbol?Symbol:{},s=a.iterator||"@@iterator",l=a.asyncIterator||"@@asyncIterator",u=a.toStringTag||"@@toStringTag";function f(e,t,n){return Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{f({},"")}catch(e){f=function(e,t,n){return e[t]=n}}function p(e,t,n,r){var a=t&&t.prototype instanceof b?t:b,i=Object.create(a.prototype),c=new O(r||[]);return o(i,"_invoke",{value:L(e,n,c)}),i}function d(e,t,n){try{return{type:"normal",arg:e.call(t,n)}}catch(e){return{type:"throw",arg:e}}}t.wrap=p;var h="suspendedStart",m="suspendedYield",y="executing",v="completed",g={};function b(){}function w(){}function A(){}var x={};f(x,s,(function(){return this}));var E=Object.getPrototypeOf,k=E&&E(E(F([])));k&&k!==n&&r.call(k,s)&&(x=k);var C=A.prototype=b.prototype=Object.create(x);function B(e){["next","throw","return"].forEach((function(t){f(e,t,(function(e){return this._invoke(t,e)}))}))}function S(e,t){function n(o,a,c,s){var l=d(e[o],e,a);if("throw"!==l.type){var u=l.arg,f=u.value;return f&&"object"==i(f)&&r.call(f,"__await")?t.resolve(f.__await).then((function(e){n("next",e,c,s)}),(function(e){n("throw",e,c,s)})):t.resolve(f).then((function(e){u.value=e,c(u)}),(function(e){return n("throw",e,c,s)}))}s(l.arg)}var a;o(this,"_invoke",{value:function(e,r){function o(){return new t((function(t,o){n(e,r,t,o)}))}return a=a?a.then(o,o):o()}})}function L(t,n,r){var o=h;return function(a,i){if(o===y)throw new Error("Generator is already running");if(o===v){if("throw"===a)throw i;return{value:e,done:!0}}for(r.method=a,r.arg=i;;){var c=r.delegate;if(c){var s=j(c,r);if(s){if(s===g)continue;return s}}if("next"===r.method)r.sent=r._sent=r.arg;else if("throw"===r.method){if(o===h)throw o=v,r.arg;r.dispatchException(r.arg)}else"return"===r.method&&r.abrupt("return",r.arg);o=y;var l=d(t,n,r);if("normal"===l.type){if(o=r.done?v:m,l.arg===g)continue;return{value:l.arg,done:r.done}}"throw"===l.type&&(o=v,r.method="throw",r.arg=l.arg)}}}function j(t,n){var r=n.method,o=t.iterator[r];if(o===e)return n.delegate=null,"throw"===r&&t.iterator.return&&(n.method="return",n.arg=e,j(t,n),"throw"===n.method)||"return"!==r&&(n.method="throw",n.arg=new TypeError("The iterator does not provide a '"+r+"' method")),g;var a=d(o,t.iterator,n.arg);if("throw"===a.type)return n.method="throw",n.arg=a.arg,n.delegate=null,g;var i=a.arg;return i?i.done?(n[t.resultName]=i.value,n.next=t.nextLoc,"return"!==n.method&&(n.method="next",n.arg=e),n.delegate=null,g):i:(n.method="throw",n.arg=new TypeError("iterator result is not an object"),n.delegate=null,g)}function N(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function _(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function O(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(N,this),this.reset(!0)}function F(t){if(t||""===t){var n=t[s];if(n)return n.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var o=-1,a=function n(){for(;++o<t.length;)if(r.call(t,o))return n.value=t[o],n.done=!1,n;return n.value=e,n.done=!0,n};return a.next=a}}throw new TypeError(i(t)+" is not iterable")}return w.prototype=A,o(C,"constructor",{value:A,configurable:!0}),o(A,"constructor",{value:w,configurable:!0}),w.displayName=f(A,u,"GeneratorFunction"),t.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===w||"GeneratorFunction"===(t.displayName||t.name))},t.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,A):(e.__proto__=A,f(e,u,"GeneratorFunction")),e.prototype=Object.create(C),e},t.awrap=function(e){return{__await:e}},B(S.prototype),f(S.prototype,l,(function(){return this})),t.AsyncIterator=S,t.async=function(e,n,r,o,a){void 0===a&&(a=Promise);var i=new S(p(e,n,r,o),a);return t.isGeneratorFunction(n)?i:i.next().then((function(e){return e.done?e.value:i.next()}))},B(C),f(C,u,"Generator"),f(C,s,(function(){return this})),f(C,"toString",(function(){return"[object Generator]"})),t.keys=function(e){var t=Object(e),n=[];for(var r in t)n.push(r);return n.reverse(),function e(){for(;n.length;){var r=n.pop();if(r in t)return e.value=r,e.done=!1,e}return e.done=!0,e}},t.values=F,O.prototype={constructor:O,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=e,this.done=!1,this.delegate=null,this.method="next",this.arg=e,this.tryEntries.forEach(_),!t)for(var n in this)"t"===n.charAt(0)&&r.call(this,n)&&!isNaN(+n.slice(1))&&(this[n]=e)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var n=this;function o(r,o){return c.type="throw",c.arg=t,n.next=r,o&&(n.method="next",n.arg=e),!!o}for(var a=this.tryEntries.length-1;a>=0;--a){var i=this.tryEntries[a],c=i.completion;if("root"===i.tryLoc)return o("end");if(i.tryLoc<=this.prev){var s=r.call(i,"catchLoc"),l=r.call(i,"finallyLoc");if(s&&l){if(this.prev<i.catchLoc)return o(i.catchLoc,!0);if(this.prev<i.finallyLoc)return o(i.finallyLoc)}else if(s){if(this.prev<i.catchLoc)return o(i.catchLoc,!0)}else{if(!l)throw new Error("try statement without catch or finally");if(this.prev<i.finallyLoc)return o(i.finallyLoc)}}}},abrupt:function(e,t){for(var n=this.tryEntries.length-1;n>=0;--n){var o=this.tryEntries[n];if(o.tryLoc<=this.prev&&r.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var a=o;break}}a&&("break"===e||"continue"===e)&&a.tryLoc<=t&&t<=a.finallyLoc&&(a=null);var i=a?a.completion:{};return i.type=e,i.arg=t,a?(this.method="next",this.next=a.finallyLoc,g):this.complete(i)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),g},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var n=this.tryEntries[t];if(n.finallyLoc===e)return this.complete(n.completion,n.afterLoc),_(n),g}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var n=this.tryEntries[t];if(n.tryLoc===e){var r=n.completion;if("throw"===r.type){var o=r.arg;_(n)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(t,n,r){return this.delegate={iterator:F(t),resultName:n,nextLoc:r},"next"===this.method&&(this.arg=e),g}},t}function s(e,t,n,r,o,a,i){try{var c=e[a](i),s=c.value}catch(e){return void n(e)}c.done?t(s):Promise.resolve(s).then(r,o)}var l={class:"mx-4 my-4"},u={key:0,class:"alert alert-info mt-4"},f=(0,r.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24",class:"stroke-current shrink-0 w-6 h-6"},[(0,r.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"})],-1),p={key:1,class:"alert alert-success mt-4 mx-3"},d=(0,r.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,r.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"})],-1),h={key:2,class:"alert alert-warning mt-4 mx-3"},m=(0,r.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,r.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"})],-1),y={key:3,class:"alert alert-error mt-4 mx-3"},v=(0,r.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,r.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"})],-1);const g={__name:"Messages",props:{flash:Object},setup:function(e){(0,a.useAppSettingStore)().showFlashMessage=!0;var t=e,n=((0,r.computed)((function(){return{"text-green-700 bg-green-100 dark:bg-green-200 dark:text-green-800":t.flash.success,"text-blue-700 bg-blue-100 dark:bg-blue-200 dark:text-blue-800":t.flash.message,"text-orange-700 bg-orange-100 dark:bg-orange-200 dark:text-orange-800":t.flash.warning,"text-red-700 bg-red-100 dark:bg-red-200 dark:text-red-800":t.flash.error}})),function(){var e,t=(e=c().mark((function e(){return c().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,o.Inertia.post(route("flash.clear"));case 2:o.Inertia.reload();case 3:case"end":return e.stop()}}),e)})),function(){var t=this,n=arguments;return new Promise((function(r,o){var a=e.apply(t,n);function i(e){s(a,r,o,i,c,"next",e)}function c(e){s(a,r,o,i,c,"throw",e)}i(void 0)}))});return function(){return t.apply(this,arguments)}}());return function(e,o){return(0,r.openBlock)(),(0,r.createElementBlock)("div",l,[(0,r.unref)(t).flash.message?((0,r.openBlock)(),(0,r.createElementBlock)("div",u,[f,(0,r.createElementVNode)("span",null,(0,r.toDisplayString)((0,r.unref)(t).flash.message),1),(0,r.createElementVNode)("button",{class:"text-xs ml-12",onClick:n}," Close")])):(0,r.createCommentVNode)("",!0),(0,r.unref)(t).flash.success?((0,r.openBlock)(),(0,r.createElementBlock)("div",p,[d,(0,r.createElementVNode)("span",null,(0,r.toDisplayString)((0,r.unref)(t).flash.success),1),(0,r.createElementVNode)("button",{class:"text-xs ml-12",onClick:n}," Close")])):(0,r.createCommentVNode)("",!0),(0,r.unref)(t).flash.warning?((0,r.openBlock)(),(0,r.createElementBlock)("div",h,[m,(0,r.createElementVNode)("span",null,(0,r.toDisplayString)((0,r.unref)(t).flash.warning),1),(0,r.createElementVNode)("button",{class:"text-xs ml-12",onClick:n}," Close")])):(0,r.createCommentVNode)("",!0),(0,r.unref)(t).flash.error?((0,r.openBlock)(),(0,r.createElementBlock)("div",y,[v,(0,r.createElementVNode)("span",null,(0,r.toDisplayString)((0,r.unref)(t).flash.error),1),(0,r.createElementVNode)("button",{class:"text-xs ml-12",onClick:n}," Close")])):(0,r.createCommentVNode)("",!0)])}}}},79559:(e,t,n)=>{n.r(t),n.d(t,{default:()=>f});var r=n(70821);function o(e){return o="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},o(e)}function a(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function i(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?a(Object(n),!0).forEach((function(t){c(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):a(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function c(e,t,n){var r;return r=function(e,t){if("object"!=o(e)||!e)return e;var n=e[Symbol.toPrimitive];if(void 0!==n){var r=n.call(e,t||"default");if("object"!=o(r))return r;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(t,"string"),(t="symbol"==o(r)?r:String(r))in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}var s={id:"messages",role:"alert"},l=["href"],u=(0,r.createElementVNode)("br",null,null,-1);const f={__name:"SrMessages",props:{messages:{type:Array,required:!0}},setup:function(e){var t=e,n=(0,r.toRef)(t,"messages"),o=(0,r.computed)((function(){return n.value.map((function(e){var t=/(pi_(\S*)\b)/,n=e.match(t);return i(i({},n&&{paymentIntent:n[0]}),{},{content:e.replace(t,"")||e})}))}));return function(e,t){return(0,r.openBlock)(),(0,r.createElementBlock)("div",s,[((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)(o.value,(function(e){return(0,r.openBlock)(),(0,r.createElementBlock)("span",{key:e},[(0,r.createTextVNode)(" > "+(0,r.toDisplayString)(e.content),1),e.paymentIntent?((0,r.openBlock)(),(0,r.createElementBlock)("a",{key:0,href:(t=e.paymentIntent,"https://dashboard.stripe.com/test/payments/".concat(t))},(0,r.toDisplayString)(e.paymentIntent),9,l)):(0,r.createCommentVNode)("",!0),u]);var t})),128))])}}}},79413:(e,t,n)=>{n.r(t),n.d(t,{default:()=>L});var r=n(70821),o=(n(9680),n(39038)),a=n(91291),i=n(24831),c=n(58358),s=n(59369),l=n(79559);function u(e){return u="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},u(e)}function f(){f=function(){return t};var e,t={},n=Object.prototype,r=n.hasOwnProperty,o=Object.defineProperty||function(e,t,n){e[t]=n.value},a="function"==typeof Symbol?Symbol:{},i=a.iterator||"@@iterator",c=a.asyncIterator||"@@asyncIterator",s=a.toStringTag||"@@toStringTag";function l(e,t,n){return Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{l({},"")}catch(e){l=function(e,t,n){return e[t]=n}}function p(e,t,n,r){var a=t&&t.prototype instanceof b?t:b,i=Object.create(a.prototype),c=new O(r||[]);return o(i,"_invoke",{value:L(e,n,c)}),i}function d(e,t,n){try{return{type:"normal",arg:e.call(t,n)}}catch(e){return{type:"throw",arg:e}}}t.wrap=p;var h="suspendedStart",m="suspendedYield",y="executing",v="completed",g={};function b(){}function w(){}function A(){}var x={};l(x,i,(function(){return this}));var E=Object.getPrototypeOf,k=E&&E(E(F([])));k&&k!==n&&r.call(k,i)&&(x=k);var C=A.prototype=b.prototype=Object.create(x);function B(e){["next","throw","return"].forEach((function(t){l(e,t,(function(e){return this._invoke(t,e)}))}))}function S(e,t){function n(o,a,i,c){var s=d(e[o],e,a);if("throw"!==s.type){var l=s.arg,f=l.value;return f&&"object"==u(f)&&r.call(f,"__await")?t.resolve(f.__await).then((function(e){n("next",e,i,c)}),(function(e){n("throw",e,i,c)})):t.resolve(f).then((function(e){l.value=e,i(l)}),(function(e){return n("throw",e,i,c)}))}c(s.arg)}var a;o(this,"_invoke",{value:function(e,r){function o(){return new t((function(t,o){n(e,r,t,o)}))}return a=a?a.then(o,o):o()}})}function L(t,n,r){var o=h;return function(a,i){if(o===y)throw new Error("Generator is already running");if(o===v){if("throw"===a)throw i;return{value:e,done:!0}}for(r.method=a,r.arg=i;;){var c=r.delegate;if(c){var s=j(c,r);if(s){if(s===g)continue;return s}}if("next"===r.method)r.sent=r._sent=r.arg;else if("throw"===r.method){if(o===h)throw o=v,r.arg;r.dispatchException(r.arg)}else"return"===r.method&&r.abrupt("return",r.arg);o=y;var l=d(t,n,r);if("normal"===l.type){if(o=r.done?v:m,l.arg===g)continue;return{value:l.arg,done:r.done}}"throw"===l.type&&(o=v,r.method="throw",r.arg=l.arg)}}}function j(t,n){var r=n.method,o=t.iterator[r];if(o===e)return n.delegate=null,"throw"===r&&t.iterator.return&&(n.method="return",n.arg=e,j(t,n),"throw"===n.method)||"return"!==r&&(n.method="throw",n.arg=new TypeError("The iterator does not provide a '"+r+"' method")),g;var a=d(o,t.iterator,n.arg);if("throw"===a.type)return n.method="throw",n.arg=a.arg,n.delegate=null,g;var i=a.arg;return i?i.done?(n[t.resultName]=i.value,n.next=t.nextLoc,"return"!==n.method&&(n.method="next",n.arg=e),n.delegate=null,g):i:(n.method="throw",n.arg=new TypeError("iterator result is not an object"),n.delegate=null,g)}function N(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function _(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function O(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(N,this),this.reset(!0)}function F(t){if(t||""===t){var n=t[i];if(n)return n.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var o=-1,a=function n(){for(;++o<t.length;)if(r.call(t,o))return n.value=t[o],n.done=!1,n;return n.value=e,n.done=!0,n};return a.next=a}}throw new TypeError(u(t)+" is not iterable")}return w.prototype=A,o(C,"constructor",{value:A,configurable:!0}),o(A,"constructor",{value:w,configurable:!0}),w.displayName=l(A,s,"GeneratorFunction"),t.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===w||"GeneratorFunction"===(t.displayName||t.name))},t.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,A):(e.__proto__=A,l(e,s,"GeneratorFunction")),e.prototype=Object.create(C),e},t.awrap=function(e){return{__await:e}},B(S.prototype),l(S.prototype,c,(function(){return this})),t.AsyncIterator=S,t.async=function(e,n,r,o,a){void 0===a&&(a=Promise);var i=new S(p(e,n,r,o),a);return t.isGeneratorFunction(n)?i:i.next().then((function(e){return e.done?e.value:i.next()}))},B(C),l(C,s,"Generator"),l(C,i,(function(){return this})),l(C,"toString",(function(){return"[object Generator]"})),t.keys=function(e){var t=Object(e),n=[];for(var r in t)n.push(r);return n.reverse(),function e(){for(;n.length;){var r=n.pop();if(r in t)return e.value=r,e.done=!1,e}return e.done=!0,e}},t.values=F,O.prototype={constructor:O,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=e,this.done=!1,this.delegate=null,this.method="next",this.arg=e,this.tryEntries.forEach(_),!t)for(var n in this)"t"===n.charAt(0)&&r.call(this,n)&&!isNaN(+n.slice(1))&&(this[n]=e)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var n=this;function o(r,o){return c.type="throw",c.arg=t,n.next=r,o&&(n.method="next",n.arg=e),!!o}for(var a=this.tryEntries.length-1;a>=0;--a){var i=this.tryEntries[a],c=i.completion;if("root"===i.tryLoc)return o("end");if(i.tryLoc<=this.prev){var s=r.call(i,"catchLoc"),l=r.call(i,"finallyLoc");if(s&&l){if(this.prev<i.catchLoc)return o(i.catchLoc,!0);if(this.prev<i.finallyLoc)return o(i.finallyLoc)}else if(s){if(this.prev<i.catchLoc)return o(i.catchLoc,!0)}else{if(!l)throw new Error("try statement without catch or finally");if(this.prev<i.finallyLoc)return o(i.finallyLoc)}}}},abrupt:function(e,t){for(var n=this.tryEntries.length-1;n>=0;--n){var o=this.tryEntries[n];if(o.tryLoc<=this.prev&&r.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var a=o;break}}a&&("break"===e||"continue"===e)&&a.tryLoc<=t&&t<=a.finallyLoc&&(a=null);var i=a?a.completion:{};return i.type=e,i.arg=t,a?(this.method="next",this.next=a.finallyLoc,g):this.complete(i)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),g},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var n=this.tryEntries[t];if(n.finallyLoc===e)return this.complete(n.completion,n.afterLoc),_(n),g}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var n=this.tryEntries[t];if(n.tryLoc===e){var r=n.completion;if("throw"===r.type){var o=r.arg;_(n)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(t,n,r){return this.delegate={iterator:F(t),resultName:n,nextLoc:r},"next"===this.method&&(this.arg=e),g}},t}function p(e,t,n,r,o,a,i){try{var c=e[a](i),s=c.value}catch(e){return void n(e)}c.done?t(s):Promise.resolve(s).then(r,o)}function d(e){return function(){var t=this,n=arguments;return new Promise((function(r,o){var a=e.apply(t,n);function i(e){p(a,r,o,i,c,"next",e)}function c(e){p(a,r,o,i,c,"throw",e)}i(void 0)}))}}var h=function(e){return(0,r.pushScopeId)("data-v-6a668dd6"),e=e(),(0,r.popScopeId)(),e},m={id:"topDiv",class:"h-[calc(100vh-16rem)] text-center bg-gray-800 text-white p-10"},y={class:"flex justify-center w-full"},v={class:"flex flex-col justify-center w-fit bg-gray-900 rounded-md py-10 mb-24"},g=h((function(){return(0,r.createElementVNode)("h1",null,"Payment",-1)})),b=h((function(){return(0,r.createElementVNode)("p",null,[(0,r.createTextVNode)(" Enable more payment method types "),(0,r.createElementVNode)("a",{href:"https://dashboard.stripe.com/settings/payment_methods",target:"_blank"},"in your dashboard"),(0,r.createTextVNode)(". ")],-1)})),w=h((function(){return(0,r.createElementVNode)("div",{id:"link-authentication-element"},null,-1)})),A=h((function(){return(0,r.createElementVNode)("div",{id:"payment-element"},null,-1)})),x=["disabled"];const E={__name:"Subscribe2",props:{intent:Object,card:(0,r.ref)(null)},setup:function(e){(0,i.U)("subscribe");var t,n,u=(0,c.useAppSettingStore)(),p=(0,r.ref)(!1),h=(0,r.ref)([]);(0,r.onMounted)(d(f().mark((function e(){var r,o,i,c;return f().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,fetch("/api/config").then((function(e){return e.json()}));case 2:return r=e.sent,r.publishableKey,e.next=6,(0,a.J)("pk_test_51KJwK5Kahp38LUVYOjg7h425exCr6UZmMm1M24d31ZaS0HTsgPWIZE9Hd2F0KnREVHuPm2VHesX3J5SQfFFg7fTC00DMNpq1Lj");case 6:return t=e.sent,e.next=9,fetch("/api/create-payment-intent").then((function(e){return e.json()}));case 9:o=e.sent,i=o.clientSecret,(c=o.error)&&h.value.push(c.message),h.value.push("Client secret returned."),n=t.elements({clientSecret:i}),n.create("payment").mount("#payment-element"),n.create("linkAuthentication").mount("#link-authentication-element"),p.value=!1;case 20:case"end":return e.stop()}}),e)}))));var E=function(){var e=d(f().mark((function e(){var r,o;return f().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(!p.value){e.next=2;break}return e.abrupt("return");case 2:return p.value=!0,e.next=5,t.confirmPayment({elements:n,confirmParams:{return_url:"".concat(window.location.origin,"/return")}});case 5:r=e.sent,"card_error"===(o=r.error).type||"validation_error"===o.type?h.value.push(o.message):h.value.push("An unexpected error occured."),p.value=!1;case 9:case"end":return e.stop()}}),e)})));return function(){return e.apply(this,arguments)}}();(0,o.cI)({name:""});return function(e,t){var n=(0,r.resolveComponent)("Head");return(0,r.openBlock)(),(0,r.createElementBlock)(r.Fragment,null,[(0,r.createVNode)(n,{title:"TEST SUBSCRIBE"}),(0,r.createElementVNode)("div",m,[(0,r.unref)(u).showFlashMessage?((0,r.openBlock)(),(0,r.createBlock)((0,r.unref)(s.Z),{key:0,flash:e.$page.props.flash},null,8,["flash"])):(0,r.createCommentVNode)("",!0),(0,r.createElementVNode)("div",y,[(0,r.createElementVNode)("div",v,[(0,r.createElementVNode)("main",null,[g,b,(0,r.createElementVNode)("form",{id:"payment-form",onSubmit:(0,r.withModifiers)(E,["prevent"])},[w,A,(0,r.createElementVNode)("button",{id:"submit",disabled:p.value}," Pay now ",8,x),(0,r.createVNode)((0,r.unref)(l.default),{messages:h.value},null,8,["messages"])],32)])])])])],64)}}};var k=n(93379),C=n.n(k),B=n(4908),S={insert:"head",singleton:!1};C()(B.Z,S);B.Z.locals;const L=(0,n(83744).Z)(E,[["__scopeId","data-v-6a668dd6"]])}}]);
//# sourceMappingURL=9413.js.map