<?php

// app/Services/DigitalOceanService.php

namespace App\Services;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class DigitalOceanService
{
  protected S3Client $s3Client;

  public function __construct()
  {
    $this->s3Client = new S3Client([
        'version' => 'latest',
        'region'  => config('filesystems.disks.spaces.region'),
        'endpoint' => config('filesystems.disks.spaces.endpoint'),
        'credentials' => [
            'key'    => config('filesystems.disks.spaces.key'),
            'secret' => config('filesystems.disks.spaces.secret'),
        ],
    ]);
  }

  public function getPreSignedUrl($bucketName, $objectKey, $expiry = '+20 minutes'): ?string {
    $objectKey = $this->sanitizePath($objectKey);

    try {
      $cmd = $this->s3Client->getCommand('GetObject', [
          'Bucket' => $bucketName,
          'Key'    => $objectKey,
      ]);

      $request = $this->s3Client->createPresignedRequest($cmd, $expiry);
      return (string) $request->getUri();
    } catch (AwsException $e) {
      // Output error message if fails
      return null;
    }
  }

  private function sanitizePath($path): string {
    return ltrim(preg_replace('/\/+/', '/', $path), '/');
  }
}
