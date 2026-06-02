<template>
  <div class="sidebar">
    <h2>PAD</h2>

    <nav>
      <RouterLink to="/">Dashboard</RouterLink>
      <RouterLink to="/transactions">Transactions</RouterLink>
      <RouterLink to="/budgets">Budgets</RouterLink>
      <RouterLink to="/goals">Goals</RouterLink>
    </nav>
  </div>
</template>

<style scoped>
.sidebar {
  width: 220px;
  background: #111827;
  color: white;
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 10px;
  height: 100vh;
}

nav {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

a {
  color: white;
  text-decoration: none;
  opacity: 0.8;
}

a:hover {
  opacity: 1;
}
</style>