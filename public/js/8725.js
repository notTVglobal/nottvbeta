"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[8725],{58725:(e,t,o)=>{o.r(t),o.d(t,{default:()=>le});var l=o(70821),a=o(9680),r=o(40191),n=o(90771),c=o(20459),s=o(23493),i=o.n(s),d=o(664),m=o(86263),u={id:"topDiv",class:"place-self-center flex flex-col gap-y-3"},g={class:"bg-gray-900 text-white px-5"},f={class:"flex justify-between mb-3 border-b border-gray-800"},v={class:"container mx-auto flex flex-col lg:flex-row items-center justify-between px-4 py-6"},x={class:"flex flex-col lg:flex-row items-center"},p=(0,l.createElementVNode)("h1",{class:"text-3xl font-semibold"},"Movies",-1),h={class:"flex ml-0 lg:ml-16 mt-6 lg:mt-0 space-x-8"},y=(0,l.createElementVNode)("li",null,[(0,l.createElementVNode)("button",{href:"",class:"text-gray-700 cursor-not-allowed"},"Categories")],-1),V=["onClick"],N=["onClick"],w={class:"flex items-center mt-6 lg:mt-0"},b={class:"relative"},E=(0,l.createElementVNode)("div",{class:"absolute top-0 flex items-center h-full ml-2"},[(0,l.createElementVNode)("svg",{class:"fill-current text-gray-400 w-4",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 512 512"},[(0,l.createElementVNode)("path",{d:"M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"})])],-1),k={class:"py-8"},C={class:"container mx-auto px-4 border-b border-gray-800 pb-16"},B=(0,l.createElementVNode)("h2",{class:"text-yellow-500 uppercase tracking-wide font-semibold text-2xl"},"Popular Movies",-1),S={class:"popular-movies text-sm grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-12 pb-12 justify-items-center"},D={class:"relative inline-block"},j=(0,l.createElementVNode)("div",{class:"absolute bottom-0 right-0 w-12 h-12 bg-gray-800 rounded-full",style:{right:"-20px",bottom:"-20px"}},[(0,l.createElementVNode)("div",{class:"font-semi-bold text-xs flex justify-center items-center h-full"},"80%")],-1),M={class:"text-gray-400 mt-1"},_={key:0},T={class:"text-gray-400 mt-1 hidden"},I={class:"flex flex-col lg:flex-row my-10"},Z={class:"recently-reviewed w-full lg:w-3/4 mr-0 md:mr-16 lg:mr-32"},F=(0,l.createElementVNode)("h2",{id:"review",class:"text-yellow-500 uppercase tracking-wide font-semibold text-2xl"},"Recently Reviewed",-1),R={class:"recently-reviewed-container space-y-12 mt-8"},L={class:"relative flex-none"},O=(0,l.createElementVNode)("div",{class:"absolute bottom-0 right-0 w-12 h-12 bg-gray-900 rounded-full",style:{right:"-20px",bottom:"-20px"}},[(0,l.createElementVNode)("div",{class:"font-semi-bold text-xs flex justify-center items-center h-full"},"80%")],-1),A={class:"ml-12"},P={class:"text-gray-400 mt-1"},q={class:"hidden"},z={class:"mt-6 pr-4 text-gray-300 hidden lg:block"},H={class:"flex flex-col xl:flex-row my-10 pl-4"},U={class:"most-anticipated w-full xl:w-3/4 mr-0 md:mr-16 xl:mr-32"},$=(0,l.createElementVNode)("h2",{class:"text-yellow-500 uppercase tracking-wide font-semibold text-2xl"},"Most Anticipated",-1),G={class:"most-anticipated-container space-y-10 mt-8"},J={class:"ml-4"},K={class:"text-gray-400 text-sm mt-1"},Q={class:"hidden"},W=(0,l.createElementVNode)("h2",{id:"coming-soon",class:"text-yellow-500 uppercase tracking-wide font-semibold mt-16 text-2xl"},"Coming Soon",-1),X={class:"most-anticipated-container space-y-10 mt-8"},Y={class:"ml-4"},ee={class:"text-gray-400 text-sm mt-1"},te={class:"hidden"},oe=(0,l.createElementVNode)("footer",{class:"border-t border-gray-800"},[(0,l.createElementVNode)("div",{class:"container text-right text-gray-800 mx-auto px-4 py-6"}," Powered by not.tv ")],-1);const le={__name:"Index",props:{movies:Object,recentlyReviewed:Object,mostAnticipated:Object,comingSoon:Object,filters:Object,can:Object},setup:function(e){var t=(0,r.q)(),o=(0,n.L)(),s=e,le=(0,l.ref)(s.filters.search);function ae(){document.getElementById("review").scrollIntoView({behavior:"smooth"})}function re(){document.getElementById("coming-soon").scrollIntoView({behavior:"smooth"})}return o.currentPage="movies",o.showFlashMessage=!0,(0,l.onMounted)((function(){t.makeVideoTopRight(),o.isMobile&&(t.ottClass="ottClose",t.ott=0),document.getElementById("topDiv").scrollIntoView()})),(0,l.watch)(le,i()((function(e){a.Inertia.get("/movies",{search:e},{preserveState:!0,replace:!0})}),300)),function(t,a){var r=(0,l.resolveComponent)("Head"),n=(0,l.resolveComponent)("Link");return(0,l.openBlock)(),(0,l.createElementBlock)(l.Fragment,null,[(0,l.createVNode)(r,{title:"Movies"}),(0,l.createElementVNode)("div",u,[(0,l.createElementVNode)("div",g,[(0,l.unref)(o).showFlashMessage?((0,l.openBlock)(),(0,l.createBlock)((0,l.unref)(d.Z),{key:0,flash:t.$page.props.flash},null,8,["flash"])):(0,l.createCommentVNode)("",!0),(0,l.createElementVNode)("header",f,[(0,l.createElementVNode)("div",v,[(0,l.createElementVNode)("div",x,[p,(0,l.createElementVNode)("ul",h,[y,(0,l.createElementVNode)("li",null,[(0,l.createElementVNode)("button",{href:"",onClick:(0,l.withModifiers)(ae,["prevent"]),class:"hover:text-blue-800"},"Reviews",8,V)]),(0,l.createElementVNode)("li",null,[(0,l.createElementVNode)("button",{href:"",onClick:(0,l.withModifiers)(re,["prevent"]),class:"hover:text-blue-800"},"Coming Soon",8,N)])])]),(0,l.createElementVNode)("div",w,[(0,l.createElementVNode)("div",b,[(0,l.withDirectives)((0,l.createElementVNode)("input",{"onUpdate:modelValue":a[0]||(a[0]=function(e){return(0,l.isRef)(le)?le.value=e:le=e}),type:"search",class:"bg-gray-800 text-sm rounded-full focus:outline-none focus:shadow w-64 pl-8 px-3 py-1",placeholder:"Search..."},null,512),[[l.vModelText,(0,l.unref)(le)]]),E])])])]),(0,l.createElementVNode)("main",k,[(0,l.createElementVNode)("div",C,[B,(0,l.createElementVNode)("div",S,[((0,l.openBlock)(!0),(0,l.createElementBlock)(l.Fragment,null,(0,l.renderList)(e.movies.data,(function(e){return(0,l.openBlock)(),(0,l.createElementBlock)("div",{key:e.id,class:"movie mt-8"},[(0,l.createElementVNode)("div",D,[(0,l.createVNode)(n,{href:"/movies/".concat(e.slug)},{default:(0,l.withCtx)((function(){return[(0,l.createVNode)((0,l.unref)(m.Z),{image:e.image,alt:"movie cover",class:(0,l.normalizeClass)("h-48 min-w-[8rem] w-28 object-cover hover:opacity-75 transition ease-in-out duration-150")},null,8,["image"])]})),_:2},1032,["href"]),j]),(0,l.createVNode)(n,{href:"/movies/".concat(e.slug),class:"block text-base font-semibold leading-tight max-w-[8rem] hover:text-gray-400 mt-4 mb-2"},{default:(0,l.withCtx)((function(){return[(0,l.createTextVNode)((0,l.toDisplayString)(e.name),1)]})),_:2},1032,["href"]),(0,l.createElementVNode)("div",M,[(0,l.createTextVNode)((0,l.toDisplayString)(e.category)+" ",1),e.release_year?((0,l.openBlock)(),(0,l.createElementBlock)("span",_,"("+(0,l.toDisplayString)(e.release_year)+")",1)):(0,l.createCommentVNode)("",!0)]),(0,l.createElementVNode)("div",T,(0,l.toDisplayString)(e.subCategory),1)])})),128))]),(0,l.createVNode)((0,l.unref)(c.Z),{data:e.movies,class:""},null,8,["data"])]),(0,l.createElementVNode)("div",I,[(0,l.createElementVNode)("div",Z,[F,(0,l.createElementVNode)("div",R,[((0,l.openBlock)(!0),(0,l.createElementBlock)(l.Fragment,null,(0,l.renderList)(e.recentlyReviewed.data,(function(e){return(0,l.openBlock)(),(0,l.createElementBlock)("div",{key:e.id,class:"movie bg-gray-800 rounded-lg shadow-md flex px-6 py-6"},[(0,l.createElementVNode)("div",L,[(0,l.createVNode)(n,{href:"/movies/".concat(e.slug),class:"hover:text-blue-400 hover:opacity-75 transition ease-in-out duration-150"},{default:(0,l.withCtx)((function(){return[(0,l.createVNode)((0,l.unref)(m.Z),{image:e.image,alt:"movie cover",class:"h-32 md:h-64 md:min-w-[8rem] w-24 md:w-48 object-cover hover:opacity-75 transition ease-in-out duration-150"},null,8,["image"])]})),_:2},1032,["href"]),O]),(0,l.createElementVNode)("div",A,[(0,l.createVNode)(n,{href:"/movies/".concat(e.slug),class:"block text-lg font-semibold leading-tight max-w-[8rem] hover:text-gray-400 mt-4"},{default:(0,l.withCtx)((function(){return[(0,l.createTextVNode)((0,l.toDisplayString)(e.name),1)]})),_:2},1032,["href"]),(0,l.createElementVNode)("div",P,[(0,l.createTextVNode)((0,l.toDisplayString)(e.category),1),(0,l.createElementVNode)("span",q,", "+(0,l.toDisplayString)(e.subCategory),1)]),(0,l.createElementVNode)("p",z,(0,l.toDisplayString)(e.logline),1)])])})),128))])]),(0,l.createElementVNode)("div",H,[(0,l.createElementVNode)("div",U,[$,(0,l.createElementVNode)("div",G,[((0,l.openBlock)(!0),(0,l.createElementBlock)(l.Fragment,null,(0,l.renderList)(e.mostAnticipated.data,(function(e){return(0,l.openBlock)(),(0,l.createElementBlock)("div",{key:e.id,class:"movie flex"},[(0,l.createVNode)(n,{href:"/movies/".concat(e.slug)},{default:(0,l.withCtx)((function(){return[(0,l.createVNode)((0,l.unref)(m.Z),{image:e.image,alt:"movie cover",class:"h-24 min-w-[4rem] w-16 object-cover hover:opacity-75 transition ease-in-out duration-150"},null,8,["image"])]})),_:2},1032,["href"]),(0,l.createElementVNode)("div",J,[(0,l.createVNode)(n,{href:"/movies/".concat(e.slug),class:"hover:text-gray-300"},{default:(0,l.withCtx)((function(){return[(0,l.createTextVNode)((0,l.toDisplayString)(e.name),1)]})),_:2},1032,["href"]),(0,l.createElementVNode)("div",K,[(0,l.createTextVNode)((0,l.toDisplayString)(e.category),1),(0,l.createElementVNode)("span",Q,", "+(0,l.toDisplayString)(e.subCategory),1)])])])})),128))]),W,(0,l.createElementVNode)("div",X,[((0,l.openBlock)(!0),(0,l.createElementBlock)(l.Fragment,null,(0,l.renderList)(e.comingSoon.data,(function(e){return(0,l.openBlock)(),(0,l.createElementBlock)("div",{key:e.id,class:"movie flex"},[(0,l.createVNode)(n,{href:"/movies/".concat(e.slug)},{default:(0,l.withCtx)((function(){return[(0,l.createVNode)((0,l.unref)(m.Z),{image:e.image,alt:"movie cover",class:"h-24 min-w-[4rem] w-16 object-cover hover:opacity-75 transition ease-in-out duration-150"},null,8,["image"])]})),_:2},1032,["href"]),(0,l.createElementVNode)("div",Y,[(0,l.createVNode)(n,{href:"/movies/".concat(e.slug),class:"hover:text-gray-300"},{default:(0,l.withCtx)((function(){return[(0,l.createTextVNode)((0,l.toDisplayString)(e.name),1)]})),_:2},1032,["href"]),(0,l.createElementVNode)("div",ee,[(0,l.createTextVNode)((0,l.toDisplayString)(e.category),1),(0,l.createElementVNode)("span",te,", "+(0,l.toDisplayString)(e.subCategory),1)])])])})),128))])])])])]),oe])])],64)}}}}}]);
//# sourceMappingURL=8725.js.map