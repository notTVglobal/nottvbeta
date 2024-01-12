import { defineStore } from "pinia";

export let useShowStore = defineStore('showStore', {
    state: () => ({
        id: 0,
        name: '',
        url: '',
        episodeName: '',
        episodeUrl: '',
        category_id: 0,
        category_sub_id: 0,
        category_description: '',
        sub_categories: [],
        description: '',
        posterName: [],
        posterId: [0],
        episodes: [],
        team_id: 'team id',
        episodePoster: '',
        noteEdit: 0,
        note: '',
        saveNoteProcessing: Boolean,
        errorMessage: '',
        episodeIsBeingDeleted: 0, // put episode id here if being deleted (used on the Show Manage page, Show Episode component)

    }),

    actions: {
        async fill() {
            let r = await import('@/Json/show.json');
            this.$state = r.default;
        },
        setName(name) {
            this.name = name;
        },
        setUrl(url) {
            this.url = url;
        },
        setEpisodeName(episodeName) {
            this.episodeName = episodeName;
        },
        setEpisodeUrl(episodeUrl) {
            this.episodeUrl = episodeUrl;
        }
    },

    getters: {
        //
    }
});
