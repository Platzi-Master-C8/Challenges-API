<?php

namespace App\Factories\CodeRunner;

use App\Constants\AvailableDockerLanguages;
use App\Constants\DockerChallengesPaths;
use App\Constants\DockerImagesNames;
use App\Constants\LocalChallengesPaths;
use App\Constants\StorageDisks;
use App\Models\Challenge;
use App\Services\DockerContainer;
use App\Util\JsonTestParser;
use App\Util\StorageWriter;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class JsCodeRunner implements CodeRunner
{


    /**
     * @throws Exception
     */
    public function run($userId, $challenge, string $code): array
    {
        $writer = new StorageWriter(StorageDisks::LOCAL_DISK, true,
            ["ChallengesTests", "javascript", $challenge->id, $userId]);
        $writer->write("user_func.js", $code);

        $writer->write("test.json", '', true);
        $docker = new DockerContainer("node-" . $userId, DockerImagesNames::NODE_IMAGE);


        $docker->detach()->exec("sh -c 'npm run test tests/$challenge->id/$userId/func.test.js -- --json"
            . "> tests/$challenge->id/$userId/test.json'");
        // npm run jest -- --json return a string with 4 extra unnecessary lines, just trim them
        $writer->trimFile(4, 'test.json');


        try {
            $result = $writer->get('test.json');
        } catch (FileNotFoundException $e) {
            $result = '{"passed": false, "message": "Server error"}';
        }
        return JsonTestParser::parse(json_decode($result), AvailableDockerLanguages::JS);
    }

    /**
     * @throws Exception
     */
    public function prepareRunner(Challenge $challenge, string $userId)
    {

        $docker = new DockerContainer("node-" . $userId, DockerImagesNames::NODE_IMAGE);

        $docker->bindMount(storage_path(LocalChallengesPaths::NODE_PATH), DockerChallengesPaths::NODE_PATH)
            ->detach()->play();

        $writer = new StorageWriter(StorageDisks::LOCAL_DISK, true,
            ["ChallengesTests", 'javascript', $challenge->id, $userId]);

        $writer->write("func.test.js", $challenge->test_template);
        if (!$writer->exists('user_func.js')) {
            $writer->write('user_func.js', $challenge->func_template);
        }
    }

    public function stop(string $userId): void
    {
        try {
            $docker = new DockerContainer("node-" . $userId, DockerImagesNames::NODE_IMAGE);
            $docker->stop();
        } catch (Exception $e) {
            //Do nothing
        }
    }
}
