"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[3717],{61930:(e,t,n)=>{n.d(t,{Z:()=>l});var r=n(94015),o=n.n(r),s=n(23645),i=n.n(s)()(o());i.push([e.id,".isFullPageCss{background:#000c}","",{version:3,sources:["webpack://./resources/js/Components/Navigation/PublicNavigationMenu.vue"],names:[],mappings:"AAgHA,eACI,gBAEJ",sourcesContent:['<template>\n    <div class="fixed top-0 w-full nav-mask">\n    <nav class="sticky top-0 bg-black border-b border-gray-100 z-50">\n        \x3c!-- Primary Navigation Menu --\x3e\n        <div class="max-w-7xl mx-auto px-4 lg:px-6 xl:px-8 z-50">\n            <div class="flex justify-between h-16">\n                <div class="flex">\n                    \x3c!-- Logo --\x3e\n                    <div class="shrink-0 flex items-center">\n                        <Link :href="route(\'home\')">\n                            <JetApplicationMark class="block h-9 w-auto"/>\n                        </Link>\n                    </div>\n\n                    \x3c!-- Navigation Links --\x3e\n                    <div class="space-x-4 py-6 pt-6 ml-8 lg:flex text-gray-200">\n                        <h3 class="inline-flex items-center relative">\n                            <Link\n                                :href="`/`"\n                                class="text-gray-200 hover:text-blue-500">\n                                Watch Now\n                            </Link>\n                        </h3>\n                        <h3 v-show="route().current() !== \'public.news.index\'"\n                            class="inline-flex items-center relative">\n                        <Link\n\n                            :href="`/public/news`"\n                            class="text-gray-200 hover:text-blue-500">\n                                News\n\x3c!--                            <div v-if="streamStore.isLive"--\x3e\n\x3c!--                                class="text-xs text-white bg-red-800 uppercase flex justify-center items-center absolute -right-4 top-1.5--\x3e\n\x3c!--                                    font-semibold inline-block py-0.5 px-1 rounded last:mr-0 mr-1">--\x3e\n\x3c!--                               live--\x3e\n\x3c!--                            </div>--\x3e\n                        </Link>\n                        </h3>\n                        <h3 v-if="route().current(\'public.news.index\')"\n                            class="inline-flex items-center relative">\n                            <Link\n                                :href="`/login`"\n                                class="hover:text-blue-500">\n                                Login\n                            </Link>\n                        </h3>\n                    </div>\n\n                </div>\n\n\n\n            </div>\n        </div>\n    </nav>\n    </div>\n</template>\n\n<script setup>\nimport JetApplicationMark from \'@/Jetstream/ApplicationMark.vue\';\nimport JetDropdownLink from \'@/Jetstream/DropdownLink.vue\';\nimport { Link } from \'@inertiajs/inertia-vue3\';\nimport NotificationsButton from "@/Components/Navigation/NotificationsButton.vue";\nimport JetDropdown from \'@/Jetstream/Dropdown.vue\';\nimport {Inertia} from "@inertiajs/inertia";\nimport { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";\nimport { useChatStore } from "@/Stores/ChatStore.js";\nimport { useStreamStore } from "@/Stores/StreamStore";\nimport { useUserStore } from "@/Stores/UserStore";\nimport { useWelcomeStore } from "@/Stores/WelcomeStore";\nimport {onMounted} from "vue";\n\nlet chat = useChatStore();\nlet videoPlayerStore = useVideoPlayerStore();\nlet streamStore = useStreamStore()\nlet userStore = useUserStore()\nlet welcomeStore = useWelcomeStore()\n\nstreamStore.isLive(true)\n\nlet props = defineProps({\n    user: Object,\n})\n// let isStreamPage = false\n//\n// function setPage() {\n//     if (videoPlayerStore.currentPage = "stream") {\n//         videoPlayerStore.currentPageIsStream = true;\n//     } else\n//         videoPlayerStore.currentPageIsStream = false;\n// }\n\n// setPage()\n\nonMounted(() => {\n    getUser()\n})\n\nfunction getUser() {\n    if (props.user) {\n        userStore.id = props.user.id\n        userStore.roleId = props.user.role_id\n        userStore.userIsAdmin = props.user.isAdmin\n    }\n    userStore.isSubscriber()\n    userStore.isCreator()\n    userStore.isVip()\n    userStore.isAdmin()\n}\n\n<\/script>\n<style>\n\n.isFullPageCss {\n    background: rgba(0, 0, 0, 0.8);\n    /*background: yellow;*/\n}\n\n</style>\n'],sourceRoot:""}]);const l=i},75460:(e,t,n)=>{n.d(t,{Z:()=>P});var r=n(70821),o=n(15458),s=(n(88333),n(39038)),i=(n(93055),n(68464),n(9680),n(40191)),l=n(35164),a=n(53489),c=n(90771),u=n(27569),d={class:"fixed top-0 w-full nav-mask"},m={class:"sticky top-0 bg-black border-b border-gray-100 z-50"},p={class:"max-w-7xl mx-auto px-4 lg:px-6 xl:px-8 z-50"},f={class:"flex justify-between h-16"},v={class:"flex"},x={class:"shrink-0 flex items-center"},h={class:"space-x-4 py-6 pt-6 ml-8 lg:flex text-gray-200"},g={class:"inline-flex items-center relative"},b={class:"inline-flex items-center relative"},S={key:0,class:"inline-flex items-center relative"};const k={__name:"PublicNavigationMenu",props:{user:Object},setup:function(e){(0,l.a)(),(0,i.q)();var t=(0,a.o)(),n=(0,c.L)();(0,u.v)();t.isLive(!0);var k=e;return(0,r.onMounted)((function(){!function(){k.user&&(n.id=k.user.id,n.roleId=k.user.role_id,n.userIsAdmin=k.user.isAdmin);n.isSubscriber(),n.isCreator(),n.isVip(),n.isAdmin()}()})),function(e,t){return(0,r.openBlock)(),(0,r.createElementBlock)("div",d,[(0,r.createElementVNode)("nav",m,[(0,r.createElementVNode)("div",p,[(0,r.createElementVNode)("div",f,[(0,r.createElementVNode)("div",v,[(0,r.createElementVNode)("div",x,[(0,r.createVNode)((0,r.unref)(s.rU),{href:e.route("home")},{default:(0,r.withCtx)((function(){return[(0,r.createVNode)(o.Z,{class:"block h-9 w-auto"})]})),_:1},8,["href"])]),(0,r.createElementVNode)("div",h,[(0,r.createElementVNode)("h3",g,[(0,r.createVNode)((0,r.unref)(s.rU),{href:"/",class:"text-gray-200 hover:text-blue-500"},{default:(0,r.withCtx)((function(){return[(0,r.createTextVNode)(" Watch Now ")]})),_:1})]),(0,r.withDirectives)((0,r.createElementVNode)("h3",b,[(0,r.createVNode)((0,r.unref)(s.rU),{href:"/public/news",class:"text-gray-200 hover:text-blue-500"},{default:(0,r.withCtx)((function(){return[(0,r.createTextVNode)(" News ")]})),_:1})],512),[[r.vShow,"public.news.index"!==e.route().current()]]),e.route().current("public.news.index")?((0,r.openBlock)(),(0,r.createElementBlock)("h3",S,[(0,r.createVNode)((0,r.unref)(s.rU),{href:"/login",class:"hover:text-blue-500"},{default:(0,r.withCtx)((function(){return[(0,r.createTextVNode)(" Login ")]})),_:1})])):(0,r.createCommentVNode)("",!0)])])])])])])}}};var w=n(93379),N=n.n(w),y=n(61930),V={insert:"head",singleton:!1};N()(y.Z,V);y.Z.locals;const P=k},34350:(e,t,n)=>{n.d(t,{Z:()=>l});var r=n(70821),o=(0,r.createElementVNode)("div",null,null,-1),s={class:"h-full w-full overflow-scroll"};const i={},l=(0,n(83744).Z)(i,[["render",function(e,t){return(0,r.openBlock)(),(0,r.createElementBlock)(r.Fragment,null,[o,(0,r.createElementVNode)("div",s,[(0,r.renderSlot)(e.$slots,"default")])],64)}]])},93717:(e,t,n)=>{n.r(t),n.d(t,{default:()=>a});var r=n(70821),o=n(75460),s=n(34350),i=(0,r.createElementVNode)("div",{class:"px-6 py-4"},[(0,r.createElementVNode)("div",{class:"bg-gray-200 mx-auto p-5 my-10 h-full w-full text-gray-900 rounded"},[(0,r.createElementVNode)("div",{class:"bg-white text-gray-900"},"Public News Page")])],-1),l={layout:s.Z};const a=Object.assign(l,{__name:"Index",setup:function(e){return function(e,t){var n=(0,r.resolveComponent)("Head");return(0,r.openBlock)(),(0,r.createElementBlock)(r.Fragment,null,[(0,r.createVNode)(n,{title:"News"}),(0,r.createElementVNode)("div",null,[(0,r.createVNode)(o.Z,{class:"fixed top-0 w-full nav-mask"})]),i],64)}}})}}]);
//# sourceMappingURL=3717.js.map