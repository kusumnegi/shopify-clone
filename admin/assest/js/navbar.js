document.getElementById('add-link-btn').addEventListener('click', function () {
    const container = document.getElementById('nav-links-container');
    const index = container.children.length;
    const div = document.createElement('div');
    div.classList.add('nav-link-row');
    div.innerHTML = `
        <div class="d-flex align-items-center mb-2">
            <input type="text" class="form-control me-2" name="nav_name[]" placeholder="Link Name" required />
            <input type="url" class="form-control me-2" name="nav_url[]" placeholder="Link URL" required />
            <button type="button" class="btn btn-danger btn-sm delete-link-btn">&times;</button>
        </div>
        <div class="submenu-wrapper"></div>
        <button type="button" class="btn btn-sm btn-outline-primary add-submenu mt-2" data-index="${index}">+ Add Submenu</button>
    `;
    container.appendChild(div);
});

document.getElementById('nav-links-container').addEventListener('click', function (event) {
    if (event.target.classList.contains('delete-link-btn')) {
        event.target.closest('.nav-link-row').remove();
    }

    if (event.target.classList.contains('add-submenu')) {
        const index = event.target.getAttribute('data-index');
        const wrapper = event.target.previousElementSibling;
        const subDiv = document.createElement('div');
        subDiv.classList.add('d-flex', 'submenu-item');
        subDiv.innerHTML = `
            <input type="text" class="form-control me-2" name="sub_name[${index}][]" placeholder="Submenu Name" />
            <input type="url" class="form-control me-2" name="sub_url[${index}][]" placeholder="Submenu URL" />
            <button type="button" class="btn btn-outline-danger btn-sm remove-submenu">&times;</button>
        `;
        wrapper.appendChild(subDiv);
    }

    if (event.target.classList.contains('remove-submenu')) {
        event.target.closest('.submenu-item').remove();
    }
});