<?php

class AdminPaymentHistory {
    
    private $paymentModel;
    
    public function __construct() {
        $this->paymentModel = new PaymentHistoryModel();
    }
    
    public function index() {
        // Get filters from GET parameters
        $filters = [
            'payment_type' => $_GET['payment_type'] ?? null,
            'search' => $_GET['search'] ?? null,
            'start_date' => $_GET['start_date'] ?? null,
            'end_date' => $_GET['end_date'] ?? null
        ];
        
        // Get payments with filters
        $payments = $this->paymentModel->get_all_payments($filters);
        
        $paymentStats = [
            'total_payments' => $this->paymentModel->get_total_payments(),
            'payments_by_type' => $this->paymentModel->get_payments_by_type(),
            'monthly_payments' => $this->paymentModel->get_monthly_payments()
        ];
        
        // Create view instance and pass data
        $view = new AdminPaymentHistoryView();
        $view->setData([
            'payments' => $payments,
            'filters' => $filters,
            'stats' => $paymentStats
        ]);
        $view->setController($this);
        $view->index();
    }
}
