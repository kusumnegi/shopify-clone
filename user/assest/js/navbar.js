document.addEventListener("DOMContentLoaded", () => {
  // 🔍 Toggle search input visibility
  const toggleSearch = (toggleId, boxId) => {
    const toggleBtn = document.getElementById(toggleId);
    const box = document.getElementById(boxId);
    if (!toggleBtn || !box) return;

    toggleBtn.addEventListener("click", (e) => {
      e.preventDefault();
      const isHidden = getComputedStyle(box).display === "none";
      box.style.display = isHidden ? "block" : "none";

      const input = box.querySelector("input");
      if (isHidden && input) input.focus();
    });
  };

  toggleSearch("searchToggleMobile", "searchBoxMobile");
  toggleSearch("searchToggleDesktop", "searchBoxDesktop");

  const searchBoxes = [
    { input: "#searchBoxMobile input", wrapper: "#searchWrapperMobile" },
    { input: "#searchBoxDesktop input", wrapper: "#searchWrapperDesktop" }
  ];

  searchBoxes.forEach(({ input, wrapper }) => {
    const inputEl = document.querySelector(input);
    const wrapperEl = document.querySelector(wrapper);
    if (!inputEl || !wrapperEl) return;

    // Suggestion box
    const suggestionBox = document.createElement("div");
    suggestionBox.className = "search-suggestions shadow-sm";
    Object.assign(suggestionBox.style, {
      position: "absolute",
      top: "100%",
      left: "0",
      right: "0",
      background: "#fff",
      border: "1px solid #ddd",
      borderRadius: "5px",
      zIndex: "9999",
      maxHeight: "250px",
      overflowY: "auto",
      display: "none"
    });
    wrapperEl.appendChild(suggestionBox);

    // Handle typing
    inputEl.addEventListener("input", async () => {
      const query = inputEl.value.trim();
      if (query.length < 1) {
        suggestionBox.style.display = "none";
        return;
      }

      try {
        // ✅ adjust path if needed depending on where header is included
        const res = await fetch(`../search.php?q=${encodeURIComponent(query)}`);
        if (!res.ok) throw new Error(`HTTP error ${res.status}`);

        const data = await res.json();
        if (!Array.isArray(data) || data.length === 0) {
          suggestionBox.style.display = "none";
          return;
        }

        suggestionBox.innerHTML = data
          .map(
            (p) => `
              <a href="product.php?id=${p.id}" 
                 class="d-flex align-items-center p-2 text-dark text-decoration-none">
                <img src="../admin/uploads/products/${p.image}" 
                     alt="${p.title}" 
                     style="width:40px;height:40px;object-fit:cover;" 
                     class="me-2 rounded">
                <span>${p.title}</span>
              </a>`
          )
          .join("");

        suggestionBox.style.display = "block";
      } catch (err) {
        console.error("Search error:", err);
        suggestionBox.style.display = "none";
      }
    });

    // Hide on blur
    inputEl.addEventListener("blur", () => {
      setTimeout(() => (suggestionBox.style.display = "none"), 200);
    });

    // Enter key → redirect
    inputEl.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        const val = inputEl.value.trim();
        if (val) {
          window.location.href = `../search-results.php?q=${encodeURIComponent(val)}`;
        }
      }
    });
  });
});
