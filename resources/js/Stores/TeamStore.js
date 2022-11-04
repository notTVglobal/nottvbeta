import {defineStore} from "pinia";
import {Inertia} from "@inertiajs/inertia";

export let useTeamStore = defineStore('teamStore', {
    state: () => ({
        id: 0,
        name: '',
        description: '',
        slug: '',
        totalSpots: '',
        memberSpots: '',
        members: [],
        activeShow: [],
        activeEpisode: [],
        creators: Object,
        showModal: Boolean,
        confirmDialog: false,
        deleteMemberName: '',
    }),

    actions: {
        // async fill() {
        //     let r = await import('@/Json/team.json');
        //     this.$state = r.default;
        // },
        setActiveTeam(team) {
            this.id = team.id;
            this.name = team.name;
            this.description = team.description;
            this.slug = team.slug;
            this.totalSpots = team.totalSpots;
            this.memberSpots = team.memberSpots;
        },
        setActiveShow(show) {
            this.activeShow = show;
        },
        setActiveEpisode(episode) {
            this.activeShow = episode;
        },
        getCreators() {
            Inertia.reload({ only: ['creators'] })
        },
        deleteTeamMemberCancel() {
            this.confirmDialog = false;
        },
    },

    getters: {
        spotsRemaining() {
            if (this.totalSpots - this.memberSpots < 1){
                return 0
            }
            return this.totalSpots - this.memberSpots;
        },

    }
});


// Another option for declaring specific data to return:
// let data = r.default;
//
// import('@/Json/team.json').then(r => {
// this.$patch({
//     name: data.name,
//     spots: data.spots,
//     members: data.members
// });
// });
