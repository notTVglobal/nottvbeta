/*! For license information please see 1688.js.LICENSE.txt */
"use strict";(self.webpackChunknottvbeta=self.webpackChunknottvbeta||[]).push([[1688],{31688:(t,e,r)=>{r.r(e),r.d(e,{useScheduleStore:()=>U});var n=r(38839),o=r(20715),a=r(20973),i=r(7447),s=r(74353),u=r.n(s),c=r(83826),d=r.n(c),l=r(88569),f=r.n(l);u().extend(d()),u().extend(f());var h=r(98867),m=r.n(h),p=r(8906),v=r.n(p),y=r(47581),g=r.n(y),w=r(49400),S=r.n(w),x=r(75125),b=r.n(x),k=r(8134),T=r.n(k),O=r(97375),D=r.n(O),M=r(92995);function L(t){return L="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},L(t)}function Y(t,e){var r="undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(!r){if(Array.isArray(t)||(r=E(t))||e&&t&&"number"==typeof t.length){r&&(t=r);var n=0,o=function(){};return{s:o,n:function(){return n>=t.length?{done:!0}:{done:!1,value:t[n++]}},e:function(t){throw t},f:o}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var a,i=!0,s=!1;return{s:function(){r=r.call(t)},n:function(){var t=r.next();return i=t.done,t},e:function(t){s=!0,a=t},f:function(){try{i||null==r.return||r.return()}finally{if(s)throw a}}}}function F(t){return function(t){if(Array.isArray(t))return A(t)}(t)||function(t){if("undefined"!=typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}(t)||E(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function E(t,e){if(t){if("string"==typeof t)return A(t,e);var r={}.toString.call(t).slice(8,-1);return"Object"===r&&t.constructor&&(r=t.constructor.name),"Map"===r||"Set"===r?Array.from(t):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?A(t,e):void 0}}function A(t,e){(null==e||e>t.length)&&(e=t.length);for(var r=0,n=Array(e);r<e;r++)n[r]=t[r];return n}function W(){W=function(){return e};var t,e={},r=Object.prototype,n=r.hasOwnProperty,o=Object.defineProperty||function(t,e,r){t[e]=r.value},a="function"==typeof Symbol?Symbol:{},i=a.iterator||"@@iterator",s=a.asyncIterator||"@@asyncIterator",u=a.toStringTag||"@@toStringTag";function c(t,e,r){return Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}),t[e]}try{c({},"")}catch(t){c=function(t,e,r){return t[e]=r}}function d(t,e,r,n){var a=e&&e.prototype instanceof y?e:y,i=Object.create(a.prototype),s=new E(n||[]);return o(i,"_invoke",{value:D(t,r,s)}),i}function l(t,e,r){try{return{type:"normal",arg:t.call(e,r)}}catch(t){return{type:"throw",arg:t}}}e.wrap=d;var f="suspendedStart",h="suspendedYield",m="executing",p="completed",v={};function y(){}function g(){}function w(){}var S={};c(S,i,(function(){return this}));var x=Object.getPrototypeOf,b=x&&x(x(A([])));b&&b!==r&&n.call(b,i)&&(S=b);var k=w.prototype=y.prototype=Object.create(S);function T(t){["next","throw","return"].forEach((function(e){c(t,e,(function(t){return this._invoke(e,t)}))}))}function O(t,e){function r(o,a,i,s){var u=l(t[o],t,a);if("throw"!==u.type){var c=u.arg,d=c.value;return d&&"object"==L(d)&&n.call(d,"__await")?e.resolve(d.__await).then((function(t){r("next",t,i,s)}),(function(t){r("throw",t,i,s)})):e.resolve(d).then((function(t){c.value=t,i(c)}),(function(t){return r("throw",t,i,s)}))}s(u.arg)}var a;o(this,"_invoke",{value:function(t,n){function o(){return new e((function(e,o){r(t,n,e,o)}))}return a=a?a.then(o,o):o()}})}function D(e,r,n){var o=f;return function(a,i){if(o===m)throw Error("Generator is already running");if(o===p){if("throw"===a)throw i;return{value:t,done:!0}}for(n.method=a,n.arg=i;;){var s=n.delegate;if(s){var u=M(s,n);if(u){if(u===v)continue;return u}}if("next"===n.method)n.sent=n._sent=n.arg;else if("throw"===n.method){if(o===f)throw o=p,n.arg;n.dispatchException(n.arg)}else"return"===n.method&&n.abrupt("return",n.arg);o=m;var c=l(e,r,n);if("normal"===c.type){if(o=n.done?p:h,c.arg===v)continue;return{value:c.arg,done:n.done}}"throw"===c.type&&(o=p,n.method="throw",n.arg=c.arg)}}}function M(e,r){var n=r.method,o=e.iterator[n];if(o===t)return r.delegate=null,"throw"===n&&e.iterator.return&&(r.method="return",r.arg=t,M(e,r),"throw"===r.method)||"return"!==n&&(r.method="throw",r.arg=new TypeError("The iterator does not provide a '"+n+"' method")),v;var a=l(o,e.iterator,r.arg);if("throw"===a.type)return r.method="throw",r.arg=a.arg,r.delegate=null,v;var i=a.arg;return i?i.done?(r[e.resultName]=i.value,r.next=e.nextLoc,"return"!==r.method&&(r.method="next",r.arg=t),r.delegate=null,v):i:(r.method="throw",r.arg=new TypeError("iterator result is not an object"),r.delegate=null,v)}function Y(t){var e={tryLoc:t[0]};1 in t&&(e.catchLoc=t[1]),2 in t&&(e.finallyLoc=t[2],e.afterLoc=t[3]),this.tryEntries.push(e)}function F(t){var e=t.completion||{};e.type="normal",delete e.arg,t.completion=e}function E(t){this.tryEntries=[{tryLoc:"root"}],t.forEach(Y,this),this.reset(!0)}function A(e){if(e||""===e){var r=e[i];if(r)return r.call(e);if("function"==typeof e.next)return e;if(!isNaN(e.length)){var o=-1,a=function r(){for(;++o<e.length;)if(n.call(e,o))return r.value=e[o],r.done=!1,r;return r.value=t,r.done=!0,r};return a.next=a}}throw new TypeError(L(e)+" is not iterable")}return g.prototype=w,o(k,"constructor",{value:w,configurable:!0}),o(w,"constructor",{value:g,configurable:!0}),g.displayName=c(w,u,"GeneratorFunction"),e.isGeneratorFunction=function(t){var e="function"==typeof t&&t.constructor;return!!e&&(e===g||"GeneratorFunction"===(e.displayName||e.name))},e.mark=function(t){return Object.setPrototypeOf?Object.setPrototypeOf(t,w):(t.__proto__=w,c(t,u,"GeneratorFunction")),t.prototype=Object.create(k),t},e.awrap=function(t){return{__await:t}},T(O.prototype),c(O.prototype,s,(function(){return this})),e.AsyncIterator=O,e.async=function(t,r,n,o,a){void 0===a&&(a=Promise);var i=new O(d(t,r,n,o),a);return e.isGeneratorFunction(r)?i:i.next().then((function(t){return t.done?t.value:i.next()}))},T(k),c(k,u,"Generator"),c(k,i,(function(){return this})),c(k,"toString",(function(){return"[object Generator]"})),e.keys=function(t){var e=Object(t),r=[];for(var n in e)r.push(n);return r.reverse(),function t(){for(;r.length;){var n=r.pop();if(n in e)return t.value=n,t.done=!1,t}return t.done=!0,t}},e.values=A,E.prototype={constructor:E,reset:function(e){if(this.prev=0,this.next=0,this.sent=this._sent=t,this.done=!1,this.delegate=null,this.method="next",this.arg=t,this.tryEntries.forEach(F),!e)for(var r in this)"t"===r.charAt(0)&&n.call(this,r)&&!isNaN(+r.slice(1))&&(this[r]=t)},stop:function(){this.done=!0;var t=this.tryEntries[0].completion;if("throw"===t.type)throw t.arg;return this.rval},dispatchException:function(e){if(this.done)throw e;var r=this;function o(n,o){return s.type="throw",s.arg=e,r.next=n,o&&(r.method="next",r.arg=t),!!o}for(var a=this.tryEntries.length-1;a>=0;--a){var i=this.tryEntries[a],s=i.completion;if("root"===i.tryLoc)return o("end");if(i.tryLoc<=this.prev){var u=n.call(i,"catchLoc"),c=n.call(i,"finallyLoc");if(u&&c){if(this.prev<i.catchLoc)return o(i.catchLoc,!0);if(this.prev<i.finallyLoc)return o(i.finallyLoc)}else if(u){if(this.prev<i.catchLoc)return o(i.catchLoc,!0)}else{if(!c)throw Error("try statement without catch or finally");if(this.prev<i.finallyLoc)return o(i.finallyLoc)}}}},abrupt:function(t,e){for(var r=this.tryEntries.length-1;r>=0;--r){var o=this.tryEntries[r];if(o.tryLoc<=this.prev&&n.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var a=o;break}}a&&("break"===t||"continue"===t)&&a.tryLoc<=e&&e<=a.finallyLoc&&(a=null);var i=a?a.completion:{};return i.type=t,i.arg=e,a?(this.method="next",this.next=a.finallyLoc,v):this.complete(i)},complete:function(t,e){if("throw"===t.type)throw t.arg;return"break"===t.type||"continue"===t.type?this.next=t.arg:"return"===t.type?(this.rval=this.arg=t.arg,this.method="return",this.next="end"):"normal"===t.type&&e&&(this.next=e),v},finish:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.finallyLoc===t)return this.complete(r.completion,r.afterLoc),F(r),v}},catch:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.tryLoc===t){var n=r.completion;if("throw"===n.type){var o=n.arg;F(r)}return o}}throw Error("illegal catch attempt")},delegateYield:function(e,r,n){return this.delegate={iterator:A(e),resultName:r,nextLoc:n},"next"===this.method&&(this.arg=t),v}},e}function _(t,e,r,n,o,a,i){try{var s=t[a](i),u=s.value}catch(t){return void r(t)}s.done?e(u):Promise.resolve(u).then(n,o)}function I(t){return function(){var e=this,r=arguments;return new Promise((function(n,o){var a=t.apply(e,r);function i(t){_(a,n,o,i,s,"next",t)}function s(t){_(a,n,o,i,s,"throw",t)}i(void 0)}))}}function H(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function P(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?H(Object(r),!0).forEach((function(e){z(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):H(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}function z(t,e,r){return(e=function(t){var e=function(t,e){if("object"!=L(t)||!t)return t;var r=t[Symbol.toPrimitive];if(void 0!==r){var n=r.call(t,e||"default");if("object"!=L(n))return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===e?String:Number)(t)}(t,"string");return"symbol"==L(e)?e:e+""}(e))in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}function j(t){var e=(0,o.useUserStore)();return t.data.map((function(t){var r=t.start_dateTime?e.formatDateTimeFromUtcToUserTimezone(t.start_dateTime,"YYYY-MM-DD HH:mm:ss"):null,n=t.end_dateTime?e.formatDateTimeFromUtcToUserTimezone(t.end_dateTime,"YYYY-MM-DD HH:mm:ss"):null;return P(P({},t),{},{start_dateTime:r,end_dateTime:n,timezone:e.timezone})}))}function N(t){for(var e=[],r=u()(t),n=0;n<6;n++)e.push(r.add(n,"hour").toDate());return e}u().extend(f()),u().extend(T()),u().extend(g()),u().extend(S()),u().extend(b()),u().extend(D()),u().extend(d()),u().extend(m()),u().extend(v());var B=function(){return(0,o.useUserStore)().timezone},C=function(){return{baseTime:u()().tz(B()),currentHalfHour:u()().tz(B()).startOf("hour").add(u()().minute()>=30?30:0,"minute"),fourHoursLater:u()().tz(B()).startOf("hour").add(u()().minute()>=30?30:0,"minute").add(4,"hour"),viewingWindowStart:u()().tz(B()).startOf("hour"),currentMonth:u()().tz(B()).startOf("month"),selectedDay:u()().tz(B()),currentWeekStart:u()().tz(B()).startOf("week"),currentWeekEnd:u()().tz(B()).endOf("week"),nextFourHoursOfContent:[],nextFourHoursOfContentWithPlaceholders:[],fiveDaySixHourSchedule:[],schedules:[],isLoading:!0,nextPage:1,hasMore:!0,todaysContent:[],weeklyContent:[],dataFetchLog:[],savingToSchedule:!1,slotIntervalMinutes:30,mediumScreenSlotHours:4,smallScreenSlotHours:2,verySmallScreenSlotHours:1,timeSlots:[],timeBanners:[{id:1,name:"Early Morning",startTime:"04:00",duration:2},{id:2,name:"Morning",startTime:"06:00",duration:6},{id:3,name:"Afternoon",startTime:"12:00",duration:5},{id:4,name:"Prime Time",startTime:"17:00",duration:3},{id:5,name:"Late Prime Time",startTime:"20:00",duration:3},{id:6,name:"Late Night",startTime:"23:00",duration:2},{id:7,name:"Overnight",startTime:"01:00",duration:3}]}},U=(0,n.nY)("scheduleStore",{state:C,actions:{resetAll:function(){Object.assign(this,C())},reset:function(){this.baseTime=u()().tz(B()),this.viewingWindowStart=u()().tz(B()).startOf("hour"),this.currentMonth=u()().tz(B()).startOf("month"),this.selectedDay=u()().tz(B())},setSelectedDay:function(t){var e=this;return I(W().mark((function r(){var n;return W().wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return n=u()(t),e.selectedDay=n.toDate(),e.viewingWindowStart=n.startOf("day").add(4,"hours").toDate(),e.currentWeekStart=n.startOf("week").toDate(),e.currentWeekEnd=n.endOf("week").toDate(),r.next=7,e.fetchWeekDataIfNeeded();case 7:case"end":return r.stop()}}),r)})))()},setSelectedDayToToday:function(t){var e=u()();this.selectedDay=e.toDate(),this.viewingWindowStart=e.startOf("hour").toDate()},changeDay:function(t){var e=this;return I(W().mark((function r(){var n,o,a;return W().wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return n=B(),o=60*u()(e.viewingWindowStart).hour()+u()(e.viewingWindowStart).minute(),a=(a=u()(e.viewingWindowStart).tz(n).add(t,"day").startOf("day")).add(o,"minute"),e.viewingWindowStart=a.toDate(),e.viewingWindowStart=a,r.next=8,e.fetchWeekDataIfNeeded();case 8:case"end":return r.stop()}}),r)})))()},shiftHours:function(t){var e=this;return I(W().mark((function r(){return W().wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return e.viewingWindowStart=u()(e.viewingWindowStart).add(t,"hour").toDate(),e.currentWeekStart=u()(e.viewingWindowStart).startOf("week").toDate(),e.currentWeekEnd=u()(e.viewingWindowStart).endOf("week").toDate(),u()(e.viewingWindowStart).isSame(u()(e.selectedDay),"day")||(e.selectedDay=u()(e.viewingWindowStart).startOf("day").toDate()),r.next=6,e.fetchWeekDataIfNeeded();case 6:case"end":return r.stop()}}),r)})))()},isElevenPM:function(t){return 23===u()(t).hour()},subtractMonth:function(){var t=this;return I(W().mark((function e(){return W().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.currentMonth=u()(t.currentMonth).subtract(1,"month").toDate(),e.prev=1,e.next=4,t.setSelectedDay(t.currentMonth);case 4:e.next=9;break;case 6:e.prev=6,e.t0=e.catch(1),console.error("Failed to set selected day based on current month ".concat(t.currentMonth,":"),e.t0);case 9:case"end":return e.stop()}}),e,null,[[1,6]])})))()},addMonth:function(){var t=this;return I(W().mark((function e(){return W().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.currentMonth=u()(t.currentMonth).add(1,"month").toDate(),e.prev=1,e.next=4,t.setSelectedDay(t.currentMonth);case 4:e.next=9;break;case 6:e.prev=6,e.t0=e.catch(1),console.error("Failed to set selected day based on current month ".concat(t.currentMonth,":"),e.t0);case 9:case"end":return e.stop()}}),e,null,[[1,6]])})))()},fetchFiveDaySixHourSchedule:function(){var t=this;return I(W().mark((function e(){var r,n;return W().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return console.error("fetchFiveDaySixHourSchedule"),e.prev=1,r=(0,o.useUserStore)(),e.next=5,axios.get("/api/schedule");case 5:n=e.sent,r.timezone||n.data.userTimezone||"UTC",t.fiveDaySixHourSchedule=j(n.data),e.next=13;break;case 10:e.prev=10,e.t0=e.catch(1),console.error("Failed to load schedule shows:",e.t0);case 13:case"end":return e.stop()}}),e,null,[[1,10]])})))()},fetchTodaysContent:function(){var t=this;return I(W().mark((function e(){var r,n;return W().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.prev=0,r=(0,o.useUserStore)(),e.next=4,axios.get("/api/schedule/today");case 4:n=e.sent,r.timezone||n.data.userTimezone||"UTC",t.todaysContent=j(n.data),e.next=12;break;case 9:e.prev=9,e.t0=e.catch(0),console.error("Failed to fetch today's content:",e.t0);case 12:case"end":return e.stop()}}),e,null,[[0,9]])})))()},preloadWeeklyContent:function(){var t=this;return I(W().mark((function e(){var r;return W().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return console.log("preloading weekly content..."),r=u()(t.baseTime),e.prev=2,e.next=5,t.loadWeekFromDate(r);case 5:e.next=10;break;case 7:e.prev=7,e.t0=e.catch(2),console.error("Failed to preload weekly content:",e.t0);case 10:case"end":return e.stop()}}),e,null,[[2,7]])})))()},fetchSchedules:function(t,e){var r=this;return I(W().mark((function n(){var a,i,s,c,d,l,f;return W().wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return r.isLoading=!0,n.prev=1,a=(0,o.useUserStore)(),i=u()(t),s=u()(e),c=i.toISOString(),d=s.toISOString(),n.next=9,axios.get("/api/schedules/range?start=".concat(c,"&end=").concat(d));case 9:l=n.sent,a.timezone||l.data.userTimezone,f=j(l.data),r.schedules=F(new Set([].concat(F(r.schedules),F(f)).map((function(t){return JSON.stringify(t)})))).map((function(t){return JSON.parse(t)})),n.next=19;break;case 15:n.prev=15,n.t0=n.catch(1),console.error("Failed to fetch schedules:",n.t0),r.isLoading=!1;case 19:return n.prev=19,r.isLoading=!1,n.finish(19);case 22:case"end":return n.stop()}}),n,null,[[1,15,19,22]])})))()},fetchMoreSchedules:function(){var t=this;return I(W().mark((function e(){var r,n,o;return W().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(!t.isLoading&&t.hasMore){e.next=2;break}return e.abrupt("return");case 2:return t.isLoading=!0,e.prev=3,r=t.schedules[t.schedules.length-1],n=r?u()(r.start_dateTime).format("YYYY-MM-DD"):u()().format("YYYY-MM-DD"),o=u()(n).add(7,"day").format("YYYY-MM-DD"),e.next=9,t.fetchSchedules(n,o);case 9:0===t.schedules.slice(t.schedules.length-1).length?t.hasMore=!1:t.nextPage+=1,e.next=16;break;case 13:e.prev=13,e.t0=e.catch(3),console.error("Failed to fetch more schedules:",e.t0);case 16:return e.prev=16,t.isLoading=!1,e.finish(16);case 19:case"end":return e.stop()}}),e,null,[[3,13,16,19]])})))()},loadWeekFromDate:function(t){var e=this;return I(W().mark((function r(){var n,a,i,s,c,d;return W().wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return n=null,e.isLoading=!0,console.log("Loading week from date..."),r.prev=3,a=(0,o.useUserStore)(),i=u()(t),s=i.toISOString(),r.next=9,axios.post("/api/schedule/week/".concat(s));case 9:c=r.sent,i.format("YYYY-MM-DD"),a.timezone||c.data.userTimezone,d=j(c.data),e.weeklyContent=[].concat(F(e.weeklyContent),F(d)).filter((function(t,e,r){return e===r.findIndex((function(e){return e.id===t.id&&e.start_dateTime===t.start_dateTime}))})),e.updateFetchLogs(t),e.isLoading=!1,r.next=22;break;case 18:r.prev=18,r.t0=r.catch(3),console.error("Failed to load content for week starting ".concat(n,":"),r.t0),e.isLoading=!1;case 22:case"end":return r.stop()}}),r,null,[[3,18]])})))()},updateFetchLogs:function(t){var e=u()(t),r=e.startOf("week").toISOString(),n=e.endOf("week").toISOString(),o=u()().toISOString(),a=this.dataFetchLog.findIndex((function(t){return t.weekStart===r&&t.weekEnd===n}));-1!==a?this.dataFetchLog[a].lastFetch=o:this.dataFetchLog.push({weekStart:r,weekEnd:n,lastFetch:o})},needsDataForWeek:function(){var t=function(t){return u()(t).format("YYYY-MM-DD")},e=t(this.currentWeekStart),r=t(this.currentWeekEnd);return!this.dataFetchLog.some((function(n){var o=t(n.weekStart),a=t(n.weekEnd);return o<=e&&a>=r}))},calculateExtendedEndForUpcomingContent:function(){return u()(this.viewingWindowStart).add(6,"hour").toDate()},checkAndFetchForUpcomingContent:function(){var t=this;return I(W().mark((function e(){var r,n,o,a,i,s;return W().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:r=N(t.viewingWindowStart),n=u()(),o=n.subtract(15,"minutes"),a=Y(r),e.prev=4,s=W().mark((function e(){var r,n,a;return W().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(r=i.value,n=u()(r),a=n.format("YYYY-MM-DD"),t.weeklyContent.some((function(e){var r=u()(e.start_dateTime).format("YYYY-MM-DD"),n=t.dataFetchLog[a],i=n&&u()(n)>o;return a===r&&i}))){e.next=8;break}return e.next=7,t.fetchDataAndUpdateLog(a,r.toDate());case 7:return e.abrupt("return",1);case 8:case"end":return e.stop()}}),e)})),a.s();case 7:if((i=a.n()).done){e.next=13;break}return e.delegateYield(s(),"t0",9);case 9:if(!e.t0){e.next=11;break}return e.abrupt("break",13);case 11:e.next=7;break;case 13:e.next=18;break;case 15:e.prev=15,e.t1=e.catch(4),a.e(e.t1);case 18:return e.prev=18,a.f(),e.finish(18);case 21:case"end":return e.stop()}}),e,null,[[4,15,18,21]])})))()},fetchDataAndUpdateLog:function(t,e){var r=this;return I(W().mark((function n(){var o;return W().wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return n.prev=0,o=u()(e),n.next=4,r.loadWeekFromDate(o.toDate());case 4:r.dataFetchLog[t]=u()().toISOString(),n.next=10;break;case 7:n.prev=7,n.t0=n.catch(0),console.error("Failed to fetch data for date ".concat(t,":"),n.t0);case 10:case"end":return n.stop()}}),n,null,[[0,7]])})))()},fetchWeekDataIfNeeded:function(){var t=this;return I(W().mark((function e(){return W().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(!t.needsDataForWeek()){e.next=5;break}return e.next=3,t.checkAndFetchForUpcomingContent().catch((function(t){return console.error("Failed to load data for the week:",t),!1}));case 3:e.next=6;break;case 5:console.log("Week data already loaded; no need to fetch.");case 6:case"end":return e.stop()}}),e)})))()},updateBaseTime:function(t){this.baseTime=u()(t).toDate()},setBaseTime:function(t){this.baseTime=u()(t).toDate()},updateNextFourHours:function(){var t=this;return I(W().mark((function e(){return W().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:t.isLoading=!0,t.updateTimeRange(),t.setTimeSlots(),t.prepareShowsForGrid(),t.isLoading=!1;case 5:case"end":return e.stop()}}),e)})))()},updateTimeRange:function(){var t=u()(this.baseTime).tz(B()),e=t.minute()<30?0:30,r=t.minute(e).second(0).millisecond(0).startOf("minute"),n=(r=r.subtract(30,"minutes")).add(4,"hours");this.currentHalfHour=r.toDate(),this.fourHoursLater=n.toDate()},initializeTimeSlots:function(){this.timeSlots=function(t){for(var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:4,r=arguments.length>2&&void 0!==arguments[2]?arguments[2]:30,n=arguments.length>3?arguments[3]:void 0,o=[],a=u()(t).tz(n),i=0;i<60*e/r;i++){var s=a.add(i*r,"minute").toDate();o.push(s)}return o}()},setTimeSlots:function(){var t,e=(0,a.useAppSettingStore)();t=e.isVerySmallScreen?this.verySmallScreenSlotHours:e.isSmallScreen?this.smallScreenSlotHours:this.mediumScreenSlotHours;for(var r=this.slotIntervalMinutes,n=[],o=60*t/r,i=u()(this.currentHalfHour),s=0;s<o;s++){var c=i.add(s*r,"minute");n.push(c.toDate())}return this.timeSlots=n,n.length},prepareShowsForGrid:function(){if(this.timeSlots&&Array.isArray(this.timeSlots)&&0!==this.timeSlots.length){var t=this.filterShowsForTimeRange(),e=this.calculateGridSlots(t,this.timeSlots),r=this.processShows(e),n=this.updateColumnOccupancy(r,this.timeSlots.length),o=n.colOccupancy,a=n.maxRowUsed,i=this.fillGapsAndCreatePlaceholders(o,a,this.timeSlots.length),s=[].concat(F(r),F(i));this.nextFourHoursOfContent=this.sortShowsByPosition(s)}else console.error("timeSlots is not properly initialized.")},filterShowsForTimeRange:function(){var t=this;return this.schedules.filter((function(e){if("string"!=typeof e.start_dateTime||"number"!=typeof e.duration_minutes)return console.warn("Invalid show data:",e.start_dateTime,e.duration_minutes),!1;var r=u()(e.start_dateTime),n=u()(e.end_dateTime),o=r.isBefore(t.fourHoursLater)&&n.isAfter(t.currentHalfHour);if(o)r.isBefore(t.currentHalfHour);return o}))},calculateGridSlots:function(t,e){if(!Array.isArray(e)||0===e.length)return console.error("Invalid or empty timeSlots array"),[];var r=u()(e[0]),n=u()(e[e.length-1]).add(30,"minutes");return t.filter((function(t){var e=u()(t.start_dateTime),o=u()(t.end_dateTime);return e.isBefore(n)&&o.isAfter(r)})).map((function(t){var r=u()(t.start_dateTime),n=u()(t.end_dateTime),o=e.findIndex((function(t){return r.isSameOrBefore(u()(t))}));(-1===o||r.isAfter(u()(e[o])))&&(o=Math.max(0,o));var a=e.findIndex((function(t){return n.isSameOrBefore(u()(t).add(30,"minutes"))}));-1!==a&&n.isBefore(u()(e[a]))&&a--,(-1===a||n.isSame(u()(e[e.length-1]).add(30,"minutes")))&&(a=e.length-1);var i=a-o+1;return P(P({},t),{},{gridStart:o+1,gridSpan:i})}))},processShows:function(t){var e=this,r=!1;return t.forEach((function(t){var n=u()(t.start_dateTime),o=n.add(t.duration_minutes,"minutes"),a=u()(e.baseTime);t.nowPlaying=!t.placeholder&&a.isAfter(n)&&a.isBefore(o)&&1===t.gridRow,r||t.placeholder||1!==t.gridRow||!a.isBefore(n)?t.comingUpNext=!1:(t.comingUpNext=!0,r=!0)})),t},updateColumnOccupancy:function(t,e){var r=new Array(e).fill(null).map((function(){return new Set})),n=0;return t.forEach((function(t){for(var o=t.gridStart-1;o<t.gridStart-1+t.gridSpan;o++)o>=0&&o<e&&(r[o].add(t.gridRow),n=Math.max(n,t.gridRow))})),{colOccupancy:r,maxRowUsed:n}},fillGapsAndCreatePlaceholders:function(t,e,r){var n=[];e=Math.max(e,1);for(var o=1;o<=e;o++)n.push.apply(n,F(this.findAndFillGapsForSingleRow(t,o,r)));return n},findAndFillGapsForSingleRow:function(t,e,r){for(var n=[],o=-1,a=0;a<r;a++)t[a].has(e)?-1!==o&&(1===e?n.push(this.createPlaceholder(o+1,a-o,e)):n.push(this.createBlankSpotPlaceholder(o+1,a-o,e)),o=-1):o=-1===o?a:o;return-1!==o&&(1===e?n.push(this.createPlaceholder(o+1,r-o,e)):n.push(this.createBlankSpotPlaceholder(o+1,r-o,e))),n},sortShowsByPosition:function(t){return Array.isArray(t)?t.sort((function(t,e){return t.gridRow-e.gridRow||t.gridStart-e.gridStart})):(console.error("Expected an array of shows, received:",t),[])},createPlaceholder:function(t,e,r){return{placeholder:!0,startTime:"placeholder",priority:0,gridStart:t,gridSpan:e,gridRow:r,content:{name:"Nothing scheduled."}}},createBlankSpotPlaceholder:function(t,e,r){return{placeholder:!0,startTime:"placeholder",priority:0,gridStart:t,gridSpan:e,gridRow:r,content:{name:"Blank Spot"}}},purgeAllCaches:function(){return I(W().mark((function t(){var e,r;return W().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e=(0,i.useNotificationStore)(),U().resetAll(),t.prev=3,t.next=6,axios.post("/admin/schedule/admin-reset-cache");case 6:r=t.sent,e.setToastNotification(r.data.message,r.data.type),t.next=13;break;case 10:t.prev=10,t.t0=t.catch(3),e.setToastNotification("Error purging caches.","error");case 13:M.QB.reload();case 14:case"end":return t.stop()}}),t,null,[[3,10]])})))()},updateSchedule:function(){return I(W().mark((function t(){var e,r;return W().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e=(0,i.useNotificationStore)(),U().resetAll(),t.prev=3,t.next=6,axios.post("/admin/schedule/admin-update-schedule");case 6:r=t.sent,e.setToastNotification(r.data.message,r.data.type),t.next=13;break;case 10:t.prev=10,t.t0=t.catch(3),e.setToastNotification("Error updating schedule.","error");case 13:M.QB.reload();case 14:case"end":return t.stop()}}),t,null,[[3,10]])})))()}},getters:{currentTime:function(t){return u()(t.baseTime).format("h:mm A")},currentDate:function(t){return u()(t.baseTime).format("MMMM DD, YYYY")},preparedTimeBanners:function(t){var e=B(),r=u()().tz(e).startOf("day"),n=r.add(1,"day"),o=r.subtract(1,"day");return t.timeBanners.flatMap((function(e){var a=r.hour(parseInt(e.startTime.split(":")[0])).minute(parseInt(e.startTime.split(":")[1])),i=a.clone().add(e.duration,"hours"),s=n.hour(parseInt(e.startTime.split(":")[0])).minute(parseInt(e.startTime.split(":")[1])),c=s.clone().add(e.duration,"hours"),d=o.hour(parseInt(e.startTime.split(":")[0])).minute(parseInt(e.startTime.split(":")[1])),l=d.clone().add(e.duration,"hours");return[P(P({},e),{},{start:d,end:l}),P(P({},e),{},{start:a,end:i}),P(P({},e),{},{start:s,end:c})].map((function(e){var r=t.timeSlots.findIndex((function(t){return e.start.isSameOrBefore(u()(t))&&e.end.isAfter(u()(t))})),n=t.timeSlots.findIndex((function(t){return e.end.isSameOrBefore(u()(t))}));-1===n||e.end.isAfter(u()(t.timeSlots[t.timeSlots.length-1]))?n=t.timeSlots.length-1:n-=1;var o=r+1,a=n-r+1;return o&&a>0?P(P({},e),{},{gridStart:o,gridSpan:a}):null}))})).filter((function(t){return null!=t})).sort((function(t,e){return t.gridStart-e.gridStart}))},nextFourHoursWithHalfHourIntervals:function(t){if(!t.timeSlots||0===t.timeSlots.length)return console.log("timeSlots is null or has no elements"),[];var e=(0,o.useUserStore)(),r=((0,a.useAppSettingStore)(),e.timezone),n=t.timeSlots.length,i=[],s=u()(t.baseTime).tz(r),c=s.minute()<30?s.startOf("hour"):s.startOf("hour").add(30,"minutes");c=c.subtract(30,"minutes");for(var d=0;d<n;d++)i.push({formatted:c.format("hh:mm A"),dateTimeString:c.format("YYYY-MM-DD HH:mm:ss")}),c=c.add(30,"minute");return i},upcomingContent:function(t){var e=u()(t.viewingWindowStart).subtract(1,"hour"),r=e.add(7,"hours"),n=t.weeklyContent.reduce((function(t,e){var r=u()(e.start_dateTime).valueOf();return t[r]||(t[r]=[]),t[r].push(e),t}),{});return Object.values(n).map((function(t){return t.reduce((function(t,e){return!t||e.priority<t.priority?e:t}),null)})).filter((function(t){var n=u()(t.start_dateTime);return n.isSameOrAfter(e)&&n.isBefore(r)})).sort((function(t,e){return u()(t.start_dateTime).unix()-u()(e.start_dateTime).unix()}))},nextSixHours:function(t){for(var e=u()(t.viewingWindowStart),r=e.add(6,"hours"),n=[],o=e;o.isBefore(r);)n.push(o.toDate()),o=o.add(1,"hour");return n},dateMessage:function(t){var e=u()(t.viewingWindowStart).startOf("day");if(!e.isValid())return"Invalid date";var r=e.format("dddd MMMM DD, YYYY");return e.isToday()?"Today - ".concat(r):e.isYesterday()?"Yesterday - ".concat(r):e.isTomorrow()?"Tomorrow - ".concat(r):r},currentMonthIndex:function(t){return u()(t.currentMonth).month()},currentMonthName:function(t){return u()(t.currentMonth).format("MMMM")},currentYear:function(t){return u()(t.currentMonth).year()},isToday:function(t){var e=u()(),r=u()(t.viewingWindowStart);return e.isSame(r,"day")},daysInMonth:function(t){for(var e=u()(t.currentMonth),r=e.startOf("month"),n=e.endOf("month"),o=r.startOf("week"),a=n.endOf("week"),i=[],s=o;s.isBefore(a)||s.isSame(a,"day");)i.push(s.toDate()),s=s.add(1,"day");return i}}})}}]);
//# sourceMappingURL=1688.js.map