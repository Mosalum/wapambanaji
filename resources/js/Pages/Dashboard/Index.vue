<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
defineProps({ cards: Object, myMemberId: Number })
</script>
<template>
  <AppLayout>
    <h2 class="text-2xl font-semibold">Group Health Score: 83%</h2>
    <div class="grid grid-cols-3 gap-4 mt-4">
      <div class="bg-slate-900 rounded-xl p-4">Members: {{ cards.members }}</div>
      <div class="bg-slate-900 rounded-xl p-4">Contributions: {{ cards.contributions }}</div>
      <div class="bg-slate-900 rounded-xl p-4">Active Loans: {{ cards.active_loans }}</div>
    </div>
    <div class="bg-slate-900 rounded-xl p-4 mt-6">My Member Account ID: {{ myMemberId ?? 'Not linked' }}</div>
  </AppLayout>
</template>
