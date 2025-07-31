  document.addEventListener('DOMContentLoaded', function () {
    const profileIcon = document.querySelector('.profile-icon');
    const dropdown = document.querySelector('.dropdown');

    profileIcon.addEventListener('click', function (e) {
      e.stopPropagation(); // Prevent closing when clicking the icon
      dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });

    document.addEventListener('click', function () {
      dropdown.style.display = 'none'; // Close dropdown on outside click
    });
  });
    function toggleDropdown() {
          const menu = document.getElementById('dropdownMenu');
          menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }

        window.onclick = function(event) {
          if (!event.target.matches('.dropdown-toggle')) {
            const dropdown = document.getElementById('dropdownMenu');
            if (dropdown && dropdown.style.display === 'block') {
              dropdown.style.display = 'none';
            }
          }
        }