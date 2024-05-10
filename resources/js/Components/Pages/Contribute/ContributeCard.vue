<template>
  <div class="relative group">
    <!-- Outer Border -->
    <div :class="outerBorderClass" class="absolute -inset-2 rounded-lg opacity-75 blur-sm"></div>

    <!-- Inner Border -->
    <div :class="innerBorderClass" class="absolute -inset-1 rounded-lg opacity-100 blur"></div>

    <div :class="[transitionClass, cardBgClass]" class="relative rounded-lg p-6" style="min-height: 350px;">
      <h3 class="text-xl font-semibold text-gray-100"><slot name="title"/></h3>
      <div class="text-yellow-400 my-4">
        <!-- Star Icon -->
        <slot name="icon"/>
      </div>
      <p class="bg-gray-800 bg-opacity-40 rounded-lg p-2 text-gray-300 mb-6"><slot name="main"/></p>
      <div class="text-center">
        <button @click="payNow(itemSelected)" :class="buttonClass" class="btn text-white py-2 px-4 rounded">
          <slot name="button"/>
        </button>
        <div>
          <slot name="noButton" :class="buttonClass"/>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { computed } from 'vue';
import { useShopStore } from '@/Stores/ShopStore'

const shopStore = useShopStore()

const props = defineProps({
  color: String,
  animation: {
    type: Boolean,
    default: true,
  },
  itemSelected: '',
  disableButton: false,
});

function payNow(subscription) {
  if (props.disableButton) {
    return
  }
  switch (subscription) {
    case 'monthlyContribution':
      shopStore.monthlyContribution();
      break;
    case 'yearlyContribution':
      shopStore.yearlyContribution();
      break;
    case 'oneTimeDonation':
      shopStore.oneTimeDonation();
      break;
    case 'favouriteShowContribution':
      shopStore.favouriteShowContribution();
      break;
    default:
      console.error("Unsupported subscription type:", subscription);
  }
  Inertia.get('/contribute/subscription');
}

const gradients = {
  blue: {
    outerFrom: 'from-blue-600',
    outerTo: 'to-blue-400',
    innerFrom: 'from-blue-500',
    innerTo: 'to-blue-300',
    buttonBg: 'bg-blue-500',
    buttonHover: 'hover:bg-blue-700'
  },
  purple: {
    outerFrom: 'from-purple-600',
    outerTo: 'to-pink-400',
    innerFrom: 'from-purple-500',
    innerTo: 'to-pink-300',
    buttonBg: 'bg-purple-500',
    buttonHover: 'hover:bg-purple-700'
  },
  green: {
    outerFrom: "from-green-600",
    outerTo: "to-green-400",
    innerFrom: "from-green-500",
    innerTo: "to-green-300",
    buttonBg: "bg-green-500",
    buttonHover: "hover:bg-green-700",
  },
  orange: {
    outerFrom: 'from-orange-600',
    outerTo: 'to-orange-400',
    innerFrom: 'from-orange-500',
    innerTo: 'to-orange-300',
    buttonBg: 'bg-orange-500',
    buttonHover: 'hover:bg-orange-700',
  },
};

const bgColors =  {
  blue: {
    outerFrom: 'from-blue-900',
    outerTo: 'to-blue-700',
    innerFrom: 'from-blue-800',
    innerTo: 'to-blue-600',
    buttonBg: 'bg-blue-900',
    buttonHover: 'hover:bg-blue-950'
  },
  purple: {
    outerFrom: 'from-purple-900',
    outerTo: 'to-pink-700',
    innerFrom: 'from-purple-800',
    innerTo: 'to-pink-600',
    buttonBg: 'bg-purple-900',
    buttonHover: 'hover:bg-purple-950'
  },
  green: {
    outerFrom: "from-green-900",
    outerTo: "to-green-700",
    innerFrom: "from-green-800",
    innerTo: "to-green-600",
    buttonBg: "bg-green-900",
    buttonHover: "hover:bg-green-950",
  },
  orange: {
    outerFrom: 'from-orange-900',
    outerTo: 'to-orange-700',
    innerFrom: 'from-orange-800',
    innerTo: 'to-orange-600',
    buttonBg: 'bg-orange-900',
    buttonHover: 'hover:bg-orange-950',
  },
}

// Computed property to dynamically set the class
const transitionClass = computed(() => {
  return props.animation
      ? 'transition duration-300 ease-in-out transform group-hover:scale-105'
      : '';
});

const outerBorderClass = computed(() => ({
  'bg-gradient-to-r': true,
  [gradients[props.color].outerFrom]: true,
  [gradients[props.color].outerTo]: true,
}));

const innerBorderClass = computed(() => ({
  'bg-gradient-to-r': true,
  [gradients[props.color].innerFrom]: true,
  [gradients[props.color].innerTo]: true,
}));

const buttonClass = computed(() => {
  // Default color handling
  const colorClasses = gradients[props.color] || gradients['blue']; // default to blue if color not found
  return {
    'btn text-white py-2 px-4 rounded': true, // Static classes
    [colorClasses.buttonBg]: true, // Dynamic background color class
    [colorClasses.buttonHover]: true, // Dynamic hover color class
  };
});

const cardBgClass = computed(() => ({
  'bg-gradient-to-r': true,
      [bgColors[props.color].innerFrom]: true,
      [bgColors[props.color].innerTo]: true,
}))
</script>
