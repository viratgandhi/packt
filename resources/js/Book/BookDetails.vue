<script setup>
import { useRouter, useRoute } from 'vue-router'
import {computed, onMounted, ref} from "vue";
import store from "/resources/js/store";

const router = useRouter()
const route = useRoute()

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

onMounted(() => {
  console.log("mounted:"+route.params.id);
  getBook(route.params.id);
})

function getBook(id) {
  console.log("getBook:"+id);
  store.dispatch("getBook",id);
}

const book = computed(() => store.state.book.data);
</script>

<template>

<div class="card mb-3" >
  <div class="row g-0">
    <div class="col-md-6">
      <!-- <img src="http://placeimg.com/480/640/any" class="img-fluid rounded-start" alt="..."> -->
      <img :src="book.image" class="img-fluid rounded-start" alt="...">
    </div>
    <div class="col-md-6">
      <div class="card-body">
        <h5 class="card-title">Book Title: {{ book.title }}</h5>
        <p class="card-text"><strong>Description:</strong> {{ book.description }}</p>
        <p class="card-text"><strong>Author:</strong> {{ book.author }}</p>
        <p class="card-text"><strong>Genre:</strong> {{ book.genre }}</p>
        <p class="card-text"><strong>ISBN:</strong> {{ book.isbn }}</p>
        <p class="card-text"><strong>Published:</strong> {{ book.published_date }}</p>
        <p class="card-text"><strong>Publisher:</strong> {{ book.publisher }}</p>
      </div>
    </div>
  </div>
</div>

</template>
