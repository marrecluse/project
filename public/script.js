// Simple confirmation message on delete
document.addEventListener('DOMContentLoaded', function () {
  const deleteLinks = document.querySelectorAll('a[href*="delete_album"]');
  deleteLinks.forEach(link => {
    link.addEventListener('click', function (e) {
      if (!confirm('Are you sure you want to delete this album?')) {
        e.preventDefault();
      }
    });
  });
});


// showing snackbar for errors
window.addEventListener('DOMContentLoaded', () => {
  const snackbar = document.getElementById("snackbar");
  const urlParams = new URLSearchParams(window.location.search);

  if (!snackbar) return;

  let message = "";
  let type = ""; // success, error, warning

  if (urlParams.get("error") === "invalid") {
    message = "❌ Incorrect password.";
    type = "error";
  } else if (urlParams.get("error") === "user-not-found") {
    message = "❌ User not found.";
    type = "error";
  } else if (urlParams.get("deleted") === "1") {
    message = "✅ Album deleted successfully.";
    type = "success";
  } else if (urlParams.get("deleted") === "0") {
    message = "⚠️ Album not found or already deleted.";
    type = "warning";
  }

  if (message) {
    snackbar.textContent = message;
    snackbar.className = `show ${type}`;
    setTimeout(() => snackbar.className = snackbar.className.replace(`show ${type}`, ""), 3000);
  }
});
