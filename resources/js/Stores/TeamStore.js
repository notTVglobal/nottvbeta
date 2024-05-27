import { defineStore } from 'pinia'
import { router } from '@inertiajs/vue3'
import dayjs from 'dayjs'
import utc from 'dayjs-plugin-utc'
import timezone from 'dayjs/plugin/timezone'
import { useUserStore } from '@/Stores/UserStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import { ref } from 'vue'

dayjs.extend(utc)
dayjs.extend(timezone)

const initialState = () => ({
    team: {},
    shows: {},
    contributors: {},
    members: {},
    managers: {},
    teamOwner: [],
    teamLeader: [],
    can: {},
    id: 0,
    name: '',
    description: '',
    slug: '',
    totalSpots: '',
    memberSpots: '',
    activeShow: [],
    activeEpisode: [],
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
    openComponent: 'teamShows',
    nextBroadcastLoaded: {
        scheduleIndexId: null,
        broadcastDate: null,
        broadcastDetails: [
            {
                zoomLink: ''
            }
        ],
        type: '',
        image: null,
        category: null,
        subCategory: null,
        slug: null,
        name: null,
        description: null,
    }
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
        initializeTeam(team) {
            const userStore = useUserStore()
            if (team.nextBroadcast && team.nextBroadcast.broadcastDetails) {
                this.nextBroadcastLoaded = team.nextBroadcast[0]
                // Ensure broadcastDetails is an array and has the zoomLink object
                this.nextBroadcastLoaded.broadcastDetails = []
                // if (!Array.isArray(this.nextBroadcastLoaded.broadcastDetails)) {
                //     this.nextBroadcastLoaded.broadcastDetails = []
                // }

                let zoomLinkObj = this.nextBroadcastLoaded.broadcastDetails.find(detail => detail.zoomLink !== undefined)
                if (!zoomLinkObj) {
                    zoomLinkObj = { zoomLink: '' }
                    this.nextBroadcastLoaded.broadcastDetails.push(zoomLinkObj)
                }
                team.nextBroadcast = team.nextBroadcast.map(broadcast => ({
                    ...broadcast,
                    broadcastDate: userStore.convertUtcToUserTimezone(broadcast.broadcastDate),

                }))
            }

            this.team = team || {}
        },
        initializeShows(shows) {
            this.shows = shows || {}
        },
        initializeContributors(contributors) {
            this.contributors = contributors || {}
        },
        setCan(can) {
            this.can = can || {}
        },
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
            this.members = this.members.filter(member => member.id !== memberId)
        },
        updateCreatorTeams(creatorId, teamId, remove = false) {
            const creator = this.creators.find(c => c.id === creatorId)
            if (creator) {
                if (remove) {
                    creator.teams = creator.teams.filter(team => team.id !== teamId)
                } else {
                    creator.teams.push({id: teamId, is_manager: false}) // Add the new team to the creator's teams
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
            }

            try {
                const response = await axios.post(route('teams.removeTeamMember'), payload)
                if (response.status === 200) {
                    this.removeMember(this.deleteMemberId)
                    this.updateCreatorTeams(this.deleteMemberId, this.id, true) // Remove the team from the creator's teams
                    this.confirmDialog = false
                    notificationStore.setToastNotification(response.data.message, 'success')
                } else {
                    this.confirmDialog = false
                    notificationStore.setToastNotification('Failed to remove member from the team.', 'warning')
                }
            } catch (error) {
                console.error(error)
                this.confirmDialog = false
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
        updatePublicMessage(html) {
            this.team.public_message = html
        },
    },

    getters: {
        spotsRemaining(state) {
            if (!state.team.members) {
                return state.team.totalSpots // Assume no members if state.members is not defined
            } else if (state.team.members) {
                return Math.max(state.team.totalSpots - state.team.members.length, 0)
            }
        },
        membersCount(state) {
            if (!state.team.members) {
                return 0 // Assume no members if state.members is not defined
            } else if (state.team.members) {
                return state.team.members.length
            }
        },
        membersCountDisplay(state) {
            if (state.team.members) {
                return state.team.members.length > 99 ? '99+' : state.team.members.length
            }
        },
        nextBroadcast(state) {
            const {team} = state
            if (!team.nextBroadcast || team.nextBroadcast.length === 0) {
                return null
            }

            const userStore = useUserStore()
            const today = dayjs().tz(userStore.timezone)

            return team.nextBroadcast.reduce((closest, broadcast) => {
                const broadcastDate = dayjs(broadcast.broadcastDate).tz(userStore.timezone)
                if (!closest || Math.abs(broadcastDate - today) < Math.abs(dayjs(closest.broadcastDate).tz(userStore.timezone) - today)) {
                    return broadcast
                }
                return closest
            }, null)
        },
        sortedBroadcasts(state) {
            if (!state.team.nextBroadcast || state.team.nextBroadcast.length === 0) {
                return []
            }

            const userStore = useUserStore()
            const today = dayjs().tz(userStore.timezone)

            return state.team.nextBroadcast
                .filter(broadcast => dayjs(broadcast.broadcastDate).tz(userStore.timezone).isAfter(today))
                .sort((a, b) => dayjs(a.broadcastDate).tz(userStore.timezone).diff(dayjs(b.broadcastDate).tz(userStore.timezone)))
                .map(broadcast => ({
                    ...broadcast,
                    localDate: dayjs(broadcast.broadcastDate).tz(userStore.timezone).format(),
                }))
        },
        futureBroadcasts(state) {
            const nextBroadcast = this.nextBroadcast
            if (!state.team.nextBroadcast || state.team.nextBroadcast.length === 0) {
                return []
            }

            const userStore = useUserStore()
            const today = dayjs().tz(userStore.timezone)

            return state.team.nextBroadcast
                .filter(broadcast =>
                    dayjs(broadcast.broadcastDate).tz(userStore.timezone).isAfter(today) &&
                    (!nextBroadcast || broadcast.broadcastDate !== nextBroadcast.broadcastDate),
                )
                .sort((a, b) => dayjs(a.broadcastDate).tz(userStore.timezone).diff(dayjs(b.broadcastDate).tz(userStore.timezone)))
                .map(broadcast => ({
                    ...broadcast,
                    localDate: dayjs(broadcast.broadcastDate).tz(userStore.timezone).format(),
                }))
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
