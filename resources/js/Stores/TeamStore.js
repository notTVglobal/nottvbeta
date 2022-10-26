import {defineStore} from "pinia";

export let useTeamStore = defineStore('teamStore', {
    state: () => ({
        id: 0,
        name: '',
        description: '',
        totalSpots: 0,
        members: [],
        activeShow: [],
        logoId: [0],
        logoName: [],
    }),

    actions: {
        async fill() {
            let r = await import('@/Json/team.json');
            this.$state = r.default;
        },
        setActiveTeam(team) {
            this.id = team.id;
            this.name = team.name;
            this.description = team.description;
            this.totalSpots = team.totalSpots;
        },
        setActiveShow(show) {
            this.activeShow = show;
        }
    },

    getters: {
        spotsRemaining() {
            return this.totalSpots - this.members.length;
        }
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
