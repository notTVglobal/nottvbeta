import { defineStore } from 'pinia'

const initialState = () => ({
    socialSharingModal: false, // show or hide Modal
    title: 'notTV',
    url: 'https://not.tv',
    description: 'Share this!',
    quote: 'Community Television Re-invented',
    hashtags: 'notTV, mediaForABetterWorld',
    twitterUser: 'notTV',
    media: 'https://not.tv/storage/images/Ping.png',
    image: {
        placeholder_url: 'https://not.tv/storage/images/logo_white_512.png'
    }
})

export const useSocialShareStore = defineStore('socialShareStore', {
    state: initialState,
    actions: {
        reset() {
        // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        showSocialSharing(payload) {
            this.socialSharingModal = true
            if (payload) {
                this.title = payload.title || this.title
                this.url = payload.url || this.url
                this.description = payload.description || this.description
                this.quote = payload.quote || this.quote
                this.hashtags = payload.hashtags || this.hashtags
                this.twitterUser = payload.twitterUser || this.twitterUser
                this.media = payload.media || this.media
                this.image = payload.image || this.image
            }
        },
        parseModel(model) {
            console.log('Parsing model:', model)
            let payload = {}
            if (model.title) {
                payload.title = model.title
                payload.description = model.description || 'Share this!'
                payload.url = model.url || window.location.href

                // Debugging image processing
                console.log('Original image data:', model.image)

                if (model.image) {
                    if (model.image.url) {
                        payload.image = { url: model.image.url }
                        payload.media = model.image.url
                    } else if (model.image.path) {
                        payload.image = { url: `https://not.tv/storage/images/${model.image.path}` }
                        payload.media = `https://not.tv/storage/images/${model.image.path}`
                    } else {
                        payload.image = { placeholder_url: 'https://not.tv/storage/images/logo_white_512.png' }
                        payload.media = 'https://not.tv/storage/images/logo_white_512.png'
                    }
                } else {
                    payload.image = { placeholder_url: 'https://not.tv/storage/images/logo_white_512.png' }
                    payload.media = 'https://not.tv/storage/images/logo_white_512.png'
                }

                console.log('Processed image data:', payload.image)
                console.log('Title:', payload.title)
                console.log('Description:', payload.description)
                console.log('URL:', payload.url)
                console.log('Media:', payload.media)
                console.log('Image:', payload.image)
                console.log('Hashtags:', payload.hashtags)
                console.log('Twitter User:', payload.twitterUser)

                payload.hashtags = 'notTV, mediaForABetterWorld'
                payload.twitterUser = 'notTV'
            }
            console.log('Payload for sharing:', payload)
            this.showSocialSharing(payload)
        },
        hideSocialSharing() {
            this.socialSharingModal = false
        }
    },
    getters: {
        //
    }
})