<?php
class MapController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) {
            $this->redirect('/auth/login');
        }
        $data = [
            'user' => $user,
            'title' => 'Campus Map'
        ];
        $this->view('map/index', $data);
    }
}