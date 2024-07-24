<h2 id="countdown" class ="mt-5 ms-5 mb-2">Login to continue, go to login page in 5 seconds.</h2>

<script>
  let countdown = 5;
  let countdownInterval = setInterval(function() {
    document.getElementById("countdown").innerHTML = `Login to continue, go to login page in ${countdown} seconds.`;
    countdown--;
    if (countdown <= 0) {
      clearInterval(countdownInterval);
      window.location.href = 'login.php';
    }
  }, 1000);
</script>