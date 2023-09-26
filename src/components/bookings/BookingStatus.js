class BookingStatus {
    
    PENDING_PAYMENT = "PENDING_PAYMENT";
    ACTIVE = "ACTIVE";
    COMPLETED = "COMPLETED";
    CANCELLED = "CANCELLED";

    parse(status) {
        const uppercaseStatus = status.toUpperCase();
        if (Object.values(this).includes(uppercaseStatus)) {
            return uppercaseStatus;
        } else {
            throw new Error(`Invalid booking status: ${status}`);
        }
    }
}


export default BookingStatus = new BookingStatus();