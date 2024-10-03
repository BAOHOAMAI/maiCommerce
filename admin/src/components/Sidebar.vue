<template>
    <div class="min-w-[160px] w-[160px]  transition-all bg-indigo-700 text-white py-4 px-2">
      <RouterLink :to="{name: 'app.products'}"
                   class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
        <span class="mr-2 text-gray-300">
          <HomeIcon class="w-5"/>
        </span>
        <span class="text-xs">
          Dashboard
        </span>
      </RouterLink>
      <RouterLink :to="{name: 'app.products'}"
                   class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
        <span class="mr-2 text-gray-300">
          <ViewListIcon class="w-5"/>
        </span>
        <span class="text-xs">
          Categories
        </span>
      </RouterLink>
      <RouterLink :to="{name: 'app.products'}"
                   class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
        <span class="mr-2 text-gray-300">
          <ViewListIcon class="w-5"/>
        </span>
        <span class="text-xs">
          Products
        </span>
      </RouterLink>
      <RouterLink :to="{name: 'app.products'}"
                   class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
        <span class="mr-2 text-gray-300">
          <ViewListIcon class="w-5"/>
        </span>
        <span class="text-xs">
          Orders
        </span>
      </RouterLink>
      <RouterLink :to="{name: 'app.products'}"
                   class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
        <span class="mr-2 text-gray-300">
          <UsersIcon class="w-5"/>
        </span>
        <span class="text-xs">
          Users
        </span>
      </RouterLink>
      <RouterLink :to="{name: 'app.products'}"
                   class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
        <span class="mr-2 text-gray-300">
          <UserGroupIcon class="w-5"/>
        </span>
        <span class="text-xs">
          Customers
        </span>
      </RouterLink>
      <RouterLink  :to="{name: 'app.products'}"
                   class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
        <span class="mr-2 text-gray-300">
          <ChartBarIcon class="w-5"/>
        </span>
        <span class="text-xs">
          Reports
        </span>
      </RouterLink>
    </div>
  </template>
  
  <script setup>
    import {HomeIcon, UserGroupIcon, UsersIcon, ViewListIcon, ChartBarIcon} from '@heroicons/vue/outline'
    import { RouterLink, RouterView } from 'vue-router'

  </script>
  
  <style scoped>
  
  </style>