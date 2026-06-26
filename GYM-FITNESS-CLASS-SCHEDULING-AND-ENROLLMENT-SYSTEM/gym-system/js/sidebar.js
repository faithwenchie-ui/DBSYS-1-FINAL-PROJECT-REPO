// Display a temporary toast notification with the given message and optional type.
function showToast(message, type = "info") {
    const containerId = "custom-toast-container";
    let container = document.getElementById(containerId);

    if (!container) {
        container = document.createElement("div");
        container.id = containerId;
        container.className = "toast-container";
        document.body.appendChild(container);
    }

    const toast = document.createElement("div");
    toast.className = `toast toast-${type}`;
    toast.textContent = message;

    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.add("toast-hide");
        setTimeout(() => toast.remove(), 260);
    }, 2800);
}

// Show a custom confirmation dialog before deleting a record.
function showConfirmDelete(event, message, form) {
    event.preventDefault();

    const overlay = document.createElement("div");
    overlay.className = "confirm-overlay";

    const card = document.createElement("div");
    card.className = "confirm-card";

    card.innerHTML = `
        <h3>Confirm action</h3>
        <p>${message}</p>
        <div class="confirm-actions">
            <button type="button" class="action-btn confirm-cancel">Cancel</button>
            <button type="button" class="add-btn confirm-ok">Yes</button>
        </div>
    `;

    overlay.appendChild(card);
    document.body.appendChild(overlay);

    const close = () => overlay.remove();

    card.querySelector(".confirm-cancel").addEventListener("click", close);
    card.querySelector(".confirm-ok").addEventListener("click", () => {
        close();
        if (form && typeof form.submit === "function") {
            form.submit();
        }
    });

    overlay.addEventListener("click", (e) => {
        if (e.target === overlay) close();
    });

    return false;
}

// Show a custom confirmation dialog for navigation actions such as logout.
function showConfirmAction(event, message, url) {
    event.preventDefault();

    const overlay = document.createElement("div");
    overlay.className = "confirm-overlay";

    const card = document.createElement("div");
    card.className = "confirm-card";

    card.innerHTML = `
        <h3>Confirm action</h3>
        <p>${message}</p>
        <div class="confirm-actions">
            <button type="button" class="action-btn confirm-cancel">Cancel</button>
            <button type="button" class="add-btn confirm-ok">Yes</button>
        </div>
    `;

    overlay.appendChild(card);
    document.body.appendChild(overlay);

    const close = () => overlay.remove();

    card.querySelector(".confirm-cancel").addEventListener("click", close);
    card.querySelector(".confirm-ok").addEventListener("click", () => {
        close();
        window.location.href = url;
    });

    overlay.addEventListener("click", (e) => {
        if (e.target === overlay) close();
    });

    return false;
}

// Toggle a sidebar menu section open or closed and save the state in sessionStorage.
function toggleMenu(menuId, titleElement) {

    const menu =
        document.getElementById(menuId);

    if (!menu || !titleElement) return;

    const isHidden =
        window.getComputedStyle(menu)
              .display === "none";

    if (isHidden) {

        menu.style.display = "block";

        titleElement.innerHTML =
            titleElement.innerHTML.replace("▶", "▼");

        sessionStorage.setItem(menuId, "open");

    } else {

        menu.style.display = "none";

        titleElement.innerHTML =
            titleElement.innerHTML.replace("▼", "▶");

        sessionStorage.setItem(menuId, "closed");

    }

}

// Apply a saved open/closed state to a sidebar menu when the page loads.
function applyMenuState(menu, title, state) {
    if (!menu || !title) return;

    if (state === "open") {

        menu.style.display = "block";

    }

    if (window.getComputedStyle(menu).display !== "none") {

        title.innerHTML =
            title.innerHTML.replace("▶", "▼");

    }

}

function setSidebarMenu(menuId) {
    const menus = [
        "memberMenu",
        "classMenu",
        "attendanceMenu",
        "reportsMenu",
        "accountMenu"
    ];

    menus.forEach(id => {
        sessionStorage.setItem(id, id === menuId ? "open" : "closed");
    });
}

document.addEventListener("DOMContentLoaded", () => {

    const menus = [
        "memberMenu",
        "classMenu",
        "attendanceMenu",
        "reportsMenu",
        "accountMenu"
    ];

    menus.forEach(menuId => {

        const menu = document.getElementById(menuId);

        if (!menu) return;

        const title = menu.previousElementSibling;

        const state = sessionStorage.getItem(menuId);

        if (state === "open") {

            menu.style.display = "block";
            title.innerHTML = title.innerHTML.replace("▶", "▼");

        } else {

            menu.style.display = "none";
            title.innerHTML = title.innerHTML.replace("▼", "▶");

        }

    });

    const dashboardLinks = document.querySelectorAll('.dashboard-link[data-menu]');

    dashboardLinks.forEach(link => {
        link.addEventListener('click', () => {
            const menuId = link.dataset.menu;
            if (menuId) {
                setSidebarMenu(menuId);
            }
        });
    });

});