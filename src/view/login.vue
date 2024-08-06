<template>
  <div class="text-center">
    <div class="hero bg-base-100 min-h-screen">
      <div class="hero-content flex-col lg:flex-row-reverse">
        <div class="text-center lg:text-left">
          <h1 class="text-5xl font-bold">Login now!</h1>
          <p class="py-6">
            Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda
            excepturi exercitationem quasi. In deleniti eaque aut repudiandae et
            a id nisi.
          </p>
        </div>
        <div class="card bg-base-200 w-full max-w-sm shrink-0 shadow-2xl">
          <form class="card-body" @submit.prevent="login">
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
            </div>
            <div class="form-control mt-6">
              <button class="btn btn-primary" type="submit">Login</button>
            </div>
          </form>
          <p class="p-3">
            Belum punya akun? Silahkan
            <router-link to="/register" class="link link-primary"
              >Klik disini</router-link
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
      email: "",
      password: "",
      passwordVisible: false,
    };
  },
  methods: {
    togglePasswordVisibility() {
      this.passwordVisible = !this.passwordVisible;
    },
    async login() {
      try {
        const response = await apiClient.post("/login", {
          email: this.email,
          password: this.password,
        });

        const token = response.data.token;
        localStorage.setItem("authToken", token);
        this.checkAuthStatus();
        this.$router.push({ name: "Souvenir" });
      } catch (error) {
        this.notifGagal();
        // console.error("Login failed:", error);
      }
    },
    checkAuthStatus() {
      const token = localStorage.getItem("authToken");
      this.isLoggedIn = !!token;
    },
    notifGagal() {
      toast("Email atau password salah", {
        autoClose: 1000,
      });
    },
  },
};
</script>

<style></style>
