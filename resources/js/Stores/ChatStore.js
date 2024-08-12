import { defineStore } from "pinia";
import Echo from "laravel-echo";
import { ref } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useUserStore } from "@/Stores/UserStore";
import videojs from "video.js";

// const appSettingStore = useAppSettingStore();

const initialState = () => ({
    showChat: false,
    showEmojiPicker: false,
    class: '',
    oldMessages: [],  // Messages loaded from the database
    newMessages: [],  // Messages received via Echo
    message: '',
    input: '',
    inputTooLong: false,
    echo: [],
    currentChannel: [],
    cooldown: false,
    cooldownIntervalId: null, // Track interval ID
    countdownSeconds: 60, // timeout until next message can be sent, change this throttle in the web.php
    errorMessage: '',
    errorTimeout: null,  // Store the timeout reference
})

export const useChatStore = defineStore('chatStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        toggleEmojiPicker() {
            this.showEmojiPicker = !this.showEmojiPicker
        },
        closeEmojiPicker() {
            this.showEmojiPicker = false
        },
        addEmoji(emoji) {
            this.message += emoji
            this.closeEmojiPicker()
        },
        toggleChatOn() {
            this.showChat = true
        },
        toggleChatOff() {
            this.showChat = false
        },
        toggleChat() {
            this.showChat = !this.showChat;
        },
        makeBig() {
            this.class = 'chatBig';
        },
        makeSmall() {
            this.class = 'chatSmall';
        },
        chatHidden() {
            this.class = 'chatHidden';
            this.showChat = false;
        },
        turnPipChatModeOn() {
            // turn chat mode on
            const videoPlayerStore = useVideoPlayerStore();
            const appSettingStore = useAppSettingStore();
            const userStore = useUserStore();

            if (userStore.isMobile) {
                let videoJs = videojs('main-player')
                videoJs.controls(false)
                // appSettingStore.ott = false
                videoPlayerStore.ottButtons = false

                if (!appSettingStore.fullPage) {
                    appSettingStore.hidePage = true;
                }
                appSettingStore.pipChatMode = true;

                appSettingStore.setPageBgColor(appSettingStore.pipBgColor);
                appSettingStore.setChatMessageBgColor(appSettingStore.pipChatMessageBgColor);

                videoPlayerStore.class = 'pipVideoClassTopRight'
                videoPlayerStore.videoContainerClass = 'pipVideoContainerTopRight'

                // if(appSettingStore.fullPage) {
                //     videoPlayerStore.class = 'pipVideoClassFullPage'
                //     videoPlayerStore.videoContainerClass = 'pipVideoContainerFullPage'
                // } else {
                //     videoPlayerStore.class = 'pipVideoClassTopRight'
                //     videoPlayerStore.videoContainerClass = 'pipVideoContainerTopRight'
                // }

            }
        },
        turnPipChatModeOff() {
            const appSettingStore = useAppSettingStore();
            const videoPlayerStore = useVideoPlayerStore();

            appSettingStore.pipChatMode = false;
            appSettingStore.hidePage = false;
            appSettingStore.setPageBgColor(appSettingStore.primaryBgColor);
            appSettingStore.setChatMessageBgColor(appSettingStore.primaryChatMessageBgColor);

            if (appSettingStore.fullPage) {
                videoPlayerStore.makeVideoFullPage();
            } else {
                videoPlayerStore.makeVideoTopRight();
            }
        },

        setErrorMessage(message) {
            this.errorMessage = message;
            // Clear any existing timeout to avoid overlaps
            if (this.errorTimeout) {
                clearTimeout(this.errorTimeout);
                this.errorTimeout = null;
            }
            // Set a new timeout to clear the message
            if (message !== '') {
                this.errorTimeout = setTimeout(() => {
                    this.errorMessage = '';
                    this.errorTimeout = null;  // Clear the reference once used
                }, 3000); // Clears the error message after 3 seconds
            }
        },

        handleCooldown() {
            this.cooldown = true

            // Set up the cooldown interval
            this.cooldownIntervalId = setInterval(() => {
                this.countdownSeconds -= 1
                this.errorMessage = `Hold your horses! Wait for ${this.countdownSeconds} more seconds before your next message. 🕒`
                if (this.countdownSeconds <= 0) {
                    clearInterval(this.cooldownIntervalId) // Clear the interval
                    this.cooldown = false
                }
            }, 1000)
        },
        clearCooldownInterval() {
            clearInterval(this.cooldownIntervalId) // Clear the interval
        },
        ensureReactionsArray(message) {
            if (!message.reactions) {
                message.reactions = [];
            }
        },
        async toggleReaction(reactionType, messageId) {
            const userStore = useUserStore();
            try {
                // Find the message in either oldMessages or newMessages
                const message = this.findMessageById(messageId);
                if (!message) return;

                // Ensure reactions is always an array
                this.ensureReactionsArray(message);

                const userId = userStore.user.id;

                // Find any existing reaction from the user
                const existingReaction = message.reactions.find(r => r.user_id === userId);

                let action; // To keep track of whether we're adding or removing the reaction

                if (existingReaction) {
                    if (existingReaction.reaction_type === reactionType) {
                        // If the user has already reacted with this type, remove the reaction
                        await axios.post('/chat/toggle-reaction', {
                            message_id: messageId,
                            reaction_type: reactionType,
                        });
                        this.removeReaction(messageId, reactionType, userId);
                        action = 'removed';
                    } else {
                        // If the user has reacted with the opposite type, change to the new type
                        await axios.post('/chat/toggle-reaction', {
                            message_id: messageId,
                            reaction_type: reactionType,
                        });
                        // Remove the old reaction
                        this.removeReaction(messageId, existingReaction.reaction_type, userId);
                        // Add the new reaction
                        this.addReaction(messageId, reactionType, userId);
                        action = 'switched';
                    }
                } else {
                    // If the user hasn't reacted with any type, add the new reaction
                    await axios.post('/chat/toggle-reaction', {
                        message_id: messageId,
                        reaction_type: reactionType,
                    });
                    this.addReaction(messageId, reactionType, userId);
                    action = 'added';
                }

                // console.log(`Reaction ${action}:`, reactionType);

            } catch (error) {
                if (error.response) {
                    console.error('Error response:', error.response.data);
                } else if (error.request) {
                    console.error('No response received:', error.request);
                } else {
                    console.error('Error setting up request:', error.message);
                }
            }
        },
        updateReactions(messageId, channelId, reactionType, userId) {
            const message = this.findMessageById(messageId);
            if (!message) return;

            this.ensureReactionsArray(message);

            // Check if the user has already reacted
            const existingReaction = message.reactions.find(r => r.user_id === userId);

            if (existingReaction) {
                if (existingReaction.reaction_type === reactionType) {
                    // User removed their reaction (should not happen via broadcast, but just in case)
                    message.reactions = message.reactions.filter(r => r.user_id !== userId);
                } else {
                    // User switched their reaction type
                    existingReaction.reaction_type = reactionType;
                }
            } else {
                // Add the new reaction
                message.reactions.push({
                    user_id: userId,
                    reaction_type: reactionType,
                });
            }
        },
        addReaction(messageId, reactionType, userId) {
            const message = this.findMessageById(messageId);
            this.ensureReactionsArray(message);

            if (message) {
                message.reactions.push({
                    user_id: userId,
                    reaction_type: reactionType,
                });
                this.incrementReactionCount(message, reactionType);
            }
        },
        removeReaction(messageId, reactionType, userId) {
            const message = this.findMessageById(messageId);
            this.ensureReactionsArray(message);

            if (message) {
                message.reactions = message.reactions.filter(r => !(r.user_id === userId && r.reaction_type === reactionType));
                this.decrementReactionCount(message, reactionType);
            }
        },
        //
        // updateReactions(messageId, reactionType, userId) {
        //     const message = this.findMessageById(messageId);
        //
        //     if (message) {
        //         const existingReaction = message.reactions.find(r => r.user_id === userId);
        //         if (existingReaction) {
        //             if (existingReaction.reaction_type === reactionType) {
        //                 // User unreacted, remove the reaction
        //                 message.reactions = message.reactions.filter(r => r.user_id !== userId);
        //                 this.decrementReactionCount(message, reactionType);
        //             } else {
        //                 // User changed reaction, update the type
        //                 this.decrementReactionCount(message, existingReaction.reaction_type);
        //                 existingReaction.reaction_type = reactionType;
        //                 this.incrementReactionCount(message, reactionType);
        //             }
        //         } else {
        //             // New reaction
        //             message.reactions.push({
        //                 user_id: userId,
        //                 reaction_type: reactionType,
        //             });
        //             this.incrementReactionCount(message, reactionType);
        //         }
        //     }
        // },
        incrementReactionCount(message, reactionType) {
            this.ensureReactionsArray(message);

            if (reactionType === 'heart') {
                message.heartCount++;
            } else if (reactionType === 'thumbs_up') {
                message.thumbsUpCount++;
            }
        },
        decrementReactionCount(message, reactionType) {
            this.ensureReactionsArray(message);

            if (reactionType === 'heart') {
                message.heartCount--;
            } else if (reactionType === 'thumbs_up') {
                message.thumbsUpCount--;
            }
        },
        findMessageById(messageId) {
            return (
                this.oldMessages.find(m => m.id === messageId) ||
                this.newMessages.find(m => m.id === messageId)
            );
        },
        // turnPipChatModeOffToFullPage() {
        //     // turn chat mode off (FullPage)
        //     const videoPlayerStore = useVideoPlayerStore();
        //
        //     if (userStore.isMobile) {
        //         videoPlayerStore.makeVideoFullPage();
        //         this.turnPipChatModeOff();
        //     }
        // },
        // turnPipChatModeOffToTopRight() {
        //     // turn chat mode off (TopRight)
        //     const videoPlayerStore = useVideoPlayerStore();
        //
        //     if (userStore.isMobile) {
        //         videoPlayerStore.makeVideoTopRight();
        //         this.turnPipChatModeOff();
        //     }
        // },




    },

    getters: {
        heartCount: (state) => (messageId) => {
            const message = state.oldMessages.find(m => m.id === messageId) ||
                state.newMessages.find(m => m.id === messageId);
            return message ? message.reactions.filter(r => r.reaction_type === 'heart').length : 0;
        },
        thumbsUpCount: (state) => (messageId) => {
            const message = state.oldMessages.find(m => m.id === messageId) ||
                state.newMessages.find(m => m.id === messageId);
            return message ? message.reactions.filter(r => r.reaction_type === 'thumbs_up').length : 0;
        },
    //     getNewMessages() {
    //         this.echo = new Echo({
    //             broadcaster: 'pusher',
    //             key: process.env.MIX_PUSHER_APP_KEY,
    //             cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    //             encrypted: true,
    //             forceTLS: true
    //         });
    //         const videoPlayer = useVideoPlayerStore();
    //     this.echo.private('chat.1')
    //         .listen('.message.new', e => {
    //             console.log('PINIA NEW MESSAGE.');
    //             console.log(e.chatMessage);
    //             // this.messages.value = e.chatMessage;
    //             axios.get('/chat/channel/' + videoPlayer.currentChannel.id + '/messages')
    //                 .then( response => {
    //                     this.messages = response.data;
    //                     // chatStore.messages = response.data;
    //                 })
    //                 .catch(error => {
    //                     console.log(error);
    //                 })
    //         });
    //     }
    }

})
