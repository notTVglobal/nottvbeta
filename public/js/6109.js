"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[6109],{15073:(e,t,o)=>{o.d(t,{Z:()=>i});var r=o(94015),n=o.n(r),l=o(23645),s=o.n(l)()(n());s.push([e.id,".channels-mask{z-index:500}","",{version:3,sources:["webpack://./resources/js/Pages/Channels.vue"],names:[],mappings:"AAoDA,eACI,WACJ",sourcesContent:['<template>\n\n    <Head title="Channels" />\n\n        <div id="topDiv" class="channelsSelectMenu absolute top-16 left-0 bg-black bg-opacity-90 text-white p-5 pt-16 h-screen w-full">\n\n            <h1 class="text-3xl font-semibold text-center">Channels</h1>\n            <h2 class="bg-red-800 col-span-3 mt-8 mb-4 py-1 text-center">\n                Channels are not currently enabled.\n            </h2>\n\n\n            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-2 space-y-2 text-center px-16 pb-20 h-3/4 overflow-y-auto items-stretch">\n                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Live</div>\n                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Ambient</div>\n                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Local</div>\n                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">News</div>\n                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Talk</div>\n                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Music</div>\n                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Sports</div>\n                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Variety</div>\n                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Kids</div>\n                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Documentaries</div>\n                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Education</div>\n                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Random</div>\n            </div>\n        </div>\n\n</template>\n\n<script setup>\nimport { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"\nimport { useChatStore } from "@/Stores/ChatStore.js"\nimport {onMounted} from "vue";\n\nlet videoPlayerStore = useVideoPlayerStore()\nlet chat = useChatStore()\n\nuserStore.currentPage = \'channels\'\n\nonMounted(() => {\n    videoPlayerStore.makeVideoFullPage();\n    if (userStore.isMobile) {\n        videoPlayerStore.ottClass = \'ottClose\'\n        videoPlayerStore.ott = 0\n    }\n    document.getElementById("topDiv").scrollIntoView()\n});\n\n<\/script>\n\n<style>\n.channels-mask {\n    z-index:500;\n}\n</style>\n\n\n'],sourceRoot:""}]);const i=s},26109:(e,t,o)=>{o.r(t),o.d(t,{default:()=>h});var r=o(70821),n=o(40191),l=o(35164),s=(0,r.createStaticVNode)('<div id="topDiv" class="channelsSelectMenu absolute top-16 left-0 bg-black bg-opacity-90 text-white p-5 pt-16 h-screen w-full"><h1 class="text-3xl font-semibold text-center">Channels</h1><h2 class="bg-red-800 col-span-3 mt-8 mb-4 py-1 text-center"> Channels are not currently enabled. </h2><div class="grid grid-cols-1 md:grid-cols-3 gap-x-2 space-y-2 text-center px-16 pb-20 h-3/4 overflow-y-auto items-stretch"><div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Live</div><div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Ambient</div><div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Local</div><div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">News</div><div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Talk</div><div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Music</div><div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Sports</div><div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Variety</div><div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Kids</div><div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Documentaries</div><div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Education</div><div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Random</div></div></div>',1);const i={__name:"Channels",setup:function(e){var t=(0,n.q)();(0,l.a)();return userStore.currentPage="channels",(0,r.onMounted)((function(){t.makeVideoFullPage(),userStore.isMobile&&(t.ottClass="ottClose",t.ott=0),document.getElementById("topDiv").scrollIntoView()})),function(e,t){var o=(0,r.resolveComponent)("Head");return(0,r.openBlock)(),(0,r.createElementBlock)(r.Fragment,null,[(0,r.createVNode)(o,{title:"Channels"}),s],64)}}};var a=o(72446),c=o.n(a),b=o(15073),x={insert:"head",singleton:!1};c()(b.Z,x);b.Z.locals;const h=i}}]);
//# sourceMappingURL=6109.js.map