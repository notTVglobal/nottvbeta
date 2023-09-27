"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[8869],{30131:(e,t,r)=>{r.d(t,{Z:()=>c});var n=r(70821),o=r(39038),l={key:0},a=(0,n.createElementVNode)("div",{class:"font-medium text-red-600"}," Whoops! Something went wrong. ",-1),s={class:"mt-3 list-disc list-inside text-sm text-red-600"};const c={__name:"ValidationErrors",setup:function(e){var t=(0,n.computed)((function(){return(0,o.qt)().props.value.errors})),r=(0,n.computed)((function(){return Object.keys(t.value).length>0}));return function(e,o){return r.value?((0,n.openBlock)(),(0,n.createElementBlock)("div",l,[a,(0,n.createElementVNode)("ul",s,[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)(t.value,(function(e,t){return(0,n.openBlock)(),(0,n.createElementBlock)("li",{key:t},(0,n.toDisplayString)(e),1)})),128))])])):(0,n.createCommentVNode)("",!0)}}}},18869:(e,t,r)=>{r.r(t),r.d(t,{default:()=>Q});var n=r(70821),o=r(39038),l=r(40191),a=r(2448),s=r(90771),c=r(664),d=r(30131),u=r(9680),i={class:"place-self-center flex flex-col gap-y-3"},m={id:"topDiv",class:"bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10"},b=(0,n.createElementVNode)("div",{class:"text-3xl"},"Create Show",-1),p=(0,n.createElementVNode)("div",{class:"bg-orange-700 text-white w-full p-6"},[(0,n.createElementVNode)("span",{class:"font-bold"},"NOTE: "),(0,n.createTextVNode)(" We are working on an episode poster and video uploader for this page. For the time being, please go to the show "),(0,n.createElementVNode)("span",{class:"font-bold"},"EDIT"),(0,n.createTextVNode)(" page after you create the show to add a video and a poster. ")],-1),x={class:"mb-6"},g=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs dark:text-gray-200",for:"name"}," Team ",-1),f=["value"],y=["textContent"],k={class:"mb-6"},v=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs dark:text-gray-200",for:"name"}," Show Name ",-1),w=["textContent"],V={class:"mb-6"},E=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs dark:text-gray-200",for:"category"}," Category ",-1),N=["value"],h=["textContent"],_={class:"mb-6"},C=(0,n.createElementVNode)("label",{class:"block mb-1 uppercase font-bold text-xs dark:text-gray-200",for:"sub_category"}," Sub-category ",-1),B=(0,n.createElementVNode)("div",{class:"mb-2 text-sm text-orange-600"},"Sub-categories are coming soon!",-1),D=[(0,n.createElementVNode)("option",{value:"1"},"Option",-1)],S=["textContent"],T={class:"mb-6"},M=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs dark:text-gray-200",for:"description"}," Description ",-1),U=["textContent"],I={class:"mb-6"},F=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs dark:text-gray-200",for:"name"}," Website URL ",-1),O=["textContent"],j={class:"mb-6"},q=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs dark:text-gray-200",for:"name"}," Instagram Handle ",-1),L=["textContent"],R={class:"mb-6"},W=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs dark:text-gray-200",for:"name"}," Telegram URL ",-1),Z=["textContent"],H={class:"mb-6"},P=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs dark:text-gray-200",for:"name"}," Twitter @ ",-1),A=["textContent"],$={class:"mb-6"},z=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs dark:text-gray-200",for:"notes"}," Notes (Only your team members see these notes, they are not public) ",-1),G=["textContent"],J={class:"flex justify-between mb-6"},K=["disabled"];const Q={__name:"Create",props:{teams:Object,userId:Number,categories:Object,subCategories:Object},setup:function(e){var t=e,r=(0,l.q)(),Q=(0,a.A)(),X=(0,s.L)();X.currentPage="shows",X.showFlashMessage=!0,(0,n.onMounted)((function(){r.makeVideoTopRight(),X.isMobile&&(r.ottClass="ottClose",r.ott=0),document.getElementById("topDiv").scrollIntoView()}));var Y=(0,o.cI)({name:"",description:"",user_id:t.userId,team_id:"",category:"",sub_category:"",www_url:"",instagram_name:"",telegram_url:"",twitter_handle:"",notes:""});Y.team_id=Q.id;var ee=(0,n.ref)(null);var te=function(){Y.post("/shows")};function re(){var e=(0,o.qt)().props.value.urlPrev;"empty"!==e&&u.Inertia.visit(e)}return function(e,r){var o=(0,n.resolveComponent)("Head");return(0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,null,[(0,n.createVNode)(o,{title:"Create Show"}),(0,n.createElementVNode)("div",i,[(0,n.createElementVNode)("div",m,[(0,n.unref)(X).showFlashMessage?((0,n.openBlock)(),(0,n.createBlock)((0,n.unref)(c.Z),{key:0,flash:e.$page.props.flash},null,8,["flash"])):(0,n.createCommentVNode)("",!0),(0,n.createElementVNode)("div",{class:"flex justify-between mt-3 mb-6"},[b,(0,n.createElementVNode)("div",null,[(0,n.createElementVNode)("button",{onClick:re,class:"px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"},"Cancel ")])]),p,(0,n.createElementVNode)("form",{onSubmit:r[12]||(r[12]=(0,n.withModifiers)((function(){return(0,n.unref)(te)&&(0,n.unref)(te).apply(void 0,arguments)}),["prevent"])),class:"max-w-md mx-auto mt-8"},[(0,n.createElementVNode)("div",x,[g,(0,n.withDirectives)((0,n.createElementVNode)("select",{class:"border border-gray-400 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs text-gray-800","onUpdate:modelValue":r[0]||(r[0]=function(e){return(0,n.unref)(Y).team_id=e}),required:""},[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)((0,n.unref)(t).teams.data,(function(e){return(0,n.openBlock)(),(0,n.createElementBlock)("option",{key:e.id,value:e.id,class:"bg-white text-black border-b dark:text-gray-50 dark:bg-gray-800 dark:border-gray-600"},(0,n.toDisplayString)(e.name),9,f)})),128))],512),[[n.vModelSelect,(0,n.unref)(Y).team_id]]),(0,n.unref)(Y).errors.team_id?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(Y).errors.team_id),class:"text-xs text-red-600 mt-1"},null,8,y)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",k,[v,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":r[1]||(r[1]=function(e){return(0,n.unref)(Y).name=e}),class:"bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500",type:"text",name:"name",id:"name",required:""},null,512),[[n.vModelText,(0,n.unref)(Y).name]]),(0,n.unref)(Y).errors.name?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(Y).errors.name),class:"text-xs text-red-600 mt-1"},null,8,w)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",V,[E,(0,n.withDirectives)((0,n.createElementVNode)("select",{class:"border border-gray-400 text-gray-800 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs","onUpdate:modelValue":r[2]||(r[2]=function(e){return(0,n.unref)(Y).category=e}),onChange:r[3]||(r[3]=function(e){return r=e,void(ee=t.categories[r.target.selectedIndex].description);var r})},[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)((0,n.unref)(t).categories,(function(e){return(0,n.openBlock)(),(0,n.createElementBlock)("option",{key:e.id,value:e.id},(0,n.toDisplayString)(e.name),9,N)})),128))],544),[[n.vModelSelect,(0,n.unref)(Y).category]]),(0,n.unref)(Y).errors.category?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(Y).errors.category),class:"text-xs text-red-600 mt-1"},null,8,h)):(0,n.createCommentVNode)("",!0),(0,n.createTextVNode)(" "+(0,n.toDisplayString)((0,n.unref)(ee)),1)]),(0,n.createElementVNode)("div",_,[C,B,(0,n.withDirectives)((0,n.createElementVNode)("select",{disabled:"",class:"border border-gray-400 text-gray-800 disabled:bg-gray-300 dark:disabled:bg-gray-600 disabled:cursor-not-allowed p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs","onUpdate:modelValue":r[4]||(r[4]=function(e){return(0,n.unref)(Y).sub_category=e})},D,512),[[n.vModelSelect,(0,n.unref)(Y).sub_category]]),(0,n.unref)(Y).errors.sub_category?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(Y).errors.sub_category),class:"text-xs text-red-600 mt-1"},null,8,S)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",T,[M,(0,n.withDirectives)((0,n.createElementVNode)("textarea",{"onUpdate:modelValue":r[5]||(r[5]=function(e){return(0,n.unref)(Y).description=e}),class:"bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5",type:"text",name:"description",id:"description",required:""},null,512),[[n.vModelText,(0,n.unref)(Y).description]]),(0,n.unref)(Y).errors.description?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(Y).errors.description),class:"text-xs text-red-600 mt-1"},null,8,U)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",I,[F,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":r[6]||(r[6]=function(e){return(0,n.unref)(Y).www_url=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"www_url",id:"www_url"},null,512),[[n.vModelText,(0,n.unref)(Y).www_url]]),(0,n.unref)(Y).errors.www_url?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(Y).errors.www_url),class:"text-xs text-red-600 mt-1"},null,8,O)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",j,[q,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":r[7]||(r[7]=function(e){return(0,n.unref)(Y).instagram_name=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"instagram_name handle",id:"instagram_name"},null,512),[[n.vModelText,(0,n.unref)(Y).instagram_name]]),(0,n.unref)(Y).errors.instagram_name?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(Y).errors.instagram_name),class:"text-xs text-red-600 mt-1"},null,8,L)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",R,[W,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":r[8]||(r[8]=function(e){return(0,n.unref)(Y).telegram_url=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"telegram_url",id:"telegram_url"},null,512),[[n.vModelText,(0,n.unref)(Y).telegram_url]]),(0,n.unref)(Y).errors.telegram_url?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(Y).errors.telegram_url),class:"text-xs text-red-600 mt-1"},null,8,Z)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",H,[P,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":r[9]||(r[9]=function(e){return(0,n.unref)(Y).twitter_handle=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"twitter_handle",id:"twitter_handle"},null,512),[[n.vModelText,(0,n.unref)(Y).twitter_handle]]),(0,n.unref)(Y).errors.twitter_handle?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(Y).errors.twitter_handle),class:"text-xs text-red-600 mt-1"},null,8,A)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",$,[z,(0,n.withDirectives)((0,n.createElementVNode)("textarea",{"onUpdate:modelValue":r[10]||(r[10]=function(e){return(0,n.unref)(Y).notes=e}),class:"bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5",type:"text",name:"notes",id:"notes"},null,512),[[n.vModelText,(0,n.unref)(Y).notes]]),(0,n.unref)(Y).errors.notes?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(Y).errors.notes),class:"text-xs text-red-600 mt-1"},null,8,G)):(0,n.createCommentVNode)("",!0)]),(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":r[11]||(r[11]=function(e){return(0,n.unref)(Y).user_id=e}),hidden:""},null,512),[[n.vModelText,(0,n.unref)(Y).user_id]]),(0,n.createElementVNode)("div",J,[(0,n.createVNode)(d.Z,{class:"mr-4"}),(0,n.createElementVNode)("button",{type:"submit",class:"h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4",disabled:(0,n.unref)(Y).processing}," Submit ",8,K)])],32)])])],64)}}}}}]);
//# sourceMappingURL=8869.js.map