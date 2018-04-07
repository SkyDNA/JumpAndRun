<?php

namespace StuckDexter;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\block\SignPost;
use pocketmine\math\Vector3;
use pocketmine\scheduler\PluginTask;

class jumpandrun extends PluginBase implements Listener{

public $prefix = "§7[§bJumpAndRun§7]§r ";
public $regsign = null;
public $regstats = null;

public function onEnable(){
@mkdir($this->getDataFolder());
$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
$players = new Config($this->getDataFolder()."players.yml", Config::YAML);
$this->getServer()->getPluginManager()->registerEvents($this, $this);
$this->getServer()->getScheduler()->scheduleRepeatingTask(new statsSign($this), 1);
}
public function onJoin(PlayerJoinEvent $event){
$players = new Config($this->getDataFolder()."players.yml", Config::YAML);
$player = $event->getPlayer();
$name = $player->getName();
if(empty($players->get($name))){
$players->setNested($name.".JumpAndRun", null);
$players->setNested($name.".Time", null);
$players->save();
}
}
public function onQuit(PlayerQuitEvent $event){
$player = $event->getPlayer();
$name = $player->getName();
$players = new Config($this->getDataFolder()."players.yml", Config::YAML);
$players->setNested($name.".JumpAndRun", null);
$players->setNested($name.".Time", null);
$players->save();
}
public function onMove(PlayerMoveEvent $event){
$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
$players = new Config($this->getDataFolder()."players.yml", Config::YAML);
$player = $event->getPlayer();
$block = $player->getLevel()->getBlock($player->floor()->subtract(0, 1));
if(!$players->getNested($player->getName().".JumpAndRun") == null){
if($block->getId() == 57){
$fzeit = time() - $players->getNested($player->getName().".Time");
$zeit = round($fzeit/60, 2);
$player->sendMessage($this->prefix."§aDu hast es geschafft \nZeit: ".$zeit);
if($zeit < $config->getNested($players->getNested($player->getName().".JumpAndRun").".1Time")){
$player->sendMessage("§6§khhhhhhhhhhhhhhhhhhhhh\n\n§r§l§a     NEUER RECORD\n\n§r§6§khhhhhhhhhhhhhhhhhhhhhh");
$config->setNested($players->getNested($player->getName().".JumpAndRun").".1Time", $zeit);
$config->setNested($players->getNested($player->getName().".JumpAndRun").".1Player", $player->getName());
$config->save();
$players->setNested($player->getName().".JumpAndRun", null);
$players->setNested($player->getName().".Time", null);
$players->save();
$player->teleport($player->getlevel()->getSafeSpawn());
}elseif($zeit < $config->getNested($players->getNested($player->getName().".JumpAndRun").".2Time")){
$player->sendMessage("§6§khhhhhhhhhhhhhhhhhhhhh\n\n§r§l§a     2.Platz\n\n§r§6§khhhhhhhhhhhhhhhhhhhhhh");
$config->setNested($players->getNested($player->getName().".JumpAndRun").".2Time", $zeit);
$config->setNested($players->getNested($player->getName().".JumpAndRun").".2Player", $player->getName());
$config->save();
$players->setNested($player->getName().".JumpAndRun", null);
$players->setNested($player->getName().".Time", null);
$players->save();
$player->teleport($player->getlevel()->getSafeSpawn());
}elseif($zeit < $config->getNested($players->getNested($player->getName().".JumpAndRun").".3Time")){
$player->sendMessage("§6§khhhhhhhhhhhhhhhhhhhhh\n\n§r§l§a     3. Platz\n\n§r§6§khhhhhhhhhhhhhhhhhhhhhh");
$config->setNested($players->getNested($player->getName().".JumpAndRun").".3Time", $zeit);
$config->setNested($players->getNested($player->getName().".JumpAndRun").".3Player", $player->getName());
$config->save();
$players->setNested($player->getName().".JumpAndRun", null);
$players->setNested($player->getName().".Time", null);
$players->save();
$player->teleport($player->getlevel()->getSafeSpawn());
}else{
$players->setNested($player->getName().".JumpAndRun", null);
$players->setNested($player->getName().".Time", null);
$players->save();
$player->teleport($player->getlevel()->getSafeSpawn());
}
}
}
}
public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
$players = new Config($this->getDataFolder()."players.yml", Config::YAML);
$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
if($cmd->getName() == "jumpandrun"){
if($sender instanceof Player){
if($sender->hasPermission("jar.admin")){
if(empty($args[0])){
$sender->sendMessage($this->prefix."/jumpandrun <stats | add | remove>");
}else{
if($args[0] == "add"){
if(empty($args[1])){
$sender->sendMessage($this->prefix."/jumpandrun add <name>");
}else{
$config->setNested($args[1].".Start", array(0, 0, 0));
$config->setNested($args[1].".1Time", 9999999999999);
$config->setNested($args[1].".1Player", null);
$config->setNested($args[1].".2Time", 9999999999999);
$config->setNested($args[1].".2Player", null);
$config->setNested($args[1].".3Time", 9999999999999);
$config->setNested($args[1].".3Player", null);
$config->save();
$sender->sendMessage($this->prefix."§aDas JumpAndRun ".$args[1]." wurde erstellt\n Mache nun /jumpandrun setStart ".$args[1]);
}
}
if($args[0] == "stats"){
if(empty($args[1])){
$sender->sendMessage($this->prefix."/jumpandrun stats <JumpAndRun>");
}else{
$this->regstats = $args[1];
$sender->sendMessage($this->prefix."§aTippe nun ein Schild an");
}
}

if($args[0] == "setStart"){
if(empty($args[1])){
$sender->sendMessage($this->prefix."/jumpandrun setStart <name>");
}else{
$config->setNested($args[1].".Start", array($sender->getX(), $sender->getY(), $sender->getZ()));
$config->save();
$sender->sendMessage($this->prefix."§aDer Start für ".$args[1]." wurde auf ".$sender->getX()." ".$sender->getY()." ".$sender->getZ()."\n Mache nun /jumpandrun regSign ".$args[1]);
}
}

if($args[0] == "regSign"){
if(empty($args[1])){
$sender->sendMessage($this->prefix."/jumpandrun regsign <name>");
}else{
$this->regsign = $args[1];
$sender->sendMessage($this->prefix."§aTippe nun ein Schild an");
}
}
}
}else{
$sender->sendMessage("§cDu darfst das nicht!");
}
}else{
$this->getLogger()->info($this->prefix."§cDie Console darf das nicht!");
}
}
}
public function onInteract(PlayerInteractEvent $event){
$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
$players = new Config($this->getDataFolder()."players.yml", Config::YAML);
$block = $event->getBlock();
$player = $event->getPlayer();
$tile = $player->getLevel()->getTile($block);
        if($block instanceof SignPost){
        if(!$this->regsign == null){
       $tile->setText("§b§lJumpAndRun", "§7========", "§r§b".$this->regsign, "§7§l========");
       $player->sendMessage($this->prefix."§aDas Schild vom JumpAndRun ".$this->regsign." wurde registriert");
        $this->regsign = null;
        }elseif(!$this->regstats == null){
        $tile->setText("§7====§l§b".$this->regstats."§r§7====", "§61. ".$config->getNested($this->regstats.".1Player")." ".$config->getNested($this->regstats.".1Time"), "§72. ".$config->getNested($this->regstats.".2Player")." ".$config->getNested($this->regstats.".2Time"), "§a3. ".$config->getNested($this->regstats.".3Player")." ".$config->getNested($this->regstats.".3Time"));
        $this->regstats = null;
        }else{
         $text = $tile->getText();
        if($text[0] == "§b§lJumpAndRun"){
        $jar = str_replace("§r", "", str_replace("§l", "", str_replace("§b", "", $text[2])));
        $player->teleport(new Vector3($config->getNested($jar.".Start")[0], $config->getNested($jar.".Start")[1], $config->getNested($jar.".Start")[2]));
        $players->setNested($player->getName().".Time", time());
        $players->setNested($player->getName().".JumpAndRun", $jar);
        $players->save();
        $player->sendMessage($this->prefix."§aLauf! Die Zeit Läuft");
      
        }
        }
        }
        }
}
class statsSign extends PluginTask{
public function __construct(JumpAndRun $plugin) {
        parent::__construct($plugin);
        $this->plugin = $plugin;
    }

    public function onRun($tick) {
    $config = new Config($this->plugin->getDataFolder()."config.yml", Config::YAML);
$level = $this->plugin->getServer()->getDefaultLevel();
        $tiles = $level->getTiles();
        foreach($tiles as $t){
            if($t->getBlock() instanceof SignPost){
                $text = $t->getText();
                $jar = str_replace("§r", "", str_replace("§l§b", "", str_replace("§7====", "", $text[0])));
        if(!empty($config->get($jar))){
        $t->setText("§7====§l§b".$jar."§r§7====", "§61. ".$config->getNested($jar.".1Player")." ".$config->getNested($jar.".1Time"), "§72. ".$config->getNested($jar.".2Player")." ".$config->getNested($jar.".2Time"), "§a3. ".$config->getNested($jar.".3Player")." ".$config->getNested($jar.".3Time"));
        }
}
}
}
}
?>
