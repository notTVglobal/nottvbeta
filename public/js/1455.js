"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[1455],{86263:(e,t,a)=>{a.d(t,{Z:()=>n});var r=a(70821),l=["src","alt"],o=["src","alt"];const n={__name:"SingleImage",props:{image:Object,class:String,alt:String},setup:function(e){var t=e;return function(a,n){return(0,r.openBlock)(),(0,r.createElementBlock)("div",null,[e.image.folder?(0,r.createCommentVNode)("",!0):((0,r.openBlock)(),(0,r.createElementBlock)("img",{key:0,src:"/storage/images/"+e.image.name,alt:e.alt,class:(0,r.normalizeClass)((0,r.unref)(t).class)},null,10,l)),e.image.folder?((0,r.openBlock)(),(0,r.createElementBlock)("img",{key:1,src:e.image.cdn_endpoint+e.image.cloud_folder+e.image.folder+"/"+e.image.name,alt:e.alt,class:(0,r.normalizeClass)((0,r.unref)(t).class)},null,10,o)):(0,r.createCommentVNode)("",!0)])}}}},92505:(e,t,a)=>{a.d(t,{Z:()=>n});var r=a(70821),l={key:0,class:"flex flex-wrap justify-center my-4 space-x-2 md:space-x-4 space-y-2"},o=(0,r.createElementVNode)("div",null,null,-1);const n={__name:"Pagination",props:["data","id"],setup:function(e){var t=e.data,a=(e.id,(0,r.ref)(1)),n=(0,r.computed)((function(){var e=5*Math.max(a.value-1,0),r=t.links[e],l=t.links[e+1],o=function(e,t,a){var r=e.findIndex((function(e){return e.active}));if(r===t+1&&e[t+1].active)return e[t+2];if(r===e.length-2&&e[e.length-2].active)return e[e.length-3];return e[r]}(t.links,e);return[r,l,o,t.links[t.links.length-2],t.links[t.links.length-1]].filter(Boolean)}));return function(t,a){return e.data.last_page>1?((0,r.openBlock)(),(0,r.createElementBlock)("div",l,[o,((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)(n.value,(function(t,l){return(0,r.openBlock)(),(0,r.createBlock)((0,r.resolveDynamicComponent)(t.url?"Link":"span"),{id:e.id,key:l,href:t.url,innerHTML:t.label,class:(0,r.normalizeClass)(["px-2 md:px-4 py-3 text-sm leading-4 h-fit",{"text-white bg-orange-400 hover:bg-orange-400 dark:bg-orange-400 dark:hover:bg-orange-400":t.active,"rounded mt-2 mr-0.5 bg-gray-100 light:bg-gray-100 light:hover:bg-gray-100 light:text-gray-300 dark:bg-gray-900 dark:hover:bg-gray-900 dark:text-gray-700":!t.url,"rounded bg-gray-200 light:bg-gray-200 light:hover:bg-gray-300 dark:bg-gray-800 dark:hover:bg-gray-600 light:text-gray-800 dark:text-gray-50 focus:text-indigo-500 hover:shadow":t.url}]),onScroll:a[0]||(a[0]=(0,r.withModifiers)((function(){}),["prevent"]))},null,40,["id","href","innerHTML","class"])})),128))])):(0,r.createCommentVNode)("",!0)}}}},61455:(e,t,a)=>{a.r(t),a.d(t,{default:()=>R});var r=a(70821),l=a(9680),o=a(40191),n=a(90771),c=a(92505),s=a(23493),d=a.n(s),i=a(664),m=a(86263),g={class:"place-self-center flex flex-col gap-y-3"},u={id:"topDiv",class:"light:bg-white light:text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10"},p={key:1,class:"flex justify-end flex-wrap-reverse gap-x-2 pt-6"},h=(0,r.createElementVNode)("button",{class:"bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded disabled:bg-gray-400"},"Add Team",-1),f=(0,r.createElementVNode)("button",{class:"px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"},"Dashboard",-1),x=(0,r.createElementVNode)("h1",{class:"text-3xl font-semibold pb-3"},"Teams",-1),k={class:"flex flex-row justify-end gap-x-4"},y={class:"flex flex-col"},b={class:"-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"},w={class:"py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"},v={class:"shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"},V={class:"overflow-hidden bg-white shadow-sm sm:rounded-lg"},N={class:"p-6 bg-white border-b border-gray-200"},E={class:"relative overflow-x-auto shadow-md sm:rounded-lg"},B={class:"w-full text-sm text-left text-gray-500 dark:text-gray-400"},C={class:"text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"},_=(0,r.createElementVNode)("th",{scope:"col",class:"min-w-[8rem] px-6 py-3"}," Logo ",-1),S=(0,r.createElementVNode)("th",{scope:"col",class:"px-6 py-3"}," Team Name ",-1),T=(0,r.createElementVNode)("th",{scope:"col",class:"px-6 py-3"}," Team Owner ",-1),M=(0,r.createElementVNode)("th",{scope:"col",class:"px-6 py-3"}," # of Members ",-1),D=(0,r.createElementVNode)("th",{scope:"col",class:"px-6 py-3"}," # of Shows ",-1),j={key:0,scope:"col",class:"px-6 py-3"},L={scope:"row",class:"min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"},I={scope:"row",class:"px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"},O={scope:"row",class:"px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"},Z={scope:"row",class:"px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"},F={scope:"row",class:"px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"},z={class:"px-6 py-4 space-x-2"},H=(0,r.createElementVNode)("button",{class:"px-4 py-2 text-white bg-purple-600 hover:bg-purple-500 rounded-lg"},"Manage",-1),P=(0,r.createElementVNode)("button",{class:"px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"},"Edit",-1);const R={__name:"Index",props:{teams:Object,filters:Object,can:Object},setup:function(e){var t=e,a=(0,o.q)(),s=(0,n.L)();s.currentPage="teams",s.showFlashMessage=!0,(0,r.onMounted)((function(){a.makeVideoTopRight(),s.isMobile&&(a.ottClass="ottClose",a.ott=0),document.getElementById("topDiv").scrollIntoView()}));var R=(0,r.ref)(t.filters.search);return(0,r.watch)(R,d()((function(e){l.Inertia.get("/teams",{search:e},{preserveState:!0,replace:!0})}),300)),function(a,l){var o=(0,r.resolveComponent)("Head"),n=(0,r.resolveComponent)("Link");return(0,r.openBlock)(),(0,r.createElementBlock)(r.Fragment,null,[(0,r.createVNode)(o,{title:"Teams"}),(0,r.createElementVNode)("div",g,[(0,r.createElementVNode)("div",u,[(0,r.unref)(s).showFlashMessage?((0,r.openBlock)(),(0,r.createBlock)((0,r.unref)(i.Z),{key:0,flash:a.$page.props.flash},null,8,["flash"])):(0,r.createCommentVNode)("",!0),(0,r.unref)(t).can.viewCreator?((0,r.openBlock)(),(0,r.createElementBlock)("div",p,[e.can.createTeam?((0,r.openBlock)(),(0,r.createBlock)(n,{key:0,href:"/teams/create"},{default:(0,r.withCtx)((function(){return[h]})),_:1})):(0,r.createCommentVNode)("",!0),(0,r.createVNode)(n,{href:"/dashboard"},{default:(0,r.withCtx)((function(){return[f]})),_:1})])):(0,r.createCommentVNode)("",!0),x,(0,r.createElementVNode)("div",k,[(0,r.withDirectives)((0,r.createElementVNode)("input",{"onUpdate:modelValue":l[0]||(l[0]=function(e){return(0,r.isRef)(R)?R.value=e:R=e}),type:"search",placeholder:"Search...",class:"border px-2 rounded-lg"},null,512),[[r.vModelText,(0,r.unref)(R)]])]),(0,r.createElementVNode)("div",y,[(0,r.createElementVNode)("div",b,[(0,r.createElementVNode)("div",w,[(0,r.createElementVNode)("div",v,[(0,r.createElementVNode)("div",V,[(0,r.createElementVNode)("div",N,[(0,r.createVNode)((0,r.unref)(c.Z),{data:e.teams,class:""},null,8,["data"]),(0,r.createElementVNode)("div",E,[(0,r.createElementVNode)("table",B,[(0,r.createElementVNode)("thead",C,[(0,r.createElementVNode)("tr",null,[_,S,T,M,D,(0,r.unref)(t).can.viewCreator?((0,r.openBlock)(),(0,r.createElementBlock)("th",j)):(0,r.createCommentVNode)("",!0)])]),(0,r.createElementVNode)("tbody",null,[((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)(e.teams.data,(function(e){return(0,r.openBlock)(),(0,r.createElementBlock)("tr",{key:e.id,class:"bg-white border-b dark:bg-gray-800 dark:border-gray-700"},[(0,r.createElementVNode)("th",L,[((0,r.openBlock)(),(0,r.createBlock)(m.Z,{image:e.image,alt:"team logo rounded full",key:(0,r.unref)(t).image,class:(0,r.normalizeClass)("rounded-full h-20 w-20 object-cover")},null,8,["image"]))]),(0,r.createElementVNode)("th",I,[(0,r.createVNode)(n,{href:"/teams/".concat(e.slug),class:"text-blue-800 hover:text-blue-600"},{default:(0,r.withCtx)((function(){return[(0,r.createTextVNode)((0,r.toDisplayString)(e.name),1)]})),_:2},1032,["href"])]),(0,r.createElementVNode)("th",O,(0,r.toDisplayString)(e.teamOwner),1),(0,r.createElementVNode)("td",Z,(0,r.toDisplayString)(e.memberSpots),1),(0,r.createElementVNode)("td",F,(0,r.toDisplayString)(e.totalShows),1),(0,r.createElementVNode)("td",z,[e.can.viewTeam?((0,r.openBlock)(),(0,r.createBlock)(n,{key:0,href:"/teams/".concat(e.slug,"/manage")},{default:(0,r.withCtx)((function(){return[H]})),_:2},1032,["href"])):(0,r.createCommentVNode)("",!0),e.can.editTeam?((0,r.openBlock)(),(0,r.createBlock)(n,{key:1,href:"/teams/".concat(e.slug,"/edit")},{default:(0,r.withCtx)((function(){return[P]})),_:2},1032,["href"])):(0,r.createCommentVNode)("",!0)])])})),128))])]),(0,r.createVNode)((0,r.unref)(c.Z),{data:e.teams,class:""},null,8,["data"])])])])])])])])])])],64)}}}}}]);
//# sourceMappingURL=1455.js.map