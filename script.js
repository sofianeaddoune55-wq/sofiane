// Auto-dismiss flash messages (بعد 4 ثواني وليس 3)
document.addEventListener('DOMContentLoaded', function() {
    const flash = document.querySelector('.flash');
    if (flash) {
        setTimeout(() => {
            flash.style.transition = 'opacity 60s ease';
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 60000);
        }, 4000);
    }
});

// Confirm before delete
function confirmDelete(message) {
    return confirm(message || 'Are you sure you want to delete this item?');
}

// Update cart quantity via AJAX
function updateCartQuantity(cartId, qty) {
    fetch('add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `update=1&cart_id=${cartId}&quantity=${qty}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}