import { usePage } from '@inertiajs/vue3'
import { useNewsPersonMessageStore } from '@/Stores/NewsPersonMessageStore'
import { useNotificationStore } from '@/Stores/NotificationStore'

export default function useEchoListeners() {
    const newsPersonMessageStore = useNewsPersonMessageStore()
    const notificationStore = useNotificationStore()
    const page = usePage().props

    const userId = page?.user?.newsPersonId
    if (userId) {
        const channel = Echo.private(`news-person-messages.${userId}`)
console.log('test')
console.log(userId)
        channel.subscribed(() => {
            console.log('subscribed')
        }).listen('.newsPersonMessage', (event) => {
            newsPersonMessageStore.fetchMessages()
                .then(r => newsPersonMessageStore.incrementMessageCount())

            const message = 'New Message Received. <Link :href="/news-person-messages" class="hover:text-blue-500 hover:cursor-pointer">View Messages</Link>';
            notificationStore.setToastNotification('New Message Received.', 'info', 10000)
            console.log('new message received', event)
        })
    }
}
