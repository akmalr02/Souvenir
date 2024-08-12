<style>
.content_image {
  display: flex;
  width: max-content;
  gap: 1rem;
}

.horizontal_scroll {
  width: 100%;
  overflow-x: scroll;
  scrollbar-color: transparent transparent;
}

.horizontal_scroll::-webkit-scrollbar {
  display: none;
}

.content_image img {
  width: 300px;
  height: 300px;
  object-fit: cover;
}
</style>

<template>
  <div class="text-center">
    <div class="hero bg-base-100">
      <div class="card bg-base-300 w-96 shadow-xl">
        <figure class="px-10 pt-10">
          <div class="horizontal_scroll">
            <div v-if="image && image.length" class="content_image">
              <img
                v-for="(data_gambar, index) in image"
                :key="index"
                :src="`${lokasi}/${data_gambar}`"
                alt="Gambar Souvenir"
              />
            </div>
          </div>
        </figure>
        <div class="card-body items-center text-center">
          <h2 class="card-title">{{ name }}</h2>
          <p>
            {{ description }}
          </p>
          <button @click="downloadPdf" class="btn btn-outline btn-accent m-3">
            <ArrowDownIcon class="size-6 text-yellow-200-500" /> Download
          </button>
          <div class="card-actions mt-3">
            <router-link to="/souvenir" class="btn btn-primary p-3">
              Back
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import apiClient from "../service/sovernir";
import { ArrowDownIcon } from "@heroicons/vue/24/solid";

export default {
  components: {
    ArrowDownIcon,
  },

  data() {
    return {
      isLoggedIn: false,
      name: "",
      description: "",
      price: "",
      quantity: "",
      image: [],
      pdf: "",
      lokasi: "http://laravel-jwt.test/storage/",
    };
  },
  created() {
    this.checkAuthStatus();
  },
  methods: {
    async downloadPdf() {
      try {
        const id = this.$route.params.id;
        const response = await apiClient.get(`/souvenirs/${id}`);
        const souvenir = response.data;

        const pdfUrl = this.lokasi + souvenir.pdf;

        const link = document.createElement("a");
        link.href = pdfUrl;
        link.setAttribute("download", "souvenir.pdf");

        document.body.appendChild(link);
        link.click();

        document.body.removeChild(link);
      } catch (error) {
        console.error("Error downloading the PDF:", error);
      }
    },
    checkAuthStatus() {
      const token = localStorage.getItem("authToken");
      if (token) {
        const payload = JSON.parse(atob(token.split(".")[1]));
        const isExpired = payload.exp * 1000 < Date.now();
        if (isExpired) {
          this.handleExpiredToken();
        } else {
          this.isLoggedIn = true;
          this.fetchSovernir();
        }
      } else {
        this.$router.push({ name: "Login" });
      }
    },
    handleExpiredToken() {
      localStorage.removeItem("authToken");
      this.isLoggedIn = false;
      this.$router.push({ name: "Login" });
    },
    async fetchSovernir() {
      const id = this.$route.params.id;
      try {
        const response = await apiClient.get(`/souvenirs/${id}`);
        const souvenir = response.data;
        const lokasi = "http://laravel-jwt.test/storage/";
        this.name = souvenir.name;
        this.description = souvenir.description;
        this.price = souvenir.price;
        this.quantity = souvenir.quantity;
        this.image = JSON.parse(souvenir.image);
        this.pdf = souvenir.pdf;
      } catch (error) {
        if (error.response && error.response.status === 401) {
          this.handleExpiredToken();
        } else {
          console.error("Error fetching souvenir:", error);
        }
      }
    },
  },
};
</script>
