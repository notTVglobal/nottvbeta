import { defineStore } from "pinia";

const initialState = () => ({
    id: 0,
    name: '',
    url: '',
    episodeName: '',
    episodeUrl: '',
    category_id: 0,
    category_description: '',
    categories: [],
    sub_category_id: 0,
    sub_category_description: '',
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
})

export const useShowStore = defineStore('showStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState());
        },
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
        },
        // },
        initializeDescriptions(categoryId, subCategoryId) {
            this.category_id = categoryId;
            const category = this.categories.find(cat => cat.id === categoryId);
            this.category_description = category ? category.description : '';
            this.sub_categories = category ? category.sub_categories : [];

            this.updateSubCategoryDescription(subCategoryId);
        },
        updateSubCategoryDescription(subCategoryId) {
            // Ensure sub_categories is not undefined
            if (!this.sub_categories) {
                this.sub_category_id = '';
                this.sub_category_description = '';
                return;
            }

            const subCategory = this.sub_categories.find(sub => sub.id === subCategoryId);
            this.sub_category_id = subCategory ? subCategory.id : '';
            this.sub_category_description = subCategory ? subCategory.description : '';
        },

        getters: {
            //
        }
    }
});
