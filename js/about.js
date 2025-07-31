function updateTime() {
  const now = new Date();

  const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
  const day = days[now.getDay()];

  let hours = now.getHours();
  const minutes = String(now.getMinutes()).padStart(2, '0');
  const seconds = String(now.getSeconds()).padStart(2, '0');

  const ampm = hours >= 12 ? 'PM' : 'AM';
  hours = hours % 12;
  hours = hours ? hours : 12; // Convert 0 to 12

  const time = `${String(hours).padStart(2, '0')}:${minutes}:${seconds} ${ampm}`;

  const date = now.toLocaleDateString('en-GB', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  });

  document.getElementById('day').textContent = day;
  document.getElementById('clock').textContent = time;
  document.getElementById('date').textContent = date;
}

setInterval(updateTime, 1000);
updateTime();
