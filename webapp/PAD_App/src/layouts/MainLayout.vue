<template>
  <div class="h-screen flex overflow-hidden">

    <Sidebar ref="sidebarRef" />

    <div class="flex-1 flex flex-col min-h-0">

      <Topbar @toggle-sidebar="sidebarRef?.open()" />

      <main class="flex-1 overflow-y-auto bg-gray-50 p-4 md:p-6">
        <router-view />
      </main>

    </div>

  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Sidebar from '../components/Sidebar.vue'
import Topbar from '../components/Topbar.vue'

const sidebarRef = ref()
</script>

<style scoped>
.app-layout {
  display: flex;
  height: 100vh;
}

.main {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.content {
  padding: 20px;
  flex: 1;
  overflow: auto;
}
</style>