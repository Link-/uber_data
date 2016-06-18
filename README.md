# Uber Crawler / Usage Analytics

    @version alpha-0.2.2

[![Build Status](https://travis-ci.org/Link-/uber_data.svg?branch=master)](https://travis-ci.org/Link-/uber_data)
[![Coverage Status](https://coveralls.io/repos/github/Link-/uber_data/badge.svg?branch=master)](https://coveralls.io/github/Link-/uber_data?branch=master)

### Synopsis

Uber web interface crawler / scraper - Convert the trips table into a CSV file

## Scraper / Crawler

### Installation & Configuration

#### Minimum Requirements

  ```
  - PHP (5.6+)
  - XDebug is a requirement for running the unit tests
  ```

Installation is very basic, just:

1. Clone this repository into any directory:

    ```sh
    git clone https://github.com/Link-/uber_data.git
    ```

2. Install dependencies and build the `autoload` file:

    ```sh
    composer install
    ```

3. Build your `App.php` configuration file:

#### Using CLI
This repository ships with a handy command-line interface companion named `uberc` - located at `./bin/uberc`

1. Add `./bin` to your path with

    ```sh
    export PATH="$PATH:./bin"
    ```

2. Configure (this has to be done only once)

    ```sh
    uberc config
    ```

3. Analyze: Will generate the analytics files in the desired directories specified at the config step

    ```sh
    uberc analyze
    ```

#### Manually

  ```sh
  cd src/Config
  mv App.example.php App.php
  nano App.php
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

  ```sh
  php src/index.php
  ```

### Sample Output

  ```text
  2016-06-03,Logan,$7.73,uberX,Los Angeles,N.A
  2016-06-03,John,$14.45,uberX,Los Angeles,N.A
  2016-06-02,Mark,$4.70,uberX,Los Angeles,N.A
  2016-06-02,Logan,Canceled,uberX,Los Angeles,N.A
  2016-06-02,Morgan,$13.23,uberX,Los Angeles,N.A
  2016-06-01,Sleimann,$4.79,uberX,Los Angeles,N.A
  2016-06-01,George,$14.36,uberX,Los Angeles,N.A
  ```

## Jupyter Notebook

### Installation & Configuration

#### Minimum Requirements

  ```
  python3 (3.4.3)
  pip3 (1.5.4)
  jupyter (4.1.0)
  pandas (0.18.1)
  matplotlib (1.5.1)
  ```

Review the installation requirements / steps per depedency by following the reference links provided below.        

1. Install `python3`, you will need a C compiler and the Python headers and finally `pip3`:

    ```sh
    sudo apt-get install python3 build-essential python3-dev python3-setuptools python3-pip
    ```

2. Verify that python3 and pip3 have been downloaded / installed:

    ```sh
    pip3 -V
    pip 1.5.4 from /usr/lib/python3/dist-packages (python 3.4)
        
    python3 -V
    Python 3.4.3
    ```
        
3. Install `Jupyter`

    ```sh
    sudo pip3 install jupyter
    ```
        
4. Install `pandas` -- usually `numpy` gets bundled with `pandas` but just in case, install it separately (link to the installation guide below)

    ```sh
    sudo pip3 install pandas
    ```

5. Install `matplotlib`

    ```sh
    sudo apt-get install python3-matplotlib
    # Upgrade to v.1.5.1
    ```


#### Installation Guides

- pip : [installation guide](https://pip.pypa.io/en/stable/installing/)
- jupyter : [installation guide](http://jupyter.readthedocs.io/en/latest/install.html)
- pandas : [installation guide](http://pandas.pydata.org/pandas-docs/stable/install.html)
- scipy (numpy) : [installation guide](http://scipy.org/install.html)
- matplotlib : [installation guide](http://matplotlib.org/users/installing.html)


### Execution

1. Run jupyter notebook:

    ```sh
    jupyter notebook
    ```

2. Open the `Uber-Data_Analysis-0.1.ipynb` found in `uber_data/analysis/`

3. In the 3rd row, change the value of `file_location` as per the below:

    ```python
    # FROM
    file_location = r'<path to uber_data>/_sample_data/sample_data.csv'
    
    # TO
    file_location = r'<path to uber data>/data/<the file created by the crawler>.csv'
    ```

4. Press `Cell` then `Run All` from the menubar

5. Voila, you should game the output as shown in the Sample Analysis Output


### Sample Analysis Output

Uber Data Anlysis v0.1 Notebook: [Uber-Data_Analysis-0.1.ipynb](https://github.com/Link-/uber_data/blob/master/analysis/Uber-Data_Analysis-0.1.ipynb)

![image](http://i.imgur.com/cTX3zts.png)

![image](http://i.imgur.com/J0enKnm.png)

![image](http://i.imgur.com/oUhMYtP.png)

![image](http://i.imgur.com/n3qeMc3.png)