<?php

namespace App\Services;

use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Image;

class GoogleVisionService
{
    public function extractText($imagePath)
    {
        $client = new ImageAnnotatorClient([
            'credentials' => storage_path('app/google/image-498104-0ea3d069b07c.json'),
        ]);

        try {

            $image = (new Image())
                ->setContent(file_get_contents($imagePath));

            $feature = (new Feature())
                ->setType(Feature\Type::TEXT_DETECTION);

            $request = (new AnnotateImageRequest())
                ->setImage($image)
                ->setFeatures([$feature]);

            $batchRequest = (new BatchAnnotateImagesRequest())
                ->setRequests([$request]);

            $response = $client->batchAnnotateImages($batchRequest);

            $responses = $response->getResponses();

            $text = '';

            if (count($responses) > 0) {

                $annotations = $responses[0]->getTextAnnotations();

                if (count($annotations) > 0) {
                    $text = $annotations[0]->getDescription();
                }
            }

            return $text;

        } finally {
            $client->close();
        }
    }
    public function extractContainerNumber($imagePath)
    {
        $text = $this->extractText($imagePath);

        // Remove spaces, dashes, line breaks
        $cleanText = strtoupper(
            preg_replace('/[^A-Z0-9]/', '', $text)
        );

        preg_match('/[A-Z]{4}[0-9]{7}/', $cleanText, $matches);

        return $matches[0] ?? null;
    }
    public function extractSealNumber($imagePath)
    {
        $text = strtoupper($this->extractText($imagePath));

        // Find seal formats like ML-TR1404635
        preg_match_all('/[A-Z]{1,5}-[A-Z]{1,5}[0-9]{4,10}/', $text, $matches);

        if (!empty($matches[0])) {
            return $matches[0][0];
        }

        // Fallback
        preg_match_all('/[A-Z0-9\-]{6,20}/', $text, $matches);

        return $matches[0][0] ?? null;
    }
}