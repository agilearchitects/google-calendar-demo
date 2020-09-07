<?php namespace App\FS;

class FileSystem {
  public function exists(string $path): bool {
    return file_exists($path);
  }
  public function getContent(string $path): string {
    return file_get_contents($path);
  }
  public function putContent(string $path, string $content): void {
    file_put_contents($path, $content);
  }
}