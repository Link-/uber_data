<?php

namespace UberCrawler\Console\Commands;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Abed Halawi <halawi.abed@gmail.com>
 */
class ConfigCommand extends Command
{
    /**
     * The path to the Config directory.
     *
     * @var string
     */
    private $configPath = __DIR__.'/../../Config/';

    /**
     * The name of the config file.
     *
     * @var string
     */
    private $configFileName = 'App.php';

    /**
     * The name of the stub config file.
     *
     * @var string
     */
    private $configStubFileName = 'App.example.php';

    /**
     * Default config values.
     *
     * @var array
     */
    private $defaults = [
        'data_storage_dir' => 'uber-data',
        'parsed_data_dir' => 'uber-parsed',
    ];

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('config')
            ->setDescription('Configure the Uber Crawler.');
    }

    /**
     * Execute the command.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<comment>Let us configure your Uber Crawler</comment>\n");

        // get the questions helper instance
        $helper = $this->getHelper('question');

        // ask for username
        $username = $this->askForUsername($helper, $input, $output);

        // ask for password
        $password = $this->askForPassword($helper, $input, $output);

        // ask for crawled data storage path
        $dataStoragePath = $this->askForDataStoragePath($helper, $input, $output);

        // ask for parsed data storage path
        $parsedDataStoragePath = $this->askForParsedDataStoragePath($helper, $input, $output);

        // write the App.php config file
        $isWritten =$this->writeConfigFileWithInput($username, $password, $dataStoragePath, $parsedDataStoragePath);

        if (!(bool) $isWritten) {
            throw Exception('Could not write config file.');
        }

        $output->writeln("<info>INFO: Written config file 🤘🏻</info>\n");
        $output->writeln('<comment>now to analyze, run "uberc analyze"</comment>');
    }

    /**
     * Promot for username.
     *
     * @param  QuestionHelper  $helper
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     *
     * @return string
     */
    private function askForUsername(QuestionHelper $helper, InputInterface $input, OutputInterface $output)
    {
        // create question
        $question = new Question('<info>Username:</info> ');
        // validate question value
        $question->setValidator(function ($value) {
            if (trim($value) == '') {
                throw new Exception('The username can not be empty');
            }

            return $value;
        });

        // prompt and return input
        return $helper->ask($input, $output, $question);
    }

    /**
     * Prompt for Password.
     *
     * @param  QuestionHelper  $helper
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     *
     * @return string
     */
    private function askForPassword(QuestionHelper $helper, InputInterface $input, OutputInterface $output)
    {
        // create question
        $question = new Question('<info>Password:</info> ');
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        // validate question value
        $question->setValidator(function ($value) {
            if (trim($value) == '') {
                throw new Exception('The password can not be empty');
            }

            return $value;
        });

        // prompt and return input
        return $helper->ask($input, $output, $question);
    }

    /**
     * Prompt for crawled data storage directory path.
     *
     * @param  QuestionHelper  $helper
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     *
     * @return string
     */
    private function askForDataStoragePath(QuestionHelper $helper, InputInterface $input, OutputInterface $output)
    {
        // create question
        $question = new Question(
            '<info>Where would you like to have the crawled files stored? <comment>[./'.$this->defaults['data_storage_dir'].']</comment> </info>',
            __DIR__.'/../../../'.$this->defaults['data_storage_dir']
        );

        // prompt and return input
        return $helper->ask($input, $output, $question);
    }

    /**
     * Prompt for parsed data storage directory path.
     *
     * @param  QuestionHelper  $helper
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     *
     * @return string
     */
    private function askForParsedDataStoragePath(QuestionHelper $helper, InputInterface $input, OutputInterface $output)
    {
        // create question
        $question = new Question(
            '<info>Where would you like to have the pared files stored? <comment>[./'.$this->defaults['parsed_data_dir'].']</comment> </info> ',
            __DIR__.'/../../../'.$this->defaults['parsed_data_dir']
        );

        // prompt and return input
        return $helper->ask($input, $output, $question);
    }

    /**
     * Write (overwrite) the given data into the config file (App.php)
     *
     * @param  string $username
     * @param  string $password
     * @param  string $dataStoragePath
     * @param  string $parsedDataStoragePath
     *
     * @return int The status of the write to disk.
     */
    private function writeConfigFileWithInput($username, $password, $dataStoragePath, $parsedDataStoragePath)
    {
        $config = $this->getStub();

        $config = $this->replaceUsername($username, $config);

        $config = $this->replacePassword($password, $config);

        $config = $this->replaceDataStoragePath($dataStoragePath, $config);

        $config = $this->replaceParsedDataStoragePath($parsedDataStoragePath, $config);

        return $this->writeConfig($config);
    }

    /**
     * Get the contents of the stub config file
     *
     * @return string
     * @throws \Exception
     */
    private function getStub()
    {
        $appStubPath = $this->configPath.$this->configStubFileName;

        if (!file_exists($appStubPath)) {
            throw new Exception('Could not find the Config stub file at '.$appStubPath);
        }

        return file_get_contents($appStubPath);
    }

    /**
     * Replace the username value in the given config stub.
     *
     * @param  string $username
     * @param  string $stub
     *
     * @return string
     */
    private function replaceUsername($username, $stub)
    {
        return str_replace("'username' => ''", "'username' => '$username'", $stub);
    }

    /**
     * Replace the password value in the given config stub.
     *
     * @param  string $password
     * @param  string $stub
     *
     * @return string
     */
    private function replacePassword($password, $stub)
    {
        return str_replace("'password' => ''", "'password' => '$password'", $stub);
    }

    /**
     * Replace the crawled data storage path value in the given config stub.
     *
     * @param  string $path
     * @param  string $stub
     *
     * @return string
     */
    private function replaceDataStoragePath($path, $stub)
    {
        return str_replace("'data_storage_dir' => '/tmp/uber-data'", "'data_storage_dir' => '$path'", $stub);
    }

    /**
     * Replace the parsed data storage path value in the given config stub.
     *
     * @param  string $path
     * @param  string $stub
     *
     * @return string
     */
    private function replaceParsedDataStoragePath($path, $stub)
    {
        return str_replace("'parsed_data_dir' => '/tmp/uber-parsed'", "'parsed_data_dir' => '$path'", $stub);
    }

    /**
     * Write the given contents into the config file.
     *
     * @param  string $contents
     *
     * @return string
     */
    private function writeConfig($contents)
    {
        return file_put_contents($this->configPath.$this->configFileName, $contents);
    }
}
