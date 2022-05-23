<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Arif Uddin.
 * Date: 2/10/13
 * Time: 5:05 PM
 *
 * HighRoller [http://highroller.io/] (a PHP wrapper for highcharts [http://www.highcharts.com])
 * wrapper for CodeIgniter
 */

require_once(APPPATH .'/third_party/highroller/HighRoller.php');
require_once(APPPATH .'/third_party/highroller/HighRollerSeriesData.php');
require_once(APPPATH .'/third_party/highroller/HighRollerLineChart.php');
require_once(APPPATH .'/third_party/highroller/HighRollerSplineChart.php');
require_once(APPPATH .'/third_party/highroller/HighRollerAreaChart.php');
require_once(APPPATH .'/third_party/highroller/HighRollerAreaSplineChart.php');
require_once(APPPATH .'/third_party/highroller/HighRollerBarChart.php');
require_once(APPPATH .'/third_party/highroller/HighRollerColumnChart.php');
require_once(APPPATH .'/third_party/highroller/HighRollerPieChart.php');
require_once(APPPATH .'/third_party/highroller/HighRollerScatterChart.php');

class HighCharts extends HighRoller
{
    public function __construct()
    {
        parent::__construct();
    }
}

/* End of file highcharts.php */
/* Location: ./application/libraries/highcharts.php */