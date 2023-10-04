/*! For license information please see 5686.js.LICENSE.txt */
"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[5686],{92505:(e,t,r)=>{r.d(t,{Z:()=>s});var a=r(70821),o={key:0,class:"flex flex-wrap justify-center my-4 space-x-2 md:space-x-4 space-y-2"},n=(0,a.createElementVNode)("div",null,null,-1);const s={__name:"Pagination",props:["data","id"],setup:function(e){var t=e.data,r=(e.id,(0,a.ref)(1)),s=(0,a.computed)((function(){var e=5*Math.max(r.value-1,0),a=t.links[e],o=t.links[e+1],n=function(e,t,r){var a=e.findIndex((function(e){return e.active}));if(a===t+1&&e[t+1].active)return e[t+2];if(a===e.length-2&&e[e.length-2].active)return e[e.length-3];return e[a]}(t.links,e);return[a,o,n,t.links[t.links.length-2],t.links[t.links.length-1]].filter(Boolean)}));return function(t,r){return e.data.last_page>1?((0,a.openBlock)(),(0,a.createElementBlock)("div",o,[n,((0,a.openBlock)(!0),(0,a.createElementBlock)(a.Fragment,null,(0,a.renderList)(s.value,(function(t,o){return(0,a.openBlock)(),(0,a.createBlock)((0,a.resolveDynamicComponent)(t.url?"Link":"span"),{id:e.id,key:o,href:t.url,innerHTML:t.label,class:(0,a.normalizeClass)(["px-2 md:px-4 py-3 text-sm leading-4 h-fit",{"text-white bg-orange-400 hover:bg-orange-400 dark:bg-orange-400 dark:hover:bg-orange-400":t.active,"rounded mt-2 mr-0.5 bg-gray-100 light:bg-gray-100 light:hover:bg-gray-100 light:text-gray-300 dark:bg-gray-900 dark:hover:bg-gray-900 dark:text-gray-700":!t.url,"rounded bg-gray-200 light:bg-gray-200 light:hover:bg-gray-300 dark:bg-gray-800 dark:hover:bg-gray-600 light:text-gray-800 dark:text-gray-50 focus:text-indigo-500 hover:shadow":t.url}]),onScroll:r[0]||(r[0]=(0,a.withModifiers)((function(){}),["prevent"]))},null,40,["id","href","innerHTML","class"])})),128))])):(0,a.createCommentVNode)("",!0)}}}},85686:(e,t,r)=>{r.r(t),r.d(t,{default:()=>Ge});var a=r(70821),o=r(40191),n=r(90771),s=r(92505),l=r(664),i=r(9680);function c(e){return c="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},c(e)}function d(){d=function(){return t};var e,t={},r=Object.prototype,a=r.hasOwnProperty,o=Object.defineProperty||function(e,t,r){e[t]=r.value},n="function"==typeof Symbol?Symbol:{},s=n.iterator||"@@iterator",l=n.asyncIterator||"@@asyncIterator",i=n.toStringTag||"@@toStringTag";function u(e,t,r){return Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{u({},"")}catch(e){u=function(e,t,r){return e[t]=r}}function m(e,t,r,a){var n=t&&t.prototype instanceof y?t:y,s=Object.create(n.prototype),l=new D(a||[]);return o(s,"_invoke",{value:B(e,r,l)}),s}function b(e,t,r){try{return{type:"normal",arg:e.call(t,r)}}catch(e){return{type:"throw",arg:e}}}t.wrap=m;var h="suspendedStart",v="suspendedYield",p="executing",g="completed",f={};function y(){}function x(){}function w(){}var k={};u(k,s,(function(){return this}));var N=Object.getPrototypeOf,E=N&&N(N(j([])));E&&E!==r&&a.call(E,s)&&(k=E);var V=w.prototype=y.prototype=Object.create(k);function C(e){["next","throw","return"].forEach((function(t){u(e,t,(function(e){return this._invoke(t,e)}))}))}function S(e,t){function r(o,n,s,l){var i=b(e[o],e,n);if("throw"!==i.type){var d=i.arg,u=d.value;return u&&"object"==c(u)&&a.call(u,"__await")?t.resolve(u.__await).then((function(e){r("next",e,s,l)}),(function(e){r("throw",e,s,l)})):t.resolve(u).then((function(e){d.value=e,s(d)}),(function(e){return r("throw",e,s,l)}))}l(i.arg)}var n;o(this,"_invoke",{value:function(e,a){function o(){return new t((function(t,o){r(e,a,t,o)}))}return n=n?n.then(o,o):o()}})}function B(t,r,a){var o=h;return function(n,s){if(o===p)throw new Error("Generator is already running");if(o===g){if("throw"===n)throw s;return{value:e,done:!0}}for(a.method=n,a.arg=s;;){var l=a.delegate;if(l){var i=T(l,a);if(i){if(i===f)continue;return i}}if("next"===a.method)a.sent=a._sent=a.arg;else if("throw"===a.method){if(o===h)throw o=g,a.arg;a.dispatchException(a.arg)}else"return"===a.method&&a.abrupt("return",a.arg);o=p;var c=b(t,r,a);if("normal"===c.type){if(o=a.done?g:v,c.arg===f)continue;return{value:c.arg,done:a.done}}"throw"===c.type&&(o=g,a.method="throw",a.arg=c.arg)}}}function T(t,r){var a=r.method,o=t.iterator[a];if(o===e)return r.delegate=null,"throw"===a&&t.iterator.return&&(r.method="return",r.arg=e,T(t,r),"throw"===r.method)||"return"!==a&&(r.method="throw",r.arg=new TypeError("The iterator does not provide a '"+a+"' method")),f;var n=b(o,t.iterator,r.arg);if("throw"===n.type)return r.method="throw",r.arg=n.arg,r.delegate=null,f;var s=n.arg;return s?s.done?(r[t.resultName]=s.value,r.next=t.nextLoc,"return"!==r.method&&(r.method="next",r.arg=e),r.delegate=null,f):s:(r.method="throw",r.arg=new TypeError("iterator result is not an object"),r.delegate=null,f)}function L(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function _(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function D(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(L,this),this.reset(!0)}function j(t){if(t||""===t){var r=t[s];if(r)return r.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var o=-1,n=function r(){for(;++o<t.length;)if(a.call(t,o))return r.value=t[o],r.done=!1,r;return r.value=e,r.done=!0,r};return n.next=n}}throw new TypeError(c(t)+" is not iterable")}return x.prototype=w,o(V,"constructor",{value:w,configurable:!0}),o(w,"constructor",{value:x,configurable:!0}),x.displayName=u(w,i,"GeneratorFunction"),t.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===x||"GeneratorFunction"===(t.displayName||t.name))},t.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,w):(e.__proto__=w,u(e,i,"GeneratorFunction")),e.prototype=Object.create(V),e},t.awrap=function(e){return{__await:e}},C(S.prototype),u(S.prototype,l,(function(){return this})),t.AsyncIterator=S,t.async=function(e,r,a,o,n){void 0===n&&(n=Promise);var s=new S(m(e,r,a,o),n);return t.isGeneratorFunction(r)?s:s.next().then((function(e){return e.done?e.value:s.next()}))},C(V),u(V,i,"Generator"),u(V,s,(function(){return this})),u(V,"toString",(function(){return"[object Generator]"})),t.keys=function(e){var t=Object(e),r=[];for(var a in t)r.push(a);return r.reverse(),function e(){for(;r.length;){var a=r.pop();if(a in t)return e.value=a,e.done=!1,e}return e.done=!0,e}},t.values=j,D.prototype={constructor:D,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=e,this.done=!1,this.delegate=null,this.method="next",this.arg=e,this.tryEntries.forEach(_),!t)for(var r in this)"t"===r.charAt(0)&&a.call(this,r)&&!isNaN(+r.slice(1))&&(this[r]=e)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var r=this;function o(a,o){return l.type="throw",l.arg=t,r.next=a,o&&(r.method="next",r.arg=e),!!o}for(var n=this.tryEntries.length-1;n>=0;--n){var s=this.tryEntries[n],l=s.completion;if("root"===s.tryLoc)return o("end");if(s.tryLoc<=this.prev){var i=a.call(s,"catchLoc"),c=a.call(s,"finallyLoc");if(i&&c){if(this.prev<s.catchLoc)return o(s.catchLoc,!0);if(this.prev<s.finallyLoc)return o(s.finallyLoc)}else if(i){if(this.prev<s.catchLoc)return o(s.catchLoc,!0)}else{if(!c)throw new Error("try statement without catch or finally");if(this.prev<s.finallyLoc)return o(s.finallyLoc)}}}},abrupt:function(e,t){for(var r=this.tryEntries.length-1;r>=0;--r){var o=this.tryEntries[r];if(o.tryLoc<=this.prev&&a.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var n=o;break}}n&&("break"===e||"continue"===e)&&n.tryLoc<=t&&t<=n.finallyLoc&&(n=null);var s=n?n.completion:{};return s.type=e,s.arg=t,n?(this.method="next",this.next=n.finallyLoc,f):this.complete(s)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),f},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.finallyLoc===e)return this.complete(r.completion,r.afterLoc),_(r),f}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.tryLoc===e){var a=r.completion;if("throw"===a.type){var o=a.arg;_(r)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(t,r,a){return this.delegate={iterator:j(t),resultName:r,nextLoc:a},"next"===this.method&&(this.arg=e),f}},t}function u(e,t,r,a,o,n,s){try{var l=e[n](s),i=l.value}catch(e){return void r(e)}l.done?t(i):Promise.resolve(i).then(a,o)}function m(e){return function(){var t=this,r=arguments;return new Promise((function(a,o){var n=e.apply(t,r);function s(e){u(n,a,o,s,l,"next",e)}function l(e){u(n,a,o,s,l,"throw",e)}s(void 0)}))}}var b={class:"place-self-center flex flex-col gap-y-3 bg-blue-500 w-full"},h={id:"topDiv",class:"rounded light:bg-white light:text-black dark:text-white dark:bg-gray-900 p-5 mb-10"},v={class:"flex justify-between mb-6 pt-4"},p=(0,a.createElementVNode)("h1",{class:"text-3xl font-semibold"},"Dashboard for Creators",-1),g={class:"w-full flex flex-wrap-reverse mx-auto gap-2 mb-6"},f={key:0,class:"bg-blue-600 hover:bg-blue-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"},y={key:0,class:"bg-yellow-600 hover:bg-yellow-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"},x=(0,a.createElementVNode)("button",{class:"bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400 cursor-not-allowed",disabled:""},"Share News",-1),w=(0,a.createElementVNode)("button",{class:"bg-orange-600 hover:bg-orange-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"},"Invite Creator",-1),k=(0,a.createElementVNode)("button",{class:"bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"},"Upload Video",-1),N=(0,a.createElementVNode)("button",{class:"bg-red-600 hover:bg-red-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"},"Go Live",-1),E={class:"grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8 mx-2 m-auto text-black"},V=(0,a.createStaticVNode)('<div class="p-5 bg-gray-200 dark:bg-gray-800 rounded"><div class="mb-3 grid grid-cols-1"><div class="mb-1 font-semibold text-xl justify-self-start dark:text-gray-50">Open Assignments</div></div><div class="mb-3 bg-orange-300 py-1 px-2 text-xs font-semibold text-red-800"> In development. Not currently working. </div><div class="ml-3 text-center"><button disabled class="text-blue-800 hover:text-blue-400 dark:text-blue-100 dark:hover:text-blue-400 disabled:text-gray-500"> Assignments list goes here </button></div><div class="text-center text-gray-500">(coming soon)</div></div>',1),C={class:"p-5 bg-gray-200 dark:bg-gray-800 rounded relative"},S={class:"mb-3 flex justify-between"},B=(0,a.createElementVNode)("div",{class:"mb-1 font-semibold text-xl dark:text-gray-50"},"My Teams ",-1),T={key:0,class:""},L=(0,a.createElementVNode)("button",{class:"bg-green-600 hover:bg-green-500 text-white px-4 py-2 text-xs rounded disabled:bg-gray-400"},"New Team",-1),_={key:0,class:"italic dark:text-gray-50 light:text-black"},D=["onClick"],j={class:"px-2 py-1"},A={class:"w-full text-center mb-12"},P=(0,a.createElementVNode)("p",{class:"text-xl font-semibold mb-2"},"🎉 Start a new team",-1),F=(0,a.createElementVNode)("p",{class:""},"Teams manage shows, ",-1),O=(0,a.createElementVNode)("p",{class:""},"even if you're a solo creator",-1),M={class:"mt-24"},I={class:"p-5 bg-gray-200 dark:bg-gray-800 rounded relative"},U={class:"mb-3 flex justify-between"},G=(0,a.createElementVNode)("div",{class:"mb-1 font-semibold text-xl dark:text-gray-50"},"My Shows",-1),z={key:0,class:""},Y={key:0},q=(0,a.createElementVNode)("button",{class:"bg-green-600 hover:bg-green-500 text-white px-4 py-2 text-xs rounded disabled:bg-gray-400"},"Create Show",-1),R={key:1},Z=(0,a.createElementVNode)("h3",{class:"font-bold text-lg mb-3"},"You don't have any teams!",-1),H=(0,a.createElementVNode)("p",{class:"py-4"},"Press ESC key or click outside to close",-1),$=(0,a.createElementVNode)("form",{method:"dialog",class:"modal-backdrop"},[(0,a.createElementVNode)("button",null,"close")],-1),J={key:0,class:"italic dark:text-gray-50 light:text-black"},W={class:"px-2 py-1"},K={class:"w-full text-center mb-12"},Q=(0,a.createElementVNode)("p",{class:"text-xl font-semibold mb-2"},"🍿 These are your shows ",-1),X=(0,a.createElementVNode)("p",{class:""},"Join or create a team to start a show.",-1),ee={class:"mt-24"},te=(0,a.createStaticVNode)('<div class="w-full light:bg-gray-300 dark:bg-gray-900 rounded p-3 my-8 mx-2 border-b border-2"><div class="stat place-items-center mb-4"><div class="stat-title light:text-black dark:text-white mb-2">Yesterday&#39;s Top Show</div><div class="stat-value text-accent md:text-lg text-sm">Down The Rabbit Hole</div><div class="stat-desc">︎Episode 2</div></div></div>',1),re={class:"w-full bg-gray-300 dark:bg-gray-900 rounded pb-8 p-3 mb-16 mx-2 border-b border-2"},ae=(0,a.createElementVNode)("div",{class:"font-semibold text-xl pb-2"},"Stats",-1),oe={class:"w-full mx-auto stats shadow stats-vertical lg:stats-horizontal"},ne=(0,a.createElementVNode)("div",{class:"stat place-items-center"},[(0,a.createElementVNode)("div",{class:"stat-title"},"Avg. Daily View Time"),(0,a.createElementVNode)("div",{class:"stat-value"},"~ minutes"),(0,a.createElementVNode)("div",{class:"stat-desc"},"From January 1st to February 1st")],-1),se={class:"stat place-items-center"},le=(0,a.createElementVNode)("div",{class:"stat-title"},"Premium subscribers",-1),ie={class:"stat-value text-secondary"},ce=(0,a.createElementVNode)("div",{class:"stat-desc"},"↗︎ ~ (~%)",-1),de=(0,a.createElementVNode)("div",{class:"stat place-items-center"},[(0,a.createElementVNode)("div",{class:"stat-title"},"New Creators"),(0,a.createElementVNode)("div",{class:"stat-value"},"~"),(0,a.createElementVNode)("div",{class:"stat-desc"},"↘︎ ~ (~%)")],-1),ue={class:"w-full mt-4 mx-auto stats shadow stats-vertical lg:stats-horizontal"},me={class:"stat place-items-center"},be=(0,a.createElementVNode)("div",{class:"stat-title"},"Total Shows",-1),he={class:"stat-value"},ve=(0,a.createElementVNode)("div",{class:"stat-desc"},"↗︎ ~ (~%)",-1),pe={class:"stat place-items-center"},ge=(0,a.createElementVNode)("div",{class:"stat-title"},"Total Users",-1),fe={class:"stat-value"},ye=(0,a.createElementVNode)("div",{class:"stat-desc"},"↗︎ ~ (~%)",-1),xe={class:"stat place-items-center"},we=(0,a.createElementVNode)("div",{class:"stat-title"},"Total Creators",-1),ke={class:"stat-value"},Ne=(0,a.createElementVNode)("div",{class:"stat-desc"},"︎↗︎ ~ (~%)",-1),Ee={class:"w-full mt-4 mx-auto stats shadow stats-vertical lg:stats-horizontal"},Ve={class:"stat place-items-center"},Ce=(0,a.createElementVNode)("div",{class:"stat-title"},"Total Video Storage Used",-1),Se={class:"stat-value text-secondary"},Be={class:"stat-desc"},Te=(0,a.createStaticVNode)('<div class="stat place-items-center"><div class="stat-title">Daily Peak Bandwidth</div><div class="stat-value text-secondary">~ Gbps</div><div class="stat-desc">︎↘︎ ~ (~%)</div></div><div class="stat place-items-center"><div class="stat-title">Average Bandwidth per User</div><div class="stat-value text-secondary">~ Mbps</div><div class="stat-desc">︎↘︎ ~ (~%)</div></div>',2),Le=(0,a.createElementVNode)("div",{class:"mt-6 h-0.5 bg-gray-800"},null,-1),_e={class:"grid grid-cols-1 mt-6 gap-2"},De=(0,a.createStaticVNode)('<div class="font-semibold text-2xl text-gray-800 dark:text-white px-2"> Account Summary </div><div class="mb-3 bg-orange-300 py-1 px-2 text-xs font-semibold text-red-800"> In development. Not currently working. </div><div class="w-fit mx-auto stats stats-vertical sm:stats-horizontal bg-primary text-primary-content"><div class="stat my-2"><div class="stat-title text-white">Total balance</div><div class="stat-value">$89,410</div><div class="stat-actions"><button class="btn btn-sm btn-success">Add funds</button></div></div><div class="stat my-2"><div class="stat-title text-white">Available balance</div><div class="stat-value">$89,400</div><div class="stat-actions"><button class="btn btn-sm mr-2">Withdrawal</button><button class="btn btn-sm">deposit</button></div></div></div>',3),je={class:"border-2"},Ae=(0,a.createStaticVNode)('<div class="grid justify-items-stretch grid-cols-3"><div class="bg-gray-800 text-white text-sm p-2 col-span-3">Membership: 000000</div></div><table class="w-full"><thead class=""><td class="bg-blue-400 font-semibold text-sm text-black px-2 mb-3">My Account Name</td><td class="bg-blue-400 px-2 mb-3 text-right font-semibold text-sm text-black">Balance</td></thead><tr class="border-b border-1 border-gray-100"><td class="px-2 col-span-2 py-2">Chequing</td><td class="px-2 text-right">89,400.00</td></tr><tr class="border-b border-1 border-gray-100"><td class="px-2 col-span-2 py-2">Equity Shares</td><td class="px-2 text-right">10.00</td></tr></table>',2),Pe={class:"w-full mt-6"},Fe=(0,a.createElementVNode)("thead",{class:""},[(0,a.createElementVNode)("td",{class:"bg-blue-400 font-semibold text-sm text-black px-2"},"Team Shares"),(0,a.createElementVNode)("td",{class:"bg-blue-400 px-2 text-right font-semibold text-sm text-black"},"Balance")],-1),Oe={class:"px-2 py-2"},Me=(0,a.createElementVNode)("td",{class:"px-2 text-right"},"0.00",-1),Ie=(0,a.createStaticVNode)('<table class="w-full mt-6"><thead class=""><td class="bg-blue-400 font-semibold text-sm text-black px-2 mb-3">Community Account Name</td><td class="bg-blue-400 px-2 mb-3 text-right font-semibold text-sm text-black">Balance</td></thead><tr class="border-b border-1 border-gray-100"><td class="px-2 col-span-2 py-2">Public Good Fund</td><td class="px-2 text-right">0.00</td></tr><tr class="border-b border-1 border-gray-100"><td class="px-2 col-span-2 py-2">Production Fund for Members</td><td class="px-2 text-right">0.00</td></tr><tr class="border-b border-1 border-gray-100"><td class="px-2 col-span-2 py-2">News Fund</td><td class="px-2 text-right">0.00</td></tr></table>',1),Ue=(0,a.createStaticVNode)('<section class="mt-16 space-y-4 w-fit"><div class="text-sm uppercase mb-4 border-b border-blue-500"> External Links </div><a href="https://www.cbsc.ca/" target="_blank"><div class="hover:bg-blue-500 p-2"> Canadian Broadcast Standards Council</div></a><a href="https://rtdnacanada.com/" target="_blank"><div class="hover:bg-blue-500 p-2"> RTNDA</div></a><a href="https://adstandards.ca/" target="_blank"><div class="hover:bg-blue-500 p-2"> Ad Standards</div></a><a href="https://www.cybertip.ca/en/" target="_blank"><div class="hover:bg-blue-500 p-2"> Cybertip</div></a></section>',1);const Ge={__name:"Dashboard",props:{id:null,isAdmin:null,isCreator:null,isNewsPerson:null,isSubscriber:null,hasAccount:null,isVip:null,shows:Object,teams:Object,myTotalStorageUsed:(0,a.ref)(String),notTvTotalStorageUsed:(0,a.ref)(String),showCount:Number,userCount:Number,creatorCount:Number,subscriptionCount:Number,can:Object},setup:function(e){var t=e,r=(0,o.q)(),c=(0,n.L)(),u=(0,a.inject)("getUserData",null);r.loggedIn=!0,c.currentPage="dashboard",c.showFlashMessage=!0,(0,a.onBeforeMount)((function(){})),(0,a.onMounted)((function(){u||(Je(),function(){He.apply(this,arguments)}()),r.makeVideoTopRight(),c.isMobile&&(r.ottClass="ottClose",r.ott=0),document.getElementById("topDiv").scrollIntoView(),i.Inertia.reload()}));var Ge=function(){i.Inertia.visit("teams/create")},ze=function(e){var t=parseFloat(e);return isNaN(t)?0:t},Ye=ze(t.myTotalStorageUsed),qe=ze(t.notTvTotalStorageUsed),Re=(0,a.computed)((function(){return(Ye/qe*100).toFixed(2)}));(0,a.computed)((function(){return Math.round(Re)}));function Ze(){document.getElementById("dashboardNoTeams").showModal()}function He(){return(He=m(d().mark((function e(){return d().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return c.id=t.id,c.isAdmin=t.isAdmin,c.isCreator=t.isCreator,c.isNewsPerson=t.isNewsPerson,c.isVip=t.isVip,c.isSubscriber=t.isSubscriber,c.hasAccount=t.hasAccount,c.getUserDataCompleted=!0,c.timezone=$e,console.log("get user data on Dashboard"),c.userSubscribedToNotifications||c.subscribeToUserNotifications(t.id),e.next=13,We();case 13:case"end":return e.stop()}}),e)})))).apply(this,arguments)}var $e=(0,a.ref)(""),Je=function(){$e.value=Intl.DateTimeFormat().resolvedOptions().timeZone},We=function(){var e=m(d().mark((function e(){var t;return d().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.prev=0,e.next=3,axios.post("/users/update-timezone",{timezone:$e.value});case 3:t=e.sent,console.log(t.data.message),e.next=11;break;case 7:e.prev=7,e.t0=e.catch(0),console.error(e.t0),e.t0.response&&console.error(e.t0.response.data);case 11:case"end":return e.stop()}}),e,null,[[0,7]])})));return function(){return e.apply(this,arguments)}}();return function(o,n){var d=(0,a.resolveComponent)("Head"),u=(0,a.resolveComponent)("Link"),m=(0,a.resolveComponent)("font-awesome-icon"),ze=(0,a.resolveComponent)("Popper");return(0,a.openBlock)(),(0,a.createElementBlock)(a.Fragment,null,[(0,a.createVNode)(d,{title:"Dashboard"}),(0,a.createElementVNode)("div",b,[(0,a.createElementVNode)("div",h,[(0,a.unref)(c).showFlashMessage?((0,a.openBlock)(),(0,a.createBlock)((0,a.unref)(l.Z),{key:0,flash:o.$page.props.flash},null,8,["flash"])):(0,a.createCommentVNode)("",!0),(0,a.createElementVNode)("div",v,[p,(0,a.createElementVNode)("p",null,"Your timezone: "+(0,a.toDisplayString)((0,a.unref)(c).timezone),1)]),(0,a.createElementVNode)("div",g,[(0,a.createVNode)(u,{href:"/admin/settings"},{default:(0,a.withCtx)((function(){return[(0,a.unref)(t).can.viewAdmin?((0,a.openBlock)(),(0,a.createElementBlock)("button",f,"Admin Settings")):(0,a.createCommentVNode)("",!0)]})),_:1}),(0,a.createVNode)(u,{href:"/newsroom"},{default:(0,a.withCtx)((function(){return[(0,a.unref)(t).can.viewNewsroom?((0,a.openBlock)(),(0,a.createElementBlock)("button",y,"Newsroom")):(0,a.createCommentVNode)("",!0)]})),_:1}),(0,a.createVNode)(u,{href:"/news/upload"},{default:(0,a.withCtx)((function(){return[x]})),_:1}),(0,a.createVNode)(u,{href:"/invite"},{default:(0,a.withCtx)((function(){return[w]})),_:1}),(0,a.createVNode)(u,{href:"/videoupload"},{default:(0,a.withCtx)((function(){return[k]})),_:1}),(0,a.createVNode)(u,{href:"/golive"},{default:(0,a.withCtx)((function(){return[N]})),_:1})]),(0,a.createElementVNode)("section",E,[V,(0,a.createElementVNode)("div",C,[(0,a.createElementVNode)("div",S,[B,e.can.createTeam?((0,a.openBlock)(),(0,a.createElementBlock)("div",T,[(0,a.createVNode)(u,{href:"/teams/create"},{default:(0,a.withCtx)((function(){return[L]})),_:1})])):(0,a.createCommentVNode)("",!0)]),0==(0,a.unref)(t).teams.data?((0,a.openBlock)(),(0,a.createElementBlock)("div",_," You have no teams. ")):(0,a.createCommentVNode)("",!0),((0,a.openBlock)(!0),(0,a.createElementBlock)(a.Fragment,null,(0,a.renderList)(e.teams.data,(function(e){return(0,a.openBlock)(),(0,a.createElementBlock)("div",{key:e.id,class:"border-b light:bg-white hover:bg-blue-300 dark:bg-gray-600 dark:border-gray-700 dark:hover:bg-blue-800 inset-x-0 bottom-0"},[(0,a.createElementVNode)("button",{onClick:function(t){return function(e){r.makeVideoTopRight(),i.Inertia.visit("/teams/".concat(e,"/manage"))}(e.slug)},class:"text-blue-800 hover:text-blue-900 dark:text-blue-100 dark:hover:text-white"},[(0,a.createElementVNode)("p",j,(0,a.toDisplayString)(e.name),1)],8,D)])})),128)),(0,a.createElementVNode)("div",A,[(0,a.createVNode)(ze,{hover:"",openDelay:"50",closeDelay:"50","offset-skid":"0","offset-distance":"0",placement:"top",arrow:""},{content:(0,a.withCtx)((function(){return[P,F,O]})),default:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("button",null,[(0,a.createVNode)(m,{icon:"fa-solid fa-question",class:"text-3xl text-pink-600 dark:text-pink-300 dark:hover:text-blue-400 hover:text-blue-400 mt-6 py-2"})])]})),_:1})]),(0,a.createElementVNode)("div",M,[(0,a.createElementVNode)("div",null,[(0,a.createVNode)((0,a.unref)(s.Z),{data:(0,a.unref)(t).teams,class:"mt-6 absolute inset-x-0 bottom-0 py-2 px-2"},null,8,["data"])])])]),(0,a.createElementVNode)("div",I,[(0,a.createElementVNode)("div",U,[G,e.can.createShow?((0,a.openBlock)(),(0,a.createElementBlock)("div",z,[(0,a.unref)(t).teams.data.length>0?((0,a.openBlock)(),(0,a.createElementBlock)("div",Y,[(0,a.createVNode)(u,{href:"/shows/create"},{default:(0,a.withCtx)((function(){return[q]})),_:1})])):((0,a.openBlock)(),(0,a.createElementBlock)("div",R,[(0,a.createElementVNode)("button",{class:"bg-green-600 hover:bg-green-500 text-white px-4 py-2 text-xs rounded disabled:bg-gray-400",onClick:Ze},"Create Show"),(0,a.createElementVNode)("dialog",{id:"dashboardNoTeams",class:"modal"},[(0,a.createElementVNode)("div",{class:"modal-box"},[Z,(0,a.createElementVNode)("button",{class:"btn btn-primary",onClick:Ge},"Create a Team"),H]),$])]))])):(0,a.createCommentVNode)("",!0)]),0==(0,a.unref)(t).shows.data?((0,a.openBlock)(),(0,a.createElementBlock)("div",J," You have no shows. ")):(0,a.createCommentVNode)("",!0),((0,a.openBlock)(!0),(0,a.createElementBlock)(a.Fragment,null,(0,a.renderList)(e.shows.data,(function(e){return(0,a.openBlock)(),(0,a.createElementBlock)("div",{key:e.id,class:"border-b light:bg-white hover:bg-blue-300 dark:bg-gray-600 dark:border-gray-700 dark:hover:bg-blue-800 inset-x-0 bottom-0"},[(0,a.createVNode)(u,{onClick:n[0]||(n[0]=function(e){return o.videoPlayer.makeVideoTopRight()}),href:"/shows/".concat(e.slug,"/manage"),class:"text-blue-800 hover:text-blue-900 dark:text-blue-100 dark:hover:text-white"},{default:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("p",W,(0,a.toDisplayString)(e.name),1)]})),_:2},1032,["href"])])})),128)),(0,a.createElementVNode)("div",K,[(0,a.createVNode)(ze,{hover:"",openDelay:"50",closeDelay:"50","offset-skid":"0","offset-distance":"0",placement:"top",arrow:""},{content:(0,a.withCtx)((function(){return[Q,X]})),default:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("button",null,[(0,a.createVNode)(m,{icon:"fa-solid fa-question",class:"text-3xl text-pink-600 dark:text-pink-300 dark:hover:text-blue-400 hover:text-blue-400 mt-6 py-2"})])]})),_:1})]),(0,a.createElementVNode)("div",ee,[(0,a.createVNode)((0,a.unref)(s.Z),{data:e.shows,class:"mt-6 absolute inset-x-0 bottom-0 py-2 px-2"},null,8,["data"])])])]),te,(0,a.createElementVNode)("div",re,[ae,(0,a.createElementVNode)("div",oe,[ne,(0,a.createElementVNode)("div",se,[le,(0,a.createElementVNode)("div",ie,(0,a.toDisplayString)(e.subscriptionCount),1),ce]),de]),(0,a.createElementVNode)("div",ue,[(0,a.createElementVNode)("div",me,[be,(0,a.createElementVNode)("div",he,(0,a.toDisplayString)(e.showCount),1),ve]),(0,a.createElementVNode)("div",pe,[ge,(0,a.createElementVNode)("div",fe,(0,a.toDisplayString)(e.userCount),1),ye]),(0,a.createElementVNode)("div",xe,[we,(0,a.createElementVNode)("div",ke,(0,a.toDisplayString)(e.creatorCount),1),Ne])]),(0,a.createElementVNode)("div",Ee,[(0,a.createElementVNode)("div",Ve,[Ce,(0,a.createElementVNode)("div",Se,(0,a.toDisplayString)(e.notTvTotalStorageUsed),1),(0,a.createElementVNode)("div",Be,"My total: "+(0,a.toDisplayString)(e.myTotalStorageUsed)+" ("+(0,a.toDisplayString)(Re.value)+"%)",1)]),Te])]),Le,(0,a.createElementVNode)("section",_e,[De,(0,a.createElementVNode)("div",je,[Ae,(0,a.createElementVNode)("table",Pe,[Fe,((0,a.openBlock)(!0),(0,a.createElementBlock)(a.Fragment,null,(0,a.renderList)(e.teams.data,(function(e){return(0,a.openBlock)(),(0,a.createElementBlock)("tr",{key:e.id,class:"border-b border-1 border-gray-100"},[(0,a.createElementVNode)("td",Oe,(0,a.toDisplayString)(e.name),1),Me])})),128))]),Ie])]),Ue])])],64)}}}}}]);
//# sourceMappingURL=5686.js.map