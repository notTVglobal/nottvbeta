/*! For license information please see 8735.js.LICENSE.txt */
"use strict";(self.webpackChunknottvbeta=self.webpackChunknottvbeta||[]).push([[8735,5045],{85045:(e,t,r)=>{r.r(t),r.d(t,{useTeamStore:()=>x});var n=r(38839),o=r(74353),a=r.n(o),i=r(48018),s=r.n(i),c=r(88569),l=r.n(c),u=r(20715),d=r(7447),m=void 0;function f(e){return f="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},f(e)}function h(){h=function(){return t};var e,t={},r=Object.prototype,n=r.hasOwnProperty,o=Object.defineProperty||function(e,t,r){e[t]=r.value},a="function"==typeof Symbol?Symbol:{},i=a.iterator||"@@iterator",s=a.asyncIterator||"@@asyncIterator",c=a.toStringTag||"@@toStringTag";function l(e,t,r){return Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{l({},"")}catch(e){l=function(e,t,r){return e[t]=r}}function u(e,t,r,n){var a=t&&t.prototype instanceof b?t:b,i=Object.create(a.prototype),s=new O(n||[]);return o(i,"_invoke",{value:V(e,r,s)}),i}function d(e,t,r){try{return{type:"normal",arg:e.call(t,r)}}catch(e){return{type:"throw",arg:e}}}t.wrap=u;var m="suspendedStart",p="suspendedYield",g="executing",v="completed",y={};function b(){}function w(){}function x(){}var k={};l(k,i,(function(){return this}));var E=Object.getPrototypeOf,N=E&&E(E(T([])));N&&N!==r&&n.call(N,i)&&(k=N);var S=x.prototype=b.prototype=Object.create(k);function _(e){["next","throw","return"].forEach((function(t){l(e,t,(function(e){return this._invoke(t,e)}))}))}function B(e,t){function r(o,a,i,s){var c=d(e[o],e,a);if("throw"!==c.type){var l=c.arg,u=l.value;return u&&"object"==f(u)&&n.call(u,"__await")?t.resolve(u.__await).then((function(e){r("next",e,i,s)}),(function(e){r("throw",e,i,s)})):t.resolve(u).then((function(e){l.value=e,i(l)}),(function(e){return r("throw",e,i,s)}))}s(c.arg)}var a;o(this,"_invoke",{value:function(e,n){function o(){return new t((function(t,o){r(e,n,t,o)}))}return a=a?a.then(o,o):o()}})}function V(t,r,n){var o=m;return function(a,i){if(o===g)throw Error("Generator is already running");if(o===v){if("throw"===a)throw i;return{value:e,done:!0}}for(n.method=a,n.arg=i;;){var s=n.delegate;if(s){var c=L(s,n);if(c){if(c===y)continue;return c}}if("next"===n.method)n.sent=n._sent=n.arg;else if("throw"===n.method){if(o===m)throw o=v,n.arg;n.dispatchException(n.arg)}else"return"===n.method&&n.abrupt("return",n.arg);o=g;var l=d(t,r,n);if("normal"===l.type){if(o=n.done?v:p,l.arg===y)continue;return{value:l.arg,done:n.done}}"throw"===l.type&&(o=v,n.method="throw",n.arg=l.arg)}}}function L(t,r){var n=r.method,o=t.iterator[n];if(o===e)return r.delegate=null,"throw"===n&&t.iterator.return&&(r.method="return",r.arg=e,L(t,r),"throw"===r.method)||"return"!==n&&(r.method="throw",r.arg=new TypeError("The iterator does not provide a '"+n+"' method")),y;var a=d(o,t.iterator,r.arg);if("throw"===a.type)return r.method="throw",r.arg=a.arg,r.delegate=null,y;var i=a.arg;return i?i.done?(r[t.resultName]=i.value,r.next=t.nextLoc,"return"!==r.method&&(r.method="next",r.arg=e),r.delegate=null,y):i:(r.method="throw",r.arg=new TypeError("iterator result is not an object"),r.delegate=null,y)}function D(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function C(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function O(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(D,this),this.reset(!0)}function T(t){if(t||""===t){var r=t[i];if(r)return r.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var o=-1,a=function r(){for(;++o<t.length;)if(n.call(t,o))return r.value=t[o],r.done=!1,r;return r.value=e,r.done=!0,r};return a.next=a}}throw new TypeError(f(t)+" is not iterable")}return w.prototype=x,o(S,"constructor",{value:x,configurable:!0}),o(x,"constructor",{value:w,configurable:!0}),w.displayName=l(x,c,"GeneratorFunction"),t.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===w||"GeneratorFunction"===(t.displayName||t.name))},t.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,x):(e.__proto__=x,l(e,c,"GeneratorFunction")),e.prototype=Object.create(S),e},t.awrap=function(e){return{__await:e}},_(B.prototype),l(B.prototype,s,(function(){return this})),t.AsyncIterator=B,t.async=function(e,r,n,o,a){void 0===a&&(a=Promise);var i=new B(u(e,r,n,o),a);return t.isGeneratorFunction(r)?i:i.next().then((function(e){return e.done?e.value:i.next()}))},_(S),l(S,c,"Generator"),l(S,i,(function(){return this})),l(S,"toString",(function(){return"[object Generator]"})),t.keys=function(e){var t=Object(e),r=[];for(var n in t)r.push(n);return r.reverse(),function e(){for(;r.length;){var n=r.pop();if(n in t)return e.value=n,e.done=!1,e}return e.done=!0,e}},t.values=T,O.prototype={constructor:O,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=e,this.done=!1,this.delegate=null,this.method="next",this.arg=e,this.tryEntries.forEach(C),!t)for(var r in this)"t"===r.charAt(0)&&n.call(this,r)&&!isNaN(+r.slice(1))&&(this[r]=e)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var r=this;function o(n,o){return s.type="throw",s.arg=t,r.next=n,o&&(r.method="next",r.arg=e),!!o}for(var a=this.tryEntries.length-1;a>=0;--a){var i=this.tryEntries[a],s=i.completion;if("root"===i.tryLoc)return o("end");if(i.tryLoc<=this.prev){var c=n.call(i,"catchLoc"),l=n.call(i,"finallyLoc");if(c&&l){if(this.prev<i.catchLoc)return o(i.catchLoc,!0);if(this.prev<i.finallyLoc)return o(i.finallyLoc)}else if(c){if(this.prev<i.catchLoc)return o(i.catchLoc,!0)}else{if(!l)throw Error("try statement without catch or finally");if(this.prev<i.finallyLoc)return o(i.finallyLoc)}}}},abrupt:function(e,t){for(var r=this.tryEntries.length-1;r>=0;--r){var o=this.tryEntries[r];if(o.tryLoc<=this.prev&&n.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var a=o;break}}a&&("break"===e||"continue"===e)&&a.tryLoc<=t&&t<=a.finallyLoc&&(a=null);var i=a?a.completion:{};return i.type=e,i.arg=t,a?(this.method="next",this.next=a.finallyLoc,y):this.complete(i)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),y},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.finallyLoc===e)return this.complete(r.completion,r.afterLoc),C(r),y}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.tryLoc===e){var n=r.completion;if("throw"===n.type){var o=n.arg;C(r)}return o}}throw Error("illegal catch attempt")},delegateYield:function(t,r,n){return this.delegate={iterator:T(t),resultName:r,nextLoc:n},"next"===this.method&&(this.arg=e),y}},t}function p(e,t,r,n,o,a,i){try{var s=e[a](i),c=s.value}catch(e){return void r(e)}s.done?t(c):Promise.resolve(c).then(n,o)}function g(e){return function(){var t=this,r=arguments;return new Promise((function(n,o){var a=e.apply(t,r);function i(e){p(a,n,o,i,s,"next",e)}function s(e){p(a,n,o,i,s,"throw",e)}i(void 0)}))}}function v(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function y(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?v(Object(r),!0).forEach((function(t){b(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):v(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function b(e,t,r){return(t=function(e){var t=function(e,t){if("object"!=f(e)||!e)return e;var r=e[Symbol.toPrimitive];if(void 0!==r){var n=r.call(e,t||"default");if("object"!=f(n))return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(e,"string");return"symbol"==f(t)?t:t+""}(t))in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}a().extend(s()),a().extend(l());var w=function(){return{team:{},shows:{},contributors:{},members:{},managers:{},teamOwner:[],teamLeader:[],can:{},id:0,name:"",description:"",slug:"",totalSpots:"",memberSpots:"",activeShow:[],activeEpisode:[],showModal:Boolean,confirmDialog:!1,confirmManagerDialog:!1,selectedManagerName:"",selectedManagerId:0,addManager:!1,removeManager:!1,deleteMemberName:"",deleteMemberId:0,noteEdit:0,note:"",noteKey:0,saveNoteProcessing:Boolean,goLiveDisplay:!1,openComponent:"teamShows",nextBroadcastLoaded:{scheduleIndexId:null,broadcastDate:null,broadcastDetails:{},type:"",image:null,category:null,subCategory:null,slug:null,name:null,description:null},nextBroadcastZoomLink:""}},x=(0,n.nY)("teamStore",{state:w,actions:{reset:function(){Object.assign(this,w())},initializeTeam:function(e){console.log("incoming team: ",e);var t=(0,u.useUserStore)();if(Array.isArray(e.nextBroadcast)&&e.nextBroadcast.length>0){var r=e.nextBroadcast[0];r.broadcastDetails&&(this.nextBroadcastLoaded=r,r.broadcastDetails.zoomLink&&(this.nextBroadcastZoomLink=r.broadcastDetails.zoomLink),e.nextBroadcast=e.nextBroadcast.map((function(e){return y(y({},e),{},{broadcastDate:t.convertUtcToUserTimezone(e.broadcastDate)})})))}else this.nextBroadcastLoaded=null,this.nextBroadcastZoomLink=null;this.team=e||{}},initializeShows:function(e){this.shows=e||{}},initializeContributors:function(e){this.contributors=e||{}},setCan:function(e){this.can=e||{}},setActiveTeam:function(e){this.team.id=e.id,this.team.name=e.name,this.team.description=e.description,this.team.slug=e.slug,this.team.members=e.members,this.team.managers=e.managers,this.team.totalSpots=e.totalSpots,this.memberSpots=e.memberSpots},setActiveShow:function(e){this.activeShow=e},setActiveEpisode:function(e){this.activeShow=e},addMember:function(e){this.team.members.push(e)},removeMember:function(e){this.team.members=this.team.members.filter((function(t){return t.id!==e}))},updateCreatorTeams:function(e,t){var r=arguments.length>2&&void 0!==arguments[2]&&arguments[2],n=this.creators.find((function(t){return t.id===e}));n&&(r?n.teams=n.teams.filter((function(e){return e.id!==t})):n.teams.push({id:t,is_manager:!1}))},deleteTeamMemberCancel:function(){this.confirmDialog=!1},confirmTeamManagerCancel:function(){this.confirmManagerDialog=!1},deleteTeamMember:function(){var e=this;return g(h().mark((function t(){var r,n,o;return h().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return r=(0,d.useNotificationStore)(),n={user_id:e.deleteMemberId,team_id:e.team.id},t.prev=2,t.next=5,axios.post(route("teams.removeTeamMember"),n);case 5:200===(o=t.sent).status?(e.removeMember(e.deleteMemberId),e.updateCreatorTeams(e.deleteMemberId,e.team.id,!0),e.confirmDialog=!1,r.setToastNotification(o.data.message,"success")):(e.confirmDialog=!1,r.setToastNotification("Failed to remove member from the team.","warning")),t.next=14;break;case 9:t.prev=9,t.t0=t.catch(2),console.error(t.t0),e.confirmDialog=!1,r.setToastNotification("An error occurred while removing the member from the team.","error");case 14:case"end":return t.stop()}}),t,null,[[2,9]])})))()},addTeamManager:function(){var e=this;return g(h().mark((function t(){var r,n,o;return h().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return r=(0,d.useNotificationStore)(),n={user_id:e.selectedManagerId,team_id:e.team.id,team_slug:e.team.slug},t.prev=2,t.next=5,axios.post(route("teams.addTeamManager"),n);case 5:200===(o=t.sent).status?(e.team.managers.push(o.data.manager),e.confirmManagerDialog=!1,r.setToastNotification(o.data.message,"success")):(e.confirmManagerDialog=!1,r.setToastNotification("Failed to add manager to the team.","warning")),t.next=14;break;case 9:t.prev=9,t.t0=t.catch(2),console.error(t.t0),e.confirmManagerDialog=!1,r.setToastNotification("An error occurred while adding the manager to the team.","error");case 14:case"end":return t.stop()}}),t,null,[[2,9]])})))()},removeTeamManager:function(){var e=this;return g(h().mark((function t(){var r,n,o;return h().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return r=(0,d.useNotificationStore)(),n={user_id:e.selectedManagerId,team_id:e.team.id,team_slug:e.team.slug},t.prev=2,t.next=5,axios.post(route("teams.removeTeamManager"),n);case 5:200===(o=t.sent).status?(e.team.managers=e.team.managers.filter((function(t){return t.id!==e.selectedManagerId})),e.confirmManagerDialog=!1,r.setToastNotification(o.data.message,"success")):(e.confirmManagerDialog=!1,r.setToastNotification("Failed to remove manager from the team.","warning")),t.next=14;break;case 9:t.prev=9,t.t0=t.catch(2),console.error(t.t0),e.confirmManagerDialog=!1,r.setToastNotification("An error occurred while removing the manager from the team.","error");case 14:case"end":return t.stop()}}),t,null,[[2,9]])})))()},toggleGoLiveDisplay:function(){this.goLiveDisplay=!this.goLiveDisplay},fetchTeamMembers:function(){return g(h().mark((function e(){return h().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,axios.get("/team/team-members").then().error();case 2:case"end":return e.stop()}}),e)})))()},setCreators:function(e){this.creators=e},updatePublicMessage:function(e){this.team.public_message=e}},getters:{spotsRemaining:function(e){return e.team.members?e.team.members?(this.totalSpots=e.team.totalSpots,Math.max(e.team.totalSpots-e.team.members.length,0)):void 0:e.team.totalSpots},membersCount:function(e){return e.team.members?e.team.members?(this.memberSpots=e.team.members.length,e.team.members.length):void 0:0},membersCountDisplay:function(e){if(e.team.members)return e.team.members.length>99?"99+":e.team.members.length},nextBroadcast:function(e){var t=e.team;if(!t.nextBroadcast||0===t.nextBroadcast.length)return null;var r=(0,u.useUserStore)(),n=a()().tz(r.timezone);return t.nextBroadcast.reduce((function(e,t){var o=a()(t.broadcastDate).tz(r.timezone);return!e||Math.abs(o-n)<Math.abs(a()(e.broadcastDate).tz(r.timezone)-n)?t:e}),null)},nextBroadcastIsOver:function(e){var t=(0,u.useUserStore)(),r=a()().utc().tz(t.timezone),n=a()(m.nextBroadcast.broadcastDate).add(m.nextBroadcast.broadcastDetails.duration_minutes,"minute").utc().tz(t.timezone);return r.isAfter(n)},sortedBroadcasts:function(e){if(!e.team.nextBroadcast||0===e.team.nextBroadcast.length)return[];var t=(0,u.useUserStore)(),r=a()().tz(t.timezone);return e.team.nextBroadcast.filter((function(e){return a()(e.broadcastDate).tz(t.timezone).isAfter(r)})).sort((function(e,r){return a()(e.broadcastDate).tz(t.timezone).diff(a()(r.broadcastDate).tz(t.timezone))})).map((function(e){return y(y({},e),{},{localDate:a()(e.broadcastDate).tz(t.timezone).format()})}))},futureBroadcasts:function(e){var t=this.nextBroadcast;if(!e.team.nextBroadcast||0===e.team.nextBroadcast.length)return[];var r=(0,u.useUserStore)(),n=a()().tz(r.timezone);return e.team.nextBroadcast.filter((function(e){return a()(e.broadcastDate).tz(r.timezone).isAfter(n)&&(!t||e.broadcastDate!==t.broadcastDate)})).sort((function(e,t){return a()(e.broadcastDate).tz(r.timezone).diff(a()(t.broadcastDate).tz(r.timezone))})).map((function(e){return y(y({},e),{},{localDate:a()(e.broadcastDate).tz(r.timezone).format()})}))}}})},2435:(e,t,r)=>{r.d(t,{c:()=>c});var n=r(29726),o=r(20715),a=r(20973),i=r(58088),s=r(92995);function c(e){var t=(0,o.useUserStore)(),r=(0,a.useAppSettingStore)(),c=(0,i.useVideoPlayerStore)();r.currentPage=e,r.showFlashMessage=!0,r.pageIsHidden=!1,t.isMobile||window.innerWidth<1024||r.fullPage?r.ott=0:(0===r.ott&&(r.ott=4),r.showOttButtons=!0),c.makeVideoTopRight(),r.pageReload&&(r.pageReload=!1,r.pageWasReloaded=!0,window.location.reload()),s.QB.on("navigate",(function(e){t.isMobile||window.innerWidth<1024||r.fullPage?r.ott=0:0===r.ott&&(r.ott=4),""!==window.location.search&&!r.shouldScrollToTop||requestAnimationFrame((function(){var e=document.getElementById("topDiv");e?e.scrollIntoView({behavior:"auto"}):window.scrollTo(0,0)})),r.shouldScrollToTop=!1})),r.setPrevUrl(),r.showOttButtons=!0,r.noLayout=!1,(0,n.watch)((function(){return r.isSmallScreen}),(function(e){r.ott=e?0:4}),{immediate:!0})}},88106:(e,t,r)=>{r.d(t,{A:()=>s});var n=r(29726),o=r(92995),a=r(20973),i=r(20715);const s={__name:"CancelButton",props:{url:String},setup:function(e){var t=(0,a.useAppSettingStore)(),r=(0,i.useUserStore)(),s=e;function c(){if(t.prevUrl)s.url?o.QB.visit(s.url):o.QB.visit(t.prevUrl);else if(s.url)o.QB.visit(s.url);else{var e=r.isCreator?"/dashboard":"/";o.QB.visit(e)}}return function(e,t){return(0,n.openBlock)(),(0,n.createElementBlock)("div",null,[(0,n.createElementVNode)("button",{onClick:(0,n.withModifiers)(c,["prevent"]),class:"ml-2 px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"},"Cancel ")])}}}},47212:(e,t,r)=>{r.d(t,{A:()=>y});var n=r(29726),o=r(92995),a=r(20973);function i(e){return i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},i(e)}function s(){s=function(){return t};var e,t={},r=Object.prototype,n=r.hasOwnProperty,o=Object.defineProperty||function(e,t,r){e[t]=r.value},a="function"==typeof Symbol?Symbol:{},c=a.iterator||"@@iterator",l=a.asyncIterator||"@@asyncIterator",u=a.toStringTag||"@@toStringTag";function d(e,t,r){return Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{d({},"")}catch(e){d=function(e,t,r){return e[t]=r}}function m(e,t,r,n){var a=t&&t.prototype instanceof b?t:b,i=Object.create(a.prototype),s=new O(n||[]);return o(i,"_invoke",{value:V(e,r,s)}),i}function f(e,t,r){try{return{type:"normal",arg:e.call(t,r)}}catch(e){return{type:"throw",arg:e}}}t.wrap=m;var h="suspendedStart",p="suspendedYield",g="executing",v="completed",y={};function b(){}function w(){}function x(){}var k={};d(k,c,(function(){return this}));var E=Object.getPrototypeOf,N=E&&E(E(T([])));N&&N!==r&&n.call(N,c)&&(k=N);var S=x.prototype=b.prototype=Object.create(k);function _(e){["next","throw","return"].forEach((function(t){d(e,t,(function(e){return this._invoke(t,e)}))}))}function B(e,t){function r(o,a,s,c){var l=f(e[o],e,a);if("throw"!==l.type){var u=l.arg,d=u.value;return d&&"object"==i(d)&&n.call(d,"__await")?t.resolve(d.__await).then((function(e){r("next",e,s,c)}),(function(e){r("throw",e,s,c)})):t.resolve(d).then((function(e){u.value=e,s(u)}),(function(e){return r("throw",e,s,c)}))}c(l.arg)}var a;o(this,"_invoke",{value:function(e,n){function o(){return new t((function(t,o){r(e,n,t,o)}))}return a=a?a.then(o,o):o()}})}function V(t,r,n){var o=h;return function(a,i){if(o===g)throw Error("Generator is already running");if(o===v){if("throw"===a)throw i;return{value:e,done:!0}}for(n.method=a,n.arg=i;;){var s=n.delegate;if(s){var c=L(s,n);if(c){if(c===y)continue;return c}}if("next"===n.method)n.sent=n._sent=n.arg;else if("throw"===n.method){if(o===h)throw o=v,n.arg;n.dispatchException(n.arg)}else"return"===n.method&&n.abrupt("return",n.arg);o=g;var l=f(t,r,n);if("normal"===l.type){if(o=n.done?v:p,l.arg===y)continue;return{value:l.arg,done:n.done}}"throw"===l.type&&(o=v,n.method="throw",n.arg=l.arg)}}}function L(t,r){var n=r.method,o=t.iterator[n];if(o===e)return r.delegate=null,"throw"===n&&t.iterator.return&&(r.method="return",r.arg=e,L(t,r),"throw"===r.method)||"return"!==n&&(r.method="throw",r.arg=new TypeError("The iterator does not provide a '"+n+"' method")),y;var a=f(o,t.iterator,r.arg);if("throw"===a.type)return r.method="throw",r.arg=a.arg,r.delegate=null,y;var i=a.arg;return i?i.done?(r[t.resultName]=i.value,r.next=t.nextLoc,"return"!==r.method&&(r.method="next",r.arg=e),r.delegate=null,y):i:(r.method="throw",r.arg=new TypeError("iterator result is not an object"),r.delegate=null,y)}function D(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function C(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function O(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(D,this),this.reset(!0)}function T(t){if(t||""===t){var r=t[c];if(r)return r.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var o=-1,a=function r(){for(;++o<t.length;)if(n.call(t,o))return r.value=t[o],r.done=!1,r;return r.value=e,r.done=!0,r};return a.next=a}}throw new TypeError(i(t)+" is not iterable")}return w.prototype=x,o(S,"constructor",{value:x,configurable:!0}),o(x,"constructor",{value:w,configurable:!0}),w.displayName=d(x,u,"GeneratorFunction"),t.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===w||"GeneratorFunction"===(t.displayName||t.name))},t.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,x):(e.__proto__=x,d(e,u,"GeneratorFunction")),e.prototype=Object.create(S),e},t.awrap=function(e){return{__await:e}},_(B.prototype),d(B.prototype,l,(function(){return this})),t.AsyncIterator=B,t.async=function(e,r,n,o,a){void 0===a&&(a=Promise);var i=new B(m(e,r,n,o),a);return t.isGeneratorFunction(r)?i:i.next().then((function(e){return e.done?e.value:i.next()}))},_(S),d(S,u,"Generator"),d(S,c,(function(){return this})),d(S,"toString",(function(){return"[object Generator]"})),t.keys=function(e){var t=Object(e),r=[];for(var n in t)r.push(n);return r.reverse(),function e(){for(;r.length;){var n=r.pop();if(n in t)return e.value=n,e.done=!1,e}return e.done=!0,e}},t.values=T,O.prototype={constructor:O,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=e,this.done=!1,this.delegate=null,this.method="next",this.arg=e,this.tryEntries.forEach(C),!t)for(var r in this)"t"===r.charAt(0)&&n.call(this,r)&&!isNaN(+r.slice(1))&&(this[r]=e)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var r=this;function o(n,o){return s.type="throw",s.arg=t,r.next=n,o&&(r.method="next",r.arg=e),!!o}for(var a=this.tryEntries.length-1;a>=0;--a){var i=this.tryEntries[a],s=i.completion;if("root"===i.tryLoc)return o("end");if(i.tryLoc<=this.prev){var c=n.call(i,"catchLoc"),l=n.call(i,"finallyLoc");if(c&&l){if(this.prev<i.catchLoc)return o(i.catchLoc,!0);if(this.prev<i.finallyLoc)return o(i.finallyLoc)}else if(c){if(this.prev<i.catchLoc)return o(i.catchLoc,!0)}else{if(!l)throw Error("try statement without catch or finally");if(this.prev<i.finallyLoc)return o(i.finallyLoc)}}}},abrupt:function(e,t){for(var r=this.tryEntries.length-1;r>=0;--r){var o=this.tryEntries[r];if(o.tryLoc<=this.prev&&n.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var a=o;break}}a&&("break"===e||"continue"===e)&&a.tryLoc<=t&&t<=a.finallyLoc&&(a=null);var i=a?a.completion:{};return i.type=e,i.arg=t,a?(this.method="next",this.next=a.finallyLoc,y):this.complete(i)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),y},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.finallyLoc===e)return this.complete(r.completion,r.afterLoc),C(r),y}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.tryLoc===e){var n=r.completion;if("throw"===n.type){var o=n.arg;C(r)}return o}}throw Error("illegal catch attempt")},delegateYield:function(t,r,n){return this.delegate={iterator:T(t),resultName:r,nextLoc:n},"next"===this.method&&(this.arg=e),y}},t}function c(e,t,r,n,o,a,i){try{var s=e[a](i),c=s.value}catch(e){return void r(e)}s.done?t(c):Promise.resolve(c).then(n,o)}var l={class:"mx-4 my-4"},u={key:0,class:"alert alert-info mt-4"},d=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24",class:"stroke-current shrink-0 w-6 h-6"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"})],-1),m={key:1,class:"alert alert-success mt-4 mx-3"},f=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"})],-1),h={key:2,class:"alert alert-warning mt-4 mx-3"},p=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"})],-1),g={key:3,class:"alert alert-error mt-4 mx-3"},v=(0,n.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",class:"stroke-current shrink-0 h-6 w-6",fill:"none",viewBox:"0 0 24 24"},[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"})],-1);const y={__name:"Messages",props:{flash:Object},setup:function(e){(0,a.useAppSettingStore)().showFlashMessage=!0;var t=e,r=((0,n.computed)((function(){return{"text-green-700 bg-green-100 dark:bg-green-200 dark:text-green-800":t.flash.success,"text-blue-700 bg-blue-100 dark:bg-blue-200 dark:text-blue-800":t.flash.message,"text-orange-700 bg-orange-100 dark:bg-orange-200 dark:text-orange-800":t.flash.warning,"text-red-700 bg-red-100 dark:bg-red-200 dark:text-red-800":t.flash.error}})),function(){var e,t=(e=s().mark((function e(){return s().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,o.QB.post(route("flash.clear"));case 2:o.QB.reload();case 3:case"end":return e.stop()}}),e)})),function(){var t=this,r=arguments;return new Promise((function(n,o){var a=e.apply(t,r);function i(e){c(a,n,o,i,s,"next",e)}function s(e){c(a,n,o,i,s,"throw",e)}i(void 0)}))});return function(){return t.apply(this,arguments)}}());return function(e,o){return(0,n.openBlock)(),(0,n.createElementBlock)("div",l,[(0,n.unref)(t).flash.message?((0,n.openBlock)(),(0,n.createElementBlock)("div",u,[d,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.message),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0),(0,n.unref)(t).flash.success?((0,n.openBlock)(),(0,n.createElementBlock)("div",m,[f,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.success),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0),(0,n.unref)(t).flash.warning?((0,n.openBlock)(),(0,n.createElementBlock)("div",h,[p,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.warning),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0),(0,n.unref)(t).flash.error?((0,n.openBlock)(),(0,n.createElementBlock)("div",g,[v,(0,n.createElementVNode)("span",null,(0,n.toDisplayString)((0,n.unref)(t).flash.error),1),(0,n.createElementVNode)("button",{class:"text-xs ml-12",onClick:r}," Close")])):(0,n.createCommentVNode)("",!0)])}}}},23611:(e,t,r)=>{r.d(t,{A:()=>c});var n=r(29726),o=r(92995),a={key:0},i=(0,n.createElementVNode)("div",{class:"font-medium text-red-600"}," Whoops! Something went wrong. ",-1),s={class:"mt-3 list-disc list-inside text-sm text-red-600"};const c={__name:"ValidationErrors",setup:function(e){var t=(0,n.computed)((function(){return(0,o.N5)().props.errors})),r=(0,n.computed)((function(){return Object.keys(t.value).length>0}));return function(e,o){return r.value?((0,n.openBlock)(),(0,n.createElementBlock)("div",a,[i,(0,n.createElementVNode)("ul",s,[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)(t.value,(function(e,t){return(0,n.openBlock)(),(0,n.createElementBlock)("li",{key:t},(0,n.toDisplayString)(e),1)})),128))])])):(0,n.createCommentVNode)("",!0)}}}},18735:(e,t,r)=>{r.r(t),r.d(t,{default:()=>$});var n=r(29726),o=r(92995),a=r(79092),i=r.n(a),s=r(83424),c=r.n(s),l=r(38460),u=r.n(l),d=r(63356),m=r.n(d),f=r(36936),h=r.n(f),p=(r(22692),r(24728),r(2435)),g=r(85045),v=r(67405),y=r(23611),b=r(88106),w=r(47212),x={class:"place-self-center flex flex-col gap-y-3"},k={id:"topDiv",class:"bg-white text-gray-800 dark:bg-gray-800 dark:text-white p-5 mb-10"},E=(0,n.createElementVNode)("div",{class:"bg-black text-red-600 font-bold text-xl p-4 mb-4 w-full text-center"},[(0,n.createTextVNode)(" This page needs updating.. attach a video that is already uploaded or upload a new video for this episode."),(0,n.createElementVNode)("br"),(0,n.createTextVNode)(" Also, upload bonus content? ")],-1),N={class:"flex justify-between mb-6"},S=(0,n.createElementVNode)("div",{class:"font-bold mb-4 text-orange-700"},"UPLOAD VIDEO",-1),_={class:"text-3xl"},B={class:""},V=(0,n.createElementVNode)("span",{class:"text-xs uppercase font-semibold"},"Show: ",-1),L={class:""},D=(0,n.createElementVNode)("span",{class:"text-xs uppercase font-semibold"},"Team: ",-1),C={class:"mb-6"},O=(0,n.createElementVNode)("span",{class:"text-xs uppercase font-semibold"},"Episode #: ",-1),T={key:0},j={key:1},M={class:"flex flex-col"},z={class:"-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"},P={class:"py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"},A={class:"shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"},I=["textContent"],F=["textContent"],G=["textContent"],U=["textContent"],Q=["textContent"],R=["textContent"],Y={class:"grid grid-cols-1 sm:grid-cols-2 space-x-6 p-6"},W={class:"max-full mx-auto mt-2 mb-6 bg-gray-200 p-6 text-dark"},Z=(0,n.createStaticVNode)('<div class="mb-3 bg-orange-300 py-1 px-2 text-xs font-semibold text-red-800"> In development. Not currently working. </div><h2 class="text-xl font-semibold text-gray-800">Upload Video</h2><ul class="pb-4 text-gray-800"><li>Max Video Length: <span class="text-orange-400">4 hours</span></li><li>File Types accepted: <span class="text-orange-400">mp4, webm, ogg</span></li></ul>',3),H={class:"flex space-y-3"},q={class:"mb-6"},K=["src"];const $={__name:"Upload",props:{episode:Object,show:Object,team:Object,poster:String},setup:function(e){(0,p.c)("shows/slug/episodes/slug/upload");var t=useAppSettingStore(),r=(0,g.useTeamStore)(),a=(0,v.useShowStore)();r.setActiveTeam(s.team),r.setActiveShow(s.show),a.episodePoster=s.poster;var s=e,l=(0,o.mN)({id:s.episode.id,name:s.episode.name,episode_number:s.episode.episode_number,description:s.episode.description,notes:s.episode.notes,show_id:s.episode.show_id,video_thumbnail:s.episode.video_thumbnail,video_file_url:s.episode.video_file_url,video_file_embed_code:s.episode.video_file_embed_code});i()(c(),u(),m(),h());return function(r,o){var a=(0,n.resolveComponent)("Head"),i=(0,n.resolveComponent)("Link");return(0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,null,[(0,n.createVNode)(a,{title:"Upload Video for ".concat((0,n.unref)(s).episode.name)},null,8,["title"]),(0,n.createElementVNode)("div",x,[(0,n.createElementVNode)("div",k,[(0,n.unref)(t).showFlashMessage?((0,n.openBlock)(),(0,n.createBlock)((0,n.unref)(w.A),{key:0,flash:r.$page.props.flash},null,8,["flash"])):(0,n.createCommentVNode)("",!0),E,(0,n.createElementVNode)("header",null,[(0,n.createElementVNode)("div",N,[(0,n.createElementVNode)("div",null,[S,(0,n.createElementVNode)("h1",_,[(0,n.createVNode)(i,{href:"/shows/".concat(e.show.slug,"/episode/").concat(e.episode.slug),class:"text-orange-700 font-bold"},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)((0,n.toDisplayString)(e.episode.name),1)]})),_:1},8,["href"])])]),(0,n.createElementVNode)("div",null,[(0,n.createVNode)((0,n.unref)(b.A))])]),(0,n.createElementVNode)("div",null,[(0,n.createElementVNode)("div",B,[V,(0,n.createVNode)(i,{href:"/shows/".concat(e.show.slug,"/manage")},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)((0,n.toDisplayString)(e.show.name),1)]})),_:1},8,["href"])]),(0,n.createElementVNode)("div",L,[D,(0,n.createVNode)(i,{href:"/teams/".concat(e.team.slug,"/manage")},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)((0,n.toDisplayString)(e.team.name),1)]})),_:1},8,["href"])]),(0,n.createElementVNode)("div",C,[O,e.episode.episode_number?((0,n.openBlock)(),(0,n.createElementBlock)("span",T,(0,n.toDisplayString)(e.episode.episode_number),1)):(0,n.createCommentVNode)("",!0),e.episode.episode_number?(0,n.createCommentVNode)("",!0):((0,n.openBlock)(),(0,n.createElementBlock)("span",j,(0,n.toDisplayString)(e.episode.id),1))])])]),(0,n.createElementVNode)("div",M,[(0,n.createElementVNode)("div",z,[(0,n.createElementVNode)("div",P,[(0,n.createElementVNode)("div",A,[(0,n.unref)(l).errors.name?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(l).errors.name),class:"bg-red-600 p-2 w-full text-white font-semibold mt-1"},null,8,I)):(0,n.createCommentVNode)("",!0),(0,n.unref)(l).errors.description?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:1,textContent:(0,n.toDisplayString)((0,n.unref)(l).errors.description),class:"bg-red-600 p-2 w-full text-white font-semibold mt-1"},null,8,F)):(0,n.createCommentVNode)("",!0),(0,n.unref)(l).errors.episode_number?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:2,textContent:(0,n.toDisplayString)((0,n.unref)(l).errors.episode_number),class:"bg-red-600 p-2 w-full text-white font-semibold mt-1"},null,8,G)):(0,n.createCommentVNode)("",!0),(0,n.unref)(l).errors.notes?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:3,textContent:(0,n.toDisplayString)((0,n.unref)(l).errors.notes),class:"bg-red-600 p-2 w-full text-white font-semibold mt-1"},null,8,U)):(0,n.createCommentVNode)("",!0),(0,n.unref)(l).errors.video_file_url?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:4,textContent:(0,n.toDisplayString)((0,n.unref)(l).errors.video_file_url),class:"bg-red-600 p-2 w-full text-white font-semibold mt-1"},null,8,Q)):(0,n.createCommentVNode)("",!0),(0,n.unref)(l).errors.video_file_embed_code?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:5,textContent:(0,n.toDisplayString)((0,n.unref)(l).errors.video_file_embed_code),class:"bg-red-600 p-2 w-full text-white font-semibold mt-1"},null,8,R)):(0,n.createCommentVNode)("",!0),(0,n.createElementVNode)("div",Y,[(0,n.createElementVNode)("div",null,[(0,n.createElementVNode)("div",null,[(0,n.createElementVNode)("div",W,[Z,(0,n.createElementVNode)("div",H,[(0,n.createElementVNode)("div",q,[(0,n.unref)(s).episode.video_thumbnail?(0,n.createCommentVNode)("",!0):((0,n.openBlock)(),(0,n.createElementBlock)("img",{src:"/storage/images/EBU_Colorbars.svg.png",key:r.video_thumbnail})),(0,n.unref)(s).episode.video_thumbnail?((0,n.openBlock)(),(0,n.createElementBlock)("img",{src:"/storage/images/"+(0,n.unref)(s).episode.video_thumbnail,key:r.video_thumbnail},null,8,K)):(0,n.createCommentVNode)("",!0)])])])]),(0,n.createVNode)((0,n.unref)(y.A),{class:"mr-4"})])])])])])])])])],64)}}}}}]);
//# sourceMappingURL=8735.js.map