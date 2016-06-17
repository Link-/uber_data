# Uber Crawler / Usage Analytics

    @version alpha-0.2.1

[![Build Status](https://travis-ci.org/Link-/uber_data.svg?branch=master)](https://travis-ci.org/Link-/uber_data)
[![Coverage Status](https://coveralls.io/repos/github/Link-/uber_data/badge.svg?branch=master)](https://coveralls.io/github/Link-/uber_data?branch=master)

### Synopsis

Uber web interface crawler - Convert the trips table into a CSV file

### Installation & Configuration

`PHP 5.6+ is a requirement`
`XDebug is a requirement for running the unit tests`

Installation is very basic, just:

1. Clone this repository into any directory:

        git clone https://github.com/Link-/uber_data.git

2. Install dependencies and build the `autoload` file:

        $: composer install

3. Build your `App.php` configuration file:

#### Using CLI
This repository ships with a handy command-line interface companion named `uberc` - located at `./bin/uberc`

1. Add `./bin` to your path with

    ```
    export PATH="$PATH:./bin"
    ```
2. Configure (this has to be done only once)

    ```
    uberc config
    ```
3. Analyze: Will generate the analytics files in the desired directories specified at the config step

    ```
    uberc analyze
    ```

#### Manually
```
$: cd src/Config
$: mv App.example.php App.php
$: nano App.php
```

1. Add your Uber Username and Password into the `App.php` file:

    ```php
    /**
     * Uber Account Username
     */
    'username' => 'name@email.com',

    /**
     * Uber Account Password
     */
    'password' => 'mypassword',
    ```

2. Change the `data_storage_dir` and `parsed_data_dir` to where you would like to store the cached html files and the generated CSV file respectively.

3. Adjust the `timezone` to your current location.

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

Uber Data Anlysis v0.1 Notebook: [Uber-Data_Analysis-0.1.ipynb](https://github.com/Link-/uber_data/blob/master/analysis/Uber-Data_Analysis-0.1.ipynb)

![image](http://i.imgur.com/cTX3zts.png)

![image](http://i.imgur.com/J0enKnm.png)

![image](http://i.imgur.com/oUhMYtP.png)

![image](http://i.imgur.com/n3qeMc3.png)
