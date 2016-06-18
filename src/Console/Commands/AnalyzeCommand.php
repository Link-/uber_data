<?php namespace UberCrawler\Console\Commands;

use UberCrawler\Libs\Helper as Helper;
use UberCrawler\Libs\Crawler as Crawler;
use Symfony\Component\Console\Command\Command;
use UberCrawler\Libs\TripsStorage as TripsStorage;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
* @author Abed Halawi <halawi.abed@gmail.com>
*/
class AnalyzeCommand extends Command
{

  private $_defaultConfigArray;

  /**
   * Takes the default configuration from ConfingCommand and stores
   * it in AnalyzeCommand. This is needed in order to check whether the
   * App.php file exists
   *
   * @param array $configArray [description]
   */
  public function setDefaultConfigArray(array $configArray) {

    $this->_defaultConfigArray = $configArray;

  }

  /**
   * Configure the command options.
   *
   * @return void
   */
  protected function configure() {

    $this->setName('analyze')
         ->setDescription('Generate analysis data of your Uber rides.');

  }

  /**
   * Execute the command.
   *
   * @param  InputInterface  $input
   * @param  OutputInterface  $output
   *
   * @return void
   */
  protected function execute(InputInterface $input, OutputInterface $output) {

    // If the Configuration file doesn't
    // exist quit
    if (!$this->verifyConfigFileExists()) {
      $output->writeln("<info>Configuration File was not created.\nrun ". 
                       "the command <options=bold>uberc config</> first!".
                       "</info>");
      exit();
    }

    $output->writeln("<comment>Analysis has begun!</comment>\n");

    $crawler = new Crawler();
    $tripCollection = $crawler->execute();

    Helper::printOut("Saving to file");
    TripsStorage::TripCollectiontoCSV($crawler->getTripsCollection());

  }


  /**
   * Verify that the 'App.php' configuration file
   * exists. Otherwise notify the user that he should run the 
   * `uberc config` command
   *
   * @return boolean [description]
   */
  protected function verifyConfigFileExists() {

    $fileName = $this->_defaultConfigArray['configPath'] . 
                $this->_defaultConfigArray['configFileName'];

    if (file_exists($fileName))
      return True;

    return False;

  }
}
