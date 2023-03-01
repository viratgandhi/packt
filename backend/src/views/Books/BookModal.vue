<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <TransitionRoot as="template" :show="show">
    <Dialog as="div" class="relative z-10" @close="show = false">
      <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                       leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-black bg-opacity-70 transition-opacity"/>
      </TransitionChild>

      <div class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
          <TransitionChild as="template" enter="ease-out duration-300"
                           enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                           enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                           leave-from="opacity-100 translate-y-0 sm:scale-100"
                           leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <DialogPanel
              class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-[700px] sm:w-full">
              <Spinner v-if="loading"
                       class="absolute left-0 top-0 bg-white right-0 bottom-0 flex items-center justify-center"/>
              <header class="py-3 px-4 flex justify-between items-center">
                <DialogTitle as="h3" class="text-lg leading-6 font-medium text-gray-900">
                  {{ book.id ? `Update book: "${props.book.title}"` : 'Create new Book' }}
                </DialogTitle>
                <button
                  @click="closeModal()"
                  class="w-8 h-8 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-[rgba(0,0,0,0.2)]"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"
                    />
                  </svg>
                </button>
              </header>
              <form @submit.prevent="onSubmit">
                <div class="p-3">
                  <div v-if="errorMsg" class="px-4 pt-5 pb-4 bg-red-500 text-white rounded mb-2">
                    <ul>
                      <li class="" v-for="msg in errorMsg">{{ msg[0] }}</li>
                    </ul>
                  </div>
                  <CustomInput class="mb-2" v-model="book.title" label="Book Title"/>
                  <CustomInput type="text" class="mb-2" v-model="book.author" label="Author"/>
                  <CustomInput type="text" class="mb-2" v-model="book.genre" label="Genre"/>
                  <CustomInput type="textarea" class="mb-2" v-model="book.description" label="Description"/>
                  <CustomInput type="text" class="mb-2" v-model="book.isbn" label="isbn"/>
                  <div class="row" v-if="changeBookImage">
                    <img class="col-auto" :src="book.image" style="width:30%"/> 
                    <button type="button" class="col-auto mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm
                          text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500" @click="changeImage()">Change Image</button>
                  </div>
                  <CustomInput v-else type="file" class="mb-2" label="Book Image" @change="file => book.image = file"/>
                  <CustomInput type="text" class="mb-2" v-model="book.publisher" label="publisher"/>
                  <CustomInput type="text" class="mb-2" v-model="book.published_date" label="Published Date"/>
                </div>
                <footer class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                  <button type="submit"
                          class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm
                          text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500">
                    Submit
                  </button>
                  <button type="button"
                          class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                          @click="closeModal" ref="cancelButtonRef">
                    Cancel
                  </button>
                </footer>
              </form>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import {computed, onUpdated, ref, toRefs} from 'vue'
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {ExclamationIcon} from '@heroicons/vue/outline'
import CustomInput from "../../components/core/CustomInput.vue";
import store from "../../store/index.js";
import Spinner from "../../components/core/Spinner.vue";
import { processSlotOutlet } from '@vue/compiler-core';

const book = ref({
  id: props.book.id,
  title: props.book.title,
  author: props.book.author,
  genre: props.book.genre,
  description: props.book.description,
  isbn: props.book.isbn,
  image: props.book.image,
  published_date: props.book.published_date,
  publisher: props.book.publisher
})

const loading = ref(false)
let errorMsg = ref("");
let showImage = ref(props.book.image)

const props = defineProps({
  modelValue: Boolean,
  book: {
    required: true,
    type: Object,
  },
})

const emit = defineEmits(['update:modelValue', 'close'])

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
})

const changeBookImage = computed({
  get: () => showImage.value,
  set: (value) => {
    showImage.value = value
  }
})
onUpdated(() => {
  book.value = {
    id: props.book.id,
    title: props.book.title,
    author: props.book.author,
    genre: props.book.genre,
    description: props.book.description,
    isbn: props.book.isbn,
    image: props.book.image,
    published_date: props.book.published_date,
    publisher: props.book.publisher
  }
  console.log(props.book.image);
  if(props.book.image) {
    changeBookImage.value = true;
  } else {
    changeBookImage.value = false;
  }
})

function closeModal() {
  show.value = false
  errorMsg.value = ""
  emit('close')
}

function changeImage() {
  changeBookImage.value = false;
}

function onSubmit() {
  loading.value = true
  if (book.value.id) {
    store.dispatch('updateBook', book.value)
      .then(response => {
        loading.value = false;
        if (response.status === 200) {
          // TODO show notification
          store.dispatch('getBooks')
          closeModal()
        }
      })
      .catch(err => {
        loading.value = false;
        errorMsg.value = err.response.data.errors;
      })
  } else {
    store.dispatch('createBook', book.value)
      .then(response => {
        loading.value = false;
        if (response.status === 201) {
          // TODO show notification
          store.dispatch('getBooks')
          closeModal()
        }
      })
      .catch(err => {
        loading.value = false;
        errorMsg.value = err.response.data.errors;
      })
  }
} 
</script>
