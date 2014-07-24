<?php
 /**
  * Class LocationDataTableSeeder
  *
  * filename:   LocationDataTableSeeder.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       7/23/14 9:34 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

class LocationDataTableSeeder extends BaseSeeder
{
    public function __construct()
    {
        $this->table        = 'location_data';
        $this->connection   = 'utils_db';
        $this->filename     = app_path().'/database/csv/location_data.csv';
    }


    public function run()
    {
        ini_set('memory_limit', '-1');
        echo "Start @ " . strtotime("now");
        DB::connection($this->connection)->table($this->table)->truncate();
        $seedData   =   $this->seedFromCSV($this->filename, ',');
        DB::connection($this->connection)->table($this->table)->insert($seedData);
        echo "End @ " . strtotime("now");
    }

    /**
     * Collect data from a given CSV file and return as array
     *
     * @param $filename
     * @param string $deliminator
     *
     * @return array|bool
     */
    protected function seedFromCSV($filename, $deliminator = ",")
    {
        if(!file_exists($filename) || !is_readable($filename))
        {
            return FALSE;
        }

        $header =   NULL;
        $data   =   array();

        if(($handle = fopen($filename, 'r')) !== FALSE)
        {
            #$x=0;
            while(($row = fgetcsv($handle, 1000, $deliminator)) !== FALSE)
            {
                if(!$header)
                {
                    $header =   $row;
                }
                else
                {
                    $row[1] =   str_pad($row[1], 5, 0, STR_PAD_LEFT);   // PO Box needs zeros
                    $data[] =   array_combine($header, $row);
                }
                #$x++;
                #if($x==150)break;
            }
            fclose($handle);
        }

        return $data;
    }
}