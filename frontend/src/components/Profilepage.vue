<template>
  <div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h1 class="text-2xl font-bold mb-4">Thông tin người dùng</h1>

    <div v-if="isLoading" class="text-gray-600">Đang tải...</div>

    <div v-else-if="errorMessage" class="text-red-500">{{ errorMessage }}</div>

    <div v-else>
      <p><strong>Name:</strong> {{ user.name }}</p>
      <p><strong>Email:</strong> {{ user.email }}</p>
      <p><strong>Tạo ngày:</strong> {{ user.created_at }}</p>
      <button
        class="mr-4 text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 focus:outline-none"
      >
        <router-link to="/todos">Tạo công việc hôm nay</router-link>
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Cookies from "js-cookie";

export default {
  name: "ProfilePage",
  data() {
    return {
      user: {},
      isLoading: true,
      errorMessage: "",
    };
  },
  beforeMount() {
    if (!Cookies.get("token")) {
      this.$router.push("/login");
    }
  },
  async mounted() {
    try {
      const response = await axios.get(`${this.$baseURL}/auth/profile`, {
        headers: {
          Accept: "application/json",
          Authorization: `Bearer ${Cookies.get("token")}`,
        },
      });

      if (response.data.status) {
        this.user = response.data.data;
      } else {
        this.errorMessage = "Không thể lấy thông tin người dùng.";
      }
    } catch (error) {
      this.errorMessage = "Đã xảy ra lỗi khi lấy dữ liệu.";
      console.error(error);
    } finally {
      this.isLoading = false;
    }
  },
};
</script>
