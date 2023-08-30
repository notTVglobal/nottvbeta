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
        async changeChannel(channel) {
            this.currentChannelName = channel.name
            this.currentChannelId = channel.id
            this.isLive = channel.isLive
            await this.addViewerToChannel()
            // await this.getViewerCount()


            // window.Echo.channel('viewerCount.' + channel.id)
            //     .listen('ViewerJoinChannel', (e) => {
            //         console.log(e);
            //         // channelStore.viewerCount = channelStore.viewerCount + 1
            //         this.increaseViewerCount();
            //     })
            //     .listen('ViewerLeaveChannel', (e) => {
            //         console.log(e);
            //         // this.viewerCount = this.viewerCount - 1;
            //         this.decreaseViewerCount();
            //     })
            // useVideoPlayerStore().toggleChannels()
            // useVideoPlayerStore().toggleOttChannels()
            // useVideoPlayerStore().ott = 0

            // for streaming to mistServer we need:
            // streamName = channel.source.name (this is the mistServer stream name)
            // streamPath = channel.source.path (e.g. 'https://mist.not.tv/hls/')
            // streamType = channel.source.type (e.g. "application/x-mpegURL")
            useVideoPlayerStore().loadNewSourceFromMist(channel.source)
        },
        async clearChannel() {
            this.currentChannelName = null;
            this.currentChannelId = null;
            await this.disconnectViewerFromChannel();
            if (useUserStore().oldLoggedOutId !== null) {
                await this.disconnectLoggedOutUserFromChannel();
            }
        },
        async getViewerCount() {
            await axios.post('/api/getCurrentViewers', {'channel_id': this.currentChannelId})
                .then(response => {
                    this.viewerCount = response.data[0];
                    console.log('number of viewers: ' + response.data[0]);
                    return response.data[0];
                })
                .catch(error => {
                    console.log(error);
                })
        },
        async addViewerToChannel() {
            console.log(this.currentChannelId)
            console.log('Channel: ' + this.currentChannelName)
            useVideoPlayerStore().videoName = ''
                await axios.post('/api/addCurrentViewer', {'channel_id': this.currentChannelId, 'user_id': useUserStore().id})
                    .then(response => {
                        this.viewerCount = response.data[0]
                        console.log('Number of viewers: ' + response.data[0])
                    })
                    .catch(error => {
                        console.log(error);
                    })
                console.log('channel connected');

            Echo.channel('viewerCount.' + this.currentChannelId)
                .listen('ViewerJoinChannel', (e) => {
                    console.log('viewer joined')
                    // channelStore.viewerCount = channelStore.viewerCount + 1
                    this.increaseViewerCount()
                })
                .listen('ViewerLeaveChannel', (e) => {
                    console.log('viewer left')
                    // this.viewerCount = this.viewerCount - 1
                    this.decreaseViewerCount()
                })

        },
        async disconnectViewerFromChannel() {
            window.Echo.leaveChannel('viewerCount.' + this.currentChannelId)
            this.viewerCount = 0
            await axios.post('/api/removeCurrentViewer', {
                'channel_id': this.currentChannelId,
                'user_id': useUserStore().id,
                'old_logged_out_id': useUserStore().oldLoggedOutId
            })
                .then(response => {
                    console.log('channel disconnected');
                })
                .catch(error => {
                    console.log(error);
                })
        },
        async disconnectLoggedOutUserFromChannel($id) {
            Echo.leaveChannel('viewerCount.' + this.currentChannelId)
            this.viewerCount = 0
            await axios.post('/api/removeCurrentViewer', {'channel_id': this.currentChannelId, 'user_id': $id})
                .then(response => {
                    //
                })
                .catch(error => {
                    console.log(error);
                })
        },
        increaseViewerCount(){
            this.viewerCount++;
        },
        decreaseViewerCount(){
            this.viewerCount--;
        }
    },

    // getters: {
    //     viewerIncrement: () => this.viewerCount++,
    //     viewerDecrement: () => this.viewerCount--,
    // }

});
