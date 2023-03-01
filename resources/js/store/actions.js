import axiosClient from "/resources/js/axios";

export function getBooks({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
  commit('setBooks', [true])
  console.log(url);
  url = url || '/books'
  console.log(url);
  const params = {
    per_page: state.books.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setBooks', [false, response.data])
    })
    .catch(() => {
      commit('setBooks', [false])
    })
}

export function getBook({commit}, id) {
  commit('setBook', [true])
  return axiosClient.get(`/books/${id}`).then((response) => {
    commit('setBook', [false, response.data])
  })
  .catch(() => {
    commit('setBook', [false])
  })
}