<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
defineProps({ member: Object })
</script>
<template><AppLayout><h1>{{ member.full_name }}</h1></AppLayout></template>
