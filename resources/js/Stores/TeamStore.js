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
    noteKey: 0,
    saveNoteProcessing: Boolean,
    // on the show episode manage page
    // turn on the go live div
    goLiveDisplay: false,
    openComponent: 'teamShows',
    nextBroadcastLoaded: {
        scheduleIndexId: null,
        broadcastDate: null,
        broadcastDetails: {},
        type: '',
        image: null,
        category: null,
        subCategory: null,
        slug: null,
        name: null,
        description: null,
    },
    nextBroadcastZoomLink: '',
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
            console.log('incoming team: ', team)
            const userStore = useUserStore()

            // Ensure nextBroadcast is an array and has at least one element
            if (Array.isArray(team.nextBroadcast) && team.nextBroadcast.length > 0) {
                const firstBroadcast = team.nextBroadcast[0]

                if (firstBroadcast.broadcastDetails) {
                    this.nextBroadcastLoaded = firstBroadcast

                    if (firstBroadcast.broadcastDetails.zoomLink) {
                        this.nextBroadcastZoomLink = firstBroadcast.broadcastDetails.zoomLink
                    }

                    // Ensure broadcastDetails is an array and has the zoomLink object
                    // this.nextBroadcastLoaded.broadcastDetails = []
                    // if (!Array.isArray(this.nextBroadcastLoaded.broadcastDetails)) {
                    //     this.nextBroadcastLoaded.broadcastDetails = []
                    // }

                    // let zoomLinkObj = this.nextBroadcastLoaded.broadcastDetails.find(detail => detail.zoomLink !== undefined)
                    // if (!zoomLinkObj) {
                    //     zoomLinkObj = {zoomLink: ''}
                    //     this.nextBroadcastLoaded.broadcastDetails.push(zoomLinkObj)
                    // }

                    team.nextBroadcast = team.nextBroadcast.map(broadcast => ({
                        ...broadcast,
                        broadcastDate: userStore.convertUtcToUserTimezone(broadcast.broadcastDate),
                    }))
                }
            } else {
                // Handle the case where nextBroadcast is not an array or is empty
                this.nextBroadcastLoaded = null
                this.nextBroadcastZoomLink = null
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
            this.team.id = team.id
            this.team.name = team.name
            this.team.description = team.description
            this.team.slug = team.slug
            this.team.members = team.members
            this.team.managers = team.managers
            this.team.totalSpots = team.totalSpots
            this.memberSpots = team.memberSpots
        },
        setActiveShow(show) {
            this.activeShow = show
        },
        setActiveEpisode(episode) {
            this.activeShow = episode
        },
        addMember(member) {
            this.team.members.push(member)
        },
        removeMember(memberId) {
            this.team.members = this.team.members.filter(member => member.id !== memberId)
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
                team_id: this.team.id,
            }

            try {
                const response = await axios.post(route('teams.removeTeamMember'), payload)
                if (response.status === 200) {
                    this.removeMember(this.deleteMemberId)
                    this.updateCreatorTeams(this.deleteMemberId, this.team.id, true) // Remove the team from the creator's teams
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
        async addTeamManager() {
            const notificationStore = useNotificationStore()
            const payload = {
                user_id: this.selectedManagerId,
                team_id: this.team.id,
                team_slug: this.team.slug,
            }
            try {
                const response = await axios.post(route('teams.addTeamManager'), payload)
                if (response.status === 200) {
                    // Add any additional logic if needed, e.g., updating team data in the store
                    this.team.managers.push(response.data.manager);
                    this.confirmManagerDialog = false
                    notificationStore.setToastNotification(response.data.message, 'success')
                } else {
                    this.confirmManagerDialog = false
                    notificationStore.setToastNotification('Failed to add manager to the team.', 'warning')
                }
            } catch (error) {
                console.error(error)
                this.confirmManagerDialog = false
                notificationStore.setToastNotification('An error occurred while adding the manager to the team.', 'error')
            }
        },
        async removeTeamManager() {
            const notificationStore = useNotificationStore()
            const payload = {
                user_id: this.selectedManagerId,
                team_id: this.team.id,
                team_slug: this.team.slug,
            }

            try {
                const response = await axios.post(route('teams.removeTeamManager'), payload)
                if (response.status === 200) {
                    // Remove the manager from the team.managers array
                    this.team.managers = this.team.managers.filter(manager => manager.id !== this.selectedManagerId)
                    this.confirmManagerDialog = false
                    notificationStore.setToastNotification(response.data.message, 'success')
                } else {
                    this.confirmManagerDialog = false
                    notificationStore.setToastNotification('Failed to remove manager from the team.', 'warning')
                }
            } catch (error) {
                console.error(error)
                this.confirmManagerDialog = false
                notificationStore.setToastNotification('An error occurred while removing the manager from the team.', 'error')
            }
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
                this.totalSpots = state.team.totalSpots
                return Math.max(state.team.totalSpots - state.team.members.length, 0)
            }
        },
        membersCount(state) {
            if (!state.team.members) {
                return 0 // Assume no members if state.members is not defined
            } else if (state.team.members) {
                this.memberSpots = state.team.members.length
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
