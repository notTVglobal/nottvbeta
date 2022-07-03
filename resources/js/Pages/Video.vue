

<template>
    <Head title="Video Player"/>
    <div class="max-w-lg mx-auto mt-24">
        <h1 class="text-4xl font-bold text-center">Video Player</h1>
        <Link :href="('/dashboard')">Dashboard</Link>
    <div>
<!--        <video width="320" height="240" controls>-->
<!--&lt;!&ndash;            <source src="https://cloudflarestream.com/91dc50d14c619364b534537fc95b45b4/manifest/manifest.m3u8" type="application/x-mpegURL">&ndash;&gt;-->
<!--&lt;!&ndash;            <source src="http://mist.nottv.io:8080/liberty_april_02.mp4" type="video/mp4">&ndash;&gt;-->
<!--            <source src="http://mist.nottv.io:8080/hls/liberty_april_02/index.m3u8" type="application/x-mpegURL">-->


<!--        </video>-->
<!--        <video-player :options="videoOptions" />-->
<!--        <video ref="video" width="100%" height="640" controls></video>-->
        <div style="position: relative; padding-top: 56.25%; width: 100%"><iframe src="https://iframe.cloudflarestream.com/8f039aa05c93a34bec6904df615dd747" style="border: none; position: absolute; top: 0; left: 0; height: 100%; width: 100%;" allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;" allowfullscreen="true"></iframe></div>

        <video-player id=vid1 width=600 height=300 class="" controls>
<!--            <source-->
<!--                src="http://mist.nottv.io:8080/hls/beta_test/index.m3u8"-->
<!--                type="application/x-mpegURL">-->
        </video-player>

    </div>
    </div>
</template>


<script>
import videojs from 'video.js';
import VideoPlayer from "../Components/useVideo";
export default {
    components: {VideoPlayer}
}
// const player = videojs();

// player.src({
//     src: 'https://d2zihajmogu5jn.cloudfront.net/bipbop-advanced/bipbop_16x9_variant.m3u8',
//     type: 'application/x-mpegURL',
//     withCredentials: true
// });vue

//         let stream = "http://mist.nottv.io:8080/hls/beta_test/index.m3u8";


</script>
