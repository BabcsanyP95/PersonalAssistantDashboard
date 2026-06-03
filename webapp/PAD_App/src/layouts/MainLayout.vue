<template>
  <div class="min-h-screen flex">

    <Sidebar />

    <div class="flex-1 flex flex-col">

      <Topbar />

      <main class="flex-1 overflow-y-auto">
        <router-view />
      </main>

    </div>

  </div>
</template>

<script setup lang="ts">
import Sidebar from '../components/Sidebar.vue'
import Topbar from '../components/Topbar.vue'
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