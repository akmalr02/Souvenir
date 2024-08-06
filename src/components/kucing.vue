<template>
  <div>
    <h1>{{ message }}</h1>
    <ul>
      <li v-for="cat in catList" :key="cat.id">
        <img :src="cat.url" :alt="cat.id" width="200" />
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      message: "Hello World",
      catList: [],
    };
  },
  methods: {
    async getList() {
      let response = await fetch(
        "https://api-partner.krl.co.id/krlweb/v1/krl-station"
      );
      let data = await response.json();
      this.catList = data;
    },
  },
  mounted() {
    this.getList();
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>
