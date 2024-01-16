import { onMounted } from 'vue'
import { useUserStore } from "@/Stores/UserStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { Inertia } from "@inertiajs/inertia"

export function usePageSetup(pageName) {
    const userStore = useUserStore()
    const appSettingStore = useAppSettingStore()
    const videoPlayerStore = useVideoPlayerStore()

    userStore.currentPage = pageName
    userStore.showFlashMessage = true
    appSettingStore.pageIsHidden = false

    if (userStore.isMobile) {
        appSettingStore.ott = 0
    }

    videoPlayerStore.makeVideoTopRight()

    onMounted(() => {
        const topDiv = document.getElementById("topDiv")
        if (topDiv) {
            topDiv.scrollIntoView()
        }
        // Only update if we're not already on this page to avoid overwriting with the current URL
        userStore.setPrevUrl()
        Inertia.reload()

    });
}
