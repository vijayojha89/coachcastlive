<?php 
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\file\FileInput;
$this->title = "Dashboard";
echo $this->render('_trainer_header.php');
$gnl = new \common\components\GeneralComponent();
?>
<div class="cmsbanner">
    <div class="ddd">
      
            
    
        <div class="cmsoon">
    <h2 style=""> Coming Soon...</h2>
</div>
</div>
        </div>
    

<style>
    
    div.cmsoon { 
    background-color: white;
    display: table;
    width: 100%;
    height: 600px;
    }
    .cmsoon h2 { margin: 0;
                 font-size: 60px;
    padding: 0;
    color: #8ac42e;
    display: table-cell;
    vertical-align: middle;
    text-align: center;
    height: 500px;}
</style>
<!--div id="content" class="inner_container">
  <section class="contentsection">
    <div class="container">
      <div class="row">
        <h1 class="maintitle">Dashboard</h1>
      </div>
    </div>
    
      <div class="container" style="margin-top: 30px;margin-bottom: 50px;">
      <div class="row">
          <div class="col-xs-6">
            <div id="bar_container" style="height: 400px; margin: 0 auto"></div>
          </div>
          <div class="col-xs-6">
              <div id="pie_container" style="height: 400px; margin: 0 auto"></div>
          </div>
          
     </div>
    </div>
  </section>
</div>-->


<?php  //$this->registerJsFile('https://code.highcharts.com/highcharts.js', [yii\web\JqueryAsset::className()]); ?> 
<?php 
        /* $this->registerJs("   
    
                  Highcharts.chart('pie_container', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Yearly Earning'
        },
        credits:{
             enabled:false,
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Earning',
            colorByPoint: true,
            data: [{
                name: 'Blogs',
                y: 56.33
            }, {
                name: 'Videos',
                y: 24.03,
                
            }, {
                name: 'Classes',
                y: 10.38
            }, {
                name: 'Schedules',
                y: 4.77
            }]
            
          }]  
              });   
              

//Bar


Highcharts.chart('bar_container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Total Monthly Earning'
    },
    credits:{
             enabled:false,
        },   
    xAxis: {
        categories: [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: ' '
        }
    },
    tooltip: {
        headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
        pointFormat: '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' +
            '<td style=\"padding:0\"><b>{point.y:.1f} ($)</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Blogs',
        data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

    }, {
        name: 'Videos',
        data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]

    }, {
        name: 'Classes',
        data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2]

    }, {
        name: 'Schedules',
        data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]

    }]
});





",\yii\web\VIEW::POS_READY);  */?>