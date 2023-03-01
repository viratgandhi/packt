
export function setToken(state, token) {
  state.user.token = token;
  if (token) {
    sessionStorage.setItem('TOKEN', token);
  } else {
    sessionStorage.removeItem('TOKEN')
  }
}

export function setBooks(state, [loading, data = null]) {

  if (data) {
    state.books = {
      ...state.books,
      data: data.data,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
    }
  }
  state.books.loading = loading;
}

export function setBook(state, [loading, data = null]) {
console.log(data);
  if (data) {
    state.book = {
      ...state.book,
      data: data,
      // links: data.meta?.links,
      // page: data.meta.current_page,
      // limit: data.meta.per_page,
      // from: data.meta.from,
      // to: data.meta.to,
      // total: data.meta.total,
    }
  }
  state.book.loading = loading;
}

export function showToast(state, message) {
  state.toast.show = true;
  state.toast.message = message;
}

export function hideToast(state) {
  state.toast.show = false;
  state.toast.message = '';
}
