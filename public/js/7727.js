/*! For license information please see 7727.js.LICENSE.txt */
"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[7727],{69812:(e,t,o)=>{function r(e){return r="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},r(e)}function n(){n=function(){return t};var e,t={},o=Object.prototype,a=o.hasOwnProperty,i=Object.defineProperty||function(e,t,o){e[t]=o.value},s="function"==typeof Symbol?Symbol:{},l=s.iterator||"@@iterator",c=s.asyncIterator||"@@asyncIterator",u=s.toStringTag||"@@toStringTag";function d(e,t,o){return Object.defineProperty(e,t,{value:o,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{d({},"")}catch(e){d=function(e,t,o){return e[t]=o}}function m(e,t,o,r){var n=t&&t.prototype instanceof w?t:w,a=Object.create(n.prototype),s=new j(r||[]);return i(a,"_invoke",{value:B(e,o,s)}),a}function p(e,t,o){try{return{type:"normal",arg:e.call(t,o)}}catch(e){return{type:"throw",arg:e}}}t.wrap=m;var f="suspendedStart",h="suspendedYield",g="executing",y="completed",v={};function w(){}function b(){}function x(){}var N={};d(N,l,(function(){return this}));var E=Object.getPrototypeOf,k=E&&E(E(M([])));k&&k!==o&&a.call(k,l)&&(N=k);var _=x.prototype=w.prototype=Object.create(N);function V(e){["next","throw","return"].forEach((function(t){d(e,t,(function(e){return this._invoke(t,e)}))}))}function S(e,t){function o(n,i,s,l){var c=p(e[n],e,i);if("throw"!==c.type){var u=c.arg,d=u.value;return d&&"object"==r(d)&&a.call(d,"__await")?t.resolve(d.__await).then((function(e){o("next",e,s,l)}),(function(e){o("throw",e,s,l)})):t.resolve(d).then((function(e){u.value=e,s(u)}),(function(e){return o("throw",e,s,l)}))}l(c.arg)}var n;i(this,"_invoke",{value:function(e,r){function a(){return new t((function(t,n){o(e,r,t,n)}))}return n=n?n.then(a,a):a()}})}function B(t,o,r){var n=f;return function(a,i){if(n===g)throw new Error("Generator is already running");if(n===y){if("throw"===a)throw i;return{value:e,done:!0}}for(r.method=a,r.arg=i;;){var s=r.delegate;if(s){var l=C(s,r);if(l){if(l===v)continue;return l}}if("next"===r.method)r.sent=r._sent=r.arg;else if("throw"===r.method){if(n===f)throw n=y,r.arg;r.dispatchException(r.arg)}else"return"===r.method&&r.abrupt("return",r.arg);n=g;var c=p(t,o,r);if("normal"===c.type){if(n=r.done?y:h,c.arg===v)continue;return{value:c.arg,done:r.done}}"throw"===c.type&&(n=y,r.method="throw",r.arg=c.arg)}}}function C(t,o){var r=o.method,n=t.iterator[r];if(n===e)return o.delegate=null,"throw"===r&&t.iterator.return&&(o.method="return",o.arg=e,C(t,o),"throw"===o.method)||"return"!==r&&(o.method="throw",o.arg=new TypeError("The iterator does not provide a '"+r+"' method")),v;var a=p(n,t.iterator,o.arg);if("throw"===a.type)return o.method="throw",o.arg=a.arg,o.delegate=null,v;var i=a.arg;return i?i.done?(o[t.resultName]=i.value,o.next=t.nextLoc,"return"!==o.method&&(o.method="next",o.arg=e),o.delegate=null,v):i:(o.method="throw",o.arg=new TypeError("iterator result is not an object"),o.delegate=null,v)}function L(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function D(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function j(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(L,this),this.reset(!0)}function M(t){if(t||""===t){var o=t[l];if(o)return o.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var n=-1,i=function o(){for(;++n<t.length;)if(a.call(t,n))return o.value=t[n],o.done=!1,o;return o.value=e,o.done=!0,o};return i.next=i}}throw new TypeError(r(t)+" is not iterable")}return b.prototype=x,i(_,"constructor",{value:x,configurable:!0}),i(x,"constructor",{value:b,configurable:!0}),b.displayName=d(x,u,"GeneratorFunction"),t.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===b||"GeneratorFunction"===(t.displayName||t.name))},t.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,x):(e.__proto__=x,d(e,u,"GeneratorFunction")),e.prototype=Object.create(_),e},t.awrap=function(e){return{__await:e}},V(S.prototype),d(S.prototype,c,(function(){return this})),t.AsyncIterator=S,t.async=function(e,o,r,n,a){void 0===a&&(a=Promise);var i=new S(m(e,o,r,n),a);return t.isGeneratorFunction(o)?i:i.next().then((function(e){return e.done?e.value:i.next()}))},V(_),d(_,u,"Generator"),d(_,l,(function(){return this})),d(_,"toString",(function(){return"[object Generator]"})),t.keys=function(e){var t=Object(e),o=[];for(var r in t)o.push(r);return o.reverse(),function e(){for(;o.length;){var r=o.pop();if(r in t)return e.value=r,e.done=!1,e}return e.done=!0,e}},t.values=M,j.prototype={constructor:j,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=e,this.done=!1,this.delegate=null,this.method="next",this.arg=e,this.tryEntries.forEach(D),!t)for(var o in this)"t"===o.charAt(0)&&a.call(this,o)&&!isNaN(+o.slice(1))&&(this[o]=e)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var o=this;function r(r,n){return s.type="throw",s.arg=t,o.next=r,n&&(o.method="next",o.arg=e),!!n}for(var n=this.tryEntries.length-1;n>=0;--n){var i=this.tryEntries[n],s=i.completion;if("root"===i.tryLoc)return r("end");if(i.tryLoc<=this.prev){var l=a.call(i,"catchLoc"),c=a.call(i,"finallyLoc");if(l&&c){if(this.prev<i.catchLoc)return r(i.catchLoc,!0);if(this.prev<i.finallyLoc)return r(i.finallyLoc)}else if(l){if(this.prev<i.catchLoc)return r(i.catchLoc,!0)}else{if(!c)throw new Error("try statement without catch or finally");if(this.prev<i.finallyLoc)return r(i.finallyLoc)}}}},abrupt:function(e,t){for(var o=this.tryEntries.length-1;o>=0;--o){var r=this.tryEntries[o];if(r.tryLoc<=this.prev&&a.call(r,"finallyLoc")&&this.prev<r.finallyLoc){var n=r;break}}n&&("break"===e||"continue"===e)&&n.tryLoc<=t&&t<=n.finallyLoc&&(n=null);var i=n?n.completion:{};return i.type=e,i.arg=t,n?(this.method="next",this.next=n.finallyLoc,v):this.complete(i)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),v},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var o=this.tryEntries[t];if(o.finallyLoc===e)return this.complete(o.completion,o.afterLoc),D(o),v}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var o=this.tryEntries[t];if(o.tryLoc===e){var r=o.completion;if("throw"===r.type){var n=r.arg;D(o)}return n}}throw new Error("illegal catch attempt")},delegateYield:function(t,o,r){return this.delegate={iterator:M(t),resultName:o,nextLoc:r},"next"===this.method&&(this.arg=e),v}},t}function a(e,t,o,r,n,a,i){try{var s=e[a](i),l=s.value}catch(e){return void o(e)}s.done?t(l):Promise.resolve(l).then(r,n)}o.d(t,{K:()=>i});var i=(0,o(69876).Q_)("showStore",{state:function(){return{id:0,name:"",episodeName:"",category_id:0,category_sub_id:0,category_description:"",sub_categories:[],description:"",posterName:[],posterId:[0],episodes:[],team_id:"team id",episodePoster:"",noteEdit:0,note:"",saveNoteProcessing:Boolean,errorMessage:"",episodeIsBeingDeleted:0}},actions:{fill:function(){var e,t=this;return(e=n().mark((function e(){var r;return n().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,o.e(6946).then(o.t.bind(o,56946,19));case 2:r=e.sent,t.$state=r.default;case 4:case"end":return e.stop()}}),e)})),function(){var t=this,o=arguments;return new Promise((function(r,n){var i=e.apply(t,o);function s(e){a(i,r,n,s,l,"next",e)}function l(e){a(i,r,n,s,l,"throw",e)}s(void 0)}))})()}},getters:{}})},2448:(e,t,o)=>{o.d(t,{A:()=>a});var r=o(69876),n=o(9680),a=(0,r.Q_)("teamStore",{state:function(){return{id:0,name:"",description:"",slug:"",totalSpots:"",memberSpots:"",teamLeader:[],members:[],managers:[],activeShow:[],activeEpisode:[],creators:[],showModal:Boolean,confirmDialog:!1,confirmManagerDialog:!1,selectedManagerName:"",selectedManagerId:0,addManager:!1,removeManager:!1,deleteMemberName:"",deleteMemberId:0,noteEdit:0,note:"",saveNoteProcessing:Boolean,goLiveDisplay:!1,can:[]}},actions:{setActiveTeam:function(e){this.id=e.id,this.name=e.name,this.description=e.description,this.slug=e.slug,this.totalSpots=e.totalSpots,this.memberSpots=e.memberSpots},setActiveShow:function(e){this.activeShow=e},setActiveEpisode:function(e){this.activeShow=e},deleteTeamMemberCancel:function(){this.confirmDialog=!1},confirmTeamManagerCancel:function(){this.confirmManagerDialog=!1},deleteTeamMember:function(){n.Inertia.visit(route("teams.removeTeamMember"),{method:"post",data:{user_id:this.deleteMemberId,team_id:this.id,team_slug:this.slug}})},addTeamManager:function(){n.Inertia.visit(route("teams.addTeamManager"),{method:"post",data:{user_id:this.selectedManagerId,team_id:this.id,team_slug:this.slug}}),this.confirmManagerDialog=!1},removeTeamManager:function(){n.Inertia.visit(route("teams.removeTeamManager"),{method:"post",data:{user_id:this.selectedManagerId,team_id:this.id,team_slug:this.slug}}),this.confirmManagerDialog=!1},toggleGoLiveDisplay:function(){this.goLiveDisplay=!this.goLiveDisplay}},getters:{spotsRemaining:function(){return this.totalSpots-this.memberSpots<1?0:this.totalSpots-this.memberSpots}}})},86263:(e,t,o)=>{o.d(t,{Z:()=>i});var r=o(70821),n=["src","alt"],a=["src","alt"];const i={__name:"SingleImage",props:{image:Object,class:String,alt:String},setup:function(e){var t=e;return function(o,i){return(0,r.openBlock)(),(0,r.createElementBlock)("div",null,[e.image.folder?(0,r.createCommentVNode)("",!0):((0,r.openBlock)(),(0,r.createElementBlock)("img",{key:0,src:"/storage/images/"+e.image.name,alt:e.alt,class:(0,r.normalizeClass)((0,r.unref)(t).class)},null,10,n)),e.image.folder?((0,r.openBlock)(),(0,r.createElementBlock)("img",{key:1,src:e.image.cdn_endpoint+e.image.cloud_folder+e.image.folder+"/"+e.image.name,alt:e.alt,class:(0,r.normalizeClass)((0,r.unref)(t).class)},null,10,a)):(0,r.createCommentVNode)("",!0)])}}}},3998:(e,t,o)=>{o.d(t,{Z:()=>l});var r=o(70821),n={class:"flex justify-end mt-6"},a={key:0},i={key:1},s={key:2};const l={__name:"EpisodeFooter",props:{team:Object,episode:Object,show:Object},setup:function(e){var t=(new Date).getFullYear();return function(o,l){var c=(0,r.resolveComponent)("Link");return(0,r.openBlock)(),(0,r.createElementBlock)("div",n,[(0,r.createVNode)(c,{href:"/teams/".concat(e.team.slug),class:"text-blue-500 hover:text-blue-700 ml-2 uppercase"},{default:(0,r.withCtx)((function(){return[(0,r.createTextVNode)((0,r.toDisplayString)(e.team.name)+" © ",1),e.show.last_release_year>0?((0,r.openBlock)(),(0,r.createElementBlock)("span",a,(0,r.toDisplayString)(e.show.first_release_year)+"-"+(0,r.toDisplayString)(e.show.last_release_year),1)):(0,r.createCommentVNode)("",!0),!e.show.last_release_year&&e.show.first_release_year?((0,r.openBlock)(),(0,r.createElementBlock)("span",i,(0,r.toDisplayString)(e.show.first_release_year),1)):(0,r.createCommentVNode)("",!0),e.show.last_release_year||e.show.first_release_year?(0,r.createCommentVNode)("",!0):((0,r.openBlock)(),(0,r.createElementBlock)("span",s,(0,r.toDisplayString)((0,r.unref)(t)),1))]})),_:1},8,["href"])])}}}},47727:(e,t,o)=>{o.r(t),o.d(t,{default:()=>me});var r=o(70821),n=o(40191),a=o(2448),i=o(69812),s=o(90771),l=o(3998),c=o(664),u=o(86263),d=o(9680),m={class:"place-self-center flex flex-col gap-y-3 overflow-x-hidden"},p={id:"topDiv",class:"text-white bg-gray-900 rounded py-5 mb-10"},f={key:1,class:"flex flex-end flex-wrap-reverse justify-end gap-2 mr-4 py-5"},h=(0,r.createElementVNode)("button",{class:"px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"},"Manage Episode ",-1),g=(0,r.createElementVNode)("button",{class:"px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"},"Edit ",-1),y=(0,r.createElementVNode)("button",{class:"px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"},"Manage Show",-1),v={class:"p-5 mb-6"},w={class:"flex flex-wrap justify-between px-5"},b={class:""},x={class:"mb-4"},N={class:"mb-1 inline-flex items-center text-3xl font-semibold hover:text-blue-500 relative"},E={class:"mb-1"},k={class:"font-semibold text-xl"},_={class:"text-xs space-y-1"},V=(0,r.createElementVNode)("span",{class:"uppercase"},"Episode Number: ",-1),S={key:0},B={key:1},C={key:0},L={class:"flex flex-col justify-end"},D={class:""},j=(0,r.createElementVNode)("span",{class:"text-xs uppercase"},"Category: ",-1),M={class:"text-sm uppercase font-semibold"},T={class:"pb-4 hidden"},O=(0,r.createElementVNode)("span",{class:"text-xs uppercase"},"Sub-category: ",-1),I={class:"text-sm uppercase font-semibold"},P={key:0},F=(0,r.createElementVNode)("span",{class:"text-xs uppercase"},"Team:",-1),G={class:"text-sm uppercase font-semibold"},A={key:0,class:"mt-12 px-3 py-3 text-gray-50 mr-1 lg:mr-36 bg-black w-full text-center lg:text-left"},Z={class:"flex flex-wrap mt-12 m-auto lg:mx-0 justify-center lg:justify-start space-x-3 space-y-3"},z=(0,r.createElementVNode)("div",null,null,-1),R=["disabled"],Y=(0,r.createElementVNode)("svg",{class:"w-6 h-6 fill-current",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 485 485"},[(0,r.createElementVNode)("path",{d:"M413.974,71.026C368.171,25.225,307.274,0,242.5,0S116.829,25.225,71.026,71.026C25.225,116.829,0,177.726,0,242.5\n\t\ts25.225,125.671,71.026,171.474C116.829,459.775,177.726,485,242.5,485s125.671-25.225,171.474-71.026\n\t\tC459.775,368.171,485,307.274,485,242.5S459.775,116.829,413.974,71.026z M242.5,455C125.327,455,30,359.673,30,242.5\n\t\tS125.327,30,242.5,30S455,125.327,455,242.5S359.673,455,242.5,455z"}),(0,r.createElementVNode)("polygon",{points:"181.062,336.575 343.938,242.5 181.062,148.425 \t"})],-1),U={key:0,class:"ml-2"},K={key:1,class:"ml-2"},Q={disabled:"",class:"flex bg-blue-500 text-white font-semibold ml-4 px-4 py-4 hover:bg-blue-400 rounded transition ease-in-out duration-150 items-center disabled:bg-gray-600 disabled:cursor-not-allowed"},$={class:""},q={disabled:"",class:"flex bg-blue-500 text-white font-semibold ml-4 px-4 py-4 hover:bg-blue-400 rounded transition ease-in-out duration-150 items-center disabled:bg-gray-600 disabled:cursor-not-allowed"},H={class:""},W={class:"my-6 p-5"},J=(0,r.createElementVNode)("div",{class:"font-semibold text-xs uppercase mb-3"},"EPISODE DESCRIPTION",-1),X={class:"flex flex-wrap justify-center shadow overflow-hidden border-y border-gray-200 w-full bg-black text-light text-2xl sm:rounded-lg p-5"},ee={class:"max-w-[50%] ml-5 py-0"},te={class:"flex flex-col px-5"},oe={class:"-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"},re={class:"py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"},ne={class:"mb-6 p-5"},ae=(0,r.createElementVNode)("div",{class:"w-full bg-gray-800 text-2xl p-4 mb-8"},"BONUS CONTENT",-1),ie=(0,r.createElementVNode)("div",{class:"mb-8 p-4"},[(0,r.createElementVNode)("span",{class:"text-orange-500"},"Bonus content will go here. This includes content mentioned in an episode.")],-1),se=(0,r.createElementVNode)("div",{class:"w-full bg-gray-800 text-2xl p-4 mb-8"},"CREDITS",-1),le={class:"flex flex-row flex-wrap"},ce={class:"flex flex-col min-w-[8rem] px-6 py-4 font-medium break-words grow-0"},ue=["src"],de={class:"light:text-gray-800 dark:text-gray-200 w-full text-center"};const me={__name:"Index",props:{show:Object,team:Object,episode:Object,image:Object,creators:Object,can:Object},setup:function(e){var t=e,o=(0,n.q)(),me=(0,a.A)(),pe=((0,i.K)(),(0,s.L)()),fe=function(){o.videoName='<Link :href="/shows/'.concat(t.show.slug,'">').concat(t.show.name,"</Link>"),o.nowPlayingUrl="/shows/".concat(t.show.slug,"/episode/").concat(t.episode.slug),o.nowPlayingName=t.episode.name,o.nowPlayingDescription=t.episode.description,o.nowPlayingImage=t.image,o.nowPlayingTeam=t.team,o.nowPlayingCreators=t.creators.data,o.nowPlayingBonusContent=[],"spaces"===t.episode.video.storage_location&&"processing"!==t.episode.video.upload_status?(o.loadNewSourceFromFile(t.episode.video),o.videoName=t.episode.name,d.Inertia.visit("/stream")):t.episode.video.video_url?(o.loadNewSourceFromUrl(t.episode.video),o.videoName=t.episode.name,d.Inertia.visit("/stream")):t.episode.youtube_url&&(o.loadNewSourceFromYouTube(t.episode.youtube_url),o.videoName=t.episode.name,d.Inertia.visit("/stream"))};return pe.currentPage="episodes",pe.showFlashMessage=!0,me.slug=t.team.slug,me.name=t.team.name,(0,r.onMounted)((function(){o.makeVideoTopRight(),pe.isMobile&&(o.ottClass="ottClose",o.ott=0),document.getElementById("topDiv").scrollIntoView()})),function(n,a){var i=(0,r.resolveComponent)("Head"),s=(0,r.resolveComponent)("Link"),d=(0,r.resolveComponent)("font-awesome-icon");return(0,r.openBlock)(),(0,r.createElementBlock)(r.Fragment,null,[(0,r.createVNode)(i,{title:"".concat((0,r.unref)(t).show.name,": ").concat((0,r.unref)(t).episode.name)},null,8,["title"]),(0,r.createElementVNode)("div",m,[(0,r.createElementVNode)("div",p,[(0,r.unref)(pe).showFlashMessage?((0,r.openBlock)(),(0,r.createBlock)((0,r.unref)(c.Z),{key:0,flash:n.$page.props.flash},null,8,["flash"])):(0,r.createCommentVNode)("",!0),(0,r.unref)(t).can.editShow||(0,r.unref)(t).can.manageShow?((0,r.openBlock)(),(0,r.createElementBlock)("div",f,[(0,r.unref)(t).can.manageShow?((0,r.openBlock)(),(0,r.createBlock)(s,{key:0,href:"/shows/".concat((0,r.unref)(t).show.slug,"/episode/").concat((0,r.unref)(t).episode.slug,"/manage")},{default:(0,r.withCtx)((function(){return[h]})),_:1},8,["href"])):(0,r.createCommentVNode)("",!0),(0,r.unref)(t).can.editShow?((0,r.openBlock)(),(0,r.createBlock)(s,{key:1,href:"/shows/".concat((0,r.unref)(t).show.slug,"/episode/").concat((0,r.unref)(t).episode.slug,"/edit")},{default:(0,r.withCtx)((function(){return[g]})),_:1},8,["href"])):(0,r.createCommentVNode)("",!0),(0,r.unref)(t).can.manageShow?((0,r.openBlock)(),(0,r.createBlock)(s,{key:2,href:"/shows/".concat((0,r.unref)(t).show.slug,"/manage")},{default:(0,r.withCtx)((function(){return[y]})),_:1},8,["href"])):(0,r.createCommentVNode)("",!0)])):(0,r.createCommentVNode)("",!0),(0,r.createElementVNode)("header",v,[(0,r.createElementVNode)("div",w,[(0,r.createElementVNode)("div",b,[(0,r.createElementVNode)("div",x,[(0,r.createElementVNode)("h3",N,[(0,r.createVNode)(s,{href:"/shows/".concat((0,r.unref)(t).show.slug,"/")},{default:(0,r.withCtx)((function(){return[(0,r.createTextVNode)((0,r.toDisplayString)((0,r.unref)(t).show.name),1)]})),_:1},8,["href"])]),(0,r.createElementVNode)("div",E,[(0,r.createElementVNode)("span",k,(0,r.toDisplayString)((0,r.unref)(t).episode.name),1)]),(0,r.createElementVNode)("div",_,[V,e.episode.episode_number?(0,r.createCommentVNode)("",!0):((0,r.openBlock)(),(0,r.createElementBlock)("span",S,(0,r.toDisplayString)(e.episode.id),1)),(0,r.unref)(t).episode.episode_number?((0,r.openBlock)(),(0,r.createElementBlock)("span",B,(0,r.toDisplayString)((0,r.unref)(t).episode.episode_number),1)):(0,r.createCommentVNode)("",!0)])]),(0,r.unref)(t).episode.release_dateTime?((0,r.openBlock)(),(0,r.createElementBlock)("div",C,(0,r.toDisplayString)(n.formatDate((0,r.unref)(t).episode.release_dateTime)),1)):(0,r.createCommentVNode)("",!0)]),(0,r.createElementVNode)("div",L,[(0,r.createElementVNode)("div",D,[j,(0,r.createElementVNode)("span",M,(0,r.toDisplayString)((0,r.unref)(t).show.categoryName),1)]),(0,r.createElementVNode)("div",T,[O,(0,r.createElementVNode)("span",I,(0,r.toDisplayString)((0,r.unref)(t).show.categorySubName),1)]),(0,r.unref)(t).can.viewCreator?((0,r.openBlock)(),(0,r.createElementBlock)("div",P,[F,(0,r.createVNode)(s,{href:"/teams/".concat((0,r.unref)(t).team.slug),class:"text-blue-300 hover:text-blue-500 ml-2"},{default:(0,r.withCtx)((function(){return[(0,r.createElementVNode)("span",G,(0,r.toDisplayString)((0,r.unref)(t).team.name),1)]})),_:1},8,["href"])])):(0,r.createCommentVNode)("",!0)])]),"processing"!==e.episode.video.upload_status||e.episode.video.video_url?(0,r.createCommentVNode)("",!0):((0,r.openBlock)(),(0,r.createElementBlock)("p",A," The episode video is currently processing. Please check back later. ")),(0,r.createElementVNode)("div",Z,[z,"spaces"===e.episode.video.storage_location||e.episode.video.video_url?((0,r.openBlock)(),(0,r.createElementBlock)("button",{key:0,disabled:"processing"===e.episode.video.upload_status&&!e.episode.video.video_url,class:"flex bg-blue-500 text-white font-semibold ml-4 px-4 py-4 hover:bg-blue-700 rounded transition ease-in-out duration-150 items-center disabled:bg-gray-600 disabled:cursor-not-allowed",onClick:a[0]||(a[0]=function(){return(0,r.unref)(fe)&&(0,r.unref)(fe).apply(void 0,arguments)})},[Y,(0,r.unref)(o).nowPlayingName===(0,r.unref)(t).episode.name?((0,r.openBlock)(),(0,r.createElementBlock)("span",U,"Now Playing")):((0,r.openBlock)(),(0,r.createElementBlock)("span",K,"Watch Episode"))],8,R)):(0,r.createCommentVNode)("",!0),(0,r.createElementVNode)("button",Q,[(0,r.createElementVNode)("span",$,[(0,r.createVNode)(d,{icon:"fa-circle-down",class:"mr-2"}),(0,r.createTextVNode)("Save For Later")])]),(0,r.createElementVNode)("button",q,[(0,r.createElementVNode)("span",H,[(0,r.createVNode)(d,{icon:"fa-share",class:"mr-2"}),(0,r.createTextVNode)("Share")])])])]),(0,r.createElementVNode)("div",W,[J,(0,r.createElementVNode)("div",null,(0,r.toDisplayString)((0,r.unref)(t).episode.description),1)]),(0,r.createElementVNode)("div",X,[(0,r.createElementVNode)("div",ee,[((0,r.openBlock)(),(0,r.createBlock)((0,r.unref)(u.Z),{image:e.image,key:e.image},null,8,["image"]))])]),(0,r.createElementVNode)("div",te,[(0,r.createElementVNode)("div",oe,[(0,r.createElementVNode)("div",re,[(0,r.createElementVNode)("div",ne,[ae,ie,se,(0,r.createElementVNode)("div",le,[((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)((0,r.unref)(t).creators.data,(function(e){return(0,r.openBlock)(),(0,r.createElementBlock)("div",{key:e.id,class:"pb-8 light:bg-light dark:bg-gray-900"},[(0,r.createElementVNode)("div",ce,[(0,r.createElementVNode)("img",{src:"/storage/"+e.profile_photo_path,class:"pb-2 rounded-full h-32 w-32 object-cover mb-2"},null,8,ue),(0,r.createElementVNode)("span",de,(0,r.toDisplayString)(e.name),1)])])})),128))])]),(0,r.createVNode)((0,r.unref)(l.Z),{team:(0,r.unref)(t).team,epsiode:(0,r.unref)(t).episode,show:(0,r.unref)(t).show},null,8,["team","epsiode","show"])])])])])])],64)}}}}}]);
//# sourceMappingURL=7727.js.map