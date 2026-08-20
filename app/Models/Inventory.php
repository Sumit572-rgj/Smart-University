<?php
// app/Models/Inventory.php

class Inventory extends Model {
    public function getAllItems() {
        $stmt = $this->db->query("SELECT * FROM inventory_items ORDER BY category, item_name");
        return $stmt->fetchAll();
    }
}
