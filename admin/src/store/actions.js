import axiosClient from "../axios";

export function getCurrentUser({commit}, data) {
  return axiosClient.get('/user', data)
    .then(({data}) => {
      commit('setUser', data);
      return data;
    })
}

export function login({commit}, data) {
  return axiosClient.post('/login', data)
    .then(({data}) => {
      commit('setUser', data.user);
      commit('setToken', data.token)
      return data;
    })
}

export function logout({commit}) {
  return axiosClient.post('/logout')
    .then((response) => {
      commit('setToken', null)

      return response;
    })
}

export function getProducts ( { commit }, { url = null, search = '', per_page , sort_field , sort_direction  } = {}) {
  commit('setProduct', [true])
  url = url || 'products'

  return axiosClient.get(url, {
    params: { search, per_page ,  sort_field , sort_direction}
  })
    .then((response) => {
      commit('setProduct', [false, response.data])
    })
    .catch(() => {
      commit('setProduct', [false]) 
    })
}
export function getProduct({commit}, id) {
  return axiosClient.get(`/products/${id}`)

}
export function createProduct ({commit ,state} , data ) {

  const form = new FormData();
  form.append('title' , data.title)
  data.images.forEach(im => form.append('images[]', im.file))
  form.append('description' , data.description)
  form.append('price' , data.price)
  form.append('quantity' , data.quantity)
  data = form;
  return axiosClient.post('products' , data , {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  })

}

export function updateProduct({ commit }, product) {
  // debugger;
  const id = product.id
  if (product.images && product.images.length) {
    const form = new FormData();
    form.append('id', product.id);
    form.append('title', product.title);
    if (product.images) {
      product.images.forEach(im => form.append('images[]', im.file))
    }
    form.append('description', product.description || '');
    if (product.deleted_images) {
      product.deleted_images.forEach((imageId, index) => {
        form.append('deleted_images[]', imageId);
      });
    }    
    form.append('price', product.price);
    form.append('_method', 'PUT');
    product = form;
  } else {
    product._method = 'PUT'
  }
  return axiosClient.post(`/products/${id}`, product)
}

export function deleteProduct({commit} , id) {
    return axiosClient.delete(`/products/${id}`)
}

