# Uber Crawler / Usage Analytics

    @version alpha-0.2.3
    
| Branch | Build Status | Coverage |
| ------ | ------------ | -------- |
| master | [![Build Status](https://travis-ci.org/mena-devs/slack_data_collector.svg?branch=master)](https://travis-ci.org/mena-devs/slack_data_collector) | [![Coverage Status](https://coveralls.io/repos/github/Link-/uber_data/badge.svg?branch=master)](https://coveralls.io/github/Link-/uber_data?branch=master) |
| alpha-0.2.3 | [![Build Status](https://travis-ci.org/Link-/uber_data.svg?branch=alpha-0.2.3)](https://travis-ci.org/Link-/uber_data) | [![Coverage Status](https://coveralls.io/repos/github/Link-/uber_data/badge.svg?branch=alpha-0.2.3)](https://coveralls.io/github/Link-/uber_data?branch=alpha-0.2.3) |

### Synopsis

Uber web interface crawler - Convert the trips table into a CSV file

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
    export PATH="$PATH:<project path>/bin"
    ```

2. Configure (this has to be done only once)

    ```sh
    uberc config
    ```

3. Analyze: Will generate the analytics files in the desired directories specified at the config step

    ```sh
    uberc analyze
    ```

### Sample Output

  ```text
  2016-03-20,John,$4.00,uberX,"L.A",#trip-80aa31b4-0e90-43c0-863a-29939fd9585a,"Street Address, Country","2016-06-20 17:02","Destination Street Address, United States","2016-06-20 17:11"
  2016-03-20,Christian,$5.45,uberX,"L.A",#trip-e6290590-e03a-4edb-9c21-bda18196ecf3,"Street Address, United States","2016-06-20 9:46","Destination Street Address, "L.A", United States","2016-06-20 10:13"
  2016-02-17,Ali,$4.54,uberX,"L.A",#trip-080cfb67-17f6-46bc-87d2-4cf9fc4b3793,"Street Address, United States","2016-06-17 17:31","Destination Street Address, United States","2016-06-17 17:41"
  2016-02-17,Mark,Canceled,uberX,"L.A",#trip-6e030662-9512-42bb-8cf1-230eb614a8c7,,N.A,,N.A
  2016-02-16,Logan,$4.89,uberX,"L.A",#trip-d667af1c-d220-486d-b510-f8efc07ce1f5,"Street Address, United States","2016-06-16 15:09","Destination Street Address, United States","2016-06-16 15:24"
  2016-02-16,Mohamad,$4.79,uberX,"L.A",#trip-ce16857c-f7b9-497a-86a7-382d9d6421f6,"Street Address, United States","2016-06-16 10:22","Destination Street Address, United States","2016-06-16 10:36"
  2016-01-15,George,$4.73,uberX,"L.A",#trip-7360a6d6-4f4d-4881-8424-1245264d9649,"Street Address, United States","2016-06-15 17:35","Destination Street Address, United States","2016-06-15 17:49"
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
