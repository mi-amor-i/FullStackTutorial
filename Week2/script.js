document.addEventListener('DOMContentLoaded', () => {
  const video = document.getElementById('hero-video');
  const videoBtn = document.getElementById('toggle-video');

  videoBtn.addEventListener('click', () => {
    if (video.paused) {
      video.play();
      videoBtn.textContent = 'Pause Video';
    } else {
      video.pause();
      videoBtn.textContent = 'Play Video';
    }
  });


  // Handle Audio Play/Pause Here
  const audio = document.getElementById('sample-audio');
  const audioBtn = document.getElementById('toggle-audio');

  audioBtn.addEventListener('click', () => {
    if (audio.paused) {
      audio.play();
      audioBtn.textContent = 'Pause Audio';
    } else {
      audio.pause();
      audioBtn.textContent = 'Play Audio';
    }
  });
  const canvas = document.getElementById('drawing-canvas');
  const ctx = canvas.getContext('2d');
  let isDrawing = false;
  let currentColor = '#000000';

  document.querySelectorAll('.color-picker button').forEach(btn => {
    btn.addEventListener('click', (e) => {
      currentColor = e.target.dataset.color;
      ctx.strokeStyle = currentColor;
    });
  });

  canvas.addEventListener('mousedown', (e) => {
    isDrawing = true;
    ctx.beginPath();
    ctx.moveTo(e.offsetX, e.offsetY);
  });

  canvas.addEventListener('mousemove', (e) => {
    if (isDrawing) {
      ctx.lineWidth = 6;
      ctx.lineCap = 'round';
      ctx.strokeStyle = currentColor;
      ctx.lineTo(e.offsetX, e.offsetY);
      ctx.stroke();
    }
  });

  canvas.addEventListener('mouseup', () => isDrawing = false);
  canvas.addEventListener('mouseout', () => isDrawing = false);


  document.getElementById('clear-canvas').addEventListener('click', () => {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
  });


  let cardCount = 7;

  // Create a separate function for Event Handler

  document.getElementById('add-card').addEventListener('click', addCard);

  function addCard(){
    const container = document.querySelector('.grid-container');
    const newCard = document.createElement('div');
    newCard.className = 'card';
    newCard.innerHTML = `Card ${cardCount}<br><small>Added dynamically!</small>`;
    container.appendChild(newCard);
    cardCount++;
  }
});