<?php

namespace App\Http\Controllers;

use App\Constants\{
    AvailableDockerLanguages,
    DockerChallengesPaths,
    DockerImagesNames,
    LocalChallengesPaths,
    StorageDisks,
};
use App\Util\{JsonTestParser, StorageWriter};

use App\Models\Challenge;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\DockerContainer;

class CodeRunnerController extends Controller
{
    /**
     * @param Challenge $challenge
     * @return false|string
     * @throws Exception
     */
    public function getChallengeEditor(Challenge $challenge)
    {
        $userIdentifier = $this->getUserIdentifier();

        $docker = new DockerContainer("node-" . $userIdentifier, DockerImagesNames::NODE_IMAGE);
        $docker->bindMount(storage_path(LocalChallengesPaths::NODE_PATH), DockerChallengesPaths::NODE_PATH)->detach()->play();
        $writer = new StorageWriter(StorageDisks::LOCAL_DISK, true,
            ["ChallengesTests", 'javascript', $challenge->id, $userIdentifier]);

        //In case that test has been updated in database. Test file will be overwritten
        $writer->write("func.test.js", $challenge->test_template);
        //Create user_func file if not exists.
        // !QUESTION: How could I improve this?
        if (!$writer->exists('user_func.js')) {
            $writer->write('user_func.js', $challenge->func_template);
        }
        return json_encode(['template' => $challenge->func_template]);
    }

    /**
     * @throws Exception in case  JsonTestParser::parse receives a non-supported language
     */
    public function runNode(Request $request, Challenge $challenge): array
    {
        $userIdentifier = $this->getUserIdentifier();
        $writer = new StorageWriter(StorageDisks::LOCAL_DISK, true,
            ["ChallengesTests", "javascript", $challenge->id, $userIdentifier]);
        $writer->write("user_func.js", $request->code);
        //Clean test file or create if not exist
        $writer->write("test.json", '', true);
        $docker = new DockerContainer("node-" . $userIdentifier, DockerImagesNames::NODE_IMAGE);


        $docker->detach()->exec("sh -c 'npm run test tests/$challenge->id/$userIdentifier/func.test.js -- --json"
            . "> tests/$challenge->id/$userIdentifier/test.json'");

        // npm run jest -- --json return a string with 4 extra unnecessary lines, just trim them
        $writer->trimFile(4, 'test.json');


        try {
            $result = $writer->get('test.json');
        } catch (FileNotFoundException $e) {
            $result = '{"passed": false, "message": "Server error"}';
        }

        // Next line create a drastic performance issue. Find a way to stop container after test avoiding affect performance
        // $docker->stop();


        $json = JsonTestParser::parse(json_decode($result), AvailableDockerLanguages::JS);
        return ['test_result' => $json,
            'template' => $request->code];
    }

    /**
     * @return string
     * Return user identifier to have always the same path for user tests
     */
    private function getUserIdentifier(): string
    {
        $user = Auth::user();
        return !is_null($user) ? $user->challenger->id . $user->nick_name : 'guest';
    }
}
