<?php

namespace Budabot\User\Modules;

use Budabot\Core\AutoInject;

/**
 * Authors: 
 *  - Tyrence (RK2)
 *
 * @Instance
 *
 * Commands this class contains:
 *	@DefineCommand(
 *		command     = 'git',
 *		accessLevel = 'admin',
 *		description = 'Updates bot from Git repository',
 *		help        = 'git.txt'
 *	)
 */
class GitController extends AutoInject {

	/**
	 * Name of the module.
	 * Set automatically by module loader.
	 */
	public $moduleName;
	
	/** @Setup */
	public function setup() {
		$this->settingManager->add($this->moduleName, "gitpath", "Path to git binary", "edit", "text", "git", "git;/usr/bin/git;C:/Program Files (x86)/Git/bin/git.exe");
	}
	
	/**
	 * @HandlesCommand("git")
	 * @Matches("/^git incoming$/i")
	 */
	public function gitIncomingCommand($message, $channel, $sender, $sendto, $args) {
		$gitpath = $this->settingManager->get('gitpath');
		$command = "$gitpath fetch origin 2>&1";
		$this->executeCommand($command);
		
		$command = "$gitpath log master ...origin/master 2>&1";
		
		$blob = $this->executeCommand($command);
		$msg = $this->text->makeBlob("git incoming", $blob);
		$sendto->reply($msg);
	}
	
	/**
	 * @HandlesCommand("git")
	 * @Matches("/^git diff$/i")
	 */
	public function gitDiffCommand($message, $channel, $sender, $sendto, $args) {
		$gitpath = $this->settingManager->get('gitpath');
		$command = "$gitpath fetch origin 2>&1";
		$this->executeCommand($command);
		
		$command = "$gitpath diff --stat HEAD ...origin/master 2>&1";
		
		$blob = $this->executeCommand($command);
		$msg = $this->text->makeBlob("git diff", $blob);
		$sendto->reply($msg);
	}

	/**
	 * @HandlesCommand("git")
	 * @Matches("/^git pull$/i")
	 */
	public function gitPullCommand($message, $channel, $sender, $sendto, $args) {
		$gitpath = $this->settingManager->get('gitpath');
		$command = "$gitpath pull origin master 2>&1";
		
		$blob = $this->executeCommand($command);
		$msg = $this->text->makeBlob("git pull", $blob);
		$sendto->reply($msg);
	}
	
	/**
	 * @HandlesCommand("git")
	 * @Matches("/^git log$/i")
	 */
	public function gitLogCommand($message, $channel, $sender, $sendto, $args) {
		$gitpath = $this->settingManager->get('gitpath');
		$command = "$gitpath log -n 20 2>&1";
		
		$blob = $this->executeCommand($command);
		$msg = $this->text->makeBlob("git log", $blob);
		$sendto->reply($msg);
	}
	
	/**
	 * @HandlesCommand("git")
	 * @Matches("/^git status$/i")
	 */
	public function gitStatusCommand($message, $channel, $sender, $sendto, $args) {
		$gitpath = $this->settingManager->get('gitpath');
		$command = "$gitpath status 2>&1";
		
		$blob = $this->executeCommand($command);
		$msg = $this->text->makeBlob("git status", $blob);
		$sendto->reply($msg);
	}
	
	private function executeCommand($command) {
		$output = array();
		$return_var = '';
		exec($command, $output, $return_var);

		$blob = $command . "\n\n";
		$blob .= implode("\n", $output);
		return $blob;
	}
}

?>
