import {defineStore} from "pinia";

export let useTeamStore = defineStore('teamStore', {
    state: () => ({
        id: 0,
        name: '',
        description: '',
        slug: '',
        totalSpots: 0,
        members: [],
        activeShow: [],
        activeEpisode: [],
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
            this.slug = team.slug;
            this.totalSpots = team.totalSpots;
        },
        setActiveShow(show) {
            this.activeShow = show;
        },
        setActiveEpisode(episode) {
            this.activeShow = episode;
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
