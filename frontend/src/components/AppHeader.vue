<template>
  <header
    class="flex justify-between items-center px-12 py-4 text-black border border-solid border-gray-300"
  >
    <router-link to="/">
      <div class="flex justify-between items-center">
        <img
          src="https://images.seeklogo.com/logo-png/27/1/vue-js-logo-png_seeklogo-274070.png"
          alt="Logo"
          width="50"
        />
        <span class="ml-4 text-2xl font-bold">Todo App</span>
      </div>
    </router-link>
    <nav>
      <template v-if="!isLoggedIn">
        <router-link to="/login">
          <button
            class="mr-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
          >
            Đăng nhập
          </button>
        </router-link>
        <router-link to="/register">
          <button
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
          >
            Đăng ký
          </button>
        </router-link>
      </template>

      <template v-else>
        <button
          class="mr-4 text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 focus:outline-none"
        >
          <router-link to="/profile">Thông tin cá nhân</router-link>
        </button>
        <button
          v-on:click="handleLogout"
          class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 focus:outline-none"
        >
          Đăng xuất
        </button>
      </template>
    </nav>
  </header>
</template>

<script>
import Cookies from "js-cookie";
import axios from "axios";
export default {
  name: "AppHeader",
  data() {
    return {
      isLoggedIn: !!Cookies.get("token"),
    };
  },
  methods: {
    async handleLogout() {
      const response = await axios.get(`${this.$baseURL}/auth/logout`, {
        headers: {
          Accept: "application/json",
          Authorization: `Bearer ${Cookies.get("token")}`,
        },
      });
      if (response.status) {
        Cookies.remove("token");
        this.$router.push("/login");
      } else {
        alert("Đăng xuất không thành công. Vui lòng thử lại");
      }
    },
  },
  // watch
};
</script>
