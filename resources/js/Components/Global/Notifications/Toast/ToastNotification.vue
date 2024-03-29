<template>
  <transition name="slide-fade">
    <div v-if="notificationStore.toastNotificationVisible" :key="notificationStore.toastNotificationKey" class="toast-notification">
      <div :class="[notificationTypeClass(notificationStore.toastNotificationStatus), 'alert']">
        <span>{{ emoji }} {{ notificationStore.toastNotificationMessage }}</span>
      </div>
    </div>
  </transition>



</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { useNotificationStore } from '@/Stores/NotificationStore'

const notificationStore = useNotificationStore()

const notificationTypeClass = (status) => {
  switch (status) {
    case 'success':
      return 'alert-success';
    case 'error':
      return 'alert-danger'; // Note: Bootstrap uses 'alert-danger' for errors
    case 'info':
      return 'alert-info';
    case 'warning':
      return 'alert-warning';
    default:
      return '';
  }
};


const emoji = computed(() => {
  switch (notificationStore.toastNotificationStatus) {
    case 'success':
      return '🎉';
    case 'error':
      return '😢';
    case 'info':
      return 'ℹ️';
    case 'warning':
      return '⚠️';
    default:
      return ''; // No emoji for an undefined status
  }
});

const messageClass = computed(() => ({
  'alert alert-success': notificationStore.toastNotificationStatus === 'success',
  'alert alert-error': notificationStore.toastNotificationStatus === 'error',
  'alert alert-info': notificationStore.toastNotificationStatus === 'info',
  'alert alert-warning': notificationStore.toastNotificationStatus === 'warning',
}))

const notificationKey = ref(0);

watch(() => notificationStore.toastNotificationVisible, (newValue) => {
  if (newValue) {
    notificationKey.value++; // Increment to force re-render
    setTimeout(() => {
      notificationStore.resetToastNotification();
    }, notificationStore.toastNotificationTimeout); // Adjust the time as needed
  }
});

</script>

<style>
.specialZindex {
  z-index: 9000
}
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s, transform 0.5s;
}

.fade-enter, .fade-leave-to {
  opacity: 0;
  transform: translateY(20px); /* Adjust for a sliding effect */
}

.toast-notification {
  position: fixed;
  bottom: 20px;
  left: 20px;
  z-index: 1050;
}

/* Assuming each toast notification has a class like `toast-1`, `toast-2`, etc., applied dynamically based on their order */
.toast-notification.toast-1 { bottom: 20px; }
.toast-notification.toast-2 { bottom: 80px; } /* Adjust based on the height and margin of the notifications */
.toast-notification.toast-3 { bottom: 140px; }
/* and so on */


.alert {
  padding: 1rem;
  margin-bottom: 1rem;
  border: 1px solid transparent;
  border-radius: 0.25rem;
  color: #004085;
  background-color: #cce5ff;
  border-color: #b8daff;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  transition: transform 0.3s ease-in-out;
}

/* Custom classes for different types */
.alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
.alert-error { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
.alert-info { background-color: #d1ecf1; color: #0c5460; border-color: #bee5eb; }
.alert-warning { background-color: #fff3cd; color: #856404; border-color: #ffeeba; }

/* Transition for the notification */
.slide-fade-enter-active, .slide-fade-leave-active {
  transition: all 0.5s ease;
}
.slide-fade-enter, .slide-fade-leave-to {
  transform: translateX(100%);
  opacity: 0;
}

/* Custom classes for different types */
.alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
.alert-error { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
.alert-info { background-color: #d1ecf1; color: #0c5460; border-color: #bee5eb; }
.alert-warning { background-color: #fff3cd; color: #856404; border-color: #ffeeba; }

/* Transition for the notification */
.slide-fade-enter-active, .slide-fade-leave-active {
  transition: all 0.5s ease;
}
.slide-fade-enter, .slide-fade-leave-to {
  transform: translateX(100%);
  opacity: 0;
}




</style>
