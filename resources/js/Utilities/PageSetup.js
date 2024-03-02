import { onBeforeMount, onMounted } from 'vue'
import { useUserStore } from "@/Stores/UserStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"

export function usePageSetup(pageName) {
    const userStore = useUserStore()
    const appSettingStore = useAppSettingStore()
    const videoPlayerStore = useVideoPlayerStore()

    appSettingStore.currentPage = pageName
    appSettingStore.showFlashMessage = true
    appSettingStore.pageIsHidden = false

    if (userStore.isMobile) {
        appSettingStore.ott = 0
    }

    videoPlayerStore.makeVideoTopRight()

    onBeforeMount(() => {
        // reload page
        if (appSettingStore.pageReload) {
            appSettingStore.pageReload = false
            window.location.reload(true);
        }
    });

    onMounted(() => {
        // Check if the URL contains query strings
        const hasQueryStrings = window.location.search !== '';

        // Only scroll into view if there are no query strings
        if (!hasQueryStrings) {
            const topDiv = document.getElementById("topDiv")
            if (topDiv) {
                topDiv.scrollIntoView()
            }
        }
        // Only update if we're not already on this page to avoid overwriting with the current URL
        appSettingStore.setPrevUrl()
        appSettingStore.noLayout = false
        appSettingStore.showOttButtons = true
        // Inertia.reload()

    });
}
