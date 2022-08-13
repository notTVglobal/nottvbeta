import { defineStore } from "pinia";

export let useVideoPlayerStore = defineStore('videoPlayer', {
    state() {
        return {
            class: '',
            videoContainerClass: '',
            videoSourceIdSrc1: '',
            videoSourceIdSrc2: '',
            videoSourceIdSrc3: '',
            videoSourceTypeSrc1: '',
            videoSourceTypeSrc2: '',
            videoSourceTypeSrc3: '',
            key: 0,
            videoName: '',
            fullPage: Boolean,
            loggedIn: Boolean,
            muted: Boolean,
            paused: Boolean
        };
    },

    actions: {
        makeVideoFullPage() {
            this.class = 'videoFullPage';
            this.videoContainerClass = 'videoContainerFullPage';
            this.fullPage = true;
        },
        makeVideoTopRight() {
            this.class = 'videoTopRight';
            this.videoContainerClass = 'videoContainerTopRight';
            this.fullPage = false;
        },
        loadVideo1() {
            this.videoSourceIdSrc1 = "http://mist.nottv.io:8080/spring.mp4";
            this.videoSourceTypeSrc1 = "video/mp4";
            this.videoSourceIdSrc2 = "http://mist.nottv.io:8080/hls/spring/index.m3u8";
            this.videoSourceTypeSrc2 = "application/x-mpegURL";
            this.videoSourceIdSrc3 = "ws://mist.nottv.io:8080/spring.mp4";
            this.videoSourceTypeSrc3 = "video/mp4";
            this.key += 1;
            this.videoName = "Spring";
            this.paused = false;
        },
        loadVideo2() {
            this.videoSourceIdSrc1 = "http://mist.nottv.io:8080/dune1984.mp4";
            this.videoSourceTypeSrc1 = "video/mp4";
            this.videoSourceIdSrc2 = "http://mist.nottv.io:8080/hls/dune1984/index.m3u8";
            this.videoSourceTypeSrc2 = "application/x-mpegURL";
            this.videoSourceIdSrc3 = "ws://mist.nottv.io:8080/dune1984.mp4";
            this.videoSourceTypeSrc3 = "video/mp4";
            this.key += 1;
            this.videoName = "Dune";
            this.paused = false;
        },
        loadVideo3() {
            this.videoSourceIdSrc1 = "http://mist.nottv.io:8080/go1984.mp4";
            this.videoSourceTypeSrc1 = "video/mp4";
            this.videoSourceIdSrc2 = "http://mist.nottv.io:8080/hls/go1984/index.m3u8";
            this.videoSourceTypeSrc2 = "application/x-mpegURL";
            this.videoSourceIdSrc3 = "ws://mist.nottv.io:8080/go1984.mp4";
            this.videoSourceTypeSrc3 = "video/mp4";
            this.key += 1;
            this.videoName = "1984";
            this.paused = false;
        },
        loadVideo4() {
            this.videoSourceIdSrc1 = "http://mist.nottv.io:8080/tmr1984.mp4";
            this.videoSourceTypeSrc1 = "video/mp4";
            this.videoSourceIdSrc2 = "http://mist.nottv.io:8080/hls/tmr1984/index.m3u8";
            this.videoSourceTypeSrc2 = "application/x-mpegURL";
            this.videoSourceIdSrc3 = "ws://mist.nottv.io:8080/tmr1984.mp4";
            this.videoSourceTypeSrc3 = "video/mp4";
            this.key += 1;
            this.videoName = "The Terminator";
            this.paused = false;
        },
        loadVideo5() {
            this.videoSourceIdSrc1 = "http://mist.nottv.io:8080/naturalworld.mp4";
            this.videoSourceTypeSrc1 = "video/mp4";
            this.videoSourceIdSrc2 = "http://mist.nottv.io:8080/hls/naturalworld/index.m3u8";
            this.videoSourceTypeSrc2 = "application/x-mpegURL";
            this.videoSourceIdSrc3 = "ws://mist.nottv.io:8080/naturalworld.mp4";
            this.videoSourceTypeSrc3 = "video/mp4";
            this.key += 1;
            this.videoName = "Natural World";
            this.paused = false;
        },
    }
})
