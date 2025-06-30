<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow">
      <h1 class="text-2xl font-semibold text-center mb-6">Đăng ký</h1>
      <form @submit.prevent="handleRegister" class="space-y-4">
        <div class="form-group">
          <label for="email" class="block text-sm font-medium text-gray-700"
            >Email:</label
          >
          <input
            type="email"
            id="email"
            v-model="email"
            required
            placeholder="Nhập email của bạn"
            class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div class="form-group">
          <label for="name" class="block text-sm font-medium text-gray-700"
            >Tên:</label
          >
          <input
            type="text"
            id="name"
            v-model="name"
            required
            placeholder="Họ và tên của bạn"
            class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div class="form-group">
          <label for="password" class="block text-sm font-medium text-gray-700"
            >Mật khẩu:</label
          >
          <input
            type="password"
            id="password"
            v-model="password"
            required
            placeholder="Nhập mật khẩu của bạn"
            class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div class="form-group">
          <label
            for="password_confirmation"
            class="block text-sm font-medium text-gray-700"
            >Nhập lại mật khẩu:</label
          >
          <input
            type="password"
            id="password_confirmation"
            v-model="password_confirmation"
            required
            placeholder="Nhập lại mật khẩu của bạn"
            class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <button
          type="submit"
          class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          Đăng ký
        </button>
      </form>

      <p v-if="errorMessage" class="text-red-500 text-sm mt-2">
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
      name: "",
      password: "",
      password_confirmation: "",
      errorMessage: "",
    };
  },
  beforeMount() {
    if (Cookies.get("token")) {
      this.$router.push("/todos");
    }
  },
  methods: {
    async handleRegister() {
      try {
        const validationError = this.validate();
        if (validationError) {
          this.errorMessage = validationError;
          return;
        }
        const response = await axios.post(
          `${this.$baseURL}/auth/register`,
          {
            email: this.email,
            name: this.name,
            password: this.password,
            password_confirmation: this.password_confirmation,
          },
          {
            headers: { Accept: "application/json" },
          }
        );

        Cookies.set("token", response.data.token, { expires: 1 });
        this.$router.push("/login");
      } catch (error) {
        if (error.response && error.response.status === 422) {
          // Nếu API trả về lỗi 401
          const errors = Object.values(error.response.data.errors);
          if (errors[0][0].includes("unique")) {
            this.errorMessage = "Email đăng ký đã tồn tại.";
          } else {
            this.errorMessage = errors[0][0];
          }
        } else {
          // Lỗi khác (mạng, server, v.v.)
          this.errorMessage = "Đã có lỗi xảy ra. Vui lòng thử lại sau.";
          console.error(error);
        }
      }
    },
    validate() {
      if (
        !this.name ||
        !this.email ||
        !this.password ||
        !this.password_confirmation
      ) {
        return "Vui lòng điền đầy đủ thông tin.";
      }

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(this.email)) {
        return "Email không hợp lệ.";
      }

      if (this.password.length < 6) {
        return "Mật khẩu phải có ít nhất 6 ký tự.";
      }

      if (this.password !== this.password_confirmation) {
        return "Mật khẩu xác nhận không khớp.";
      }

      return null; // Không có lỗi
    },
  },
};
</script>

<style scoped>
.login {
  max-width: 400px;
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

input {
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
}

button {
  width: 100%;
  padding: 10px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}

.error {
  color: red;
  margin-top: 10px;
}
</style>
