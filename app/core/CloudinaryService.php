<?php
namespace App\core;

use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;

class CloudinaryService
{
    private Cloudinary $cloudinary;
    public static string $path = '../../public/images/uploads/';

    public function __construct(string $cloudName, string $apiKey, string $apiSecret)
    {
        $this->cloudinary = new Cloudinary(
            [
                'cloud' => [
                    'cloud_name' => $cloudName,
                    'api_key'    => $apiKey,
                    'api_secret' => $apiSecret,
                ]
            ]
        );
    }

    /**
     * Upload d'une image
     */
    public function uploadImage(string $filePath, string $folder = 'uploads', string $publicId = null): string
    {
        $options = ["folder" => $folder];
        if ($publicId) {
            $options["public_id"] = $publicId;
        }

        $result = (new UploadApi())->upload($filePath, $options);

        return $result['secure_url']; // URL finale de l'image
    }

    /**
     * Supprimer une image
     */
    public function deleteImage(string $publicId): array
    {
        return (new UploadApi())->destroy($publicId);
    }
}
