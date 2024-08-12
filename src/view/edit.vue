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
  width: 100px;
  height: 100px;
  object-fit: cover;
}

.error-message {
  color: red;
  font-weight: bold;
  margin-top: 0.5rem;
}
</style>

<template>
  <div class="flex justify-center items-center min-h-screen mb-6">
    <div class="card bg-base-300 w-96 shadow-xl">
      <div class="card-body">
        <h2 class="flex justify-center card-title text-center">
          Edit Souvenir
        </h2>
        <form class="card-body pt-3" @submit.prevent="updatePost()">
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
            <div v-if="errors.name" class="alert alert-danger mt-2">
              <span>{{ errors.name[0] }}</span>
            </div>
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
            <div v-if="errors.description" class="alert alert-danger mt-2">
              <span>{{ errors.description[0] }}</span>
            </div>
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
            <div v-if="errors.price" class="alert alert-danger mt-2">
              <span>{{ errors.price[0] }}</span>
            </div>
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
            <div v-if="errors.quantity" class="alert alert-danger mt-2">
              <span>{{ errors.quantity[0] }}</span>
            </div>
          </div>
          <!-- Menampilkan Gambar -->
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
          <!-- upluad image -->
          <div class="form-control">
            <label class="label">
              <span class="label-text">Upload Images</span>
            </label>
            <input
              ref="images"
              type="file"
              @change="handleFileChange"
              multiple
            />
            <div v-if="errors.image" class="error-message">
              {{ errors.image[0] }}
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
          <!-- Menampilkan PDF -->
          <div v-if="pdfUrl" class="form-control mt-4">
            <label class="label">
              <span class="label-text">Current PDF</span>
            </label>
            <a :href="`${lokasi}/${pdfUrl}`" target="_blank">View PDF</a>
          </div>

          <!-- Upload PDF -->
          <div class="form-control">
            <label class="label">
              <span class="label-text">Upload New PDF</span>
            </label>
            <input
              ref="pdf"
              type="file"
              accept="application/pdf"
              @change="handlePDFUpload"
            />
          </div>

          <div class="form-control mt-2">
            <button class="btn btn-primary p-3 mb-2" type="submit">
              Submit
            </button>
            <router-link to="/souvenir" class="btn btn-primary p-3">
              Back
            </router-link>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import apiClient from "@/service/sovernir";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

const router = useRouter();
const route = useRoute();

const name = ref("");
const description = ref("");
const price = ref("");
const quantity = ref("");
const image = ref([]);
const uploadedImages = ref([]);
const pdfUrl = ref("");
const pdfFile = ref(null);
const errors = ref([]);

const lokasi = "http://laravel-jwt.test/storage/";

onMounted(async () => {
  try {
    const response = await apiClient.get(`/souvenirs/${route.params.id}`);
    const souvenir = response.data;
    name.value = souvenir.name;
    description.value = souvenir.description;
    price.value = souvenir.price;
    quantity.value = souvenir.quantity;
    image.value = JSON.parse(souvenir.image);
    pdfUrl.value = souvenir.pdf;
  } catch (error) {
    notifGagal();
  }
});

const handleFileChange = (e) => {
  const files = Array.from(e.target.files);
  uploadedImages.value = [...uploadedImages.value, ...files];

  if (uploadedImages.value.length < 3) {
    errors.value.image = ["Please upload at least 3 images."];
  } else {
    errors.value.image = [];
  }
};

const handlePDFUpload = (e) => {
  pdfFile.value = e.target.files[0];
};

const removeImage = (index) => {
  uploadedImages.value.splice(index, 1);

  if (uploadedImages.value.length < 3) {
    errors.value.image = ["Please upload at least 3 images."];
  } else {
    errors.value.image = [];
  }
};

const updatePost = async () => {
  if (uploadedImages.value.length < 3) {
    errors.value.image = ["Please upload at least 3 images."];
    return;
  }

  try {
    let formData = new FormData();
    formData.append("name", name.value);
    formData.append("description", description.value);
    formData.append("price", price.value);
    formData.append("quantity", quantity.value);

    uploadedImages.value.forEach((image) => {
      formData.append("image[]", image);
    });

    if (pdfFile.value) {
      formData.append("pdf", pdfFile.value);
    }

    formData.append("_method", "PATCH");

    await apiClient
      .post(`/souvenirs/${route.params.id}`, formData)
      .then(() => {
        notifBerhasil();
        router.push({ path: "/souvenir" });
      })
      .catch((error) => {
        errors.value = error.response.data;
      });
  } catch (error) {
    notifGagal();
  }
};

const notifGagal = () => {
  toast("Gagal Edit souvenir !!!", {
    autoClose: 1000,
  });
};

const notifBerhasil = () => {
  toast("Berhasil Edit Data", {
    autoClose: 1000,
  });
};
</script>
