<template>
  <div class="flex items-center justify-between mb-3">
    <h1 v-if="!loading" class="text-3xl font-semibold">
      {{ product.id ? `Update product: "${product.title}"` : 'Create new Product' }}
    </h1>
  </div>
  <div class="bg-white rounded-lg shadow animate-fade-in-down">
    <Spinner v-if="loading"
             class="absolute left-0 top-0 bg-white right-0 bottom-0 flex items-center justify-center z-50"/>
    <form v-if="!loading" @submit.prevent="onSubmit">
      <div class="grid grid-cols-3">
        <div class="col-span-2 px-4 pt-5 pb-4">
          <CustomInput class="mb-2" v-model="product.title" label="Product Title" :errors="errors['title']"/>
          <CustomInput type="richtext" class="mb-2" v-model="product.description" label="Description" :errors="errors['description']"/>
          <CustomInput type="file" class="mb-2" v-model="product.images" label="Product Image" :errors="errors['images']"/>
          <div v-for="(myFile, ind) of product.images">
                <div
                    class="group aspect-square bg-blue-100 flex flex-col items-center justify-center text-gray-500 relative border-2"
                    >

                    <button
                        type="button"
                        @click="removeFile(myFile)"
                        class="absolute z-20 right-3 top-3 w-7 h-7 flex items-center justify-center bg-black/30 text-white rounded-full hover:bg-black/40">
                        <TrashIcon class="h-5 w-5"/>
                    </button>

                    <img
                          :src="myFile.url"
                          class="object-contain aspect-square"
                          />
                </div>
            </div>
          <CustomInput type="number" class="mb-2" v-model="product.price" label="Price" prepend="$" :errors="errors['price']"/>
          <CustomInput type="number" class="mb-2" v-model="product.quantity" label="Quantity" :errors="errors['quantity']"/>
          <CustomInput type="checkbox" class="mb-2" v-model="product.published" label="Published" :errors="errors['published']"/>
        </div>

      </div>
      <footer class="bg-gray-50 rounded-b-lg px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <button type="submit"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm
                          text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500">
          Save
        </button>
        <button type="button"
                @click="onSubmit($event, true)"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm
                          text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500">
          Save & Close
        </button>
        <router-link :to="{name: 'app.products'}"
                     class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                     ref="cancelButtonRef">
          Cancel
        </router-link>
      </footer>
    </form>
  </div>
</template>

<script setup>
import {onMounted, ref , computed} from 'vue'
import CustomInput from "../../components/core/CustomInput.vue";
import store from "../../store/index.js";
import Spinner from "../../components/core/Spinner.vue";
import {useRoute, useRouter} from "vue-router";
import axiosClient from "../../axios.js";
import { TrashIcon } from '@heroicons/vue/outline'

const route = useRoute()
const router = useRouter()
const errors = ref({});
const loading = ref(false)
const options = ref([])
const attachmentFiles = ref([]);

const product = ref({
  id: null,
  title: null,
  images: attachmentFiles,
  deleted_images: [],
  image_positions: {},
  description: '',
  price: null,
  quantity: null,
  published: false,
  categories: []
})

  function removeFile(myFile) {
    console.log( product.value.deleted_images);
    product.value.images =product.value.images.filter(f => f !== myFile);
    product.value.deleted_images.push(myFile.id);
  }

  const emit = defineEmits(['update:modelValue', 'close'])

  onMounted(() => {
    if (route.params.id) {
      loading.value = true
      store.dispatch('getProduct', route.params.id)
        .then((response) => {
          loading.value = false;
          product.value = {
            ...response.data,
            deleted_images : product.value.deleted_images || [],
          }
        })
    }
  })

  function onSubmit($event, close = false) {
  loading.value = true
  errors.value = {};
  product.value.quantity = product.value.quantity || null
  if (product.value.id) {
    store.dispatch('updateProduct', product.value)
      .then(response => {
        loading.value = false;
        if (response.status === 200) {
          product.value = response.data
          store.dispatch('getProducts')
          if (close) {
            router.push({name: 'app.products'})
          }
        }
      })
      .catch(err => {
        loading.value = false;
        errors.value = err.response.data.errors
      })
  } else {
    store.dispatch('createProduct', product.value)
      .then(response => {
        loading.value = false;
        if (response.status === 201) {
          product.value = response.data
          store.dispatch('getProducts')
          if (close) {
            router.push({name: 'app.products'})
          } else {
            product.value = response.data
            router.push({name: 'app.products.edit', params: {id: response.data.id}})
          }
        }
      })
      .catch(err => {
        loading.value = false;
        errors.value = err.response.data.errors
      })
  }
}

</script>