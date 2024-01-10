"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[2276],{2448:(e,t,r)=>{r.d(t,{A:()=>a});var n=r(9876),o=r(9680),a=(0,n.Q_)("teamStore",{state:function(){return{id:0,name:"",description:"",slug:"",totalSpots:"",memberSpots:"",teamLeader:[],members:[],managers:[],activeShow:[],activeEpisode:[],creators:[],showModal:Boolean,confirmDialog:!1,confirmManagerDialog:!1,selectedManagerName:"",selectedManagerId:0,addManager:!1,removeManager:!1,deleteMemberName:"",deleteMemberId:0,noteEdit:0,note:"",saveNoteProcessing:Boolean,goLiveDisplay:!1,can:[]}},actions:{setActiveTeam:function(e){this.id=e.id,this.name=e.name,this.description=e.description,this.slug=e.slug,this.totalSpots=e.totalSpots,this.memberSpots=e.memberSpots},setActiveShow:function(e){this.activeShow=e},setActiveEpisode:function(e){this.activeShow=e},deleteTeamMemberCancel:function(){this.confirmDialog=!1},confirmTeamManagerCancel:function(){this.confirmManagerDialog=!1},deleteTeamMember:function(){o.Inertia.visit(route("teams.removeTeamMember"),{method:"post",data:{user_id:this.deleteMemberId,team_id:this.id,team_slug:this.slug}})},addTeamManager:function(){o.Inertia.visit(route("teams.addTeamManager"),{method:"post",data:{user_id:this.selectedManagerId,team_id:this.id,team_slug:this.slug}}),this.confirmManagerDialog=!1},removeTeamManager:function(){o.Inertia.visit(route("teams.removeTeamManager"),{method:"post",data:{user_id:this.selectedManagerId,team_id:this.id,team_slug:this.slug}}),this.confirmManagerDialog=!1},toggleGoLiveDisplay:function(){this.goLiveDisplay=!this.goLiveDisplay}},getters:{spotsRemaining:function(){return this.totalSpots-this.memberSpots<1?0:this.totalSpots-this.memberSpots}}})},1720:(e,t,r)=>{r.d(t,{Z:()=>l});var n=r(821),o=r(9680),a=r(771);const l={__name:"CancelButton",setup:function(e){var t=(0,a.L)();function r(){t.prevUrl&&o.Inertia.visit(t.prevUrl)}return function(e,t){return(0,n.openBlock)(),(0,n.createElementBlock)("div",null,[(0,n.createElementVNode)("button",{onClick:r,class:"ml-2 px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"},"Cancel ")])}}}},6414:(e,t,r)=>{r.d(t,{Z:()=>a});var n=r(821),o=["onKeydown","textContent"];const a={__name:"TabbableTextarea",props:{modelValue:String},emits:["update:modelValue"],setup:function(e,t){var r=t.emit;function a(e){var t=e.target,r=t.value,n=t.selectionStart,o=t.selectionEnd;t.value=r.substring(0,n)+"\t"+r.substring(o),t.selectionStart=t.selectionEnd=n+1}return function(t,l){return(0,n.openBlock)(),(0,n.createElementBlock)("textarea",{onKeydown:(0,n.withKeys)((0,n.withModifiers)(a,["prevent"]),["tab"]),onKeyup:l[0]||(l[0]=function(e){return(0,n.unref)(r)("update:modelValue",e.target.value)}),textContent:(0,n.toDisplayString)(e.modelValue)},null,40,o)}}}},762:(e,t,r)=>{r.d(t,{Z:()=>V});var n=r(821),o=r(1658),a=r.n(o),l=r(521),s=r.n(l),i=r(8236),c=r.n(i),d=r(2965),m=r.n(d),u=r(2185),p=r.n(u);r(6647),r(6593),r(9680);function g(e){return g="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},g(e)}function f(e,t,r){var n;return n=function(e,t){if("object"!=g(e)||!e)return e;var r=e[Symbol.toPrimitive];if(void 0!==r){var n=r.call(e,t||"default");if("object"!=g(n))return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(t,"string"),(t="symbol"==g(n)?n:String(n))in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}var b={class:""},x={class:"max-full mx-auto mt-2 mb-6 bg-gray-200 p-6 text-black"},v={class:"text-xl font-semibold"},h={class:"pb-4"},y={class:"text-orange-400"},w={class:"text-orange-400"};const V={__name:"ImageUpload",props:{image:Object,name:String,metadataKey:String,metadataValue:String,server:String,maxSize:String,fileTypes:String},emits:["reloadImage"],setup:function(e,t){var r=t.emit,o=e,l=(f({},o.metadataKey,o.metadataValue),a()(s(),c(),m(),p()));function i(){console.log("Filepond is ready!")}l.setOptions={fileMetadataObject:{show_id:"1"}};var d=r;function u(e,t){if(e)return console.log("Filepond processed file"),console.log(e),void console.log(t);d("reloadImage")}return function(t,r){return(0,n.openBlock)(),(0,n.createElementBlock)("div",b,[(0,n.createElementVNode)("div",x,[(0,n.createElementVNode)("h2",v,(0,n.toDisplayString)(e.name),1),(0,n.createElementVNode)("ul",h,[(0,n.createElementVNode)("li",null,[(0,n.createTextVNode)("Max File Size: "),(0,n.createElementVNode)("span",y,(0,n.toDisplayString)(e.maxSize),1)]),(0,n.createElementVNode)("li",null,[(0,n.createTextVNode)("File Types accepted: "),(0,n.createElementVNode)("span",w,(0,n.toDisplayString)(e.fileTypes),1)])]),(0,n.createVNode)((0,n.unref)(l),{name:"image",ref:"pond","label-idle":"Click to choose file, or drag here...",onInit:i,server:e.server,"accepted-file-types":e.fileTypes,onProcessfile:u,"max-file-size":e.maxSize},null,8,["server","accepted-file-types","max-file-size"])])])}}}},131:(e,t,r)=>{r.d(t,{Z:()=>i});var n=r(821),o=r(9038),a={key:0},l=(0,n.createElementVNode)("div",{class:"font-medium text-red-600"}," Whoops! Something went wrong. ",-1),s={class:"mt-3 list-disc list-inside text-sm text-red-600"};const i={__name:"ValidationErrors",setup:function(e){var t=(0,n.computed)((function(){return(0,o.qt)().props.value.errors})),r=(0,n.computed)((function(){return Object.keys(t.value).length>0}));return function(e,o){return r.value?((0,n.openBlock)(),(0,n.createElementBlock)("div",a,[l,(0,n.createElementVNode)("ul",s,[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)(t.value,(function(e,t){return(0,n.openBlock)(),(0,n.createElementBlock)("li",{key:t},(0,n.toDisplayString)(e),1)})),128))])])):(0,n.createCommentVNode)("",!0)}}}},2276:(e,t,r)=>{r.r(t),r.d(t,{default:()=>xe});var n=r(821),o=r(9680),a=r(9038),l=r(191),s=r(2448),i=r(9812),c=r(771),d=r(131),m=r(6414),u=r(1720),p={class:"flex justify-between pt-6 mb-6"},g=(0,n.createElementVNode)("div",{class:"font-bold mb-4 text-red-700"},"EDIT SHOW",-1),f={class:"text-3xl"},b={class:"flex flex-wrap-reverse justify-end gap-2"},x=["disabled"],v={class:"mb-6"},h=(0,n.createElementVNode)("span",{class:"text-xs uppercase font-semibold"},"Team: ",-1);const y={__name:"ShowEditHeader",props:{show:Object,team:Object,form:Object},emits:["submit"],setup:function(e,t){var r=t.emit,o=(0,c.L)(),a=r,l=function(){a("submit")};return function(t,r){var a=(0,n.resolveComponent)("Link");return(0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,null,[(0,n.createElementVNode)("header",null,[(0,n.createElementVNode)("div",p,[(0,n.createElementVNode)("div",null,[g,(0,n.createElementVNode)("h1",f,[(0,n.createVNode)(a,{href:"/shows/".concat(e.show.slug),class:"text-red-700 font-bold uppercase"},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)((0,n.toDisplayString)(e.show.name),1)]})),_:1},8,["href"])])]),(0,n.createElementVNode)("div",b,[(0,n.createElementVNode)("div",null,[(0,n.createElementVNode)("button",{type:"submit",class:"h-fit bg-blue-600 hover:bg-blue-500 text-white rounded-lg py-2 px-4",disabled:e.form.processing,onClick:l}," Save ",8,x)]),(0,n.createVNode)(u.Z)])])]),(0,n.createElementVNode)("div",null,[(0,n.createElementVNode)("div",v,[h,(0,n.createElementVNode)("button",{onClick:r[0]||(r[0]=function(t){return(0,n.unref)(o).btnRedirect("/teams/".concat(e.team.slug))}),class:"font-bold uppercase text-blue-700 dark:text-blue-300 hover:text-blue-500"},(0,n.toDisplayString)(e.team.name),1)])])],64)}}};var w=r(6263),V=r(664),E=r(762),k={id:"topDiv",class:"place-self-center flex flex-col gap-y-3"},N={class:"bg-white text-black dark:bg-gray-800 dark:text-gray-50 px-5 mb-10"},S={class:"flex flex-col"},_={class:"-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"},C={class:"py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"},B={class:"shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"},D=["textContent"],M=["textContent"],T={class:"grid grid-cols-1 sm:grid-cols-2 space-x-6 p-6"},I={class:"flex space-y-3"},j={class:"mb-6"},O={class:"w-full"},U=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-light text-red-700",for:"name"}," Change Show Poster ",-1),L={class:"mb-6"},F=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-light text-red-700",for:"name"}," Show Notes (only visible to team members) ",-1),Z=["textContent"],P=(0,n.createElementVNode)("div",{class:"mb-6"},null,-1),K={class:"mb-6"},z=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-light text-red-700",for:"name"}," Show Name ",-1),A=["textContent"],R={class:"mb-6"},q=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-light text-red-700",for:"category"}," Category ",-1),H=["value"],W=["textContent"],G={class:"dark:text-gray-50"},Q={class:"mb-6"},$=(0,n.createElementVNode)("label",{class:"block mb-1 text-gray-600 uppercase font-bold text-xs text-light text-gray-600",for:"sub_category"}," Sub-category ",-1),J=(0,n.createElementVNode)("div",{class:"mb-2 text-sm text-orange-600"},"Sub-categories are coming soon!",-1),X=[(0,n.createElementVNode)("option",{value:"1"},"Option",-1)],Y=["textContent"],ee={class:"mb-6"},te=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-light text-red-700",for:"description"}," Description ",-1),re=["textContent"],ne={class:"mb-6"},oe=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-light text-red-700",for:"name"}," Website URL ",-1),ae=["textContent"],le={class:"mb-6"},se=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-light text-red-700",for:"name"}," Instagram Handle ",-1),ie=["textContent"],ce={class:"mb-6"},de=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-light text-red-700",for:"name"}," Telegram URL ",-1),me=["textContent"],ue={class:"mb-6"},pe=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-light text-red-700",for:"name"}," Twitter @ ",-1),ge=["textContent"],fe={class:"flex justify-end mb-6"},be=["disabled"];const xe={__name:"Edit",props:{user:Object,show:Object,team:Object,poster:String,image:Object,categories:Object,subCategories:Object,showCategory:Object,message:String},setup:function(e){var t=(0,l.q)(),r=(0,s.A)(),u=((0,i.K)(),(0,c.L)()),p=e,g=(0,a.cI)({name:p.show.name,description:p.show.description,category:p.show.show_category_id,sub_category:p.show.show_category_sub_id,www_url:p.show.www_url,instagram_name:p.show.instagram_name,telegram_url:p.show.telegram_url,twitter_handle:p.show.twitter_handle,notes:p.show.notes}),f=p.showCategory.Description,b=function(){o.Inertia.reload({only:["image"]})},x=function(){g.put(route("shows.update",p.show.slug))};return u.currentPage="shows",u.showFlashMessage=!0,r.setActiveTeam(p.team),r.setActiveShow(p.show),(0,n.onMounted)((function(){t.makeVideoTopRight(),u.isMobile&&(t.ottClass="ottClose",t.ott=0),document.getElementById("topDiv").scrollIntoView()})),function(e,t){var r=(0,n.resolveComponent)("Head");return(0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,null,[(0,n.createVNode)(r,{title:"Edit Show: ".concat((0,n.unref)(p).show.name)},null,8,["title"]),(0,n.createElementVNode)("div",k,[(0,n.createElementVNode)("div",N,[(0,n.unref)(u).showFlashMessage?((0,n.openBlock)(),(0,n.createBlock)((0,n.unref)(V.Z),{key:0,flash:e.$page.props.flash},null,8,["flash"])):(0,n.createCommentVNode)("",!0),(0,n.createVNode)((0,n.unref)(y),{show:(0,n.unref)(p).show,team:(0,n.unref)(p).team,form:(0,n.unref)(g),onSubmit:(0,n.unref)(x)},null,8,["show","team","form","onSubmit"]),(0,n.createElementVNode)("div",S,[(0,n.createElementVNode)("div",_,[(0,n.createElementVNode)("div",C,[(0,n.createElementVNode)("div",B,[(0,n.unref)(g).errors.name?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(g).errors.name),class:"bg-red-600 p-2 w-full text-white font-semibold mt-1 mb-6"},null,8,D)):(0,n.createCommentVNode)("",!0),(0,n.unref)(g).errors.description?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:1,textContent:(0,n.toDisplayString)((0,n.unref)(g).errors.description),class:"bg-red-600 p-2 w-full text-white font-semibold mt-1 mb-6"},null,8,M)):(0,n.createCommentVNode)("",!0),(0,n.createElementVNode)("div",T,[(0,n.createElementVNode)("div",null,[(0,n.createElementVNode)("div",I,[(0,n.createElementVNode)("div",j,[((0,n.openBlock)(),(0,n.createBlock)((0,n.unref)(w.Z),{image:(0,n.unref)(p).image,key:(0,n.unref)(p).image},null,8,["image"]))])]),(0,n.createElementVNode)("div",O,[U,(0,n.createVNode)((0,n.unref)(E.Z),{image:(0,n.unref)(p).image,server:"/showsUploadPoster",name:"Upload Show Poster",metadataKey:"foo2",metadataValue:"bar2",maxSize:"30MB",fileTypes:"image/jpg, image/jpeg, image/png",onReloadImage:(0,n.unref)(b)},null,8,["image","onReloadImage"])])]),(0,n.createElementVNode)("div",null,[(0,n.createElementVNode)("form",{onSubmit:t[10]||(t[10]=(0,n.withModifiers)((function(){return(0,n.unref)(x)&&(0,n.unref)(x).apply(void 0,arguments)}),["prevent"]))},[(0,n.createElementVNode)("div",L,[F,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[0]||(t[0]=function(e){return(0,n.unref)(g).notes=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"notes",id:"notes"},null,512),[[n.vModelText,(0,n.unref)(g).notes]]),(0,n.unref)(g).errors.notes?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(g).errors.notes),class:"text-xs text-red-600 mt-1"},null,8,Z)):(0,n.createCommentVNode)("",!0)]),P,(0,n.createElementVNode)("div",K,[z,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[1]||(t[1]=function(e){return(0,n.unref)(g).name=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"name",id:"name",required:""},null,512),[[n.vModelText,(0,n.unref)(g).name]]),(0,n.unref)(g).errors.name?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(g).errors.name),class:"text-xs text-red-600 mt-1"},null,8,A)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",R,[q,(0,n.withDirectives)((0,n.createElementVNode)("select",{class:"border border-gray-400 text-gray-800 p-2 w-full rounded-lg block my-2 uppercase font-bold text-xs","onUpdate:modelValue":t[2]||(t[2]=function(e){return(0,n.unref)(g).category=e}),onChange:t[3]||(t[3]=function(e){return t=e,void(f=p.categories[t.target.selectedIndex].description);var t})},[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)((0,n.unref)(p).categories,(function(e){return(0,n.openBlock)(),(0,n.createElementBlock)("option",{key:e.id,value:e.id},(0,n.toDisplayString)(e.name),9,H)})),128))],544),[[n.vModelSelect,(0,n.unref)(g).category]]),(0,n.unref)(g).errors.category?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(g).errors.category),class:"text-xs text-red-600 mt-1"},null,8,W)):(0,n.createCommentVNode)("",!0),(0,n.createElementVNode)("span",G,(0,n.toDisplayString)((0,n.unref)(f)),1)]),(0,n.createElementVNode)("div",Q,[$,J,(0,n.withDirectives)((0,n.createElementVNode)("select",{disabled:"",class:"border border-gray-400 text-gray-800 disabled:bg-gray-300 dark:disabled:bg-gray-600 disabled:cursor-not-allowed p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs","onUpdate:modelValue":t[4]||(t[4]=function(e){return(0,n.unref)(g).sub_category=e})},X,512),[[n.vModelSelect,(0,n.unref)(g).sub_category]]),(0,n.unref)(g).errors.sub_category?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(g).errors.sub_category),class:"text-xs text-red-600 mt-1"},null,8,Y)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",ee,[te,(0,n.createVNode)((0,n.unref)(m.Z),{modelValue:(0,n.unref)(g).description,"onUpdate:modelValue":t[5]||(t[5]=function(e){return(0,n.unref)(g).description=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",name:"description",id:"description",rows:"10",cols:"30",required:""},null,8,["modelValue"]),(0,n.unref)(g).errors.description?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(g).errors.description),class:"text-xs text-red-600 mt-1"},null,8,re)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",ne,[oe,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[6]||(t[6]=function(e){return(0,n.unref)(g).www_url=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"www_url",id:"www_url"},null,512),[[n.vModelText,(0,n.unref)(g).www_url]]),(0,n.unref)(g).errors.www_url?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(g).errors.www_url),class:"text-xs text-red-600 mt-1"},null,8,ae)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",le,[se,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[7]||(t[7]=function(e){return(0,n.unref)(g).instagram_name=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"instagram_name handle",id:"instagram_name"},null,512),[[n.vModelText,(0,n.unref)(g).instagram_name]]),(0,n.unref)(g).errors.instagram_name?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(g).errors.instagram_name),class:"text-xs text-red-600 mt-1"},null,8,ie)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",ce,[de,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[8]||(t[8]=function(e){return(0,n.unref)(g).telegram_url=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"telegram_url",id:"telegram_url"},null,512),[[n.vModelText,(0,n.unref)(g).telegram_url]]),(0,n.unref)(g).errors.telegram_url?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(g).errors.telegram_url),class:"text-xs text-red-600 mt-1"},null,8,me)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",ue,[pe,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[9]||(t[9]=function(e){return(0,n.unref)(g).twitter_handle=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"twitter_handle",id:"twitter_handle"},null,512),[[n.vModelText,(0,n.unref)(g).twitter_handle]]),(0,n.unref)(g).errors.twitter_handle?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(g).errors.twitter_handle),class:"text-xs text-red-600 mt-1"},null,8,ge)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",fe,[(0,n.createVNode)(d.Z,{class:"mr-4"}),(0,n.createElementVNode)("button",{type:"submit",class:"h-fit bg-blue-600 hover:bg-blue-500 text-white rounded-lg py-2 px-4",disabled:(0,n.unref)(g).processing}," Save ",8,be)])],32)])])])])])])])])],64)}}}}}]);
//# sourceMappingURL=2276.js.map