<?php

namespace App\Services;

use App\Constants\DockerImagesNames;
use Exception;

class DockerContainer
{
    private string $id;
    private string $name;
    private string $image;

    private string $localStorageBind;
    private string $dockerStorageBind;
    private bool $detachable = false;
    private bool $bindMount = false;

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
        return $this;
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
        $detach = $this->detachable ? '-d ' : '';
        $bindMount = $this->bindMount ? '-v ' . $this->localStorageBind . ':' . $this->dockerStorageBind . ' ' : '';
        $name = $this->name ? "--name $this->name " : '';
        $command = 'docker run ' . $detach . $name . $bindMount . $this->image;
        $this->run($command);
    }

    private function run($command)
    {
        $status = shell_exec($command);
        if (!$status) {
            $this->start();
        }
    }

    public function exec($command)
    {
        shell_exec("docker exec " . $this->name . ' ' . $command);
    }

    private function start(): void
    {
        shell_exec('docker start ' . $this->name);
    }

    public function stop()
    {
        shell_exec('docker stop ' . $this->name);
    }

}
