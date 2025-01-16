<?php

class AdminPaymentHistory {
    
    private $paymentModel;
    
    public function __construct() {
        $this->paymentModel = new PaymentHistoryModel();
    }
    
    public function index() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
            header('Location: /auth');
            return;
        }
        $filters = [
            'payment_type' => $_GET['payment_type'] ?? null,
            'search' => $_GET['search'] ?? null,
            'start_date' => $_GET['start_date'] ?? null,
            'end_date' => $_GET['end_date'] ?? null
        ];
        
        $payments = $this->paymentModel->get_all_payments($filters);
        
        $paymentStats = [
            'total_payments' => $this->paymentModel->get_total_payments(),
            'payments_by_type' => $this->paymentModel->get_payments_by_type(),
            'monthly_payments' => $this->paymentModel->get_monthly_payments()
        ];
        
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
