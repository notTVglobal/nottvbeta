import { defineStore } from "pinia";

const initialState = () => ({
    category_id: 0,
    category_description: '',
    categories: [],
    sub_category_id: 0,
    sub_category_description: '',
    sub_categories: [],
})

export const useMovieStore = defineStore('movieStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState());
        },
        // initializeDescriptions(categories, categoryId, subCategoryId) {
        //     this.categories = categories
        //     this.category_id = categoryId
        //     this.sub_category_id = subCategoryId
        //     console.log("Initializing Descriptions with Category ID:", this.category_id, "Subcategory ID:", this.sub_category_id);
        //     const category = this.categories.find(cat => cat.id === this.category_id);
        //     console.log("Found Category:", category);
        //     // this.category_id = category ? category.id : '';
        //     this.category_description = category ? category.description : '';
        //     // this.sub_category_id = subCategoryId;
        //     this.sub_categories = category ? category.sub_categories : [];
        //     console.log("Updated sub_category_id to:", this.sub_category_id);
        //
        //     this.updateSubCategoryDescription(subCategoryId);
        // },


        initializeDescriptions(categoryId, subCategoryId) {
            this.category_id = categoryId;
            const category = this.categories.find(cat => cat.id === categoryId);
            this.category_description = category ? category.description : '';
            this.sub_categories = category ? category.sub_categories : [];

            this.updateSubCategoryDescription(subCategoryId);
        },
        updateSubCategoryDescription(subCategoryId) {
            const subCategory = this.sub_categories.find(sub => sub.id === subCategoryId);
            this.sub_category_id = subCategory ? subCategory.id : '';
            this.sub_category_description = subCategory ? subCategory.description : '';
        }







        // initializeDescriptions(categoryId, subCategoryId) {
        //     this.category_id = categoryId
        //     this.sub_category_id = subCategoryId
        //     console.log("Initializing Descriptions with Category ID:", this.category_id, "Subcategory ID:", this.sub_category_id);
        //     const category = this.categories.find(cat => cat.id === this.category_id);
        //     console.log("Found Category:", category);
        //     this.category_id = category ? category.id : '';
        //     this.category_description = category ? category.description : '';
        //         this.sub_category_id = subCategoryId;
        //         this.sub_categories = category ? category.sub_categories : [];
        //         console.log("Updated sub_category_id to:", this.sub_category_id);
        //     // // this.sub_category_id = subCategoryId;
        //     // this.sub_categories = category ? category.sub_categories : [];
        //     // console.log("Updated sub_category_id to:", this.sub_category_id);
        //     //
        //     this.updateSubCategoryDescription(this.sub_category_id);
        // },
        // updateSubCategoryDescription(subCategoryId) {
        //     const subCategory = this.sub_categories.find(sub => sub.id === subCategoryId);
        //     this.sub_category_id = subCategory ? subCategory.id : '';
        //     this.sub_category_description = subCategory ? subCategory.description : '';
        // }
    },

    getters: {
        //
    }
});
