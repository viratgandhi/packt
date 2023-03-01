<script setup>
import { computed, onMounted, ref } from "vue";
import store from "/resources/js/store";
import { PRODUCTS_PER_PAGE } from "/resources/js/constants";

const perPage = ref(PRODUCTS_PER_PAGE);
const search = ref("");
const sortField = ref("updated_at");
const sortDirection = ref("desc");

onMounted(() => {
  getBooks();
  return search;
});

function getBooks(url = null) {
  store.dispatch("getBooks", {
    url,
    search: search.value,
    per_page: perPage.value,
    sort_field: sortField.value,
    sort_direction: sortDirection.value,
  });
}

function getForPage(ev, link) {
  ev.preventDefault();
  if (!link.url || link.active) {
    return;
  }

  getBooks(link.url);
}

const books = computed(() => store.state.books);
</script>
<template>
  <div class="container">
    <input
      type="search"
      class="from-control col-3 offset-8 mb-3"
      placeholder="Search by Title, Author, ISBN, or Publisher"
      aria-label="Search"
      v-model="search"
      
    />
    <button @click="getBooks(null)" class="btn btn-primary ml-1">Search</button>
    <div class="row">
      <div class="col-4 pb-4" v-for="(book, key) in books.data">
        <div class="card">
          <img class="card-img-top" :src="book.image_url" alt="Card image cap" />
          <div class="card-body text-center">
            <h5 class="card-title font-bold">{{ book.title }}</h5>
            <span>By</span>
            <p class="card-text">{{ book.author }}</p>
            <router-link
              :to="{ name: 'book.show', params: { id: book.id } }"
              class="btn btn-primary"
              >View Book</router-link
            >
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="offset-3 col-6">
        <nav aria-label="..." v-if="!books.loading">
          <ul class="pagination" v-if="books.total > books.limit">
            <template v-for="(link, i) of books.links" :key="i">
              <li
                class="page-item"
                :class="[link.active ? 'active' : !link.url ? 'disabled' : '']"
              >
                <a
                  :disabled="!link.url"
                  href="#"
                  @click="getForPage($event, link)"
                  aria-current="page"
                  class="page-link"
                  :class="[
                    link.active
                      ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                      : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                    i === 0 ? 'rounded-l-md' : '',
                    i === books.links.length - 1 ? 'rounded-r-md' : '',
                    !link.url ? ' bg-gray-100 text-gray-700' : '',
                  ]"
                  v-html="link.label"
                >
                </a>
              </li>
            </template>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</template>
