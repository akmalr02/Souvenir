<template>
  <div class="text-center">
    <div class="overflow-x-auto m-5">
      <div v-if="isLoggedIn">
        <td>
          <router-link to="/tambah" class="btn btn-primary mb-3">
            <PlusCircleIcon class="size-6 text-error-200-500" />Tambah Souvenir
          </router-link>
        </td>
        <table class="table table-zebra w-full">
          <!-- head -->
          <thead>
            <tr>
              <th>Name</th>
              <th>Description</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Keterangan</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="souvenir in souvenirs" :key="souvenir.id">
              <td>{{ souvenir.name }}</td>
              <td>{{ souvenir.description }}</td>
              <td>${{ souvenir.price }}</td>
              <td>{{ souvenir.quantity }}</td>
              <td>
                <router-link
                  :to="{ name: 'view', params: { id: souvenir.id } }"
                  class="btn btn-primary mt-2"
                >
                  <EyeIcon class="size-6 text-blue-100-300" /> View
                </router-link>
                <router-link
                  :to="{ name: 'edit', params: { id: souvenir.id } }"
                  class="btn btn-outline btn-warning mt-2"
                >
                  <PencilIcon class="size-6 text-yellow-200-500" /> Edit
                </router-link>
              </td>
              <td>
                <form @submit.prevent="deleteSouvenir(souvenir.id)">
                  <button type="submit" class="btn btn-outline btn-error">
                    <TrashIcon class="size-6 text-error-200-500" />Delete
                  </button>
                </form>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else>
        <h2 class="font-bold text-center m-10 p-10">Harap Login Dahulu!!!</h2>
      </div>
    </div>
  </div>
</template>

<script>
import apiClient from "../service/sovernir";
import {
  PencilIcon,
  TrashIcon,
  PlusCircleIcon,
  EyeIcon,
} from "@heroicons/vue/24/solid";

export default {
  components: {
    PencilIcon,
    TrashIcon,
    PlusCircleIcon,
    EyeIcon,
  },
  data() {
    return {
      isLoggedIn: false,
      souvenirs: [],
    };
  },
  created() {
    this.checkAuthStatus();
    if (this.isLoggedIn) {
    }
  },
  methods: {
    checkAuthStatus() {
      const token = localStorage.getItem("authToken");
      if (token) {
        const payload = JSON.parse(atob(token.split(".")[1]));
        const isExpired = payload.exp * 1000 < Date.now();
        if (isExpired) {
          this.handleExpiredToken();
        } else {
          this.isLoggedIn = true;
          this.fetchSouvenirs();
        }
      }
    },

    handleExpiredToken() {
      localStorage.removeItem("authToken");
      this.isLoggedIn = false;
      this.$router.push({ name: "Login" });
    },

    async fetchSouvenirs() {
      try {
        const response = await apiClient.get("/souvenirs");
        this.souvenirs = response.data;
      } catch (error) {
        if (error.response && error.response.status === 401) {
          this.handleExpiredToken();
        } else {
          console.error("Error fetching souvenirs:", error);
        }
      }
    },

    async deleteSouvenir(id) {
      try {
        await apiClient.delete(`/souvenirs/${id}`);
        this.fetchSouvenirs();
      } catch (error) {
        if (error.response && error.response.status === 401) {
          this.handleExpiredToken();
        } else {
          console.error("Error deleting souvenir:", error);
        }
      }
    },
  },
};
</script>

<style></style>
