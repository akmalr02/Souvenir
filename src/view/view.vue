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
          <button @click="downloadPdf" class="btn btn-outline btn-accent">
            <ArrowDownIcon class="size-6 text-yellow-200-500" />
          </button>
          <div class="card-actions">
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
// import { DownloadIcon } from "@vue-hero-icons/outline";
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
      // souvenir: "",
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

        // Asumsikan `souvenir.pdf` adalah URL ke file PDF
        const pdfUrl = this.lokasi + souvenir.pdf;

        // Buat link element untuk mendownload file
        const link = document.createElement("a");
        link.href = pdfUrl;
        link.setAttribute("download", "souvenir.pdf"); // Nama file yang diinginkan

        // Append link ke body untuk men-trigger download
        document.body.appendChild(link);
        link.click();

        // Hapus link element setelah download
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
        // this.image = souvernir.image.JSON.parse("image[]", souvenir.image);
        this.image = JSON.parse(souvenir.image);
        this.pdf = souvenir.pdf;

        // console.log(this.pdf);
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
