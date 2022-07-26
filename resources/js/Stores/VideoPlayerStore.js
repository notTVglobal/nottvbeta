import { defineStore } from "pinia";

export let useVideoPlayerStore = defineStore('videoPlayer', {
    state() {
        return {
            class: 'blue',
            videoSourceId: '',
            key: 0,
            videoName: ''
        };
    },

    actions: {
        makeVideoFullPage() {
            this.class = 'videoFullPage';
        },
        makeVideoTopRight() {
            this.class = 'videoTopRight';
        },
        loadVideo1() {
            this.videoSourceId = "../../images/Spring-BlenderOpenMovie-WhWc3b3KhnY.webm";
            this.key += 1;
            this.videoName = "Spring";
        },
        loadVideo2() {
            this.videoSourceId = "../../images/dune-1984.mp4";
            this.key += 1;
            this.videoName = "Dune";
        },
        loadVideo3() {
            this.videoSourceId = "../../images/GO1984.mp4";
            this.key += 1;
            this.videoName = "1984";
        },
        loadVideo4() {
            this.videoSourceId = "../../images/TMR1984.mp4";
            this.key += 1;
            this.videoName = "The Terminator";
        },
        loadVideo5() {
            this.videoSourceId = "../../images/Natural.World.S03E04.The.Desire.of.the.Moth.1984.VHSrip.x264.AAC-ASTRO.mp4";
            this.key += 1;
            this.videoName = "Natural World";
        },
    }
})
