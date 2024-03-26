import { defineStore } from 'pinia'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
// const appSettingStore = useAppSettingStore()
import { useNowPlayingStore } from '@/Stores/NowPlayingStore'
import { useNotificationStore } from '@/Stores/NotificationStore'

const initialState = () => ({
    currentChannelId: 0,
    currentChannelName: '',
    isLive: false,
    viewerCount: 0,
    playbackPriorityType: '',
    mistStreamId: null,
    mistStreamName: null,
    mistStream: null,
    externalSourceId: null,
    channelPlaylistId: null,
    currentChannel: null,
    channel_list: {},
    userAddedToChannels: false,
    channelsLoaded: false,
    reloadChannelsList: false,
})

export const useChannelStore = defineStore('channelStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        getChannels() {
            if (!this.channelsLoaded){
                axios.get('/api/channels_list')
                    .then(response => {
                        this.channel_list = response.data;
                        this.channelsLoaded = true; // removing this flag until we have a Laravel Echo for getting updates.
                    })
                    .catch(error => {
                        console.log(error);
                    })
            }
        },
        setMistStream(stream) {
            this.mistStream = stream;
        },
        clearMistStream() {
            this.mistStream = null;
        },
        reloadChannels() {
                axios.get('/api/channels_list')
                    .then(response => {
                        this.channel_list = response.data;
                        this.channelsLoaded = true; // removing this flag until we have a Laravel Echo for getting updates.
                    })
                    .catch(error => {
                        console.log(error);
                    })
        },

        async fetchChannelDetails(channelId) {
          return await axios.get()
        },

        async changeChannel(channel) {

            this.currentChannel = channel
            // oldChannelId is used to determine whether to disconnect the viewer from the chat channel or not.
            let oldChannelId = 0
            oldChannelId = this.currentChannelId

            // await this.loadChannelMediaByPriority(channel)
            const loadSuccess = await this.loadChannelMediaByPriority(channel);
            if (!loadSuccess) {

                 // Stop execution if loading media failed
            }



            // New channel change process (2024-02-09 tec21 and ChatGPT)
            // Fetch channel details, including playback_priority_type
            // const newChannel = await this.fetchChannelDetails(channel.id);
            // this.currentChannel = channel; // Assume this stores current channel details


            // Add user to the chat channel. However, for the MVP we are only using 1 chat channel.
            if (useUserStore().id !== null) {
                this.userAddedToChannels = true
                if (oldChannelId !== 0) {
                    this.disconnectViewerFromChannel(oldChannelId)
                }
                await this.addViewerToChannel(channel.id)
            }

            // Some externalSource logic... this is old logic.
            // if (channel.channel_external_source !== null) {
            //     videoPlayerStore.videoName = channel.channel_source.name
            //     if (channel.channel_external_source.provider === 'youtube'){
            //         videoPlayerStore.loadNewSourceFromYouTube(channel.channel_external_source.path)
            //     } else if (channel.channel_external_source.provider === 'rumble') {
            //         videoPlayerStore.loadNewLiveSourceFromRumble(channel.channel_external_source.path)
            //     }
            //
            //
            // } else
                // about to get moved to our new process for handling our priorityTypes
                // videoPlayerStore.loadNewSourceFromMist(channel.stream)
            // for streaming to mistServer we need:
            // streamName = channel.source.name (this is the mistServer stream name)
            // streamPath = channel.source.path (e.g. 'https://mist.not.tv/hls/')
            // streamType = channel.source.type (e.g. "application/vnd.apple.mpegURL")


            await this.getViewerCount()

        },

            async loadChannelMediaByPriority(channel) {
                const videoPlayerStore = useVideoPlayerStore();
                const notificationStore = useNotificationStore();
                // console.log('Changing channel with priority:', channel.playback_priority_type); // Log the attempted change
                switch (channel.playback_priority_type) {
                    case 'channelPlaylist':
                        if (channel.channel_playlist) {
                            this.channelPlaylist = channel.channel_playlist;
                            await videoPlayerStore.loadPlaylistVideos(channel.channel_playlist);
                            this.loadChannelInfo(channel);
                        } else {
                            let errorTitle = 'Unable to load channel: ' + channel.name
                            let errorBody = 'No Channel Playlist.'
                            console.warn(errorTitle);
                            console.warn(errorBody);
                            notificationStore.setGeneralServiceNotification(errorTitle, errorBody)
                            // Setup fallback options here.
                        }
                        break;

                    case 'externalSource':
                        if (channel.channel_external_source) {
                            this.externalSource = channel.channel_external_source;
                            await videoPlayerStore.loadExternalSourceVideo(channel.channel_external_source);
                            this.loadChannelInfo(channel);
                        } else {
                            let errorTitle = 'Unable to load channel: ' + channel.name
                            let errorBody = 'No External Source.'
                            console.warn(errorTitle);
                            console.warn(errorBody);
                            notificationStore.setGeneralServiceNotification(errorTitle, errorBody)
                            // Setup fallback options here.
                        }
                        break;

                    case 'mistStream':
                        // console.log('Attempting to load Mist Stream:', channel.mist_stream); // Log the Mist Stream attempt
                        if (channel.mist_stream) {
                            this.mistStream = channel.mist_stream;
                            await videoPlayerStore.loadMistStreamVideo(channel.mist_stream);
                            this.loadChannelInfo(channel);
                        } else {
                            let errorTitle = 'Unable to load channel: ' + channel.name
                            let errorBody = 'No Mist Stream.'
                            console.warn(errorTitle);
                            console.warn(errorBody);
                            notificationStore.setGeneralServiceNotification(errorTitle, errorBody)
                            // Setup fallback options here.
                        }
                        break;

                    default:
                        console.warn('Unknown playback priority type:', channel.playback_priority_type);
                        console.warn('Unable to load channel:', channel.name);
                }
            },
        loadChannelInfo(channel) {
            const nowPlayingStore = useNowPlayingStore();
            this.currentChannelName = channel.name
            this.currentChannelId = channel.id
            this.playbackPriorityType = channel.playback_priority_type
            this.externalSourceId = channel.channel_external_source_id
            this.channelPlaylistId = channel.channel_playlist_id
            this.mistStreamId = channel.mist_stream_id
            this.mistStreamName = channel.mist_stream.name


            console.log('Change Channel')
            console.log(channel.id)
            console.log(channel.name)
            nowPlayingStore.reset()
            nowPlayingStore.activeMedia.type = 'channel'

            // Display the LIVE badge and ViewerCount on the OSD
            // For now this is hardcoded...
            if (this.currentChannelId === 1) {
                nowPlayingStore.viewerCountIsVisible = true
                nowPlayingStore.isLive = true
                this.isLive = true
                console.log('Channel is live.')
            }
        },


        clearChannel() {
            console.log('clear channel')
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
            const videoPlayerStore = useVideoPlayerStore();

            videoPlayerStore.videoName = ''
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
            console.log('disconnect from viewer count')
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
    getters: {
        activeChannels: (state) => {
            // Assuming channel_list is an object; convert it to an array first
            const channelsArray = Object.values(state.channel_list);

            // Filter channels that are active
            // Return the filtered list of active channels
            return channelsArray.filter(channel => channel.active === true);
        }
    },

});
