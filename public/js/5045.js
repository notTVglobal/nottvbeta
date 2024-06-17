/*! For license information please see 5045.js.LICENSE.txt */
"use strict";(self.webpackChunknottvbeta=self.webpackChunknottvbeta||[]).push([[5045],{85045:(t,e,r)=>{r.r(e),r.d(e,{useTeamStore:()=>x});var n=r(38839),o=r(74353),a=r.n(o),i=r(48018),s=r.n(i),c=r(88569),u=r.n(c),m=r(20715),l=r(7447),f=void 0;function h(t){return h="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},h(t)}function d(){d=function(){return e};var t,e={},r=Object.prototype,n=r.hasOwnProperty,o=Object.defineProperty||function(t,e,r){t[e]=r.value},a="function"==typeof Symbol?Symbol:{},i=a.iterator||"@@iterator",s=a.asyncIterator||"@@asyncIterator",c=a.toStringTag||"@@toStringTag";function u(t,e,r){return Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}),t[e]}try{u({},"")}catch(t){u=function(t,e,r){return t[e]=r}}function m(t,e,r,n){var a=e&&e.prototype instanceof y?e:y,i=Object.create(a.prototype),s=new _(n||[]);return o(i,"_invoke",{value:T(t,r,s)}),i}function l(t,e,r){try{return{type:"normal",arg:t.call(e,r)}}catch(t){return{type:"throw",arg:t}}}e.wrap=m;var f="suspendedStart",p="suspendedYield",g="executing",v="completed",b={};function y(){}function w(){}function x(){}var D={};u(D,i,(function(){return this}));var S=Object.getPrototypeOf,L=S&&S(S(k([])));L&&L!==r&&n.call(L,i)&&(D=L);var M=x.prototype=y.prototype=Object.create(D);function z(t){["next","throw","return"].forEach((function(e){u(t,e,(function(t){return this._invoke(e,t)}))}))}function O(t,e){function r(o,a,i,s){var c=l(t[o],t,a);if("throw"!==c.type){var u=c.arg,m=u.value;return m&&"object"==h(m)&&n.call(m,"__await")?e.resolve(m.__await).then((function(t){r("next",t,i,s)}),(function(t){r("throw",t,i,s)})):e.resolve(m).then((function(t){u.value=t,i(u)}),(function(t){return r("throw",t,i,s)}))}s(c.arg)}var a;o(this,"_invoke",{value:function(t,n){function o(){return new e((function(e,o){r(t,n,e,o)}))}return a=a?a.then(o,o):o()}})}function T(e,r,n){var o=f;return function(a,i){if(o===g)throw Error("Generator is already running");if(o===v){if("throw"===a)throw i;return{value:t,done:!0}}for(n.method=a,n.arg=i;;){var s=n.delegate;if(s){var c=B(s,n);if(c){if(c===b)continue;return c}}if("next"===n.method)n.sent=n._sent=n.arg;else if("throw"===n.method){if(o===f)throw o=v,n.arg;n.dispatchException(n.arg)}else"return"===n.method&&n.abrupt("return",n.arg);o=g;var u=l(e,r,n);if("normal"===u.type){if(o=n.done?v:p,u.arg===b)continue;return{value:u.arg,done:n.done}}"throw"===u.type&&(o=v,n.method="throw",n.arg=u.arg)}}}function B(e,r){var n=r.method,o=e.iterator[n];if(o===t)return r.delegate=null,"throw"===n&&e.iterator.return&&(r.method="return",r.arg=t,B(e,r),"throw"===r.method)||"return"!==n&&(r.method="throw",r.arg=new TypeError("The iterator does not provide a '"+n+"' method")),b;var a=l(o,e.iterator,r.arg);if("throw"===a.type)return r.method="throw",r.arg=a.arg,r.delegate=null,b;var i=a.arg;return i?i.done?(r[e.resultName]=i.value,r.next=e.nextLoc,"return"!==r.method&&(r.method="next",r.arg=t),r.delegate=null,b):i:(r.method="throw",r.arg=new TypeError("iterator result is not an object"),r.delegate=null,b)}function E(t){var e={tryLoc:t[0]};1 in t&&(e.catchLoc=t[1]),2 in t&&(e.finallyLoc=t[2],e.afterLoc=t[3]),this.tryEntries.push(e)}function j(t){var e=t.completion||{};e.type="normal",delete e.arg,t.completion=e}function _(t){this.tryEntries=[{tryLoc:"root"}],t.forEach(E,this),this.reset(!0)}function k(e){if(e||""===e){var r=e[i];if(r)return r.call(e);if("function"==typeof e.next)return e;if(!isNaN(e.length)){var o=-1,a=function r(){for(;++o<e.length;)if(n.call(e,o))return r.value=e[o],r.done=!1,r;return r.value=t,r.done=!0,r};return a.next=a}}throw new TypeError(h(e)+" is not iterable")}return w.prototype=x,o(M,"constructor",{value:x,configurable:!0}),o(x,"constructor",{value:w,configurable:!0}),w.displayName=u(x,c,"GeneratorFunction"),e.isGeneratorFunction=function(t){var e="function"==typeof t&&t.constructor;return!!e&&(e===w||"GeneratorFunction"===(e.displayName||e.name))},e.mark=function(t){return Object.setPrototypeOf?Object.setPrototypeOf(t,x):(t.__proto__=x,u(t,c,"GeneratorFunction")),t.prototype=Object.create(M),t},e.awrap=function(t){return{__await:t}},z(O.prototype),u(O.prototype,s,(function(){return this})),e.AsyncIterator=O,e.async=function(t,r,n,o,a){void 0===a&&(a=Promise);var i=new O(m(t,r,n,o),a);return e.isGeneratorFunction(r)?i:i.next().then((function(t){return t.done?t.value:i.next()}))},z(M),u(M,c,"Generator"),u(M,i,(function(){return this})),u(M,"toString",(function(){return"[object Generator]"})),e.keys=function(t){var e=Object(t),r=[];for(var n in e)r.push(n);return r.reverse(),function t(){for(;r.length;){var n=r.pop();if(n in e)return t.value=n,t.done=!1,t}return t.done=!0,t}},e.values=k,_.prototype={constructor:_,reset:function(e){if(this.prev=0,this.next=0,this.sent=this._sent=t,this.done=!1,this.delegate=null,this.method="next",this.arg=t,this.tryEntries.forEach(j),!e)for(var r in this)"t"===r.charAt(0)&&n.call(this,r)&&!isNaN(+r.slice(1))&&(this[r]=t)},stop:function(){this.done=!0;var t=this.tryEntries[0].completion;if("throw"===t.type)throw t.arg;return this.rval},dispatchException:function(e){if(this.done)throw e;var r=this;function o(n,o){return s.type="throw",s.arg=e,r.next=n,o&&(r.method="next",r.arg=t),!!o}for(var a=this.tryEntries.length-1;a>=0;--a){var i=this.tryEntries[a],s=i.completion;if("root"===i.tryLoc)return o("end");if(i.tryLoc<=this.prev){var c=n.call(i,"catchLoc"),u=n.call(i,"finallyLoc");if(c&&u){if(this.prev<i.catchLoc)return o(i.catchLoc,!0);if(this.prev<i.finallyLoc)return o(i.finallyLoc)}else if(c){if(this.prev<i.catchLoc)return o(i.catchLoc,!0)}else{if(!u)throw Error("try statement without catch or finally");if(this.prev<i.finallyLoc)return o(i.finallyLoc)}}}},abrupt:function(t,e){for(var r=this.tryEntries.length-1;r>=0;--r){var o=this.tryEntries[r];if(o.tryLoc<=this.prev&&n.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var a=o;break}}a&&("break"===t||"continue"===t)&&a.tryLoc<=e&&e<=a.finallyLoc&&(a=null);var i=a?a.completion:{};return i.type=t,i.arg=e,a?(this.method="next",this.next=a.finallyLoc,b):this.complete(i)},complete:function(t,e){if("throw"===t.type)throw t.arg;return"break"===t.type||"continue"===t.type?this.next=t.arg:"return"===t.type?(this.rval=this.arg=t.arg,this.method="return",this.next="end"):"normal"===t.type&&e&&(this.next=e),b},finish:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.finallyLoc===t)return this.complete(r.completion,r.afterLoc),j(r),b}},catch:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.tryLoc===t){var n=r.completion;if("throw"===n.type){var o=n.arg;j(r)}return o}}throw Error("illegal catch attempt")},delegateYield:function(e,r,n){return this.delegate={iterator:k(e),resultName:r,nextLoc:n},"next"===this.method&&(this.arg=t),b}},e}function p(t,e,r,n,o,a,i){try{var s=t[a](i),c=s.value}catch(t){return void r(t)}s.done?e(c):Promise.resolve(c).then(n,o)}function g(t){return function(){var e=this,r=arguments;return new Promise((function(n,o){var a=t.apply(e,r);function i(t){p(a,n,o,i,s,"next",t)}function s(t){p(a,n,o,i,s,"throw",t)}i(void 0)}))}}function v(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function b(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?v(Object(r),!0).forEach((function(e){y(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):v(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}function y(t,e,r){return(e=function(t){var e=function(t,e){if("object"!=h(t)||!t)return t;var r=t[Symbol.toPrimitive];if(void 0!==r){var n=r.call(t,e||"default");if("object"!=h(n))return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===e?String:Number)(t)}(t,"string");return"symbol"==h(e)?e:e+""}(e))in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}a().extend(s()),a().extend(u());var w=function(){return{team:{},shows:{},contributors:{},members:{},managers:{},teamOwner:[],teamLeader:[],can:{},id:0,name:"",description:"",slug:"",totalSpots:"",memberSpots:"",activeShow:[],activeEpisode:[],showModal:Boolean,confirmDialog:!1,confirmManagerDialog:!1,selectedManagerName:"",selectedManagerId:0,addManager:!1,removeManager:!1,deleteMemberName:"",deleteMemberId:0,noteEdit:0,note:"",noteKey:0,saveNoteProcessing:Boolean,goLiveDisplay:!1,openComponent:"teamShows",nextBroadcastLoaded:{scheduleIndexId:null,broadcastDate:null,broadcastDetails:{},type:"",image:null,category:null,subCategory:null,slug:null,name:null,description:null},nextBroadcastZoomLink:""}},x=(0,n.nY)("teamStore",{state:w,actions:{reset:function(){Object.assign(this,w())},initializeTeam:function(t){console.log("incoming team: ",t);var e=(0,m.useUserStore)();if(Array.isArray(t.nextBroadcast)&&t.nextBroadcast.length>0){var r=t.nextBroadcast[0];r.broadcastDetails&&(this.nextBroadcastLoaded=r,r.broadcastDetails.zoomLink&&(this.nextBroadcastZoomLink=r.broadcastDetails.zoomLink),t.nextBroadcast=t.nextBroadcast.map((function(t){return b(b({},t),{},{broadcastDate:e.convertUtcToUserTimezone(t.broadcastDate)})})))}else this.nextBroadcastLoaded=null,this.nextBroadcastZoomLink=null;this.team=t||{}},initializeShows:function(t){this.shows=t||{}},initializeContributors:function(t){this.contributors=t||{}},setCan:function(t){this.can=t||{}},setActiveTeam:function(t){this.team.id=t.id,this.team.name=t.name,this.team.description=t.description,this.team.slug=t.slug,this.team.members=t.members,this.team.managers=t.managers,this.team.totalSpots=t.totalSpots,this.memberSpots=t.memberSpots},setActiveShow:function(t){this.activeShow=t},setActiveEpisode:function(t){this.activeShow=t},addMember:function(t){this.team.members.push(t)},removeMember:function(t){this.team.members=this.team.members.filter((function(e){return e.id!==t}))},updateCreatorTeams:function(t,e){var r=arguments.length>2&&void 0!==arguments[2]&&arguments[2],n=this.creators.find((function(e){return e.id===t}));n&&(r?n.teams=n.teams.filter((function(t){return t.id!==e})):n.teams.push({id:e,is_manager:!1}))},deleteTeamMemberCancel:function(){this.confirmDialog=!1},confirmTeamManagerCancel:function(){this.confirmManagerDialog=!1},deleteTeamMember:function(){var t=this;return g(d().mark((function e(){var r,n,o;return d().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=(0,l.useNotificationStore)(),n={user_id:t.deleteMemberId,team_id:t.team.id},e.prev=2,e.next=5,axios.post(route("teams.removeTeamMember"),n);case 5:200===(o=e.sent).status?(t.removeMember(t.deleteMemberId),t.updateCreatorTeams(t.deleteMemberId,t.team.id,!0),t.confirmDialog=!1,r.setToastNotification(o.data.message,"success")):(t.confirmDialog=!1,r.setToastNotification("Failed to remove member from the team.","warning")),e.next=14;break;case 9:e.prev=9,e.t0=e.catch(2),console.error(e.t0),t.confirmDialog=!1,r.setToastNotification("An error occurred while removing the member from the team.","error");case 14:case"end":return e.stop()}}),e,null,[[2,9]])})))()},addTeamManager:function(){var t=this;return g(d().mark((function e(){var r,n,o;return d().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=(0,l.useNotificationStore)(),n={user_id:t.selectedManagerId,team_id:t.team.id,team_slug:t.team.slug},e.prev=2,e.next=5,axios.post(route("teams.addTeamManager"),n);case 5:200===(o=e.sent).status?(t.team.managers.push(o.data.manager),t.confirmManagerDialog=!1,r.setToastNotification(o.data.message,"success")):(t.confirmManagerDialog=!1,r.setToastNotification("Failed to add manager to the team.","warning")),e.next=14;break;case 9:e.prev=9,e.t0=e.catch(2),console.error(e.t0),t.confirmManagerDialog=!1,r.setToastNotification("An error occurred while adding the manager to the team.","error");case 14:case"end":return e.stop()}}),e,null,[[2,9]])})))()},removeTeamManager:function(){var t=this;return g(d().mark((function e(){var r,n,o;return d().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=(0,l.useNotificationStore)(),n={user_id:t.selectedManagerId,team_id:t.team.id,team_slug:t.team.slug},e.prev=2,e.next=5,axios.post(route("teams.removeTeamManager"),n);case 5:200===(o=e.sent).status?(t.team.managers=t.team.managers.filter((function(e){return e.id!==t.selectedManagerId})),t.confirmManagerDialog=!1,r.setToastNotification(o.data.message,"success")):(t.confirmManagerDialog=!1,r.setToastNotification("Failed to remove manager from the team.","warning")),e.next=14;break;case 9:e.prev=9,e.t0=e.catch(2),console.error(e.t0),t.confirmManagerDialog=!1,r.setToastNotification("An error occurred while removing the manager from the team.","error");case 14:case"end":return e.stop()}}),e,null,[[2,9]])})))()},toggleGoLiveDisplay:function(){this.goLiveDisplay=!this.goLiveDisplay},fetchTeamMembers:function(){return g(d().mark((function t(){return d().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,axios.get("/team/team-members").then().error();case 2:case"end":return t.stop()}}),t)})))()},setCreators:function(t){this.creators=t},updatePublicMessage:function(t){this.team.public_message=t}},getters:{spotsRemaining:function(t){return t.team.members?t.team.members?(this.totalSpots=t.team.totalSpots,Math.max(t.team.totalSpots-t.team.members.length,0)):void 0:t.team.totalSpots},membersCount:function(t){return t.team.members?t.team.members?(this.memberSpots=t.team.members.length,t.team.members.length):void 0:0},membersCountDisplay:function(t){if(t.team.members)return t.team.members.length>99?"99+":t.team.members.length},nextBroadcast:function(t){var e=t.team;if(!e.nextBroadcast||0===e.nextBroadcast.length)return null;var r=(0,m.useUserStore)(),n=a()().tz(r.timezone);return e.nextBroadcast.reduce((function(t,e){var o=a()(e.broadcastDate).tz(r.timezone);return!t||Math.abs(o-n)<Math.abs(a()(t.broadcastDate).tz(r.timezone)-n)?e:t}),null)},nextBroadcastIsOver:function(t){var e=(0,m.useUserStore)(),r=a()().utc().tz(e.timezone),n=a()(f.nextBroadcast.broadcastDate).add(f.nextBroadcast.broadcastDetails.duration_minutes,"minute").utc().tz(e.timezone);return r.isAfter(n)},sortedBroadcasts:function(t){if(!t.team.nextBroadcast||0===t.team.nextBroadcast.length)return[];var e=(0,m.useUserStore)(),r=a()().tz(e.timezone);return t.team.nextBroadcast.filter((function(t){return a()(t.broadcastDate).tz(e.timezone).isAfter(r)})).sort((function(t,r){return a()(t.broadcastDate).tz(e.timezone).diff(a()(r.broadcastDate).tz(e.timezone))})).map((function(t){return b(b({},t),{},{localDate:a()(t.broadcastDate).tz(e.timezone).format()})}))},futureBroadcasts:function(t){var e=this.nextBroadcast;if(!t.team.nextBroadcast||0===t.team.nextBroadcast.length)return[];var r=(0,m.useUserStore)(),n=a()().tz(r.timezone);return t.team.nextBroadcast.filter((function(t){return a()(t.broadcastDate).tz(r.timezone).isAfter(n)&&(!e||t.broadcastDate!==e.broadcastDate)})).sort((function(t,e){return a()(t.broadcastDate).tz(r.timezone).diff(a()(e.broadcastDate).tz(r.timezone))})).map((function(t){return b(b({},t),{},{localDate:a()(t.broadcastDate).tz(r.timezone).format()})}))}}})}}]);
//# sourceMappingURL=5045.js.map