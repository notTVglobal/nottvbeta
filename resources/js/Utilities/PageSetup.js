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

    if (userStore.isMobile || window.innerWidth < 1024 || appSettingStore.fullPage) {
        appSettingStore.ott = 0;
    } else {
        appSettingStore.ott = 1;
        appSettingStore.showOttButtons = true;
    }

    videoPlayerStore.makeVideoTopRight()

    if (appSettingStore.pageReload) {
        appSettingStore.pageReload = false
        appSettingStore.pageWasReloaded = true
        window.location.reload();
    }
    // Check if the URL contains query strings


    // Only scroll into view if there are no query strings

        // const topDiv = document.getElementById("topDiv")
        // if (topDiv) {
        //     topDiv.scrollIntoView()
        // }
        Inertia.on('navigate', (event) => {
            if (userStore.isMobile || window.innerWidth < 1024 || appSettingStore.fullPage) {
                appSettingStore.ott = 0;
            } else {
                appSettingStore.ott = 1;
            }
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
