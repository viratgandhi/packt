<template>
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-3xl font-semibold">Books</h1>
    <button type="button"
            @click="showAddNewModal()"
            class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      Add new book
    </button>
  </div>
  <BooksTable @clickEdit="editBook"/>
  <BookModal v-model="showBookModal" :book="bookModel" @close="onModalClose"/>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import store from "../../store";
import BookModal from "./BookModal.vue";
import BooksTable from "./BooksTable.vue";

const DEFAULT_BOOK = {
  id: '',
  title: '',
  author: '',
  genre: '',
  description: '',
  isbn:'',
  image: '',
  published_date: '',
  publisher: ''
}

const books = computed(() => store.state.books);

const bookModel = ref({...DEFAULT_BOOK})
const showBookModal = ref(false);

function showAddNewModal() {
  showBookModal.value = true
}

function editBook(p) {
  console.log(p);
  store.dispatch('getBook', p.id)
    .then(({data}) => {
      bookModel.value = data
      showAddNewModal();
    })
}

function onModalClose() {
  bookModel.value = {...DEFAULT_BOOK}
}
</script>

<style scoped>

</style>
