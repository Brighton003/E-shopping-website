document.addEventListener('DOMContentLoaded', () => {
    // Add to cart functionality
    const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
    
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', async (e) => {
            const productId = e.target.getAttribute('data-id');
            const qtyInput = document.getElementById(`qty-${productId}`);
            const quantity = qtyInput ? parseInt(qtyInput.value) : 1;
            
            try {
                const response = await fetch('/api/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ product_id: productId, quantity: quantity })
                });
                
                const result = await response.json();
                if(result.success) {
                    document.getElementById('cartCount').textContent = result.cartCount;
                    // Optional: show a toast notification here
                    e.target.textContent = 'Added!';
                    e.target.style.backgroundColor = '#4caf50';
                    setTimeout(() => {
                        e.target.textContent = 'Add to Cart';
                        e.target.style.backgroundColor = '';
                    }, 2000);
                }
            } catch (error) {
                console.error('Error adding to cart', error);
            }
        });
    });

    // Remove from cart functionality
    const removeBtns = document.querySelectorAll('.remove-item-btn');
    removeBtns.forEach(btn => {
        btn.addEventListener('click', async (e) => {
            const productId = e.target.getAttribute('data-id');
            
            try {
                const response = await fetch('/api/cart/remove', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ product_id: productId })
                });
                
                const result = await response.json();
                if(result.success) {
                    // Remove row from UI
                    document.getElementById(`cart-row-${productId}`).remove();
                    // Basic page reload to recalculate totals, or calculate in JS
                    window.location.reload(); 
                }
            } catch(error) {
                console.error('Error removing from cart', error);
            }
        });
    });

    // Search Live AJAX
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        // Implement debounced search dropdown if needed
    }
});
