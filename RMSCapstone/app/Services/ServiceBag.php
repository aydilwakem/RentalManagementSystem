<?php

namespace App\Services;

class ServiceBag
{
    public function __construct(
        public TransactionLoader $loader,
        public ActivityCartService $activityCartService,
        public RoomCartService $roomCartService,
        public ActivityTransactionService $activityTransactionService,
        public ServiceTransactionService $serviceTransactionService,
        public InvoiceService $invoiceService,
        public BrandingService $brandingService,
        public EmailService $emailService,
        public PayMongoService $payMongoService,
        public NotificationService $notificationService,
        public ReceiptService $receiptService,
        public PaymentService $paymentService,
        public CartService $cartService,
        public GuestDetailService $guestDetailService,
    ) {}
}
