document.getElementById('add-more-link').addEventListener('click', () => {
  const container = document.getElementById('link-fields');
  const html = `
    <div class="row mb-2">
      <div class="col-md-5">
        <input type="text" name="link_text[]" class="form-control" placeholder="Link Text" required>
      </div>
      <div class="col-md-5">
        <input type="text" name="link_url[]" class="form-control" placeholder="Link URL" required>
      </div>
      <div class="col-md-2">
        <button type="button" class="btn btn-danger remove-link">Remove</button>
      </div>
    </div>`;
  container.insertAdjacentHTML('beforeend', html);
});

document.addEventListener('click', function (e) {
  if (e.target.classList.contains('remove-link')) {
    e.target.closest('.row').remove();
  }
});