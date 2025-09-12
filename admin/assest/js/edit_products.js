function showSizes() {
    const type = document.getElementById('product-type').value;
    const shoeSizes = ['5', '6', '7', '8', '9', '10', '11', '12'];
    const clothSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Free'];
    const container = document.getElementById('size-options');
    container.innerHTML = '';
    let currentSizes = (document.getElementById('selected-sizes').value || '').split(',');

    const sizes = type === 'shoes' ? shoeSizes : (type === 'cloth' ? clothSizes : []);
    sizes.forEach(size => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-outline-primary btn-sm me-2 mb-2';
        btn.textContent = size;
        btn.dataset.selected = currentSizes.includes(size) ? 'true' : 'false';
        if (btn.dataset.selected === 'false') {
            btn.classList.remove('btn-outline-primary');
            btn.classList.add('btn-outline-danger');
            btn.innerHTML = size + ' ❌';
        }

        btn.onclick = function () {
            if (btn.dataset.selected === 'true') {
                btn.dataset.selected = 'false';
                btn.classList.remove('btn-outline-primary');
                btn.classList.add('btn-outline-danger');
                btn.innerHTML = size + ' ❌';
            } else {
                btn.dataset.selected = 'true';
                btn.classList.remove('btn-outline-danger');
                btn.classList.add('btn-outline-primary');
                btn.innerHTML = size;
            }
            updateSizeInput();
        };

        container.appendChild(btn);
    });
    updateSizeInput();
}

function updateSizeInput() {
    const buttons = document.querySelectorAll('#size-options button[data-selected="true"]');
    const sizes = Array.from(buttons).map(btn => btn.textContent.replace(' ❌', '').trim());
    document.getElementById('selected-sizes').value = sizes.join(',');
}

window.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('product-type').value) {
        showSizes();
    }
});
