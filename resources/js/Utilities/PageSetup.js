import { onBeforeMount, onMounted, watch } from 'vue'
import { useUserStore } from "@/Stores/UserStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { router } from '@inertiajs/vue3'

export function usePageSetup(pageName) {
    const userStore = useUserStore()
    const appSettingStore = useAppSettingStore()
    const videoPlayerStore = useVideoPlayerStore()

    appSettingStore.currentPage = pageName
    appSettingStore.showFlashMessage = true
    appSettingStore.pageIsHidden = false

// PageSetup utility
    if (userStore.isMobile || window.innerWidth < 1024) {
        appSettingStore.ott = 0; // Close all panels on mobile devices
    } else if (appSettingStore.fullPage) {
        appSettingStore.ott = 0; // Default to closed in fullPage mode
    } else {
        if (appSettingStore.ott === 0) {
            appSettingStore.ott = 4
        }
        // appSettingStore.ott = 4; // Open OttChat by default on larger screens
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
        router.on('navigate', (event) => {
            // Ensure OTT panels do not open automatically on mobile devices
            if (userStore.isMobile || window.innerWidth < 1024 || appSettingStore.fullPage) {
                appSettingStore.ott = 0; // Close all panels on mobile devices
            } else {
                if (appSettingStore.ott === 0) {
                    appSettingStore.ott = 4
                }
                // appSettingStore.ott = 0; // Default to closed on larger screens
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

    // router.reload()

    watch(
        () => appSettingStore.isSmallScreen,
        (newValue) => {
            if (newValue) {
                appSettingStore.ott = 0;
            } else {
                appSettingStore.ott = 4;
            }
        },
        { immediate: true } // This option ensures the watch executes immediately with the current value
    );

}
