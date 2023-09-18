import {defineStore} from "pinia";
import {ref} from "vue";
import {useUserStore} from "@/Stores/UserStore";
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore";

export let useChannelStore = defineStore('channelStore', {
    state: () => ({
        currentChannelId: ref(0),
        currentChannelName: '',
        isLive: false,
        viewerCount: 0,
        channel_list: {},
        userAddedToChannels: false,
    }),

    actions: {
        getChannels() {
            axios.get('/api/channels_list')
                .then(response => {
                    this.channel_list = response.data;
                })
                .catch(error => {
                    console.log(error);
                })
        },
        async changeChannel(channel) {
            useVideoPlayerStore().currentChannelName = channel.name
            this.currentChannelName = channel.name
            this.currentChannelId = channel.id
            this.isLive = channel.isLive
            console.log('Change Channel')
            console.log(this.currentChannelId)
            console.log(this.currentChannelName)
            if (this.isLive) {
                console.log('Channel is live.')
            }
            if (useUserStore().id !== null) {
                this.userAddedToChannels = true
                 await this.disconnectViewerFromChannel()
                 await this.addViewerToChannel()
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
        async clearChannel() {
            this.currentChannelName = null;
            this.currentChannelId = null;
            await this.disconnectViewerFromChannel();
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
        async addViewerToChannel() {
            useVideoPlayerStore().videoName = null
            Echo.join('viewerCount.' + this.currentChannelId)
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
        async disconnectViewerFromChannel() {
            Echo.leaveAllChannels('viewerCount.' + this.currentChannelId)
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
