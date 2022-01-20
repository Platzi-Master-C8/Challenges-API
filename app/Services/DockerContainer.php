<?php

namespace App\Services;

use App\Constants\DockerImagesNames;
use Exception;
use Symfony\Component\Process\Process;

class DockerContainer
{
    private string $id;
    private string $name;
    private string $image;
    private bool $isRunning;
    private string $localStorageBind;
    private string $dockerStorageBind;
    private bool $detachable;
    private bool $bindMount;

    /**
     * @param string $name : Docker name
     * @param string $image : Docker image name, it must be equal to a constant of App\Constants\DockerImagesNames
     * @throws Exception
     */
    public function __construct(string $name, string $image)
    {
        $this->name = $name;
        $this->image = $this->authorizeImage($image);

    }

    /**
     * @throws Exception
     */
    private function authorizeImage($image)
    {
        if (!in_array($image, DockerImagesNames::toArray())) {
            throw new Exception('Image not authorized!');
        }
        return $image;
    }

    public function detach()
    {
        $this->detachable = true;
    }

    public function bindMount($localStorageBind, $dockerStorageBind): DockerContainer
    {
        $this->bindMount = true;
        $this->localStorageBind = $localStorageBind;
        $this->dockerStorageBind = $dockerStorageBind;
        return $this;
    }

    public function resetCommand(): DockerContainer
    {
        $this->command = '';
        return $this;
    }


    // Use process to execute command from shell
    public function play()
    {
        $this->isRunning = true;
        $bindMount = $this->bindMount ? '-v ' . $this->localStorageBind . ':' . $this->dockerStorageBind : '';
        $command = '';

    }


}
