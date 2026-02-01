document.addEventListener('DOMContentLoaded', function() {
    const roomsContainer = document.getElementById('roomsContainer');
    const roomCards = Array.from(document.querySelectorAll('.room-card'));
    const scrollLeftBtn = document.getElementById('scrollLeft');
    const scrollRightBtn = document.getElementById('scrollRight');
    const filterPrice = document.getElementById('filterPrice');
    const filterCapacity = document.getElementById('filterCapacity');
    const sortBy = document.getElementById('sortBy');
    const resetFilters = document.getElementById('resetFilters');
    const roomsCount = document.getElementById('roomsCount');
    
    // Store original room cards for reset
    const originalRoomCards = roomCards.map(card => card.outerHTML);
    
    // Scroll functionality
    scrollLeftBtn.addEventListener('click', () => {
        roomsContainer.scrollBy({ left: -350, behavior: 'smooth' });
    });
    
    scrollRightBtn.addEventListener('click', () => {
        roomsContainer.scrollBy({ left: 350, behavior: 'smooth' });
    });
    
    // Filter and sort function
    function filterAndSortRooms() {
        const priceFilter = filterPrice.value;
        const capacityFilter = filterCapacity.value;
        const sortValue = sortBy.value;
        
        // Filter rooms
        let filteredRooms = roomCards.filter(card => {
            const price = parseFloat(card.dataset.price);
            const capacity = parseInt(card.dataset.capacity);
            
            // Price filter
            let priceMatch = true;
            if (priceFilter !== 'all') {
                const [min, max] = priceFilter.split('-').map(Number);
                if (max === undefined) {
                    priceMatch = price <= min;
                } else {
                    priceMatch = price >= min && price <= max;
                }
            }
            
            // Capacity filter
            let capacityMatch = true;
            if (capacityFilter !== 'all') {
                const capValue = parseInt(capacityFilter);
                if (capValue === 4) {
                    capacityMatch = capacity >= 4;
                } else {
                    capacityMatch = capacity === capValue;
                }
            }
            
            return priceMatch && capacityMatch;
        });
        
        // Sort rooms
        filteredRooms.sort((a, b) => {
            const priceA = parseFloat(a.dataset.price);
            const priceB = parseFloat(b.dataset.price);
            const capacityA = parseInt(a.dataset.capacity);
            const capacityB = parseInt(b.dataset.capacity);
            const nameA = a.dataset.name.toLowerCase();
            const nameB = b.dataset.name.toLowerCase();
            
            switch(sortValue) {
                case 'name_asc':
                    return nameA.localeCompare(nameB);
                case 'name_desc':
                    return nameB.localeCompare(nameA);
                case 'price_low':
                    return priceA - priceB;
                case 'price_high':
                    return priceB - priceA;
                case 'capacity_low':
                    return capacityA - capacityB;
                case 'capacity_high':
                    return capacityB - capacityA;
                default:
                    return 0;
            }
        });
        
        // Clear container
        roomsContainer.innerHTML = '';
        
        // Add filtered and sorted rooms
        if (filteredRooms.length === 0) {
            roomsContainer.innerHTML = '<div class="no-rooms-message"><p>No rooms match your filters.</p></div>';
        } else {
            filteredRooms.forEach(card => {
                roomsContainer.appendChild(card);
            });
        }
        
        // Update counter
        roomsCount.textContent = filteredRooms.length;
        
        // Show/hide scroll buttons based on content
        updateScrollButtons();
    }
    
    // Update scroll buttons visibility
    function updateScrollButtons() {
        const hasHorizontalScroll = roomsContainer.scrollWidth > roomsContainer.clientWidth;
        scrollLeftBtn.style.display = hasHorizontalScroll ? 'flex' : 'none';
        scrollRightBtn.style.display = hasHorizontalScroll ? 'flex' : 'none';
    }
    
    // Event listeners for filters
    filterPrice.addEventListener('change', filterAndSortRooms);
    filterCapacity.addEventListener('change', filterAndSortRooms);
    sortBy.addEventListener('change', filterAndSortRooms);
    
    // Reset filters
    resetFilters.addEventListener('click', () => {
        filterPrice.value = 'all';
        filterCapacity.value = 'all';
        sortBy.value = 'name_asc';
        filterAndSortRooms();
    });
    
    // Initialize
    filterAndSortRooms();
    
    // Handle window resize
    window.addEventListener('resize', updateScrollButtons);
    updateScrollButtons();
    
    // Add touch/swipe support for mobile
    let startX = 0;
    let scrollLeft = 0;
    
    roomsContainer.addEventListener('touchstart', (e) => {
        startX = e.touches[0].pageX - roomsContainer.offsetLeft;
        scrollLeft = roomsContainer.scrollLeft;
    });
    
    roomsContainer.addEventListener('touchmove', (e) => {
        e.preventDefault();
        const x = e.touches[0].pageX - roomsContainer.offsetLeft;
        const walk = (x - startX) * 2;
        roomsContainer.scrollLeft = scrollLeft - walk;
    });
});

function setupCounter(counterId, min = 1, max = 10) {
    const input = document.getElementById(counterId);
    const increaseBtn = input.parentElement.querySelector('.increase');
    const decreaseBtn = input.parentElement.querySelector('.decrease');
    
    increaseBtn.addEventListener('click', () => {
        if (parseInt(input.value) < max) {
            input.value = parseInt(input.value) + 1;
        }
    });
    
    decreaseBtn.addEventListener('click', () => {
        if (parseInt(input.value) > min) {
            input.value = parseInt(input.value) - 1;
        }
    });
}

// Initialize all counters
document.addEventListener('DOMContentLoaded', function() {
    setupCounter('adults', 1, 10);
    setupCounter('children', 0, 10);
    setupCounter('rooms', 1, 5);
});

const modal = document.getElementById('bookingModal');
const roomIdInput = document.getElementById('room_id');

document.querySelectorAll('.openBookingModal').forEach(btn => {
    btn.addEventListener('click', () => {
        roomIdInput.value = btn.dataset.roomId;
        modal.style.display = 'block';
    });
});


