// assets/js/main.js

document.addEventListener('DOMContentLoaded', () => {
    // Basic confirmation dialogs
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (!confirm('Are you sure you want to perform this action?')) {
                e.preventDefault();
            }
        });
    });

    // Booking Price Calculator
    const checkInInput = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    const priceDisplay = document.getElementById('total_price_display');
    const priceInput = document.getElementById('total_price');
    const roomPriceElement = document.getElementById('room_price');

    if (checkInInput && checkOutInput && priceDisplay && roomPriceElement) {
        const roomPrice = parseFloat(roomPriceElement.dataset.price);

        const calculatePrice = () => {
            const checkInDate = new Date(checkInInput.value);
            const checkOutDate = new Date(checkOutInput.value);

            if (checkInDate && checkOutDate && checkOutDate > checkInDate) {
                const diffTime = Math.abs(checkOutDate - checkInDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const total = diffDays * roomPrice;
                
                priceDisplay.textContent = '$' + total.toFixed(2);
                if (priceInput) priceInput.value = total.toFixed(2);
            } else {
                priceDisplay.textContent = '$0.00';
                if (priceInput) priceInput.value = '0.00';
            }
        };

        checkInInput.addEventListener('change', calculatePrice);
        checkOutInput.addEventListener('change', calculatePrice);
    }

    // Room Filtering (Live Search)
    const searchInput = document.getElementById('search_rooms');
    const roomCards = document.querySelectorAll('.room-card');

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            roomCards.forEach(card => {
                const title = card.querySelector('.room-title').textContent.toLowerCase();
                if (title.includes(query)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        setTimeout(() => {
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    }
});
