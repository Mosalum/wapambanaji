<template>
  <div class="min-h-screen bg-slate-950 text-slate-100">
    <aside class="w-64 fixed inset-y-0 bg-slate-900 p-4"> 
      <h1 class="font-bold text-cyan-400">VICOBA</h1>
      <nav class="mt-6 space-y-2 text-sm">
        <a href="/dashboard">Dashboard</a>
        <p class="text-slate-400 mt-4">Operations</p>
        <a href="/members">Members</a>
        <p class="text-slate-400 mt-4">My Account</p>
        <a href="/my/contributions">My Contributions</a>
      </nav>
    </aside>
    <main class="ml-64 p-6"><slot /></main>
  </div>
</template>
