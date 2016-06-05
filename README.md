# Uber Crawler / Usage Analytics
    
    @version 0.1

### Synopsis
TBD

### Installation & Configuration
Installation is very basic, just: 

1. Clone this repository into any directory:

        git clone https://github.com/Link-/uber_data.git
    
2. Install dependencies (there aren't any at the moment) and build the `autoload` file:

        $: cd src/
        $: composer install
    
3. Build your `App.php` configuration file:

        $: cd src/Config
        $: cp App.example.php App.php
        $: nano App.php

4. Add your Uber Username and Password into the `App.php` file:

        /**
         * Uber Account Username
         */
        'username' => 'name@email.com',
        
        /**
         * Uber Account Password
         */
        'password' => 'mypassword',
 
5. Change the `data_storage_dir` and `parsed_data_dir` to where you would like to store the cached html files and the generated CSV file respectively.

6. Adjust the `timezone` to your current location.
  
### Execution

Run the script as such:

        $: php src/index.php

### Sample Output

        2016-06-03,Logan,$7.73,uberX,Los Angeles,N.A
        2016-06-03,John,$14.45,uberX,Los Angeles,N.A
        2016-06-02,Mark,$4.70,uberX,Los Angeles,N.A
        2016-06-02,Logan,Canceled,uberX,Los Angeles,N.A
        2016-06-02,Morgan,$13.23,uberX,Los Angeles,N.A
        2016-06-01,Sleimann,$4.79,uberX,Los Angeles,N.A
        2016-06-01,George,$14.36,uberX,Los Angeles,N.A

### Sample Analysis Output

Uber Data Anlysis v0.1 Notebook: https://github.com/Link-/uber_data/blob/master/analysis/Uber-Data_Analysis-0.1.ipynb