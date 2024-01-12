import {defineStore} from "pinia";
import {ref} from "vue";
import {useUserStore} from "@/Stores/UserStore";
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore";
import videojs from "video.js";

export const useChannelStore = defineStore('channelStore', {
    state: () => ({
        currentChannelId: 0,
        currentChannelName: '',
        isLive: false,
        viewerCount: 0,
        channel_list: {},
        userAddedToChannels: false,
        channelsLoaded: false,
    }),

    actions: {
        getChannels() {
            axios.get('/api/channels_list')
                .then(response => {
                    this.channel_list = response.data;
                    this.channelsLoaded = true;
                })
                .catch(error => {
                    console.log(error);
                })
        },
        async changeChannel(channel) {
            let oldChannelId = 0
            oldChannelId = this.currentChannelId
            useVideoPlayerStore().currentChannelName = channel.name
            this.currentChannelName = channel.name
            this.currentChannelId = channel.id
            this.isLive = channel.isLive
            useVideoPlayerStore().clearNowPlaying()
            console.log('Change Channel')
            console.log(channel.id)
            console.log(channel.name)
            if (this.isLive) {
                console.log('Channel is live.')
            }
            if (useUserStore().id !== null) {
                this.userAddedToChannels = true
                if (oldChannelId !== 0) {
                    this.disconnectViewerFromChannel(oldChannelId)
                }
                await this.addViewerToChannel(channel.id)
            }

            if (channel.channel_source !== null) {
                useVideoPlayerStore().videoName = channel.channel_source.name
                if (channel.channel_source.provider === 'youtube'){
                    useVideoPlayerStore().loadNewSourceFromYouTube(channel.channel_source.path)
                } else if (channel.channel_source.provider === 'rumble') {
                    useVideoPlayerStore().loadNewLiveSourceFromRumble(channel.channel_source.path)
                }


            } else
                useVideoPlayerStore().loadNewSourceFromMist(channel.stream)


            // await this.getViewerCount()

            // for streaming to mistServer we need:
            // streamName = channel.source.name (this is the mistServer stream name)
            // streamPath = channel.source.path (e.g. 'https://mist.not.tv/hls/')
            // streamType = channel.source.type (e.g. "application/x-mpegURL")

        },
        clearChannel() {
            this.disconnectViewerFromChannel(this.currentChannelId);
            this.currentChannelName = null;
            this.currentChannelId = null;
            this.userAddedToChannels = false;
            this.viewerCount = 0;
            // if (useUserStore().oldLoggedOutId !== null) {
            //     await this.disconnectLoggedOutUserFromChannel();
            // }
        },
        async getViewerCount() {
            // await axios.post('/api/getCurrentViewers', {'channel_id': this.currentChannelId})
            //     .then(response => {
            //         this.viewerCount = response.data[0];
            //         console.log('number of viewers: ' + response.data[0]);
            //         return response.data[0];
            //     })
            //     .catch(error => {
            //         console.log(error);
            //     })
        },
        async addViewerToChannel(id) {
            useVideoPlayerStore().videoName = ''
            Echo.join('viewerCount.' + id)
                .here((users) => {
                    this.userAddedToChannels = true
                    this.viewerCount = users.length
                    console.log('User added to channel')
                    console.log('# of users: ' + users.length)
                })
                .joining((users) => {
                    console.log("Someone entered");
                    this.viewerCount++
                    // this.increaseViewerCount()
                })
                .leaving((users) => {
                    console.log("Someone left");
                    this.viewerCount--
                    // this.decreaseViewerCount()
                })
                // await axios.post('/api/addCurrentViewer', {'channel_id': this.currentChannelId, 'user_id': useUserStore().id})
                //     .then(response => {
                //         this.viewerCount = response.data[0]
                //         console.log('Number of viewers: ' + response.data[0])
                //     })
                //     .catch(error => {
                //         console.log(error);
                //     })
                console.log('Channel connected');

            // Echo.channel('viewerCount.' + this.currentChannelId)
            //     .listen('ViewerJoinChannel', (e) => {
            //         console.log('viewer joined')
            //         // channelStore.viewerCount = channelStore.viewerCount + 1
            //         // this.increaseViewerCount()
            //     })
            //     .listen('ViewerLeaveChannel', (e) => {
            //         console.log('viewer left')
            //         // this.viewerCount = this.viewerCount - 1
            //         // this.decreaseViewerCount()
            //     })

        },
        disconnectViewerFromChannel(id) {
            Echo.leave('viewerCount.' + id)
            // await axios.post('/api/removeCurrentViewer', {
            //     'channel_id': this.currentChannelId,
            //     'user_id': useUserStore().id,
            //     'old_logged_out_id': useUserStore().oldLoggedOutId
            // })
            //     .then(response => {
            //         console.log('channel disconnected');
            //     })
            //     .catch(error => {
            //         console.log(error);
            //     })
        },
        // async disconnectLoggedOutUserFromChannel($id) {
        //     Echo.leaveChannel('viewerCount.' + this.currentChannelId)
        //     this.viewerCount = 0
        //     await axios.post('/api/removeCurrentViewer', {'channel_id': this.currentChannelId, 'user_id': $id})
        //         .then(response => {
        //             //
        //         })
        //         .catch(error => {
        //             console.log(error);
        //         })
        // },
    },

    // getters: {
    //     viewerIncrement: () => this.viewerCount++,
    //     viewerDecrement: () => this.viewerCount--,
    // }

});
