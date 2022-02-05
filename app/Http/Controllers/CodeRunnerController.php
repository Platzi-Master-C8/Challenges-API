<?php

namespace App\Http\Controllers;

use App\Constants\AvailableDockerLanguages;
use App\Constants\DockerChallengesPaths;
use App\Constants\DockerImagesNames;
use App\Constants\LocalChallengesPaths;
use App\Constants\StorageDisks;
use App\Models\Challenge;
use App\Util\JsonTestParser;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Util\StorageWriter;

class CodeRunnerController extends Controller
{
    /**
     * @param Challenge $challenge
     * @return false|string
     */
    public function getChallengeEditor(Challenge $challenge)
    {


        $writer = new StorageWriter(StorageDisks::LOCAL_DISK, true);
        $userIdentifier = $this->getUserIdentifier();
        $writer->cdDirs(["ChallengesTests", 'javascript', $challenge->id, $userIdentifier]);
        $dockerName = "docker-" . $userIdentifier;

        $dockerStatus = shell_exec("docker run -d --name $dockerName  -v "
            . storage_path() . LocalChallengesPaths::NODE_PATH . ':' . DockerChallengesPaths::NODE_PATH . ' ' . DockerImagesNames::NODE_IMAGE);

        //If stopped, start it
        if (is_null($dockerStatus)) {
            shell_exec("docker start $dockerName");
        }

        //In case that test has been updated in database. Test file will be rewritten
        $writer->write("func.test.js", $challenge->test_template);
        //Create user_func file if not exists
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

        $writer = new StorageWriter(StorageDisks::LOCAL_DISK, true);

        $writer->cdDirs(["ChallengesTests", "javascript", $challenge->id, $userIdentifier]);

        $writer->write("user_func.js", $request->code);

        //Clean test file or create if not exist
        $writer->write("test.json", '');
        //This should be replaced with docker class
        $command = "docker exec docker-$userIdentifier  sh -c 'npm run test tests/$challenge->id/$userIdentifier/func.test.js -- --json"
            . "> tests/$challenge->id/$userIdentifier/test.json'";

        shell_exec($command);

        // npm run jest -- --json return a string with 4 extra unnecessary lines, just trim them
        $writer->trimFile(4, 'test.json');
        try {
            $result = $writer->get('test.json');
        } catch (FileNotFoundException $e) {
            $result = '{"passed": false, "message": "Server error"}';
        }
        // We have to stop containers with Async in order to get server response faster

        //        $stopCommand = "docker stop docker-$userIdentifier";
        //        shell_exec($s topCommand);


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
