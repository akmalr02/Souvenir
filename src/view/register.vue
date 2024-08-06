<template>
  <div class="text-center">
    <div class="hero bg-base-200 min-h-screen">
      <div class="hero-content flex-col lg:flex-row-reverse">
        <div class="text-center lg:text-left">
          <h1 class="text-5xl font-bold">Registrasi now!</h1>
          <p class="py-6">
            Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda
            excepturi exercitationem quasi. In deleniti eaque aut repudiandae et
            a id nisi.
          </p>
        </div>
        <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
          <form class="card-body" @submit.prevent="register">
            <div class="form-control">
              <label class="label">
                <span class="label-text">Name</span>
              </label>
              <input
                v-model="name"
                type="text"
                placeholder="Name"
                class="input input-bordered"
                required
              />
              <div v-if="errors.name" class="text-red-500">
                {{ errors.name }}
              </div>
            </div>
            <div class="form-control">
              <label class="label">
                <span class="label-text">Email</span>
              </label>
              <input
                v-model="email"
                type="email"
                placeholder="Email"
                class="input input-bordered"
                required
              />
              <div v-if="errors.email" class="text-red-500">
                {{ errors.email }}
              </div>
            </div>
            <div class="form-control">
              <label class="label">
                <span class="label-text">Password</span>
              </label>
              <div class="relative">
                <input
                  :type="passwordVisible ? 'text' : 'password'"
                  v-model="password"
                  placeholder="Password"
                  class="input input-bordered w-full"
                  required
                />
                <button
                  type="button"
                  @click="togglePasswordVisibility"
                  class="absolute right-2 top-2 btn btn-xs btn-outline"
                >
                  <component
                    :is="passwordVisible ? 'EyeIcon' : 'XCircleIcon'"
                    class="w-4 h-4"
                  />
                </button>
              </div>
              <label
                for="password"
                class="text-error font-bold"
                v-if="passwordError"
              >
                Minimal password 8 karakter
              </label>
              <div v-if="errors.password" class="text-red-500">
                {{ errors.password }}
              </div>
            </div>
            <div class="form-control">
              <label class="label">
                <span class="label-text">Konfirmasi Password</span>
              </label>
              <div class="relative">
                <input
                  :type="passwordConfirmVisible ? 'text' : 'password'"
                  v-model="password_confirmation"
                  placeholder="Konfirmasi Password"
                  class="input input-bordered w-full"
                  required
                />
                <button
                  type="button"
                  @click="toggleConfirmPasswordVisibility"
                  class="absolute right-2 top-2 btn btn-xs btn-outline"
                >
                  <component
                    :is="passwordConfirmVisible ? 'EyeIcon' : 'XCircleIcon'"
                    class="w-4 h-4"
                  />
                </button>
              </div>
              <label
                for="password_confirmation"
                class="text-error font-bold"
                v-if="confirmPasswordError"
              >
                Password tidak sama
              </label>
              <div v-if="errors.password_confirmation" class="text-red-500">
                {{ errors.password_confirmation }}
              </div>
            </div>
            <div class="form-control mt-6">
              <button class="btn btn-primary" type="submit">Register</button>
            </div>
          </form>
          <p class="p-3">
            Sudah punya akun? Silahkan
            <router-link to="/login" class="link link-primary"
              >Login</router-link
            >
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import apiClient from "@/service/sovernir";
import { EyeIcon, XCircleIcon } from "@heroicons/vue/24/solid";

export default {
  components: {
    EyeIcon,
    XCircleIcon,
  },
  data() {
    return {
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      errors: {},
      passwordError: false,
      confirmPasswordError: false,
      passwordVisible: false,
      passwordConfirmVisible: false,
    };
  },
  watch: {
    password(value) {
      this.passwordError = value.length < 8;
      this.validateConfirmPassword();
    },
    password_confirmation(value) {
      this.validateConfirmPassword();
    },
  },
  methods: {
    togglePasswordVisibility() {
      this.passwordVisible = !this.passwordVisible;
    },
    toggleConfirmPasswordVisibility() {
      this.passwordConfirmVisible = !this.passwordConfirmVisible;
    },
    resetForm() {
      this.name = "";
      this.email = "";
      this.password = "";
      this.password_confirmation = "";
      this.errors = {};
    },
    validateConfirmPassword() {
      this.confirmPasswordError = this.password_confirmation !== this.password;
    },
    async register() {
      this.errors = {};
      try {
        const response = await apiClient.post("/register", {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
        });
        this.notifBerhasil();
        this.resetForm();
      } catch (error) {
        if (
          error.response &&
          error.response.data &&
          error.response.data.errors
        ) {
          this.errors = error.response.data.errors;
        }
        this.notifGagal();
      }
    },
    notifBerhasil() {
      toast("Daftar Berhasil!", {
        autoClose: 1000,
      });
    },
    notifGagal() {
      toast("Gagal Register!", {
        autoClose: 1000,
      });
    },
  },
};
</script>

<style></style>
