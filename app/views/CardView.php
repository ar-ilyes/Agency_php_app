<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class CardView {
    private $member_data;
    private $controller;
    private $width = 1000;
    private $height = 600;
    private $image;
    
    public function __construct($controller) {
        $this->controller = $controller;
        $this->image = imagecreatetruecolor($this->width, $this->height);
        $white = imagecolorallocate($this->image, 255, 255, 255);
        imagefill($this->image, 0, 0, $white);
    }
    
    public function generate_card_image($member_id) {
        $this->member_data = $this->controller->get_member_data($member_id);
        
        $this->draw_header();
        $this->draw_member_info();
        $this->draw_qr_code();
        
        $filename = "card_" . $member_id . ".png";
        $filepath = "../public/cards/" . $filename;
        
        if (!is_dir("../public/cards/")) {
            mkdir("../public/cards/", 0777, true);
        }
        
        imagepng($this->image, $filepath);
        imagedestroy($this->image);
        
        return "/cards/" . $filename;
    }
    
    private function draw_header() {
        $logo = imagecreatefrompng("./../public/assets/logo.png");
        $logo_width = 200;
        $logo_height = 200;
        imagecopyresampled(
            $this->image, $logo,
            ($this->width - $logo_width) / 2, 50,
            0, 0,
            $logo_width, $logo_height,
            imagesx($logo), imagesy($logo)
        );
        
        $black = imagecolorallocate($this->image, 0, 0, 0);
        $font = "./../public/fonts/Roboto-Regular.ttf"; 
        imagettftext($this->image, 40, 0, ($this->width - 150) / 2, 300, $black, $font, "ASSCO");
    }
    
    private function draw_member_info() {
        $member_photo = imagecreatefrompng("./../public" . $this->member_data['photo']);
        $photo_size = 150;
        imagecopyresampled(
            $this->image, $member_photo,
            ($this->width - $photo_size) / 2, 350,
            0, 0,
            $photo_size, $photo_size,
            imagesx($member_photo), imagesy($member_photo)
        );
        
        $black = imagecolorallocate($this->image, 0, 0, 0);
        $font = "./../public/fonts/Roboto-Regular.ttf"; 
        
        $member_id = "ID: " . $this->member_data['member_id'];
        $member_name = "Name: " . $this->member_data['first_name'] . " " . $this->member_data['last_name'];
        
        imagettftext($this->image, 20, 0, ($this->width - 300) / 2, 530, $black, $font, $member_id);
        imagettftext($this->image, 20, 0, ($this->width - 300) / 2, 560, $black, $font, $member_name);
    }
    
    private function draw_qr_code() {
        $qrCode = new QrCode($this->member_data['member_id']);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        
        $qr_image = imagecreatefromstring($result->getString());
        $qr_size = 100;
        
        imagecopyresampled(
            $this->image, $qr_image,
            $this->width - 150, $this->height - 150,
            0, 0,
            $qr_size, $qr_size,
            imagesx($qr_image), imagesy($qr_image)
        );
    }
}
