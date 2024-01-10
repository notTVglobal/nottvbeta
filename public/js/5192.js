"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[5192],{7849:(e,t,r)=>{r.d(t,{Z:()=>i});var a=r(821),o={class:"flex justify-between my-3"},l={class:"mb-4"},n={class:"text-3xl font-semibold"},s={class:"flex flex-wrap-reverse justify-end gap-2"},c=(0,a.createElementVNode)("button",{class:"px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"},"Admin Settings ",-1);const d={},i=(0,r(3744).Z)(d,[["render",function(e,t){var r=(0,a.resolveComponent)("Link");return(0,a.openBlock)(),(0,a.createElementBlock)("header",null,[(0,a.createElementVNode)("div",o,[(0,a.createElementVNode)("div",l,[(0,a.createElementVNode)("h1",n,[(0,a.renderSlot)(e.$slots,"default")])]),(0,a.createElementVNode)("div",null,[(0,a.createElementVNode)("div",s,[(0,a.createVNode)(r,{href:"/admin/settings"},{default:(0,a.withCtx)((function(){return[c]})),_:1})])])])])}]])},2505:(e,t,r)=>{r.d(t,{Z:()=>n});var a=r(821),o={key:0,class:"flex flex-wrap justify-center my-4 space-x-4 space-y-2"},l=(0,a.createElementVNode)("div",null,null,-1);const n={__name:"Pagination",props:{data:Object},setup:function(e){return function(t,r){return e.data.last_page>1?((0,a.openBlock)(),(0,a.createElementBlock)("div",o,[l,((0,a.openBlock)(!0),(0,a.createElementBlock)(a.Fragment,null,(0,a.renderList)(e.data.links,(function(e,t){return(0,a.openBlock)(),(0,a.createBlock)((0,a.resolveDynamicComponent)(e.url?"Link":"span"),{key:t,href:e.url,innerHTML:e.label,class:(0,a.normalizeClass)(["px-4 py-3 text-sm leading-4 h-fit",{"text-white bg-orange-400 hover:bg-orange-400 dark:bg-orange-400 dark:hover:bg-orange-400":e.active,"rounded mt-2 mr-0.5 bg-gray-100 hover:bg-gray-100 text-gray-300 dark:bg-gray-900 dark:hover:bg-gray-900 dark:text-gray-700":!e.url,"rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-800 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-50 focus:text-indigo-500 hover:shadow":e.url}]),onScroll:r[0]||(r[0]=(0,a.withModifiers)((function(){}),["prevent"]))},null,40,["href","innerHTML","class"])})),128))])):(0,a.createCommentVNode)("",!0)}}}},5192:(e,t,r)=>{r.r(t),r.d(t,{default:()=>F});var a=r(821),o=r(9680),l=r(9038),n=r(191),s=r(771),c=r(2505),d=r(3493),i=r.n(d),u=r(664),m=r(7849),p={class:"place-self-center flex flex-col gap-y-3"},g={id:"topDiv",class:"bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10"},h={class:"flex flex-row justify-between gap-x-4"},f={class:"flex flex-col"},x={class:"-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"},b={class:"py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"},y={class:"shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"},k={class:"overflow-hidden bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg"},v={class:"p-6 bg-white dark:bg-gray-800 border-b border-gray-200"},w={class:"relative overflow-x-auto shadow-md sm:rounded-lg"},V={class:"w-full text-sm text-left text-gray-500 dark:text-gray-400"},N=(0,a.createElementVNode)("thead",{class:"text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"},[(0,a.createElementVNode)("tr",null,[(0,a.createElementVNode)("th",{scope:"col",class:"min-w-[8rem] px-6 py-3"}," Avatar "),(0,a.createElementVNode)("th",{scope:"col",class:"px-6 py-3"}," Name "),(0,a.createElementVNode)("th",{scope:"col",class:"px-6 py-3"}," Email "),(0,a.createElementVNode)("th",{scope:"col",class:"px-6 py-3"}," Role "),(0,a.createElementVNode)("th",{scope:"col",class:"px-6 py-3"}," Edit ")])],-1),E={scope:"row",class:"min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"},B=["src"],C=["src"],_={scope:"row",class:"px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"},S={key:0,class:"ml-2 bg-green-700 text-white text-xs font-bold rounded-lg py-1 px-2"},j={key:1,class:"ml-2 bg-red-800 text-white text-xs font-bold rounded-lg py-1 px-2"},D={key:2,class:"ml-2 bg-orange-600 text-white text-xs font-bold rounded-lg py-1 px-2"},I={key:3,class:"ml-2 bg-purple-700 text-white text-xs font-bold rounded-lg py-1 px-2"},M={scope:"row",class:"px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"},T={class:"px-6 py-4"},L={class:"px-6 py-4 space-x-2"},Z=(0,a.createElementVNode)("button",{class:"px-4 py-2 mb-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"},"Edit",-1),A=["onClick"];const F={__name:"Index",props:{users:Object,filters:Object,can:Object},setup:function(e){var t=(0,n.q)(),r=(0,s.L)(),d=e,F=(0,a.ref)(d.filters.search);(0,l.cI)({userId:""});return r.currentPage="users",r.showFlashMessage=!0,(0,a.onMounted)((function(){t.makeVideoTopRight(),r.isMobile&&(t.ottClass="ottClose",t.ott=0),document.getElementById("topDiv").scrollIntoView()})),(0,a.watch)(F,i()((function(e){o.Inertia.get("/users",{search:e},{preserveState:!0,replace:!0})}),300)),function(t,l){var n=(0,a.resolveComponent)("Head"),s=(0,a.resolveComponent)("Link");return(0,a.openBlock)(),(0,a.createElementBlock)(a.Fragment,null,[(0,a.createVNode)(n,{title:"Users"}),(0,a.createElementVNode)("div",p,[(0,a.createElementVNode)("div",g,[(0,a.unref)(r).showFlashMessage?((0,a.openBlock)(),(0,a.createBlock)((0,a.unref)(u.Z),{key:0,flash:t.$page.props.flash},null,8,["flash"])):(0,a.createCommentVNode)("",!0),(0,a.createVNode)(m.Z,null,{default:(0,a.withCtx)((function(){return[(0,a.createTextVNode)("Users")]})),_:1}),(0,a.createElementVNode)("div",h,[(0,a.createElementVNode)("button",{onClick:l[0]||(l[0]=function(e){return(0,a.unref)(r).btnRedirect("/users/create")}),class:"bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg disabled:bg-gray-400"},"Add User"),(0,a.withDirectives)((0,a.createElementVNode)("input",{"onUpdate:modelValue":l[1]||(l[1]=function(e){return(0,a.isRef)(F)?F.value=e:F=e}),type:"search",placeholder:"Search...",class:"text-black border px-2 rounded-lg"},null,512),[[a.vModelText,(0,a.unref)(F)]])]),(0,a.createElementVNode)("div",f,[(0,a.createElementVNode)("div",x,[(0,a.createElementVNode)("div",b,[(0,a.createElementVNode)("div",y,[(0,a.createTextVNode)((0,a.toDisplayString)((0,a.unref)(d).users.teams)+" ",1),(0,a.createElementVNode)("div",k,[(0,a.createElementVNode)("div",v,[(0,a.createElementVNode)("div",w,[(0,a.createVNode)((0,a.unref)(c.Z),{data:e.users,class:"mb-6"},null,8,["data"]),(0,a.createElementVNode)("table",V,[N,(0,a.createElementVNode)("tbody",null,[((0,a.openBlock)(!0),(0,a.createElementBlock)(a.Fragment,null,(0,a.renderList)(e.users.data,(function(e){return(0,a.openBlock)(),(0,a.createElementBlock)("tr",{key:e.id,class:"bg-white border-b dark:bg-gray-800 dark:border-gray-700"},[(0,a.createElementVNode)("th",E,[(0,a.createVNode)(s,{href:"/users/".concat(e.id),class:""},{default:(0,a.withCtx)((function(){return[e.profile_photo_path?(0,a.createCommentVNode)("",!0):((0,a.openBlock)(),(0,a.createElementBlock)("img",{key:0,src:e.userSelected.profile_photo_url,class:"rounded-full h-20 w-20 object-cover"},null,8,B)),e.profile_photo_path?((0,a.openBlock)(),(0,a.createElementBlock)("img",{key:1,src:"/storage/".concat(e.profile_photo_path),class:"rounded-full h-20 w-20 object-cover"},null,8,C)):(0,a.createCommentVNode)("",!0)]})),_:2},1032,["href"])]),(0,a.createElementVNode)("th",_,[(0,a.createVNode)(s,{href:"/users/".concat(e.id),class:"text-blue-800 dark:text-blue-200 hover:text-blue-600 dark:hover:text-blue-400"},{default:(0,a.withCtx)((function(){return[(0,a.createTextVNode)((0,a.toDisplayString)(e.name),1)]})),_:2},1032,["href"]),e.isSubscriber?((0,a.openBlock)(),(0,a.createElementBlock)("span",S,"Premium")):(0,a.createCommentVNode)("",!0),e.isAdmin?((0,a.openBlock)(),(0,a.createElementBlock)("span",j,"Admin")):(0,a.createCommentVNode)("",!0),e.isNewsPerson?((0,a.openBlock)(),(0,a.createElementBlock)("span",D,"News Team")):(0,a.createCommentVNode)("",!0),e.isVip?((0,a.openBlock)(),(0,a.createElementBlock)("span",I,"VIP")):(0,a.createCommentVNode)("",!0)]),(0,a.createElementVNode)("th",M,(0,a.toDisplayString)(e.email),1),(0,a.createElementVNode)("td",T,[(0,a.createElementVNode)("span",null,(0,a.toDisplayString)(e.role),1)]),(0,a.createElementVNode)("td",L,[(0,a.createVNode)(s,{href:"/users/".concat(e.id,"/edit")},{default:(0,a.withCtx)((function(){return[Z]})),_:2},1032,["href"]),(0,a.createVNode)(s,{href:"#"},{default:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("button",{onClick:(0,a.withModifiers)((function(t){return r=e,void(confirm("Are you sure you want to delete "+r.name+"? This action is not reversible and may have devastating effects on the database.")&&o.Inertia.post("/admin/user/delete",{userId:r.id}));var r}),["prevent"]),class:"px-4 py-2 mb-2 text-white bg-red-600 hover:bg-red-500 rounded-lg"},"Delete",8,A)]})),_:2},1024)])])})),128))])]),(0,a.createVNode)((0,a.unref)(c.Z),{data:e.users,class:"mt-6"},null,8,["data"])])])])])])])])])])],64)}}}}}]);
//# sourceMappingURL=5192.js.map