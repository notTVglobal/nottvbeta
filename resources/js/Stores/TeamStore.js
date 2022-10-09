import { defineStore } from "pinia";

export let useTeamStore = defineStore('teamStore', {
   state: () => ({
           id: 0,
           name: '',
           description: '',
           spots: 0,
           members: [],
           activeTeam: '',
           logo: '',
       }),

    actions: {
       async fill() {
           let r = await import('@/Json/team.json');
           this.$state = r.default;
       },
       setActiveTeam(id) {
           this.activeTeam = id;
       }
    },

    getters: {
       spotsRemaining() {
           return this.spots - this.members.length;
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
