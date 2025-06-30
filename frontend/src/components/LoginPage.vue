<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow">
      <h1 class="text-2xl font-bold mb-6 text-center">Đăng nhập</h1>
      <form v-on:submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label for="email" class="block font-medium mb-1">Email:</label>
          <input
            type="email"
            id="email"
            v-model="email"
            required
            placeholder="Nhập email"
            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-blue-500"
          />
        </div>
        <div>
          <label for="password" class="block font-medium mb-1">Mật khẩu:</label>
          <input
            type="password"
            id="password"
            v-model="password"
            required
            placeholder="Nhập mật khẩu"
            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-blue-500"
          />
        </div>
        <button
          type="submit"
          :disabled="isLoading"
          class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 disabled:opacity-50"
        >
          {{ isLoading ? "Đang đăng nhập..." : "Đăng nhập" }}
        </button>
      </form>
      <p v-if="errorMessage" class="text-red-500 mt-4 text-center">
        {{ errorMessage }}
      </p>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Cookies from "js-cookie";
export default {
  name: "LoginPage",
  data() {
    return {
      email: "",
      password: "",
      errorMessage: "",
      isLoading: false,
    };
  },
  beforeMount() {
    if (Cookies.get("token")) {
      this.$router.push("/todos");
    }
  },
  methods: {
    async handleLogin() {
      try {
        this.isLoading = true;
        const response = await axios.post(
          `${this.$baseURL}/auth/login`,
          {
            email: this.email,
            password: this.password,
          },
          {
            headers: { Accept: "application/json" },
          }
        );

        Cookies.set("token", response.data.token, { expires: 1 });
        this.$router.push("/todos");
      } catch (error) {
        if (error.response && error.response.status === 401) {
          // Nếu API trả về lỗi 401
          this.errorMessage =
            error.response.data.message || "Sai tài khoản hoặc mật khẩu 2.";
        } else {
          // Lỗi khác (mạng, server, v.v.)
          this.errorMessage = "Đã có lỗi xảy ra. Vui lòng thử lại sau.";
          console.error(error);
        }
      } finally {
        this.isLoading = false;
      }
    },
  },
};
</script>
