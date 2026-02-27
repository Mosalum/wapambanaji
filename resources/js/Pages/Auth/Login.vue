<script setup>
import { useForm } from '@inertiajs/vue3'
const form = useForm({ login: '', password: '', remember: true })
const submit = () => form.post('/login')
</script>
<template>
  <div class="min-h-screen grid place-content-center bg-slate-950">
    <form class="w-96 bg-slate-900 p-6 rounded-xl space-y-4" @submit.prevent="submit">
      <h1 class="text-white text-xl">Welcome Back</h1>
      <input v-model="form.login" placeholder="Email or phone" class="w-full rounded bg-slate-800 text-white" />
      <input v-model="form.password" type="password" placeholder="Password" class="w-full rounded bg-slate-800 text-white" />
      <button class="w-full bg-cyan-500 rounded py-2">Login</button>
    </form>
  </div>
</template>
