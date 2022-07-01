<template>
    <!-- Test video player -->
<!--    <div class="mistvideo w-80" id="liberty_april_03_RY9YDHbitV4r">-->
<!--    </div>-->
    <video width="320" height="240" controls>
        <source src="https://cloudflarestream.com/$VIDEOID/manifest/manifest.m3u8" type="application/x-mpegURL">
    </video>

</template>

<script setup>
// MistServer Script
// var a = function(){
//     mistPlay("liberty_april_03",{
//         target: document.getElementById("liberty_april_03_RY9YDHbitV4r"),
//         muted: true
//     });
// };
// if (!window.mistplayers) {
//     var p = document.createElement("script");
//     p.src = "http://mist.nottv.io:8080/player.js"
//     document.head.appendChild(p);
//     p.onload = a;
// }
// else { a(); }

</script>
