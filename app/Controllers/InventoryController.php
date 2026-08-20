<?php
// app/Controllers/InventoryController.php

class InventoryController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user || $user['role'] !== 'admin') { $this->redirect('/dashboard'); }

        $inventoryModel = $this->model('Inventory');
        $data = ['user' => $user, 'title' => 'Inventory Management', 'items' => $inventoryModel->getAllItems()];
        $this->view('inventory/index', $data);
    }
}
