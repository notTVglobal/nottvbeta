"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[8869],{2448:(e,t,r)=>{r.d(t,{A:()=>a});var o=r(9876),n=r(9680),a=(0,o.Q_)("teamStore",{state:function(){return{id:0,name:"",description:"",slug:"",totalSpots:"",memberSpots:"",teamLeader:[],members:[],managers:[],activeShow:[],activeEpisode:[],creators:[],showModal:Boolean,confirmDialog:!1,confirmManagerDialog:!1,selectedManagerName:"",selectedManagerId:0,addManager:!1,removeManager:!1,deleteMemberName:"",deleteMemberId:0,noteEdit:0,note:"",saveNoteProcessing:Boolean,goLiveDisplay:!1,can:[]}},actions:{setActiveTeam:function(e){this.id=e.id,this.name=e.name,this.description=e.description,this.slug=e.slug,this.totalSpots=e.totalSpots,this.memberSpots=e.memberSpots},setActiveShow:function(e){this.activeShow=e},setActiveEpisode:function(e){this.activeShow=e},deleteTeamMemberCancel:function(){this.confirmDialog=!1},confirmTeamManagerCancel:function(){this.confirmManagerDialog=!1},deleteTeamMember:function(){n.Inertia.visit(route("teams.removeTeamMember"),{method:"post",data:{user_id:this.deleteMemberId,team_id:this.id,team_slug:this.slug}})},addTeamManager:function(){n.Inertia.visit(route("teams.addTeamManager"),{method:"post",data:{user_id:this.selectedManagerId,team_id:this.id,team_slug:this.slug}}),this.confirmManagerDialog=!1},removeTeamManager:function(){n.Inertia.visit(route("teams.removeTeamManager"),{method:"post",data:{user_id:this.selectedManagerId,team_id:this.id,team_slug:this.slug}}),this.confirmManagerDialog=!1},toggleGoLiveDisplay:function(){this.goLiveDisplay=!this.goLiveDisplay}},getters:{spotsRemaining:function(){return this.totalSpots-this.memberSpots<1?0:this.totalSpots-this.memberSpots}}})},1720:(e,t,r)=>{r.d(t,{Z:()=>l});var o=r(821),n=r(9680),a=r(771);const l={__name:"CancelButton",setup:function(e){var t=(0,a.L)();function r(){t.prevUrl&&n.Inertia.visit(t.prevUrl)}return function(e,t){return(0,o.openBlock)(),(0,o.createElementBlock)("div",null,[(0,o.createElementVNode)("button",{onClick:r,class:"ml-2 px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"},"Cancel ")])}}}},131:(e,t,r)=>{r.d(t,{Z:()=>d});var o=r(821),n=r(9038),a={key:0},l=(0,o.createElementVNode)("div",{class:"font-medium text-red-600"}," Whoops! Something went wrong. ",-1),s={class:"mt-3 list-disc list-inside text-sm text-red-600"};const d={__name:"ValidationErrors",setup:function(e){var t=(0,o.computed)((function(){return(0,n.qt)().props.value.errors})),r=(0,o.computed)((function(){return Object.keys(t.value).length>0}));return function(e,n){return r.value?((0,o.openBlock)(),(0,o.createElementBlock)("div",a,[l,(0,o.createElementVNode)("ul",s,[((0,o.openBlock)(!0),(0,o.createElementBlock)(o.Fragment,null,(0,o.renderList)(t.value,(function(e,t){return(0,o.openBlock)(),(0,o.createElementBlock)("li",{key:t},(0,o.toDisplayString)(e),1)})),128))])])):(0,o.createCommentVNode)("",!0)}}}},8869:(e,t,r)=>{r.r(t),r.d(t,{default:()=>X});var o=r(821),n=r(9038),a=r(191),l=r(2448),s=r(771),d=r(664),c=r(131),i=(r(9680),r(1720)),u={class:"place-self-center flex flex-col gap-y-3"},m={id:"topDiv",class:"bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10"},g={class:"flex justify-between mt-3 mb-6"},p=(0,o.createElementVNode)("div",{class:"text-3xl"},"Create Show",-1),b=(0,o.createElementVNode)("div",{class:"bg-orange-700 text-white w-full p-6"},[(0,o.createElementVNode)("span",{class:"font-bold"},"NOTE: "),(0,o.createTextVNode)(" We are working on an episode poster and video uploader for this page. For the time being, please go to the show "),(0,o.createElementVNode)("span",{class:"font-bold"},"EDIT"),(0,o.createTextVNode)(" page after you create the show to add a video and a poster. ")],-1),f={class:"mb-6"},x=(0,o.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs dark:text-gray-200",for:"name"}," Team ",-1),v=["value"],y=["textContent"],k={class:"mb-6"},h=(0,o.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs dark:text-gray-200",for:"name"}," Show Name ",-1),w=["textContent"],V={class:"mb-6"},E=(0,o.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs dark:text-gray-200",for:"category"}," Category ",-1),N=["value"],_=["textContent"],C={class:"mb-6"},B=(0,o.createElementVNode)("label",{class:"block mb-1 uppercase font-bold text-xs dark:text-gray-200",for:"sub_category"}," Sub-category ",-1),S=(0,o.createElementVNode)("div",{class:"mb-2 text-sm text-orange-600"},"Sub-categories are coming soon!",-1),D=[(0,o.createElementVNode)("option",{value:"1"},"Option",-1)],M=["textContent"],T={class:"mb-6"},I=(0,o.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs dark:text-gray-200",for:"description"}," Description ",-1),U=["textContent"],L={class:"mb-6"},F=(0,o.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs dark:text-gray-200",for:"name"}," Website URL ",-1),O=["textContent"],j={class:"mb-6"},q=(0,o.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs dark:text-gray-200",for:"name"}," Instagram Handle ",-1),A=["textContent"],Z={class:"mb-6"},R=(0,o.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs dark:text-gray-200",for:"name"}," Telegram URL ",-1),W=["textContent"],H={class:"mb-6"},P=(0,o.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs dark:text-gray-200",for:"name"}," Twitter @ ",-1),G=["textContent"],Q={class:"mb-6"},$=(0,o.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs dark:text-gray-200",for:"notes"}," Notes (Only your team members see these notes, they are not public) ",-1),z=["textContent"],J={class:"flex justify-between mb-6"},K=["disabled"];const X={__name:"Create",props:{teams:Object,userId:Number,categories:Object,subCategories:Object},setup:function(e){var t=(0,a.q)(),r=(0,l.A)(),X=(0,s.L)();X.currentPage="shows",X.showFlashMessage=!0,(0,o.onMounted)((function(){t.makeVideoTopRight(),X.isMobile&&(t.ottClass="ottClose",t.ott=0),document.getElementById("topDiv").scrollIntoView()}));var Y=e,ee=(0,n.cI)({name:"",description:"",user_id:Y.userId,team_id:"",category:"",sub_category:"",www_url:"",instagram_name:"",telegram_url:"",twitter_handle:"",notes:""});ee.team_id=r.id;var te=(0,o.ref)(null);var re=function(){ee.post("/shows")};return function(e,t){var r=(0,o.resolveComponent)("Head");return(0,o.openBlock)(),(0,o.createElementBlock)(o.Fragment,null,[(0,o.createVNode)(r,{title:"Create Show"}),(0,o.createElementVNode)("div",u,[(0,o.createElementVNode)("div",m,[(0,o.unref)(X).showFlashMessage?((0,o.openBlock)(),(0,o.createBlock)((0,o.unref)(d.Z),{key:0,flash:e.$page.props.flash},null,8,["flash"])):(0,o.createCommentVNode)("",!0),(0,o.createElementVNode)("div",g,[p,(0,o.createElementVNode)("div",null,[(0,o.createVNode)(i.Z)])]),b,(0,o.createElementVNode)("form",{onSubmit:t[12]||(t[12]=(0,o.withModifiers)((function(){return(0,o.unref)(re)&&(0,o.unref)(re).apply(void 0,arguments)}),["prevent"])),class:"max-w-md mx-auto mt-8"},[(0,o.createElementVNode)("div",f,[x,(0,o.withDirectives)((0,o.createElementVNode)("select",{class:"border border-gray-400 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs text-gray-800","onUpdate:modelValue":t[0]||(t[0]=function(e){return(0,o.unref)(ee).team_id=e}),required:""},[((0,o.openBlock)(!0),(0,o.createElementBlock)(o.Fragment,null,(0,o.renderList)((0,o.unref)(Y).teams.data,(function(e){return(0,o.openBlock)(),(0,o.createElementBlock)("option",{key:e.id,value:e.id,class:"bg-white text-black border-b dark:text-gray-50 dark:bg-gray-800 dark:border-gray-600"},(0,o.toDisplayString)(e.name),9,v)})),128))],512),[[o.vModelSelect,(0,o.unref)(ee).team_id]]),(0,o.unref)(ee).errors.team_id?((0,o.openBlock)(),(0,o.createElementBlock)("div",{key:0,textContent:(0,o.toDisplayString)((0,o.unref)(ee).errors.team_id),class:"text-xs text-red-600 mt-1"},null,8,y)):(0,o.createCommentVNode)("",!0)]),(0,o.createElementVNode)("div",k,[h,(0,o.withDirectives)((0,o.createElementVNode)("input",{"onUpdate:modelValue":t[1]||(t[1]=function(e){return(0,o.unref)(ee).name=e}),class:"bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500",type:"text",name:"name",id:"name",required:""},null,512),[[o.vModelText,(0,o.unref)(ee).name]]),(0,o.unref)(ee).errors.name?((0,o.openBlock)(),(0,o.createElementBlock)("div",{key:0,textContent:(0,o.toDisplayString)((0,o.unref)(ee).errors.name),class:"text-xs text-red-600 mt-1"},null,8,w)):(0,o.createCommentVNode)("",!0)]),(0,o.createElementVNode)("div",V,[E,(0,o.withDirectives)((0,o.createElementVNode)("select",{class:"border border-gray-400 text-gray-800 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs","onUpdate:modelValue":t[2]||(t[2]=function(e){return(0,o.unref)(ee).category=e}),onChange:t[3]||(t[3]=function(e){return t=e,void(te=Y.categories[t.target.selectedIndex].description);var t})},[((0,o.openBlock)(!0),(0,o.createElementBlock)(o.Fragment,null,(0,o.renderList)((0,o.unref)(Y).categories,(function(e){return(0,o.openBlock)(),(0,o.createElementBlock)("option",{key:e.id,value:e.id},(0,o.toDisplayString)(e.name),9,N)})),128))],544),[[o.vModelSelect,(0,o.unref)(ee).category]]),(0,o.unref)(ee).errors.category?((0,o.openBlock)(),(0,o.createElementBlock)("div",{key:0,textContent:(0,o.toDisplayString)((0,o.unref)(ee).errors.category),class:"text-xs text-red-600 mt-1"},null,8,_)):(0,o.createCommentVNode)("",!0),(0,o.createTextVNode)(" "+(0,o.toDisplayString)((0,o.unref)(te)),1)]),(0,o.createElementVNode)("div",C,[B,S,(0,o.withDirectives)((0,o.createElementVNode)("select",{disabled:"",class:"border border-gray-400 text-gray-800 disabled:bg-gray-300 dark:disabled:bg-gray-600 disabled:cursor-not-allowed p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs","onUpdate:modelValue":t[4]||(t[4]=function(e){return(0,o.unref)(ee).sub_category=e})},D,512),[[o.vModelSelect,(0,o.unref)(ee).sub_category]]),(0,o.unref)(ee).errors.sub_category?((0,o.openBlock)(),(0,o.createElementBlock)("div",{key:0,textContent:(0,o.toDisplayString)((0,o.unref)(ee).errors.sub_category),class:"text-xs text-red-600 mt-1"},null,8,M)):(0,o.createCommentVNode)("",!0)]),(0,o.createElementVNode)("div",T,[I,(0,o.withDirectives)((0,o.createElementVNode)("textarea",{"onUpdate:modelValue":t[5]||(t[5]=function(e){return(0,o.unref)(ee).description=e}),class:"bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5",type:"text",name:"description",id:"description",required:""},null,512),[[o.vModelText,(0,o.unref)(ee).description]]),(0,o.unref)(ee).errors.description?((0,o.openBlock)(),(0,o.createElementBlock)("div",{key:0,textContent:(0,o.toDisplayString)((0,o.unref)(ee).errors.description),class:"text-xs text-red-600 mt-1"},null,8,U)):(0,o.createCommentVNode)("",!0)]),(0,o.createElementVNode)("div",L,[F,(0,o.withDirectives)((0,o.createElementVNode)("input",{"onUpdate:modelValue":t[6]||(t[6]=function(e){return(0,o.unref)(ee).www_url=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"www_url",id:"www_url"},null,512),[[o.vModelText,(0,o.unref)(ee).www_url]]),(0,o.unref)(ee).errors.www_url?((0,o.openBlock)(),(0,o.createElementBlock)("div",{key:0,textContent:(0,o.toDisplayString)((0,o.unref)(ee).errors.www_url),class:"text-xs text-red-600 mt-1"},null,8,O)):(0,o.createCommentVNode)("",!0)]),(0,o.createElementVNode)("div",j,[q,(0,o.withDirectives)((0,o.createElementVNode)("input",{"onUpdate:modelValue":t[7]||(t[7]=function(e){return(0,o.unref)(ee).instagram_name=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"instagram_name handle",id:"instagram_name"},null,512),[[o.vModelText,(0,o.unref)(ee).instagram_name]]),(0,o.unref)(ee).errors.instagram_name?((0,o.openBlock)(),(0,o.createElementBlock)("div",{key:0,textContent:(0,o.toDisplayString)((0,o.unref)(ee).errors.instagram_name),class:"text-xs text-red-600 mt-1"},null,8,A)):(0,o.createCommentVNode)("",!0)]),(0,o.createElementVNode)("div",Z,[R,(0,o.withDirectives)((0,o.createElementVNode)("input",{"onUpdate:modelValue":t[8]||(t[8]=function(e){return(0,o.unref)(ee).telegram_url=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"telegram_url",id:"telegram_url"},null,512),[[o.vModelText,(0,o.unref)(ee).telegram_url]]),(0,o.unref)(ee).errors.telegram_url?((0,o.openBlock)(),(0,o.createElementBlock)("div",{key:0,textContent:(0,o.toDisplayString)((0,o.unref)(ee).errors.telegram_url),class:"text-xs text-red-600 mt-1"},null,8,W)):(0,o.createCommentVNode)("",!0)]),(0,o.createElementVNode)("div",H,[P,(0,o.withDirectives)((0,o.createElementVNode)("input",{"onUpdate:modelValue":t[9]||(t[9]=function(e){return(0,o.unref)(ee).twitter_handle=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"twitter_handle",id:"twitter_handle"},null,512),[[o.vModelText,(0,o.unref)(ee).twitter_handle]]),(0,o.unref)(ee).errors.twitter_handle?((0,o.openBlock)(),(0,o.createElementBlock)("div",{key:0,textContent:(0,o.toDisplayString)((0,o.unref)(ee).errors.twitter_handle),class:"text-xs text-red-600 mt-1"},null,8,G)):(0,o.createCommentVNode)("",!0)]),(0,o.createElementVNode)("div",Q,[$,(0,o.withDirectives)((0,o.createElementVNode)("textarea",{"onUpdate:modelValue":t[10]||(t[10]=function(e){return(0,o.unref)(ee).notes=e}),class:"bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5",type:"text",name:"notes",id:"notes"},null,512),[[o.vModelText,(0,o.unref)(ee).notes]]),(0,o.unref)(ee).errors.notes?((0,o.openBlock)(),(0,o.createElementBlock)("div",{key:0,textContent:(0,o.toDisplayString)((0,o.unref)(ee).errors.notes),class:"text-xs text-red-600 mt-1"},null,8,z)):(0,o.createCommentVNode)("",!0)]),(0,o.withDirectives)((0,o.createElementVNode)("input",{"onUpdate:modelValue":t[11]||(t[11]=function(e){return(0,o.unref)(ee).user_id=e}),hidden:""},null,512),[[o.vModelText,(0,o.unref)(ee).user_id]]),(0,o.createElementVNode)("div",J,[(0,o.createVNode)(c.Z,{class:"mr-4"}),(0,o.createElementVNode)("button",{type:"submit",class:"h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4",disabled:(0,o.unref)(ee).processing}," Submit ",8,K)])],32)])])],64)}}}}}]);
//# sourceMappingURL=8869.js.map