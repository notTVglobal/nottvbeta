import { defineStore } from "pinia";

export let useVideoPlayerStore = defineStore('videoPlayer', {
    state() {
        return {
            class: 'blue',
            videoSourceId: '',
            key: 0,
            videoName: '',
            fullPage: Boolean,
            muted: Boolean,
            paused: Boolean
        };
    },

    actions: {
        makeVideoFullPage() {
            this.class = 'videoFullPage';
            this.fullPage = true;
        },
        makeVideoTopRight() {
            this.class = 'videoTopRight';
            this.fullPage = false;
        },
        loadVideo1() {
            this.videoSourceId = "http://mist.nottv.io:8080/spring.mp4";
            this.key += 1;
            this.videoName = "Spring";
            this.paused = false;
        },
        loadVideo2() {
            this.videoSourceId = "http://mist.nottv.io:8080/dune1984.mp4";
            this.key += 1;
            this.videoName = "Dune";
            this.paused = false;
        },
        loadVideo3() {
            this.videoSourceId = "http://mist.nottv.io:8080/go1984.mp4";
            this.key += 1;
            this.videoName = "1984";
            this.paused = false;
        },
        loadVideo4() {
            this.videoSourceId = "http://mist.nottv.io:8080/tmr1984.mp4";
            this.key += 1;
            this.videoName = "The Terminator";
            this.paused = false;
        },
        loadVideo5() {
            this.videoSourceId = "http://mist.nottv.io:8080/naturalworld.mp4";
            this.key += 1;
            this.videoName = "Natural World";
            this.paused = false;
        },
    }
})
