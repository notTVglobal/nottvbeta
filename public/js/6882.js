/*! For license information please see 6882.js.LICENSE.txt */
"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[6882],{9812:(e,t,o)=>{function n(e){return n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},n(e)}function r(){r=function(){return e};var e={},t=Object.prototype,o=t.hasOwnProperty,a=Object.defineProperty||function(e,t,o){e[t]=o.value},i="function"==typeof Symbol?Symbol:{},s=i.iterator||"@@iterator",l=i.asyncIterator||"@@asyncIterator",c=i.toStringTag||"@@toStringTag";function d(e,t,o){return Object.defineProperty(e,t,{value:o,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{d({},"")}catch(e){d=function(e,t,o){return e[t]=o}}function u(e,t,o,n){var r=t&&t.prototype instanceof f?t:f,i=Object.create(r.prototype),s=new B(n||[]);return a(i,"_invoke",{value:N(e,o,s)}),i}function p(e,t,o){try{return{type:"normal",arg:e.call(t,o)}}catch(e){return{type:"throw",arg:e}}}e.wrap=u;var m={};function f(){}function h(){}function g(){}var v={};d(v,s,(function(){return this}));var y=Object.getPrototypeOf,x=y&&y(y(_([])));x&&x!==t&&o.call(x,s)&&(v=x);var w=g.prototype=f.prototype=Object.create(v);function b(e){["next","throw","return"].forEach((function(t){d(e,t,(function(e){return this._invoke(t,e)}))}))}function E(e,t){function r(a,i,s,l){var c=p(e[a],e,i);if("throw"!==c.type){var d=c.arg,u=d.value;return u&&"object"==n(u)&&o.call(u,"__await")?t.resolve(u.__await).then((function(e){r("next",e,s,l)}),(function(e){r("throw",e,s,l)})):t.resolve(u).then((function(e){d.value=e,s(d)}),(function(e){return r("throw",e,s,l)}))}l(c.arg)}var i;a(this,"_invoke",{value:function(e,o){function n(){return new t((function(t,n){r(e,o,t,n)}))}return i=i?i.then(n,n):n()}})}function N(e,t,o){var n="suspendedStart";return function(r,a){if("executing"===n)throw new Error("Generator is already running");if("completed"===n){if("throw"===r)throw a;return C()}for(o.method=r,o.arg=a;;){var i=o.delegate;if(i){var s=k(i,o);if(s){if(s===m)continue;return s}}if("next"===o.method)o.sent=o._sent=o.arg;else if("throw"===o.method){if("suspendedStart"===n)throw n="completed",o.arg;o.dispatchException(o.arg)}else"return"===o.method&&o.abrupt("return",o.arg);n="executing";var l=p(e,t,o);if("normal"===l.type){if(n=o.done?"completed":"suspendedYield",l.arg===m)continue;return{value:l.arg,done:o.done}}"throw"===l.type&&(n="completed",o.method="throw",o.arg=l.arg)}}}function k(e,t){var o=t.method,n=e.iterator[o];if(void 0===n)return t.delegate=null,"throw"===o&&e.iterator.return&&(t.method="return",t.arg=void 0,k(e,t),"throw"===t.method)||"return"!==o&&(t.method="throw",t.arg=new TypeError("The iterator does not provide a '"+o+"' method")),m;var r=p(n,e.iterator,t.arg);if("throw"===r.type)return t.method="throw",t.arg=r.arg,t.delegate=null,m;var a=r.arg;return a?a.done?(t[e.resultName]=a.value,t.next=e.nextLoc,"return"!==t.method&&(t.method="next",t.arg=void 0),t.delegate=null,m):a:(t.method="throw",t.arg=new TypeError("iterator result is not an object"),t.delegate=null,m)}function V(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function S(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function B(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(V,this),this.reset(!0)}function _(e){if(e){var t=e[s];if(t)return t.call(e);if("function"==typeof e.next)return e;if(!isNaN(e.length)){var n=-1,r=function t(){for(;++n<e.length;)if(o.call(e,n))return t.value=e[n],t.done=!1,t;return t.value=void 0,t.done=!0,t};return r.next=r}}return{next:C}}function C(){return{value:void 0,done:!0}}return h.prototype=g,a(w,"constructor",{value:g,configurable:!0}),a(g,"constructor",{value:h,configurable:!0}),h.displayName=d(g,c,"GeneratorFunction"),e.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===h||"GeneratorFunction"===(t.displayName||t.name))},e.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,g):(e.__proto__=g,d(e,c,"GeneratorFunction")),e.prototype=Object.create(w),e},e.awrap=function(e){return{__await:e}},b(E.prototype),d(E.prototype,l,(function(){return this})),e.AsyncIterator=E,e.async=function(t,o,n,r,a){void 0===a&&(a=Promise);var i=new E(u(t,o,n,r),a);return e.isGeneratorFunction(o)?i:i.next().then((function(e){return e.done?e.value:i.next()}))},b(w),d(w,c,"Generator"),d(w,s,(function(){return this})),d(w,"toString",(function(){return"[object Generator]"})),e.keys=function(e){var t=Object(e),o=[];for(var n in t)o.push(n);return o.reverse(),function e(){for(;o.length;){var n=o.pop();if(n in t)return e.value=n,e.done=!1,e}return e.done=!0,e}},e.values=_,B.prototype={constructor:B,reset:function(e){if(this.prev=0,this.next=0,this.sent=this._sent=void 0,this.done=!1,this.delegate=null,this.method="next",this.arg=void 0,this.tryEntries.forEach(S),!e)for(var t in this)"t"===t.charAt(0)&&o.call(this,t)&&!isNaN(+t.slice(1))&&(this[t]=void 0)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(e){if(this.done)throw e;var t=this;function n(o,n){return i.type="throw",i.arg=e,t.next=o,n&&(t.method="next",t.arg=void 0),!!n}for(var r=this.tryEntries.length-1;r>=0;--r){var a=this.tryEntries[r],i=a.completion;if("root"===a.tryLoc)return n("end");if(a.tryLoc<=this.prev){var s=o.call(a,"catchLoc"),l=o.call(a,"finallyLoc");if(s&&l){if(this.prev<a.catchLoc)return n(a.catchLoc,!0);if(this.prev<a.finallyLoc)return n(a.finallyLoc)}else if(s){if(this.prev<a.catchLoc)return n(a.catchLoc,!0)}else{if(!l)throw new Error("try statement without catch or finally");if(this.prev<a.finallyLoc)return n(a.finallyLoc)}}}},abrupt:function(e,t){for(var n=this.tryEntries.length-1;n>=0;--n){var r=this.tryEntries[n];if(r.tryLoc<=this.prev&&o.call(r,"finallyLoc")&&this.prev<r.finallyLoc){var a=r;break}}a&&("break"===e||"continue"===e)&&a.tryLoc<=t&&t<=a.finallyLoc&&(a=null);var i=a?a.completion:{};return i.type=e,i.arg=t,a?(this.method="next",this.next=a.finallyLoc,m):this.complete(i)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),m},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var o=this.tryEntries[t];if(o.finallyLoc===e)return this.complete(o.completion,o.afterLoc),S(o),m}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var o=this.tryEntries[t];if(o.tryLoc===e){var n=o.completion;if("throw"===n.type){var r=n.arg;S(o)}return r}}throw new Error("illegal catch attempt")},delegateYield:function(e,t,o){return this.delegate={iterator:_(e),resultName:t,nextLoc:o},"next"===this.method&&(this.arg=void 0),m}},e}function a(e,t,o,n,r,a,i){try{var s=e[a](i),l=s.value}catch(e){return void o(e)}s.done?t(l):Promise.resolve(l).then(n,r)}o.d(t,{K:()=>i});var i=(0,o(9876).Q_)("showStore",{state:function(){return{id:0,name:"",category_id:0,category_sub_id:0,category_description:"",sub_categories:[],description:"",posterName:[],posterId:[0],episodes:[],team_id:"team id",episodePoster:"",noteEdit:0,note:"",saveNoteProcessing:Boolean}},actions:{fill:function(){var e,t=this;return(e=r().mark((function e(){var n;return r().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,o.e(6946).then(o.t.bind(o,6946,19));case 2:n=e.sent,t.$state=n.default;case 4:case"end":return e.stop()}}),e)})),function(){var t=this,o=arguments;return new Promise((function(n,r){var i=e.apply(t,o);function s(e){a(i,n,r,s,l,"next",e)}function l(e){a(i,n,r,s,l,"throw",e)}s(void 0)}))})()}},getters:{}})},6320:(e,t,o)=>{o.d(t,{Z:()=>r});var n=o(821);const r={__name:"Pagination",props:{links:Array,id:String},setup:function(e){return function(t,o){return(0,n.openBlock)(),(0,n.createElementBlock)("div",null,[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)(e.links,(function(t,r){return(0,n.openBlock)(),(0,n.createBlock)((0,n.resolveDynamicComponent)(t.url?"Link":"span"),{id:e.id,key:r,href:t.url,innerHTML:t.label,class:(0,n.normalizeClass)(["px-1 text-gray-800 dark:text-gray-50 dark:hover:text-blue-400 hover:text-blue-400",{"text-gray-300 dark:text-gray-700 dark:hover:text-gray-700 hover:text-gray-300":!t.url,"font-bold":t.active}]),onScroll:o[0]||(o[0]=(0,n.withModifiers)((function(){}),["prevent"]))},null,40,["id","href","innerHTML","class"])})),128))])}}}},3032:(e,t,o)=>{o.d(t,{Z:()=>s});var n=o(821),r={class:"flex justify-end mt-6 mb-24"},a={key:0},i={key:1};const s={__name:"ShowFooter",props:{team:Object,show:Object},setup:function(e){(new Date).getFullYear();return function(t,o){var s=(0,n.resolveComponent)("Link");return(0,n.openBlock)(),(0,n.createElementBlock)("div",r,[(0,n.createVNode)(s,{href:"/teams/".concat(e.team.slug),class:"text-blue-500 ml-2 uppercase"},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)((0,n.toDisplayString)(e.team.name)+" © ",1),e.show.last_release_year?((0,n.openBlock)(),(0,n.createElementBlock)("span",a,(0,n.toDisplayString)(e.show.first_release_year)+"-"+(0,n.toDisplayString)(e.show.last_release_year),1)):(0,n.createCommentVNode)("",!0),e.show.last_release_year?(0,n.createCommentVNode)("",!0):((0,n.openBlock)(),(0,n.createElementBlock)("span",i,(0,n.toDisplayString)(e.show.first_release_year),1))]})),_:1},8,["href"])])}}}},6882:(e,t,o)=>{o.r(t),o.d(t,{default:()=>Be});var n=o(821),r=o(191),a=o(9812),i=o(2448),s={class:"flex flex-row"},l=["src"],c={class:"inline-flex items-center text-3xl font-semibold relative"};const d={__name:"ShowHeader",props:{show:Object,team:Object},setup:function(e){(0,a.K)(),(0,i.A)();return function(t,o){var r=(0,n.resolveComponent)("Link");return(0,n.openBlock)(),(0,n.createElementBlock)("div",null,[(0,n.createVNode)(r,{href:"/shows/".concat(e.show.slug),class:"uppercase"},{default:(0,n.withCtx)((function(){return[(0,n.createElementVNode)("div",s,[(0,n.createElementVNode)("img",{src:"/storage/images/"+e.show.poster,alt:"",class:"w-20 mr-2 justify-left"},null,8,l),(0,n.createElementVNode)("span",c,(0,n.toDisplayString)(e.show.name),1)])]})),_:1},8,["href"])])}}};var u=o(9038),p=["onKeyup"];const m={__name:"EpisodeNoteEdit",props:{episode:Object},emits:["saveNoteProcessing"],setup:function(e,t){var o=t.emit,r=e,i=(0,a.K)();i.saveNoteProcessing=!1;var s=(0,u.cI)({note:""});function l(){i.saveNoteProcessing=!0,i.note=s.note,o("saveNoteProcessing"),axios.post("/shows/episode/notes",{notes:s.note,episodeId:r.episode.id}).then((function(e){i.noteEdit=0,console.log("note saved"),i.saveNoteProcessing=!1})).catch((function(e){console.log(e)}))}return s.note=r.episode.notes,function(e,t){return(0,n.openBlock)(),(0,n.createElementBlock)("div",null,[(0,n.createElementVNode)("form",{onSubmit:t[1]||(t[1]=(0,n.withModifiers)((function(){}),["prevent"]))},[(0,n.withDirectives)((0,n.createElementVNode)("input",{class:"text-black p-1 w-3/4",placeholder:"Write a note...",type:"text","onUpdate:modelValue":t[0]||(t[0]=function(e){return(0,n.unref)(s).note=e}),onKeyup:(0,n.withKeys)(l,["enter"])},null,40,p),[[n.vModelText,(0,n.unref)(s).note]])],32)])}}};var f={class:"px-6 py-4 text-sm"},h={key:0},g={key:1},v={class:"text-xl font-medium flex items-center gap-x-4 px-6 py-4 uppercase w-fit"},y={key:0,class:"italic"},x={class:"px-6 py-4 text-right"},w={key:0,class:"font-semibold text-xl text-orange-400"},b={key:1,class:"text-xl text-green-400"},E={key:2,class:"font-semibold text-xl text-green-600"},N={key:3,class:"font-bold text-xl text-green-600"},k={key:4,class:"font-semibold text-xl text-purple-700"},V={key:5,class:"font-italic text-xl text-pink-500"},S={key:6,class:"font-bold text-xl text-black"},B={key:7,class:"font-medium text-xl text-gray-500"},_={key:8,class:"font-semibold font-italic text-xl text-red-700"},C={key:9,class:"font-bold text-xl text-red-800"},L={class:""},D=(0,n.createElementVNode)("button",{class:"px-4 py-2 text-white font-semibold bg-blue-500 hover:bg-blue-600 rounded-lg"},"Edit ",-1);const j={__name:"ShowEpisode",props:{episode:Object,showSlug:String},setup:function(e){var t=e,o=(0,i.A)(),r=(0,a.K)();r.noteEdit=0;var s=(0,n.ref)(0);function l(){t.episode.notes=r.note,s.value+=1}(0,u.cI)({note:""});function c(){r.noteEdit=t.episode.id}return function(a,i){var d=(0,n.resolveComponent)("Link");return(0,n.openBlock)(),(0,n.createElementBlock)("tr",null,[(0,n.createElementVNode)("td",f,[e.episode.episodeNumber?(0,n.createCommentVNode)("",!0):((0,n.openBlock)(),(0,n.createElementBlock)("div",h,(0,n.toDisplayString)(e.episode.id),1)),e.episode.episodeNumber?((0,n.openBlock)(),(0,n.createElementBlock)("div",g,(0,n.toDisplayString)(e.episode.episodeNumber),1)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("td",v,[(0,n.createVNode)(d,{href:"/shows/".concat(e.showSlug,"/episode/").concat(e.episode.slug,"/"),class:"font-semibold light:text-blue-800 light:hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-200"},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)((0,n.toDisplayString)(e.episode.name),1)]})),_:1},8,["href"])]),(0,n.createElementVNode)("td",{class:"light:text-gray-600 dark:text-gray-100 px-6 py-4 text-sm w-full min-w-[16rem]",onClick:c},[e.episode.notes?(0,n.createCommentVNode)("",!0):(0,n.withDirectives)(((0,n.openBlock)(),(0,n.createElementBlock)("span",y,"Click here to add/edit a note.",512)),[[n.vShow,(0,n.unref)(r).noteEdit!==(0,n.unref)(t).episode.id]]),e.episode.notes?(0,n.withDirectives)(((0,n.openBlock)(),(0,n.createElementBlock)("span",{key:s.value},(0,n.toDisplayString)(e.episode.notes),1)),[[n.vShow,(0,n.unref)(r).noteEdit!==(0,n.unref)(t).episode.id]]):(0,n.createCommentVNode)("",!0),(0,n.withDirectives)((0,n.createElementVNode)("div",null,[(0,n.createVNode)((0,n.unref)(m),{episode:(0,n.unref)(t).episode,onSaveNoteProcessing:l},null,8,["episode"]),(0,n.withDirectives)((0,n.createElementVNode)("div",null,"Saving...",512),[[n.vShow,(0,n.unref)(r).saveNoteProcessing]])],512),[[n.vShow,(0,n.unref)(r).noteEdit===(0,n.unref)(t).episode.id]])]),(0,n.createElementVNode)("td",x,[1===e.episode.episodeStatusId?((0,n.openBlock)(),(0,n.createElementBlock)("button",w,(0,n.toDisplayString)(e.episode.episodeStatus),1)):(0,n.createCommentVNode)("",!0),2===e.episode.episodeStatusId?((0,n.openBlock)(),(0,n.createElementBlock)("button",b,(0,n.toDisplayString)(e.episode.episodeStatus),1)):(0,n.createCommentVNode)("",!0),3===e.episode.episodeStatusId?((0,n.openBlock)(),(0,n.createElementBlock)("button",E,(0,n.toDisplayString)(e.episode.episodeStatus),1)):(0,n.createCommentVNode)("",!0),4===e.episode.episodeStatusId?((0,n.openBlock)(),(0,n.createElementBlock)("button",N,(0,n.toDisplayString)(e.episode.episodeStatus),1)):(0,n.createCommentVNode)("",!0),5===e.episode.episodeStatusId?((0,n.openBlock)(),(0,n.createElementBlock)("button",k,(0,n.toDisplayString)(e.episode.episodeStatus),1)):(0,n.createCommentVNode)("",!0),6===e.episode.episodeStatusId?((0,n.openBlock)(),(0,n.createElementBlock)("button",V,(0,n.toDisplayString)(e.episode.episodeStatus),1)):(0,n.createCommentVNode)("",!0),7===e.episode.episodeStatusId?((0,n.openBlock)(),(0,n.createElementBlock)("button",S,(0,n.toDisplayString)(e.episode.episodeStatus),1)):(0,n.createCommentVNode)("",!0),8===e.episode.episodeStatusId?((0,n.openBlock)(),(0,n.createElementBlock)("button",B,(0,n.toDisplayString)(e.episode.episodeStatus),1)):(0,n.createCommentVNode)("",!0),9===e.episode.episodeStatusId?((0,n.openBlock)(),(0,n.createElementBlock)("button",_,(0,n.toDisplayString)(e.episode.episodeStatus),1)):(0,n.createCommentVNode)("",!0),10===e.episode.episodeStatusId?((0,n.openBlock)(),(0,n.createElementBlock)("button",C,(0,n.toDisplayString)(e.episode.episodeStatus),1)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("td",null,[(0,n.createElementVNode)("div",L,[(0,n.unref)(o).can.editShow?((0,n.openBlock)(),(0,n.createBlock)(d,{key:0,href:"/shows/".concat(e.showSlug,"/episode/").concat(e.episode.slug,"/edit")},{default:(0,n.withCtx)((function(){return[D]})),_:1},8,["href"])):(0,n.createCommentVNode)("",!0)])])])}}};var O=o(6320),T={class:"min-w-full divide-y divide-gray-200"},I=(0,n.createElementVNode)("thead",{class:"divide-y divide-gray-200"},[(0,n.createElementVNode)("tr",null,[(0,n.createElementVNode)("td",{class:"px-6 py-4 whitespace-nowrap text-sm font-medium"}," Ep.# "),(0,n.createElementVNode)("td",{class:"px-6 py-4 whitespace-nowrap w-1/3"},[(0,n.createElementVNode)("div",{class:"flex items-start"},[(0,n.createElementVNode)("div",{class:"text-sm font-medium"}," Episode Name ")])]),(0,n.createElementVNode)("td",{class:"px-6 py-4 whitespace-nowrap text-sm font-medium"}," Episode Notes "),(0,n.createElementVNode)("td",{class:"px-6 py-4 whitespace-nowrap text-sm font-medium text-right"}," Episode Status "),(0,n.createElementVNode)("td")])],-1),P={class:"divide-y divide-gray-200"};const A={__name:"ShowEpisodesList",props:{show:Object,episodes:Object,filters:Object,can:Object},setup:function(e){var t=e;return function(o,r){return(0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,null,[(0,n.createElementVNode)("table",T,[I,(0,n.createElementVNode)("tbody",P,[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)((0,n.unref)(t).episodes.data,(function(e){return(0,n.openBlock)(),(0,n.createBlock)(j,{episode:e,showSlug:(0,n.unref)(t).show.slug},null,8,["episode","showSlug"])})),256))])]),(0,n.createVNode)((0,n.unref)(O.Z),{links:e.episodes.links,class:"mb-6"},null,8,["links"])],64)}}};var F=o(3032),G={class:"min-w-full divide-y divide-gray-200"},M=[(0,n.createElementVNode)("thead",{class:"divide-y divide-gray-200"},[(0,n.createElementVNode)("tr",null,[(0,n.createElementVNode)("td",{class:"px-6 py-4 whitespace-nowrap"},[(0,n.createElementVNode)("div",{class:"flex items-center"},[(0,n.createElementVNode)("div",null,[(0,n.createElementVNode)("div",{class:"text-sm font-medium pl-14"}," Name ")])])]),(0,n.createElementVNode)("td",{class:"px-6 py-4 whitespace-nowrap text-sm font-medium"}," Role "),(0,n.createElementVNode)("td",{class:"px-6 py-4 whitespace-nowrap text-sm font-medium"}," Completed Assignments "),(0,n.createElementVNode)("td",{class:"px-6 py-4 whitespace-nowrap text-sm font-medium"}," Total Credits "),(0,n.createElementVNode)("td",{class:"px-6 py-4 whitespace-nowrap text-sm font-medium text-right"}," Status ")])],-1),(0,n.createElementVNode)("tbody",{class:"bg-white divide-y divide-gray-200"},null,-1)];const K={},H=(0,o(3744).Z)(K,[["render",function(e,t){return(0,n.openBlock)(),(0,n.createElementBlock)("table",G,M)}]]);var Z=o(771),R=(0,n.createElementVNode)("div",{id:"topDiv"},null,-1),W={class:"place-self-center flex flex-col gap-y-3"},Y={class:"light:bg-white light:text-black dark:bg-gray-800 dark:text-gray-50 rounded p-5 mb-10"},q={key:0,class:"p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800",role:"alert"},z={class:"font-medium"},Q={class:"flex justify-between mb-3"},U={class:"gap-2"},$=(0,n.createElementVNode)("div",{class:"font-bold mb-4 text-orange-400"},"MANAGE SHOW",-1),J={class:"flex flex-wrap-reverse justify-end gap-2"},X={class:""},ee=(0,n.createElementVNode)("button",{class:"px-4 py-2 text-white font-semibold bg-red-500 hover:bg-red-600 rounded-lg disabled:bg-gray-400"},"Go Live ",-1),te={class:""},oe=(0,n.createElementVNode)("button",{class:"px-4 py-2 text-white font-semibold bg-green-500 hover:bg-green-600 rounded-lg disabled:bg-gray-400"},"Create Episode ",-1),ne={class:""},re=(0,n.createElementVNode)("button",{class:"px-4 py-2 text-white font-semibold bg-blue-500 hover:bg-blue-600 rounded-lg"},"Edit ",-1),ae={hidden:""},ie=(0,n.createElementVNode)("button",{class:"px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"},"Dashboard ",-1),se={class:"flex justify-end mt-6"},le={class:"flex flex-col"},ce=(0,n.createElementVNode)("span",{class:"text-xs font-semibold uppercase"},"Team: ",-1),de=(0,n.createElementVNode)("span",{class:"text-xs font-semibold mr-2 uppercase"},"Show Runner: ",-1),ue={class:"font-bold"},pe=(0,n.createElementVNode)("span",{class:"text-xs font-semibold mr-2 uppercase"},"Category: ",-1),me={class:"font-bold"},fe=(0,n.createElementVNode)("span",{class:"text-xs font-semibold mr-2 uppercase"},"Sub-category: ",-1),he={class:"font-bold"},ge={class:"p-5 text-gray-100"},ve=(0,n.createElementVNode)("span",{class:"uppercase text-xs font-semibold text-orange-300"},"SHOW NOTES: ",-1),ye={class:"flex flex-col"},xe={class:"-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"},we={class:"py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"},be={class:"shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"},Ee=(0,n.createElementVNode)("div",{class:"bg-orange-300 p-2 font-bold text-black"},"Episodes",-1),Ne={class:"mt-4 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"},ke=(0,n.createElementVNode)("div",{class:"bg-orange-300 p-2 font-bold text-black"},"Credits",-1),Ve=(0,n.createElementVNode)("div",{class:"border-1 border-t mb-3 bg-orange-300 py-1 px-2 text-xs font-semibold text-red-800"}," In development. Not currently working. ",-1),Se=(0,n.createElementVNode)("button",{class:"bg-green-500 hover:bg-green-600 text-white ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max",disabled:""},"Create Assignment ",-1);const Be={__name:"Manage",props:{show:Object,team:Object,episodes:Object,message:String,can:Object},setup:function(e){var t=e,o=(0,r.q)(),s=((0,a.K)(),(0,i.A)()),l=(0,Z.L)();return o.currentPage="shows",(0,n.onBeforeMount)((function(){l.scrollToTopCounter=0})),(0,n.onMounted)((function(){o.makeVideoTopRight(),0===l.scrollToTopCounter&&(document.getElementById("topDiv").scrollIntoView(),l.scrollToTopCounter++)})),s.setActiveTeam(t.team),s.setActiveShow(t.show),s.can=t.can,function(o,r){var a=(0,n.resolveComponent)("Head"),i=(0,n.resolveComponent)("Link");return(0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,null,[(0,n.createVNode)(a,{title:"Manage Show: ".concat((0,n.unref)(t).show.name)},null,8,["title"]),R,(0,n.createElementVNode)("div",W,[(0,n.createElementVNode)("div",Y,[(0,n.unref)(t).message?((0,n.openBlock)(),(0,n.createElementBlock)("div",q,[(0,n.createElementVNode)("span",z,(0,n.toDisplayString)((0,n.unref)(t).message),1)])):(0,n.createCommentVNode)("",!0),(0,n.createElementVNode)("header",null,[(0,n.createElementVNode)("div",Q,[(0,n.createElementVNode)("div",U,[$,(0,n.createElementVNode)("div",null,[(0,n.createVNode)((0,n.unref)(d),{show:(0,n.unref)(t).show,team:(0,n.unref)(t).team},null,8,["show","team"])])]),(0,n.createElementVNode)("div",null,[(0,n.createElementVNode)("div",J,[(0,n.createElementVNode)("div",X,[(0,n.unref)(s).can.goLive?((0,n.openBlock)(),(0,n.createBlock)(i,{key:0,href:"/golive"},{default:(0,n.withCtx)((function(){return[ee]})),_:1})):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",te,[(0,n.unref)(s).can.createEpisode?((0,n.openBlock)(),(0,n.createBlock)(i,{key:0,href:o.route("shows.createEpisode",{show:(0,n.unref)(t).show.slug})},{default:(0,n.withCtx)((function(){return[oe]})),_:1},8,["href"])):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",ne,[(0,n.unref)(s).can.editShow?((0,n.openBlock)(),(0,n.createBlock)(i,{key:0,href:"/shows/".concat(e.show.slug,"/edit")},{default:(0,n.withCtx)((function(){return[re]})),_:1},8,["href"])):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",ae,[(0,n.createVNode)(i,{href:"/dashboard"},{default:(0,n.withCtx)((function(){return[ie]})),_:1})])]),(0,n.createElementVNode)("div",se,[(0,n.createElementVNode)("div",le,[(0,n.createElementVNode)("div",null,[ce,(0,n.createVNode)(i,{href:"/teams/".concat(e.team.slug,"/manage"),class:"text-blue-500 ml-2 uppercase font-bold"},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)((0,n.toDisplayString)(e.team.name),1)]})),_:1},8,["href"])]),(0,n.createElementVNode)("div",null,[de,(0,n.createElementVNode)("span",ue,(0,n.toDisplayString)(e.show.showRunner),1)]),(0,n.createElementVNode)("div",null,[pe,(0,n.createElementVNode)("span",me,(0,n.toDisplayString)(e.show.categoryName),1)]),(0,n.createElementVNode)("div",null,[fe,(0,n.createElementVNode)("span",he,(0,n.toDisplayString)(e.show.subCategoryName),1)])])])])])]),(0,n.createElementVNode)("div",ge,[ve,(0,n.createTextVNode)(" "+(0,n.toDisplayString)((0,n.unref)(t).show.notes),1)]),(0,n.createElementVNode)("div",ye,[(0,n.createElementVNode)("div",xe,[(0,n.createElementVNode)("div",we,[(0,n.createElementVNode)("div",be,[(0,n.createElementVNode)("div",null,[Ee,(0,n.createVNode)((0,n.unref)(A),{episodes:(0,n.unref)(t).episodes,show:(0,n.unref)(t).show},null,8,["episodes","show"])])]),(0,n.createElementVNode)("div",Ne,[ke,Ve,(0,n.unref)(s).can.createAssignment?((0,n.openBlock)(),(0,n.createBlock)(i,{key:0,href:"#"},{default:(0,n.withCtx)((function(){return[Se]})),_:1})):(0,n.createCommentVNode)("",!0),(0,n.createVNode)((0,n.unref)(H))])])])]),(0,n.createVNode)((0,n.unref)(F.Z),{team:(0,n.unref)(t).team,show:(0,n.unref)(t).show},null,8,["team","show"])])])],64)}}}}}]);
//# sourceMappingURL=6882.js.map