<template>
    <div class="fixed top-6 right-6 z-[100] flex flex-col gap-3 w-80">
      <TransitionGroup 
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div 
          v-if="isVisible" 
          :key="message"
          :class="[
            'p-4 rounded-2xl shadow-2xl border flex items-start gap-3',
            type === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-red-50 border-red-200 text-red-800'
          ]"
        >
          <div class="mt-0.5">
            <span v-if="type === 'success'" class="text-xl">✅</span>
            <span v-else class="text-xl">⚠️</span>
          </div>
          <div class="flex-1">
            <p class="text-sm font-bold leading-tight">{{ type === 'success' ? 'Success!' : 'Error' }}</p>
            <p class="text-xs opacity-90 mt-1">{{ message }}</p>
          </div>
          <button @click="isVisible = false" class="text-gray-400 hover:text-gray-600 transition">
            ✕
          </button>
        </div>
      </TransitionGroup>
    </div>
  </template>
  
  <script setup>
  import { ref, watch, onMounted } from 'vue';
  import { usePage } from '@inertiajs/vue3';
  
  const page = usePage();
  const isVisible = ref(false);
  const message = ref('');
  const type = ref('success');
  let timer = null;
  
  const show = (msg, msgType) => {
    message.value = msg;
    type.value = msgType;
    isVisible.value = true;
    
    if (timer) clearTimeout(timer);
    timer = setTimeout(() => {
      isVisible.value = false;
    }, 5000);
  };
  
  // Watch for flash messages from Inertia
  watch(() => page.props.flash, (flash) => {
    if (flash?.success) show(flash.success, 'success');
    if (flash?.error) show(flash.error, 'error');
  }, { deep: true });
  
  // Check on initial load
  onMounted(() => {
    if (page.props.flash?.success) show(page.props.flash.success, 'success');
    if (page.props.flash?.error) show(page.props.flash.error, 'error');
  });
  </script>