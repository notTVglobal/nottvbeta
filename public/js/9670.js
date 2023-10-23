"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[9670],{13971:(e,o,n)=>{n.d(o,{Z:()=>a});var r=n(94015),i=n.n(r),d=n(23645),l=n.n(d)()(i());l.push([e.id,".dropzone[data-v-39899ada]{align-items:center;background-color:#fce4bb;border:2px dashed #000;display:flex;flex-direction:column;justify-content:center;row-gap:16px;transition:all .3s ease}label[data-v-39899ada]{padding:8px 12px;transition:all .3s ease}.active-dropzone[data-v-39899ada],label[data-v-39899ada]{background-color:#4bb1b1;color:#fff}.active-dropzone[data-v-39899ada]{border-color:#fff}.active-dropzone label[data-v-39899ada]{background-color:#fff;color:#4bb1b1}","",{version:3,sources:["webpack://./resources/js/Components/Uploaders/VideoUpload.vue"],names:[],mappings:"AAuIA,2BAII,kBAAmB,CAGnB,wBAAyB,CADzB,sBAA0B,CAL1B,YAAa,CACb,qBAAsB,CACtB,sBAAuB,CAEvB,YAAa,CAGb,uBACJ,CAEA,uBACI,gBAAiB,CAGjB,uBACJ,CAEA,yDAJI,wBAAyB,CADzB,UASJ,CAJA,kCAEI,iBAEJ,CAEA,wCACI,qBAAsB,CACtB,aACJ",sourcesContent:['<template>\n    <div>\n\n        <progress v-show="userStore.uploadPercentage != 0" max="100" :value="userStore.uploadPercentage" class="w-full" />\n        <div v-show="userStore.uploadPercentage != 0" class="w-full mb-4">{{userStore.uploadPercentageRounded}}%</div>\n\n        <div v-show="uploadingMessage" class="mb-4 font-bold text-center">Please stay on this screen until upload is complete.</div>\n        <div v-show="uploadCompleteMessage" class="mb-4 font-bold text-center">Upload is complete. The video is now processing.</div>\n        <form v-show="!isHidden" id="videoUploadForm" action="/videoupload" class="dropzone dropzoneFile border border-black rounded w-full h-48 max-w-md px-2 py-2 mb-6">\n            \x3c!--                            add input fields and a submit button to send data back to Laravel --\x3e\n            <input hidden name="movieId" v-model="form.movieId">\n\x3c!--            <input hidden name="movieTrailerId" v-model="form.movieTrailerId">--\x3e\n            <input hidden name="showEpisodeId" v-model="form.showEpisodeId">\n        </form>\n\n    </div>\n</template>\n\n<script setup>\nimport { Dropzone } from "dropzone";\nimport { useForm } from "@inertiajs/inertia-vue3"\nimport { useUserStore } from "@/Stores/UserStore";\nimport { onMounted, ref } from "vue";\nimport { Inertia } from "@inertiajs/inertia";\n\nlet userStore = useUserStore()\nlet uploadPercentage = ref(0);\nlet uploadingMessage = ref(0);\nlet uploadCompleteMessage = ref(0);\nlet isHidden = ref(false);\n\nonMounted(() => {\n    // Make sure the element with the ID "videoUploadForm" is available in the DOM.\n    const videoUploadForm = document.getElementById(\'videoUploadForm\');\n\n    if (videoUploadForm) {\n        // Initialize Dropzone on the element.\n\n        // see options for Dropzone here: https://github.com/dropzone/dropzone/blob/main/src/options.js\n        let myDropzone = new Dropzone("#videoUploadForm", {\n            url: "/videoupload",\n            paramName: "file", // The name that will be used to transfer the file\n            maxFilesize: \'25 GB\', // MB\n            chunking: true,\n            chunkSize: 2 * 1024 * 1024,\n            parallelChunkUploads: false,\n            retryChunks: true,\n            retryChunksLimit: 10,\n            capture: null,\n            // can set the capture method as camera, microphone or video\n            // for mobile devices to skip the file selection and choose the\n            // recording device instead.\n            acceptedFiles: \'video/*, audio/*\',\n            uploadprogress: function(file, progress, bytesSent) {\n                userStore.uploadPercentage = progress;\n                console.log(userStore.uploadPercentage);\n                if(userStore.uploadPercentage !== 100){\n                    isHidden = true;\n                }\n            },\n            dictDefaultMessage: "Click here or Drop video here to upload <br>(Max video file size is 25GB)",\n            forceFallback: false, // for testing, set to true.\n            accept: function(file, done) {\n                if (file.name === "") {\n                    done("Need a file.");\n                } else if (file.size > 25000000000) {\n                    console.log(file.size)\n                    done("Video file too big.");\n                    alert(\'Video file must be smaller than 25GB\');\n                }\n                else { done(); }\n            }\n        });\n\n        myDropzone.on("addedfile", file => {\n            uploadingMessage = 1;\n            console.log(`File added: ${file.name}`);\n\n        });\n\n        myDropzone.on("complete", function(file) {\n            uploadingMessage = 0;\n            uploadCompleteMessage = 1;\n            myDropzone.removeFile(file);\n            userStore.uploadPercentage = 0;\n            isHidden = false;\n            Inertia.reload({\n                only: ["videos"],\n            });\n        });\n\n    } else {\n        console.error(\'Element with ID "videoUploadForm" not found in the DOM.\');\n    }\n})\n\nlet props = defineProps({\n    movieId: Number,\n    movieTrailerId: Number,\n    showEpisodeId: Number,\n})\n\nfunction setMovieOrEpisodeId() {\n    if (props.movieId !== null) {\n        userStore.uploadMovieId = props.mov;\n    } else if (props.movieTrailerId !== null) {\n        userStore.movieTrailerId = props.movieTrailerId;\n    } else if (props.showEpisodeId !== null) {\n        userStore.uploadShowEpisodeId = props.showEpisodeId;\n    }\n}\n\nsetMovieOrEpisodeId()\n\nlet form = useForm({\n    file: [],\n    // movieId: userStore.uploadMovieId,\n    movieId: props.movieId,\n    movieTrailerId: props.movieId,\n    showEpisodeId: props.showEpisodeId,\n});\n\n// let props = defineProps({\n//     filters: Object,\n//     can: Object,\n//     videos: Object,\n//     message: String,\n//     errors: ref(\'\'),\n//     isHidden: ref(false),\n//     done: ref(),\n// });\n\n<\/script>\n<style scoped>\n\n.dropzone {\n    display: flex;\n    flex-direction: column;\n    justify-content: center;\n    align-items: center;\n    row-gap: 16px;\n    border: 2px dashed #000000;\n    background-color: #fce4bb;\n    transition: 0.3s ease all;\n}\n\nlabel {\n    padding: 8px 12px;\n    color: #fff;\n    background-color: #4bb1b1;\n    transition: 0.3s ease all;\n}\n\n.active-dropzone {\n    color: #fff;\n    border-color: #fff;\n    background-color: #4bb1b1;\n}\n\n.active-dropzone  label {\n    background-color: #fff;\n    color: #4bb1b1;\n}\n/*6b7280*/\n/*4bb1b1*/\n\n</style>\n'],sourceRoot:""}]);const a=l},79670:(e,o,n)=>{n.r(o),n.d(o,{default:()=>h});var r=n(70821),i=n(94623),d=n(39038),l=n(90771),a=n(9680),t=["value"],s={class:"mb-4 font-bold text-center"},p={class:"mb-4 font-bold text-center"},u={id:"videoUploadForm",action:"/videoupload",class:"dropzone dropzoneFile border border-black rounded w-full h-48 max-w-md px-2 py-2 mb-6"};const c={__name:"VideoUpload",props:{movieId:Number,movieTrailerId:Number,showEpisodeId:Number},setup:function(e){var o=(0,l.L)(),n=((0,r.ref)(0),(0,r.ref)(0)),c=(0,r.ref)(0),m=(0,r.ref)(!1);(0,r.onMounted)((function(){if(document.getElementById("videoUploadForm")){var e=new i.f("#videoUploadForm",{url:"/videoupload",paramName:"file",maxFilesize:"25 GB",chunking:!0,chunkSize:2097152,parallelChunkUploads:!1,retryChunks:!0,retryChunksLimit:10,capture:null,acceptedFiles:"video/*, audio/*",uploadprogress:function(e,n,r){o.uploadPercentage=n,console.log(o.uploadPercentage),100!==o.uploadPercentage&&(m=!0)},dictDefaultMessage:"Click here or Drop video here to upload <br>(Max video file size is 25GB)",forceFallback:!1,accept:function(e,o){""===e.name?o("Need a file."):e.size>25e9?(console.log(e.size),o("Video file too big."),alert("Video file must be smaller than 25GB")):o()}});e.on("addedfile",(function(e){n=1,console.log("File added: ".concat(e.name))})),e.on("complete",(function(r){n=0,c=1,e.removeFile(r),o.uploadPercentage=0,m=!1,a.Inertia.reload({only:["videos"]})}))}else console.error('Element with ID "videoUploadForm" not found in the DOM.')}));var f=e;null!==f.movieId?o.uploadMovieId=f.mov:null!==f.movieTrailerId?o.movieTrailerId=f.movieTrailerId:null!==f.showEpisodeId&&(o.uploadShowEpisodeId=f.showEpisodeId);var v=(0,d.cI)({file:[],movieId:f.movieId,movieTrailerId:f.movieId,showEpisodeId:f.showEpisodeId});return function(e,i){return(0,r.openBlock)(),(0,r.createElementBlock)("div",null,[(0,r.withDirectives)((0,r.createElementVNode)("progress",{max:"100",value:(0,r.unref)(o).uploadPercentage,class:"w-full"},null,8,t),[[r.vShow,0!=(0,r.unref)(o).uploadPercentage]]),(0,r.withDirectives)((0,r.createElementVNode)("div",{class:"w-full mb-4"},(0,r.toDisplayString)((0,r.unref)(o).uploadPercentageRounded)+"%",513),[[r.vShow,0!=(0,r.unref)(o).uploadPercentage]]),(0,r.withDirectives)((0,r.createElementVNode)("div",s,"Please stay on this screen until upload is complete.",512),[[r.vShow,(0,r.unref)(n)]]),(0,r.withDirectives)((0,r.createElementVNode)("div",p,"Upload is complete. The video is now processing.",512),[[r.vShow,(0,r.unref)(c)]]),(0,r.withDirectives)((0,r.createElementVNode)("form",u,[(0,r.withDirectives)((0,r.createElementVNode)("input",{hidden:"",name:"movieId","onUpdate:modelValue":i[0]||(i[0]=function(e){return(0,r.unref)(v).movieId=e})},null,512),[[r.vModelText,(0,r.unref)(v).movieId]]),(0,r.withDirectives)((0,r.createElementVNode)("input",{hidden:"",name:"showEpisodeId","onUpdate:modelValue":i[1]||(i[1]=function(e){return(0,r.unref)(v).showEpisodeId=e})},null,512),[[r.vModelText,(0,r.unref)(v).showEpisodeId]])],512),[[r.vShow,!(0,r.unref)(m)]])])}}};var m=n(93379),f=n.n(m),v=n(13971),b={insert:"head",singleton:!1};f()(v.Z,b);v.Z.locals;const h=(0,n(83744).Z)(c,[["__scopeId","data-v-39899ada"]])}}]);
//# sourceMappingURL=9670.js.map