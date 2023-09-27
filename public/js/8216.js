/*! For license information please see 8216.js.LICENSE.txt */
"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[8216],{97849:(e,t,n)=>{n.d(t,{Z:()=>u});var r=n(70821),o={class:"flex justify-between my-3"},l={class:"mb-4"},a={class:"text-3xl font-semibold"},i={class:"flex flex-wrap-reverse justify-end gap-2"},s=(0,r.createElementVNode)("button",{class:"px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"},"Admin Settings ",-1);const c={},u=(0,n(83744).Z)(c,[["render",function(e,t){var n=(0,r.resolveComponent)("Link");return(0,r.openBlock)(),(0,r.createElementBlock)("header",null,[(0,r.createElementVNode)("div",o,[(0,r.createElementVNode)("div",l,[(0,r.createElementVNode)("h1",a,[(0,r.renderSlot)(e.$slots,"default")])]),(0,r.createElementVNode)("div",null,[(0,r.createElementVNode)("div",i,[(0,r.createVNode)(n,{href:"/admin/settings"},{default:(0,r.withCtx)((function(){return[s]})),_:1})])])])])}]])},78216:(e,t,n)=>{n.r(t),n.d(t,{default:()=>le});var r=n(70821),o=n(40191),l=n(53489),a=n(90771),i=n(664),s=n(97849);function c(e){return c="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},c(e)}function u(){u=function(){return t};var e,t={},n=Object.prototype,r=n.hasOwnProperty,o=Object.defineProperty||function(e,t,n){e[t]=n.value},l="function"==typeof Symbol?Symbol:{},a=l.iterator||"@@iterator",i=l.asyncIterator||"@@asyncIterator",s=l.toStringTag||"@@toStringTag";function d(e,t,n){return Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{d({},"")}catch(e){d=function(e,t,n){return e[t]=n}}function p(e,t,n,r){var l=t&&t.prototype instanceof E?t:E,a=Object.create(l.prototype),i=new A(r||[]);return o(a,"_invoke",{value:B(e,n,i)}),a}function m(e,t,n){try{return{type:"normal",arg:e.call(t,n)}}catch(e){return{type:"throw",arg:e}}}t.wrap=p;var f="suspendedStart",h="suspendedYield",y="executing",g="completed",v={};function E(){}function N(){}function V(){}var b={};d(b,a,(function(){return this}));var k=Object.getPrototypeOf,w=k&&k(k(M([])));w&&w!==n&&r.call(w,a)&&(b=w);var x=V.prototype=E.prototype=Object.create(b);function S(e){["next","throw","return"].forEach((function(t){d(e,t,(function(e){return this._invoke(t,e)}))}))}function D(e,t){function n(o,l,a,i){var s=m(e[o],e,l);if("throw"!==s.type){var u=s.arg,d=u.value;return d&&"object"==c(d)&&r.call(d,"__await")?t.resolve(d.__await).then((function(e){n("next",e,a,i)}),(function(e){n("throw",e,a,i)})):t.resolve(d).then((function(e){u.value=e,a(u)}),(function(e){return n("throw",e,a,i)}))}i(s.arg)}var l;o(this,"_invoke",{value:function(e,r){function o(){return new t((function(t,o){n(e,r,t,o)}))}return l=l?l.then(o,o):o()}})}function B(t,n,r){var o=f;return function(l,a){if(o===y)throw new Error("Generator is already running");if(o===g){if("throw"===l)throw a;return{value:e,done:!0}}for(r.method=l,r.arg=a;;){var i=r.delegate;if(i){var s=C(i,r);if(s){if(s===v)continue;return s}}if("next"===r.method)r.sent=r._sent=r.arg;else if("throw"===r.method){if(o===f)throw o=g,r.arg;r.dispatchException(r.arg)}else"return"===r.method&&r.abrupt("return",r.arg);o=y;var c=m(t,n,r);if("normal"===c.type){if(o=r.done?g:h,c.arg===v)continue;return{value:c.arg,done:r.done}}"throw"===c.type&&(o=g,r.method="throw",r.arg=c.arg)}}}function C(t,n){var r=n.method,o=t.iterator[r];if(o===e)return n.delegate=null,"throw"===r&&t.iterator.return&&(n.method="return",n.arg=e,C(t,n),"throw"===n.method)||"return"!==r&&(n.method="throw",n.arg=new TypeError("The iterator does not provide a '"+r+"' method")),v;var l=m(o,t.iterator,n.arg);if("throw"===l.type)return n.method="throw",n.arg=l.arg,n.delegate=null,v;var a=l.arg;return a?a.done?(n[t.resultName]=a.value,n.next=t.nextLoc,"return"!==n.method&&(n.method="next",n.arg=e),n.delegate=null,v):a:(n.method="throw",n.arg=new TypeError("iterator result is not an object"),n.delegate=null,v)}function L(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function R(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function A(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(L,this),this.reset(!0)}function M(t){if(t||""===t){var n=t[a];if(n)return n.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var o=-1,l=function n(){for(;++o<t.length;)if(r.call(t,o))return n.value=t[o],n.done=!1,n;return n.value=e,n.done=!0,n};return l.next=l}}throw new TypeError(c(t)+" is not iterable")}return N.prototype=V,o(x,"constructor",{value:V,configurable:!0}),o(V,"constructor",{value:N,configurable:!0}),N.displayName=d(V,s,"GeneratorFunction"),t.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===N||"GeneratorFunction"===(t.displayName||t.name))},t.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,V):(e.__proto__=V,d(e,s,"GeneratorFunction")),e.prototype=Object.create(x),e},t.awrap=function(e){return{__await:e}},S(D.prototype),d(D.prototype,i,(function(){return this})),t.AsyncIterator=D,t.async=function(e,n,r,o,l){void 0===l&&(l=Promise);var a=new D(p(e,n,r,o),l);return t.isGeneratorFunction(n)?a:a.next().then((function(e){return e.done?e.value:a.next()}))},S(x),d(x,s,"Generator"),d(x,a,(function(){return this})),d(x,"toString",(function(){return"[object Generator]"})),t.keys=function(e){var t=Object(e),n=[];for(var r in t)n.push(r);return n.reverse(),function e(){for(;n.length;){var r=n.pop();if(r in t)return e.value=r,e.done=!1,e}return e.done=!0,e}},t.values=M,A.prototype={constructor:A,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=e,this.done=!1,this.delegate=null,this.method="next",this.arg=e,this.tryEntries.forEach(R),!t)for(var n in this)"t"===n.charAt(0)&&r.call(this,n)&&!isNaN(+n.slice(1))&&(this[n]=e)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var n=this;function o(r,o){return i.type="throw",i.arg=t,n.next=r,o&&(n.method="next",n.arg=e),!!o}for(var l=this.tryEntries.length-1;l>=0;--l){var a=this.tryEntries[l],i=a.completion;if("root"===a.tryLoc)return o("end");if(a.tryLoc<=this.prev){var s=r.call(a,"catchLoc"),c=r.call(a,"finallyLoc");if(s&&c){if(this.prev<a.catchLoc)return o(a.catchLoc,!0);if(this.prev<a.finallyLoc)return o(a.finallyLoc)}else if(s){if(this.prev<a.catchLoc)return o(a.catchLoc,!0)}else{if(!c)throw new Error("try statement without catch or finally");if(this.prev<a.finallyLoc)return o(a.finallyLoc)}}}},abrupt:function(e,t){for(var n=this.tryEntries.length-1;n>=0;--n){var o=this.tryEntries[n];if(o.tryLoc<=this.prev&&r.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var l=o;break}}l&&("break"===e||"continue"===e)&&l.tryLoc<=t&&t<=l.finallyLoc&&(l=null);var a=l?l.completion:{};return a.type=e,a.arg=t,l?(this.method="next",this.next=l.finallyLoc,v):this.complete(a)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),v},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var n=this.tryEntries[t];if(n.finallyLoc===e)return this.complete(n.completion,n.afterLoc),R(n),v}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var n=this.tryEntries[t];if(n.tryLoc===e){var r=n.completion;if("throw"===r.type){var o=r.arg;R(n)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(t,n,r){return this.delegate={iterator:M(t),resultName:n,nextLoc:r},"next"===this.method&&(this.arg=e),v}},t}function d(e,t,n,r,o,l,a){try{var i=e[l](a),s=i.value}catch(e){return void n(e)}i.done?t(s):Promise.resolve(s).then(r,o)}function p(e){return function(){var t=this,n=arguments;return new Promise((function(r,o){var l=e.apply(t,n);function a(e){d(l,r,o,a,i,"next",e)}function i(e){d(l,r,o,a,i,"throw",e)}a(void 0)}))}}var m={class:"place-self-center flex flex-col gap-y-3"},f={id:"topDiv",class:"bg-white text-black p-5 mb-10"},h={class:"flex justify-between"},y={class:""},g={class:"font-semibold"},v=["onClick"],E=["onClick"],N=["onClick"],V={key:0,class:""},b={key:1,class:"mb-8"},k=(0,r.createElementVNode)("div",{class:"py-3 px-4 mb-4 bg-orange-800 text-white rounded"},"MistServer needs to be authenticated",-1),w=(0,r.createElementVNode)("div",{class:"font-semibold text-2xl px-2"}," Connect to the MistServer ",-1),x=(0,r.createElementVNode)("div",{class:"my-3 pl-2 text-sm w-1/2"},'If the MistServer Status will either be OK, CHALL, NOACC or ACC_MADE. If it\'s "CHALL" then you need to re-authenticate with the username and password.',-1),S=(0,r.createElementVNode)("div",{class:"mt-2"},"Challenge:",-1),D=(0,r.createElementVNode)("div",{class:"font-semibold mt-2"},"MistServer Username:",-1),B=(0,r.createElementVNode)("div",{class:"font-semibold mt-2"},"MistServer Password:",-1),C=(0,r.createElementVNode)("div",{class:"mb-4 w-1/2 text-sm"},[(0,r.createTextVNode)(" Credit to Jeff Mott for his work on a pure JS implementation of the MD5 algorithm. You can find the npm package "),(0,r.createElementVNode)("a",{href:"https://www.npmjs.com/package/md5",target:"_blank",class:"text-blue-800 hover:text-gray-500"},"here.")],-1),L=["onClick"],R={key:2,class:"mb-8"},A=(0,r.createElementVNode)("div",{class:"py-3 px-4 mb-4 bg-green-900 text-white rounded"},"MistServer is connected",-1),M={class:"grid grid-cols-1 md:grid-cols-3"},P={class:"col-span-1"},F={class:"flex flex-col space-y-2"},_={class:"md:col-span-2 pl-6"},O=(0,r.createElementVNode)("div",{class:"mt-2 text-xs uppercase"},"Returned data:",-1),j={key:0},T=(0,r.createElementVNode)("thead",null,[(0,r.createElementVNode)("td"),(0,r.createElementVNode)("td"),(0,r.createElementVNode)("td"),(0,r.createElementVNode)("td"),(0,r.createElementVNode)("td")],-1),G={key:1},U=(0,r.createElementVNode)("div",{class:"mt-2 font-semibold"},"CPU",-1),I=(0,r.createElementVNode)("div",{class:"mt-2 font-semibold"},"Load",-1),z=(0,r.createElementVNode)("div",{class:"mt-2 font-semibold"},"Mem",-1),q={key:2},H={key:3},K=(0,r.createElementVNode)("td",null,"time",-1),Z={key:4},Y=(0,r.createElementVNode)("thead",{class:"font-semibold mb-2"},[(0,r.createElementVNode)("td",null,"Stream Name")],-1),$={key:5},J={key:6},X={key:7},Q=(0,r.createElementVNode)("div",{class:"font-semibold my-2"},"Push a Stream:",-1),W={class:""},ee=(0,r.createElementVNode)("label",{for:"streamName",class:"mb-1"},"Choose stream:",-1),te=(0,r.createElementVNode)("label",{for:"rtmpDestination",class:"mb-1"},"Set destination:",-1),ne=["onClick"],re=(0,r.createElementVNode)("video",{src:"https://streams.not.tv/vmixsource01%2bspring.mp4",class:"mt-20 w-96",controls:"",autoplay:""},null,-1),oe=(0,r.createElementVNode)("br",null,null,-1);const le={__name:"MistServerApi",props:{apiReturn:Object,message:(0,r.ref)(String),mistNewHashedPassword:(0,r.ref)(String)},setup:function(e){var t=(0,o.q)(),c=(0,l.o)(),d=(0,a.L)();t.apiActiveStreams=null,t.mistStatus=!1,d.currentPage="adminSettings",d.showFlashMessage=!0,(0,r.onMounted)((function(){t.makeVideoTopRight(),d.isMobile&&(t.ottClass="ottClose",t.ott=0),document.getElementById("topDiv").scrollIntoView()}));var le=(0,r.reactive)({challenge:t.challenge,status:t.status,username:"",password:""});t.mistUsername="nottvadmin",le.password="20y$!PwX12S",t.mistStatus=!0,t.mistDisplayPushForm=!1;(0,r.ref)("");var ae=n(2568),ie="https://mist.not.tv/";function se(){return ce.apply(this,arguments)}function ce(){return(ce=p(u().mark((function e(){return u().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,axios.get(ie).then((function(e){t.apiRequest=e.data,t.challenge=t.apiRequest.authorize.challenge,t.status=t.apiRequest.authorize.status})).catch((function(e){console.log(e)}));case 2:console.log("get API");case 3:case"end":return e.stop()}}),e)})))).apply(this,arguments)}function ue(){return de.apply(this,arguments)}function de(){return(de=p(u().mark((function e(){var n,r;return u().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return n=ae(le.password),console.log("Hashed password: "+n),r=ae(n+t.challenge),t.mistPassword=r,console.log("Final hashed password: "+r),e.next=7,axios.get(ie+"?command=%7B%0A%22authorize%22%3A%20%7B%0A%22username%22%3A%20%22"+t.mistUsername+"%22,%0A%22password%22%3A%20%22"+r+"%22%0A%7D%0A%7D").then((function(e){t.apiRequest=e.data,t.challenge=t.apiRequest.authorize.challenge,t.status=t.apiRequest.authorize.status,console.log(e.data)})).catch((function(e){console.log(e)}));case 7:console.log("mistServer API authorization sent.");case 8:case"end":return e.stop()}}),e)})))).apply(this,arguments)}var pe=function(){t.mistDisplay="updates";Ne('"update": true')},me=function(){t.mistDisplay="capabilities";Ne("%22capabilities%22%3A%20true")},fe=function(){t.mistDisplay="totals";Ne('"totals": {}')},he=function(){t.mistDisplay="active_streams";Ne('"active_streams": true')},ye=function(){t.mistDisplay="clients";Ne('"clients": {}')},ge=function(){t.mistDisplay="log";Ne('"log": {}')},ve=function(){t.mistDisplay="log";Ne('"clearstatlog": true')},Ee=function(){t.mistDisplay="recordings";Ne('"path": "/media/upload"')};function Ne(e){return Ve.apply(this,arguments)}function Ve(){return(Ve=p(u().mark((function e(n){var r;return u().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.mistStatus=!0,t.mistDisplayPushForm=!1,r="%7B%20%22authorize%22%3A%20%7B%0A%20%20%20%20%22username%22%3A%20%22"+t.mistUsername+"%22,%0A%20%20%20%20%22password%22%3A%20%22"+t.mistPassword+"%22%0A%20%20%20%7D,%0A%20"+n+"%0A%0A%7D",e.next=5,axios.get(ie+"?command="+r).then((function(e){t.apiResponse=e.data,t.challenge=t.apiResponse.authorize.challenge,t.status=t.apiResponse.authorize.status})).catch((function(e){console.log(e)}));case 5:console.log("mistServer API request sent.");case 6:case"end":return e.stop()}}),e)})))).apply(this,arguments)}function be(){he(),t.mistStatus=!1,t.mistDisplayPushForm=!0}function ke(){var e="%7B%20%22push_start%22%3A%20%7B%22stream%22%3A%22"+c.streamName+"%22,%22target%22%3A%22"+c.rtmpDestination+"%22%7D%7D";Ne(e),t.mistStatus=!1,t.mistDisplayPushForm=!0,console.log("stream push started: "+e)}function we(){console.log("adding new stream");var e="%7B%22addstream%22%3A%7B%22streamname%22%3A%7B%22source%22%3A%22push%3A%2F%2F%22%7D%7D%7D";Ne(e),t.mistStatus=!0,t.mistDisplayPushForm=!1,console.log("stream added: "+e)}return function(e,n){var o=(0,r.resolveComponent)("Head");return(0,r.openBlock)(),(0,r.createElementBlock)(r.Fragment,null,[(0,r.createVNode)(o,{title:"MistServer API"}),(0,r.createElementVNode)("div",m,[(0,r.createElementVNode)("div",f,[(0,r.unref)(d).showFlashMessage?((0,r.openBlock)(),(0,r.createBlock)(i.Z,{key:0,flash:e.$page.props.flash},null,8,["flash"])):(0,r.createCommentVNode)("",!0),(0,r.createVNode)(s.Z,null,{default:(0,r.withCtx)((function(){return[(0,r.createTextVNode)("MistServer API")]})),_:1}),(0,r.createElementVNode)("div",h,[(0,r.createElementVNode)("div",null,[(0,r.createElementVNode)("div",y,[(0,r.createTextVNode)("Status: "),(0,r.createElementVNode)("span",g,(0,r.toDisplayString)((0,r.unref)(t).status),1)]),(0,r.createElementVNode)("button",{class:"ml-2 py-2 my-2 px-4 text-white bg-orange-800 hover:bg-orange-500 mr-2 rounded-xl",onClick:(0,r.withModifiers)(se,["prevent"])}," Get Status ",8,v),"OK"===(0,r.unref)(t).status?((0,r.openBlock)(),(0,r.createElementBlock)("button",{key:0,class:"ml-2 py-2 my-2 px-4 text-white bg-blue-800 hover:bg-blue-500 mr-2 rounded-xl",onClick:(0,r.withModifiers)(be,["prevent"])}," Start Push ",8,E)):(0,r.createCommentVNode)("",!0),"OK"===(0,r.unref)(t).status?((0,r.openBlock)(),(0,r.createElementBlock)("button",{key:1,class:"ml-2 py-2 my-2 px-4 text-white bg-blue-800 hover:bg-blue-500 mr-2 rounded-xl",onClick:(0,r.withModifiers)(we,["prevent"])}," Add Stream ",8,N)):(0,r.createCommentVNode)("",!0)]),"OK"===(0,r.unref)(t).status?((0,r.openBlock)(),(0,r.createElementBlock)("div",V)):(0,r.createCommentVNode)("",!0)]),"CHALL"===(0,r.unref)(t).status?((0,r.openBlock)(),(0,r.createElementBlock)("div",b,[k,w,x,(0,r.createElementVNode)("form",{onSubmit:n[3]||(n[3]=(0,r.withModifiers)((function(){}),["prevent"])),class:"mt-2 pl-2"},[S,(0,r.withDirectives)((0,r.createElementVNode)("input",{type:"text",name:"challenge",id:"challenge","onUpdate:modelValue":n[0]||(n[0]=function(e){return(0,r.unref)(t).challenge=e}),disabled:""},null,512),[[r.vModelText,(0,r.unref)(t).challenge]]),D,(0,r.withDirectives)((0,r.createElementVNode)("input",{class:"mb-2",type:"text",name:"username","onUpdate:modelValue":n[1]||(n[1]=function(e){return(0,r.unref)(t).mistUsername=e})},null,512),[[r.vModelText,(0,r.unref)(t).mistUsername]]),B,(0,r.withDirectives)((0,r.createElementVNode)("input",{type:"password",name:"password","onUpdate:modelValue":n[2]||(n[2]=function(e){return(0,r.unref)(le).password=e})},null,512),[[r.vModelText,(0,r.unref)(le).password]]),C,(0,r.createElementVNode)("button",{class:"ml-2 py-2 px-4 text-white bg-green-800 hover:bg-green-500 rounded-xl",onClick:(0,r.withModifiers)(ue,["prevent"])}," Authenticate ",8,L)],32)])):(0,r.createCommentVNode)("",!0),"OK"===(0,r.unref)(t).status?((0,r.openBlock)(),(0,r.createElementBlock)("div",R,[A,(0,r.createElementVNode)("div",M,[(0,r.createElementVNode)("div",P,[(0,r.createElementVNode)("div",F,[(0,r.createElementVNode)("button",{class:"ml-2 py-2 px-4 text-white bg-blue-800 hover:bg-blue-500 rounded-xl",onClick:n[4]||(n[4]=(0,r.withModifiers)((function(){return(0,r.unref)(pe)&&(0,r.unref)(pe).apply(void 0,arguments)}),["prevent"]))}," Check for Updates "),(0,r.createElementVNode)("button",{class:"ml-2 py-2 px-4 text-white bg-blue-800 hover:bg-blue-500 rounded-xl",onClick:n[5]||(n[5]=(0,r.withModifiers)((function(){return(0,r.unref)(me)&&(0,r.unref)(me).apply(void 0,arguments)}),["prevent"]))}," Get Server Capabilities "),(0,r.createElementVNode)("button",{class:"ml-2 py-2 px-4 text-white bg-blue-800 hover:bg-blue-500 mr-2 rounded-xl",onClick:n[6]||(n[6]=(0,r.withModifiers)((function(){return(0,r.unref)(fe)&&(0,r.unref)(fe).apply(void 0,arguments)}),["prevent"]))}," Get Totals "),(0,r.createElementVNode)("button",{class:"ml-2 py-2 px-4 text-white bg-blue-800 hover:bg-blue-500 mr-2 rounded-xl",onClick:n[7]||(n[7]=(0,r.withModifiers)((function(){return(0,r.unref)(ye)&&(0,r.unref)(ye).apply(void 0,arguments)}),["prevent"]))}," Get Clients "),(0,r.createElementVNode)("button",{class:"ml-2 py-2 px-4 text-white bg-blue-800 hover:bg-blue-500 rounded-xl",onClick:n[8]||(n[8]=(0,r.withModifiers)((function(){return(0,r.unref)(he)&&(0,r.unref)(he).apply(void 0,arguments)}),["prevent"]))}," Get Active Streams "),(0,r.createElementVNode)("button",{class:"ml-2 py-2 px-4 text-white bg-blue-800 hover:bg-blue-500 rounded-xl",onClick:n[9]||(n[9]=(0,r.withModifiers)((function(){return(0,r.unref)(ge)&&(0,r.unref)(ge).apply(void 0,arguments)}),["prevent"]))}," Get Log "),(0,r.createElementVNode)("button",{class:"ml-2 py-2 px-4 text-white bg-orange-800 hover:bg-orange-500 rounded-xl",onClick:n[10]||(n[10]=(0,r.withModifiers)((function(){return(0,r.unref)(ve)&&(0,r.unref)(ve).apply(void 0,arguments)}),["prevent"]))}," Clear Log "),(0,r.createElementVNode)("button",{class:"ml-2 py-2 px-4 text-white bg-blue-800 hover:bg-blue-500 rounded-xl",onClick:n[11]||(n[11]=(0,r.withModifiers)((function(){return(0,r.unref)(Ee)&&(0,r.unref)(Ee).apply(void 0,arguments)}),["prevent"]))}," Recordings ")])]),(0,r.createElementVNode)("div",_,[O,"updates"===(0,r.unref)(t).mistDisplay?((0,r.openBlock)(),(0,r.createElementBlock)("div",j,[(0,r.createElementVNode)("table",null,[T,((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)((0,r.unref)(t).apiResponse.update,(function(e){return(0,r.openBlock)(),(0,r.createElementBlock)("tr",{key:e.item},[(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[0]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[1]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[2]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[3]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[4]),1)])})),128))])])):(0,r.createCommentVNode)("",!0),"capabilities"===(0,r.unref)(t).mistDisplay?((0,r.openBlock)(),(0,r.createElementBlock)("div",G,[U,(0,r.createElementVNode)("table",null,[((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)((0,r.unref)(t).apiResponse.capabilities.cpu[0],(function(e,t){return(0,r.openBlock)(),(0,r.createElementBlock)("tr",{key:t},[(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(t),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e),1)])})),128))]),I,(0,r.createElementVNode)("table",null,[((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)((0,r.unref)(t).apiResponse.capabilities.load,(function(e,t){return(0,r.openBlock)(),(0,r.createElementBlock)("tr",{key:t},[(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(t),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e),1)])})),128))]),z,(0,r.createElementVNode)("table",null,[((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)((0,r.unref)(t).apiResponse.capabilities.mem,(function(e,t){return(0,r.openBlock)(),(0,r.createElementBlock)("tr",{key:t},[(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(t),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e),1)])})),128))])])):(0,r.createCommentVNode)("",!0),"totals"===(0,r.unref)(t).mistDisplay?((0,r.openBlock)(),(0,r.createElementBlock)("div",q,[(0,r.createElementVNode)("table",null,[(0,r.createElementVNode)("thead",null,[(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.totals.fields[0]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.totals.fields[1]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.totals.fields[2]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.totals.fields[3]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.totals.fields[4]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.totals.fields[5]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.totals.fields[6]),1)]),((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)((0,r.unref)(t).apiResponse.totals.data.slice().reverse(),(function(e){return(0,r.openBlock)(),(0,r.createElementBlock)("tr",{key:e.item},[(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[0]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[1]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[2]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[3]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[4]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[5]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[6]),1)])})),128))])])):(0,r.createCommentVNode)("",!0),"clients"===(0,r.unref)(t).mistDisplay?((0,r.openBlock)(),(0,r.createElementBlock)("div",H,[(0,r.createElementVNode)("table",null,[(0,r.createElementVNode)("thead",null,[(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.clients.fields[0]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.clients.fields[1]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.clients.fields[2]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.clients.fields[3]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.clients.fields[4]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.clients.fields[5]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.clients.fields[6]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.clients.fields[7]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.clients.fields[8]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.clients.fields[9]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.clients.fields[10]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.clients.fields[11]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.clients.fields[12]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)((0,r.unref)(t).apiResponse.clients.fields[13]),1),K]),((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)((0,r.unref)(t).apiResponse.clients.data,(function(e){return(0,r.openBlock)(),(0,r.createElementBlock)("tr",{key:e.item},[(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[0]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[1]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[2]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[3]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[4]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[5]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[6]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[7]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[8]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[9]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[10]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[11]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[12]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[13]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[14]),1)])})),128))])])):(0,r.createCommentVNode)("",!0),"active_streams"===(0,r.unref)(t).mistDisplay?((0,r.openBlock)(),(0,r.createElementBlock)("div",Z,[(0,r.createElementVNode)("table",null,[Y,((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)((0,r.unref)(t).apiResponse.active_streams,(function(e){return(0,r.openBlock)(),(0,r.createElementBlock)("tr",{key:e.item},[(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e),1)])})),128))])])):(0,r.createCommentVNode)("",!0),"log"===(0,r.unref)(t).mistDisplay?((0,r.openBlock)(),(0,r.createElementBlock)("div",$,[(0,r.createElementVNode)("table",null,[((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)((0,r.unref)(t).apiResponse.log,(function(e){return(0,r.openBlock)(),(0,r.createElementBlock)("tr",{key:e.item},[(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[0]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[1]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[2]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[3]),1)])})),128))])])):(0,r.createCommentVNode)("",!0),"recordings"===(0,r.unref)(t).mistDisplay?((0,r.openBlock)(),(0,r.createElementBlock)("div",J,[(0,r.createElementVNode)("table",null,[((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)((0,r.unref)(t).apiResponse.log,(function(e){return(0,r.openBlock)(),(0,r.createElementBlock)("tr",{key:e.item},[(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[0]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[1]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[2]),1),(0,r.createElementVNode)("td",null,(0,r.toDisplayString)(e[3]),1)])})),128))])])):(0,r.createCommentVNode)("",!0),(0,r.unref)(t).mistDisplayPushForm?((0,r.openBlock)(),(0,r.createElementBlock)("div",X,[Q,(0,r.createElementVNode)("div",W,[ee,(0,r.withDirectives)((0,r.createElementVNode)("select",{id:"streamName","onUpdate:modelValue":n[12]||(n[12]=function(e){return(0,r.unref)(c).streamName=e}),class:"w-full mb-2"},[((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)((0,r.unref)(t).apiResponse.active_streams,(function(e){return(0,r.openBlock)(),(0,r.createElementBlock)("option",{key:e},(0,r.toDisplayString)(e),1)})),128))],512),[[r.vModelSelect,(0,r.unref)(c).streamName]]),te,(0,r.withDirectives)((0,r.createElementVNode)("input",{type:"text",id:"rtmpDestination","onUpdate:modelValue":n[13]||(n[13]=function(e){return(0,r.unref)(c).rtmpDestination=e}),placeholder:"RTMP destination...",class:"w-full my-2"},null,512),[[r.vModelText,(0,r.unref)(c).rtmpDestination]]),(0,r.createElementVNode)("button",{onClick:(0,r.withModifiers)(ke,["prevent"]),class:"ml-2 py-2 px-4 text-white bg-green-800 hover:bg-green-500 rounded-xl"}," Push ",8,ne),re,oe,(0,r.createTextVNode)('Preview video is hardcoded to "vmixsource01+spring" ')])])):(0,r.createCommentVNode)("",!0)])])])):(0,r.createCommentVNode)("",!0)])])],64)}}}}}]);
//# sourceMappingURL=8216.js.map