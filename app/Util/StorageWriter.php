<?php

namespace App\Util;

use Exception;
use App\Constants\StorageDisks;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

class StorageWriter implements IFileWriter
{
    private string $disk;
    private string $path;
    private bool $constantPath;

    /**
     * @param string $disk : Go to App\Constants\StorageDisks to see all available disks
     * @param bool $constantPath : If true, the path won't reset after execute any action, else it will
     * @param string $dirs : dirs to cd into
     * @throws Exception: In case $disk param is not in StorageDisks
     */
    public function __construct(string $disk, bool $constantPath = false, $dirs = [])
    {

        if (!in_array($disk, StorageDisks::toArray())) {
            throw new Exception("Invalid disk name");
        }
        $this->path = "";
        $this->disk = $disk;
        $this->constantPath = $constantPath;
        if (!empty($dirs)) {
            $this->cdDirs($dirs);
        }
    }

    public function cdDir(string $directory): StorageWriter
    {
        $this->path .= $directory . '/';
        return $this;
    }

    /**
     * @param $directories
     * @return $this
     */
    public function cdDirs($directories): StorageWriter
    {
        foreach ($directories as $directory) {
            $this->cdDir($directory);
        }
        return $this;
    }


    public function write(string $fileName, string $content, bool $givePermissions = false): void
    {
        Storage::disk($this->disk)->put($this->path . $fileName, $content);
        $this->resetPath();
        if ($givePermissions) {
            chmod(storage_path('app/' . $this->path . $fileName), 0777);
        }
    }

    public function exists(string $fileName): bool
    {
        $exists = Storage::disk($this->disk)->exists($this->path . $fileName);
        $this->resetPath();
        return $exists;
    }

    /**
     * @param string $fileName : Name of the file to retrieve
     * Return the given file
     * @throws FileNotFoundException
     */
    public function get(string $fileName): string
    {

        $content = Storage::disk($this->disk)->get($this->path . $fileName);
        $this->resetPath();
        return $content;
    }

    /**
     * Delete a file
     * @param string $fileName
     * @return void
     */
    public function delete(string $fileName): void
    {
        Storage::disk($this->disk)->delete($this->path . $fileName);
        $this->resetPath();
    }


    public function trimFile($lines, $filename)
    {
        FileHelper::trimLines(Storage::disk($this->disk)->path('/') . $this->path . $filename, $lines);
        $this->resetPath();
    }

    /**
     * If constant path is true, then the path is not reset
     * Rest path after execute Write, Exists or delete
     * @return void
     */

    private function resetPath()
    {
        if (!$this->constantPath) {
            $this->path = "";
        }
    }
}

