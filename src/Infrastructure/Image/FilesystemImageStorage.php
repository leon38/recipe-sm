<?php

declare(strict_types=1);

namespace App\Infrastructure\Image;

use App\Application\Exception\UnsupportedImageException;
use App\Application\Image\ImageStorageInterface;
use App\Domain\Recipe\ValueObject\ValueId;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use RuntimeException;

final class FilesystemImageStorage implements ImageStorageInterface
{
    public function __construct( 
        private readonly Filesystem $filesystem,
        #[Autowire('%kernel.project_dir%/public/uploads/recipes')]
        private string $uploadDirectory,

        #[Autowire('/uploads/recipes')]
        private string $publicPrefix = '/uploads/recipes',

        private int $quality = 85,
    ) {
    }

    public function store(string $image, ValueId $id): string
    {
        if (!preg_match(
            '#^data:image/(?<extension>png|jpeg|jpg|webp);base64,(?<data>.+)$#',
            $image,
            $matches,
        )) {
            throw new UnsupportedImageException('Unsupported image format.');
        }

        $binary = base64_decode($matches['data'], true);

        if ($binary === false) {
            throw new RuntimeException('Invalid base64 image.');
        }

        $resource = imagecreatefromstring($binary);

        if ($resource === false) {
            throw new RuntimeException('Unable to decode image.');
        }

        if (!is_dir($this->uploadDirectory)) {
            mkdir($this->uploadDirectory, 0775, true);
        }

        imagepalettetotruecolor($resource);

        imagesavealpha($resource, true);

        $filename = sprintf(
            '%s-%s.webp',
            $id,
            bin2hex(random_bytes(4)),
        );

        $path = $this->path($filename);

        if (!imagewebp($resource, $path, $this->quality)) {
            unset($resource);
            throw new RuntimeException('Unable to save image.');
        }

        return sprintf(
            '%s/%s',
            rtrim($this->publicPrefix, '/'),
            $filename,
        );
    }

    public function exists(string $url): bool
    {
        $filename = basename($url);

        $path = sprintf(
            '%s/%s',
            rtrim($this->uploadDirectory, '/'),
            $filename
        );

        return $this->filesystem->exists($path);
    }

    public function delete(string $url): void
    {
        $filename = basename($url);

        $path = sprintf(
            '%s/%s',
            rtrim($this->uploadDirectory, '/'),
            $filename
        );

        if (is_file($path)) {
            $this->filesystem->remove($path);
        }
    }

    private function path(string $filename): string
    {
        return sprintf(
            '%s/%s',
            rtrim($this->uploadDirectory, '/'),
            $filename,
        );
    }
}