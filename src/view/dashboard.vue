<template>
  <div class="home text-center">
    <div
      class="hero bg-base-100 min-h-screen flex flex-col justify-center items-center"
    >
      <h1 class="text-5xl font-bold mb-6">Welcome to Our Home Page</h1>
      <p class="text-lg mb-4">
        We are excited to have you here! Explore our website to find out more
        about what we do and how we can help you.
      </p>
      <div class="flex flex-col lg:flex-row justify-center items-center">
        <div class="m-4 p-4 max-w-xs bg-white rounded shadow-lg">
          <h2 class="text-3xl font-bold mb-2">Our Mission</h2>
          <p>
            Our mission is to provide high-quality services that exceed our
            customers' expectations. We are committed to continuous improvement
            and innovation.
          </p>
        </div>
        <div class="m-4 p-4 max-w-xs bg-white rounded shadow-lg">
          <h2 class="text-3xl font-bold mb-2">Our Vision</h2>
          <p>
            Our vision is to be the leading company in our industry, known for
            our commitment to quality, customer satisfaction, and
            sustainability.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "HomePage",
};
</script>

<style>
.home {
  background-color: #f0f4f8;
}
.hero {
  padding: 2rem;
}
.btn-primary {
  background-color: #007bff;
  color: white;
}
.btn-primary:hover {
  background-color: #0056b3;
}
</style>
