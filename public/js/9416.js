"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[9416],{97849:(e,t,a)=>{a.d(t,{Z:()=>i});var r=a(70821),o={class:"flex justify-between my-3"},l={class:"mb-4"},n={class:"text-3xl font-semibold"},c={class:"flex flex-wrap-reverse justify-end gap-2"},s=(0,r.createElementVNode)("button",{class:"px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"},"Admin Settings ",-1);const d={},i=(0,a(83744).Z)(d,[["render",function(e,t){var a=(0,r.resolveComponent)("Link");return(0,r.openBlock)(),(0,r.createElementBlock)("header",null,[(0,r.createElementVNode)("div",o,[(0,r.createElementVNode)("div",l,[(0,r.createElementVNode)("h1",n,[(0,r.renderSlot)(e.$slots,"default")])]),(0,r.createElementVNode)("div",null,[(0,r.createElementVNode)("div",c,[(0,r.createVNode)(a,{href:"/admin/settings"},{default:(0,r.withCtx)((function(){return[s]})),_:1})])])])])}]])},92505:(e,t,a)=>{a.d(t,{Z:()=>n});var r=a(70821),o={key:0,class:"flex flex-wrap justify-center my-4 space-x-4 space-y-2"},l=(0,r.createElementVNode)("div",null,null,-1);const n={__name:"Pagination",props:{data:Object},setup:function(e){return function(t,a){return e.data.last_page>1?((0,r.openBlock)(),(0,r.createElementBlock)("div",o,[l,((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)(e.data.links,(function(e,t){return(0,r.openBlock)(),(0,r.createBlock)((0,r.resolveDynamicComponent)(e.url?"Link":"span"),{key:t,href:e.url,innerHTML:e.label,class:(0,r.normalizeClass)(["px-4 py-3 text-sm leading-4 h-fit",{"text-white bg-orange-400 hover:bg-orange-400 dark:bg-orange-400 dark:hover:bg-orange-400":e.active,"rounded mt-2 mr-0.5 bg-gray-100 hover:bg-gray-100 text-gray-300 dark:bg-gray-900 dark:hover:bg-gray-900 dark:text-gray-700":!e.url,"rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-800 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-50 focus:text-indigo-500 hover:shadow":e.url}]),onScroll:a[0]||(a[0]=(0,r.withModifiers)((function(){}),["prevent"]))},null,40,["href","innerHTML","class"])})),128))])):(0,r.createCommentVNode)("",!0)}}}},39416:(e,t,a)=>{a.r(t),a.d(t,{default:()=>H});var r=a(70821),o=a(9680),l=a(40191),n=a(90771),c=a(92505),s=a(23493),d=a.n(s),i=a(664),m=a(86263),u=a(97849),p={class:"place-self-center flex flex-col gap-y-3"},g={id:"topDiv",class:"bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10"},f={class:"flex flex-row justify-end gap-x-4"},h={class:"flex flex-col"},x={class:"-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"},b={class:"py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"},y={class:"shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"},k={class:"overflow-hidden bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg"},w={class:"p-6 bg-white dark:bg-gray-800 border-b border-gray-200"},v={class:"relative overflow-x-auto shadow-md sm:rounded-lg"},V={class:"w-full text-sm text-left text-gray-500 dark:text-gray-400 overflow-x-auto"},N={class:"text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"},E=(0,r.createElementVNode)("th",{scope:"col",class:"min-w-[8rem] px-6 py-3"}," Logo ",-1),C=(0,r.createElementVNode)("th",{scope:"col",class:"px-6 py-3"}," Team Name ",-1),B=(0,r.createElementVNode)("th",{scope:"col",class:"px-6 py-3"}," Team Owner ",-1),T=(0,r.createElementVNode)("th",{scope:"col",class:"px-6 py-3"}," # of Members ",-1),S=(0,r.createElementVNode)("th",{scope:"col",class:"px-6 py-3"}," # of Shows ",-1),M={key:0,scope:"col",class:"px-6 py-3"},j={scope:"row",class:"min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"},L={scope:"row",class:"px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"},_={scope:"row",class:"px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"},D={scope:"row",class:"px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"},Z={scope:"row",class:"px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"},O={class:"px-6 py-4 space-x-2"},F=["onClick"],R=["onClick"];const H={__name:"Teams",props:{teams:Object,filters:Object,can:Object},setup:function(e){var t=(0,l.q)(),a=(0,n.L)(),s=e,H=(0,r.ref)(s.filters.search);return a.currentPage="adminTeams",a.showFlashMessage=!0,(0,r.onMounted)((function(){t.makeVideoTopRight(),a.isMobile&&(t.ottClass="ottClose",t.ott=0),document.getElementById("topDiv").scrollIntoView()})),(0,r.watch)(H,d()((function(e){o.Inertia.get("/admin/teams",{search:e},{preserveState:!0,replace:!0})}),300)),function(t,o){var l=(0,r.resolveComponent)("Head"),n=(0,r.resolveComponent)("Link");return(0,r.openBlock)(),(0,r.createElementBlock)(r.Fragment,null,[(0,r.createVNode)(l,{title:"Teams"}),(0,r.createElementVNode)("div",p,[(0,r.createElementVNode)("div",g,[(0,r.unref)(a).showFlashMessage?((0,r.openBlock)(),(0,r.createBlock)((0,r.unref)(i.Z),{key:0,flash:t.$page.props.flash},null,8,["flash"])):(0,r.createCommentVNode)("",!0),(0,r.createVNode)(u.Z,null,{default:(0,r.withCtx)((function(){return[(0,r.createTextVNode)("Teams")]})),_:1}),(0,r.createElementVNode)("button",{onClick:o[0]||(o[0]=function(e){return(0,r.unref)(a).btnRedirect("/teams/create")}),class:"bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded disabled:bg-gray-400"},"Create Team (needs to go to a new page: Admin/teams/create)"),(0,r.createElementVNode)("div",f,[(0,r.withDirectives)((0,r.createElementVNode)("input",{"onUpdate:modelValue":o[1]||(o[1]=function(e){return(0,r.isRef)(H)?H.value=e:H=e}),type:"search",placeholder:"Search...",class:"text-black border px-2 rounded-lg"},null,512),[[r.vModelText,(0,r.unref)(H)]])]),(0,r.createElementVNode)("div",h,[(0,r.createElementVNode)("div",x,[(0,r.createElementVNode)("div",b,[(0,r.createElementVNode)("div",y,[(0,r.createElementVNode)("div",k,[(0,r.createElementVNode)("div",w,[(0,r.createVNode)((0,r.unref)(c.Z),{data:e.teams,class:"mb-6"},null,8,["data"]),(0,r.createElementVNode)("div",v,[(0,r.createElementVNode)("table",V,[(0,r.createElementVNode)("thead",N,[(0,r.createElementVNode)("tr",null,[E,C,B,T,S,(0,r.unref)(s).can.viewCreator?((0,r.openBlock)(),(0,r.createElementBlock)("th",M)):(0,r.createCommentVNode)("",!0)])]),(0,r.createElementVNode)("tbody",null,[((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)(e.teams.data,(function(e){return(0,r.openBlock)(),(0,r.createElementBlock)("tr",{key:e.id,class:"bg-white border-b dark:bg-gray-800 dark:border-gray-700"},[(0,r.createElementVNode)("th",j,[(0,r.createVNode)(n,{href:"/teams/".concat(e.slug),class:""},{default:(0,r.withCtx)((function(){return[(0,r.createVNode)(m.Z,{image:e.image,poster:e.logo,alt:"show cover",class:"rounded-full h-20 w-20 object-cover"},null,8,["image","poster"])]})),_:2},1032,["href"])]),(0,r.createElementVNode)("th",L,[(0,r.createVNode)(n,{href:"/teams/".concat(e.slug),class:"text-blue-800 hover:text-blue-600 dark:text-blue-200 dark:hover:text-blue-400"},{default:(0,r.withCtx)((function(){return[(0,r.createTextVNode)((0,r.toDisplayString)(e.name),1)]})),_:2},1032,["href"])]),(0,r.createElementVNode)("th",_,(0,r.toDisplayString)(e.teamOwner),1),(0,r.createElementVNode)("td",D,(0,r.toDisplayString)(e.memberSpots),1),(0,r.createElementVNode)("td",Z,(0,r.toDisplayString)(e.totalShows),1),(0,r.createElementVNode)("td",O,[e.can.viewTeam?((0,r.openBlock)(),(0,r.createElementBlock)("button",{key:0,onClick:function(t){return(0,r.unref)(a).btnRedirect("/teams/".concat(e.slug,"/manage"))},class:"px-4 py-2 text-white bg-purple-600 hover:bg-purple-500 rounded-lg"},"Manage",8,F)):(0,r.createCommentVNode)("",!0),e.can.editTeam?((0,r.openBlock)(),(0,r.createElementBlock)("button",{key:1,onClick:function(t){return(0,r.unref)(a).btnRedirect("/teams/".concat(e.slug,"/edit"))},class:"px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"},"Edit",8,R)):(0,r.createCommentVNode)("",!0)])])})),128))])]),(0,r.createVNode)((0,r.unref)(c.Z),{data:e.teams,class:"pb-6"},null,8,["data"])])])])])])])])])])],64)}}}}}]);
//# sourceMappingURL=9416.js.map