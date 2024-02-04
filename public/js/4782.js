/*! For license information please see 4782.js.LICENSE.txt */
"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[4782,2448],{2448:(e,t,r)=>{r.r(t),r.d(t,{useTeamStore:()=>s});var o=r(69876),n=r(9680),a=function(){return{id:0,name:"",description:"",slug:"",totalSpots:"",memberSpots:"",teamCreator:[],teamLeader:[],members:[],managers:[],activeShow:[],activeEpisode:[],creators:[],showModal:Boolean,confirmDialog:!1,confirmManagerDialog:!1,selectedManagerName:"",selectedManagerId:0,addManager:!1,removeManager:!1,deleteMemberName:"",deleteMemberId:0,noteEdit:0,note:"",saveNoteProcessing:Boolean,goLiveDisplay:!1,can:[],openComponent:"teamShows"}},s=(0,o.Q_)("teamStore",{state:a,actions:{reset:function(){Object.assign(this,a())},setActiveTeam:function(e){this.id=e.id,this.name=e.name,this.description=e.description,this.slug=e.slug,this.totalSpots=e.totalSpots,this.memberSpots=e.memberSpots},setActiveShow:function(e){this.activeShow=e},setActiveEpisode:function(e){this.activeShow=e},deleteTeamMemberCancel:function(){this.confirmDialog=!1},confirmTeamManagerCancel:function(){this.confirmManagerDialog=!1},deleteTeamMember:function(){n.Inertia.visit(route("teams.removeTeamMember"),{method:"post",data:{user_id:this.deleteMemberId,team_id:this.id,team_slug:this.slug}})},addTeamManager:function(){n.Inertia.visit(route("teams.addTeamManager"),{method:"post",data:{user_id:this.selectedManagerId,team_id:this.id,team_slug:this.slug}}),this.confirmManagerDialog=!1},removeTeamManager:function(){n.Inertia.visit(route("teams.removeTeamManager"),{method:"post",data:{user_id:this.selectedManagerId,team_id:this.id,team_slug:this.slug}}),this.confirmManagerDialog=!1},toggleGoLiveDisplay:function(){this.goLiveDisplay=!this.goLiveDisplay}},getters:{spotsRemaining:function(){return this.totalSpots-this.memberSpots<1?0:this.totalSpots-this.memberSpots}}})},24831:(e,t,r)=>{r.d(t,{U:()=>i});var o=r(70821),n=r(90771),a=r(58358),s=r(40191),l=r(9680);function i(e){var t=(0,n.useUserStore)(),r=(0,a.useAppSettingStore)(),i=(0,s.useVideoPlayerStore)();r.currentPage=e,r.showFlashMessage=!0,r.pageIsHidden=!1,t.isMobile&&(r.ott=0),i.makeVideoTopRight();(0,o.onBeforeMount)((function(){r.pageReload&&(r.pageReload=!1,window.location.reload(!0))})),(0,o.onMounted)((function(){if(!(""!==window.location.search)){var e=document.getElementById("topDiv");e&&e.scrollIntoView()}r.setPrevUrl(),r.noLayout=!1,l.Inertia.reload()}))}},59369:(e,t,r)=>{r.d(t,{Z:()=>y});var o=r(70821),n=r(9680),a=r(58358);function s(e){return s="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},s(e)}function l(){l=function(){return t};var e,t={},r=Object.prototype,o=r.hasOwnProperty,n=Object.defineProperty||function(e,t,r){e[t]=r.value},a="function"==typeof Symbol?Symbol:{},i=a.iterator||"@@iterator",c=a.asyncIterator||"@@asyncIterator",u=a.toStringTag||"@@toStringTag";function d(e,t,r){return Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{d({},"")}catch(e){d=function(e,t,r){return e[t]=r}}function m(e,t,r,o){var a=t&&t.prototype instanceof v?t:v,s=Object.create(a.prototype),l=new j(o||[]);return n(s,"_invoke",{value:B(e,r,l)}),s}function h(e,t,r){try{return{type:"normal",arg:e.call(t,r)}}catch(e){return{type:"throw",arg:e}}}t.wrap=m;var f="suspendedStart",p="suspendedYield",g="executing",w="completed",y={};function v(){}function b(){}function x(){}var k={};d(k,i,(function(){return this}));var V=Object.getPrototypeOf,E=V&&V(V(P([])));E&&E!==r&&o.call(E,i)&&(k=E);var N=x.prototype=v.prototype=Object.create(k);function _(e){["next","throw","return"].forEach((function(t){d(e,t,(function(e){return this._invoke(t,e)}))}))}function S(e,t){function r(n,a,l,i){var c=h(e[n],e,a);if("throw"!==c.type){var u=c.arg,d=u.value;return d&&"object"==s(d)&&o.call(d,"__await")?t.resolve(d.__await).then((function(e){r("next",e,l,i)}),(function(e){r("throw",e,l,i)})):t.resolve(d).then((function(e){u.value=e,l(u)}),(function(e){return r("throw",e,l,i)}))}i(c.arg)}var a;n(this,"_invoke",{value:function(e,o){function n(){return new t((function(t,n){r(e,o,t,n)}))}return a=a?a.then(n,n):n()}})}function B(t,r,o){var n=f;return function(a,s){if(n===g)throw new Error("Generator is already running");if(n===w){if("throw"===a)throw s;return{value:e,done:!0}}for(o.method=a,o.arg=s;;){var l=o.delegate;if(l){var i=C(l,o);if(i){if(i===y)continue;return i}}if("next"===o.method)o.sent=o._sent=o.arg;else if("throw"===o.method){if(n===f)throw n=w,o.arg;o.dispatchException(o.arg)}else"return"===o.method&&o.abrupt("return",o.arg);n=g;var c=h(t,r,o);if("normal"===c.type){if(n=o.done?w:p,c.arg===y)continue;return{value:c.arg,done:o.done}}"throw"===c.type&&(n=w,o.method="throw",o.arg=c.arg)}}}function C(t,r){var o=r.method,n=t.iterator[o];if(n===e)return r.delegate=null,"throw"===o&&t.iterator.return&&(r.method="return",r.arg=e,C(t,r),"throw"===r.method)||"return"!==o&&(r.method="throw",r.arg=new TypeError("The iterator does not provide a '"+o+"' method")),y;var a=h(n,t.iterator,r.arg);if("throw"===a.type)return r.method="throw",r.arg=a.arg,r.delegate=null,y;var s=a.arg;return s?s.done?(r[t.resultName]=s.value,r.next=t.nextLoc,"return"!==r.method&&(r.method="next",r.arg=e),r.delegate=null,y):s:(r.method="throw",r.arg=new TypeError("iterator result is not an object"),r.delegate=null,y)}function M(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function L(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function j(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(M,this),this.reset(!0)}function P(t){if(t||""===t){var r=t[i];if(r)return r.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var n=-1,a=function r(){for(;++n<t.length;)if(o.call(t,n))return r.value=t[n],r.done=!1,r;return r.value=e,r.done=!0,r};return a.next=a}}throw new TypeError(s(t)+" is not iterable")}return b.prototype=x,n(N,"constructor",{value:x,configurable:!0}),n(x,"constructor",{value:b,configurable:!0}),b.displayName=d(x,u,"GeneratorFunction"),t.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===b||"GeneratorFunction"===(t.displayName||t.name))},t.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,x):(e.__proto__=x,d(e,u,"GeneratorFunction")),e.prototype=Object.create(N),e},t.awrap=function(e){return{__await:e}},_(S.prototype),d(S.prototype,c,(function(){return this})),t.AsyncIterator=S,t.async=function(e,r,o,n,a){void 0===a&&(a=Promise);var s=new S(m(e,r,o,n),a);return t.isGeneratorFunction(r)?s:s.next().then((function(e){return e.done?e.value:s.next()}))},_(N),d(N,u,"Generator"),d(N,i,(function(){return this})),d(N,"toString",(function(){return"[object Generator]"})),t.keys=function(e){var t=Object(e),r=[];for(var o in t)r.push(o);return r.reverse(),function e(){for(;r.length;){var o=r.pop();if(o in t)return e.value=o,e.done=!1,e}return e.done=!0,e}},t.values=P,j.prototype={constructor:j,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=e,this.done=!1,this.delegate=null,this.method="next",this.arg=e,this.tryEntries.forEach(L),!t)for(var r in this)"t"===r.charAt(0)&&o.call(this,r)&&!isNaN(+r.slice(1))&&(this[r]=e)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var r=this;function n(o,n){return l.type="throw",l.arg=t,r.next=o,n&&(r.method="next",r.arg=e),!!n}for(var a=this.tryEntries.length-1;a>=0;--a){var s=this.tryEntries[a],l=s.completion;if("root"===s.tryLoc)return n("end");if(s.tryLoc<=this.prev){var i=o.call(s,"catchLoc"),c=o.call(s,"finallyLoc");if(i&&c){if(this.prev<s.catchLoc)return n(s.catchLoc,!0);if(this.prev<s.finallyLoc)return n(s.finallyLoc)}else if(i){if(this.prev<s.catchLoc)return n(s.catchLoc,!0)}else{if(!c)throw new Error("try statement without catch or finally");if(this.prev<s.finallyLoc)return n(s.finallyLoc)}}}},abrupt:function(e,t){for(var r=this.tryEntries.length-1;r>=0;--r){var n=this.tryEntries[r];if(n.tryLoc<=this.prev&&o.call(n,"finallyLoc")&&this.prev<n.finallyLoc){var a=n;break}}a&&("break"===e||"continue"===e)&&a.tryLoc<=t&&t<=a.finallyLoc&&(a=null);var s=a?a.completion:{};return s.type=e,s.arg=t,a?(this.method="next",this.next=a.finallyLoc,y):this.complete(s)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),y},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.finallyLoc===e)return this.complete(r.completion,r.afterLoc),L(r),y}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.tryLoc===e){var o=r.completion;if("throw"===o.type){var n=o.arg;L(r)}return n}}throw new Error("illegal catch attempt")},delegateYield:function(t,r,o){return this.delegate={iterator:P(t),resultName:r,nextLoc:o},"next"===this.method&&(this.arg=e),y}},t}function i(e,t,r,o,n,a,s){try{var l=e[a](s),i=l.value}catch(e){return void r(e)}l.done?t(i):Promise.resolve(i).then(o,n)}var c={class:"mx-4 my-4"},u={key:0,class:"alert alert-info mt-4"},d=(0,o.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24",class:"stroke-current shrink-0 w-6 h-6"},[(0,o.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"})],-1),m={key:1,class:"alert alert-success mt-4 mx-3"},h=(0,o.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,o.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"})],-1),f={key:2,class:"alert alert-warning mt-4 mx-3"},p=(0,o.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,o.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"})],-1),g={key:3,class:"alert alert-error mt-4 mx-3"},w=(0,o.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,o.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"})],-1);const y={__name:"Messages",props:{flash:Object},setup:function(e){(0,a.useAppSettingStore)().showFlashMessage=!0;var t=e,r=((0,o.computed)((function(){return{"text-green-700 bg-green-100 dark:bg-green-200 dark:text-green-800":t.flash.success,"text-blue-700 bg-blue-100 dark:bg-blue-200 dark:text-blue-800":t.flash.message,"text-orange-700 bg-orange-100 dark:bg-orange-200 dark:text-orange-800":t.flash.warning,"text-red-700 bg-red-100 dark:bg-red-200 dark:text-red-800":t.flash.error}})),function(){var e,t=(e=l().mark((function e(){return l().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,n.Inertia.post(route("flash.clear"));case 2:n.Inertia.reload();case 3:case"end":return e.stop()}}),e)})),function(){var t=this,r=arguments;return new Promise((function(o,n){var a=e.apply(t,r);function s(e){i(a,o,n,s,l,"next",e)}function l(e){i(a,o,n,s,l,"throw",e)}s(void 0)}))});return function(){return t.apply(this,arguments)}}());return function(e,n){return(0,o.openBlock)(),(0,o.createElementBlock)("div",c,[(0,o.unref)(t).flash.message?((0,o.openBlock)(),(0,o.createElementBlock)("div",u,[d,(0,o.createElementVNode)("span",null,(0,o.toDisplayString)((0,o.unref)(t).flash.message),1),(0,o.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,o.createCommentVNode)("",!0),(0,o.unref)(t).flash.success?((0,o.openBlock)(),(0,o.createElementBlock)("div",m,[h,(0,o.createElementVNode)("span",null,(0,o.toDisplayString)((0,o.unref)(t).flash.success),1),(0,o.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,o.createCommentVNode)("",!0),(0,o.unref)(t).flash.warning?((0,o.openBlock)(),(0,o.createElementBlock)("div",f,[p,(0,o.createElementVNode)("span",null,(0,o.toDisplayString)((0,o.unref)(t).flash.warning),1),(0,o.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,o.createCommentVNode)("",!0),(0,o.unref)(t).flash.error?((0,o.openBlock)(),(0,o.createElementBlock)("div",g,[w,(0,o.createElementVNode)("span",null,(0,o.toDisplayString)((0,o.unref)(t).flash.error),1),(0,o.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,o.createCommentVNode)("",!0)])}}}},94241:(e,t,r)=>{r.d(t,{Z:()=>i});var o=r(70821),n={class:"flex justify-end mt-6 pb-24"},a={key:0},s={key:1},l={key:2};const i={__name:"ShowFooter",props:{team:Object,show:Object},setup:function(e){var t=(new Date).getFullYear();return function(r,i){var c=(0,o.resolveComponent)("Link");return(0,o.openBlock)(),(0,o.createElementBlock)("div",n,[(0,o.createVNode)(c,{href:"/teams/".concat(e.team.slug),class:"text-blue-500 hover:text-blue-700 ml-2 uppercase"},{default:(0,o.withCtx)((function(){return[(0,o.createTextVNode)((0,o.toDisplayString)(e.team.name)+" © ",1),e.show.last_release_year>0?((0,o.openBlock)(),(0,o.createElementBlock)("span",a,(0,o.toDisplayString)(e.show.first_release_year)+"-"+(0,o.toDisplayString)(e.show.last_release_year),1)):(0,o.createCommentVNode)("",!0),!e.show.last_release_year&&e.show.first_release_year?((0,o.openBlock)(),(0,o.createElementBlock)("span",s,(0,o.toDisplayString)(e.show.first_release_year),1)):(0,o.createCommentVNode)("",!0),e.show.last_release_year||e.show.first_release_year?(0,o.createCommentVNode)("",!0):((0,o.openBlock)(),(0,o.createElementBlock)("span",l,(0,o.toDisplayString)((0,o.unref)(t)),1))]})),_:1},8,["href"])])}}}},64782:(e,t,r)=>{r.r(t),r.d(t,{default:()=>Ve});var o=r(70821),n=r(9680),a=r(24831),s=r(58358),l=r(90771),i=r(28037),c=r(40191),u=r(2448),d=r(93213),m=r(25543),h={class:"container mx-auto px-4 mb-12"},f=(0,o.createElementVNode)("div",{class:"w-full bg-gray-800 text-2xl p-4 mb-4"},"EPISODES",-1),p={class:"show-episodes w-full text-sm grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-8"},g={class:"relative inline-block"},w={key:0,class:"text-gray-400 mt-1"},y={key:1,class:"text-gray-400 mt-1"},v={class:"text-gray-400 mt-1"};const b={__name:"ShowEpisodesList",props:{show:Object,episodes:Object,filters:Object},setup:function(e){return function(t,r){var n=(0,o.resolveComponent)("Link");return(0,o.openBlock)(),(0,o.createElementBlock)("div",h,[f,(0,o.createElementVNode)("div",p,[((0,o.openBlock)(!0),(0,o.createElementBlock)(o.Fragment,null,(0,o.renderList)(e.episodes.data,(function(r){return(0,o.openBlock)(),(0,o.createElementBlock)("div",{key:r.id,class:"episode mt-8 w-full mx-auto"},[(0,o.createElementVNode)("div",g,[(0,o.createVNode)(n,{href:"/shows/".concat(e.show.slug,"/episode/").concat(r.slug)},{default:(0,o.withCtx)((function(){return[(0,o.createVNode)((0,o.unref)(m.Z),{image:r.image,alt:"episode cover",class:(0,o.normalizeClass)("h-28 w-48 min-w-[12rem] object-cover hover:opacity-75 transition ease-in-out duration-150 bg-black")},null,8,["image"])]})),_:2},1032,["href"])]),(0,o.createVNode)(n,{href:"/shows/".concat(e.show.slug,"/episode/").concat(r.slug),class:"block text-base font-semibold leading-tight hover:text-gray-400 mt-4"},{default:(0,o.withCtx)((function(){return[(0,o.createTextVNode)((0,o.toDisplayString)(r.name),1)]})),_:2},1032,["href"]),r.episode_number?(0,o.createCommentVNode)("",!0):((0,o.openBlock)(),(0,o.createElementBlock)("div",w,"Episode "+(0,o.toDisplayString)(r.id),1)),r.episode_number?((0,o.openBlock)(),(0,o.createElementBlock)("div",y,"Episode "+(0,o.toDisplayString)(r.episode_number),1)):(0,o.createCommentVNode)("",!0),(0,o.createElementVNode)("div",v,(0,o.toDisplayString)(t.formatDate(r.created_at)),1)])})),128))]),(0,o.createVNode)((0,o.unref)(d.Z),{data:e.episodes,class:"mt-12 mb-6 pb-6 border-b border-gray-800"},null,8,["data"])])}}};var x=r(94241),k=r(59369),V={class:"place-self-center flex flex-col"},E={id:"topDiv",class:"bg-gray-900 text-white px-5 pt-6"},N={key:1,class:"flex justify-end"},_={class:"flex flex-end flex-wrap-reverse justify-end gap-2 mr-4 my-4"},S={class:"container mx-auto px-4 gap-y-3"},B={class:"show-details border-b border-gray-800 pb-12 flex flex-col md:flex-row"},C={class:"items-center"},M={key:0,id:"topDiv"},L={class:"lg:ml-12 lg:mr-0"},j={class:"font-semibold text-4xl text-center lg:text-left"},P={class:"text-gray-400 text-center lg:text-left"},D={class:"hidden"},O={key:0},z={key:1},T={class:"flex flex-wrap my-2 m-auto lg:mx-0 justify-center lg:justify-start space-x-4 space-y-2"},F=(0,o.createElementVNode)("div",null,null,-1),I={key:0,class:"ml-3 px-3 py-3 text-gray-50 bg-black w-full text-center lg:text-left"},H=(0,o.createElementVNode)("br",null,null,-1),U=["disabled"],A=(0,o.createElementVNode)("svg",{class:"w-6 h-6 fill-current",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 485 485"},[(0,o.createElementVNode)("path",{d:"M413.974,71.026C368.171,25.225,307.274,0,242.5,0S116.829,25.225,71.026,71.026C25.225,116.829,0,177.726,0,242.5\n                                                s25.225,125.671,71.026,171.474C116.829,459.775,177.726,485,242.5,485s125.671-25.225,171.474-71.026\n                                                C459.775,368.171,485,307.274,485,242.5S459.775,116.829,413.974,71.026z M242.5,455C125.327,455,30,359.673,30,242.5\n                                                S125.327,30,242.5,30S455,125.327,455,242.5S359.673,455,242.5,455z"}),(0,o.createElementVNode)("polygon",{points:"181.062,336.575 343.938,242.5 181.062,148.425 \t"})],-1),R={key:0,class:"ml-2"},G={key:1,class:"ml-2"},Z={disabled:"",class:"flex bg-blue-500 text-white font-semibold ml-4 px-4 py-4 hover:bg-blue-400 rounded transition ease-in-out duration-150 items-center disabled:bg-gray-600 disabled:cursor-not-allowed"},Y={class:""},W={disabled:"",class:"flex bg-blue-500 text-white font-semibold ml-4 px-4 py-4 hover:bg-blue-400 rounded transition ease-in-out duration-150 items-center disabled:bg-gray-600 disabled:cursor-not-allowed"},Q={class:""},X={class:"flex flex-wrap justify-center lg:justify-start mt-4 m-auto lg:mx-0 space-x-4 space-y-2"},$=(0,o.createStaticVNode)('<div></div><div class="flex items-center"><div class="w-16 h-16 bg-gray-800 rounded-full"><div class="font-semibold text-xs flex justify-center items-center h-full">90%</div></div><div class="ml-4 text-xs">Member <br> Rating <br><span class="text-orange-500">(feature coming soon)</span></div></div><div class="flex items-center"><div class="w-16 h-16 bg-gray-800 rounded-full"><div class="font-semibold text-xs flex justify-center items-center h-full">92%</div></div><div class="ml-4 text-xs">Audience <br> Rating <br><span class="text-orange-500">(feature coming soon)</span></div></div>',3),q={class:"flex flex-wrap m-auto space-x-4 space-y-2 lg:ml-12 pt-6 lg:mt-2 2xl:pt-0"},J=(0,o.createElementVNode)("div",null,null,-1),K={key:0,class:"website-url w-8 h-8 bg-gray-800 rounded-full flex justify-center items-center"},ee=["href"],te=[(0,o.createElementVNode)("svg",{class:"w-5 h-5 fill-current",viewBox:"0 0 16 16",xmlns:"http://www.w3.org/2000/svg"},[(0,o.createElementVNode)("path",{d:"M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z"})],-1)],re={key:1,class:"instagram-url w-8 h-8 bg-gray-800 rounded-full flex justify-center items-center"},oe=["href"],ne=[(0,o.createElementVNode)("svg",{class:"w-4 h-4 fill-current",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 300 300"},[(0,o.createElementVNode)("path",{d:"M38.52,0.012h222.978C282.682,0.012,300,17.336,300,38.52v222.978c0,21.178-17.318,38.49-38.502,38.49\n\t\tH38.52c-21.184,0-38.52-17.313-38.52-38.49V38.52C0,17.336,17.336,0.012,38.52,0.012z M218.546,33.329\n\t\tc-7.438,0-13.505,6.091-13.505,13.525v32.314c0,7.437,6.067,13.514,13.505,13.514h33.903c7.426,0,13.506-6.077,13.506-13.514\n\t\tV46.854c0-7.434-6.08-13.525-13.506-13.525H218.546z M266.084,126.868h-26.396c2.503,8.175,3.86,16.796,3.86,25.759\n\t\tc0,49.882-41.766,90.34-93.266,90.34c-51.487,0-93.254-40.458-93.254-90.34c0-8.963,1.37-17.584,3.861-25.759H33.35v126.732\n\t\tc0,6.563,5.359,11.902,11.916,11.902h208.907c6.563,0,11.911-5.339,11.911-11.902V126.868z M150.283,90.978\n\t\tc-33.26,0-60.24,26.128-60.24,58.388c0,32.227,26.98,58.375,60.24,58.375c33.278,0,60.259-26.148,60.259-58.375\n\t\tC210.542,117.105,183.561,90.978,150.283,90.978z"})],-1)],ae={key:2,class:"telegram-url w-8 h-8 bg-gray-800 rounded-full flex justify-center items-center"},se=["href"],le=[(0,o.createElementVNode)("svg",{class:"w-5 h-5 fill-current pr-1",viewBox:"0 0 48 48",xmlns:"http://www.w3.org/2000/svg"},[(0,o.createElementVNode)("path",{d:"M41.4193 7.30899C41.4193 7.30899 45.3046 5.79399 44.9808 9.47328C44.8729 10.9883 43.9016 16.2908 43.1461 22.0262L40.5559 39.0159C40.5559 39.0159 40.3401 41.5048 38.3974 41.9377C36.4547 42.3705 33.5408 40.4227 33.0011 39.9898C32.5694 39.6652 24.9068 34.7955 22.2086 32.4148C21.4531 31.7655 20.5897 30.4669 22.3165 28.9519L33.6487 18.1305C34.9438 16.8319 36.2389 13.8019 30.8426 17.4812L15.7331 27.7616C15.7331 27.7616 14.0063 28.8437 10.7686 27.8698L3.75342 25.7055C3.75342 25.7055 1.16321 24.0823 5.58815 22.459C16.3807 17.3729 29.6555 12.1786 41.4193 7.30899Z"})],-1)],ie={key:3,class:"twitter-url w-8 h-8 bg-gray-800 rounded-full flex justify-center items-center"},ce=["href"],ue=[(0,o.createElementVNode)("svg",{class:"w-4 h-4 fill-current",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 310 310"},[(0,o.createElementVNode)("path",{id:"XMLID_827_",d:"M302.973,57.388c-4.87,2.16-9.877,3.983-14.993,5.463c6.057-6.85,10.675-14.91,13.494-23.73\n\t\tc0.632-1.977-0.023-4.141-1.648-5.434c-1.623-1.294-3.878-1.449-5.665-0.39c-10.865,6.444-22.587,11.075-34.878,13.783\n\t\tc-12.381-12.098-29.197-18.983-46.581-18.983c-36.695,0-66.549,29.853-66.549,66.547c0,2.89,0.183,5.764,0.545,8.598\n\t\tC101.163,99.244,58.83,76.863,29.76,41.204c-1.036-1.271-2.632-1.956-4.266-1.825c-1.635,0.128-3.104,1.05-3.93,2.467\n\t\tc-5.896,10.117-9.013,21.688-9.013,33.461c0,16.035,5.725,31.249,15.838,43.137c-3.075-1.065-6.059-2.396-8.907-3.977\n\t\tc-1.529-0.851-3.395-0.838-4.914,0.033c-1.52,0.871-2.473,2.473-2.513,4.224c-0.007,0.295-0.007,0.59-0.007,0.889\n\t\tc0,23.935,12.882,45.484,32.577,57.229c-1.692-0.169-3.383-0.414-5.063-0.735c-1.732-0.331-3.513,0.276-4.681,1.597\n\t\tc-1.17,1.32-1.557,3.16-1.018,4.84c7.29,22.76,26.059,39.501,48.749,44.605c-18.819,11.787-40.34,17.961-62.932,17.961\n\t\tc-4.714,0-9.455-0.277-14.095-0.826c-2.305-0.274-4.509,1.087-5.294,3.279c-0.785,2.193,0.047,4.638,2.008,5.895\n\t\tc29.023,18.609,62.582,28.445,97.047,28.445c67.754,0,110.139-31.95,133.764-58.753c29.46-33.421,46.356-77.658,46.356-121.367\n\t\tc0-1.826-0.028-3.67-0.084-5.508c11.623-8.757,21.63-19.355,29.773-31.536c1.237-1.85,1.103-4.295-0.33-5.998\n\t\tC307.394,57.037,305.009,56.486,302.973,57.388z"})],-1)],de={class:"mt-12 pr-6 text-gray-300 mr-1 lg:mr-36 w-full text-center lg:text-left"},me={class:"flex flex-col px-5"},he={class:"-my-2 overflow-x-hidden sm:-mx-6 lg:-mx-8"},fe={class:"py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"},pe={class:"mb-6 p-5"},ge={class:"container mx-auto px-4 mb-12"},we=(0,o.createElementVNode)("div",{class:"w-full bg-gray-800 text-2xl p-4 mb-4"},"CREATORS",-1),ye={class:"flex flex-row flex-wrap"},ve={class:"flex flex-col max-w-[8rem] px-3 py-4 font-medium break-words grow-0"},be=["src"],xe={class:"text-gray-200 w-full text-center text-sm"},ke=(0,o.createElementVNode)("div",{class:"container mx-auto px-4 mb-12"},[(0,o.createElementVNode)("div",{class:"w-full bg-gray-800 text-2xl p-4 mb-8"},"BONUS CONTENT")],-1);const Ve={__name:"Index",props:{show:Object,team:Object,episodes:Object,creators:Object,can:Object},setup:function(e){(0,a.U)("showsShow");var t=(0,s.useAppSettingStore)(),r=(0,l.useUserStore)(),h=(0,i.useNowPlayingStore)(),f=(0,u.useTeamStore)(),p=(0,c.useVideoPlayerStore)(),g=e,w=function(){h.reset(),h.show.name=g.show.name,h.show.url="/shows/".concat(g.show.slug),h.show.description=g.show.description,h.show.image=g.show.image,h.show.category=g.show.category,h.show.categorySub=g.show.categorySub,p.makeVideoFullPage(),n.Inertia.visit("/stream"),"spaces"===g.show.firstPlayVideo.storage_location&&"processing"!==g.show.firstPlayVideo.upload_status?(h.show.episode.name=g.show.firstPlayVideo.name,h.show.episode.url="/shows/".concat(g.show.slug,"/episode/").concat(g.show.firstPlayVideo.slug),h.show.episode.image=g.show.firstPlayVideo.image,p.loadNewSourceFromFile(g.show.firstPlayVideo)):""!==g.show.firstPlayVideoFromUrl.video_url&&(h.isFromWeb=!0,h.show.episode.name=g.show.firstPlayVideoFromUrl.name,h.show.episode.url="/shows/".concat(g.show.slug,"/episode/").concat(g.show.firstPlayVideoFromUrl.slug),h.show.episode.image=g.show.firstPlayVideoFromUrl.image,p.loadNewSourceFromUrl(g.show.firstPlayVideoFromUrl))};(new Date).getFullYear();return f.slug=g.team.slug,f.name=g.team.name,function(n,a){var s=(0,o.resolveComponent)("Head"),l=(0,o.resolveComponent)("font-awesome-icon");return(0,o.openBlock)(),(0,o.createElementBlock)(o.Fragment,null,[(0,o.createVNode)(s,{title:(0,o.unref)(g).show.name},null,8,["title"]),(0,o.createElementVNode)("div",V,[(0,o.createElementVNode)("div",E,[(0,o.unref)(t).showFlashMessage?((0,o.openBlock)(),(0,o.createBlock)(k.Z,{key:0,flash:n.$page.props.flash},null,8,["flash"])):(0,o.createCommentVNode)("",!0),(0,o.unref)(g).can.editShow||(0,o.unref)(g).can.manageShow?((0,o.openBlock)(),(0,o.createElementBlock)("header",N,[(0,o.createElementVNode)("div",_,[(0,o.createElementVNode)("div",null,[(0,o.unref)(g).can.editShow?((0,o.openBlock)(),(0,o.createElementBlock)("button",{key:0,onClick:a[0]||(a[0]=function(e){return(0,o.unref)(t).btnRedirect("/shows/".concat((0,o.unref)(g).show.slug,"/edit"))}),class:"px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"},"Edit ")):(0,o.createCommentVNode)("",!0)]),(0,o.createElementVNode)("div",null,[(0,o.unref)(g).can.manageShow?((0,o.openBlock)(),(0,o.createElementBlock)("button",{key:0,onClick:a[1]||(a[1]=function(e){return(0,o.unref)(t).btnRedirect("/shows/".concat((0,o.unref)(g).show.slug,"/manage"))}),class:"px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"},"Manage Show ")):(0,o.createCommentVNode)("",!0)])])])):(0,o.createCommentVNode)("",!0),(0,o.createElementVNode)("main",null,[(0,o.createElementVNode)("div",S,[(0,o.createElementVNode)("div",B,[(0,o.createElementVNode)("div",C,[(0,o.createVNode)((0,o.unref)(m.Z),{image:(0,o.unref)(g).show.image,alt:"show cover",class:"h-96 min-w-[16rem] w-64 object-cover mb-6 lg:mb-0 m-auto lg:m-0"},null,8,["image"])]),!(0,o.unref)(g).can.viewCreator&&(0,o.unref)(r).isMobile?((0,o.openBlock)(),(0,o.createElementBlock)("div",M)):(0,o.createCommentVNode)("",!0),(0,o.createElementVNode)("div",L,[(0,o.createElementVNode)("h2",j,(0,o.toDisplayString)(e.show.name),1),(0,o.createElementVNode)("div",P,[(0,o.createElementVNode)("span",null,(0,o.toDisplayString)(e.show.categoryName),1),(0,o.createTextVNode)(" · "),(0,o.createElementVNode)("span",D,(0,o.toDisplayString)(e.show.categorySubName)+" ·",1),e.show.last_release_year?((0,o.openBlock)(),(0,o.createElementBlock)("span",O,(0,o.toDisplayString)(e.show.first_release_year)+"-"+(0,o.toDisplayString)(e.show.last_release_year),1)):(0,o.createCommentVNode)("",!0),e.show.last_release_year?(0,o.createCommentVNode)("",!0):((0,o.openBlock)(),(0,o.createElementBlock)("span",z,(0,o.toDisplayString)(e.show.first_release_year),1))]),(0,o.createElementVNode)("div",T,[F,"processing"!==(0,o.unref)(g).show.firstPlayVideo.upload_status||(0,o.unref)(g).show.firstPlayVideoFromUrl?(0,o.createCommentVNode)("",!0):((0,o.openBlock)(),(0,o.createElementBlock)("div",I,[(0,o.createTextVNode)(" The first episode is currently processing. "),H,(0,o.createTextVNode)("Please check back later. ")])),(0,o.unref)(g).show.firstPlayVideo.file_name||""!==(0,o.unref)(g).show.firstPlayVideoFromUrl.video_url?((0,o.openBlock)(),(0,o.createElementBlock)("button",{key:1,disabled:"processing"===(0,o.unref)(g).show.firstPlayVideo.upload_status&&!(0,o.unref)(g).show.firstPlayVideoUrl,class:"flex bg-blue-500 text-white font-semibold ml-4 px-4 py-4 hover:bg-blue-400 rounded transition ease-in-out duration-150 items-center disabled:bg-gray-600 disabled:cursor-not-allowed",onClick:a[2]||(a[2]=function(){return(0,o.unref)(w)&&(0,o.unref)(w).apply(void 0,arguments)})},[A,(0,o.unref)(p).nowPlayingName===(0,o.unref)(g).show.firstPlayVideo.name||(0,o.unref)(p).nowPlayingName===(0,o.unref)(g).show.firstPlayVideoFromUrl.name?((0,o.openBlock)(),(0,o.createElementBlock)("span",R,"Now Playing")):((0,o.openBlock)(),(0,o.createElementBlock)("span",G,"Watch Now"))],8,U)):(0,o.createCommentVNode)("",!0),(0,o.createElementVNode)("button",Z,[(0,o.createElementVNode)("span",Y,[(0,o.createVNode)(l,{icon:"fa-circle-down",class:"mr-2"}),(0,o.createTextVNode)("Save For Later")])]),(0,o.createElementVNode)("button",W,[(0,o.createElementVNode)("span",Q,[(0,o.createVNode)(l,{icon:"fa-share",class:"mr-2"}),(0,o.createTextVNode)("Share")])])]),(0,o.createElementVNode)("div",X,[$,(0,o.createElementVNode)("div",q,[J,(0,o.unref)(g).show.www_url?((0,o.openBlock)(),(0,o.createElementBlock)("div",K,[(0,o.createElementVNode)("a",{href:(0,o.unref)(g).show.www_url,class:"hover:text-gray-400",target:"_blank"},te,8,ee)])):(0,o.createCommentVNode)("",!0),(0,o.unref)(g).show.instagram_name?((0,o.openBlock)(),(0,o.createElementBlock)("div",re,[(0,o.createElementVNode)("a",{href:"https://www.instagram.com/"+(0,o.unref)(g).show.instagram_name,class:"hover:text-gray-400",target:"_blank"},ne,8,oe)])):(0,o.createCommentVNode)("",!0),(0,o.unref)(g).show.telegram_url?((0,o.openBlock)(),(0,o.createElementBlock)("div",ae,[(0,o.createElementVNode)("a",{href:(0,o.unref)(g).show.telegram_url,class:"hover:text-gray-400",target:"_blank"},le,8,se)])):(0,o.createCommentVNode)("",!0),(0,o.unref)(g).show.twitter_handle?((0,o.openBlock)(),(0,o.createElementBlock)("div",ie,[(0,o.createElementVNode)("a",{href:"https://www.twitter.com/"+(0,o.unref)(g).show.twitter_handle,class:"hover:text-gray-400",target:"_blank"},ue,8,ce)])):(0,o.createCommentVNode)("",!0)])]),(0,o.createElementVNode)("div",de,(0,o.toDisplayString)(e.show.description),1)])])])]),(0,o.createElementVNode)("div",me,[(0,o.createElementVNode)("div",he,[(0,o.createElementVNode)("div",fe,[(0,o.createElementVNode)("div",pe,[(0,o.createVNode)((0,o.unref)(b),{episodes:(0,o.unref)(g).episodes,show:(0,o.unref)(g).show},null,8,["episodes","show"]),(0,o.createElementVNode)("div",ge,[we,(0,o.createElementVNode)("div",ye,[((0,o.openBlock)(!0),(0,o.createElementBlock)(o.Fragment,null,(0,o.renderList)((0,o.unref)(g).creators.data,(function(e){return(0,o.openBlock)(),(0,o.createElementBlock)("div",{key:e.id,class:"pb-8 mx-auto lg:mx-0"},[(0,o.createElementVNode)("div",ve,[(0,o.createElementVNode)("img",{src:"/storage/"+e.profile_photo_path,class:"pb-2 rounded-full h-20 w-20 object-cover mb-2 justify-center"},null,8,be),(0,o.createElementVNode)("span",xe,(0,o.toDisplayString)(e.name),1)])])})),128))]),(0,o.createVNode)((0,o.unref)(d.Z),{data:(0,o.unref)(g).creators,class:"mb-6 pb-6 border-b border-gray-800"},null,8,["data"])]),ke]),(0,o.createVNode)((0,o.unref)(x.Z),{team:(0,o.unref)(g).team,show:(0,o.unref)(g).show},null,8,["team","show"])])])])])])],64)}}}}}]);
//# sourceMappingURL=4782.js.map