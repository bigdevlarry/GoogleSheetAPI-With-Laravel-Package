<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\sendXmlSpreadsheetJob;
use App\Traits\xmlSpreadsheetTrait;
use Illuminate\Support\Facades\Http;

class sendXmlToGoogleSpreadsheet extends Command
{
    use  xmlSpreadsheetTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xml-to-google-spreadsheet {file_path?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The command creates spools data from xml and creates on a google spreadsheet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    private $spreadsheetId;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return 
     */
    public function handle()
    {
        $path = $this->choice('Enter preferred xml data path', ['local', 'remote'], null,
                            $maxAttempts = null,
                            $allowMultipleSelections = false);

        $file_path = $this->argument('file_path');
        switch ($path) {
            case 'remote':
                $file_path = $file_path ? $file_path : $this->choice('Enter valid xml file url ', 
                            ['https://raw.githubusercontent.com/LarrySul/Infobip/master/coffee_feed.xml']);

                if(!filter_var($file_path, FILTER_VALIDATE_URL)){
                    $this->error('Please provide a valid xml remote url');
                    return;
                }
               
                $response_xml_data = Http::get($file_path);
                if($response_xml_data->failed()){
                    $this->error('Failed to fetch xml data from remote url');
                    return;
                }

                $this->buildAndDispatchXmlData($response_xml_data);
           
            break;
            default:
                $file_path = $file_path ? $file_path : $this->choice(
                                                        'Enter filename in public directory', 
                                                        ['coffee_feed.xml']);

                $response_xml_data = file_get_contents(public_path($file_path));
                $this->buildAndDispatchXmlData($response_xml_data);
            break;
        }
    }

    private function buildAndDispatchXmlData(string $response_xml_data) :bool
    {
        $xmlData = $this->decodeXmlDataToArray($response_xml_data);
        $data = $this->formatXmlDataItemToArray($xmlData);
        sendXmlSpreadsheetJob::dispatch($data);
        $this->info('The command was successful!');
        return 1;
    }
}
