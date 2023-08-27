import {defineStore} from "pinia";
import {ref} from "vue";
import {useUserStore} from "@/Stores/UserStore";
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore";

export let useChannelStore = defineStore('channelStore', {
    state: () => ({
        currentChannelId: 0,
        currentChannelName: '',
        currentVideoName: '',
        isLive: null,
        viewerCount: 0,
        channel_list: {},

    }),

    actions: {
        getChannels() {
            axios.get('/api/channel_list')
                .then(response => {
                    this.channel_list = response.data;
                })
                .catch(error => {
                    console.log(error);
                })
        },
        changeChannel(channel) {
            this.currentChannelName = channel.name
            this.currentChannelId = channel.id
            if (channel.video != null) {
            this.currentVideoName = channel.video.name
            }
            this.isLive = channel.isLive
            // for streaming to mistServer we need:
            // streamName = channel.source.name (this is the mistServer stream name)
            // streamPath = channel.source.path (e.g. 'https://mist.not.tv/hls/')
            // streamType = channel.source.type (e.g. "application/x-mpegURL")
            useVideoPlayerStore().loadNewSourceFromMist(channel.source)
            this.addViewerToChannel()
            this.getViewerCount()
        },
        getViewerCount() {
            if(this.currentChannelId !== null) {
                axios.post('/api/getCurrentViewers', {'channel_id': this.currentChannelId})
                    .then(response => {
                        this.viewerCount = response.data[0];
                    })
                    .catch(error => {
                        console.log(error);
                    })
            }
        },
        addViewerToChannel() {
            if(this.currentChannelId !== null) {
                axios.post('/api/addCurrentViewer', {'channel_id': this.currentChannelId, 'user_id': useUserStore().id})
                    .then(response => {
                        this.viewerCount++
                    })
                    .catch(error => {
                        console.log(error);
                    })
                console.log('channel connected');
            }
        },
        disconnectViewerFromChannel() {
            if(this.currentChannelId !== null) {
                axios.post('/api/removeCurrentViewer', {
                    'channel_id': this.currentChannelId,
                    'user_id': useUserStore().id,
                    'old_logged_out_id': useUserStore().oldLoggedOutId
                })
                    .then(response => {
                        //
                    })
                    .catch(error => {
                        console.log(error);
                    })
                console.log('channel disconnected');
            }
        },
        disconnectLoggedOutUserFromChannel($id) {
            if(this.currentChannelId !== null) {
                axios.post('/api/removeCurrentViewer', {'channel_id': this.currentChannelId, 'user_id': $id})
                    .then(response => {
                        //
                    })
                    .catch(error => {
                        console.log(error);
                    })
            }
        },
        increaseViewerCount(){
            this.viewerCount++
        },
        decreaseViewerCount(){
            this.viewerCount--
        }
    },

    getters: {
    //     viewerIncrement: () => this.viewerCount++,
    //     viewerDecrement: () => this.viewerCount--,
    }

});
