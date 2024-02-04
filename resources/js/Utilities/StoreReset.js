// StoreReset.js
import { defineStore } from 'pinia';

export const useStoreReset = defineStore('storeReset', {
    actions: {
        async resetAllStores() {
            // List of all your store modules
            const storeModules = [
                'AppSettingStore',
                'ChannelStore',
                'ChatStore',
                'DashboardStore',
                'MistStore',
                'MovieStore',
                'NewsStore',
                'NotificationStore',
                'NowPlayingStore',
                'ShopStore',
                'ShowStore',
                'StreamStore',
                'TeamStore',
                'UserStore',
                'VideoPlayerStore',
                'WelcomeStore',
                // Add more stores as needed
            ];

            // Dynamically import and reset each store
            for (const moduleName of storeModules) {
                const module = await import(`@/Stores/${moduleName}`);
                const store = module[`use${moduleName}`]();
                store.reset?.();
            }
        },
    },
});
