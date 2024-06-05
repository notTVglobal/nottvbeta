import { defineStore } from 'pinia'
import { useNotificationStore } from '@/Stores/NotificationStore'
import { router } from '@inertiajs/vue3'

const initialState = () => ({
    messages: [],
    messageCount: 0,
    newMessageCount: 0,
    newsPersons: [],
    searchInput: '',
    recipient: null,
    subject: null,
    deleteAllTimestamp: null,
})

export const useNewsPersonMessageStore = defineStore('newsPersonMessageStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        initializeNewsPersonMessageStore(messages) {
            this.messages = messages
        },
        async fetchMessages() {
            try {
                const response = await axios.get('/news-person-messages-fetch')
                this.messages = response.data
            } catch (error) {
                console.error('Error fetching messages:', error)
            }
        },
        async fetchMessageCount() {
            const notificationStore = useNotificationStore()
            try {
                const response = await axios.get('/news-person-messages-count')
                this.messageCount = response.data.count
                this.newMessageCount = response.data.newMessageCount
                if (response.data.newMessageCount >= 1) {
                    notificationStore.setToastNotification('You have ' + response.data.count + ' messages.', 'info')
                }
            } catch (error) {
                console.error('Error fetching message count:', error)
            }
        },
        async fetchNewsPersons() {
            try {
                const response = await axios.get('/api/news/persons');
                this.newsPersons = response.data;
            } catch (error) {
                console.error('Error fetching news persons:', error);
            }
        },
        setDeleteAllTimestamp() {
            const date = new Date();
            this.deleteAllTimestamp = date.toISOString().slice(0, 19).replace('T', ' ');
        },
        async deleteMessage(messageId) {
            const notificationStore = useNotificationStore()
            try {
                await router.post(`/news-person-messages/${messageId}`)
                await this.fetchMessages();
                await this.fetchMessageCount();
                notificationStore.setToastNotification('Message deleted.', 'warning')
            } catch (error) {
                console.error('Error deleting message:', error);
                notificationStore.setToastNotification(error, 'error')
            }
        },
        async deleteAllMessages() {
            const notificationStore = useNotificationStore()
            try {
                await axios.post('/news-person-messages-delete-all', {
                    deleteAllTimestamp: this.deleteAllTimestamp,
                });
                await this.fetchMessages();
                await this.fetchMessageCount();
                this.deleteAllTimestamp = null;
                notificationStore.setToastNotification('All messages deleted.', 'warning')
            } catch (error) {
                console.error('Error deleting all messages:', error);
                this.deleteAllTimestamp = null;
                notificationStore.setToastNotification(error, 'error')
            }
        },
        updateMessage(updatedMessage) {
            this.messages = this.messages.map(message =>
                message.id === updatedMessage.id ? updatedMessage : message
            );
        },
        setSearchInput(value) {
            this.searchInput = value;
        },
        addMessage(newMessage) {
            this.messages.push(newMessage);
        },
        incrementMessageCount() {
            this.messageCount += 1;
        },
        setRecipient(recipient) {
            this.recipient = recipient;
        },
        clearRecipient() {
            this.recipient = null;
        },
        setSubject(subject) {
            this.subject = subject
        },
        clearSubject() {
            this.subject = ''
        }
    },
    getters: {
        computedMessageCount: (state) => {
            return state.messages.length > 99 ? '99+' : state.messages.length;
        },
        computedNewMessageCount: (state) => {
            return state.messages.filter(message => message.read_at === null).length;
        },
        filteredNewsPersons: (state) => {
            if (!state.searchInput) {
                return state.newsPersons;
            }
            return state.newsPersons.filter(person =>
                person.name.toLowerCase().includes(state.searchInput.toLowerCase())
            );
        },
    },
})