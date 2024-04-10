import { defineStore } from 'pinia'

const initialState = () => ({
    districtType: 'federal', // Default to Federal
    districtName: '',
    federalDistricts: [],
    subnationalDistricts: [],
    provinces: [],
    selectedProvinceId: null, // ID of the selected province
})

export const useNewsDistrictStore = defineStore('newsDistrictStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        setDistrictType(type) {
            this.districtType = type;
        },
        clearSelectedProvinceId() {
            this.selectedProvinceId = null
        },
        setSelectedProvinceId(abbreviation) {
            const province = this.provinces.find(p => p.abbreviation === abbreviation);
            if (province) {
                this.selectedProvinceId = province.id; // Assuming each province object has an 'id' field
            }
        }


    },

    getters: {
        // Filtered federal districts based on the selected province
        filteredFederalDistricts: (state) => {
            return state.federalDistricts.filter(district => district.province_id === state.selectedProvinceId);
        },
        selectedProvince: (state) => {
            return state.provinces.find(p => p.id === state.selectedProvinceId);
        }
    },

})

