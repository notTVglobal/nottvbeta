import { defineStore } from 'pinia'
import dayjs from 'dayjs'
import utc from 'dayjs-plugin-utc'
import timezone from 'dayjs/plugin/timezone'
import { useUserStore } from '@/Stores/UserStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import { filterAndSortBroadcasts } from '@/Utilities/BroadcastUtils'

dayjs.extend(utc)
dayjs.extend(timezone)

const initialState = () => ({
    team: {},
    shows: {},
    contributors: {},
    members: {
        pages: {}, // Cache for member pages
        current_page: 1,
        last_page: 1,
        per_page: 5,
        total: 0,
        data: [], // Current page data
    },
    managers: [],
    creators: [],
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
            // const userStore = useUserStore()

            // // Ensure nextBroadcast is an array and has at least one element
            // if (Array.isArray(team.nextBroadcast) && team.nextBroadcast.length > 0) {
            //     const firstBroadcast = team.nextBroadcast[0]
            //
            //     if (firstBroadcast.broadcastDetails) {
            //
            //
            //         // Ensure broadcastDetails is an array and has the zoomLink object
            //         // this.nextBroadcastLoaded.broadcastDetails = []
            //         // if (!Array.isArray(this.nextBroadcastLoaded.broadcastDetails)) {
            //         //     this.nextBroadcastLoaded.broadcastDetails = []
            //         // }
            //
            //         // let zoomLinkObj = this.nextBroadcastLoaded.broadcastDetails.find(detail => detail.zoomLink !== undefined)
            //         // if (!zoomLinkObj) {
            //         //     zoomLinkObj = {zoomLink: ''}
            //         //     this.nextBroadcastLoaded.broadcastDetails.push(zoomLinkObj)
            //         // }
            //
            //         team.nextBroadcast = team.nextBroadcast.map(broadcast => ({
            //             ...broadcast,
            //             broadcastDate: userStore.convertUtcToUserTimezone(broadcast.broadcastDate),
            //         }))
            //     }
            // } else {
            //     // Handle the case where nextBroadcast is not an array or is empty
            //     this.nextBroadcastLoaded = null
            //     this.nextBroadcastZoomLink = null
            // }
            this.members = team.members ? team.members.data : [] // Directly set members
            this.managers = team.managers || []
            this.team = team || {}
            // this.setActiveTeam(team)
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
            this.team.totalSpots = team.totalSpots
        },
        setActiveShow(show) {
            this.activeShow = show
        },
        setActiveEpisode(episode) {
            this.activeShow = episode
        },
        async fetchPaginatedTeamMembers(page = 1) {
            // Ensure team.members is defined
            if (!this.team.members) {
                this.team.members = {
                    current_page: 1,
                    last_page: 1,
                    per_page: 5,
                    total: 0,
                    data: [],
                    links: {},
                };
            }

            try {
                // Fetch the paginated members data
                const response = await axios.get(`/teams/${this.team.slug}/fetch-paginated-team-members?members=${page}`);

                // Extract meta data and the list of members
                const meta = response.data.meta;
                const data = response.data.data;
                const links = response.data.links;

                // If the requested page exceeds the total pages, adjust to the last valid page
                if (page > meta.last_page) {
                    // Fetch the last valid page
                    return await this.fetchPaginatedTeamMembers(meta.last_page);
                }

                // Update the team.members state with the paginated response
                this.team.members.current_page = meta.current_page;
                this.team.members.last_page = meta.last_page;
                this.team.members.per_page = meta.per_page;
                this.team.members.total = meta.total;
                this.team.members.data = data; // Store the current page's data
                this.team.members.links = links; // Store the pagination links

            } catch (error) {
                console.error('Failed to fetch team members:', error);
            }
        },
        async addMember(member) {
            // this.team.members.data.push(member)
            this.team.memberCount++
            // Refresh the current page of members
            await this.fetchPaginatedTeamMembers(this.team.members.current_page) // Use the current page from the prop

        },
        async removeMember(memberId) {
            // this.members = this.members.filter(member => member.id !== memberId)
            // this.team.members.data = this.team.members.data.filter(member => member.id !== memberId)
            // this.team.members.data = this.members
            this.team.memberCount--
            // Refresh the current page of members
            await this.fetchPaginatedTeamMembers(this.team.members.current_page) // Use the current page from the prop
            // Ensure the members array is updated by reassigning it
            this.team.members = {
                ...this.team.members,
                data: [...this.team.members.data] // Force Vue to recognize the change
            };

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
                    this.team.managers.push(response.data.manager)
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
        setCreators(creators) {
            this.creators = creators
        },
        updatePublicMessage(html) {
            this.team.public_message = html
        },
    },

    getters: {
        spotsRemaining(state) {
            if (!state.team.memberCount) {
                return state.team.totalSpots // Assume no members if state.members is not defined
            } else if (state.team.memberCount) {
                this.totalSpots = state.team.totalSpots
                return Math.max(state.team.totalSpots - state.team.memberCount, 0)
            }
        },
        membersCount(state) {
            if (!state.team.memberCount) {
                return 0 // Assume no members if state.members is not defined
            } else if (state.team.memberCount) {
                state.memberSpots = state.team.memberCount
                return state.team.memberCount
            }
        },
        membersCountDisplay(state) {
            if (state.team.memberCount) {
                return state.team.memberCount > 99 ? '99+' : state.team.memberCount
            }
        },
        // nextBroadcast(state) {
        //     const {team} = state
        //     if (!team.nextBroadcast || team.nextBroadcast.length === 0) {
        //         return null
        //     }
        //
        //     const userStore = useUserStore()
        //     const today = dayjs().tz(userStore.timezone)
        //
        //     return team.nextBroadcast.reduce((closest, broadcast) => {
        //         // Check if broadcastDate is valid
        //         const broadcastDateString = broadcast.broadcastDate
        //         const isValidDate = broadcastDateString && !isNaN(Date.parse(broadcastDateString)) && broadcastDateString !== 'No broadcast date'
        //
        //         if (!isValidDate) {
        //             return closest
        //         }
        //
        //         const broadcastDate = dayjs(broadcastDateString).tz(userStore.timezone)
        //         if (!closest || Math.abs(broadcastDate - today) < Math.abs(dayjs(closest.broadcastDate).tz(userStore.timezone) - today)) {
        //             return broadcast
        //         }
        //         return closest
        //     }, null)
        // },
        nextBroadcast(state) {
            // Leverage the sortedBroadcasts array and return the first item
            const sorted = this.sortedBroadcasts
            return sorted.length > 0 ? sorted[0] : null
        },
        // nextBroadcastIsOver: (state) => {
        //     const userStore = useUserStore()
        //     const nowInUserTimezone = dayjs().utc().tz(userStore.timezone)
        //
        //     const nextBroadcast = state.nextBroadcastLoaded
        //
        //     if (!nextBroadcast || !nextBroadcast.broadcastDate || !nextBroadcast.broadcastDetails?.duration_minutes) {
        //         return false // Handle cases where the necessary data is missing
        //     }
        //
        //     const broadcastEndTime = dayjs(nextBroadcast.broadcastDate)
        //         .add(nextBroadcast.broadcastDetails.duration_minutes, 'minute')
        //         .utc()
        //         .tz(userStore.timezone)
        //
        //     return nowInUserTimezone.isAfter(broadcastEndTime)
        // },
        nextBroadcastIsOver(state) {
            const userStore = useUserStore()
            const nowInUserTimezone = dayjs().tz(userStore.timezone)

            const nextBroadcast = this.nextBroadcast

            if (!nextBroadcast || !nextBroadcast.broadcastDate || !nextBroadcast.broadcastDetails?.duration_minutes) {
                return false // Handle cases where the necessary data is missing
            }

            const broadcastEndTime = dayjs(nextBroadcast.broadcastDate)
                .add(nextBroadcast.broadcastDetails.duration_minutes, 'minute')
                .tz(userStore.timezone)

            return nowInUserTimezone.isAfter(broadcastEndTime)
        },
        setTeamNextBroadcast(state, broadcasts) {
            const userStore = useUserStore()

            state.team.nextBroadcast = broadcasts.map(broadcast => ({
                ...broadcast,
                broadcastDate: userStore.convertUtcToUserTimezone(broadcast.broadcastDate),
            }))
        },
        sortedBroadcasts(state) {
            if (!state.team.nextBroadcast || state.team.nextBroadcast.length === 0) {
                return []
            }

            const userStore = useUserStore()
            const sortedBroadcasts = filterAndSortBroadcasts(state.team.nextBroadcast, state.nextBroadcastLoaded, userStore.timezone)

            // Set the first broadcast as the nextBroadcastLoaded
            if (sortedBroadcasts.length > 0) {
                const firstBroadcast = sortedBroadcasts[0]
                state.nextBroadcastLoaded = firstBroadcast

                // Check if the zoomLink is available and set it
                if (firstBroadcast.broadcastDetails && firstBroadcast.broadcastDetails.zoomLink) {
                    state.nextBroadcastZoomLink = firstBroadcast.broadcastDetails.zoomLink
                } else {
                    state.nextBroadcastZoomLink = null  // Reset if no zoomLink is found
                }
            } else {
                state.nextBroadcastLoaded = null
                state.nextBroadcastZoomLink = null  // Reset if no broadcasts are found
            }

            return sortedBroadcasts
        },
        futureBroadcasts(state) {
            if (!state.team.nextBroadcast || state.team.nextBroadcast.length === 0) {
                return []
            }

            const userStore = useUserStore()
            const sortedAndFilteredBroadcasts = filterAndSortBroadcasts(state.team.nextBroadcast, state.nextBroadcastLoaded, userStore.timezone)

            // Exclude the first item in the sorted array
            return sortedAndFilteredBroadcasts.slice(1)
        },
        // sortedBroadcasts(state) {
        //     if (!state.team.nextBroadcast || state.team.nextBroadcast.length === 0) {
        //         return []
        //     }
        //
        //     const userStore = useUserStore()
        //     const nowInUserTimezone = dayjs().tz(userStore.timezone)
        //
        //     return state.team.nextBroadcast
        //         .filter(broadcast => {
        //             // Calculate broadcastEndTime for the current broadcast
        //             const broadcastEndTime = dayjs(broadcast.broadcastDate)
        //                 .add(broadcast.broadcastDetails.duration_minutes, 'minute')
        //                 .utc()
        //                 .tz(userStore.timezone)
        //
        //             // Filter out broadcasts that have already ended
        //             const isAfterNow = nowInUserTimezone.isBefore(broadcastEndTime)
        //
        //             // Exclude if it matches state.nextBroadcastLoaded.broadcastDate && broadcastEndTime
        //             const isNotLoadedBroadcast = !(
        //                 state.nextBroadcastLoaded &&
        //                 state.nextBroadcastLoaded.broadcastDate === broadcast.broadcastDate &&
        //                 dayjs(state.nextBroadcastLoaded.broadcastDate)
        //                     .add(state.nextBroadcastLoaded.broadcastDetails?.duration_minutes || 0, 'minute')
        //                     .utc()
        //                     .tz(userStore.timezone)
        //                     .isSame(broadcastEndTime)
        //             )
        //
        //             return isAfterNow && isNotLoadedBroadcast
        //         })
        //         .sort((a, b) => dayjs(a.broadcastDate).tz(userStore.timezone).diff(dayjs(b.broadcastDate).tz(userStore.timezone)))
        //         .map(broadcast => ({
        //             ...broadcast,
        //             localDate: dayjs(broadcast.broadcastDate).tz(userStore.timezone).format(),
        //         }))
        // },
        // futureBroadcasts(state) {
        //     const nextBroadcast = this.nextBroadcast
        //     if (!state.team.nextBroadcast || state.team.nextBroadcast.length === 0) {
        //         return []
        //     }
        //
        //     const userStore = useUserStore()
        //     const nowInUserTimezone = dayjs().tz(userStore.timezone)
        //
        //     // Apply similar logic to sortedBroadcasts
        //     const sortedAndFilteredBroadcasts = state.team.nextBroadcast
        //         .filter(broadcast => {
        //             // Calculate broadcastEndTime for the current broadcast
        //             const broadcastEndTime = dayjs(broadcast.broadcastDate)
        //                 .add(broadcast.broadcastDetails.duration_minutes, 'minute')
        //                 .utc()
        //                 .tz(userStore.timezone)
        //
        //             // Filter out broadcasts that have already ended
        //             const isAfterNow = nowInUserTimezone.isBefore(broadcastEndTime)
        //
        //             // Exclude if it matches state.nextBroadcastLoaded.broadcastDate && broadcastEndTime
        //             const isNotLoadedBroadcast = !(
        //                 state.nextBroadcastLoaded &&
        //                 state.nextBroadcastLoaded.broadcastDate === broadcast.broadcastDate &&
        //                 dayjs(state.nextBroadcastLoaded.broadcastDate)
        //                     .add(state.nextBroadcastLoaded.broadcastDetails?.duration_minutes || 0, 'minute')
        //                     .utc()
        //                     .tz(userStore.timezone)
        //                     .isSame(broadcastEndTime)
        //             )
        //
        //             return isAfterNow && isNotLoadedBroadcast
        //         })
        //         .sort((a, b) => dayjs(a.broadcastDate).tz(userStore.timezone).diff(dayjs(b.broadcastDate).tz(userStore.timezone)))
        //         .map(broadcast => ({
        //             ...broadcast,
        //             localDate: dayjs(broadcast.broadcastDate).tz(userStore.timezone).format(),
        //         }))
        //
        //     // Exclude the first item in the sorted array
        //     return sortedAndFilteredBroadcasts.slice(1)
        // },
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
