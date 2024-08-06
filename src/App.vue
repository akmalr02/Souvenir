<script setup>
import navbar from "./components/layouts/navbar.vue";
import iklan from "./components/layouts/footer.vue";
</script>

<template>
  <div id="app">
    <navbar />
    <RouterView />
    <iklan />
  </div>
</template>

<style scoped></style>
