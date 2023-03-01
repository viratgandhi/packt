import { createRouter, createWebHistory } from 'vue-router'
import BookListView from '/resources/js/Book/BookList.vue'
import BookDetails from '/resources/js/Book/BookDetails.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'books',
      component: () => BookListView
    },
    {
      path: '/book-details/:id',
      name: 'book.show',
      component: () => BookDetails
    }
  ]
})

export default router
