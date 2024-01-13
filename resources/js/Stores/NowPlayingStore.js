import { defineStore } from 'pinia';

const initialState = () => ({
    type: {
        1: 'firstPlay',
        2: 'channel',
        3: 'playlist',
        4: 'videoFile',
        5: 'externalVideo',
        6: 'show',
        7: 'movie',
        8: 'mistStream',
    },
    activeType: 0,
    displayCurrentViewerCount: false,
    isLive: false,
    isFromWeb: false,
    channel: {
        id: 0,
        name: '',
    },
    show: {
        name: '',
        url: '',
        description: '',
        episode: {
            name: '',
            url: '',
            description: '',
            // Add Episode Date here.
            image: {
                id: '',
                name: '',
                folder: '',
                cdn_endpoint: '',
                cloud_folder: '',
            },
            // Add Bonus Content here
            // Add Creators or Credits here
        },
        category: '',
        categorySub: '',
        image: {
            id: '',
            name: '',
            folder: '',
            cdn_endpoint: '',
            cloud_folder: '',
        },
        // Add Team Name here
        // Add Team Url here
    },
    movie: {
        name: '',
        logLine: '',
        year: '',
        description: '',
        url: '',
        image: {
            id: '',
            name: '',
            folder: '',
            cdn_endpoint: '',
            cloud_folder: '',
        },
    },
    newsPost: {
        name: '',
        byLine: '',
        dateTime: '',
        description: '',
        url: '',
        image: {
            id: '',
            name: '',
            folder: '',
            cdn_endpoint: '',
            cloud_folder: '',
        },
    },
    videoFile: {
        name: '',
    }
    // ... other properties
});

export const useNowPlayingStore = defineStore('nowPlayingStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState());
        },
        setActiveType(num) {
            if (this.type[num]) {
                this.activeType = { [num]: this.type[num] };
            } else {
                console.error('Type not found for the given number');
                // Handle the case where the num does not exist in your type object
            }
        }
    }
});
