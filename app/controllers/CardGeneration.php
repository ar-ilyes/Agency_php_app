<?php
class CardGeneratorController {
    // Static configuration
    private const TEMPLATE_PATH = '../../assets/images/card-template.jpeg';
    private const FONT_PATH = '../../assets/fonts/Roboto-Regular.ttf';
    private const OVERLAY_SIZE = 50;
    private const TEXT_SIZE = 20;

    /**
     * Main method to generate card, called by the view
     */
    public function generate_card($userName, $date, $overlay_image_paths) {
        // Create and resize the template image
        $templateImage = imagecreatefrompng(self::TEMPLATE_PATH);
        $resizedTemplate = $this->resize_template($templateImage);
        imagedestroy($templateImage);

        // Create black color for text
        $black = imagecolorallocate($resizedTemplate, 0, 0, 0);

        // Add each overlay image
        foreach ($overlay_image_paths as $index => $overlay_image_path) {
            $this->add_overlay_image($resizedTemplate, $overlay_image_path, $index);
        }

        // Add text to the image
        $this->add_text($resizedTemplate, $userName, $date, $black);

        // Output the final image
        $this->output_image($resizedTemplate);
    }

    /**
     * Resize the template image
     */
    private function resize_template($templateImage) {
        $width = imagesx($templateImage);
        $height = imagesy($templateImage);
        
        $resizedTemplate = imagecreatetruecolor($width, $height);
        
        // Preserve transparency
        imagealphablending($resizedTemplate, true);
        imagesavealpha($resizedTemplate, true);
        
        // Copy and resize
        imagecopyresampled(
            $resizedTemplate, 
            $templateImage, 
            0, 0, 0, 0, 
            $width, 
            $height, 
            $width, 
            $height
        );

        return $resizedTemplate;
    }

    /**
     * Add an overlay image to the template
     */
    private function add_overlay_image($resizedTemplate, $overlay_image_path, $index) {
        // Create an image from the overlay image
        $overlay_image = imagecreatefrompng($overlay_image_path);
        
        // Create resized overlay image
        $resized_overlay_image = imagecreatetruecolor(self::OVERLAY_SIZE, self::OVERLAY_SIZE);
        
        // Preserve transparency for overlay
        imagealphablending($resized_overlay_image, true);
        imagesavealpha($resized_overlay_image, true);
        
        // Resize overlay
        imagecopyresampled(
            $resized_overlay_image, 
            $overlay_image, 
            0, 0, 0, 0, 
            self::OVERLAY_SIZE, 
            self::OVERLAY_SIZE, 
            imagesx($overlay_image), 
            imagesy($overlay_image)
        );
        
        // Define position for overlay
        $x = 50 + ($index * (self::OVERLAY_SIZE + 10));
        $y = 150;
        
        // Copy overlay onto template
        imagecopy(
            $resizedTemplate, 
            $resized_overlay_image, 
            $x, $y, 0, 0, 
            self::OVERLAY_SIZE, 
            self::OVERLAY_SIZE
        );
        
        // Clean up
        imagedestroy($overlay_image);
        imagedestroy($resized_overlay_image);
    }

    /**
     * Add text elements to the image
     */
    private function add_text($resizedTemplate, $userName, $date, $color) {
        // Add username
        imagettftext(
            $resizedTemplate, 
            self::TEXT_SIZE, 
            0, 
            50, 50, 
            $color, 
            self::FONT_PATH, 
            $userName
        );
        
        // Add date
        imagettftext(
            $resizedTemplate, 
            self::TEXT_SIZE, 
            0, 
            50, 100, 
            $color, 
            self::FONT_PATH, 
            $date
        );
    }

    /**
     * Output the final image
     */
    private function output_image($image) {
        header('Content-Type: image/png');
        imagepng($image);
        imagedestroy($image);
    }
}
?>