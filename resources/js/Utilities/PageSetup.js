import { onBeforeMount, onMounted } from 'vue'
import { useUserStore } from "@/Stores/UserStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { Inertia } from '@inertiajs/inertia'

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

    if (appSettingStore.pageReload) {
        appSettingStore.pageReload = false
        window.location.reload(true);
    }
    // Check if the URL contains query strings


    // Only scroll into view if there are no query strings

        // const topDiv = document.getElementById("topDiv")
        // if (topDiv) {
        //     topDiv.scrollIntoView()
        // }
        Inertia.on('navigate', (event) => {
            const hasQueryStrings = window.location.search !== '';
            if (!hasQueryStrings || appSettingStore.shouldScrollToTop) {
                requestAnimationFrame(() => {
                    const topDiv = document.getElementById("topDiv");
                    if (topDiv) {
                        topDiv.scrollIntoView({behavior: 'auto'});
                    } else {
                        window.scrollTo(0, 0);
                    }
                });
            }
            appSettingStore.shouldScrollToTop = false;
        })
    // Only update if we're not already on this page to avoid overwriting with the current URL
    appSettingStore.setPrevUrl()
    appSettingStore.showOttButtons = true
    appSettingStore.noLayout = false

    // Inertia.reload()

}
