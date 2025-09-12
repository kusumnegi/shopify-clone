
window.addEventListener("load", function () {
  const preloader = document.getElementById("preloader");
  const content = document.getElementById("nav-content");

  // Optional delay to avoid instant disappearance
  setTimeout(() => {
    preloader.style.display = "none";
    content.style.display = "block";
  }, 1000); // 500ms delay (optional)
});




// products video
function loadVideo(container, videoId) {
  const iframe = document.createElement('iframe');
  iframe.width = "100%";
  iframe.height = "615";
  iframe.src = "https://www.youtube.com/embed/" + videoId + "?autoplay=1";
  iframe.title = "YouTube video player";
  iframe.frameBorder = "0";
  iframe.allow = "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share";
  iframe.allowFullscreen = true;

  container.parentNode.replaceChild(iframe, container);
}


//youtube video
function playVideo(videoId) {
  const container = document.getElementById('videoBox');
  container.innerHTML = `
        <iframe src="https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&mute=0"
                allow="autoplay; encrypted-media" allowfullscreen></iframe>
      `;
}