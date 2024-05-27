import { defineStore } from 'pinia'

// Initial state setup for the SocialShareStore
const initialState = () => ({
    socialSharingModal: false, // Controls the visibility of the social sharing modal
    title: 'notTV', // Default title for sharing
    url: 'https://not.tv', // Default URL for sharing
    description: 'Share this!', // Default description for sharing
    quote: 'Community Television Re-invented', // Default quote for sharing
    hashtags: 'notTV,mediaForABetterWorld', // Default hashtags for sharing
    twitterUser: 'notTV', // Default Twitter user for sharing
    media: 'https://not.tv/storage/images/logo_white_512.png', // Default media (image) URL for sharing
})

export const useSocialShareStore = defineStore('socialShareStore', {
    state: initialState, // Set the initial state of the store
    actions: {
        // Action to reset the store to its initial state
        reset() {
            Object.assign(this, initialState())
        },
        // Action to show the social sharing modal and set the payload
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
            }
        },
        // Action to parse the model and prepare the payload for sharing
        parseModel(model) {
            // console.log('Parsing model:', model)
            let payload = {}

            if (model.name) {
                // Assign model values to payload or use default store values
                payload.title = model.name
                payload.description = model.description || this.description
                payload.url = model.url || window.location.href
                payload.quote = model.quote || this.quote
                payload.hashtags = model.hashtags || this.hashtags
                payload.twitterUser = model.twitterUser || this.twitterUser
                payload.media = model.image || this.media

                // Debugging media processing
                // console.log('Original media data:', model.image)

                if (model.image) {
                    // Handle media if it is a string
                    if (typeof model.image === 'string') {
                        payload.media = model.image
                    }
                    // Handle media if it is an object
                    else if (typeof model.image === 'object') {
                        if (model.image.url) {
                            payload.media = model.image.url
                        } else if (
                            model.image.cdn_endpoint &&
                            model.image.cloud_folder &&
                            model.image.folder &&
                            model.image.name
                        ) {
                            // Construct media URL from parts if all are present
                            payload.media = `${model.image.cdn_endpoint}${model.image.cloud_folder}${model.image.folder}/${model.image.name}`
                        } else if (model.image.placeholder_url) {
                            payload.media = model.image.placeholder_url
                        } else {
                            // Use default media if any part is missing
                            payload.media = this.media
                        }
                    }
                } else {
                    // Use default media if model.media is not provided
                    payload.media = this.media
                }

                // Debugging the processed media URL and other payload data
                // console.log('Processed media URL:', payload.media)
                // console.log('Title:', payload.title)
                // console.log('Description:', payload.description)
                // console.log('URL:', payload.url)
                // console.log('Media:', payload.media)
                // console.log('Hashtags:', payload.hashtags)
                // console.log('Twitter User:', payload.twitterUser)
            }

            // console.log('Payload for sharing:', payload)
            this.showSocialSharing(payload)
        },
        // Action to hide the social sharing modal
        hideSocialSharing() {
            this.socialSharingModal = false
        },
    },
    getters: {
        // Getters can be added here if needed in the future
    },
})
