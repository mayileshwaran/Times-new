$(document).ready(function(){
    $('.tabs-nav li').click(function(){
      var tab_id = $(this).data('tab');

      $('.tabs-nav li').removeClass('active');
      $('.tab-content').removeClass('active');

      $(this).addClass('active');
      $("#" + tab_id).addClass('active');
    });
  });
  $(document).ready(function () {
    $('.set-heading').click(function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active')
            $(this).siblings('.set-container').slideUp(500);
            $('.set-heading span i').removeClass('fa-minus').addClass('fa-plus');
        }
        else {
            $('.set-heading span i').removeClass('fa-minus').addClass('fa-plus');
            $(this).find('i').removeClass('fa-plus').addClass('fa-minus');

            $('.set-heading').removeClass('active');
            $(this).addClass('active');

            $('.set-container').slideUp(500);
            $(this).siblings('.set-container').slideDown(500)
        }
    })
});
$(document).ready(function(){
    let carouselItem=$('.carousel-item')
    carouselItem.first().addClass('active');
    
    let currIndex=0;
    let carouselLength=carouselItem.length;
    carouselItem.first().addClass('active')

    function showItem(currInd){
        carouselItem.removeClass('active')
        carouselItem.eq(currInd).addClass('active');
    }
    $('.carousel-prev-indicator').click(function(){
        currIndex=(currIndex-1)% carouselLength;
        showItem(currIndex);
    })
    $('.carousel-next-indicator').click(function(){
        currIndex=(currIndex+1)% carouselLength;
        showItem(currIndex);
    })
    function autoplay(){
         currIndex=(currIndex+1) % carouselItem.length;
        currIndex=(currIndex+1) % carouselLength;
        showItem(currIndex);
    }
    setInterval(autoplay,10000)

})
$(document).ready(function () {
  const future = new Date("2025-08-31T23:59:59");

  function updateCountdown() {
    const currentTime = new Date().getTime();
    const distance = future.getTime() - currentTime;

    if (distance <= 0) {
      $('#days, #hours, #minutes, #seconds').text('00');
      return;
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    $('#days').text(String(days).padStart(2, '0'));
    $('#hours').text(String(hours).padStart(2, '0'));
    $('#minutes').text(String(minutes).padStart(2, '0'));
    $('#seconds').text(String(seconds).padStart(2, '0'));
  }

  updateCountdown();
  setInterval(updateCountdown, 1000);
});

    $(document).ready(function(){
            $('.open-popup').click(function(){
                $('#popup').fadeIn();
            });
            $('#confirm').click(function(){
                alert('confirmed');
                $('#popup').fadeOut();
            })
            $('#cancel').click(function(){
              
                $('#popup').fadeOut();
            })
        });   

//Form validation
document.getElementById("contactForm").addEventListener("submit", function (e) {
  const name = document.getElementById("name").value.trim();
  const email = document.getElementById("email").value.trim();
  const phone = document.getElementById("phone").value.trim();
  const message = document.getElementById("message").value.trim();

  const emailRegex = /^[a-zA-Z][\w.-]*@[a-zA-Z]+\.[a-zA-Z]{2,}$/;
  const phoneRegex = /^[6-9]\d{9}$/;

  if (!name) {
    alert("Please enter your name.");
    e.preventDefault();
    return;
  }

  if (!emailRegex.test(email)) {
    alert("Please enter a valid email that starts with a letter.");
    e.preventDefault();
    return;
  }

  if (!phoneRegex.test(phone)) {
    alert("Please enter a valid 10-digit phone number starting with 6-9.");
    e.preventDefault();
    return;
  }

  if (!message) {
    alert("Please enter your message.");
    e.preventDefault();
    return;
  }
});
