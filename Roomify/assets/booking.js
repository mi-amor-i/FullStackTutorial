document.addEventListener('DOMContentLoaded', function() {
    const roomSelect = document.getElementById('room_id');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    
    // Display elements
    const roomNameDisplay = document.getElementById('display_room_name');
    const roomTypeDisplay = document.getElementById('display_room_type');
    const roomCapacityDisplay = document.getElementById('display_room_capacity');
    const roomPriceDisplay = document.getElementById('display_room_price');
    const summaryRoomPrice = document.getElementById('summary_room_price');
    const durationDisplay = document.getElementById('duration_display');
    const totalAmountDisplay = document.getElementById('total_amount_display');
    
    // Set today's date as minimum
    const today = new Date().toISOString().split('T')[0];
    startTimeInput.min = today;
    endTimeInput.min = today;
    
    // Update end date min based on start date
    startTimeInput.addEventListener('change', function() {
        if (this.value) {
            endTimeInput.min = this.value;
            if (endTimeInput.value && endTimeInput.value <= this.value) {
                endTimeInput.value = '';
            }
            calculateBooking();
        }
    });
    
    // When room is selected, update room info
    roomSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            // Update room info display
            roomNameDisplay.textContent = selectedOption.getAttribute('data-name') || '-';
            roomTypeDisplay.textContent = selectedOption.getAttribute('data-formatted-type') || '-';
            roomCapacityDisplay.textContent = selectedOption.getAttribute('data-capacity') || '-';
            
            const price = parseFloat(selectedOption.getAttribute('data-price') || 0);
            roomPriceDisplay.textContent = 'NPR ' + price.toFixed(2);
            summaryRoomPrice.textContent = 'NPR ' + price.toFixed(2);
        } else {
            // Reset displays
            roomNameDisplay.textContent = '-';
            roomTypeDisplay.textContent = '-';
            roomCapacityDisplay.textContent = '-';
            roomPriceDisplay.textContent = 'NPR 0.00';
            summaryRoomPrice.textContent = 'NPR 0.00';
        }
        
        calculateBooking();
    });
    
    // Recalculate when dates change
    endTimeInput.addEventListener('change', calculateBooking);
    
    // Calculate booking function
    function calculateBooking() {
        const selectedOption = roomSelect.options[roomSelect.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price') || 0);
        const startDate = startTimeInput.value;
        const endDate = endTimeInput.value;
        
        // Reset if no data
        if (!selectedOption.value || !startDate || !endDate) {
            durationDisplay.textContent = '0 days';
            totalAmountDisplay.textContent = 'NPR 0.00';
            return;
        }
        
        // Calculate days
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        if (end <= start) {
            durationDisplay.textContent = '0 days';
            totalAmountDisplay.textContent = 'NPR 0.00';
            return;
        }
        
        // Calculate difference in days
        const timeDiff = end.getTime() - start.getTime();
        const days = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
        if (days > 0) {
            const total = price * days;
            durationDisplay.textContent = days + ' day' + (days !== 1 ? 's' : '');
            totalAmountDisplay.textContent = 'NPR ' + total.toFixed(2);
        } else {
            durationDisplay.textContent = '0 days';
            totalAmountDisplay.textContent = 'NPR 0.00';
        }
    }
    
    // Initialize
    calculateBooking();
});