<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SISUKMA - Sistem Informasi Survei Kepuasan Masyarakat</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100">

  <!-- Navbar -->
  <header class="bg-gradient-to-r from-blue-700 to-blue-600 shadow-md fixed top-0 left-0 right-0 z-50">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-4 py-3">
      <h1 class="text-xl font-bold text-white tracking-widest">S I S U K M A</h1>
      <nav class="hidden md:flex space-x-6">
        <a href="#" class="text-white hover:text-yellow-200 transition">Beranda</a>
        <a href="#statistik" class="text-white hover:text-yellow-200 transition">Statistik</a>
        <a href="#gallery" class="text-white hover:text-yellow-200 transition">Gallery</a>
        <a href="#login" class="text-white font-semibold hover:text-yellow-200 transition">Login</a>
      </nav>
      <button id="menu-btn" class="md:hidden text-white focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>
    <div id="mobile-menu" class="hidden md:hidden flex flex-col space-y-2 px-4 pb-4 bg-blue-700">
      <a href="#" class="text-white hover:text-yellow-200 transition">Beranda</a>
      <a href="#statistik" class="text-white hover:text-yellow-200 transition">Statistik</a>
      <a href="#gallery" class="text-white hover:text-yellow-200 transition">Gallery</a>
      <a href="#login" class="text-white font-semibold hover:text-yellow-200 transition">Login</a>
    </div>
  </header>
  @yield('content')
     <!-- Footer -->
    <footer class="bg-blue-900 text-blue-100 py-6 text-center">
        <p>© 2025 Bagian Oragnisasi Sekretariat Daerah Kabupaten Bengkalis. All Rights Reserved. <br> Dikembangkan oleh Diskominfotik Kab. Bengkalis</p>
    </footer>
    <script>
      // Toggle mobile menu
      document.getElementById('menu-btn').addEventListener('click', () => {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
      });

      // Hero slider
      const slides = document.querySelectorAll('.slide');
      const dots = document.querySelectorAll('.dot');
      let current = 0;

      function showSlide(index) {
        slides.forEach((slide, i) => {
          slide.style.opacity = i === index ? '1' : '0';
          dots[i].classList.toggle('bg-white', i === index);
          dots[i].classList.toggle('bg-white/50', i !== index);
        });
      }

      dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
          current = i;
          showSlide(current);
        });
      });

      setInterval(() => {
        current = (current + 1) % slides.length;
        showSlide(current);
      }, 5000); // 5 detik

      showSlide(current);
    </script>
    </body>
    
    </html>