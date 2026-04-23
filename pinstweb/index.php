<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PINST</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    #nav-logo { padding-left: 0 !important; }
    #nav-menu { padding-right: 0 !important; }
    @media (min-width: 768px) {
      #nav-logo { padding-left: 200px !important; }
      #nav-menu { padding-right: 200px !important; }
    }
  </style>
</head>
<body class="bg-white pt-[50px]">

  <?php include 'sections/nav/nav.php'; ?>
  <?php include 'sections/banner/banner.php'; ?>
  <?php include 'sections/introduce1/introduce1.php'; ?>
  <?php include 'sections/introduce2/introduce2.php'; ?>
  <?php include 'sections/introduce3/introduce3.php'; ?>

  <script>
    function setNavPadding() {
      var logo = document.getElementById('nav-logo');
      var menu = document.getElementById('nav-menu');
      if (window.innerWidth >= 768) {
        logo.style.paddingLeft = '200px';
        menu.style.paddingRight = '200px';
      } else {
        logo.style.paddingLeft = '0';
        menu.style.paddingRight = '0';
      }
    }
    setNavPadding();
    window.addEventListener('resize', setNavPadding);

    document.getElementById('menu-toggle').addEventListener('click', function () {
      document.getElementById('nav-menu').classList.toggle('hidden');
    });

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          e.preventDefault();
          const offset = 50;
          const top = target.getBoundingClientRect().top + window.scrollY - offset;
          window.scrollTo({ top, behavior: 'smooth' });
        }
      });
    });
  </script>

</body>
</html>
