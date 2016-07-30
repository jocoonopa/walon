<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Model\Assign;
use App\Model\Game;
use App\Model\Mission;
use App\Model\Election;
use App\Model\Vote;
use App\Model\Player;
use App\Model\Character;
use App\Model\User;
use DB;

class GenGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wan:gengames {count=100}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto generate games';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 1. 亂數決定人數 v
     * 2. 亂數挑選玩家 v
     * 3. 亂數分配角色
     *      -> new Game
     *      -> new Player
     * 4. 亂數選擇出任務者
     * 5. 亂數投票
     * 6. 第一輪若出任務者有壞人, 50%機率會是成功, 其他輪設定為100%失敗[6人以上第四輪需要兩個任務失敗才算失敗]
     * 7. 任務小計，確認遊戲進入結束狀態(成功/失敗)或繼續下一個任務
     * 
     */
    public function handle()
    {
        set_time_limit(0);

        $bar = $this->output->createProgressBar($this->argument('count'));
        $bar->setFormat('verbose');
        $bar->setOverwrite(true);

        for ($i = 0; $i <= $this->argument('count'); $i ++) {
            $this->proc();
            $bar->advance();
        }

        $bar->finish();
        
        return $this->info("\r\ndone!");
    }

    protected function proc()
    {
        $users = User::where('id', '<=', '15')->get()->random(rand(6, 10));
        $assign = Assign::where('count', '=', $users->count())->first();

        $users = $users->sortBy(function ($product, $key) {
            return rand(0, 9999999);
        });

        $game = new Game;
        $game->assign_id = $assign->id;
        $game->location = '青島東路';
        $game->save();

        $playerCollection = collect([]);

        foreach (array_merge($assign->good_choices, $assign->bad_choices) as $characterId) {
            $user = $users->shift();

            $player = new Player();
            $player->user_id = $user->id;
            $player->game_id = $game->id;
            $player->character_id = $characterId;
            $player->save();

            $playerCollection->push($player);
        }

        $currentHost = 0;

        // Mission
        foreach ($assign->places as $serno => $amount) {
            $mission = $this->newMission($game, $serno);

            for ($turn = 0; $turn < 5; $turn ++) {
                $election = $this->newElection($game, $mission, $playerCollection, $playerCollection[$currentHost], $turn, $amount);
                $currentHost = ($assign->count - 1) === $currentHost ? 0 : $currentHost + 1;

                $playerCollection->each(function ($player, $key) use ($election) {
                    $vote = $this->newVote($election, $player);
                });

                if ($this->isMissionAllow($election)) {
                    $election->is_pass = true;
                    $election->save();

                    $mission->is_success = $this->isMissionSuccess($mission, $election);
                    $mission->save();

                    break;
                } else {
                    $election->is_pass = false;
                    $election->save();
                }                
            }

            if ($this->isGameOverByGood($game)) {
                $game->is_own_by_jus = (rand(0, 100) - floor(100/count($assign->good_choices))) > 0;
                $game->save();
                
                break;
            }

            if ($this->isGameOverByBad($game)) {
                $game->is_own_by_jus = false;
                $game->save();

                break;
            }

            continue;
        }

        return $this;
    }

    protected function newMission(Game $game, $serno)
    {
        $mission = new Mission;
        $mission->game_id = $game->id;
        $mission->serno = $serno;

        $mission->save();

        return $mission;
    }

    protected function newElection(Game $game, Mission $mission, $players, Player $player, $turn, $amount)
    {
        $election = new Election;
        $election->turn = $turn;
        $election->mission_id = $mission->id;
        $election->host_id = $player->id;

        $election->save();

        // 假設梅林一定不會挑到壞人
        if (in_array($player->character()->first()->id, [1, 2])) {
            $pickedPlayers = collect(DB::table('players')
                ->select('players.*')
                ->leftJoin('games', 'players.game_id', '=', 'games.id')
                ->leftJoin('characters', 'players.character_id', '=', 'characters.id')
                ->where('games.id', '=', $game->id)
                ->take($amount)
                ->get())
            ;
        } else {
            $pickedPlayers = $players->random($amount);
        }
        
        $pickedPlayers->each(function ($player) use ($election) {
            $election->players()->attach($player->id);  
        });

        return $election;
    }

    protected function newVote(Election $election, Player $player)
    {
        $vote = new Vote;
        $vote->election_id = $election->id;
        $vote->player_id = $player->id;
        $vote->is_agree = (rand(0, 100) - 65) > 0;

        if (1 === $player->character()->first()->id) {
            $badCount = $this->getElectionBadCount($election);

            $vote->is_agree = (0 === $badCount);
        }

        if (2 === $player->character()->first()->id) {
            if (1 === $election->host()->first()->character()->first()->id) {
                $vote->is_agree = true;
            }

            if (5 === $election->host()->first()->character()->first()->id) {
                $vote->is_agree = false;
            }            
        }

        if (4 === $election->turn && true === $player->character()->first()->is_good) {
            $vote->is_agree = true;
        }

        $vote->save();

        return $vote;
    }

    protected function isGameOverByGood(Game $game)
    {
        $count = $game->missions()->where('is_success', '=', true)->count();

        return $count >= 3;
    }

    protected function isGameOverByBad(Game $game)
    {
        $count = $game->missions()->where('is_success', '=', false)->count();

        return $count >= 3;
    }

    protected function isMissionAllow(Election $election)
    {
        return $election->votes()->where('is_agree', '=', 1)->count() >= $election->votes()->where('is_agree', '=', 0)->count();
    }

    protected function isMissionSuccess(Mission $mission, Election $election)
    {
        $players = $election->players()->get();

        $badCount = $this->getElectionBadCount($election);

        if (0 < $badCount && 0 === $mission->serno) {
            return (rand(0, 100) - 35) > 0;
        }

        if (0 < $badCount && in_array($mission->serno, [1, 2, 4])) {
            return false;
        }

        if (1 < $badCount && 3 === $mission->serno) {
            return false;
        }

        return true;
    }

    protected function getElectionBadCount(Election $election)
    {
        return DB::table('election_player')
                ->leftJoin('players', 'election_player.player_id', '=', 'players.id')
                ->leftJoin('characters', 'characters.id', '=', 'players.character_id')
                ->where('characters.is_good', '=', false)
                ->where('election_player.election_id', $election->id)
                ->count()
            ;
    }
}
