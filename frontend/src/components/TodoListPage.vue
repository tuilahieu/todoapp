<template>
  <div class="flex h-screen font-sans" style="max-height: calc(100vh - 84px)">
    <!-- Sidebar -->
    <div class="w-1/4 bg-black text-white p-6 space-y-6 shadow-md">
      <div class="flex items-center justify-between mb-4 border-b">
        <h2 class="text-xl text-center font-semibold border-gray-700 pb-2">
          CÔNG VIỆC
          <span>( {{ totalTodos }} )</span>
        </h2>
        <button
          @click="addTodo"
          type="button"
          class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5"
        >
          Tạo mới
        </button>
      </div>
      <ul
        class="space-y-2 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-800"
        style="max-height: calc(100vh - 84px - 93px)"
      >
        <li
          v-for="todo in todos"
          :key="todo.id"
          @click="showTodo(todo.id)"
          class="flex justify-between items-center cursor-pointer bg-gray-900 hover:bg-gray-600 p-3 rounded-lg transition duration-150"
        >
          <span
            :class="
              todo.is_completed
                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'
            "
            class="text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm"
          >
            {{ todo.is_completed ? "Đã làm" : "Chưa làm" }}
          </span>

          <span class="block truncate font-medium">{{ todo.title }}</span>
          <button
            @click.stop="deleteTodo(todo.id)"
            type="button"
            class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-1"
          >
            Xóa
          </button>
        </li>
        <p v-if="isLoading">Đang tải dữ liệu.</p>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="w-3/4 p-10 bg-gray-50 overflow-auto">
      <div v-if="selectedTodo" class="space-y-4">
        <div class="bg-white rounded-lg shadow p-6 space-y-2">
          <p class="mb-3 text-xs text-gray-500 dark:text-gray-400">
            Đã tạo: {{ formatToVNTime(selectedTodo.created_at) }} - Đã cập nhật:
            {{ formatToVNTime(selectedTodo.updated_at) }}
          </p>
          <input
            @input="debouncedUpdateTodo(selectedTodo.is_completed)"
            type="text"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
            v-model="selectedTodo.title"
            required
          />

          <textarea
            @input="debouncedUpdateTodo(selectedTodo.is_completed)"
            rows="10"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
            v-model="selectedTodo.description"
          ></textarea>
          <button
            v-if="!selectedTodo.is_completed"
            @click="updateTodo(true)"
            type="button"
            class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800"
          >
            Đánh dấu đã hoàn thành
          </button>
          <button
            v-if="selectedTodo.is_completed"
            @click="updateTodo(false)"
            type="button"
            class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800"
          >
            Đánh dấu chưa hoàn thành
          </button>
        </div>
      </div>
      <div
        v-else
        class="flex items-center justify-center h-full text-gray-500 text-lg"
      ></div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Cookies from "js-cookie";

export default {
  name: "TodoPage",
  data() {
    return {
      todos: [],
      totalTodos: 0,
      selectedTodo: null,
      errorMessage: "",
      debouncedUpdateTodo: null,
      isLoading: false,
    };
  },
  beforeMount() {
    if (!Cookies.get("token")) {
      this.$router.push("/login");
      return;
    }
    this.fetchTodos();
  },
  mounted() {
    this.debouncedUpdateTodo = this.debounce(this.updateTodo, 500);
  },
  methods: {
    formatToVNTime(datetime) {
      const date = new Date(datetime);
      return new Intl.DateTimeFormat("vi-VN", {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        timeZone: "Asia/Ho_Chi_Minh",
        hour12: false,
      }).format(date);
    },
    async fetchTodos() {
      try {
        this.isLoading = true;
        const response = await axios.get(`${this.$baseURL}/todos`, {
          headers: {
            Authorization: `Bearer ${Cookies.get("token")}`,
          },
        });
        this.todos = response.data.data.data;
        this.totalTodos = response.data.data.total;
      } catch (error) {
        this.errorMessage = "Failed to fetch todos.";
      } finally {
        this.isLoading = false;
      }
    },
    async addTodo() {
      try {
        const response = await axios.post(
          `${this.$baseURL}/todos`,
          {
            title: "Untitle Todo",
            description: "Chỉnh sửa chi tiết ở đây nhé !",
            is_completed: false,
          },
          {
            headers: {
              Authorization: `Bearer ${Cookies.get("token")}`,
            },
          }
        );
        // this.fetchTodos();
        this.todos.unshift(response.data.data);
        this.totalTodos++;
      } catch (error) {
        this.errorMessage = "Có lỗi xảy ra khi thêm Todo.";
      }
    },
    async deleteTodo(id) {
      try {
        await axios.delete(`${this.$baseURL}/todos/${id}`, {
          headers: {
            Authorization: `Bearer ${Cookies.get("token")}`,
          },
        });
        // this.fetchTodos();
        this.todos = this.todos.filter((todo) => todo.id !== id);
        this.totalTodos--;
        if (this.selectedTodo?.id === id) this.selectedTodo = null;
      } catch (error) {
        this.errorMessage = "Có lỗi xảy ra khi xóa Todo.";
      }
    },
    async showTodo(id) {
      try {
        const response = await axios.get(`${this.$baseURL}/todos/${id}`, {
          headers: {
            Authorization: `Bearer ${Cookies.get("token")}`,
          },
        });
        if (response.status) {
          this.selectedTodo = response.data.data;
        } else {
          this.errorMessage = "Không tìm thấy Todo.";
        }
      } catch (error) {
        this.errorMessage = "Có lỗi xảy ra khi tải Todo.";
      }
    },
    async updateTodo(isCompleted = false) {
      if (!this.selectedTodo?.id) return;

      try {
        const response = await axios.put(
          `${this.$baseURL}/todos/${this.selectedTodo.id}`,
          {
            title: this.selectedTodo.title,
            description: this.selectedTodo.description,
            is_completed: isCompleted,
          },
          {
            headers: {
              Authorization: `Bearer ${Cookies.get("token")}`,
            },
          }
        );
        this.selectedTodo = response.data.data;
        this.fetchTodos();
        // this.todos[this.selectedTodo.id - 1].title = response.data.data;
      } catch (error) {
        this.errorMessage = "Có lỗi khi cập nhật.";
      }
    },
    debounce(func, delay) {
      let timer;
      return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => {
          func.apply(this, args);
        }, delay);
      };
    },
  },
};
</script>
