<template>
  <div class="flex justify-center items-center min-h-screen mb-6">
    <div class="card bg-base-300 w-96 shadow-xl">
      <div class="card-body">
        <h2 class="flex justify-center card-title text-center">
          Tambah Souvenir
        </h2>
        <form
          class="card-body pt-3"
          @submit.prevent="submitSouvenir"
          enctype="multipart/form-data"
        >
          <div class="form-control">
            <label class="label">
              <span class="label-text">Nama</span>
            </label>
            <input
              v-model="name"
              type="text"
              placeholder="Name"
              class="input input-bordered"
              required
            />
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">Description</span>
            </label>
            <textarea
              v-model="description"
              placeholder="Description"
              class="textarea textarea-bordered"
              required
            ></textarea>
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">Price</span>
            </label>
            <input
              v-model="price"
              type="text"
              inputmode="numeric"
              placeholder="Price"
              class="input input-bordered"
              required
            />
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">Quantity</span>
            </label>
            <input
              v-model="quantity"
              type="text"
              inputmode="numeric"
              placeholder="Quantity"
              class="input input-bordered"
              required
            />
          </div>

          <!-- Upload Image -->
          <div class="form-control">
            <label class="label">
              <span class="label-text">Images</span>
            </label>
            <input
              ref="images"
              type="file"
              @change="handleFileUpload"
              multiple
            />
            <!-- Tampilkan pesan error jika kurang dari 3 gambar -->
            <div v-if="errors.image" class="error-message">
              {{ errors.image }}
            </div>
            <ul class="mt-3">
              <li v-for="(image, index) in uploadedImages" :key="index">
                {{ image.name }}
                <button @click="removeImage(index)" class="btn btn-error mb-3">
                  X
                </button>
              </li>
            </ul>
          </div>

          <!-- Upload PDF -->
          <div class="form-control">
            <label class="label">
              <span class="label-text">PDF</span>
            </label>
            <input ref="pdf" type="file" @change="handlePdfUpload" />
            <ul class="mt-3">
              <li v-if="pdfFile">
                {{ pdfFile.name }}
                <button @click="removePdf()" class="btn btn-error mb-3">
                  Remove
                </button>
              </li>
            </ul>
          </div>
          <div class="form-control mt-2">
            <button class="btn btn-primary p-3 mb-2" type="submit">
              Submit
            </button>
            <router-link to="/souvenir" class="btn btn-primary p-3"
              >Back</router-link
            >
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import apiClient from "@/service/sovernir";

export default {
  data() {
    return {
      name: "",
      description: "",
      price: "",
      quantity: "",
      uploadedImages: [],
      pdfFile: null,
      errors: {
        image: "",
      },
    };
  },
  methods: {
    handleFileUpload(event) {
      const files = Array.from(event.target.files);
      this.uploadedImages = [...this.uploadedImages, ...files];

      // Validasi minimal 3 gambar
      if (this.uploadedImages.length < 3) {
        this.errors.image = "Minimal unggah 3 gambar!";
      } else {
        this.errors.image = "";
      }
    },
    handlePdfUpload(event) {
      this.pdfFile = event.target.files[0];
    },
    removeImage(index) {
      this.uploadedImages.splice(index, 1);

      // Periksa lagi jumlah gambar setelah penghapusan
      if (this.uploadedImages.length < 3) {
        this.errors.image = "Minimal unggah 3 gambar!";
      } else {
        this.errors.image = "";
      }
    },
    removePdf() {
      this.pdfFile = null;
    },
    async submitSouvenir() {
      if (this.uploadedImages.length < 3) {
        this.errors.image = "Minimal unggah 3 gambar!";
        this.notifGagal(this.errors.image);
        return;
      }

      const formData = new FormData();
      formData.append("name", this.name);
      formData.append("description", this.description);
      formData.append("price", this.price);
      formData.append("quantity", this.quantity);

      this.uploadedImages.forEach((image) => {
        formData.append("image[]", image);
      });

      if (this.pdfFile) {
        formData.append("pdf", this.pdfFile);
      }

      try {
        const response = await apiClient.post("/souvenirs", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });

        this.souvenir = response.data;
        console.log(response);
        this.resetForm();
        this.notifBerhasil();
      } catch (error) {
        this.notifGagal();
      }
    },

    notifGagal(message = "Gagal tambah souvenir !!!") {
      toast(message, {
        autoClose: 1000,
      });
    },
    notifBerhasil() {
      toast("Berhasil tambah data", {
        autoClose: 1000,
      });
    },
    resetForm() {
      this.name = "";
      this.description = "";
      this.price = "";
      this.quantity = "";
      this.uploadedImages = [];
      this.pdfFile = null;
      this.$refs.images.value = "";
      this.$refs.pdf.value = "";
    },
  },
};
</script>

<style>
/* Tambahkan styling untuk pesan kesalahan */
.error-message {
  color: red;
  font-weight: bold;
  margin-top: 0.5rem; /* Beri jarak sedikit dari elemen lainnya */
}
</style>
