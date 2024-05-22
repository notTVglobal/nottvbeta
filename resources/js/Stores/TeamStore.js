import { defineStore } from 'pinia'
import { router } from '@inertiajs/vue3'
import { useNotificationStore } from '@/Stores/NotificationStore'

const initialState = () => ({
    id: 0,
    name: '',
    description: '',
    slug: '',
    totalSpots: '',
    memberSpots: '',
    teamCreator: [],
    teamLeader: [],
    members: [],
    managers: [],
    activeShow: [],
    activeEpisode: [],
    creators: [],
    showModal: Boolean,
    confirmDialog: false,
    confirmManagerDialog: false,
    selectedManagerName: '',
    selectedManagerId: 0,
    addManager: false,
    removeManager: false,
    deleteMemberName: '',
    deleteMemberId: 0,
    noteEdit: 0,
    note: '',
    saveNoteProcessing: Boolean,
    // on the show episode manage page
    // turn on the go live div
    goLiveDisplay: false,
    can: [],
    openComponent: 'teamShows',
})

export const useTeamStore = defineStore('teamStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        // async fill() {
        //     let r = await import('@/Json/team.json');
        //     this.$state = r.default;
        // },
        setActiveTeam(team) {
            this.id = team.id
            this.name = team.name
            this.description = team.description
            this.slug = team.slug
            this.members = team.members
            this.managers = team.managers
            this.totalSpots = team.totalSpots
            this.memberSpots = team.memberSpots
        },
        setActiveShow(show) {
            this.activeShow = show
        },
        setActiveEpisode(episode) {
            this.activeShow = episode
        },
        addMember(member) {
            this.members.push(member)
        },
        removeMember(memberId) {
            this.members = this.members.filter(member => member.id !== memberId);
        },
        updateCreatorTeams(creatorId, teamId, remove = false) {
            const creator = this.creators.find(c => c.id === creatorId);
            if (creator) {
                if (remove) {
                    creator.teams = creator.teams.filter(team => team.id !== teamId);
                } else {
                    creator.teams.push({ id: teamId, is_manager: false }); // Add the new team to the creator's teams
                }
            }
        },
        // getCreators() {
        //     router.reload({ only: ['creators'] })
        // },
        deleteTeamMemberCancel() {
            this.confirmDialog = false
        },
        confirmTeamManagerCancel() {
            this.confirmManagerDialog = false
        },
        // loadTeamMembers(members){
        //     this.members = members;
        // }
        // deleteTeamMember() {
        //     router.visit(route('teams.removeTeamMember'), {
        //         method: 'post',
        //         data: {
        //             user_id: this.deleteMemberId,
        //             team_id: this.id,
        //             team_slug: this.slug,
        //         },
        //     })
        // },
        async deleteTeamMember() {
            const notificationStore = useNotificationStore()
            const payload = {
                user_id: this.deleteMemberId,
                team_id: this.id,
            };

            try {
                const response = await axios.post(route('teams.removeTeamMember'), payload);
                if (response.status === 200) {
                    this.removeMember(this.deleteMemberId);
                    this.updateCreatorTeams(this.deleteMemberId, this.id, true); // Remove the team from the creator's teams
                    this.confirmDialog = false;
                    notificationStore.setToastNotification(response.data.message, 'success')
                } else {
                    this.confirmDialog = false;
                    notificationStore.setToastNotification('Failed to remove member from the team.', 'warning')
                }
            } catch (error) {
                console.error(error);
                this.confirmDialog = false;
                notificationStore.setToastNotification('An error occurred while removing the member from the team.', 'error')
            }
        },

        addTeamManager() {
            router.visit(route('teams.addTeamManager'), {
                method: 'post',
                data: {
                    user_id: this.selectedManagerId,
                    team_id: this.id,
                    team_slug: this.slug,
                },
            })
            this.confirmManagerDialog = false
        },
        removeTeamManager() {
            router.visit(route('teams.removeTeamManager'), {
                method: 'post',
                data: {
                    user_id: this.selectedManagerId,
                    team_id: this.id,
                    team_slug: this.slug,
                },
            })
            this.confirmManagerDialog = false
        },
        toggleGoLiveDisplay() {
            this.goLiveDisplay = !this.goLiveDisplay
        },
        async fetchTeamMembers() {
            await axios.get('/team/team-members').then().error()
        },
        setCreators(creators) {
            this.creators = creators
        },
    },

    getters: {
        spotsRemaining(state) {
            if (!state.members) {
                return state.totalSpots; // Assume no members if state.members is not defined
            }
            return Math.max(state.totalSpots - state.members.length, 0);
        },
        membersCount(state) {
            if (!state.members) {
                return 0; // Assume no members if state.members is not defined
            }
            return state.members.length;
        },
    },
})


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
